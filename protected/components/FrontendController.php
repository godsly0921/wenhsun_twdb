<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class FrontendController extends CController
{

    protected $defaultLanguageType = ["zh-tw", "zh-cn"];
    public $languageUrls = [];

    public function init()
    {
        if (isset($_GET['lang']) && $_GET['lang'] != "") {
            $lang = $_GET['lang'];
        } else if (isset($_COOKIE['language']) && $_COOKIE['language'] != "") {
            $lang = $_COOKIE['language'];
        } else {
            //取得瀏覽器語系
            $browserLang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? (strtolower(strtok(strip_tags($_SERVER['HTTP_ACCEPT_LANGUAGE']), ','))) : 'en';
            //瀏覽器語系是中文給中文設定檔
            $languages = $this->defaultLanguageType;

            $check_language = in_array($browserLang, $languages);

            if ($check_language === false) {
                $lang = 'zh-tw';
            } else {
                $lang = $browserLang;
            }

        }

        Yii::app()->language = $lang;
        setcookie('language', $lang, time() + 86400, '/', $_SERVER['HTTP_HOST'], false, true);

        unset($_GET['lang']);
        $queryString = http_build_query($_GET, '', '&');

        if ($queryString === "") {

            foreach ($this->defaultLanguageType as $langType) {

                $this->languageUrls[$langType] = $_SERVER['HTTP_HOST']
                    . explode('?', $_SERVER['REQUEST_URI'])[0]
                    . "?lang={$langType}";
            }
        } else {
            foreach ($this->defaultLanguageType as $langType) {

                $this->languageUrls[$langType] = $_SERVER['HTTP_HOST']
                    . explode('?', $_SERVER['REQUEST_URI'])[0]
                    . "?{$queryString}"
                    . "&lang={$langType}";
            }
        }

    }
}
