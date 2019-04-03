<?php

class AuthorController extends Controller
{
    public $layout = "//layouts/back_end";

    protected function needLogin(): bool
    {
        return true;
    }

    public function actionList()
    {
        $this->render('list');
    }

    public function actionCreateForm()
    {
        $this->render('create_form');
    }
}