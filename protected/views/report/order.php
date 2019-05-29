<!-- bootstrap-daterangepicker -->
<link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
<div class="row top_tiles">
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-caret-square-o-right"></i></div>
            <div class="count"><?=$count_order_sum['order_total']?></div>
            <h3>總金額</h3>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-comments-o"></i></div>
            <div class="count"><?=$count_order_sum['single_total']?></div>
            <h3>單圖授權</h3>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
            <div class="count"><?=$count_order_sum['point_total']?></div>
            <h3>點數</h3>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
            <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
            <div class="count"><?=$count_order_sum['sub_total']?></div>
            <h3>自由載</h3>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>每日銷售金額統計</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
        <div class="col-md-9 col-sm-12 col-xs-12">
          <div class="demo-container" style="height:280px">
            <div id="system_report" class="demo-placeholder"></div>
          </div>
        </div>

        <div class="col-md-3 col-sm-12 col-xs-12">
          <div>
            <div class="x_title">
              <h2>Top Profiles</h2>
              <div class="clearfix"></div>
            </div>
            <ul class="list-unstyled top_profiles scroll-view">
              <?php foreach ($top3_order as $key => $value) {?>
                <li class="media event">
                    <a class="pull-left border-green profile_thumb">
                        <i class="fa fa-user green"></i>
                    </a>
                    <div class="media-body">
                        <a class="title" href="#"><?=$value['member_name']?></a>
                        <p><strong><?=$value['order_datetime']?></strong></p>
                        <p><strong><?=$value['order_category']?></strong></p>
                    </div>
                </li>
              <?php }?>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-xl-12">
        <table id="specialcaseTable" width="100%" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
            <thead>
            <tr role="row">
                <th>用戶</th>
                <th>帳號</th>
                <th>方案</th>
                <th>金額</th>
                <th>折扣</th>               
                <th>訂單日期</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($all_order as $key => $value){ ?>
                <tr class="gradeC" role="row">
                    <td><?=$value['member_name']?></td>
                    <td><?=$value['member_account']?></td>
                    <td><?=$value['order_category']?></td>
                    <td><?=$value['cost_total']?></td>
                    <td><?=$value['discount']?></td>  
                    <td><?=$value['order_datetime']?></td>
               </tr>
            <?php } ?>
            </tbody>
        </table>
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
<script src="<?php echo Yii::app()->request->baseUrl;?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        var s = '<?=$count_eachday_order?>';
        var chart_plot_data = JSON.parse(s);
        console.log(chart_plot_data);
        var system_data = [];
        for (var i = 0; i < chart_plot_data.length; i++) {
          system_data.push([new Date(chart_plot_data[i][0]).getTime(), chart_plot_data[i][1]]);
        }
        var system_settings = {
            grid: {
                show: true,
                aboveData: true,
                color: "#3f3f3f",
                labelMargin: 10,
                axisMargin: 0,
                borderWidth: 0,
                borderColor: null,
                minBorderMargin: 2,
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
                timeformat: "%Y-%m-%d",
                min: system_data[0][0],
                max: system_data[chart_plot_data.length-1][0]
            }
        };  
        if ($("#system_report").length){           
            $.plot( $("#system_report"), 
            [{ 
                label: "銷售金額", 
                data: system_data, 
                lines: { 
                    fillColor: "rgba(150, 202, 89, 0.12)" 
                }, 
                points: { 
                    fillColor: "#fff" } 
            }], system_settings);
            
        }
        $('#specialcaseTable').DataTable( {
            //"scrollX": true,
            "lengthChange": false,
            "oLanguage": {
                "oPaginate": {"sFirst": "第一頁", "sPrevious": "上一頁", "sNext": "下一頁", "sLast": "最後一頁"},
                "sEmptyTable": "無任何聯繫資料"
            }
        });
    })
</script>