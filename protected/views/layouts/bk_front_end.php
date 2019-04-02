<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language;?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?=Yii::t('messages', 'title')?></title>
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/site/ext/responsive-nav/dist/styles/responsive-nav.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/site/css/main.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/site/css/main2.css">
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/site/ext/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/site/ext/responsive-nav/dist/responsive-nav.min.js"></script>
</head>
<body>
<div class="container">
<div class="wrapper">
    <header>
<!--        <div class="lang-wrap">-->
<!--            <a href="http://--><?//=$this->languageUrls['zh-tw']?><!--">繁體中文</a>-->
<!--            <a href="http://--><?//=$this->languageUrls['zh-cn']?><!--">简体中文</a>-->
<!--        </div>-->
<!--        <div class="logo-wrap">-->
<!--            <h1 class="logo">-->
<!--                <a href="/"><img class="logo-lg" src="--><?php //echo Yii::app()->request->baseUrl; ?><!--/assets/site/images/comman/gjftamc_logo.png" alt="格捷Logo" /></a>-->
<!--            </h1>-->
<!--            <a href="#nav" id="toggle" class="hamburger-icon" aria-hidden="false" class="active"></a>-->
<!--            <nav class="none-res-nav">-->
<!--                <ul>-->
<!--                    --><?php //foreach(Yii::t('messages', 'nav') as $nav):?>
<!--                        <li><a href="--><?//=Yii::app()->request->baseUrl.$nav['url']?><!--">--><?//=$nav['name']?><!--</a></li>-->
<!--                    --><?php //endforeach;?>
<!--                </ul>-->
<!--            </nav>-->
            <!--<a class="login-btn" href="<?#= Yii::app()->request->baseUrl ?>/admin/login"><?#=Yii::t('messages', 'loginBtn')?></a>-->
<!--        </div>-->

<!--        <nav class="nav-collapse">-->
<!--            <ul>-->
<!--                --><?php //foreach(Yii::t('messages', 'nav') as $nav):?>
<!--                    <li><a href="--><?//=Yii::app()->request->baseUrl.$nav['url']?><!--">--><?//=$nav['name']?><!--</a></li>-->
<!--                --><?php //endforeach;?>
<!--            </ul>-->
<!--        </nav>-->
    </header>

    <?= $content ?>

</div>
<footer>
<!--    <nav class="footer-nav">-->
<!--        <ul>-->
<!--            --><?php //foreach(Yii::t('messages', 'nav') as $nav):?>
<!--                <li><a href="--><?//=Yii::app()->request->baseUrl.$nav['url']?><!--">--><?//=$nav['name']?><!--</a></li>-->
<!--            --><?php //endforeach;?>
<!--        </ul>-->
<!--    </nav>-->
    <div class="footer-content">
        <p class="copy-right">
            <?=Yii::t('messages', 'footer')['copyright']?>
        </p>
        <p class="address">
            <?=Yii::t('messages', 'footer')['address']?>
        </p>
    </div>
</footer>
</div>
<script>
    $(document).ready(function(){
        var nav = responsiveNav(".nav-collapse", {customToggle: "#toggle"});

        document.oncontextmenu = new Function("return false");
    });
</script>
</body>
</html>