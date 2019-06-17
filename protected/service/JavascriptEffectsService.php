<?php
class JavascriptEffectsService
{
    public static function goTop()
    {
       echo '<script type="text/javascript">
        $(function () {
            $("#go_top").click(function () {
                jQuery("html,body").animate({
                scrollTop: 0
            }, 1000);
        });
            $(window).scroll(function () {
                if ($(this).scrollTop() > 300) {
                    $("#go_top").fadeIn("fast");
                } else {
                    $("#go_top").stop().fadeOut("fast");
                }
            });
        });
        </script>
        <style type="text/css">
            #go_top
            {
                display: none;
                position: fixed;
                right: 30px;
                bottom: 20px;
                padding: 10px 10px;
                font-size: 16px;
                background: hsl(0, 0%, 46%);
                color: white;
                cursor: pointer;
                z-index: 999;
            }
        </style>
        <div id="go_top">TOP</div>';
    }
}