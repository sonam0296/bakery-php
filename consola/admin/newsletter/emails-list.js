var ConteudoDados = function () {

    var initPickers = function () {
        //init date pickers
        $('.date-picker').datepicker({
            rtl: Metronic.isRTL(),
            autoclose: true
        });
    }

    var nome_script = $('script[src*=emails]');
    var texto1 = nome_script.attr('data-texto1');
    var texto2 = nome_script.attr('data-texto2');
    var texto3 = nome_script.attr('data-texto3');

    var handleProducts = function() {
        var grid = new Datatable();
		var isReloadedFromCookies = 0;
		
		
		$.fn.dataTableExt.afnFiltering = new Array();
		var oControls = $('.filter').find(':input[name]');
		oControls.each(function() {
			var oControl = $(this);
	 
			//Add custom filters
			$.fn.dataTableExt.afnFiltering.push(function( oSettings, aData, iDataIndex ) {
				if ( !oControl.val() || !oControl.hasClass('form-filter') ) return true;
				for ( i=0; i<aData.length; i++ )
					if ( aData[i].indexOf(oControl.val()) != -1 )
						return true;
	 
				return false;
			});
		});
		
        grid.init({
            src: $("#datatable_products"),
            onSuccess: function (grid) {
                // execute some code after table records loaded
            },
            onError: function (grid) {
                // execute some code on network or other general error  
            },
            loadingMessage: texto1,
            dataTable: { // here you can define a typical datatable settings from http://datatables.net/usage/options 

                // Uncomment below line("dom" parameter) to fix the dropdown overflow issue in the datatable cells. The default datatable layout
                // setup uses scrollable div(table-scrollable) with overflow:auto to enable vertical scroll(see: assets/global/scripts/datatable.js). 
                // So when dropdowns used the scrollable div should be removed. 
                //"dom": "<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'<'table-group-actions pull-right'>>r>t<'row'<'col-md-8 col-sm-12'pli><'col-md-4 col-sm-12'>>",

                "lengthMenu": [
                    [10, 20, 50, 100, 150],
                    [10, 20, 50, 100, 150] // change per page values here 
                ],
                "pageLength": 10, // default record count per page
                "ajax": {
                    "url": "emails-list.php", // ajax source,
                },
				"columnDefs": [{ // define columns sorting options(by default all columns are sortable extept the first checkbox column)
					'orderable': false,
					'targets': [0, 2, 7]
				}/*,
				{
					"targets": [0],
					"visible": false
				}*/],
                "order": [
                    [3, "desc"], // data desc
                ], // set first column as a default sort by asc
				"stateSave": true,
				"fnStateSaveParams": 	function ( oSettings, sValue ) {
					//Save custom filters
					oControls.each(function() {
						if ( $(this).attr('name') )
							sValue[ $(this).attr('name') ] = $(this).val().replace('"', '"');
					});
					return sValue;
				},
				"fnStateLoadParams"	: function ( oSettings, oData ) {
					//Load custom filters
					oControls.each(function() {
						var oControl = $(this);
		 
						$.each(oData, function(index, value) {
							if ( index == oControl.attr('name') ) {
								if(value) isReloadedFromCookies = 1;
								oControl.val( value );

                                // dropdowns select2
                                var type = oControl.prop('nodeName');
                                if (oControl.prop('class').toLowerCase().indexOf("select2me") >= 0)
                                    if(type == "SELECT") 
                                        oControl.select2().select2('val',value);
							}
						});
					});
					return true;
				},
				"fnInitComplete": function(settings) {
					setTimeout(function(){if(isReloadedFromCookies == 1){ grid.submitFilter()}},100);
				},/*,
				fnDrawCallback: function(settings) {
					console.log(settings);
					if (isReloadedFromCookies == true)
					{
						isReloadedFromCookies = false;
						restoreFilters(settings);
					}
					return true;
				},
				fnStateLoadCallback: function(settings) {
					console.log(settings);
					isReloadedFromCookies = true;
					return true; // if we don't return true here, the reload is cancelled.
				}*/
            }
        });

         // handle group actionsubmit button click
        grid.getTableWrapper().on('click', '.table-group-action-submit', function (e) {
            e.preventDefault();
            var action = $(".table-group-action-input", grid.getTableWrapper());
            if (action.val() != "" && grid.getSelectedRowsCount() > 0) {
                grid.setAjaxParam("customActionType", "group_action");
                grid.setAjaxParam("customActionName", action.val());
                grid.setAjaxParam("id", grid.getSelectedRows());
                grid.getDataTable().ajax.reload();
                grid.clearAjaxParams();

                setTimeout(function(){grid.submitFilter()},100); //DAVIDE
            } else if (action.val() == "") {
                Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: texto2,
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            } else if (grid.getSelectedRowsCount() === 0) {
                Metronic.alert({
                    type: 'danger',
                    icon: 'warning',
                    message: texto3,
                    container: grid.getTableWrapper(),
                    place: 'prepend'
                });
            }
        });
    }

    return {

        //main function to initiate the module
        init: function () {

            handleProducts();
            initPickers();
            
        }

    };

}();