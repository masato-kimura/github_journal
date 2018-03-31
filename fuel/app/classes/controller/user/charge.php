<?php
use service\UserService;
use Fuel\Core\Presenter;
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
class Controller_User_Charge extends Controller_TemplateJournal
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
			$presenter = Presenter::forge('user/charge/index');
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -チャージ");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/charge/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -チャージ");
		}
	}
}
