<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>文訊雜誌社人資管理系統</title>
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/nprogress/nprogress.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap-select/dist/css/bootstrap-select.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/build/css/custom.min.css" rel="stylesheet">
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/parsley/parsley.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/build/js/i18n/zh_tw.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap-select/dist/js/bootstrap-select.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/moment/moment.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
</head>
<style>
    .lmask {
        text-align: center;
        color: #FFF;
        padding: 150px;
        position: absolute;
        height: 100%;
        width: 100%;
        background-color: #000;
        bottom: 0;
        left: 0;
        right: 0;
        top: 0;
        z-index: 9999;;
        opacity: 0.4;
    }
</style>
<body class="nav-md">
    <div class='lmask' style="display: none;">資料處理中...</div>
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                <div class="left_col scroll-view">
                    <div class="navbar nav_title" style="border: 0;">
                        <a href="<?php echo Yii::app()->createUrl('news/list'); ?>" class="site_title"><span>文訊雜誌社人資系統</span></a>
                    </div>

                    <div class="clearfix"></div>

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                        <div class="menu_section">
                            <ul class="nav side-menu">

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
                                                <i class="fa fa-users fa-fw"></i>
                                            <?php elseif($jsons['system_range'] == 1):?>
                                                <i class="fa fa-lock fa-fw"></i>
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
                                                <i class="fa fa-image fa-fw"></i>
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
                                            <?= $jsons["system_name"] ?><span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                            <?php foreach($power_session_jsons as $pwoer_jsons): ?>
                                                <?php if ($jsons["system_number"] == $pwoer_jsons["power_master_number"] && $pwoer_jsons["power_master_number"] !='' && $pwoer_jsons["power_display"] == 1): ?>
                                                <li class='collapse'>
                                                    <a href="<?php echo Yii::app()->createUrl($pwoer_jsons["power_controller"]); ?>"><?= $pwoer_jsons["power_name"] ?></a>
                                                </li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                            </ul>
                                        </li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <!-- /sidebar menu -->
                </div>
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <div class="nav_menu">
                    <nav>
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>

                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    Hello, <?php echo Yii::app()->session['pid']?>
                                    <span class=" fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li><a href="<?php echo Yii::app() -> createUrl('admin/logout'); ?>"><i class="fa fa-sign-out pull-right"></i> </i> 登出</a>
                                </ul>
                            </li>


                        </ul>
                    </nav>
                </div>
            </div>

            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col">
                <?= $content ?>
            </div>
            <!-- /page content -->
        </div>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/fastclick/lib/fastclick.js"></script>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/nprogress/nprogress.js"></script>
       <!-- <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/build/js/custom.min.js"></script>-->
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/build/js/custom.js"></script>
</body>