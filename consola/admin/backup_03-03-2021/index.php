<?php include_once("inc_pages.php"); ?>

<?php include_once("inc_head_1.php"); ?>
<?php include_once("inc_head_2.php"); ?>
<style>
#chart_11 > div > div > a, #chart_10 > div > div > a, #chart_4 > div > div > a, #chartdiv > div > div > a, #chartdiv2 > div > div > a {display:none !important;}
</style>
<body class="<?php echo $body_info; ?> page-sidebar-closed-hide-logo page-container-bg-solid">
<?php include_once("inc_topo.php"); ?>
<div class="clearfix">
</div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
  <?php include_once("inc_menu.php"); ?>
  <!-- BEGIN CONTENT -->
  <div class="page-content-wrapper">
    <div class="page-content">
    
      <?php //include_once("inc_customize.php"); ?>
      <!-- BEGIN PAGE HEADER-->
      <div class="page-bar">
        <ul class="page-breadcrumb">
          <li>
            <i class="fa fa-home"></i>
            <a href="javascript:void(null)"><?php echo $RecursosCons->RecursosCons['home']; ?></a>
            <i class="fa fa-angle-right"></i>
          </li>
          <li>
            <a href="index.php"><?php echo $RecursosCons->RecursosCons['dashboard']; ?></a>
          </li>
        </ul>
      </div>
      <h3 class="page-title">
      <?php echo $RecursosCons->RecursosCons['dashboard']; ?> <small><?php echo $RecursosCons->RecursosCons['painel_principal']; ?></small>
      </h3>
      <!-- END PAGE HEADER-->
      <!-- BEGIN DASHBOARD STATS -->
      
      <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat red-intense">
            <div class="visual" style="height: 110px;">
              <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
              <div class="number">
                <?php echo $enc_final; ?>
              </div>
              <div class="desc">
                <?php echo $RecursosCons->RecursosCons['receita_total_label']; ?>
              </div>
              <div class="desc" style="margin-top: 1rem;">
                <strong><?php echo date('Y'); ?></strong> - <?php echo $enc_final_ano; ?>
              </div>
            </div>
            <a class="more" href="<?php echo ROOTPATH_HTTP_ADMIN; ?>encomendas/encomendas.php">
            Ver mais <i class="m-icon-swapright m-icon-white"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat green-haze">
            <div class="visual" style="height: 110px;">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
              <div class="number">
                <?php echo $row_rsEncomendas['total']; ?>
              </div>
              <div class="desc">
                <?php echo $RecursosCons->RecursosCons['encomendas']; ?>
              </div>
              <div class="desc" style="margin-top: 1rem;">
                <strong><?php echo date('Y'); ?></strong> - <?php echo $row_rsEncomendasAno['total']; ?>
              </div>
            </div>
            <a class="more" href="<?php echo ROOTPATH_HTTP_ADMIN; ?>encomendas/encomendas.php">
            <?php echo $RecursosCons->RecursosCons['ver_mais']; ?> <i class="m-icon-swapright m-icon-white"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat purple-plum">
            <div class="visual" style="height: 110px;">
              <i class="fa fa-globe"></i>
            </div>
            <div class="details">
              <div class="number">
                <?php echo $row_rsClientes['total']; ?>
              </div>
              <div class="desc">
                <?php echo $RecursosCons->RecursosCons['registos']; ?>
              </div>
              <div class="desc" style="margin-top: 1rem;">
                <strong><?php echo date('Y'); ?></strong> - <?php echo $row_rsClientesAno['total']; ?>
              </div>
            </div>
            <a class="more" href="<?php echo ROOTPATH_HTTP_ADMIN; ?>clientes/clientes.php">
            <?php echo $RecursosCons->RecursosCons['ver_mais']; ?> <i class="m-icon-swapright m-icon-white"></i>
            </a>
          </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
          <div class="dashboard-stat blue-madison">
            <div class="visual" style="height: 110px;">
              <i class="fa fa-comments"></i>
            </div>
            <div class="details">
              <div class="number">
                <?php echo $row_rsTickets['total']; ?>
              </div>
              <div class="desc">
                <?php echo $RecursosCons->RecursosCons['tickets']; ?>
              </div>
              <div class="desc" style="margin-top: 1rem;">
                <strong><?php echo date('Y'); ?></strong> - <?php echo $row_rsTicketsAno['total']; ?>
              </div>
            </div>
            <a class="more" href="<?php echo ROOTPATH_HTTP_ADMIN; ?>tickets/tickets.php">
            <?php echo $RecursosCons->RecursosCons['ver_mais']; ?> <i class="m-icon-swapright m-icon-white"></i>
            </a>
          </div>
        </div>
      </div>

    </div>
  </div>
  <!-- END CONTENT -->
  <?php include_once("inc_quick_sidebar.php"); ?>
</div>
<!-- END CONTAINER -->
<?php include_once(ROOTPATH_ADMIN."inc_footer_1.php"); ?>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery.pulsate.min.js" type="text/javascript"></script>
<!-- IMPORTANT! fullcalendar depends on jquery-ui.min.js for drag & drop support -->
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/jquery.sparkline.min.js" type="text/javascript"></script>
<!-- 3D CHARTS -->
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/amcharts.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/serial.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/pie.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/radar.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/themes/light.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/themes/patterns.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amcharts/themes/chalk.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/ammap/ammap.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/ammap/maps/js/continentsLow.js"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/ammap/maps/js/worldLow.js" type="text/javascript"></script>
<script src="<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/amstockcharts/amstock.js" type="text/javascript"></script>
<!-- 3D CHARTS -->
<?php include_once(ROOTPATH_ADMIN."inc_footer_2.php"); ?>
<script>
var Index = function () {
  return {
    //main function
    init: function () {
      Metronic.addResizeHandler(function () {
        jQuery('.vmaps').each(function () {
          var map = jQuery(this);
          map.width(map.parent().width());
        });
      });
    },

    initCharts: function () {
      if(!jQuery.plot) {
        return;
      }

      function showChartTooltip(x, y, xValue, yValue) {
        $('<div id="tooltip" class="chart-tooltip">' + yValue + '<\/div>').css({
          position: "absolute",
          display: "none",
          top: y - 40,
          left: x - 40,
          border: "0px solid #ccc",
          padding: "2px 6px",
          "background-color": "#fff"
        }).appendTo("body").fadeIn(200);
      }

      var data = [];
      var totalPoints = 250;

      // random data generator for plot charts
      function randValue() {}

      var visitors = <?php echo $flot_data_visits; ?>;

      if($('#site_statistics').size() != 0) {
        $('#site_statistics_loading').hide();
        $('#site_statistics_content').show();

        var plot_statistics = $.plot($("#site_statistics"),
          [{
            data: visitors,
            lines: {
              fill: 0.6,
              lineWidth: 0
            },
            color: ["#1F897F"]
          }, {
            data: visitors,
            points: {
              show: true,
              fill: true,
              radius: 5,
              fillColor: "#1F897F",
              lineWidth: 3
            },
            color: "#fff",
            shadowSize: 0
          }],
          {
            xaxis: {
              tickLength: 0,
              tickDecimals: 0,
              mode: "categories",
              min: 0,
              font: {
                lineHeight: 14,
                style: "normal",
                variant: "small-caps",
                color: "#6F7B8A"
              }
            },
            yaxis: {
              ticks: 5,
              tickDecimals: 0,
              tickColor: "#eee",
              font: {
                lineHeight: 14,
                style: "normal",
                variant: "small-caps",
                color: "#6F7B8A"
              }
            },
            grid: {
              hoverable: true,
              clickable: true,
              tickColor: "#eee",
              borderColor: "#eee",
              borderWidth: 1
            }
          }
        );

        var previousPoint = null;
        $("#site_statistics").bind("plothover", function (event, pos, item) {
          $("#x").text(pos.x.toFixed(2));
          $("#y").text(pos.y.toFixed(2));

          if(item) {
            if(previousPoint != item.dataIndex) {
              previousPoint = item.dataIndex;

              $("#tooltip").remove();
              var x = item.datapoint[0].toFixed(2),
                  y = item.datapoint[1].toFixed(2);

              showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + ' visitas');
            }
          } 
          else {
            $("#tooltip").remove();
            previousPoint = null;
          }
        });
      }
      
      if($('#site_activities').size() != 0) {
        //site activities
        var previousPoint2 = null;
        $('#site_activities_loading').hide();
        $('#site_activities_content').show();

        var data1 = [
          <?php $counter=0; foreach($row_rsEncValor as $encomenda) {
            $query_rsEncValorTotal = "SELECT SUM(valor_c_iva) AS valor_c_iva FROM encomendas WHERE estado!='5' AND month(data) = $encomenda[mes] AND year(data) = $encomenda[ano]";
            $rsEncValorTotal = DB::getInstance()->prepare($query_rsEncValorTotal);
            $rsEncValorTotal->execute();
            $row_rsEncValorTotal = $rsEncValorTotal->fetch(PDO::FETCH_ASSOC);
            $totalRows_rsEncValorTotal = $rsEncValorTotal->rowCount();
            DB::close();
            
            $valor_final = number_format($row_rsEncValorTotal['valor_c_iva'], 2, ".", ""); 
            $counter++;
            
            if($encomenda["mes"]==1) $mes_nome = "JAN";
            elseif($encomenda["mes"]==2) $mes_nome = "FEV";
            elseif($encomenda["mes"]==3) $mes_nome = "MAR";
            elseif($encomenda["mes"]==4) $mes_nome = "ABR";
            elseif($encomenda["mes"]==5) $mes_nome = "MAI";
            elseif($encomenda["mes"]==6) $mes_nome = "JUN";
            elseif($encomenda["mes"]==7) $mes_nome = "JUL";
            elseif($encomenda["mes"]==8) $mes_nome = "AGO";
            elseif($encomenda["mes"]==9) $mes_nome = "SET";
            elseif($encomenda["mes"]==10) $mes_nome = "OUT";
            elseif($encomenda["mes"]==11) $mes_nome = "NOV";
            elseif($encomenda["mes"]==12) $mes_nome = "DEZ";
            
            if($encomenda["mes"]==date("m")) $mes_nome.=" ".$encomenda['ano'];
            ?>
            ['<?php echo $mes_nome; ?>', <?php echo $valor_final; ?>]<?php if($counter < $totalRows_rsEncValor) echo ","; ?>
          <?php } ?>
        ];

        var plot_statistics = $.plot($("#site_activities"),
          [{
            data: data1,
            lines: {
              fill: 0.2,
              lineWidth: 0,
            },
            color: ["#BAD9F5"]
          }, {
            data: data1,
            points: {
              show: true,
              fill: true,
              radius: 4,
              fillColor: "#9ACAE6",
              lineWidth: 2
            },
            color: "#9ACAE6",
            shadowSize: 1
          }, {
            data: data1,
            lines: {
              show: true,
              fill: false,
              lineWidth: 3
            },
            color: "#9ACAE6",
            shadowSize: 0
          }],
          {
            xaxis: {
              tickLength: 0,
              tickDecimals: 0,
              mode: "categories",
              min: 0,
              font: {
                lineHeight: 18,
                style: "normal",
                variant: "small-caps",
                color: "#6F7B8A"
              }
            },
            yaxis: {
              ticks: 5,
              tickDecimals: 0,
              tickColor: "#eee",
              font: {
                lineHeight: 14,
                style: "normal",
                variant: "small-caps",
                color: "#6F7B8A"
              }
            },
            grid: {
              hoverable: true,
              clickable: true,
              tickColor: "#eee",
              borderColor: "#eee",
              borderWidth: 1
            }
          }
        );

        $("#site_activities").bind("plothover", function (event, pos, item) {
          $("#x").text(pos.x.toFixed(2));
          $("#y").text(pos.y.toFixed(2));
          if(item) {
            if(previousPoint2 != item.dataIndex) {
              previousPoint2 = item.dataIndex;
              $("#tooltip").remove();
              var x = item.datapoint[0].toFixed(2),
                  y = item.datapoint[1].toFixed(2);
              showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1] + '€');
            }
          }
        });

        $('#site_activities').bind("mouseleave", function () {
          $("#tooltip").remove();
        });
      }
      
      var latlong = {};
      latlong["AD"] = {
        "latitude": 42.5,
        "longitude": 1.5
      };
      latlong["AE"] = {
        "latitude": 24,
        "longitude": 54
      };
      latlong["AF"] = {
        "latitude": 33,
        "longitude": 65
      };
      latlong["AG"] = {
        "latitude": 17.05,
        "longitude": -61.8
      };
      latlong["AI"] = {
        "latitude": 18.25,
        "longitude": -63.1667
      };
      latlong["AL"] = {
        "latitude": 41,
        "longitude": 20
      };
      latlong["AM"] = {
        "latitude": 40,
        "longitude": 45
      };
      latlong["AN"] = {
        "latitude": 12.25,
        "longitude": -68.75
      };
      latlong["AO"] = {
        "latitude": -12.5,
        "longitude": 18.5
      };
      latlong["AP"] = {
        "latitude": 35,
        "longitude": 105
      };
      latlong["AQ"] = {
        "latitude": -90,
        "longitude": 0
      };
      latlong["AR"] = {
        "latitude": -34,
        "longitude": -64
      };
      latlong["AS"] = {
        "latitude": -14.3333,
        "longitude": -170
      };
      latlong["AT"] = {
        "latitude": 47.3333,
        "longitude": 13.3333
      };
      latlong["AU"] = {
        "latitude": -27,
        "longitude": 133
      };
      latlong["AW"] = {
        "latitude": 12.5,
        "longitude": -69.9667
      };
      latlong["AZ"] = {
        "latitude": 40.5,
        "longitude": 47.5
      };
      latlong["BA"] = {
        "latitude": 44,
        "longitude": 18
      };
      latlong["BB"] = {
        "latitude": 13.1667,
        "longitude": -59.5333
      };
      latlong["BD"] = {
        "latitude": 24,
        "longitude": 90
      };
      latlong["BE"] = {
        "latitude": 50.8333,
        "longitude": 4
      };
      latlong["BF"] = {
        "latitude": 13,
        "longitude": -2
      };
      latlong["BG"] = {
        "latitude": 43,
        "longitude": 25
      };
      latlong["BH"] = {
        "latitude": 26,
        "longitude": 50.55
      };
      latlong["BI"] = {
        "latitude": -3.5,
        "longitude": 30
      };
      latlong["BJ"] = {
        "latitude": 9.5,
        "longitude": 2.25
      };
      latlong["BM"] = {
        "latitude": 32.3333,
        "longitude": -64.75
      };
      latlong["BN"] = {
        "latitude": 4.5,
        "longitude": 114.6667
      };
      latlong["BO"] = {
        "latitude": -17,
        "longitude": -65
      };
      latlong["BR"] = {
        "latitude": -10,
        "longitude": -55
      };
      latlong["BS"] = {
        "latitude": 24.25,
        "longitude": -76
      };
      latlong["BT"] = {
        "latitude": 27.5,
        "longitude": 90.5
      };
      latlong["BV"] = {
        "latitude": -54.4333,
        "longitude": 3.4
      };
      latlong["BW"] = {
        "latitude": -22,
        "longitude": 24
      };
      latlong["BY"] = {
        "latitude": 53,
        "longitude": 28
      };
      latlong["BZ"] = {
        "latitude": 17.25,
        "longitude": -88.75
      };
      latlong["CA"] = {
        "latitude": 54,
        "longitude": -100
      };
      latlong["CC"] = {
        "latitude": -12.5,
        "longitude": 96.8333
      };
      latlong["CD"] = {
        "latitude": 0,
        "longitude": 25
      };
      latlong["CF"] = {
        "latitude": 7,
        "longitude": 21
      };
      latlong["CG"] = {
        "latitude": -1,
        "longitude": 15
      };
      latlong["CH"] = {
        "latitude": 47,
        "longitude": 8
      };
      latlong["CI"] = {
        "latitude": 8,
        "longitude": -5
      };
      latlong["CK"] = {
        "latitude": -21.2333,
        "longitude": -159.7667
      };
      latlong["CL"] = {
        "latitude": -30,
        "longitude": -71
      };
      latlong["CM"] = {
        "latitude": 6,
        "longitude": 12
      };
      latlong["CN"] = {
        "latitude": 35,
        "longitude": 105
      };
      latlong["CO"] = {
        "latitude": 4,
        "longitude": -72
      };
      latlong["CR"] = {
        "latitude": 10,
        "longitude": -84
      };
      latlong["CU"] = {
        "latitude": 21.5,
        "longitude": -80
      };
      latlong["CV"] = {
        "latitude": 16,
        "longitude": -24
      };
      latlong["CX"] = {
        "latitude": -10.5,
        "longitude": 105.6667
      };
      latlong["CY"] = {
        "latitude": 35,
        "longitude": 33
      };
      latlong["CZ"] = {
        "latitude": 49.75,
        "longitude": 15.5
      };
      latlong["DE"] = {
        "latitude": 51,
        "longitude": 9
      };
      latlong["DJ"] = {
        "latitude": 11.5,
        "longitude": 43
      };
      latlong["DK"] = {
        "latitude": 56,
        "longitude": 10
      };
      latlong["DM"] = {
        "latitude": 15.4167,
        "longitude": -61.3333
      };
      latlong["DO"] = {
        "latitude": 19,
        "longitude": -70.6667
      };
      latlong["DZ"] = {
        "latitude": 28,
        "longitude": 3
      };
      latlong["EC"] = {
        "latitude": -2,
        "longitude": -77.5
      };
      latlong["EE"] = {
        "latitude": 59,
        "longitude": 26
      };
      latlong["EG"] = {
        "latitude": 27,
        "longitude": 30
      };
      latlong["EH"] = {
        "latitude": 24.5,
        "longitude": -13
      };
      latlong["ER"] = {
        "latitude": 15,
        "longitude": 39
      };
      latlong["ES"] = {
        "latitude": 40,
        "longitude": -4
      };
      latlong["ET"] = {
        "latitude": 8,
        "longitude": 38
      };
      latlong["EU"] = {
        "latitude": 47,
        "longitude": 8
      };
      latlong["FI"] = {
        "latitude": 62,
        "longitude": 26
      };
      latlong["FJ"] = {
        "latitude": -18,
        "longitude": 175
      };
      latlong["FK"] = {
        "latitude": -51.75,
        "longitude": -59
      };
      latlong["FM"] = {
        "latitude": 6.9167,
        "longitude": 158.25
      };
      latlong["FO"] = {
        "latitude": 62,
        "longitude": -7
      };
      latlong["FR"] = {
        "latitude": 46,
        "longitude": 2
      };
      latlong["GA"] = {
        "latitude": -1,
        "longitude": 11.75
      };
      latlong["GB"] = {
        "latitude": 54,
        "longitude": -2
      };
      latlong["GD"] = {
        "latitude": 12.1167,
        "longitude": -61.6667
      };
      latlong["GE"] = {
        "latitude": 42,
        "longitude": 43.5
      };
      latlong["GF"] = {
        "latitude": 4,
        "longitude": -53
      };
      latlong["GH"] = {
        "latitude": 8,
        "longitude": -2
      };
      latlong["GI"] = {
        "latitude": 36.1833,
        "longitude": -5.3667
      };
      latlong["GL"] = {
        "latitude": 72,
        "longitude": -40
      };
      latlong["GM"] = {
        "latitude": 13.4667,
        "longitude": -16.5667
      };
      latlong["GN"] = {
        "latitude": 11,
        "longitude": -10
      };
      latlong["GP"] = {
        "latitude": 16.25,
        "longitude": -61.5833
      };
      latlong["GQ"] = {
        "latitude": 2,
        "longitude": 10
      };
      latlong["GR"] = {
        "latitude": 39,
        "longitude": 22
      };
      latlong["GS"] = {
        "latitude": -54.5,
        "longitude": -37
      };
      latlong["GT"] = {
        "latitude": 15.5,
        "longitude": -90.25
      };
      latlong["GU"] = {
        "latitude": 13.4667,
        "longitude": 144.7833
      };
      latlong["GW"] = {
        "latitude": 12,
        "longitude": -15
      };
      latlong["GY"] = {
        "latitude": 5,
        "longitude": -59
      };
      latlong["HK"] = {
        "latitude": 22.25,
        "longitude": 114.1667
      };
      latlong["HM"] = {
        "latitude": -53.1,
        "longitude": 72.5167
      };
      latlong["HN"] = {
        "latitude": 15,
        "longitude": -86.5
      };
      latlong["HR"] = {
        "latitude": 45.1667,
        "longitude": 15.5
      };
      latlong["HT"] = {
        "latitude": 19,
        "longitude": -72.4167
      };
      latlong["HU"] = {
        "latitude": 47,
        "longitude": 20
      };
      latlong["ID"] = {
        "latitude": -5,
        "longitude": 120
      };
      latlong["IE"] = {
        "latitude": 53,
        "longitude": -8
      };
      latlong["IL"] = {
        "latitude": 31.5,
        "longitude": 34.75
      };
      latlong["IN"] = {
        "latitude": 20,
        "longitude": 77
      };
      latlong["IO"] = {
        "latitude": -6,
        "longitude": 71.5
      };
      latlong["IQ"] = {
        "latitude": 33,
        "longitude": 44
      };
      latlong["IR"] = {
        "latitude": 32,
        "longitude": 53
      };
      latlong["IS"] = {
        "latitude": 65,
        "longitude": -18
      };
      latlong["IT"] = {
        "latitude": 42.8333,
        "longitude": 12.8333
      };
      latlong["JM"] = {
        "latitude": 18.25,
        "longitude": -77.5
      };
      latlong["JO"] = {
        "latitude": 31,
        "longitude": 36
      };
      latlong["JP"] = {
        "latitude": 36,
        "longitude": 138
      };
      latlong["KE"] = {
        "latitude": 1,
        "longitude": 38
      };
      latlong["KG"] = {
        "latitude": 41,
        "longitude": 75
      };
      latlong["KH"] = {
        "latitude": 13,
        "longitude": 105
      };
      latlong["KI"] = {
        "latitude": 1.4167,
        "longitude": 173
      };
      latlong["KM"] = {
        "latitude": -12.1667,
        "longitude": 44.25
      };
      latlong["KN"] = {
        "latitude": 17.3333,
        "longitude": -62.75
      };
      latlong["KP"] = {
        "latitude": 40,
        "longitude": 127
      };
      latlong["KR"] = {
        "latitude": 37,
        "longitude": 127.5
      };
      latlong["KW"] = {
        "latitude": 29.3375,
        "longitude": 47.6581
      };
      latlong["KY"] = {
        "latitude": 19.5,
        "longitude": -80.5
      };
      latlong["KZ"] = {
        "latitude": 48,
        "longitude": 68
      };
      latlong["LA"] = {
        "latitude": 18,
        "longitude": 105
      };
      latlong["LB"] = {
        "latitude": 33.8333,
        "longitude": 35.8333
      };
      latlong["LC"] = {
        "latitude": 13.8833,
        "longitude": -61.1333
      };
      latlong["LI"] = {
        "latitude": 47.1667,
        "longitude": 9.5333
      };
      latlong["LK"] = {
        "latitude": 7,
        "longitude": 81
      };
      latlong["LR"] = {
        "latitude": 6.5,
        "longitude": -9.5
      };
      latlong["LS"] = {
        "latitude": -29.5,
        "longitude": 28.5
      };
      latlong["LT"] = {
        "latitude": 55,
        "longitude": 24
      };
      latlong["LU"] = {
        "latitude": 49.75,
        "longitude": 6
      };
      latlong["LV"] = {
        "latitude": 57,
        "longitude": 25
      };
      latlong["LY"] = {
        "latitude": 25,
        "longitude": 17
      };
      latlong["MA"] = {
        "latitude": 32,
        "longitude": -5
      };
      latlong["MC"] = {
        "latitude": 43.7333,
        "longitude": 7.4
      };
      latlong["MD"] = {
        "latitude": 47,
        "longitude": 29
      };
      latlong["ME"] = {
        "latitude": 42.5,
        "longitude": 19.4
      };
      latlong["MG"] = {
        "latitude": -20,
        "longitude": 47
      };
      latlong["MH"] = {
        "latitude": 9,
        "longitude": 168
      };
      latlong["MK"] = {
        "latitude": 41.8333,
        "longitude": 22
      };
      latlong["ML"] = {
        "latitude": 17,
        "longitude": -4
      };
      latlong["MM"] = {
        "latitude": 22,
        "longitude": 98
      };
      latlong["MN"] = {
        "latitude": 46,
        "longitude": 105
      };
      latlong["MO"] = {
        "latitude": 22.1667,
        "longitude": 113.55
      };
      latlong["MP"] = {
        "latitude": 15.2,
        "longitude": 145.75
      };
      latlong["MQ"] = {
        "latitude": 14.6667,
        "longitude": -61
      };
      latlong["MR"] = {
        "latitude": 20,
        "longitude": -12
      };
      latlong["MS"] = {
        "latitude": 16.75,
        "longitude": -62.2
      };
      latlong["MT"] = {
        "latitude": 35.8333,
        "longitude": 14.5833
      };
      latlong["MU"] = {
        "latitude": -20.2833,
        "longitude": 57.55
      };
      latlong["MV"] = {
        "latitude": 3.25,
        "longitude": 73
      };
      latlong["MW"] = {
        "latitude": -13.5,
        "longitude": 34
      };
      latlong["MX"] = {
        "latitude": 23,
        "longitude": -102
      };
      latlong["MY"] = {
        "latitude": 2.5,
        "longitude": 112.5
      };
      latlong["MZ"] = {
        "latitude": -18.25,
        "longitude": 35
      };
      latlong["NA"] = {
        "latitude": -22,
        "longitude": 17
      };
      latlong["NC"] = {
        "latitude": -21.5,
        "longitude": 165.5
      };
      latlong["NE"] = {
        "latitude": 16,
        "longitude": 8
      };
      latlong["NF"] = {
        "latitude": -29.0333,
        "longitude": 167.95
      };
      latlong["NG"] = {
        "latitude": 10,
        "longitude": 8
      };
      latlong["NI"] = {
        "latitude": 13,
        "longitude": -85
      };
      latlong["NL"] = {
        "latitude": 52.5,
        "longitude": 5.75
      };
      latlong["NO"] = {
        "latitude": 62,
        "longitude": 10
      };
      latlong["NP"] = {
        "latitude": 28,
        "longitude": 84
      };
      latlong["NR"] = {
        "latitude": -0.5333,
        "longitude": 166.9167
      };
      latlong["NU"] = {
        "latitude": -19.0333,
        "longitude": -169.8667
      };
      latlong["NZ"] = {
        "latitude": -41,
        "longitude": 174
      };
      latlong["OM"] = {
        "latitude": 21,
        "longitude": 57
      };
      latlong["PA"] = {
        "latitude": 9,
        "longitude": -80
      };
      latlong["PE"] = {
        "latitude": -10,
        "longitude": -76
      };
      latlong["PF"] = {
        "latitude": -15,
        "longitude": -140
      };
      latlong["PG"] = {
        "latitude": -6,
        "longitude": 147
      };
      latlong["PH"] = {
        "latitude": 13,
        "longitude": 122
      };
      latlong["PK"] = {
        "latitude": 30,
        "longitude": 70
      };
      latlong["PL"] = {
        "latitude": 52,
        "longitude": 20
      };
      latlong["PM"] = {
        "latitude": 46.8333,
        "longitude": -56.3333
      };
      latlong["PR"] = {
        "latitude": 18.25,
        "longitude": -66.5
      };
      latlong["PS"] = {
        "latitude": 32,
        "longitude": 35.25
      };
      latlong["PT"] = {
        "latitude": 39.5,
        "longitude": -8
      };
      latlong["PW"] = {
        "latitude": 7.5,
        "longitude": 134.5
      };
      latlong["PY"] = {
        "latitude": -23,
        "longitude": -58
      };
      latlong["QA"] = {
        "latitude": 25.5,
        "longitude": 51.25
      };
      latlong["RE"] = {
        "latitude": -21.1,
        "longitude": 55.6
      };
      latlong["RO"] = {
        "latitude": 46,
        "longitude": 25
      };
      latlong["RS"] = {
        "latitude": 44,
        "longitude": 21
      };
      latlong["RU"] = {
        "latitude": 60,
        "longitude": 100
      };
      latlong["RW"] = {
        "latitude": -2,
        "longitude": 30
      };
      latlong["SA"] = {
        "latitude": 25,
        "longitude": 45
      };
      latlong["SB"] = {
        "latitude": -8,
        "longitude": 159
      };
      latlong["SC"] = {
        "latitude": -4.5833,
        "longitude": 55.6667
      };
      latlong["SD"] = {
        "latitude": 15,
        "longitude": 30
      };
      latlong["SE"] = {
        "latitude": 62,
        "longitude": 15
      };
      latlong["SG"] = {
        "latitude": 1.3667,
        "longitude": 103.8
      };
      latlong["SH"] = {
        "latitude": -15.9333,
        "longitude": -5.7
      };
      latlong["SI"] = {
        "latitude": 46,
        "longitude": 15
      };
      latlong["SJ"] = {
        "latitude": 78,
        "longitude": 20
      };
      latlong["SK"] = {
        "latitude": 48.6667,
        "longitude": 19.5
      };
      latlong["SL"] = {
        "latitude": 8.5,
        "longitude": -11.5
      };
      latlong["SM"] = {
        "latitude": 43.7667,
        "longitude": 12.4167
      };
      latlong["SN"] = {
        "latitude": 14,
        "longitude": -14
      };
      latlong["SO"] = {
        "latitude": 10,
        "longitude": 49
      };
      latlong["SR"] = {
        "latitude": 4,
        "longitude": -56
      };
      latlong["ST"] = {
        "latitude": 1,
        "longitude": 7
      };
      latlong["SV"] = {
        "latitude": 13.8333,
        "longitude": -88.9167
      };
      latlong["SY"] = {
        "latitude": 35,
        "longitude": 38
      };
      latlong["SZ"] = {
        "latitude": -26.5,
        "longitude": 31.5
      };
      latlong["TC"] = {
        "latitude": 21.75,
        "longitude": -71.5833
      };
      latlong["TD"] = {
        "latitude": 15,
        "longitude": 19
      };
      latlong["TF"] = {
        "latitude": -43,
        "longitude": 67
      };
      latlong["TG"] = {
        "latitude": 8,
        "longitude": 1.1667
      };
      latlong["TH"] = {
        "latitude": 15,
        "longitude": 100
      };
      latlong["TJ"] = {
        "latitude": 39,
        "longitude": 71
      };
      latlong["TK"] = {
        "latitude": -9,
        "longitude": -172
      };
      latlong["TM"] = {
        "latitude": 40,
        "longitude": 60
      };
      latlong["TN"] = {
        "latitude": 34,
        "longitude": 9
      };
      latlong["TO"] = {
        "latitude": -20,
        "longitude": -175
      };
      latlong["TR"] = {
        "latitude": 39,
        "longitude": 35
      };
      latlong["TT"] = {
        "latitude": 11,
        "longitude": -61
      };
      latlong["TV"] = {
        "latitude": -8,
        "longitude": 178
      };
      latlong["TW"] = {
        "latitude": 23.5,
        "longitude": 121
      };
      latlong["TZ"] = {
        "latitude": -6,
        "longitude": 35
      };
      latlong["UA"] = {
        "latitude": 49,
        "longitude": 32
      };
      latlong["UG"] = {
        "latitude": 1,
        "longitude": 32
      };
      latlong["UM"] = {
        "latitude": 19.2833,
        "longitude": 166.6
      };
      latlong["US"] = {
        "latitude": 38,
        "longitude": -97
      };
      latlong["UY"] = {
        "latitude": -33,
        "longitude": -56
      };
      latlong["UZ"] = {
        "latitude": 41,
        "longitude": 64
      };
      latlong["VA"] = {
        "latitude": 41.9,
        "longitude": 12.45
      };
      latlong["VC"] = {
        "latitude": 13.25,
        "longitude": -61.2
      };
      latlong["VE"] = {
        "latitude": 8,
        "longitude": -66
      };
      latlong["VG"] = {
        "latitude": 18.5,
        "longitude": -64.5
      };
      latlong["VI"] = {
        "latitude": 18.3333,
        "longitude": -64.8333
      };
      latlong["VN"] = {
        "latitude": 16,
        "longitude": 106
      };
      latlong["VU"] = {
        "latitude": -16,
        "longitude": 167
      };
      latlong["WF"] = {
        "latitude": -13.3,
        "longitude": -176.2
      };
      latlong["WS"] = {
        "latitude": -13.5833,
        "longitude": -172.3333
      };
      latlong["YE"] = {
        "latitude": 15,
        "longitude": 48
      };
      latlong["YT"] = {
        "latitude": -12.8333,
        "longitude": 45.1667
      };
      latlong["ZA"] = {
        "latitude": -29,
        "longitude": 24
      };
      latlong["ZM"] = {
        "latitude": -15,
        "longitude": 30
      };
      latlong["ZW"] = {
        "latitude": -20,
        "longitude": 30
      };
  
      var mapData = <?php echo $mapa_data; ?>;
  
      var map;
      var minBulletSize = 5;
      var maxBulletSize = 50;
      var min = Infinity;
      var max = -Infinity;
  
      // get min and max values
      for (var i = 0; i < mapData.length; i++) {
        var value = mapData[i].value;
        if (value < min) {
          min = value;
        }
        if (value > max) {
          max = value;
        }
      }
  
      // build map
      AmCharts.ready(function() {
        AmCharts.theme = AmCharts.themes.dark;
        map = new AmCharts.AmMap();
        map.pathToImages = "<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/ammap/images/",
  
        map.fontFamily = "Open Sans";
        map.fontSize = "13";
        map.color = "#888";
        
        map.areasSettings = {
          unlistedAreasColor: "#000000",
          unlistedAreasAlpha: 0.1
        };
        map.imagesSettings.balloonText = "<span style='font-size:14px;'><b>[[title]]</b>: [[value]]</span>";
  
        var dataProvider = {
          mapVar: AmCharts.maps.worldLow,
          images: []
        }
  
        // create circle for each country
        for (var i = 0; i < mapData.length; i++) {
          var dataItem = mapData[i];
          var value = dataItem.value;
          // calculate size of a bubble
          var size = (value - min) / (max - min) * (maxBulletSize - minBulletSize) + minBulletSize;
          if (size < minBulletSize) {
            size = minBulletSize;
          }
          var id = dataItem.code;
          if(latlong[id]) {
            dataProvider.images.push({
              type: "circle",
              width: size,
              height: size,
              color: dataItem.color,
              longitude: latlong[id].longitude,
              latitude: latlong[id].latitude,
              title: dataItem.name,
              value: value
            });
          }
        }
  
        map.dataProvider = dataProvider;
  
        map.write("chart_10");
      });
      
      <?php if($totalRows_rsGraph > 0) { ?>
         var chart = AmCharts.makeChart("chart_4", {
          "theme": "light",
          "type": "serial",
          "dataProvider": <?php echo $data_graph; ?>,
          "valueAxes": [{
            "stackType": "3d",
            "unit": "€",
            "position": "left",
            "title": "",
          }],
          "startDuration": 1,
          "graphs": [{
            "balloonText": "Quantidade: <b>[[value]]</b>",
            "fillAlphas": 0.9,
            "lineAlpha": 0.2,
            "title": "2004",
            "type": "column",
            "valueField": "year2004"
          }, {
            "balloonText": "Receita: <b>[[value]]€</b>",
            "fillAlphas": 0.9,
            "lineAlpha": 0.2,
            "title": "2005",
            "type": "column",
            "valueField": "year2005"
          }],
          "plotAreaFillAlphas": 0.1,
          "depth3D": 60,
          "angle": 30,
          "categoryField": "country",
          "categoryAxis": {
            "gridPosition": "start",
            "labelRotation": 45
          },
          "export": {
            "enabled": true
           }
        }); 
      <?php } ?>
      
      AmCharts.maps.worldLow2 = {
        "svg": {
          "defs": {
            "amcharts:ammap": {
              "projection":"mercator",
              "leftLongitude":"-169.522279",
              "topLatitude":"83.646363",
              "rightLongitude":"190.122401",
              "bottomLatitude":"-55.621433"
            }
          },
          "g":{
            "path": <?php echo $mapa_data2; ?>
          }
        }
      };

      var map = AmCharts.makeChart( "chart_11", {
        "type": "map",
        "theme": "light",

        "dataProvider": {
          "map": "worldLow2",
          "areas": <?php echo $mapa_areas; ?>,
          //"map": AmCharts.maps.worldLow,
          "getAreasFromMap": true
        },
        "areasSettings": {
          "autoZoom": true,
          "selectedColor": "#CC0000"
        },
        "smallMap": {},
        "export": {
          "enabled": true,
          "position": "bottom-right"
        },
        "pathToImages": "<?php echo ROOTPATH_HTTP_CONSOLA; ?>assets/global/plugins/amcharts/ammap/images/"
      });
    }
  };
}();
</script>
<script>
jQuery(document).ready(function() {    
  Metronic.init(); // init metronic core componets
  Layout.init(); // init layout
  QuickSidebar.init(); // init quick sidebar
  Demo.init(); // init demo features
  Index.init();   
  Index.initCharts(); // init index pages custom scripts
});
</script>
</body>
<!-- END BODY -->
</html>