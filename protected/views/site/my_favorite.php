<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/justifiedGallery.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/jquery.fancybox.min.css">
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
    .caption.caption-visible{
        opacity: 0.6 !important;
    }
</style>
<div id="banner"></div>

<div class="container">
    <div id="title" class="text-center">
        <h3>我的收藏</h3>
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
                        <a href="<?php echo Yii::app()->createUrl('site/my_favorite'); ?>" class="active">我的收藏</a>
                    </td>
                </tr>
            </thead>
        </table>
    </div>
    <div id="download">
        <div class="row">
            <div class="col-lg-12 text-center" id="image_result">
                <?php foreach ($data as $key => $value) {?>
                    <div id="favorite_<?=$value['single_id']?>" onclick="open_image_info('<?=$value['single_id']?>')" style="cursor:pointer;">
                        <img  class="download" src="<?= Yii::app()->createUrl('/'). "/" .PHOTOGRAPH_STORAGE_URL . $value['single_id']?>.jpg">
                        <div class="caption">
                            <span class="btn btn-dark btn-sm fa fa-window-close-o remove_favorite" onclick="remove_favorite('<?=$value['single_id']?>')" style="font-size: 24px;color:white;line-height: 24px;"></span>
                        </div>
                    </div>
                <?php }?>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.fancybox.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/jquery.justifiedGallery.min.js"></script>
<script type="text/javascript">
    function remove_favorite(single_id){
        <?php if (Yii::app() -> user -> isGuest){
            Yii::app()->user->returnUrl = Yii::app()->request->urlReferrer;
        ?>
            localStorage.setItem("remove_favorite",single_id);
            parent.location.href="<?=Yii::app()->createUrl('site/login')?>";
        <?php }else{?>
            $.ajax({  
                url: "<?php echo Yii::app()->createUrl('site/remove_favorite')?>",  
                type: "post",  
                dataType: "json",  
                data: {
                    single_id: single_id,
                }, 
                success: function(data) {
                    if(!data.status){
                        $.fancybox.open('<div class="alert alert-danger"><h4>加入失敗，請在試一次</h4></p></div>');
                    }
                    if(data.status){
                        $('#favorite_'+single_id).remove();
                        rejustifiedGallery_init();
                        return;
                    }
                }  
            });
        <?php }?>
    }
    function open_image_info(single_id){
        $.fancybox.open({
            type: 'iframe',
            src: '<?= Yii::app()->createUrl('site/ImageInfo');?>/'+single_id,
            toolbar  : false,
            smallBtn : true,
            iframe : {
                preload : true,
                css : {
                    width : '90%',
                    height: '90%'
                }
            }
        });
    }
    function rejustifiedGallery_init(){
        $('#image_result').justifiedGallery({
            rowHeight: 200,
            maxRowHeight: 200,
            margins : 25,
            // refreshTime: 1000,
            rel : 'gallery1',
        });
    }
    $(document).ready( function() {
        rejustifiedGallery_init();
        if (localStorage.getItem("single_id") != null) {
            open_image_info(localStorage.getItem("single_id"));
            localStorage.removeItem("single_id");
        }
        $('.remove_favorite').click(function(e){ e.stopPropagation(); });
    });
</script>