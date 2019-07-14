<div id="banner" class="row"></div>

<div class="container">
    <div id="title" class="text-center">
        <h3>我的收藏</h3>
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
                        <a href="#">購買記錄</a>
                    </td>
                    <td>
                        <a href="<?php echo Yii::app()->createUrl('site/my_favorite'); ?>" class="active">我的收藏</a>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
    <div id="download">
        <div class="row">
            <div class="col-sm-4 text-center">
                <img src="<?= Yii::app()->request->baseUrl . '/assets/image/demo1.png' ?>" class="download">
                <button class="btn download">重新下載</button>
            </div>
            <div class="col-sm-4 text-center">
                <img src="<?= Yii::app()->request->baseUrl . '/assets/image/demo2.png' ?>" class="download">
                <button class="btn download">重新下載</button>
            </div>
            <div class="col-sm-4 text-center">
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
        border-color: #7d7d7d;
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
        color: #7d7d7d;
    }

    td a.active {
        font-size: 23px;
        color: #dc5514;
    }

    table {
        margin-bottom: 66px;
    }

    img.download {
        height: 196px;
    }

    button.download {
        width: 196px;
        background-color: #dc5514;
        color: white;
        padding: 0, 0, 0, 0;
        border-radius: 0;
    }

    #download {
        padding-bottom: 30px;
    }
</style>
