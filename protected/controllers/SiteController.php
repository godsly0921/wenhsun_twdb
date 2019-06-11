<?php
class SiteController extends CController{
    // layout
    public $layout = "//layouts/front_end";
    protected function needLogin(): bool
    {
        return true;
    }
    //首頁site
    public function ActionIndex(){
        $this->render('index');
    }
}
?>