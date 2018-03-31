<?php

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
class Controller_Fx extends Controller_TemplateJournalMaterializeNoneHeader
{
	public function action_index()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('fx/index');

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "FX利損計算");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('fx/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "FX利損計算");
		}
	}

	public function action_range()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('fx/range');

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "FXレンジ利損計算");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('fx/range');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "FXレンジ利損計算");
		}
	}



}
