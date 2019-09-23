<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/justifiedGallery.min.css">
<style type="text/css">
    #title {
        padding-top: 25px;
        padding-bottom: 16px;
        color: #6b4c2e;
    }

    td {
        text-align: center;
        border-left: solid 2px;
        border-right: solid 2px;
        border-color: #6b4c2e;
        font-size: 55px;
    }

    td a {
        font-size: 23px;
        color: #7d7d7d;
    }

    td a:hover {
        color: #7d7d7d;
    }

    td a.small {
        font-size: 16px;
        color: #dc5514;
    }

    hr.plan {
        border: 1px solid #6b4c2e;
        margin-top: 30px;
        margin-bottom: 30px;
    }

    hr.top {
        border: 1px solid #6b4c2e;
        margin-bottom: 66px;
    }

    hr.bottom {
        border: 1px solid #6b4c2e;
        margin-top: 66px;
        margin-bottom: 66px;
    }

    .plan {
        font-size: 23px;
        color: #dc5514;
    }

    button.add-point {
        background-color: #dc5514;
        color: white;
        font-size: 23px;
    }

    .download-title {
        font-size: 23px;
        padding-bottom: 30px;
        color: #dc5514;
    }

    img.download {
        height: 196px;
    }

    button.download {
        width: 196px;
        background-color: #7d7d7d;
        color: white;
        padding: 0, 0, 0, 0;
        border-radius: 0;
    }

    #download {
        padding-bottom: 30px;
    }
</style>
<div id="banner" class="row"></div>
<div class="container">
    <div id="title" class="text-center">
        <h3>會員專區</h3>
    </div>
    <div id="table" class="row">
        <table class="col-sm-12">
            <thead>
                <tr>
                    <td>
                        <a href="<?php echo Yii::app()->createUrl('site/my_account'); ?>">我的帳戶</a>
                    </td>
                    <td>
                        <a href="<?php echo Yii::app()->createUrl('site/my_points'); ?>" class="small">我的點數與下載</a>
                    </td>
                    <td>
                        <a href="<?php echo Yii::app()->createUrl('site/my_record'); ?>">購買記錄</a>
                    </td>
                    <td>
                        <a href="<?php echo Yii::app()->createUrl('site/my_favorite'); ?>">我的收藏</a>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
    <hr class="top">
    <div class="point">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-3 plan">
                <img src="<?= Yii::app()->request->baseUrl . '/assets/image/point_icon.png' ?>">&nbsp;點數方案
            </div>
            <div class="col-sm-2 plan">
                剩餘 <?= $member->active_point?> 點
            </div>
            <div class="col-sm-3">
                <a href="<?php echo Yii::app()->createUrl('site/plan'); ?>">
                    <button type="button" class="btn add-point col-sm-12">
                        <i class="fa fa-plus">&nbsp;添加點數</i>
                    </button>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <hr class="plan">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-3 plan">
                <img src="<?= Yii::app()->request->baseUrl . '/assets/image/point_icon.png' ?>">&nbsp;30天自由載
            </div>
            <div class="col-sm-2 plan">
                <?php if($member_plan['2'] != 0 ){?>
                    剩餘 <?= $member_plan['2']?> 張
                <?php }else{?>
                    無
                <?php }?>
            </div>
            <div class="col-sm-3">
                <a href="<?php echo Yii::app()->createUrl('site/plan'); ?>">
                    <button type="button" class="btn add-point col-sm-12">
                        <i class="fa fa-plus">&nbsp;購買方案</i>
                    </button>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <hr class="plan">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-3 plan">
                <img src="<?= Yii::app()->request->baseUrl . '/assets/image/point_icon.png' ?>">&nbsp;90天自由載
            </div>
            <div class="col-sm-2 plan">
                <?php if($member_plan['3'] != 0 ){?>
                    剩餘 <?= $member_plan['3']?> 張
                <?php }else{?>
                    無
                <?php }?>
            </div>
            <div class="col-sm-3">
                <a href="<?php echo Yii::app()->createUrl('site/plan'); ?>">
                    <button type="button" class="btn add-point col-sm-12">
                        <i class="fa fa-plus">&nbsp;購買方案</i>
                    </button>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <hr class="plan">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-3 plan">
                <img src="<?= Yii::app()->request->baseUrl . '/assets/image/point_icon.png' ?>">&nbsp;360天自由載
            </div>
            <div class="col-sm-2 plan">
                <?php if($member_plan['4'] != 0 ){?>
                    剩餘 <?= $member_plan['4']?> 張
                <?php }else{?>
                    無
                <?php }?>
            </div>
            <div class="col-sm-3">
                <a href="<?php echo Yii::app()->createUrl('site/plan'); ?>">
                    <button type="button" class="btn add-point col-sm-12">
                        <i class="fa fa-plus">&nbsp;購買方案</i>
                    </button>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <hr class="plan">
            </div>
        </div>
    </div>
    <hr class="bottom">
    <div id="download">
        <p class="download-title">下載記錄</p>
        <div class="row col-lg-12" id="image_result">
            <?php foreach ($image_download as $key => $value) {?>
                <div><img src="<?= Yii::app()->createUrl('/'). "/" .PHOTOGRAPH_STORAGE_URL . $value['single_id']?>.jpg"></div>
            <?php }?>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.justifiedGallery.min.js"></script>
<script type="text/javascript">
    $(document).ready( function() {
        $('#image_result').justifiedGallery({
            rowHeight: 200,
            maxRowHeight: 200,
            margins : 25,
            // refreshTime: 1000,
            rel : 'gallery1',
        });
    });
</script>
