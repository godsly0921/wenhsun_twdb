<!DOCTYPE html>
<html lang="zh-tw">
<head>
	<meta charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/css/metisMenu.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/css/dataTables.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/css/sb-admin-2.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/css/main.css" />
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/bootstrap.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/metisMenu.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/sb-admin-2.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/Chart.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/jquery.dataTables.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/admin/ext/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.mtz.monthpicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.26.9/dist/sweetalert2.all.min.js"></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <title>文訊雜誌社人資管理系統</title>
</head>
<style type="text/css">
.loader{
    height: 100%;
    width: 100%;
    background-color:rgba(0,0,0,0.5);
    position: fixed;
    top: 0px;
    left:0px;
    z-index: 9999;
    display: none;
}
h1{
    margin-top: 20%;
    font-family: '微軟正黑體';
    color:#FFF;
    /*font-size:16px;*/
    letter-spacing:1px;
    font-weight:900;
    text-align:center;
}
.loader span{
    width:16px;
    height:16px;
    border-radius:50%;
    display:inline-block;
    position:absolute;
    left:50%;
    margin-left:-10px;
    -webkit-animation:3s infinite linear;
    -moz-animation:3s infinite linear;
    -o-animation:3s infinite linear;
    
}


.loader span:nth-child(2){
    background:#E84C3D;
    -webkit-animation:kiri 1.2s infinite linear;
    -moz-animation:kiri 1.2s infinite linear;
    -o-animation:kiri 1.2s infinite linear;
    
}
.loader span:nth-child(3){
    background:#F1C40F;
    z-index:100;
}
.loader span:nth-child(4){
    background:#2FCC71;
    -webkit-animation:kanan 1.2s infinite linear;
    -moz-animation:kanan 1.2s infinite linear;
    -o-animation:kanan 1.2s infinite linear;
}


@-webkit-keyframes kanan {
    0% {-webkit-transform:translateX(20px);
    }
   
    50%{-webkit-transform:translateX(-20px);
    }
    
    100%{-webkit-transform:translateX(20px);
    z-index:200;
    }
}
@-moz-keyframes kanan {
    0% {-moz-transform:translateX(20px);
    }
   
    50%{-moz-transform:translateX(-20px);
    }
    
    100%{-moz-transform:translateX(20px);
    z-index:200;
    }
}
@-o-keyframes kanan {
    0% {-o-transform:translateX(20px);
    }
   
    50%{-o-transform:translateX(-20px);
    }
    
    100%{-o-transform:translateX(20px);
    z-index:200;
    }
}




@-webkit-keyframes kiri {
     0% {-webkit-transform:translateX(-20px);
    z-index:200;
    }
    50%{-webkit-transform:translateX(20px);
    }
    100%{-webkit-transform:translateX(-20px);
    }
}

@-moz-keyframes kiri {
     0% {-moz-transform:translateX(-20px);
    z-index:200;
    }
    50%{-moz-transform:translateX(20px);
    }
    100%{-moz-transform:translateX(-20px);
    }
}
@-o-keyframes kiri {
     0% {-o-transform:translateX(-20px);
    z-index:200;
    }
    50%{-o-transform:translateX(20px);
    }
    100%{-o-transform:translateX(-20px);
    }
}
</style>
<body>
    
    <div class="loader">
        <h1>指令發送中</h1>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div id="wrapper">

        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand">文訊雜誌社人資管理系統</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right nav-ul-right">
                <p>您好, <?php echo Yii::app()->session['pid']?></p>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?php echo Yii::app() -> createUrl('admin/logout'); ?>"><i class="fa fa-sign-out fa-fw"></i> 登出</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <div style="clear:both;"></div>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="menu">
                        <?php
                            $system_session_jsons = [];
                            $power_session_jsons = [];
                            
                            if (isset(Yii::app()->session['system_session_jsons']))
                                $system_session_jsons = CJSON::decode(Yii::app()->session['system_session_jsons']);

                            if (isset(Yii::app()->session['power_session_jsons']))
                                $power_session_jsons = CJSON::decode(Yii::app()->session['power_session_jsons']);
                        ?>

                        <?php foreach ($system_session_jsons as $jsons): ?>
                            <?php if ($jsons["system_type"] == 1 && $jsons["system_name"] !== ''): ?>
                                <li data-ctrl="<?= $jsons['system_controller']?>"><a>

                                <?php if ($jsons['system_range'] == 0): ?>
                                    <i class="fa fa-lock fa-fw"></i>
                                <?php elseif($jsons['system_range'] == 1):?>
                                    <i class="fa fa-users fa-fw"></i>
                                <?php elseif($jsons['system_range'] == 2):?>
                                    <i class="fa fa-gear  fa-fw"></i>
                                <?php elseif($jsons['system_range'] == 3):?>
                                    <i class="fa fa-info-circle  fa-fw"></i>
                                <?php elseif($jsons['system_range'] == 4):?>
                                    <i class="fa fa-exclamation-circle fa-fw"></i>
                                <?php elseif($jsons['system_range'] == 5):?>
                                    <i class="fa fa-dashboard  fa-fw"></i>
                                <?php elseif($jsons['system_range'] == 6):?>
                                    <i class="fa fa-dollar    fa-fw"></i>    
                                <?php elseif($jsons['system_range'] == 7):?>
                                    <i class="fa fa-check-square-o fa-fw"></i>   
                                <?php elseif($jsons['system_range'] == 8):?>
                                    <i class="fa fa-pencil-square fa-fw"></i>                                      
                                <?php elseif($jsons['system_range'] == 9):?>
                                    <i class="fa fa-file-o fa-fw"></i>   
                                <?php elseif($jsons['system_range'] == 10):?>
                                    <i class="fa fa-search-plus fa-fw"></i>    
                                <?php elseif($jsons['system_range'] == 11):?>
                                    <i class="fa fa-sitemap fa-fw"></i>    
                                <?php elseif($jsons['system_range'] == 12):?>
                                    <i class="fa fa-user fa-fw"></i>                                                                                                           
                                <?php endif; ?>

                                <span class="fa arrow"></span><?= $jsons["system_name"] ?></a>

                                    <?php foreach($power_session_jsons as $pwoer_jsons): ?>
                                        <?php if ($jsons["system_number"] == $pwoer_jsons["power_master_number"] && $pwoer_jsons["power_master_number"] !='' && $pwoer_jsons["power_display"] == 1): ?>
                                            <ul class="nav">
                                                <li class='collapse'>

                                                    <a href="<?php echo Yii::app()->createUrl($pwoer_jsons["power_controller"]); ?>"><?= $pwoer_jsons["power_name"] ?></a>
                                                </li>
                                            </ul>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>

                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            <?= $content ?>
        </div>
    </div>
    <script>

        function openMenu() {
            //get url controller
            //var li = $('#menu li');
            //li.addClass('active');
            //li.find('ul').height('').addClass('in');
        }

		$(function() {
			$('#menu').metisMenu({
				toggle : false
			});

            openMenu();

		});
    </script>
</body>
</html>
