<div id="banner" class="row"></div>
<div class="container">
    <div id="title" class="text-center">挑選適合您的方案</div>
    <div id="type">
        <div class="row">
            <div class="col-sm-4 text-center">
                <img src="<?= Yii::app()->request->baseUrl . '/assets/image/plan1.png' ?>" class="img-circle">
                <br>
                <p class="user">老師、學生、研究者</p>
                <p class="description">
                    可以使用本圖庫尺寸為 S﹙72dpi﹚<br>
                    或 M﹙96dpi﹚的尺寸。可用於書面<br>
                    報告、簡報檔或一般書面印刷使用。<br>
                    亦可用於網路文章配圖。
                </p>
            </div>
            <div class="col-sm-4 text-center">
                <img src="<?= Yii::app()->request->baseUrl . '/assets/image/plan2.png' ?>" class="img-circle">
                <br>
                <p class="user">出版社、媒體廣告</p>
                <p class="description">
                    可以使用本圖庫尺寸為 L﹙150dpi﹚<br>
                    或 XL﹙300dpi﹚的尺寸。可作圖書<br>
                    封面、內頁的印刷用，媒體廣告可依<br>
                    其使用性質，選用 XL 或更大的尺寸<br>
                    ﹙請直接接洽文訊﹚。<br>
                    *所有相關出版品、海報或實體的產<br>
                    出品，請提供三份供文訊留存。
                </p>
            </div>
            <div class="col-sm-4 text-center">
                <img src="<?= Yii::app()->request->baseUrl . '/assets/image/plan3.png' ?>" class="img-circle">
                <br>
                <p class="user">企業、活動</p>
                <p class="description">
                    請直接接洽文訊，我們將依使用範<br>
                    圍、性質、曝光度另外報價。
                </p>
            </div>
        </div>
    </div>
    <hr>
    <div id="title2" class="text-center">方案介紹</div>
    <div id="option">
        <div class="plan col-sm-12 row">
            <div class="col-sm-6">
                <form method='GET' class='form-horizontal' action='<?= Yii::app()->createUrl('site/check_order');?>'>
                    <div class="shadow">
                        <div class="header">
                            <div class="header-div col-sm-12">
                                <p class="header-title text-center">點數</p>
                                <p class="header-description text-center">依消費者需求購買，一點100元</p>
                                <p class="header-description text-center">適合單張和少量購買的您</p>
                            </div>
                        </div>
                        <?php if($data){
                            $checked = true;
                            ?>
                            <?php foreach ($data as $key => $value) {?>
                                <?php if($value['product_type'] == 1){?>
                                    <div class="strip">
                                        <div class="padding-radio col-sm-12 row">
                                            <div class="col-sm-2"></div>
                                            <div class="col-sm-7">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="product_id" value="<?=$value['product_id']?>" <?=$checked?"checked":""?>>
                                                        <?= $value['product_name'] ?> ( <?=$product_type[$value['product_type']] . " " . $value['pic_point']?> 點 ) 
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <p class="point"><?=$value['price']?> 元</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php $checked=false;}?>
                            <?php }?>
                        <?php }?>
                    </div>
                    <?php if($data){?>
                        <?php foreach ($data as $key => $value) {?>
                            <?php if($value['product_type'] == 1){?>
                                <div class="button-div text-center">
                                    <button type="submit" class="purchase">立即購買</button>
                                </div>
                            <?php break;}?>
                        <?php }?>
                    <?php }?>
                </form>
            </div>
            <div class="col-sm-6">
                <form method='GET' class='form-horizontal' action='<?= Yii::app()->createUrl('site/check_order');?>'>
                    <div class="shadow">
                        <div class="header">
                            <div class="header-div col-sm-12">
                                <p class="header-title text-center">自由載</p>
                                <p class="header-description text-center">不限年代，隨時取得圖片，讓您彈性下載</p>
                                <p class="header-title2 text-center">最高省85%</p>
                            </div>
                        </div>
                        <?php if($data){
                            $checked = true;
                            ?>
                            <?php foreach ($data as $key => $value) {?>
                                <?php if($value['product_type'] != 1){?>
                                    <div class="strip">
                                        <div class="padding-radio col-sm-12 row">
                                            <div class="col-sm-1"></div>
                                            <div class="col-sm-7">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="product_id" value="<?=$value['product_id']?>" <?=$checked?"checked":""?>>
                                                        <?= $value['product_name'] ?> ( <?=$product_type[$value['product_type']] . " " . $value['pic_number']?> 張 ) 
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <p class="point"><?=$value['price']?> 元</p>
                                            </div>
                                        </div>
                                    </div>
                                <?php $checked=false;}?>
                            <?php }?>
                        <?php }?>   
                    </div>
                    <?php if($data){?>
                        <?php foreach ($data as $key => $value) {?>
                            <?php if($value['product_type'] != 1){?>
                                <div class="button-div text-center">
                                    <button type="submit" class="purchase">立即購買</button>
                                </div>
                            <?php break;}?>
                        <?php }?>
                    <?php }?>
                </form>
            </div>
        </div>
    </div>
    <hr>
    <div id="title2" class="text-center">尺寸指南</div>
    <div class="size">
        <div class="shadow1">
            <div class="strip_size_info">
                <div class="col-sm-12 row text-center padding-radio">
                    <div class="col-sm-2">尺寸</div>
                    <div class="col-sm-2">解析度</div>
                    <div class="col-sm-2">格式</div>
                    <div class="col-sm-6">用途</div>
                </div>
            </div>
            <div class="strip_size_info">
                <div class="col-sm-12 row text-center padding-radio">
                    <div class="col-sm-2 point">S</div>
                    <div class="col-sm-2 wording">72dpi</div>
                    <div class="col-sm-2 wording">JPG</div>
                    <div class="col-sm-6 point">適合於網路文章搭配</div>
                </div>
            </div>
            <div class="strip_size_info">
                <div class="col-sm-12 row text-center padding-radio">
                    <div class="col-sm-2 point">M</div>
                    <div class="col-sm-2 wording">96dpi</div>
                    <div class="col-sm-2 wording">JPG</div>
                    <div class="col-sm-6 point">適合於學術報告、簡報</div>
                </div>
            </div>
            <div class="strip_size_info">
                <div class="col-sm-12 row text-center padding-radio">
                    <div class="col-sm-2 point">L</div>
                    <div class="col-sm-2 wording">150dpi</div>
                    <div class="col-sm-2 wording">JPG</div>
                    <div class="col-sm-6 point">適合於網站廣告</div>
                </div>
            </div>
            <div class="strip_size_info">
                <div class="col-sm-12 row text-center padding-radio">
                    <div class="col-sm-2 point">XL</div>
                    <div class="col-sm-2 wording">300dpi</div>
                    <div class="col-sm-2 wording">JPG、TIFF</div>
                    <div class="col-sm-6 point">適合於書刊、雜誌印刷出版、廣告海報或大圖輸出、展覽</div>
                </div>
            </div>
            <div class="strip_size_info">
                <div class="col-sm-12 row text-center padding-radio">
                    <div class="col-sm-12 point">
                        *商業用途、或需大批檔案下載、600dpi以上檔案者，需與文訊進一步確認用途等相關事宜，價格則由本公司依媒體、數量、地域、網路流量等做報價，並保留是否接受訂單之最後權利。
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    #title {
        padding-bottom: 80px;
        font-size: 48px;
    }

    #title2 {
        padding-top: 80px;
        padding-bottom: 45px;
        font-size: 48px;
    }

    .user {
        font-size: 20px;
        color: #dc5514;
        padding-top: 25px;
        padding-bottom: 25px;
    }

    .description {
        font-size: 12px;
        padding-bottom: 45px;
    }

    #banner {
        padding-bottom: 85px;
    }

    .shadow {
        box-shadow: 8px 8px 3px rgb(0, 0, 0, 0.6);
        background-color: white;
    }

    .shadow1 {
        box-shadow: 8px 8px 3px rgb(0, 0, 0, 0.3);
        background-color: #e3decd;
    }

    .header {
        height: 150px;

    }

    .header-title {
        font-size: 28px;
        color: #dc5514;

    }

    .header-title2 {
        font-size: 20px;
        color: #dc5514;

    }

    .header-div {
        padding-top: 15px;
    }

    .header-description {
        font-size: 12px;
    }
    .strip_size_info:nth-child(even){
        background-color: #e3decd;
        height: 80px;
    }
    .strip_size_info:nth-child(odd){
        height: 80px;
        background-color: #f8f5ec;
    }
    .strip:nth-child(even){
        background-color: #e3decd;
        height: 80px;
    }

    .strip:nth-child(odd){
        background-color: white;
        height: 80px;
    }

    .plan {
        padding-bottom: 30px;
    }

    .padding-radio {
        padding-top: 30px;
    }

    label {
        font-size: 12px;
    }

    .point {
        font-size: 12px;
        color: #dc5514;
    }

    .discount {
        font-size: 12px;
        color: #ab0303;
    }

    .purchase {
        background-color: #dc5514;
        border: none;
        color: white;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 28px;
        border-radius: 12px;
    }

    .button-div {
        margin-top: 35px;
        margin-bottom: 45px;
    }

    .strip3 {
        height: 80px;
        background-color: #f8f5ec;
    }

    .wording {
        font-size: 12px;
    }

    .size {
        padding-bottom: 100px;
    }
</style>
