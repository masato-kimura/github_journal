<?php

use service\UserService;
use service\SessionService;
use Fuel\Core\Controller_Hybrid;
use Fuel\Core\Request;
class Controller_TemplateJournal extends Controller_Hybrid
{
	public $template = 'template';
	protected $arr_user_info = array();


	public function before()
	{
		\Log::debug('[start]'. __METHOD__);
		\Log::info(\Request::main()->controller);
		if ( ! \Session::get('auto_login', false))
		{
			\Log::info('sessionはブラウザcloseと同時に破棄されます。');
			\Session::instance()->set_config('expire_on_close', true);
		}
		else
		{
			\Log::info('sessionはブラウザcloseと同時に破棄されません。');
			\Session::instance()->set_config('expire_on_close', false);
		}

		parent::before();

		$arr_user_info = SessionService::get('user_info');
		\Log::info($arr_user_info);
		if ( ! empty($arr_user_info) )
		{
			UserService::validation_for_login_user($arr_user_info);
			UserService::set_login_user($arr_user_info);
			$this->arr_user_info = $arr_user_info;
		}
		if ( ! $this->is_restful())
		{
			$this->template->user_id = '';
			$this->template->user_name = '';
			$this->template->user_id     = $arr_user_info['user_id'];
			$this->template->user_name   = htmlentities($arr_user_info['user_name'], ENT_QUOTES, mb_internal_encoding());
			$this->template->set_global('message', \Session::get_flash('message'));
		}
		$this->response = \Response::forge();
		$this->response->set_header('X-FRAME-OPTIONS', 'SAMEORIGIN');
	}

	public function after($response)
	{
		$response = $this->response;
		if ( ! $this->is_restful())
		{
			$response->body = $this->template;
		}
		return parent::after($response);
	}
}
