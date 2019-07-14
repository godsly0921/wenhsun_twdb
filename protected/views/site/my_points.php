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
                        <a href="#">我的帳戶</a>
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
                剩餘&nbsp;3&nbsp;點
            </div>
            <div class="col-sm-3">
                <button type="button" class="btn add-point col-sm-12">
                    <i class="fa fa-plus">&nbsp;添加點數</i>
                </button>
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
                剩餘&nbsp;56&nbsp;天
            </div>
            <div class="col-sm-3">
                <button type="button" class="btn add-point col-sm-12">
                    <i class="fa fa-plus">&nbsp;購買天數</i>
                </button>
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
                <img src="<?= Yii::app()->request->baseUrl . '/assets/image/point_icon.png' ?>">&nbsp;60天自由載
            </div>
            <div class="col-sm-2 plan">
                無
            </div>
            <div class="col-sm-3">
                <button type="button" class="btn add-point col-sm-12">
                    <i class="fa fa-plus">&nbsp;購買天數</i>
                </button>
            </div>
        </div>
    </div>
    <hr class="bottom">
    <div id="download">
        <p class="download-title">下載記錄</p>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-sm-2">
                <img src="<?= Yii::app()->request->baseUrl . '/assets/image/demo1.png' ?>" class="download">
                <button class="btn download">重新下載</button>
            </div>
            <div class="col-sm-2"></div>
            <div class="col-sm-2">
                <img src="<?= Yii::app()->request->baseUrl . '/assets/image/demo2.png' ?>" class="download">
                <button class="btn download">重新下載</button>
            </div>
            <div class="col-sm-2"></div>
            <div class="col-sm-2">
                <img src="<?= Yii::app()->request->baseUrl . '/assets/image/demo3.png' ?>" class="download">
                <button class="btn download">重新下載</button>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
    #title {
        padding-top: 25px;
        padding-bottom: 16px;
    }

    td {
        text-align: center;
        border-left: solid 2px;
        border-right: solid 2px;
        border-color: black;
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
        border: 1px solid black;
        margin-top: 30px;
        margin-bottom: 30px;
    }

    hr.top {
        border: 1px solid black;
        margin-bottom: 66px;
    }

    hr.bottom {
        border: 1px solid black;
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