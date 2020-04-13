<?php

/*************************************
 * Company:
 * Program: code.inc.php
 * Version: 1.0
 * Description:	restful 標準API error
 *************************************/
//200、400、401、403、404、500 

//2XX
define("SUCCESS_GEL_NO" ,"200");
define("SUCCESS_GEL_MSG","執行成功");
define("SUCCESS_GEL_EMSG","OK");

define("SUCCESS_LOGERR_NO" ,"299");
define("SUCCESS_LOGERR_MSG","執行成功, 資料邏輯有誤");
define("SUCCESS_LOGERR_EMSG","OK, Incorrect logic");

//3XX
define("ERROR_WEB_TOKENERR_NO" ,"308");
define("ERROR_WEB_TOKENERR_MSG","認證Token錯誤");
define("ERROR_WEB_TOKENERR_EMSG","Authentication Token error");

define("SUCCESS_EMPTYERR_NO" ,"399");
define("SUCCESS_EMPTYERR_MSG","圖片資料庫沒有對應資料");
define("SUCCESS_EMPTYERR_EMSG","OK,no result");

//4XX
define("ERROR_WEB_POSTONLY_NO" ,"400");
define("ERROR_WEB_POSTONLY_MSG","只接受POST傳遞");
define("ERROR_WEB_POSTONLY_EMSG","Bad Request Post Only");

define("ERROR_WEB_TOKEN_NO" ,"401");
define("ERROR_WEB_TOKEN_MSG","缺少認證Token");
define("ERROR_WEB_TOKEN_EMSG","Missing Token");

define("ERROR_WEB_NOAPI_NO" ,"404");
define("ERROR_WEB_NOAPI_MSG","沒有此API");
define("ERROR_WEB_NOAPI_EMSG","API not found");

define("ERROR_WEB_PARAMETERTYPE_NO" ,"410");
define("ERROR_WEB_PARAMETERTYPE_MSG","參數尚未設定");
define("ERROR_WEB_PARAMETERTYPE_EMSG","be short of params");

// define("ERROR_WEB_DB_NO" ,"410");
// define("ERROR_WEB_DB_MSG","參數型態錯誤");

//5XX
define("ERROR_SERVER_NO" ,"500");
define("ERROR_SERVER_MSG","伺服器一般錯誤");
define("ERROR_SERVER_EMSG","Internal error");

?>