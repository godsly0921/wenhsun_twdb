<!-- bootstrap-daterangepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<div class="row top_tiles">
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
            <div class="count">179</div>
            <h3>目前圖數</h3>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-comments-o"></i></div>
            <div class="count">179</div>
            <h3>上圖張數</h3>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
            <div class="count">179</div>
            <h3>上架張數</h3>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-check-square-o"></i></div>
            <div class="count">179</div>
            <h3>系統操作</h3>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Transaction Summary <small>Weekly progress</small></h2>
        <div class="filter">
          <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
            <span>December 30, 2014 - January 28, 2015</span> <b class="caret"></b>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <div class="col-md-9 col-sm-12 col-xs-12">
          <div class="demo-container" style="height:280px">
            <div id="chart_plot_02" class="demo-placeholder"></div>
          </div>
          <div class="tiles">
            <div class="col-md-4 tile">
              <span>Total Sessions</span>
              <h2>231,809</h2>
              <span class="sparkline11 graph" style="height: 160px;">
                   <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
              </span>
            </div>
            <div class="col-md-4 tile">
              <span>Total Revenue</span>
              <h2>$231,809</h2>
              <span class="sparkline22 graph" style="height: 160px;">
                    <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
              </span>
            </div>
            <div class="col-md-4 tile">
              <span>Total Sessions</span>
              <h2>231,809</h2>
              <span class="sparkline11 graph" style="height: 160px;">
                     <canvas width="200" height="60" style="display: inline-block; vertical-align: top; width: 94px; height: 30px;"></canvas>
              </span>
            </div>
          </div>

        </div>

        <div class="col-md-3 col-sm-12 col-xs-12">
          <div>
            <div class="x_title">
              <h2>Top Profiles</h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li class="dropdown">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Settings 1</a>
                    </li>
                    <li><a href="#">Settings 2</a>
                    </li>
                  </ul>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <ul class="list-unstyled top_profiles scroll-view">
              <li class="media event">
                <a class="pull-left border-aero profile_thumb">
                  <i class="fa fa-user aero"></i>
                </a>
                <div class="media-body">
                  <a class="title" href="#">Ms. Mary Jane</a>
                  <p><strong>$2300. </strong> Agent Avarage Sales </p>
                  <p> <small>12 Sales Today</small>
                  </p>
                </div>
              </li>
              <li class="media event">
                <a class="pull-left border-green profile_thumb">
                  <i class="fa fa-user green"></i>
                </a>
                <div class="media-body">
                  <a class="title" href="#">Ms. Mary Jane</a>
                  <p><strong>$2300. </strong> Agent Avarage Sales </p>
                  <p> <small>12 Sales Today</small>
                  </p>
                </div>
              </li>
              <li class="media event">
                <a class="pull-left border-blue profile_thumb">
                  <i class="fa fa-user blue"></i>
                </a>
                <div class="media-body">
                  <a class="title" href="#">Ms. Mary Jane</a>
                  <p><strong>$2300. </strong> Agent Avarage Sales </p>
                  <p> <small>12 Sales Today</small>
                  </p>
                </div>
              </li>
              <li class="media event">
                <a class="pull-left border-aero profile_thumb">
                  <i class="fa fa-user aero"></i>
                </a>
                <div class="media-body">
                  <a class="title" href="#">Ms. Mary Jane</a>
                  <p><strong>$2300. </strong> Agent Avarage Sales </p>
                  <p> <small>12 Sales Today</small>
                  </p>
                </div>
              </li>
              <li class="media event">
                <a class="pull-left border-green profile_thumb">
                  <i class="fa fa-user green"></i>
                </a>
                <div class="media-body">
                  <a class="title" href="#">Ms. Mary Jane</a>
                  <p><strong>$2300. </strong> Agent Avarage Sales </p>
                  <p> <small>12 Sales Today</small>
                  </p>
                </div>
              </li>
            </ul>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<!-- Flot -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/Flot/jquery.flot.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/Flot/jquery.flot.pie.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/Flot/jquery.flot.time.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/Flot/jquery.flot.stack.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/Flot/jquery.flot.resize.js"></script>
<!-- Flot plugins -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/flot.curvedlines/curvedLines.js"></script>
<!-- DateJS -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/DateJS/build/date.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/moment/min/moment.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<script>
    $(document).ready(function() {
        var s = '<?=$count_eachday_upload?>';
        var chart_plot_02_data = JSON.parse(s);
        console.log(chart_plot_02_data);
        var chart_plot_02_data = [];
        for (var i = 0; i < 30; i++) {
          chart_plot_02_data.push([new Date(Date.today().add(i).days()).getTime(), randNum() + i + i + 10]);
        }
        console.log(chart_plot_02_data);
        var chart_plot_02_settings = {
            grid: {
                show: true,
                aboveData: true,
                color: "#3f3f3f",
                labelMargin: 10,
                axisMargin: 0,
                borderWidth: 0,
                borderColor: null,
                minBorderMargin: 5,
                clickable: true,
                hoverable: true,
                autoHighlight: true,
                mouseActiveRadius: 100
            },
            series: {
                lines: {
                    show: true,
                    fill: true,
                    lineWidth: 2,
                    steps: false
                },
                points: {
                    show: true,
                    radius: 4.5,
                    symbol: "circle",
                    lineWidth: 3.0
                }
            },
            legend: {
                position: "ne",
                margin: [0, -25],
                noColumns: 0,
                labelBoxBorderColor: null,
                labelFormatter: function(label, series) {
                    return label + '&nbsp;&nbsp;';
                },
                width: 40,
                height: 1
            },
            colors: ['#96CA59', '#3F97EB', '#72c380', '#6f7a8a', '#f7cb38', '#5a8022', '#2c7282'],
            shadowSize: 0,
            tooltip: true,
            tooltipOpts: {
                content: "%s: %y.0",
                xDateFormat: "%d/%m",
            shifts: {
                x: -30,
                y: -50
            },
            defaultTheme: false
            },
            yaxis: {
                min: 0
            },
            xaxis: {
                mode: "time",
                minTickSize: [1, "day"],
                timeformat: "%d/%m/%y",
                min: chart_plot_02_data[0][0],
                max: chart_plot_02_data[20][0]
            }
        };  
        if ($("#chart_plot_02").length){
            console.log('Plot2');
            
            $.plot( $("#chart_plot_02"), 
            [{ 
                label: "Email Sent", 
                data: chart_plot_02_data, 
                lines: { 
                    fillColor: "rgba(150, 202, 89, 0.12)" 
                }, 
                points: { 
                    fillColor: "#fff" } 
            }], chart_plot_02_settings);
            
        }
    })
</script>