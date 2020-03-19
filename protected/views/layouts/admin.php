<?php
$configService = new ConfigService();
$config = $configService->findByConfigName('title');
?>
<!DOCTYPE html>
<html lang="zh-tw">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$config[0]['config_value']?></title>
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/build/css/custom.min.css" rel="stylesheet">
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/jquery/dist/jquery.min.js"></script>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/validator/validator.js"></script>
</head>
<?= $content ?>
</html>
