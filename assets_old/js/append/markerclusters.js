/**
 * @name MarkerManager v3
 * @version 1.1
 * @copyright (c) 2007 Google Inc.
 * @author Doug Ricket, Bjorn Brala (port to v3), others,
 *
 * @fileoverview Marker manager is an interface between the map and the user,
 * designed to manage adding and removing many points when the viewport changes.
 * <br /><br />
 * <b>How it Works</b>:<br/> 
 * The MarkerManager places its markers onto a grid, similar to the map tiles.
 * When the user moves the viewport, it computes which grid cells have
 * entered or left the viewport, and shows or hides all the markers in those
 * cells.
 * (If the users scrolls the viewport beyond the markers that are loaded,
 * no markers will be visible until the <code>EVENT_moveend</code> 
 * triggers an update.)
 * In practical consequences, this allows 10,000 markers to be distributed over
 * a large area, and as long as only 100-200 are visible in any given viewport,
 * the user will see good performance corresponding to the 100 visible markers,
 * rather than poor performance corresponding to the total 10,000 markers.
 * Note that some code is optimized for speed over space,
 * with the goal of accommodating thousands of markers.
 */

/*
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License. 
 */

/**
 * @name MarkerManagerOptions
 * @class This class represents optional arguments to the {@link MarkerManager}
 *     constructor.
 * @property {Number} maxZoom Sets the maximum zoom level monitored by a
 *     marker manager. If not given, the manager assumes the maximum map zoom
 *     level. This value is also used when markers are added to the manager
 *     without the optional {@link maxZoom} parameter.
 * @property {Number} borderPadding Specifies, in pixels, the extra padding
 *     outside the map's current viewport monitored by a manager. Markers that
 *     fall within this padding are added to the map, even if they are not fully
 *     visible.
 * @property {Boolean} trackMarkers=false Indicates whether or not a marker
 *     manager should track markers' movements. If you wish to move managed
 *     markers using the {@link setPoint}/{@link setLatLng} methods, 
 *     this option should be set to {@link true}.
 */

/**
 * Creates a new MarkerManager that will show/hide markers on a map.
 *
 * Events:
 * @event changed (Parameters: shown bounds, shown markers) Notify listeners when the state of what is displayed changes.
 * @event loaded MarkerManager has succesfully been initialized.
 *
 * @constructor
 * @param {Map} map The map to manage.
 * @param {Object} opt_opts A container for optional arguments:
 *   {Number} maxZoom The maximum zoom level for which to create tiles.
 *   {Number} borderPadding The width in pixels beyond the map border,
 *                   where markers should be display.
 *   {Boolean} trackMarkers Whether or not this manager should track marker
 *                   movements.
 */
function MarkerManager(map, opt_opts) {
  var me = this;
  me.map_ = map;
  me.mapZoom_ = map.getZoom();
  
  me.projectionHelper_ = new ProjectionHelperOverlay(map);
  google.maps.event.addListener(me.projectionHelper_, 'ready', function () {
    me.projection_ = this.getProjection();
    me.initialize(map, opt_opts);
  });
}

  
MarkerManager.prototype.initialize = function (map, opt_opts) {
  var me = this;
  
  opt_opts = opt_opts || {};
  me.tileSize_ = MarkerManager.DEFAULT_TILE_SIZE_;

  var mapTypes = map.mapTypes;

  // Find max zoom level
  var mapMaxZoom = 1;
  for (var sType in mapTypes ) {
    if (mapTypes.hasOwnProperty(sType) &&
        mapTypes.get(sType) && mapTypes.get(sType).maxZoom === 'number') {
      var mapTypeMaxZoom = map.mapTypes.get(sType).maxZoom;
      if (mapTypeMaxZoom > mapMaxZoom) {
        mapMaxZoom = mapTypeMaxZoom;
      }
    }
  }
  
  me.maxZoom_  = opt_opts.maxZoom || 19;

  me.trackMarkers_ = opt_opts.trackMarkers;
  me.show_ = opt_opts.show || true;

  var padding;
  if (typeof opt_opts.borderPadding === 'number') {
    padding = opt_opts.borderPadding;
  } else {
    padding = MarkerManager.DEFAULT_BORDER_PADDING_;
  }
  // The padding in pixels beyond the viewport, where we will pre-load markers.
  me.swPadding_ = new google.maps.Size(-padding, padding);
  me.nePadding_ = new google.maps.Size(padding, -padding);
  me.borderPadding_ = padding;

  me.gridWidth_ = {};

  me.grid_ = {};
  me.grid_[me.maxZoom_] = {};
  me.numMarkers_ = {};
  me.numMarkers_[me.maxZoom_] = 0;


  google.maps.event.addListener(map, 'dragend', function () {
    me.onMapMoveEnd_();
  });
  
  google.maps.event.addListener(map, 'idle', function () {
    me.onMapMoveEnd_();
  });
  
  google.maps.event.addListener(map, 'zoom_changed', function () {
    me.onMapMoveEnd_();
  });



  /**
   * This closure provide easy access to the map.
   * They are used as callbacks, not as methods.
   * @param GMarker marker Marker to be removed from the map
   * @private
   */
  me.removeOverlay_ = function (marker) {
    marker.setMap(null);
    me.shownMarkers_--;
  };

  /**
   * This closure provide easy access to the map.
   * They are used as callbacks, not as methods.
   * @param GMarker marker Marker to be added to the map
   * @private
   */
  me.addOverlay_ = function (marker) {
    if (me.show_) {
      marker.setMap(me.map_);
      me.shownMarkers_++;
    }
  };

  me.resetManager_();
  me.shownMarkers_ = 0;

  me.shownBounds_ = me.getMapGridBounds_();
  
  google.maps.event.trigger(me, 'loaded');
  
};

/**
 *  Default tile size used for deviding the map into a grid.
 */
MarkerManager.DEFAULT_TILE_SIZE_ = 1024;

/*
 *  How much extra space to show around the map border so
 *  dragging doesn't result in an empty place.
 */
MarkerManager.DEFAULT_BORDER_PADDING_ = 100;

/**
 *  Default tilesize of single tile world.
 */
MarkerManager.MERCATOR_ZOOM_LEVEL_ZERO_RANGE = 256;


/**
 * Initializes MarkerManager arrays for all zoom levels
 * Called by constructor and by clearAllMarkers
 */
MarkerManager.prototype.resetManager_ = function () {
  var mapWidth = MarkerManager.MERCATOR_ZOOM_LEVEL_ZERO_RANGE;
  for (var zoom = 0; zoom <= this.maxZoom_; ++zoom) {
    this.grid_[zoom] = {};
    this.numMarkers_[zoom] = 0;
    this.gridWidth_[zoom] = Math.ceil(mapWidth / this.tileSize_);
    mapWidth <<= 1;
  }

};

/**
 * Removes all markers in the manager, and
 * removes any visible markers from the map.
 */
MarkerManager.prototype.clearMarkers = function () {
  this.processAll_(this.shownBounds_, this.removeOverlay_);
  this.resetManager_();
};


/**
 * Gets the tile coordinate for a given latlng point.
 *
 * @param {LatLng} latlng The geographical point.
 * @param {Number} zoom The zoom level.
 * @param {google.maps.Size} padding The padding used to shift the pixel coordinate.
 *               Used for expanding a bounds to include an extra padding
 *               of pixels surrounding the bounds.
 * @return {GPoint} The point in tile coordinates.
 *
 */
MarkerManager.prototype.getTilePoint_ = function (latlng, zoom, padding) {

  var pixelPoint = this.projectionHelper_.LatLngToPixel(latlng, zoom);

  var point = new google.maps.Point(
    Math.floor((pixelPoint.x + padding.width) / this.tileSize_),
    Math.floor((pixelPoint.y + padding.height) / this.tileSize_)
  );

  return point;
};


/**
 * Finds the appropriate place to add the marker to the grid.
 * Optimized for speed; does not actually add the marker to the map.
 * Designed for batch-processing thousands of markers.
 *
 * @param {Marker} marker The marker to add.
 * @param {Number} minZoom The minimum zoom for displaying the marker.
 * @param {Number} maxZoom The maximum zoom for displaying the marker.
 */
MarkerManager.prototype.addMarkerBatch_ = function (marker, minZoom, maxZoom) {
  var me = this;

  var mPoint = marker.getPosition();
  marker.MarkerManager_minZoom = minZoom;
  
  
  // Tracking markers is expensive, so we do this only if the
  // user explicitly requested it when creating marker manager.
  if (this.trackMarkers_) {
    google.maps.event.addListener(marker, 'changed', function (a, b, c) {
      me.onMarkerMoved_(a, b, c);
    });
  }

  var gridPoint = this.getTilePoint_(mPoint, maxZoom, new google.maps.Size(0, 0, 0, 0));

  for (var zoom = maxZoom; zoom >= minZoom; zoom--) {
    var cell = this.getGridCellCreate_(gridPoint.x, gridPoint.y, zoom);
    cell.push(marker);

    gridPoint.x = gridPoint.x >> 1;
    gridPoint.y = gridPoint.y >> 1;
  }
};


/**
 * Returns whether or not the given point is visible in the shown bounds. This
 * is a helper method that takes care of the corner case, when shownBounds have
 * negative minX value.
 *
 * @param {Point} point a point on a grid.
 * @return {Boolean} Whether or not the given point is visible in the currently
 * shown bounds.
 */
MarkerManager.prototype.isGridPointVisible_ = function (point) {
  var vertical = this.shownBounds_.minY <= point.y &&
      point.y <= this.shownBounds_.maxY;
  var minX = this.shownBounds_.minX;
  var horizontal = minX <= point.x && point.x <= this.shownBounds_.maxX;
  if (!horizontal && minX < 0) {
    // Shifts the negative part of the rectangle. As point.x is always less
    // than grid width, only test shifted minX .. 0 part of the shown bounds.
    var width = this.gridWidth_[this.shownBounds_.z];
    horizontal = minX + width <= point.x && point.x <= width - 1;
  }
  return vertical && horizontal;
};


/**
 * Reacts to a notification from a marker that it has moved to a new location.
 * It scans the grid all all zoom levels and moves the marker from the old grid
 * location to a new grid location.
 *
 * @param {Marker} marker The marker that moved.
 * @param {LatLng} oldPoint The old position of the marker.
 * @param {LatLng} newPoint The new position of the marker.
 */
MarkerManager.prototype.onMarkerMoved_ = function (marker, oldPoint, newPoint) {
  // NOTE: We do not know the minimum or maximum zoom the marker was
  // added at, so we start at the absolute maximum. Whenever we successfully
  // remove a marker at a given zoom, we add it at the new grid coordinates.
  var zoom = this.maxZoom_;
  var changed = false;
  var oldGrid = this.getTilePoint_(oldPoint, zoom, new google.maps.Size(0, 0, 0, 0));
  var newGrid = this.getTilePoint_(newPoint, zoom, new google.maps.Size(0, 0, 0, 0));
  while (zoom >= 0 && (oldGrid.x !== newGrid.x || oldGrid.y !== newGrid.y)) {
    var cell = this.getGridCellNoCreate_(oldGrid.x, oldGrid.y, zoom);
    if (cell) {
      if (this.removeFromArray_(cell, marker)) {
        this.getGridCellCreate_(newGrid.x, newGrid.y, zoom).push(marker);
      }
    }
    // For the current zoom we also need to update the map. Markers that no
    // longer are visible are removed from the map. Markers that moved into
    // the shown bounds are added to the map. This also lets us keep the count
    // of visible markers up to date.
    if (zoom === this.mapZoom_) {
      if (this.isGridPointVisible_(oldGrid)) {
        if (!this.isGridPointVisible_(newGrid)) {
          this.removeOverlay_(marker);
          changed = true;
        }
      } else {
        if (this.isGridPointVisible_(newGrid)) {
          this.addOverlay_(marker);
          changed = true;
        }
      }
    }
    oldGrid.x = oldGrid.x >> 1;
    oldGrid.y = oldGrid.y >> 1;
    newGrid.x = newGrid.x >> 1;
    newGrid.y = newGrid.y >> 1;
    --zoom;
  }
  if (changed) {
    this.notifyListeners_();
  }
};


/**
 * Removes marker from the manager and from the map
 * (if it's currently visible).
 * @param {GMarker} marker The marker to delete.
 */
MarkerManager.prototype.removeMarker = function (marker) {
  var zoom = this.maxZoom_;
  var changed = false;
  var point = marker.getPosition();
  var grid = this.getTilePoint_(point, zoom, new google.maps.Size(0, 0, 0, 0));
  while (zoom >= 0) {
    var cell = this.getGridCellNoCreate_(grid.x, grid.y, zoom);

    if (cell) {
      this.removeFromArray_(cell, marker);
    }
    // For the current zoom we also need to update the map. Markers that no
    // longer are visible are removed from the map. This also lets us keep the count
    // of visible markers up to date.
    if (zoom === this.mapZoom_) {
      if (this.isGridPointVisible_(grid)) {
        this.removeOverlay_(marker);
        changed = true;
      }
    }
    grid.x = grid.x >> 1;
    grid.y = grid.y >> 1;
    --zoom;
  }
  if (changed) {
    this.notifyListeners_();
  }
  this.numMarkers_[marker.MarkerManager_minZoom]--;
};


/**
 * Add many markers at once.
 * Does not actually update the map, just the internal grid.
 *
 * @param {Array of Marker} markers The markers to add.
 * @param {Number} minZoom The minimum zoom level to display the markers.
 * @param {Number} opt_maxZoom The maximum zoom level to display the markers.
 */
MarkerManager.prototype.addMarkers = function (markers, minZoom, opt_maxZoom) {
  var maxZoom = this.getOptMaxZoom_(opt_maxZoom);
  for (var i = markers.length - 1; i >= 0; i--) {
    this.addMarkerBatch_(markers[i], minZoom, maxZoom);
  }

  this.numMarkers_[minZoom] += markers.length;
};


/**
 * Returns the value of the optional maximum zoom. This method is defined so
 * that we have just one place where optional maximum zoom is calculated.
 *
 * @param {Number} opt_maxZoom The optinal maximum zoom.
 * @return The maximum zoom.
 */
MarkerManager.prototype.getOptMaxZoom_ = function (opt_maxZoom) {
  return opt_maxZoom || this.maxZoom_;
};


/**
 * Calculates the total number of markers potentially visible at a given
 * zoom level.
 *
 * @param {Number} zoom The zoom level to check.
 */
MarkerManager.prototype.getMarkerCount = function (zoom) {
  var total = 0;
  for (var z = 0; z <= zoom; z++) {
    total += this.numMarkers_[z];
  }
  return total;
};

/** 
 * Returns a marker given latitude, longitude and zoom. If the marker does not 
 * exist, the method will return a new marker. If a new marker is created, 
 * it will NOT be added to the manager. 
 * 
 * @param {Number} lat - the latitude of a marker. 
 * @param {Number} lng - the longitude of a marker. 
 * @param {Number} zoom - the zoom level 
 * @return {GMarker} marker - the marker found at lat and lng 
 */ 
MarkerManager.prototype.getMarker = function (lat, lng, zoom) {
  var mPoint = new google.maps.LatLng(lat, lng); 
  var gridPoint = this.getTilePoint_(mPoint, zoom, new google.maps.Size(0, 0, 0, 0));

  var marker = new google.maps.Marker({position: mPoint}); 
    
  var cellArray = this.getGridCellNoCreate_(gridPoint.x, gridPoint.y, zoom);
  if (cellArray !== undefined) {
    for (var i = 0; i < cellArray.length; i++) 
    { 
      if (lat === cellArray[i].getPosition().lat() && lng === cellArray[i].getPosition().lng()) {
        marker = cellArray[i]; 
      } 
    } 
  } 
  return marker; 
}; 

/**
 * Add a single marker to the map.
 *
 * @param {Marker} marker The marker to add.
 * @param {Number} minZoom The minimum zoom level to display the marker.
 * @param {Number} opt_maxZoom The maximum zoom level to display the marker.
 */
MarkerManager.prototype.addMarker = function (marker, minZoom, opt_maxZoom) {
  var maxZoom = this.getOptMaxZoom_(opt_maxZoom);
  this.addMarkerBatch_(marker, minZoom, maxZoom);
  var gridPoint = this.getTilePoint_(marker.getPosition(), this.mapZoom_, new google.maps.Size(0, 0, 0, 0));
  if (this.isGridPointVisible_(gridPoint) &&
      minZoom <= this.shownBounds_.z &&
      this.shownBounds_.z <= maxZoom) {
    this.addOverlay_(marker);
    this.notifyListeners_();
  }
  this.numMarkers_[minZoom]++;
};


/**
 * Helper class to create a bounds of INT ranges.
 * @param bounds Array.<Object.<string, number>> Bounds object.
 * @constructor
 */
function GridBounds(bounds) {
  // [sw, ne]
  
  this.minX = Math.min(bounds[0].x, bounds[1].x);
  this.maxX = Math.max(bounds[0].x, bounds[1].x);
  this.minY = Math.min(bounds[0].y, bounds[1].y);
  this.maxY = Math.max(bounds[0].y, bounds[1].y);
      
}

/**
 * Returns true if this bounds equal the given bounds.
 * @param {GridBounds} gridBounds GridBounds The bounds to test.
 * @return {Boolean} This Bounds equals the given GridBounds.
 */
GridBounds.prototype.equals = function (gridBounds) {
  if (this.maxX === gridBounds.maxX && this.maxY === gridBounds.maxY && this.minX === gridBounds.minX && this.minY === gridBounds.minY) {
    return true;
  } else {
    return false;
  }  
};

/**
 * Returns true if this bounds (inclusively) contains the given point.
 * @param {Point} point  The point to test.
 * @return {Boolean} This Bounds contains the given Point.
 */
GridBounds.prototype.containsPoint = function (point) {
  var outer = this;
  return (outer.minX <= point.x && outer.maxX >= point.x && outer.minY <= point.y && outer.maxY >= point.y);
};

/**
 * Get a cell in the grid, creating it first if necessary.
 *
 * Optimization candidate
 *
 * @param {Number} x The x coordinate of the cell.
 * @param {Number} y The y coordinate of the cell.
 * @param {Number} z The z coordinate of the cell.
 * @return {Array} The cell in the array.
 */
MarkerManager.prototype.getGridCellCreate_ = function (x, y, z) {
  var grid = this.grid_[z];
  if (x < 0) {
    x += this.gridWidth_[z];
  }
  var gridCol = grid[x];
  if (!gridCol) {
    gridCol = grid[x] = [];
    return (gridCol[y] = []);
  }
  var gridCell = gridCol[y];
  if (!gridCell) {
    return (gridCol[y] = []);
  }
  return gridCell;
};


/**
 * Get a cell in the grid, returning undefined if it does not exist.
 *
 * NOTE: Optimized for speed -- otherwise could combine with getGridCellCreate_.
 *
 * @param {Number} x The x coordinate of the cell.
 * @param {Number} y The y coordinate of the cell.
 * @param {Number} z The z coordinate of the cell.
 * @return {Array} The cell in the array.
 */
MarkerManager.prototype.getGridCellNoCreate_ = function (x, y, z) {
  var grid = this.grid_[z];
  
  if (x < 0) {
    x += this.gridWidth_[z];
  }
  var gridCol = grid[x];
  return gridCol ? gridCol[y] : undefined;
};


/**
 * Turns at geographical bounds into a grid-space bounds.
 *
 * @param {LatLngBounds} bounds The geographical bounds.
 * @param {Number} zoom The zoom level of the bounds.
 * @param {google.maps.Size} swPadding The padding in pixels to extend beyond the
 * given bounds.
 * @param {google.maps.Size} nePadding The padding in pixels to extend beyond the
 * given bounds.
 * @return {GridBounds} The bounds in grid space.
 */
MarkerManager.prototype.getGridBounds_ = function (bounds, zoom, swPadding, nePadding) {
  zoom = Math.min(zoom, this.maxZoom_);

  var bl = bounds.getSouthWest();
  var tr = bounds.getNorthEast();
  var sw = this.getTilePoint_(bl, zoom, swPadding);

  var ne = this.getTilePoint_(tr, zoom, nePadding);
  var gw = this.gridWidth_[zoom];

  // Crossing the prime meridian requires correction of bounds.
  if (tr.lng() < bl.lng() || ne.x < sw.x) {
    sw.x -= gw;
  }
  if (ne.x - sw.x  + 1 >= gw) {
    // Computed grid bounds are larger than the world; truncate.
    sw.x = 0;
    ne.x = gw - 1;
  }

  var gridBounds = new GridBounds([sw, ne]);
  gridBounds.z = zoom;

  return gridBounds;
};


/**
 * Gets the grid-space bounds for the current map viewport.
 *
 * @return {Bounds} The bounds in grid space.
 */
MarkerManager.prototype.getMapGridBounds_ = function () {
  return this.getGridBounds_(this.map_.getBounds(), this.mapZoom_, this.swPadding_, this.nePadding_);
};


/**
 * Event listener for map:movend.
 * NOTE: Use a timeout so that the user is not blocked
 * from moving the map.
 *
 * Removed this because a a lack of a scopy override/callback function on events. 
 */
MarkerManager.prototype.onMapMoveEnd_ = function () {
  this.objectSetTimeout_(this, this.updateMarkers_, 0);
};


/**
 * Call a function or evaluate an expression after a specified number of
 * milliseconds.
 *
 * Equivalent to the standard window.setTimeout function, but the given
 * function executes as a method of this instance. So the function passed to
 * objectSetTimeout can contain references to this.
 *    objectSetTimeout(this, function () { alert(this.x) }, 1000);
 *
 * @param {Object} object  The target object.
 * @param {Function} command  The command to run.
 * @param {Number} milliseconds  The delay.
 * @return {Boolean}  Success.
 */
MarkerManager.prototype.objectSetTimeout_ = function (object, command, milliseconds) {
  return window.setTimeout(function () {
    command.call(object);
  }, milliseconds);
};


/**
 * Is this layer visible?
 *
 * Returns visibility setting
 *
 * @return {Boolean} Visible
 */
MarkerManager.prototype.visible = function () {
  return this.show_ ? true : false;
};


/**
 * Returns true if the manager is hidden.
 * Otherwise returns false.
 * @return {Boolean} Hidden
 */
MarkerManager.prototype.isHidden = function () {
  return !this.show_;
};


/**
 * Shows the manager if it's currently hidden.
 */
MarkerManager.prototype.show = function () {
  this.show_ = true;
  this.refresh();
};


/**
 * Hides the manager if it's currently visible
 */
MarkerManager.prototype.hide = function () {
  this.show_ = false;
  this.refresh();
};


/**
 * Toggles the visibility of the manager.
 */
MarkerManager.prototype.toggle = function () {
  this.show_ = !this.show_;
  this.refresh();
};


/**
 * Refresh forces the marker-manager into a good state.
 * <ol>
 *   <li>If never before initialized, shows all the markers.</li>
 *   <li>If previously initialized, removes and re-adds all markers.</li>
 * </ol>
 */
MarkerManager.prototype.refresh = function () {
  if (this.shownMarkers_ > 0) {
    this.processAll_(this.shownBounds_, this.removeOverlay_);
  }
  // An extra check on this.show_ to increase performance (no need to processAll_)
  if (this.show_) {
    this.processAll_(this.shownBounds_, this.addOverlay_);
  }
  this.notifyListeners_();
};


/**
 * After the viewport may have changed, add or remove markers as needed.
 */
MarkerManager.prototype.updateMarkers_ = function () {
  this.mapZoom_ = this.map_.getZoom();
  var newBounds = this.getMapGridBounds_();
    
  // If the move does not include new grid sections,
  // we have no work to do:
  if (newBounds.equals(this.shownBounds_) && newBounds.z === this.shownBounds_.z) {
    return;
  }

  if (newBounds.z !== this.shownBounds_.z) {
    this.processAll_(this.shownBounds_, this.removeOverlay_);
    if (this.show_) { // performance
      this.processAll_(newBounds, this.addOverlay_);
    }
  } else {
    // Remove markers:
    this.rectangleDiff_(this.shownBounds_, newBounds, this.removeCellMarkers_);

    // Add markers:
    if (this.show_) { // performance
      this.rectangleDiff_(newBounds, this.shownBounds_, this.addCellMarkers_);
    }
  }
  this.shownBounds_ = newBounds;

  this.notifyListeners_();
};


/**
 * Notify listeners when the state of what is displayed changes.
 */
MarkerManager.prototype.notifyListeners_ = function () {
  google.maps.event.trigger(this, 'changed', this.shownBounds_, this.shownMarkers_);
};


/**
 * Process all markers in the bounds provided, using a callback.
 *
 * @param {Bounds} bounds The bounds in grid space.
 * @param {Function} callback The function to call for each marker.
 */
MarkerManager.prototype.processAll_ = function (bounds, callback) {
  for (var x = bounds.minX; x <= bounds.maxX; x++) {
    for (var y = bounds.minY; y <= bounds.maxY; y++) {
      this.processCellMarkers_(x, y,  bounds.z, callback);
    }
  }
};


/**
 * Process all markers in the grid cell, using a callback.
 *
 * @param {Number} x The x coordinate of the cell.
 * @param {Number} y The y coordinate of the cell.
 * @param {Number} z The z coordinate of the cell.
 * @param {Function} callback The function to call for each marker.
 */
MarkerManager.prototype.processCellMarkers_ = function (x, y, z, callback) {
  var cell = this.getGridCellNoCreate_(x, y, z);
  if (cell) {
    for (var i = cell.length - 1; i >= 0; i--) {
      callback(cell[i]);
    }
  }
};


/**
 * Remove all markers in a grid cell.
 *
 * @param {Number} x The x coordinate of the cell.
 * @param {Number} y The y coordinate of the cell.
 * @param {Number} z The z coordinate of the cell.
 */
MarkerManager.prototype.removeCellMarkers_ = function (x, y, z) {
  this.processCellMarkers_(x, y, z, this.removeOverlay_);
};


/**
 * Add all markers in a grid cell.
 *
 * @param {Number} x The x coordinate of the cell.
 * @param {Number} y The y coordinate of the cell.
 * @param {Number} z The z coordinate of the cell.
 */
MarkerManager.prototype.addCellMarkers_ = function (x, y, z) {
  this.processCellMarkers_(x, y, z, this.addOverlay_);
};


/**
 * Use the rectangleDiffCoords_ function to process all grid cells
 * that are in bounds1 but not bounds2, using a callback, and using
 * the current MarkerManager object as the instance.
 *
 * Pass the z parameter to the callback in addition to x and y.
 *
 * @param {Bounds} bounds1 The bounds of all points we may process.
 * @param {Bounds} bounds2 The bounds of points to exclude.
 * @param {Function} callback The callback function to call
 *                   for each grid coordinate (x, y, z).
 */
MarkerManager.prototype.rectangleDiff_ = function (bounds1, bounds2, callback) {
  var me = this;
  me.rectangleDiffCoords_(bounds1, bounds2, function (x, y) {
    callback.apply(me, [x, y, bounds1.z]);
  });
};


/**
 * Calls the function for all points in bounds1, not in bounds2
 *
 * @param {Bounds} bounds1 The bounds of all points we may process.
 * @param {Bounds} bounds2 The bounds of points to exclude.
 * @param {Function} callback The callback function to call
 *                   for each grid coordinate.
 */
MarkerManager.prototype.rectangleDiffCoords_ = function (bounds1, bounds2, callback) {
  var minX1 = bounds1.minX;
  var minY1 = bounds1.minY;
  var maxX1 = bounds1.maxX;
  var maxY1 = bounds1.maxY;
  var minX2 = bounds2.minX;
  var minY2 = bounds2.minY;
  var maxX2 = bounds2.maxX;
  var maxY2 = bounds2.maxY;

  var x, y;
  for (x = minX1; x <= maxX1; x++) {  // All x in R1
    // All above:
    for (y = minY1; y <= maxY1 && y < minY2; y++) {  // y in R1 above R2
      callback(x, y);
    }
    // All below:
    for (y = Math.max(maxY2 + 1, minY1);  // y in R1 below R2
         y <= maxY1; y++) {
      callback(x, y);
    }
  }

  for (y = Math.max(minY1, minY2);
       y <= Math.min(maxY1, maxY2); y++) {  // All y in R2 and in R1
    // Strictly left:
    for (x = Math.min(maxX1 + 1, minX2) - 1;
         x >= minX1; x--) {  // x in R1 left of R2
      callback(x, y);
    }
    // Strictly right:
    for (x = Math.max(minX1, maxX2 + 1);  // x in R1 right of R2
         x <= maxX1; x++) {
      callback(x, y);
    }
  }
};


/**
 * Removes value from array. O(N).
 *
 * @param {Array} array  The array to modify.
 * @param {any} value  The value to remove.
 * @param {Boolean} opt_notype  Flag to disable type checking in equality.
 * @return {Number}  The number of instances of value that were removed.
 */
MarkerManager.prototype.removeFromArray_ = function (array, value, opt_notype) {
  var shift = 0;
  for (var i = 0; i < array.length; ++i) {
    if (array[i] === value || (opt_notype && array[i] === value)) {
      array.splice(i--, 1);
      shift++;
    }
  }
  return shift;
};







/**
*   Projection overlay helper. Helps in calculating
*   that markers get into the right grid.
*   @constructor
*   @param {Map} map The map to manage.
**/
function ProjectionHelperOverlay(map) {
  
  this.setMap(map);

  var TILEFACTOR = 8;
  var TILESIDE = 1 << TILEFACTOR;
  var RADIUS = 7;

  this._map = map;
  this._zoom = -1;
  this._X0 =
  this._Y0 =
  this._X1 =
  this._Y1 = -1;

  
}
ProjectionHelperOverlay.prototype = new google.maps.OverlayView();

/**
 *  Helper function to convert Lng to X
 *  @private
 *  @param {float} lng
 **/
ProjectionHelperOverlay.prototype.LngToX_ = function (lng) {
  return (1 + lng / 180);
};

/**
 *  Helper function to convert Lat to Y
 *  @private
 *  @param {float} lat
 **/
ProjectionHelperOverlay.prototype.LatToY_ = function (lat) {
  var sinofphi = Math.sin(lat * Math.PI / 180);
  return (1 - 0.5 / Math.PI * Math.log((1 + sinofphi) / (1 - sinofphi)));
};

/**
*   Old school LatLngToPixel
*   @param {LatLng} latlng google.maps.LatLng object
*   @param {Number} zoom Zoom level
*   @return {position} {x: pixelPositionX, y: pixelPositionY}
**/
ProjectionHelperOverlay.prototype.LatLngToPixel = function (latlng, zoom) {
  var map = this._map;
  var div = this.getProjection().fromLatLngToDivPixel(latlng);
  var abs = {x: ~~(0.5 + this.LngToX_(latlng.lng()) * (2 << (zoom + 6))), y: ~~(0.5 + this.LatToY_(latlng.lat()) * (2 << (zoom + 6)))};
  return abs;
};


/**
 * Draw function only triggers a ready event for
 * MarkerManager to know projection can proceed to
 * initialize.
 */
ProjectionHelperOverlay.prototype.draw = function () {
  if (!this.ready) {
    this.ready = true;
    google.maps.event.trigger(this, 'ready');
  }
};





function d(a){return function(b){this[a]=b}}function f(a){return function(){return this[a]}}var k;
function l(a,b,c){this.extend(l,google.maps.OverlayView);this.b=a;this.a=[];this.f=[];this.da=[53,56,66,78,90];this.j=[];this.A=!1;c=c||{};this.g=c.gridSize||60;this.l=c.minimumClusterSize||2;this.K=c.maxZoom||null;this.j=c.styles||[];this.Y=c.imagePath||this.R;this.X=c.imageExtension||this.Q;this.P=!0;void 0!=c.zoomOnClick&&(this.P=c.zoomOnClick);this.r=!1;void 0!=c.averageCenter&&(this.r=c.averageCenter);m(this);this.setMap(a);this.L=this.b.getZoom();var e=this;google.maps.event.addListener(this.b,
"zoom_changed",function(){var a=e.b.getZoom(),b=e.b.minZoom||0,c=Math.min(e.b.maxZoom||100,e.b.mapTypes[e.b.getMapTypeId()].maxZoom),a=Math.min(Math.max(a,b),c);e.L!=a&&(e.L=a,e.m())});google.maps.event.addListener(this.b,"idle",function(){e.i()});b&&(b.length||Object.keys(b).length)&&this.C(b,!1)}k=l.prototype;k.R="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/markerclusterer/images/m";k.Q="png";
k.extend=function(a,b){return function(a){for(var b in a.prototype)this.prototype[b]=a.prototype[b];return this}.apply(a,[b])};k.onAdd=function(){this.A||(this.A=!0,p(this))};k.draw=function(){};function m(a){if(!a.j.length)for(var b=0,c;c=a.da[b];b++)a.j.push({url:a.Y+(b+1)+"."+a.X,height:c,width:c})}k.T=function(){for(var a=this.o(),b=new google.maps.LatLngBounds,c=0,e;e=a[c];c++)b.extend(e.getPosition());this.b.fitBounds(b)};k.w=f("j");k.o=f("a");k.W=function(){return this.a.length};k.ca=d("K");
k.J=f("K");k.G=function(a,b){for(var c=0,e=a.length,g=e;0!==g;)g=parseInt(g/10,10),c++;c=Math.min(c,b);return{text:e,index:c}};k.aa=d("G");k.H=f("G");k.C=function(a,b){if(a.length)for(var c=0,e;e=a[c];c++)s(this,e);else if(Object.keys(a).length)for(e in a)s(this,a[e]);b||this.i()};function s(a,b){b.s=!1;b.draggable&&google.maps.event.addListener(b,"dragend",function(){b.s=!1;a.M()});a.a.push(b)}k.q=function(a,b){s(this,a);b||this.i()};
function t(a,b){var c=-1;if(a.a.indexOf)c=a.a.indexOf(b);else for(var e=0,g;g=a.a[e];e++)if(g==b){c=e;break}if(-1==c)return!1;b.setMap(null);a.a.splice(c,1);return!0}k.Z=function(a,b){var c=t(this,a);return!b&&c?(this.m(),this.i(),!0):!1};k.$=function(a,b){for(var c=!1,e=0,g;g=a[e];e++)g=t(this,g),c=c||g;if(!b&&c)return this.m(),this.i(),!0};k.V=function(){return this.f.length};k.getMap=f("b");k.setMap=d("b");k.I=f("g");k.ba=d("g");
k.v=function(a){var b=this.getProjection(),c=new google.maps.LatLng(a.getNorthEast().lat(),a.getNorthEast().lng()),e=new google.maps.LatLng(a.getSouthWest().lat(),a.getSouthWest().lng()),c=b.fromLatLngToDivPixel(c);c.x+=this.g;c.y-=this.g;e=b.fromLatLngToDivPixel(e);e.x-=this.g;e.y+=this.g;c=b.fromDivPixelToLatLng(c);b=b.fromDivPixelToLatLng(e);a.extend(c);a.extend(b);return a};k.S=function(){this.m(!0);this.a=[]};
k.m=function(a){for(var b=0,c;c=this.f[b];b++)c.remove();for(b=0;c=this.a[b];b++)c.s=!1,a&&c.setMap(null);this.f=[]};k.M=function(){var a=this.f.slice();this.f.length=0;this.m();this.i();window.setTimeout(function(){for(var b=0,c;c=a[b];b++)c.remove()},0)};k.i=function(){p(this)};
function p(a){if(a.A)for(var b=new google.maps.LatLngBounds(a.b.getBounds().getSouthWest(),a.b.getBounds().getNorthEast()),b=a.v(b),c=0,e;e=a.a[c];c++)if(!e.s&&b.contains(e.getPosition())){for(var g=a,u=4E4,q=null,x=0,n=void 0;n=g.f[x];x++){var h=n.getCenter();if(h){var r=e.getPosition();if(h&&r)var y=(r.lat()-h.lat())*Math.PI/180,z=(r.lng()-h.lng())*Math.PI/180,h=Math.sin(y/2)*Math.sin(y/2)+Math.cos(h.lat()*Math.PI/180)*Math.cos(r.lat()*Math.PI/180)*Math.sin(z/2)*Math.sin(z/2),h=12742*Math.atan2(Math.sqrt(h),
Math.sqrt(1-h));else h=0;h<u&&(u=h,q=n)}}q&&q.F.contains(e.getPosition())?q.q(e):(n=new v(g),n.q(e),g.f.push(n))}}function v(a){this.k=a;this.b=a.getMap();this.g=a.I();this.l=a.l;this.r=a.r;this.d=null;this.a=[];this.F=null;this.n=new w(this,a.w())}k=v.prototype;
k.q=function(a){var b;a:if(this.a.indexOf)b=-1!=this.a.indexOf(a);else{b=0;for(var c;c=this.a[b];b++)if(c==a){b=!0;break a}b=!1}if(b)return!1;this.d?this.r&&(c=this.a.length+1,b=(this.d.lat()*(c-1)+a.getPosition().lat())/c,c=(this.d.lng()*(c-1)+a.getPosition().lng())/c,this.d=new google.maps.LatLng(b,c),A(this)):(this.d=a.getPosition(),A(this));a.s=!0;this.a.push(a);b=this.a.length;b<this.l&&a.getMap()!=this.b&&a.setMap(this.b);if(b==this.l)for(c=0;c<b;c++)this.a[c].setMap(null);b>=this.l&&a.setMap(null);
a=this.b.getZoom();if((b=this.k.J())&&a>b)for(a=0;b=this.a[a];a++)b.setMap(this.b);else this.a.length<this.l?B(this.n):(b=this.k.H()(this.a,this.k.w().length),this.n.setCenter(this.d),a=this.n,a.B=b,a.c&&(a.c.innerHTML=b.text),b=Math.max(0,a.B.index-1),b=Math.min(a.j.length-1,b),b=a.j[b],a.ea=b.url,a.h=b.height,a.p=b.width,a.N=b.textColor,a.e=b.anchor,a.O=b.textSize,a.D=b.backgroundPosition,this.n.show());return!0};
k.getBounds=function(){for(var a=new google.maps.LatLngBounds(this.d,this.d),b=this.o(),c=0,e;e=b[c];c++)a.extend(e.getPosition());return a};k.remove=function(){this.n.remove();this.a.length=0;delete this.a};k.U=function(){return this.a.length};k.o=f("a");k.getCenter=f("d");function A(a){var b=new google.maps.LatLngBounds(a.d,a.d);a.F=a.k.v(b)}k.getMap=f("b");
function w(a,b){a.k.extend(w,google.maps.OverlayView);this.j=b;this.u=a;this.d=null;this.b=a.getMap();this.B=this.c=null;this.t=!1;this.setMap(this.b)}k=w.prototype;
k.onAdd=function(){this.c=document.createElement("DIV");if(this.t){var a=C(this,this.d);this.c.style.cssText=D(this,a);this.c.innerHTML=this.B.text}this.getPanes().overlayMouseTarget.appendChild(this.c);var b=this;google.maps.event.addDomListener(this.c,"click",function(){var a=b.u.k;google.maps.event.trigger(a,"clusterclick",b.u);a.P&&b.b.fitBounds(b.u.getBounds())})};function C(a,b){var c=a.getProjection().fromLatLngToDivPixel(b);c.x-=parseInt(a.p/2,10);c.y-=parseInt(a.h/2,10);return c}
k.draw=function(){if(this.t){var a=C(this,this.d);this.c.style.top=a.y+"px";this.c.style.left=a.x+"px"}};function B(a){a.c&&(a.c.style.display="none");a.t=!1}k.show=function(){if(this.c){var a=C(this,this.d);this.c.style.cssText=D(this,a);this.c.style.display=""}this.t=!0};k.remove=function(){this.setMap(null)};k.onRemove=function(){this.c&&this.c.parentNode&&(B(this),this.c.parentNode.removeChild(this.c),this.c=null)};k.setCenter=d("d");
function D(a,b){var c=[];c.push("background-image:url("+a.ea+");");c.push("background-position:"+(a.D?a.D:"0 0")+";");"object"===typeof a.e?("number"===typeof a.e[0]&&0<a.e[0]&&a.e[0]<a.h?c.push("height:"+(a.h-a.e[0])+"px; padding-top:"+a.e[0]+"px;"):c.push("height:"+a.h+"px; line-height:"+a.h+"px;"),"number"===typeof a.e[1]&&0<a.e[1]&&a.e[1]<a.p?c.push("width:"+(a.p-a.e[1])+"px; padding-left:"+a.e[1]+"px;"):c.push("width:"+a.p+"px; text-align:center;")):c.push("height:"+a.h+"px; line-height:"+a.h+
"px; width:"+a.p+"px; text-align:center;");c.push("cursor:pointer; top:"+b.y+"px; left:"+b.x+"px; color:"+(a.N?a.N:"black")+"; position:absolute; font-size:"+(a.O?a.O:11)+"px; font-family:Arial,sans-serif; font-weight:bold");return c.join("")}window.MarkerClusterer=l;l.prototype.addMarker=l.prototype.q;l.prototype.addMarkers=l.prototype.C;l.prototype.clearMarkers=l.prototype.S;l.prototype.fitMapToMarkers=l.prototype.T;l.prototype.getCalculator=l.prototype.H;l.prototype.getGridSize=l.prototype.I;
l.prototype.getExtendedBounds=l.prototype.v;l.prototype.getMap=l.prototype.getMap;l.prototype.getMarkers=l.prototype.o;l.prototype.getMaxZoom=l.prototype.J;l.prototype.getStyles=l.prototype.w;l.prototype.getTotalClusters=l.prototype.V;l.prototype.getTotalMarkers=l.prototype.W;l.prototype.redraw=l.prototype.i;l.prototype.removeMarker=l.prototype.Z;l.prototype.removeMarkers=l.prototype.$;l.prototype.resetViewport=l.prototype.m;l.prototype.repaint=l.prototype.M;l.prototype.setCalculator=l.prototype.aa;
l.prototype.setGridSize=l.prototype.ba;l.prototype.setMaxZoom=l.prototype.ca;l.prototype.onAdd=l.prototype.onAdd;l.prototype.draw=l.prototype.draw;v.prototype.getCenter=v.prototype.getCenter;v.prototype.getSize=v.prototype.U;v.prototype.getMarkers=v.prototype.o;w.prototype.onAdd=w.prototype.onAdd;w.prototype.draw=w.prototype.draw;w.prototype.onRemove=w.prototype.onRemove;Object.keys=Object.keys||function(a){var b=[],c;for(c in a)a.hasOwnProperty(c)&&b.push(c);return b};

