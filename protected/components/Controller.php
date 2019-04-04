<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
abstract class Controller extends CController
{
    const ERR_MSG_KEY = 'error_msg';
    const SUCCESS_MSG_KEY = 'success';

    protected abstract function needLogin(): bool;

    protected function beforeAction($action)
    {
        if ($this->needLogin()) {
            return RequestLogin::checkLogin($action) ? true : $this->redirect(Yii::app()->createUrl('admin/index'));
        }

        return true;
    }

    public function checkCSRF($redirectURL)
    {
        if (!CsrfProtector::comparePost()) {
            $this->redirect($redirectURL);
        }
    }

    public function checkCsrfAjax()
    {
        if (!CsrfProtector::comparePost()) {
            $this->sendErrAjaxRsp(403, "Forbidden");
        }
    }

    public function sendSuccAjaxRsp()
    {
        $rsp = new AjaxResponse();
        $rsp->statusCode = 200;
        $rsp->message = 'ok';

        echo json_encode($rsp);
        exit;
    }

    public function sendErrAjaxRsp($code, $msg)
    {
        $rsp = new AjaxResponse();
        $rsp->statusCode = $code;
        $rsp->message = $msg;

        http_response_code($code);

        echo json_encode($rsp);
        exit;
    }

	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = "//layouts/back_end";
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

    protected function clearMsg()
    {
        unset(Yii::app()->session['error_msg']);
        unset(Yii::app()->session['success_msg']);
    }
}