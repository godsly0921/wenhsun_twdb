<div id="banner" class="row"></div>
<div class="container">
    <div id="news" class="text-center">
        <h3>最新消息</h3>
    </div>
    <hr>
    <div class="content">
        <?php foreach ($news as $content) { ?>
            <?php if ($content['count'] % 2 == 0) : ?>
                <div class="row">
                <?php endif; ?>
                <div class="col-sm-6">
                    <?php if ($content['title'] != '') : ?>
                    <div class="text-center image">
                        <img src="<?= Yii::app()->request->baseUrl . $content['image'] ?>" class="img-rounded">
                    </div>
                    <div class="title">
                        <a href="<?= Yii::app()->createUrl('site/news_detail/' . $content['id']) ?>"><?= $content['title'] ?></a>
                    </div>
                    <div class="second-title">
                        <?= $content['second_title'] ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php if ($content['count'] % 2 == 1) : ?>
                </div>
            <?php endif; ?>
        <?php } ?>
        <div class="text-center">
            <div class="pagination">
                <?php for ($i = 1; $i <= $page; $i++) { ?>
                <a href="<?= Yii::app()->createUrl('site/news?pageCount=' . $i) ?>" <?php if($pageCount == $i): ?>class="active"<?php endif; ?>><?= $i ?></a>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    hr {
        border: 1px solid black;
    }

    .container {
        padding-top: 25px;
    }

    img {
        width: 100%;
    }

    .title {
        padding-top: 16px;
    }

    .title a {
        font-size: 27px;
        color: #dc5514;
    }

    .second-title {
        font-size: 25px;
        color: #a19d9d;
        margin-bottom: 16px;
    }

    .paging {
        padding-top: 16px;
        padding-bottom: 16px;
    }

    .paging-underline {
        border-bottom: 1px solid;
    }

    .pagination {
        display: inline-block;
        padding-top: 16px;
        padding-bottom: 16px;
    }

    .pagination a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
        transition: background-color .3s;
        border: 1px solid #ddd;
    }

    .pagination a.active {
        background-color: #3f2211;
        color: white;
        border: 1px solid #3f2211;
    }

    .content {
        padding-top: 16px;
    }
</style>
