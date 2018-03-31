<?php
use Fuel\Core\Presenter;
use service\PaymentService;
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
class Controller_Payment extends Controller_TemplateJournalMaterialize
{
	public function action_list()
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
					'year'       => \Input::param('year', \Date::forge()->format('%Y')),
					'month'      => \Input::param('month', \Date::forge()->format('%m')),
					'day'        => \Input::param('day', ''),
					'search'     => \Input::param('search', ''),
					'page'       => \Input::param('page', ''),
					'sort_by'    => \Input::param('sort_by', 'date'),
					'direction'  => \Input::param('direction', 'DESC'),
			);

			# プレゼンタ生成
			$presenter = Presenter::forge('payment/list');

			# バリデーション
			if ( ! PaymentService::validation_for_list($arr_params))
			{
				$presenter->set('arr_validation_error', PaymentService::get_validation_error());
			}

			# リクエストをサービスメンバ変数に格納
			PaymentService::set_request($arr_params);

			# ページネーション
			$uri_base = \Uri::base();
			$config = array(
					'pagination_url' => $uri_base.'payment/list/?'. http_build_query($arr_params),
					'total_items'    => PaymentService::get_count_from_api(),
					'per_page'       => 20,
					'uri_segment'    => 'page',
			);
			$pagination = Pagination::forge('list_pagination', $config);
			$offset = ($pagination->offset < 0)? 0: $pagination->offset;

			# APIからデータ取得
			$obj_list_data  = PaymentService::get_list_from_api($offset, $pagination->per_page);

			$presenter->set('obj_list_data', $obj_list_data);
			$presenter->set('pagination', $pagination);
			$presenter->set('arr_params', $arr_params);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -支出データ一覧");
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			\Log::error($e->getCode());
			UserService::session_destroy();
			if ($e->getCode() == '7010') // login_hashエラー
			{
				\Response::redirect('/user/login');
			}
			$presenter = Presenter::forge('payment/list');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -支出データ一覧");
		}
	}

	public function post_listajax()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! UserService::is_login())
			{
				throw new \Exception('no login error');
			}

			# リクエストを取得
			$handle = fopen('php://input', 'r');
			$json_request = fgets($handle);
			fclose($handle);
			$obj_request = json_decode($json_request);

			# リクエスト配列
			$arr_params = array(
				'year'       => (empty($obj_request->year))? \Date::forge()->format('%Y'): $obj_request->year,
				'month'      => (! isset($obj_request->month))? \Date::forge()->format('%m'): $obj_request->month,
				'day'        => (! isset($obj_request->day))? '': $obj_request->day,
				'search'     => (empty($obj_request->search))? '': $obj_request->search,
				'page'       => (empty($obj_request->page))? 1: $obj_request->page,
				'sort_by'    => (empty($obj_request->sort_by))? 'date': $obj_request->sort_by,
				'direction'  => (empty($obj_request->direction))? 'desc': $obj_request->direction,
				'count'      => (empty($obj_request->count))? null: $obj_request->count,
			);

			# バリデーション
			if ( ! PaymentService::validation_for_list($arr_params))
			{
				$arr_validation_error = PaymentService::get_validation_error();
				$error_message = '';
				foreach ($arr_validation_error as $i => $val)
				{
					$error_message = $error_message. PHP_EOL. $i. '：'. $val. '　';
				}
				throw new \Exception($error_message);
			}

			# リクエストをサービスメンバ変数に格納
			PaymentService::set_request($arr_params);

			# ページネーション
			$config = array(
					'pagination_url' => \Config::get('journal.www_host').'/payment/list/?'. http_build_query($arr_params),
					'per_page'       => 20,
					'uri_segment'    => 'page',
					'current_page'   => $arr_params['page'],
			);
			if (is_null($arr_params['count']))
			{
				$config['total_items'] = PaymentService::get_count_from_api();
			}
			else
			{
				$config['total_items'] = $arr_params['count'];
			}
			$pagination = Pagination::forge('list_pagination', $config);

			# APIからデータ取得
			$limit = 20;
			$offset = ($arr_params['page'] - 1) * $limit;
			$obj_list_data  = PaymentService::get_list_from_api($offset, $limit, true);
			$arr_response = array(
				'success'  => true,
				'code'     => '1001',
				'response' => '',
				'result'   => array(
					'list' => $obj_list_data->list,
					'pagination' => $pagination->render(),
				),
			);
			return $this->response($arr_response);
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			\Log::error($e->getCode());
			$arr_response = array(
				'success'  => false,
				'code'     => '9001',
				'response' => $e->getMessage(),
				'result'   => array(),
			);
			return $this->response($arr_response);
		}
	}

	public function action_add()
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
					'year'      => \Input::param('year', \Date::forge()->format('%Y')),
					'month'     => \Input::param('month', \Date::forge()->format('%m')),
					'day'       => \Input::param('day', ''),
					'search'    => \Input::param('search', ''),
					'sort_by'   => \Input::param('sort_by', 'date'),
					'direction' => \Input::param('direction', 'DESC'),
					'page'      => \Input::param('page', ''),
			);

			# プレゼンタ生成
			$presenter = Presenter::forge('payment/add');

			# リクエストバリデーション
			if ( ! PaymentService::validation_for_add($arr_params))
			{
				$presenter->set('arr_validation_error', PaymentService::get_validation_error());
			}

			# 登録済みカテゴリー一覧を取得
			$obj_fix_list = FixService::get_list();
			$presenter->set('obj_fix_list', $obj_fix_list);

			# プレゼンタにリクエストデータをセット
			$presenter->set('arr_params', $arr_params);

			# プレゼンタをレンダリングしテンプレートにセット
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -新規登録");
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');

			$presenter = Presenter::forge('payment/add');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -新規登録");
		}
	}

	public function action_adddone()
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
					'year'          => \Input::param('year', \Date::forge()->format('%Y')),
					'month'         => \Input::param('month', \Date::forge()->format('%m')),
					'day'           => \Input::param('day', ''),
					'search'        => \Input::param('search', ''),
					'sort_by'       => \Input::param('sort_by', 'date'),
					'direction'     => \Input::param('direction', 'DESC'),
					'page'          => \Input::param('page', ''),
					'date'          => \Input::param('date', \Date::forge()->format('%Y-%m-%d')),
					'fix_id'        => \Input::param('fix_id', ''),
					'is_fix'        => \Input::param('is_fix', ''),
					'name'          => \Input::param('name', ''),
					'detail'        => \Input::param('detail', ''),
					'shop'          => \Input::param('shop', ''),
					'cost'          => \Input::param('cost', ''),
					'remark'        => \Input::param('remark', ''),
					'work_side_per' => \Input::param('work_side_per', '0'),
					'use_type'      => \Input::param('use_type', '0'),
					'paymethod_id'  => \Input::param('paymethod_id', '0'),
			);

			# バリデーション
			if ( ! PaymentService::validation_for_adddone($arr_params))
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('payment/add');
				# 登録済みカテゴリー一覧を取得
				$obj_fix_list = FixService::get_list();
				$presenter->set('obj_fix_list', $obj_fix_list);
				$presenter->set('arr_validation_error', PaymentService::get_validation_error());
				$presenter->set('arr_params', $arr_params);
				$this->template->set_safe("contents", $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -新規登録");
				return true;
			}

			# リクエストをサービスメンバ変数にセット
			PaymentService::set_request($arr_params);
			# APIへデータ送信
			PaymentService::send_api_add_data();
			# 一覧にリダイレクト
			\Session::set_flash('message', $arr_params['date'].' '. $arr_params['name'].' '. $arr_params['shop']. 'のデータ登録完了しました。');
			\Response::redirect('/payment/add/?'. http_build_query(
				array(
						'year'      => $arr_params['year'],
						'month'     => $arr_params['month'],
						'day'       => $arr_params['day'],
						'search'    => $arr_params['search'],
						'sort_by'   => $arr_params['sort_by'],
						'direction' => $arr_params['direction'],
						'page'      => $arr_params['page'],
						'date'      => $arr_params['date'],
				)
			), 'location', '302');
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');

			$presenter = Presenter::forge('payment/add');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -新規登録");
		}
	}

	public function action_edit($id)
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
					'id'         => $id,
					'year'       => \Input::param('year', \Date::forge()->format('%Y')),
					'month'      => \Input::param('month', \Date::forge()->format('%m')),
					'day'        => \Input::param('day', ''),
					'search'     => \Input::param('search', ''),
					'sort_by'    => \Input::param('sort_by', 'date'),
					'direction'  => \Input::param('direction', 'DESC'),
					'page'       => \Input::param('page', ''),
			);

			# プレゼンタ生成
			$presenter = Presenter::forge('payment/edit');

			# バリデーション
			if ( ! PaymentService::validation_for_edit($arr_params))
			{
				$arr_validation_error = PaymentService::get_validation_error();
				$presenter->set('arr_validation_error', $arr_validation_error);
			}

			# 登録済みカテゴリーリスト取得
			$obj_fix_list = FixService::get_list();
			$presenter->set('obj_fix_list', $obj_fix_list);

			# 詳細データ取得
			$arr_payment_deital = PaymentService::get_detail_from_api($id);
			$presenter->set('arr_detail', $arr_payment_deital);

			# プレゼンタにリクエストデータをセット
			$presenter->set('arr_params', $arr_params);

			# プレゼンタをレンダリングしテンプレートにセット
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -データ更新");
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');

			$presenter = Presenter::forge('payment/edit');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -データ更新");
		}
	}

	public function action_editdone()
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
					'year'      => \Input::param('year', \Date::forge()->format('%Y')),
					'month'     => \Input::param('month', \Date::forge()->format('%m')),
					'day'       => \Input::param('day', ''),
					'search'    => \Input::param('search', ''),
					'sort_by'   => \Input::param('sort_by', 'date'),
					'direction' => \Input::param('direction', 'DESC'),
					'page'      => \Input::param('page', ''),
					'id'        => \Input::param('id'),
					'date'      => \Input::param('date'),
					'fix_id'    => \Input::param('fix_id', ''),
					'is_fix'    => \Input::param('is_fix', ''),
					'name'      => \Input::param('name', ''),
					'detail'    => \Input::param('detail', ''),
					'shop'      => \Input::param('shop', ''),
					'cost'      => \Input::param('cost', ''),
					'remark'    => \Input::param('remark', ''),
					'work_side_per' => \Input::param('work_side_per'),
					'use_type'      => \Input::param('use_type'),
					'paymethod_id'  => \Input::param('paymethod_id', '0'),
					'payment_reserve_status' => \Input::param('payment_reserve_status', '0'),
			);
			# バリデーション
			PaymentService::validation_for_editdone($arr_params);
			# リクエストをサービスメンバ変数にセット
			PaymentService::set_request($arr_params);
			# APIへデータ送信
			PaymentService::send_api_edit_data();

			if ( ! PaymentService::is_validation_error())
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('payment/edit');
				# 登録済みカテゴリーリスト取得
				$obj_fix_list = FixService::get_list();
				$presenter->set('obj_fix_list', $obj_fix_list);
				# 詳細データ取得
				$arr_payment_deital = PaymentService::get_detail_from_api(\Input::post('id'));
				$presenter->set('arr_params', $arr_params);
				$presenter->set('arr_detail', $arr_payment_deital);
				$presenter->set('arr_validation_error', PaymentService::get_validation_error());
				$this->template->set_safe("contents", $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -データ更新");
				return true;
			}

			# 一覧にリダイレクト
			\Session::set_flash('message', $arr_params['date'].' '. $arr_params['name'].' '. $arr_params['shop']. 'のデータ更新完了しました。');
			\Response::redirect('/payment/list/?'. http_build_query(array(
				'year'      => $arr_params['year'],
				'month'     => $arr_params['month'],
				'day'       => $arr_params['day'],
				'search'    => $arr_params['search'],
				'sort_by'   => $arr_params['sort_by'],
				'direction' => $arr_params['direction'],
				'page'      => $arr_params['page'],
			)), 'location', '302');
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');

			$presenter = Presenter::forge('payment/edit');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -データ更新");
		}
	}


	public function action_remove($id)
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! UserService::is_login())
			{
				\Response::redirect('/user/login');
			}
			# リクエスト配列へ格納
			$arr_params = array(
					'id'        => $id,
					'year'      => \Input::param('year', \Date::forge()->format('%Y')),
					'month'     => \Input::param('month', \Date::forge()->format('%m')),
					'day'       => \Input::param('day', ''),
					'search'    => \Input::param('search', ''),
					'sort_by'   => \Input::param('sort_by', 'date'),
					'direction' => \Input::param('direction', 'DESC'),
					'page'      => \Input::param('page', ''),
			);
			# バリデーション
			if ( ! PaymentService::validation_for_remove($arr_params))
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('payment/list');
				$presenter->set('arr_validation_error', PaymentService::get_validation_error());
				$presenter->set('arr_params', $arr_params);
				$this->template->set_safe('contents', $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -支出データ一覧");
				return true;
			}
			# リクエストデータをサービスメンバ変数にセット
			PaymentService::set_request($arr_params);
			# APIへ送信
			PaymentService::send_api_remove_data();
			# 一覧にリダイレクト
			\Session::set_flash('message', '支出データを一件削除しました。');
			\Response::redirect('/payment/list/?'. http_build_query(array(
				'year'      => $arr_params['year'],
				'month'     => $arr_params['month'],
				'day'       => $arr_params['day'],
				'search'    => $arr_params['search'],
				'sort_by'   => $arr_params['sort_by'],
				'direction' => $arr_params['direction'],
				'page'      => $arr_params['page'],
			)), 'location', '302');
		}
		catch(\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');

			$presenter = Presenter::forge('payment/list');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -支出データ一覧");
		}
	}


	public function action_reservelist()
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
					'year'       => \Input::param('year', \Date::forge()->format('%Y')),
					'month'      => \Input::param('month'),
					'day'        => \Input::param('day', ''),
					'search'     => \Input::param('search', ''),
					'page'       => \Input::param('page', ''),
					'sort_by'    => \Input::param('sort_by', 'every_type'),
					'direction'  => \Input::param('direction', 'ASC'),
			);

			# プレゼンタ生成
			$presenter = Presenter::forge('payment/reservelist');

			# バリデーション
			if ( ! PaymentService::validation_for_reservelist($arr_params))
			{
				$presenter->set('arr_validation_error', PaymentService::get_validation_error());
			}

			# リクエストをサービスメンバ変数に格納
			PaymentService::set_request($arr_params);

			# ページネーション
			$uri_base = \Uri::base();
			$config = array(
					'pagination_url' => $uri_base.'payment/reservelist/?'. http_build_query($arr_params),
					'total_items'    => PaymentService::get_reservecount_from_api(),
					'per_page'       => 20,
					'uri_segment'    => 'page',
			);
			$pagination = Pagination::forge('list_pagination', $config);
			$offset = ($pagination->offset < 0)? 0: $pagination->offset;

			# APIからデータ取得
			$obj_list_data  = PaymentService::get_reservelist_from_api($offset, $pagination->per_page);

			$presenter->set('obj_list_data', $obj_list_data);
			$presenter->set('pagination', $pagination);
			$presenter->set('arr_params', $arr_params);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -定期支出登録一覧");
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			\Log::error($e->getCode());
			UserService::session_destroy();
			if ($e->getCode() == '7010') // login_hashエラー
			{
				\Response::redirect('/user/login');
			}
			$presenter = Presenter::forge('payment/reservelist');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -定期支出登録一覧");
		}
	}


	public function post_reservelistajax()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! UserService::is_login())
			{
				throw new \Exception('no login error');
			}

			# リクエストを取得
			$handle = fopen('php://input', 'r');
			$json_request = fgets($handle);
			fclose($handle);
			$obj_request = json_decode($json_request);

			# リクエスト配列
			$arr_params = array(
					'year'       => (empty($obj_request->year))? \Date::forge()->format('%Y'): $obj_request->year,
					'month'      => (! isset($obj_request->month))? \Date::forge()->format('%m'): $obj_request->month,
					'day'        => (! isset($obj_request->day))? '': $obj_request->day,
					'search'     => (empty($obj_request->search))? '': $obj_request->search,
					'page'       => (empty($obj_request->page))? 1: $obj_request->page,
					'sort_by'    => (empty($obj_request->sort_by))? 'date': $obj_request->sort_by,
					'direction'  => (empty($obj_request->direction))? 'desc': $obj_request->direction,
					'count'      => (empty($obj_request->count))? null: $obj_request->count,
			);

			# バリデーション
			if ( ! PaymentService::validation_for_reservelist($arr_params))
			{
				$arr_validation_error = PaymentService::get_validation_error();
				$error_message = '';
				foreach ($arr_validation_error as $i => $val)
				{
					$error_message = $error_message. PHP_EOL. $i. '：'. $val. '　';
				}
				throw new \Exception($error_message);
			}

			# リクエストをサービスメンバ変数に格納
			PaymentService::set_request($arr_params);

			# ページネーション
			$config = array(
					'pagination_url' => \Config::get('journal.www_host').'/payment/reservelist/?'. http_build_query($arr_params),
					'per_page'       => 20,
					'uri_segment'    => 'page',
					'current_page'   => $arr_params['page'],
			);
			if (is_null($arr_params['count']))
			{
				$config['total_items'] = PaymentService::get_reservecount_from_api();
			}
			else
			{
				$config['total_items'] = $arr_params['count'];
			}
			$pagination = Pagination::forge('list_pagination', $config);

			# APIからデータ取得
			$limit = 20;
			$offset = ($arr_params['page'] - 1) * $limit;
			$obj_list_data  = PaymentService::get_reservelist_from_api($offset, $limit, true);
			$arr_response = array(
					'success'  => true,
					'code'     => '1001',
					'response' => '',
					'result'   => array(
							'list' => $obj_list_data->list,
							'pagination' => $pagination->render(),
					),
			);
			return $this->response($arr_response);
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			\Log::error($e->getCode());
			$arr_response = array(
					'success'  => false,
					'code'     => '9001',
					'response' => $e->getMessage(),
					'result'   => array(),
			);
			return $this->response($arr_response);
		}
	}


	public function action_reserveadd()
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
					'year'      => \Input::param('year', \Date::forge()->format('%Y')),
					'month'     => \Input::param('month', \Date::forge()->format('%m')),
					'day'       => \Input::param('day', ''),
					'search'    => \Input::param('search', ''),
					'sort_by'   => \Input::param('sort_by', 'date'),
					'direction' => \Input::param('direction', 'DESC'),
					'page'      => \Input::param('page', ''),
			);

			# プレゼンタ生成
			$presenter = Presenter::forge('payment/reserveadd');

			# リクエストバリデーション
			if ( ! PaymentService::validation_for_add($arr_params))
			{
				$presenter->set('arr_validation_error', PaymentService::get_validation_error());
			}

			# 登録済みカテゴリー一覧を取得
			$obj_fix_list = FixService::get_list();
			$presenter->set('obj_fix_list', $obj_fix_list);

			# プレゼンタにリクエストデータをセット
			$presenter->set('arr_params', $arr_params);

			# プレゼンタをレンダリングしテンプレートにセット
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -定期支出データ登録");
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');

			$presenter = Presenter::forge('payment/reserveadd');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -定期支出データ登録");
		}
	}


	public function action_reserveadddone()
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
					'year'          => \Input::param('year', \Date::forge()->format('%Y')),
					'month'         => \Input::param('month', \Date::forge()->format('%m')),
					'day'           => \Input::param('day', ''),
					'search'        => \Input::param('search', ''),
					'sort_by'       => \Input::param('sort_by', 'every_type'),
					'direction'     => \Input::param('direction', 'DESC'),
					'page'          => \Input::param('page', ''),
					'every_type'    => \Input::param('every_type', ''),
					'every_month_selected'     => \Input::param('every_month_selected', '0'),
					'every_day_selected'       => \Input::param('every_day_selected', '0'),
					'every_dayofweek_selected' => \Input::param('every_dayofweek_selected', ''), // 0は日曜なのでデフォルト空
					'date_from'     => \Input::param('date_from', \Date::forge()->format('%Y-01-01')),
					'date_to'       => \Input::param('date_to', \Date::forge()->format('%Y-12-31')),
					'fix_id'        => \Input::param('fix_id', ''),
					'is_fix'        => \Input::param('is_fix', ''),
					'name'          => \Input::param('name', ''),
					'detail'        => \Input::param('detail', ''),
					'shop'          => \Input::param('shop', ''),
					'cost'          => \Input::param('cost', ''),
					'remark'        => \Input::param('remark', ''),
					'work_side_per' => \Input::param('work_side_per', '0'),
					'use_type'      => \Input::param('use_type', '0'),
					'paymethod_id'  => \Input::param('paymethod_id', '0'),
			);

			# バリデーション
			if ( ! PaymentService::validation_for_reserveadddone($arr_params))
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('payment/reserveadd');
				# 登録済みカテゴリー一覧を取得
				$obj_fix_list = FixService::get_list();
				$presenter->set('obj_fix_list', $obj_fix_list);
				$presenter->set('arr_validation_error', PaymentService::get_validation_error());
				$presenter->set('arr_params', $arr_params);
				$this->template->set_safe("contents", $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -定期支出データ登録");
				return true;
			}

			# リクエストをサービスメンバ変数にセット
			PaymentService::set_request($arr_params);
			# APIへデータ送信
			PaymentService::send_api_reserveadd_data();
			# 一覧にリダイレクト
			\Session::set_flash('message', $arr_params['date_from'].'～'. $arr_params['date_to']. ' '. $arr_params['name'].' '. $arr_params['shop']. 'のデータ登録完了しました。');
			\Response::redirect('/payment/reserveadd/?'. http_build_query(
					array(
							'year'      => $arr_params['year'],
							'month'     => $arr_params['month'],
							'day'       => $arr_params['day'],
							'search'    => $arr_params['search'],
							'sort_by'   => $arr_params['sort_by'],
							'direction' => $arr_params['direction'],
							'page'      => $arr_params['page'],
							'every_type' => $arr_params['every_type'],
							'date_from'  => $arr_params['date_from'],
							'date_to'    => $arr_params['date_to'],
					)
					), 'location', '302');
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');

			$presenter = Presenter::forge('payment/reserveadd');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -定期支出データ登録");
		}
	}


	public function action_reserveedit($id)
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
					'id'         => $id,
					'year'       => \Input::param('year', \Date::forge()->format('%Y')),
					'month'      => \Input::param('month', \Date::forge()->format('%m')),
					'day'        => \Input::param('day', ''),
					'search'     => \Input::param('search', ''),
					'sort_by'    => \Input::param('sort_by', 'date'),
					'direction'  => \Input::param('direction', 'DESC'),
					'page'       => \Input::param('page', ''),
			);

			# プレゼンタ生成
			$presenter = Presenter::forge('payment/reserveedit');

			# バリデーション
			if ( ! PaymentService::validation_for_reserveedit($arr_params))
			{
				$arr_validation_error = PaymentService::get_validation_error();
				$presenter->set('arr_validation_error', $arr_validation_error);
			}

			# 登録済みカテゴリーリスト取得
			$obj_fix_list = FixService::get_list();
			$presenter->set('obj_fix_list', $obj_fix_list);

			# 詳細データ取得
			$arr_payment_detail = PaymentService::get_reservedetail_from_api($id);
			$presenter->set('arr_detail', $arr_payment_detail);

			# プレゼンタにリクエストデータをセット
			$presenter->set('arr_params', $arr_params);

			# プレゼンタをレンダリングしテンプレートにセット
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -データ更新");
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');

			$presenter = Presenter::forge('payment/reserveedit');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -データ更新");
		}
	}

	public function action_reserveeditdone()
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
					'year'      => \Input::param('year', \Date::forge()->format('%Y')),
					'month'     => \Input::param('month', \Date::forge()->format('%m')),
					'day'       => \Input::param('day', ''),
					'search'    => \Input::param('search', ''),
					'sort_by'   => \Input::param('sort_by', 'date'),
					'direction' => \Input::param('direction', 'DESC'),
					'page'      => \Input::param('page', ''),
					'id'        => \Input::param('id'),
					'every_type'    => \Input::param('every_type', ''),
					'every_month_selected'     => \Input::param('every_month_selected', '0'),
					'every_day_selected'       => \Input::param('every_day_selected', '0'),
					'every_dayofweek_selected' => \Input::param('every_dayofweek_selected', ''), // 0は日曜なのでデフォルト空
					'date_from' => \Input::param('date_from'),
					'date_to'   => \Input::param('date_to'),
					'fix_id'    => \Input::param('fix_id', ''),
					'is_fix'    => \Input::param('is_fix', ''),
					'name'      => \Input::param('name', ''),
					'detail'    => \Input::param('detail', ''),
					'shop'      => \Input::param('shop', ''),
					'cost'      => \Input::param('cost', ''),
					'remark'    => \Input::param('remark', ''),
					'work_side_per' => \Input::param('work_side_per'),
					'use_type'      => \Input::param('use_type'),
					'paymethod_id'  => \Input::param('paymethod_id', '0'),
			);
			# バリデーション
			PaymentService::validation_for_reserveeditdone($arr_params);
			# リクエストをサービスメンバ変数にセット
			PaymentService::set_request($arr_params);
			# APIへデータ送信
			PaymentService::send_api_reserveedit_data();

			if ( ! PaymentService::is_validation_error())
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('payment/reserveedit');
				# 登録済みカテゴリーリスト取得
				$obj_fix_list = FixService::get_list();
				$presenter->set('obj_fix_list', $obj_fix_list);
				# 詳細データ取得
				$arr_payment_deital = PaymentService::get_reservedetail_from_api(\Input::post('id'));
				$presenter->set('arr_params', $arr_params);
				$presenter->set('arr_detail', $arr_payment_deital);
				$presenter->set('arr_validation_error', PaymentService::get_validation_error());
				$this->template->set_safe("contents", $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -定期支出データ更新");
				return true;
			}

			# 一覧にリダイレクト
			\Session::set_flash('message', $arr_params['date_from'].'～'. $arr_params['date_to']. ' '. $arr_params['shop']. 'のデータ更新完了しました。');
			\Response::redirect('/payment/reservelist/?'. http_build_query(array(
					'year'      => $arr_params['year'],
					'month'     => $arr_params['month'],
					'day'       => $arr_params['day'],
					'search'    => $arr_params['search'],
					'sort_by'   => $arr_params['sort_by'],
					'direction' => $arr_params['direction'],
					'page'      => $arr_params['page'],
			)), 'location', '302');
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');

			$presenter = Presenter::forge('payment/reserveedit');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -定期支出データ更新");
		}
	}


	public function action_reserveremove($id)
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! UserService::is_login())
			{
				\Response::redirect('/user/login');
			}
			# リクエスト配列へ格納
			$arr_params = array(
					'id'        => $id,
					'year'      => \Input::param('year', \Date::forge()->format('%Y')),
					'month'     => \Input::param('month', \Date::forge()->format('%m')),
					'day'       => \Input::param('day', ''),
					'search'    => \Input::param('search', ''),
					'sort_by'   => \Input::param('sort_by', 'date'),
					'direction' => \Input::param('direction', 'DESC'),
					'page'      => \Input::param('page', ''),
			);
			# バリデーション
			if ( ! PaymentService::validation_for_remove($arr_params))
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('payment/reservelist');
				$presenter->set('arr_validation_error', PaymentService::get_validation_error());
				$presenter->set('arr_params', $arr_params);
				$this->template->set_safe('contents', $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -定期支出一覧");
				return true;
			}
			# リクエストデータをサービスメンバ変数にセット
			PaymentService::set_request($arr_params);
			# APIへ送信
			PaymentService::send_api_reserveremove_data();
			# 一覧にリダイレクト
			\Session::set_flash('message', '定期支出データを一件削除しました。');
			\Response::redirect('/payment/reservelist/?'. http_build_query(array(
					'year'      => $arr_params['year'],
					'month'     => $arr_params['month'],
					'day'       => $arr_params['day'],
					'search'    => $arr_params['search'],
					'sort_by'   => $arr_params['sort_by'],
					'direction' => $arr_params['direction'],
					'page'      => $arr_params['page'],
			)), 'location', '302');
		}
		catch(\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');

			$presenter = Presenter::forge('payment/reservelist');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -定期支出一覧");
		}
	}

}
