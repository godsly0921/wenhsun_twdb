<div id="banner" class="row"></div>

<div class="container">
    <div id="title" class="text-center">
        <h3>購買記錄</h3>
    </div>
    <div id="table" class="row">
        <table class="col-sm-12">
            <tbody>
                <tr>
                    <td class="title">
                        <a href="<?php echo Yii::app()->createUrl('site/my_account'); ?>">我的帳戶</a>
                    </td>
                    <td class="title">
                        <a href="<?php echo Yii::app()->createUrl('site/my_points'); ?>" class="small">我的點數與下載</a>
                    </td>
                    <td class="title">
                        <a href="<?php echo Yii::app()->createUrl('site/my_favorite'); ?>" class="active">購買記錄</a>
                    </td>
                    <td class="title">
                        <a href="<?php echo Yii::app()->createUrl('site/my_favorite'); ?>">我的收藏</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr class="top">
    <div id="record1" style="padding-bottom: 30px">
        <p class="record-title">最近購買記錄</p>
        <table class="record col-sm-12">
            <thead>
                <tr class="header">
                    <th width="25%">產品編號</th>
                    <th width="40%">產品名稱</th>
                    <th class="text-center" width="25%">產品訂價</th>
                </tr>
            </thead>
            <tbody>
                <tr class="tr-record">
                    <td>3</td>
                    <td>自由載 90 天 ( 50 張 )</td>
                    <td class="text-center">5000.00</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="record2" style="padding-bottom: 50px">
        <p class="record-title">先前購買項目</p>
        <table class="record col-sm-12">
            <thead>
                <tr class="header">
                    <th width="25%">產品編號</th>
                    <th width="40%">產品名稱</th>
                    <th class="text-center" width="25%">產品訂價</th>
                </tr>
            </thead>
            <tbody>
                <tr class="tr-record">
                    <td>3</td>
                    <td>自由載 90 天 ( 50 張 )</td>
                    <td class="text-center">5000.00</td>
                </tr>
            </tbody>
        </table>
        <table class="record col-sm-12">
            <thead>
                <tr class="header">
                    <th width="25%">產品編號</th>
                    <th width="40%">產品名稱</th>
                    <th class="text-center" width="25%">產品訂價</th>
                </tr>
            </thead>
            <tbody>
                <tr class="tr-record">
                    <td>2</td>
                    <td>點數 (10點 )</td>
                    <td class="text-center">300.00</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<style type="text/css">
    #title {
        padding-top: 25px;
        padding-bottom: 16px;
    }

    td.title {
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
        color: #7d7d7d;
    }

    td a.active {
        font-size: 23px;
        color: #dc5514;
    }

    hr.top {
        border: 1px solid black;
        margin-bottom: 50px;
    }

    .record-title {
        font-size: 23px;
    }

    .tr-record {
        color: #7d7d7d;
        font-size: 23px;
    }

    thead {
        color: #7d7d7d;
        font-size: 23px;
    }

    tr.header {
        border-bottom: 1px solid #7d7d7d;
    }

    table.record {
        margin-bottom: 50px;
    }

    tr.header > th {
        padding-bottom: 10px;
    }

    tr.tr-record > td {
        padding-top: 10px;
    }
</style>
