<!DOCTYPE html>
<html lang="<?php echo Yii::app()->language;?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title><?=Yii::t('messages', 'title')?></title>
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/site/css/invlayout_indem.css">
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap/js/bootstrap.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/site/ext/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/site/ext/responsive-nav/dist/responsive-nav.min.js"></script>

    <script> 
    $(document).ready(function(){
        $("#pmswitch").click(function(){
            $("#ppanel").slideToggle("slow");
        });
    });
    </script>

</head>
<body>

<div class="container-fluid">


    
    <!-- HEAD 樣式 -->
    
    <div class="row"> 
        <!-- 手機版上方menu-->
        <div id='pmenu' class="col-md-12 col-sm-12 col-xs-12">
            <div class='col-md-0 col-md-offset-0 col-sm-0 col-sm-offset-0 col-xs-1 col-xs-offset-10'>
                <img id='pmswitch' src="<?php echo Yii::app()->request->baseUrl; ?>/assets/site/images/menubar.png">
            </div>
       
        </div>
            
        <div id="ppanel" class='col-md-0 col-md-offset-0 col-sm-0 col-sm-offset-0 col-xs-12 col-xs-offset-0'>
                
        </div> 
        <div id='headimg' class="col-md-12 col-sm-12 col-xs-12">
            <img id='pbanner' src="<?php echo Yii::app()->request->baseUrl; ?>/assets/site/images/pbanner.png" width='100%'>

            <img id='tbn' src="<?php echo Yii::app()->request->baseUrl; ?>/assets/site/images/tbn.jpg" width='100%'>
        </div>



    <?= $content ?>



        <div id="footer" class='col-md-12 col-sm-12 col-xs-12'>
            <div class='col-md-12 col-sm-12 col-xs-12'>
                <div id='iconbox' class='col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2 text-center'>
                    <div>
                        <img src="<?=Yii::app()->request->baseUrl; ?>/assets/site/images/icon3.png" width='40px' height='38px;'>


                        <img src="<?=Yii::app()->request->baseUrl; ?>/assets/site/images/icon2.png">                        
                  
   
                        <img src="<?=Yii::app()->request->baseUrl; ?>/assets/site/images/icon1.png">                        
                    </div>                    
                    
                </div>

                <div id='textbox' class='col-md-2 col-md-offset-5 col-sm-12 col-sm-offset-0 col-xs-12 col-xs-offset-0'>
                </div>                
            </div>
        </div>

    </div>
    
</div>
<script>
    $(document).ready(function(){
        var nav = responsiveNav(".nav-collapse", {customToggle: "#toggle"});

        document.oncontextmenu = new Function("return false");
    });
</script>
</body>
</html>