<style type="text/css">
    .justify{
        width: 100%;
        display: inline-block;
        text-align: justify;
        text-justify: distribute-all-lines;
        text-align-last: justify;
    }
    .order_row{
        margin-bottom: 80px;
    }
    .table_row{
        border-bottom: 1px solid #dc5514;
    }
    .main_title_row{
        margin-bottom: 45px;
    }
    .main_title{
        font-size: 40px;
        color: #351d0f;
    }
    
    #banner {
        padding-bottom: 85px;
    }

    .shadow {
        box-shadow: 8px 8px 3px rgb(0, 0, 0, 0.6);
        background-color: #ece6d7;
    }

    .table_text{
        color: #dc5514;
        font-size: 24px;
    }
    .form-control,.form-control:focus{
        border-color: #c8c5be;
        background-color: transparent;
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
</style>
<div id="banner" class="row"></div>
<div class="container">
    <form role="form" class="col-lg-12" action="<?php echo Yii::app()->createUrl('site/send_order'); ?>" method="post" accept-charset="utf-8">
        <input type="hidden" name="product_id" value="<?=$data->product_id ?>">
        <div class="text-center">
            <h3 class="main_title main_title_row">確認購買資訊</h3>
        </div>
        <div class="row col-lg-12 shadow order_row">
            <table class="col-lg-8 mx-auto my-5 table_text" cellpadding="15">
                <tr class="table_row">
                    <th>名稱</th><th>數量</th><th>金額</th>
                </tr>
                <tr class="table_row">
                    <td><?=$data->product_name ?></td><td></td><td>$ <?=$data->price ?></td>
                </tr>
                <tr class="table_row">
                    <td>金額小計</td><td></td><td>$ <?= $data->price ?></td>
                </tr>
            </table>
        </div>
        <div class="text-center">
            <h3 class="main_title main_title_row">選擇付款方式</h3>
        </div>
        <div class="row col-lg-12 shadow order_row">
            <div class="mb-4 text-center col-lg-12 mt-4">
                <label class="table_text"><input type="radio" name="pay_method" value="1" checked> 綠界線上支付 </label>
            </div>
            <div class="mb-4 text-center col-lg-12">
                <label class="table_text"><input type="radio" name="pay_method" value="2"> 土地銀行線上支付 </label>
            </div>
        </div>
        <div class="text-center">
            <h3 class="main_title main_title_row">發票資訊</h3>
        </div>
        <div class="row col-lg-12 text-center order_row">
            <div class="mb-4 text-center col-lg-12">
                <label class="table_text"><input type="radio" name="invoice_category" value="0" checked> 發票捐贈，捐贈對象：</label>
            </div>
            <div class="mb-4 text-center col-lg-12">
                <label class="table_text"><input type="radio" name="invoice_category" value="1"> 二聯式統一發票</label>
            </div>
            <div class="mb-4 text-center col-lg-12">
                <label class="table_text"><input type="radio" name="invoice_category" value="2"> 三聯式統一發票</label>
            </div>
        </div>
        <div class="text-center">
            <h3 class="main_title main_title_row">發票寄送地址與聯繫資料</h3>
        </div>
        <div class="row col-lg-12 order_row">
            <div class="form-group row col-lg-10 mx-auto justify-content-around">
                <label for="phone" class="col-sm-3 col-form-label justify">行動電話</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" name="mobile" value="<?= $memberaddressbook['mobile'] ?>" placeholder="請輸入行動電話" value="">
                </div>
            </div>
        
            <div class="form-group row col-lg-10 mx-auto justify-content-around">
                <label for="nationality" class="col-sm-3 col-form-label justify">國別</label>
                <div class="col-sm-7">
                    <select class="selectpicker countrypicker form-control" id="nationality" name="nationality" data-default="<?= $memberaddressbook['nationality'] ?>" value="<?= $memberaddressbook['nationality'] ?>"></select>
                </div>
            </div>

            <div class="form-group row col-lg-10 mx-auto justify-content-around">
                <label for="county" class="col-sm-3 col-form-label justify">縣市</label>
                <div class="col-sm-7">
                    <div id="twzipcode" class="col-form-label"></div>
                </div>
            </div>

            <div class="form-group row col-lg-10 mx-auto justify-content-around">
                <label for="address" class="col-sm-3 col-form-label justify">地址</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="address" name="address" placeholder="請輸入詳細地址" value="<?= $memberaddressbook['address'] ?>">
                </div>
            </div>
            <div class="form-group row col-lg-10 mx-auto justify-content-around">
                <label for="address" class="col-sm-3 col-form-label justify">統編</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="invoice_number" name="invoice_number" placeholder="請輸入統編" value="<?= $memberaddressbook['invoice_number'] ?>">
                </div>
            </div>
            <div class="form-group row col-lg-10 mx-auto justify-content-around">
                <label for="address" class="col-sm-3 col-form-label justify">發票抬頭</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="invoice_title" name="invoice_title" placeholder="請輸入發票抬頭" value="<?= $memberaddressbook['invoice_title'] ?>">
                </div>
            </div>
        </div>
        <div class="button-div text-center order_row">
            <button type="submit" class="purchase">確認送出</button>
        </div>
    </form>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/js/twzipcode.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/bootstrap-select-country/dist/js/bootstrap-select-country.min.js"></script>
<script type="text/javascript">
    $(document).ready( function() {
        $('#twzipcode').twzipcode({
            zipcodeIntoDistrict: true,
            css: ["county form-control d-inline-block w-auto mr-2", "town form-control d-inline-block w-auto"],
            countyName: "county",
            districtName: "town"
        });
        $("#twzipcode").twzipcode("set", {
            "county": "<?php echo $memberaddressbook['country']; ?>",
            "district": "<?php echo $memberaddressbook['town']; ?>"
        });
        $('input[type=radio][name=invoice_category]').change(function() {
            if (this.value == '2') {
                $("#invoice_number").attr("required", true);
                $("#invoice_title").attr("required", true);
                $('#address').attr("required", true);
            }else if(this.value == '1'){
                $('#address').attr("required", true);
                $("#invoice_number").attr("required", false);
                $("#invoice_title").attr("required", false);
            }else{
                $("#invoice_number").attr("required", false);
                $("#invoice_title").attr("required", false);
                $('#address').attr("required", false);
            }
        });
    })
</script>