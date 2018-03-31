<?php
use Fuel\Core\Presenter;
use service\FixService;
use service\UserService;

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
class Controller_Fix extends Controller_TemplateJournalMaterialize
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

			# リクエスト配列
			$arr_params = array();

			# リクエストをサービスメンバ変数に格納
			FixService::set_request($arr_params);

			# プレゼンタ生成
			$presenter = Presenter::forge('fix/index');

			# 一覧取得
			$obj_list = FixService::get_list();
			$presenter->set('list', $obj_list);

			$presenter->set('arr_params', $arr_params);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -登録済みカテゴリー");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('fix/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -登録済みカテゴリー");
		}
	}

	public function action_sort()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! UserService::is_login())
			{
				\Response::redirect('/user/login');
			}
			# リクエスト配列
			$arr_params = array(
				'sorted' => \Input::post('sorted', array()),
			);
			# バリデーション
			if ( ! FixService::validation_for_sort($arr_params))
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('fix/index');
				# 一覧取得
				$obj_list = FixService::get_list();

				$presenter->set('list', $obj_list);
				$presenter->set('arr_validation_error', FixService::get_validation_error());
				$presenter->set('arr_params', $arr_params);
				# プレゼンタをレンダリンしテンプレートにセット
				$this->template->set_safe('contents', $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -登録済みカテゴリー");
				return true;
			}
			# リクエストをサービスにセット
			FixService::set_request($arr_params);

			# API送信
			FixService::send_api_for_sort();

			\Response::redirect('/fix/index/', 'location', '302');
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('fix/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -登録済みカテゴリー");
		}
	}

	public function action_add()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック
			if ( ! UserService::is_login())
			{
				\Response::redirect('/user/login');
			}
			# リクエスト配列
			$arr_params = array(
				'name'     => \Input::post('name'),
				'remark'   => \Input::post('remark'),
				'is_fix'   => \Input::post('is_fix'),
				'is_disp'  => \Input::post('is_disp'),
				'to_aggre' => \Input::post('to_aggre'),
				'sort'     => 999,
			);
			# バリデーション
			if ( ! FixService::validation_for_add($arr_params))
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('fix/index');
				# 一覧取得
				$obj_list = FixService::get_list();

				$presenter->set('list', $obj_list);
				$presenter->set('arr_validation_error', FixService::get_validation_error());
				$presenter->set('error_from', 'add');
				$presenter->set('arr_params', $arr_params);
				$this->template->set_safe('contents', $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -登録済みカテゴリー");
				return true;
			}
			# リクエストをサービスメンバ変数にセット
			FixService::set_request($arr_params);
			# API送信
			FixService::send_api_for_add();
			# リダイレクト
			\Response::redirect('/fix/index/', 'location', '302');
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('fix/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -登録済みカテゴリー");
		}
	}

	public function action_edit()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック
			if ( ! UserService::is_login())
			{
				\Response::redirect('/user/login');
			}
			# リクエスト配列
			$arr_params = array(
				'id'       => \Input::post('id'),
				'name'     => \Input::post('name'),
				'remark'   => \Input::post('remark'),
				'is_fix'   => \Input::post('is_fix'),
				'is_disp'  => \Input::post('is_disp'),
				'to_aggre' => \Input::post('to_aggre'),
				'sort'     => \Input::post('sort'),
			);
			# バリデーション
			if ( ! FixService::validation_for_edit($arr_params))
			{
				# プレゼンテーション生成
				$presenter = Presenter::forge('fix/index');
				# 一覧取得
				$obj_list = FixService::get_list();

				$presenter->set('list', $obj_list);
				$presenter->set('arr_validation_error', FixService::get_validation_error());
				$presenter->set('error_from', 'edit_'. \Input::post('id'));
				$presenter->set('arr_params', $arr_params);
				# プレゼンタをレンダリングしテンプレートにセット
				$this->template->set_safe('contents', $presenter->render());
				$this->template->set_safe('title', '家計簿アプリ -登録済みカテゴリー');
				return true;
			}
			# リクエストをサービスメンバ変数にセット
			FixService::set_request($arr_params);
			# API送信
			FixService::send_api_for_edit();
			# リダイレクト
			\Response::redirect('/fix/index/', 'location', '302');
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('fix/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -登録済みカテゴリー");
		}
	}

	public function action_remove($id)
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック
			if ( ! UserService::is_login())
			{
				\Response::redirect('/user/login');
			}
			# リクエスト配列
			$arr_params = array(
				'id'      => $id,
			);
			# バリデーション
			if ( ! FixService::validation_for_remove($arr_params))
			{
				# プレゼンテーション生成
				$presenter = Presenter::forge('fix/index');
				# 一覧取得
				$obj_list = FixService::get_list();

				$presenter->set('list', $obj_list);
				$presenter->set('arr_validation_error', FixService::get_validation_error());
				$presenter->set('arr_params', $arr_params);
				# プレゼンタをレンダリングしテンプレートにセット
				$this->template->set_safe('contents', $presenter->render());
				$this->template->set_safe('title', '家計簿アプリ -登録済みカテゴリー');
				return true;
			}
			# リクエストをサービスメンバ変数にセット
			FixService::set_request($arr_params);
			# API送信
			FixService::send_api_for_remove();
			# リダイレクト
			\Response::redirect('/fix/index/', 'location', '302');
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('fix/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -登録済みカテゴリー");
		}
	}

}
