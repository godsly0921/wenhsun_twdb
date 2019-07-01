<div id="banner" class="row"></div>
<div class="container">
    <div id="news" class="text-center">
        <h3>最新消息</h3>
    </div>
    <hr>
    <div class="content">
        <div class="second-title">
            <?= $news->second_title ?>
        </div>
        <div class="title">
            <?= $news->title ?>
        </div>
        <div class="content1">
            <?= nl2br($news->content) ?>
        </div>
        <div class="image text-center">
            <img src="<?= Yii::app()->request->baseUrl . $news->image ?>" class="img-rounded">
        </div>
        <div class="main-content">
            <?= nl2br($news->main_content) ?>
        </div>
        <div class="text-center">
        <a href="javascript: history.back();">->返回上頁</a>
        </div>
        <br>
    </div>
</div>
<style type="text/css">
    hr {
        border: 1px solid black;
    }

    .container {
        padding-top: 25px;
    }

    .title {
        font-size: 27px;
        color: #dc5514;
        padding-bottom: 16px;
    }

    .second-title {
        font-size: 25px;
        color: #a19d9d;
    }

    img {
        padding-top: 16px;
        padding-bottom: 16px;
    }

    .main-content {
        padding-bottom: 16px;
    }
</style>
