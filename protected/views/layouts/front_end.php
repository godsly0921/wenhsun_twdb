<html>
  <head>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta charset="utf-8">
    <meta name="keywords" content='' />
    <meta name="description" content=''/>
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+TC&amp;subset=chinese-traditional,japanese" rel="stylesheet" class="next-head">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <title>台灣文學照片資料庫</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/assets/css/layout.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </head>
  <body>
    <!-- Header --- Start -->
    <header>
      <nav class="navbar navbar-light bg-lignt navbar-expand-md header_navbar">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-nav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse dual-nav order-1 order-md-1 justify-content-end">
            <ul class="navbar-nav header_navbar_nav col-md-12 col-lg-8 mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="about">關於我們</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">最新消息</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">圖片專欄</a>
                </li>
            </ul>
        </div>
        <a href="/" class="navbar-brand mx-auto order-0 order-md-2 p-2">台灣文學照片資料庫</a>
        <div class="navbar-collapse collapse dual-nav order-2 order-md-2 justify-content-end">
            <ul class="navbar-nav header_navbar_nav col-md-12 col-lg-8 mx-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">圖片檢索</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">我的下載</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">註冊/登入</a>
                </li>
            </ul>
        </div>
      </nav>
    </header>
    <!--   Header  End -->
    <?= $content ?>
    <!-- Footer --- Start -->
    <footer>
      <div class="container py-5">
        <div class="row">
          <div class="col-lg-4">
            <a href="/"><h3 class="footer_company_name">台灣文學照片資料庫</h3></a>
            <p>本計畫受文化部推動國家文化記憶庫計畫補助</p>
          </div>
          <div class="col-lg-3">
            <div class="row">
              <div class="col-lg-4">隱私條款</div>
              <div class="col-lg-4">合作洽談</div>
              <div class="col-lg-4">服務條款</div>
            </div>
          </div>
          <div class="col-lg-5 footer_right">
            <div class="row">
              <div class="col-lg-12">文訊雜誌社 地址：10048台北市中山南路11號B2</div>
              <div class="col-lg-12">代表號:02-23433142 傳真:02-23946103 E-mail:wenhsun7@ms19.hinet.net</div>
              <div class="col-lg-12">本站台資料為版權所有，非經同意請勿作任何形式之轉載使用</div>
              <p class="col-lg-12">Copyright © . All rights reserved.</p>
            </div>
          </div>
        </div>
      </div>
    </footer>
    <!-- Footer --- End -->
  </body>
</html>