<!DOCTYPE html>
<html>
    <head>
        <link href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/normalize.css" rel="stylesheet">
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/gentelella/vendors/jquery/dist/jquery.min.js"></script>
    </head>
    <body>
        <style>
            .year_of_tw{
                width: 45%;
                display: inline-block;
                text-align: justify;
                text-justify: distribute-all-lines;
                text-align-last: justify;
            }
            .group_date{
                display: inline-block;
                position:relative;
                overflow:hidden;
                vertical-align: bottom;
            }
            .group_date.year,.group_date.day{
                width:20%;
            }
            .group_date.month{
                width:10%;
            }
            .group_date input{
                width: 70%;
                position:relative;
            }
            .group_date label{
                color: black;
                position: absolute;
                right: 0;
                top: 0;
            }
            .padding_left_35{
                padding-left: 35px;
            }
            .padding_left_2em{
                width: 150px;
                display: inline-block;
                padding-bottom: 5px;
                padding-top: 5px;
                margin: 0px;
                text-indent: 2em;
                text-align: justify;
                text-justify: distribute-all-lines;
                text-align-last: justify;
            }
            .ul_radio{
                padding-inline-start: 2em;
                margin: 0px;
            }
            .confidentiality_title{
                padding-left: 2em;
            }
            .confidentiality_title span{
                padding-right: 2em;
            }
            .paper-id {
                text-indent: 0em;
                font-size: 14px;
            }
            
            .small_label{
                font-size: 14px;
                font-weight: normal;
            }
            p{
                font-family:DFKai-sb,標楷體;
                text-indent: 4em;
                line-height: 24px;
            }
            input{
                font-family:DFKai-sb,標楷體;
                line-height: 24px;
            }
            span{
                font-family:DFKai-sb,標楷體;
                line-height: 24px;
            }
            ul.first_level{
                padding-inline-start: 0px;
            }
            ul.first_level li{
                font-family:DFKai-sb,標楷體;
                line-height: 24px;
                margin-left: 2em;
                text-indent: -2em;
            }
            ul.second_level li{
                font-family:DFKai-sb,標楷體;
                line-height: 24px;
                margin-left: .7em;
                text-indent: -2.7em;
            }
            ol.second_level li{
                font-family:DFKai-sb,標楷體;
                line-height: 24px;
                margin-left: -1em;
                text-indent: .1em;
                list-style: decimal !important;
                /*list-style-type:decimal;*/
            }
            .contract_personal ul li,.contract_personal ol li,.contract_personal p,.contract_personal span{
                line-height: 38px;
            }
            section.contract-wrapper {
                width: 60%;
                margin: 20px auto;
                border: 1px solid gray;
                padding: 5px 60px;
                box-sizing: border-box;
                line-height: normal;
            }
            section.contract-wrapper h1 {
                text-align: center;
            }
            section.contract-wrapper input {
                color: black;
                text-align: center;
                border: none;
                border-bottom: 1px solid black;
            }
            section.contract-wrapper li {
                list-style: none;
                /*margin: 10px 0*/
            }
            section.contract-wrapper span {
                margin: 0 0 0 3px
            }
            section.contract-wrapper input {
                outline: none;
                margin: 0 0 0 5px
            }
            section.contract-wrapper input[type="radio"] {
                -webkit-appearance: checkbox; /* Chrome, Safari, Opera */
                -moz-appearance: checkbox;    /* Firefox */
                -ms-appearance: checkbox;
            }
            section.contract-wrapper input.short-input {
                width: 60px;
                display: inline-block;
                border-bottom: 0px solid black;
                text-align: right;
            }
            section.contract-wrapper input.short-input.year,section.contract-wrapper input.short-input.day {
                display: inline-block;
                width: 15%;
                text-align: right;
                margin-left: 0px;
            }
            section.contract-wrapper input.short-input.month {
                display: inline-block;
                width: 10%;
                text-align: right;
                margin-left: 0px;
            }
            section.contract-wrapper input.long-input {
                width: 60%;
            }
            .print-btn {
                border: none;
                background: #FFF;
                position: fixed;
                right: 20px;
                top: 80px;
                cursor: pointer;
            }
            
            @media print {
                @page {
                    margin: 1.6cm !important;
                }
                body {
                    /*margin: 1.6cm !important;*/
                }
                .page-break  { display:block; page-break-before:always; }
                section.contract-wrapper {
                    padding: 0px;
                    width: 95%;
                    border: none;
                }
                .print-btn {
                    display: none;
                }
            }
        </style>
        <button class="print-btn" id="print-btn">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/assets/image/printer-button.png" alt="print button">
        </button>
        <section class="contract-wrapper page-break">
            <p class="paper-id">員工編號:<?=$data->id?></p>
            <h1>保密合約書</h1>
            <p class="padding_left_35">
                茲有
                <input type="radio" <?=$data->department == '文訊'?'checked':''?> name="confidentiality_type"><span>文訊</span>
                <input type="radio" <?=$data->department == '基金會'?'checked':''?> name="confidentiality_type"><span>基金會</span>
                <input type="radio" <?=$data->department == '紀州庵'?'checked':''?> name="confidentiality_type"><span>紀州庵</span>
                 ( 以下簡稱甲方 ) 因業務需要，委請 <input type="text" value="<?=$data->name?>" disabled> 君(以下簡稱乙方)於甲方公司所在地或其他指定地點提供服務，雙方同意遵守下列約定：
            </p>

            <ul class="first_level">
                <li>
                    一、本合約所稱之「機密資料」乃指，但不限於：
                    <ul class="second_level">
                        <li>(一)、甲方或甲方關係企業，在業務上蒐集或使用的任何型式之資料，包括但不限於與業務或研究開發有關之文書、圖稿、模型、軟體、磁片或流程等。</li>
                        <li>(二)、與甲方的工作有關，其成果尚不足以對外公布，且其所有權歸屬甲方或甲方關係企業所有者。</li>
                        <li>(三)、經甲方或甲方關係企業標示「機密」、「限閱」或其他同義字之一切商業上、技術上、或生產上尚未公開之秘密。</li>
                        <li>(四)、甲方依約或依據法令，對第三人負有保密責任之第三人之「機密資料」。</li>
                    </ul>
                </li>
                <li>二、本合約所稱「關係企業」包括由甲方直接或間接所有/經營之合法組織體。</li>
                <li>三、乙方對甲方所提供之「機密資料」，除了依甲方事前同意之方式使用外，不得作為其他用途。且未經甲方事前書面同意，乙方不得將甲方或甲方關係企業的「機密資料」透露或交予任何第三人(包括自然人、法人等)。</li>
                <li>四、乙方若違反前述條文，因而造成甲方或甲方關係企業任何損失時，乙方必須賠償甲方一切損失，並負擔全部法律責任。</li>
                <li>五、本契約自簽定之日起生效，且於乙方離職或不再受甲方委託之日起二年內仍繼續有效，本契約非經雙方書面同意，任一方不得任意終止。</li>
                <li>六、本契約未盡事宜悉依中華民國相關法律為準。若因本合約書約定事項涉訟時，雙方同意以台灣台北地方法院為第一審管轄法院。</li>
                <li>七、本契約一式兩份，由甲、乙方各執一份為憑。</li>
            </ul>

            <p class="padding_left_2em">甲方：</p>
            <ul class="ul_radio">
                <li><input type="radio" name="confidentiality_boss_company" checked><span>文訊雜誌社</span></li>
                <li><input type="radio" name="confidentiality_boss_company"><span>財團法人台灣文學發展基金會</span></li>
                <li><input type="radio" name="confidentiality_boss_company"><span>財團法人台灣文學發展基金會市定古蹟紀州庵及新館營運管理處</span></li>
            </ul>
            <p class="padding_left_2em">代表人：</p><span><?=$management_data[0]->name?></span>
            <div class="confidentiality_title">
                <input type="radio" checked name="confidentiality_boss_title"><span>社長</span>
                <input type="radio" name="confidentiality_boss_title"><span>董事長</span>
                <input type="radio" name="confidentiality_boss_title"><span>館長</span>
            </div>
            <div><p class="padding_left_2em">乙方：</p><input type="text" value="<?=$data->name?>"disabled ></div>
            <div><p class="padding_left_2em">身份證字號：</p><input type="text" value="<?=$data->person_id?>" disabled></div>
            <div><p class="padding_left_2em">戶籍地址：</p><input type="text" class="long-input" value="<?=$data->country?><?=$data->dist?><?=$data->address?>" disabled></div>
            <p style="max-width: 100%">
                <div class="year_of_tw">中華民國</div>
                <div class="group_date year">
                    <input type="text" value="<?=date("Y")-1911?>">
                    <label>年</label>
                </div>
                <div class="group_date month">
                    <input type="text" value="<?=date("m")?>">
                    <label>月</label>
                </div>
                <div class="group_date day">
                    <input type="text" value="<?=date("d")?>">
                    <label>日</label>
                </div>
            </p>
        </section>

        <section class="contract-wrapper page-break">
            <p class="paper-id">員工編號:<?=$data->id?></p>
            <h1>智慧財產權合約書</h1>
            <p class="padding_left_35">
                茲有
                <input type="radio" <?=$data->department == '文訊'?'checked':''?> name="right_intellectual_type"><span>文訊</span>
                <input type="radio" <?=$data->department == '基金會'?'checked':''?> name="right_intellectual_type"><span>基金會</span>
                <input type="radio" <?=$data->department == '紀州庵'?'checked':''?> name="right_intellectual_type"><span>紀州庵</span>
                (以下簡稱甲方)僱(聘)用 <input type="text" value="<?=$data->name?>" disabled> 君(以下簡稱乙方)提供服務，自乙方受甲方聘僱日起，關於智慧財產權之歸屬及附屬事項，雙方約定如下：
            </p>

            <ul class="first_level">
                <li>一、乙方因職務上或與職務上有關之著作，以甲方為著作人。</li>
                <li>二、乙方因職務上或與職務有關之發明，其專利權歸屬於甲方，新型或新式樣，亦適用前項約定。</li>
                <li>三、乙方為完成職務上或與職務有關之著作、發明(新型或新式樣)，不得侵害第三人之商標、著作權或專利權等智慧財產權，如有違反，致甲方受到損害，應賠償甲方因此所受之一切損失，並承擔全部法律責任。但侵害非乙方所能明知者或乙方已於事前經該第三人之書面同意者，不在此限。</li>
                <li>四、本契約未盡事宜悉依中華民國相關法律為準。若因本合約書約定事項涉訟時，雙方同意以台灣台北地方法院為第一審管轄法院。</li>
                <li>五、本契約一式兩份，由甲、乙方各執一份為憑。</li>
            </ul>
            <br/><br/>
            <p class="padding_left_2em">甲方：</p>
            <ul class="ul_radio">
                <li><input type="radio" name="right_confidentiality_company" checked><span>文訊雜誌社</span></li>
                <li><input type="radio" name="right_confidentiality_company"><span>財團法人台灣文學發展基金會</span></li>
                <li><input type="radio" name="right_confidentiality_company"><span>財團法人台灣文學發展基金會市定古蹟紀州庵及新館營運管理處</span></li>
            </ul>
            <p class="padding_left_2em">代表人：</p><span><?=$management_data[0]->name?></span>
            <div class="confidentiality_title">
                <input type="radio" name="confidentiality_title" checked><span>社長</span>
                <input type="radio" name="confidentiality_title"><span>董事長</span>
                <input type="radio" name="confidentiality_title"><span>館長</span>
            </div>
            <div><p class="padding_left_2em">乙方：</p><input type="text" value="<?=$data->name?>"disabled></div>
            <div><p class="padding_left_2em">身份證字號：</p><input type="text" value="<?=$data->person_id?>" disabled></div>
            <div><p class="padding_left_2em">戶籍地址：</p><input type="text" class="long-input" value="<?=$data->country?><?=$data->dist?><?=$data->address?>" disabled></div>
            <p style="max-width: 100%">
                <div class="year_of_tw">中華民國</div>
                <div class="group_date year">
                    <input type="text" value="<?=date("Y")-1911?>">
                    <label>年</label>
                </div>
                <div class="group_date month">
                    <input type="text" value="<?=date("m")?>">
                    <label>月</label>
                </div>
                <div class="group_date day">
                    <input type="text" value="<?=date("d")?>">
                    <label>日</label>
                </div>
            </p>
        </section>

        
        <section class="contract-wrapper page-break contract_personal">
            <p class="paper-id">員工編號:<?=$data->id?></p>
            <h1>保密契約書<span class="small_label">「個人資料保護法」</span></h1>
            <p class="padding_left_35">
                茲有
                <input type="radio" <?=$data->department == '文訊'?'checked':''?> name="private_type"><span>文訊</span>
                <input type="radio" <?=$data->department == '基金會'?'checked':''?> name="private_type"><span>基金會</span>
                <input type="radio" <?=$data->department == '紀州庵'?'checked':''?> name="private_type"><span>紀州庵</span>
                (以下共稱甲方)因業務需要，委請 <input type="text" value="<?=$data->name?>" disabled> 君(以下簡稱乙方)於甲方公司所在地或甲方我指定之地點提供服務，乙方向甲方承諾遵守下列事項：
            </p>
            <ul class="first_level">
                <li>一、遵循個人資料保護法相關規定，非經甲方事前書面同意，不得以口頭、影印、借閱、交付或以任何形式將乙方所知悉或持有之個人資料洩露或交付第三人(包括自然人、法人等)，並嚴守保密義務。</li>
                <li>二、依個人資料保護法規定於蒐集、處理或利用個人資料時應受甲方適當之監督。</li>
                <li>三、乙方僅得於甲方指示之範圍內，蒐集、處理或利用個人資料。乙方於執行甲方之指示時，知悉有違反個人資料保護法或相關法令、法規之情事，應立即以書面向甲方通知個人資料被侵害之事實及事故原因，以及已採取之因應措施。</li>
                <li>四、如乙方違反前述條文，或可歸責於乙方之疏忽致洩漏個人資料予非甲方或甲方關係企業之第三者，因而造成甲方或甲方關係企業任何損失時，乙方必須賠償甲方一切損失，並負擔全部法律責任，包括因此所致甲方或第三人涉訟，所需支付之一切費用及賠償。且於第三人對甲方提出請求、訴訟，經甲方以書面通知乙方提供相關資料，乙方應合作提供，絕無異議。</li>
                <li>五、乙方離職或不再受甲方委託後，不得繼續使用甲方交付之相關資料，並應予歸還、銷毀或刪除，且仍需保證不得以任何形式洩露或交付第三人因本專案所知悉之個人資料與相關業務，並仍應嚴守保密義務。</li>
                <li>六、本契約所稱之「個人資料」乃指個人資料保護法第2條之定義：自然人之姓名、出生年月日、國民身分證統一編號、護照號碼、特徵、指紋、婚姻、家庭、教育、職業、病歷、醫療、基因、性生活、健康檢查、犯罪前科、聯絡方式、財務情況、社會活動及其他得以直接或間接方式識別該個人之資料。</li>
                <li>七、本契約所稱個人資料之「聯絡方式」包含但不限於：服務單位或任職公司、辦公電話、住家電話、手機、公司地址、住家地址、戶籍地址、電子郵件、網路社群帳號等。</li>
                <li>
                    八、本契約所稱之「蒐集」、「處理」、「利用」乃指個人資料保護法第2條之定義：
                    <ol class="second_level">
                        <li>蒐集：指以任何方式取得個人資料。</li>
                        <li>處理：指為建立或利用個人資料檔案所為資料之記錄、輸入、儲存、編輯、更正、複製、檢索、刪除、輸出、連結或內部傳送。</li>
                        <li>利用：指將蒐集之個人資料為處理以外之使用。</li>
                    </ol>
                </li>
                <li>九、本契約所稱「關係企業」包括由甲方直接或間接所有/經營之合法組織體。</li>
                <li>十、本契約自簽定之日起生效，且於乙方離職或不再受甲方委託之日起仍繼續有效。本契約非經雙方書面同意，任一方不得任意終止。</li>
                <li>十一、本契約未盡事宜悉依中華民國相關法律為準。若因本契約書約定事項涉訟時，雙方同意以臺灣台北地方法院為第一審管轄法院。</li>
                <li>十二、本契約一式兩份，由甲、乙方各執一份為憑。</li>
            </ul>
            <br/><br/><br/><br/>
            <p class="padding_left_2em">甲方：</p>
            <ul class="ul_radio">
                <li><input type="radio" name="confidentiality_company" checked><span>文訊雜誌社</span></li>
                <li><input type="radio" name="confidentiality_company"><span>財團法人台灣文學發展基金會</span></li>
                <li><input type="radio" name="confidentiality_company"><span>財團法人台灣文學發展基金會市定古蹟紀州庵及新館營運管理處</span></li>
            </ul>
            <p class="padding_left_2em">代表人：</p>
            <input type="radio" name="confidentiality_title_personal" checked><span>社長</span>
            <input type="radio" name="confidentiality_title_personal"><span>董事長</span>
            <input type="radio" name="confidentiality_title_personal"><span>館長</span>
            <div><p class="padding_left_2em"></p><span><?=$management_data[0]->name?></span></div>
            <div><p class="padding_left_2em">乙方：</p><input type="text" value="<?=$data->name?>"disabled></div>
            <div><p class="padding_left_2em">身份證字號：</p><input type="text" value="<?=$data->person_id?>" disabled></div>
            <div><p class="padding_left_2em">戶籍地址：</p><input type="text" class="long-input" value="<?=$data->country?><?=$data->dist?><?=$data->address?>" disabled></div>
            <p style="max-width: 100%">
                <div class="year_of_tw">中華民國</div>
                <div class="group_date year">
                    <input type="text" value="<?=date("Y")-1911?>">
                    <label>年</label>
                </div>
                <div class="group_date month">
                    <input type="text" value="<?=date("m")?>">
                    <label>月</label>
                </div>
                <div class="group_date day">
                    <input type="text" value="<?=date("d")?>">
                    <label>日</label>
                </div>
            </p>
        </section>
        <script>
            $(function(){
                $('#print-btn').on('click', function(){
                    window.print();
                });
            });
        </script>
    </body>
</html>

