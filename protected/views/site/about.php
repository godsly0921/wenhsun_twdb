<div id="banner" class="row">
    <img src="
        <?php if ($about['banner']['image'] == '') : ?>
                <?= Yii::app()->request->baseUrl . ABOUT_IMAGE . 'about_banner.jpg' ?>
        <?php else : ?>
                <?= Yii::app()->request->baseUrl . $about['banner']['image'] ?>
        <?php endif; ?>
    ">
</div>
<br>
<div class="container">
    <div class="paragraph">
        <p class="header">
            <?php if ($about['header']['paragraph'] == '') : ?>
                《文訊》創社緣起
            <?php else : ?>
                <?= $about['header']['paragraph'] ?>
            <?php endif; ?>
        </p>
        <p>
            <?php if ($about['paragraph1']['paragraph'] == '') : ?>
                1983年7月1日，《文訊》由國民黨中央文化工作會創辦。初期的目的在為文藝作家服務，蒐集、整理文學史料，為文學歷史奠基，幾年內就做出了一些成績，頗受文藝界、學界的稱讚。但《文訊》不以此為滿足，每期藉專題企畫的方式，探討不同階段的文學發展，將各個階段的作家作品、學術思想記錄下來，肯定前輩作家的文學表現，也重視文壇新秀的努力創新。<br>
                《文訊》不僅致力於文學史料的蒐集、整理及研究，並試圖呈現完整的藝文與出版資訊，報導作家創作與活動。既重視城市文學的繁華典雅，亦從不忽略地方文學 的純樸動人。發行二十多年來，重點始終放在現當代台灣文學整理及研究上，成績粲然可觀，誠為研究當代台灣文學必讀之文學刊物。由於長期的用心經營，我們 獲得文藝界及學界普遍的肯定，已經成為台灣現代文學的資料庫，可說是台灣文學發展的檢驗指標。
            <?php else : ?>
                <?= $about['paragraph1']['paragraph'] ?>
            <?php endif; ?>
        </p>
    </div>
    <div class="paragraph">
        <div class="row">
            <div class="col-sm-5">
                <img src="
                    <?php if ($about['paragraph2']['image'] == '') : ?>
                            <?= Yii::app()->request->baseUrl . ABOUT_IMAGE . 'image1.jpg' ?>
                    <?php else : ?>
                            <?= Yii::app()->request->baseUrl . $about['paragraph2']['image'] ?>
                    <?php endif; ?>
                ">
            </div>
            <div class="col-sm-7">
                <p>
                    <?php if ($about['paragraph2']['paragraph'] == '') : ?>
                        《文訊》所附設的「文藝資料研究及服務中心」自始便立下以「文學史料蒐藏」為核心，進行研究、推廣與服務的使命。三十多年來，搭配每一期《文訊》的專題與專欄規劃，同時以專案式的、系統化的方式蒐集文學史料，目前已累積中文文藝圖書十萬餘冊、已停刊或正在發行之文學雜誌，計七百餘種，約六萬冊、作家及文藝活動照片近四萬餘張、作家手稿六千多份、累積近三十年報紙副刊、文化版、讀書版、上百種文學專題資料卷宗、十萬餘筆作家評論資料、八千餘筆作家學者媒體人的通訊錄，而且這些資料還在持續成長中。
                    <?php else : ?>
                        <?= $about['paragraph2']['paragraph'] ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
    <div class="paragraph">
        <div class="row">
            <div class="col-sm-7">
                <p>
                    <?php if ($about['paragraph3']['paragraph'] == '') : ?>
                        「台灣文學照片資料庫」即以文訊擁有之龐大作家照片為基底，將一切珍貴台灣文學資源轉化成數位應用，創造出新的價值與意義，更有助於:<br><br>
                        (一) 為台灣文學發展過程的作家創作歷程留下真實的紀錄;<br>
                        (二) 充實台灣文學發展史中，文學出版社的價值與影響;<br>
                        (三) 有助於台灣文學社團、文學媒體、期刊的史料搜尋;<br>
                        (四) 因照片人物、地點等的出現，可以彌補某些斷裂的、失聯的文學歷史。<br>
                        (五) 本計畫受文化部推動國家文化記憶庫計畫補助建置，期望透過網站分享給社會大眾，並透過數位典藏的再利用，讓大眾得以透過文學影像，喚起對土地、文化、社會的情感，豐富台灣的人文記憶。
                    <?php else : ?>
                        <?= $about['paragraph3']['paragraph'] ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="col-sm-5">
                <img src="
                    <?php if ($about['paragraph3']['image'] == '') : ?>
                            <?= Yii::app()->request->baseUrl . ABOUT_IMAGE . 'image2.jpg' ?>
                    <?php else : ?>
                            <?= Yii::app()->request->baseUrl . $about['paragraph3']['image'] ?>
                    <?php endif; ?>
                ">
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .header {
        font-size: 20px;
        font-weight: bold;
    }

    .paragraph {
        padding-top: 20px;
        padding-bottom: 20px;

    }
</style>
