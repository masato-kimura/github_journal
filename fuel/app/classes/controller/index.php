<?php
use Fuel\Core\Presenter;

/**
 *
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
class Controller_Index extends Controller_TemplateJournalMaterializeIndex
{
	public function action_index()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('index/index');

			$presenter->set('arr_params', $arr_params);
			$presenter->set('arr_user_info', $this->arr_user_info);

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('index/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル");
		}
	}

	public function action_terms()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('info/terms');

			$presenter->set('arr_params', $arr_params);

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル 利用規約");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('info/terms');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル 利用規約");
		}
	}

	public function action_privacy()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('info/prvacy');

			$presenter->set('arr_params', $arr_params);

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル プライバシーポリシー");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('info/prvacy');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル プライバシーポリシー");
		}
	}

	public function action_contract()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('index/contract');

			$presenter->set('arr_params', $arr_params);

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル 特定商取引に基づく表記");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('index/contract');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル 特定商取引に基づく表記");
		}
	}


}
