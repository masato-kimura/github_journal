<?php
use service\UserService;
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2016 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_User_Logout extends Controller_TemplateJournal
{
	public function action_index()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! UserService::is_login())
			{
				\Response::redirect('/user/login');
			}

			// バリデーション
			UserService::validation_for_logout();

			# apiにログアウト情報を送信
			UserService::logout();

			# セッションおよびクッキーのログイン情報を削除
			UserService::session_destroy();

			# ログアウト後の遷移画面
			\Response::redirect('/');
		}
		catch (\Exception $e)
		{
			UserService::session_destroy();

			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			\Session::set_flash('error_info', 'ログアウト処理に失敗しました。');
			\Response::redirect('/');
		}
	}
}
