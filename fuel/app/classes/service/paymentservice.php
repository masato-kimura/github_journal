<?php
namespace service;

use Fuel\Core\Validation;
use service\UserService;

class PaymentService extends Service
{
	const EVERY_TYPE_YEAR  = '3';
	const EVERY_TYPE_MONTH = '2';
	const EVERY_TYPE_WEEK  = '1';
	const EVERY_TYPE_DAY   = '0';

	private static $user_id;
	private static $login_hash;
	private static $oauth;

	private static $id;
	private static $fix_id;
	private static $is_fix;
	private static $name;
	private static $detail;
	private static $shop;
	private static $date;
	private static $cost;
	private static $remark;
	private static $work_side_per;
	private static $use_type;
	private static $offset;
	private static $limit;
	private static $direction;
	private static $paymethod_id;

	// 定期
	private static $date_from;
	private static $date_to;
	private static $every_type;
	private static $every_month_selected;
	private static $every_day_selected;
	private static $every_dayofweek_selected;
	private static $payment_reserve_status;

	// 共通
	private static $year;
	private static $month;
	private static $day;
	private static $search;
	private static $page;
	private static $sort_by;

	private static $sort;

	public static function set_request(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		foreach ($arr_params as $key => $val)
		{
			if (property_exists('service\PaymentService', $key))
			{
				if (is_array($val))
				{
					static::$$key = $val;
				}
				else
				{
					static::$$key = trim($val);
				}
			}
		}
		static::$offset    = isset(static::$offset)? static::$offset: '0';
		static::$limit     = isset(static::$limit)? static::$limit: '100';
		static::$sort_by   = isset(static::$sort_by)? static::$sort_by: 'date';
		static::$direction = isset(static::$direction)? static::$direction: 'DESC';
		return true;
	}

	public static function validation_for_list(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		$validation->add('year', 'year')
			->add_rule('valid_string', array('numeric'))
			->add_rule('exact_length', 4);
		$validation->add('month', 'month')
			->add_rule('valid_string', array('numeric'))
			->add_rule('exact_length', 2);
		$validation->add('day', 'day')
			->add_rule('valid_string', array('numeric'))
			->add_rule('exact_length', 2);
		$validation->add('search', 'search')
			->add_rule('max_length', 100);
		$validation->add('page', 'page')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));
		$validation->add('sort_by', 'sort_by')
			->add_rule('valid_string', array('numeric', 'dashes', 'alpha'))
			->add_rule('max_length', 20);
		$validation->add('direction', 'direction')
			->add_rule('match_pattern', '/(asc)|(desc)/i');

		$arr_params = array_merge($arr_params, (array)UserService::get_login_user_from_property());
		if ( ! $validation->run($arr_params))
		{
			foreach ($validation->error() as $i => $error)
			{
				static::$arr_validation_error[$i] = $error->get_message();
			}
			return false;
		}
		return true;
	}

	public static function validation_for_add(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		$validation->add('year', 'year')
			->add_rule('exact_length', 4)
			->add_rule('valid_string', array('numeric'));
		$validation->add('month', 'month')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('day', 'day')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('search', 'search')
			->add_rule('max_length', 100);
		$validation->add('sort_by', 'sort_by')
			->add_rule('max_length', 20);
		$validation->add('direction', 'direction')
			->add_rule('match_pattern', '/(asc)|(desc)/i');
		$validation->add('page', 'page')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));

		$arr_params = array_merge($arr_params, (array)UserService::get_login_user_from_property());
		if ( ! $validation->run($arr_params))
		{
			foreach ($validation->error() as $i => $error)
			{
				static::$arr_validation_error[$i] = $error->get_message();
			}
			return false;
		}
		return true;
	}


	public static function validation_for_adddone(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		# required
		$validation->add('fix_id', 'カテゴリー')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'))
			->add_rule('numeric_min', '1')
			->add_rule('max_length', 19);
		$validation->add('name', 'その他のカテゴリー')
			->add_rule('required')
			->add_rule('max_length', 50);
		$validation->add('detail', '内訳')
//			->add_rule('required')
			->add_rule('max_length', 50);
		$validation->add('date', '日時')
			->add_rule('required')
			->add_rule('valid_date', 'Y-m-d');
		$validation->add('cost', '金額')
			->add_rule('required')
			->add_rule('max_length', 11)
			->add_rule('valid_string', array('numeric'));
		$validation->add('work_side_per', 'work_side_per')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'))
			->add_rule('numeric_min', 0)
			->add_rule('max_length', 3);
		$validation->add('use_type', 'use_type')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'))
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 3);
		$validation->add('paymethod_id', '支払い方法')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'));

		# not required
		$validation->add('shop', '購入店舗')
			->add_rule('max_length', 50);
		$validation->add('remark', '備考')
			->add_rule('max_length', 1000);
		$validation->add('sort', 'sort')
			->add_rule('max_length', 10)
			->add_rule('valid_string', array('numeric'));
		$validation->add('is_fix', 'is_fix')
			->add_rule('valid_string', array('numeric'))
			->add_rule('max_length', 1);

		# paging request
		$validation->add('year', 'year')
			->add_rule('exact_length', 4)
			->add_rule('valid_string', array('numeric'));
		$validation->add('month', 'month')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('day', 'day')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('search', 'search')
			->add_rule('max_length', 100);
		$validation->add('sort_by', 'sort_by')
			->add_rule('max_length', 20);
		$validation->add('direction', 'direction')
			->add_rule('match_pattern', '/(asc)|(desc)/i');
		$validation->add('page', 'page')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));

		$arr_params = array_merge($arr_params, (array)UserService::get_login_user_from_property());
		if ( ! $validation->run($arr_params))
		{
			foreach ($validation->error() as $i => $error)
			{
				switch ($i)
				{
					case "fix_id":
						static::$arr_validation_error[$i] = "カテゴリーを選択してください。";
						break;
					default:
						static::$arr_validation_error[$i] = $error->get_message();
				}
			}
			return false;
		}
		return true;
	}

	public static function validation_for_edit(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		# required
		$validation->add('id', 'id')
			->add_rule('required')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));

		# paging request
		$validation->add('year', 'year')
			->add_rule('exact_length', 4)
			->add_rule('valid_string', array('numeric'));
		$validation->add('month', 'month')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('day', 'day')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('search', 'search')
			->add_rule('max_length', 100);
		$validation->add('sort_by', 'sort_by')
			->add_rule('max_length', 20);
		$validation->add('direction', 'direction')
			->add_rule('match_pattern', '/(asc)|(desc)/i');
		$validation->add('page', 'page')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));

		$arr_params = array_merge($arr_params, (array)UserService::get_login_user_from_property());
		if ( ! $validation->run($arr_params))
		{
			foreach ($validation->error() as $i => $error)
			{
				if ($i === 'id')
				{
					throw new \Exception($error->get_message(). '['. $i. ']');
				}
				static::$arr_validation_error[$i] = $error->get_message();
			}
			return false;
		}
		return true;
	}

	public static function validation_for_editdone(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		# required
		$validation->add('id', 'id')
			->add_rule('required')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));
		$validation->add('fix_id', 'カテゴリー')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'))
			->add_rule('numeric_min', '1')
			->add_rule('max_length', 19);
		$validation->add('name', 'その他のカテゴリー')
			->add_rule('required')
			->add_rule('max_length', 50);
		$validation->add('detail', '内訳')
//			->add_rule('required')
			->add_rule('max_length', 50);
		$validation->add('date', '日時')
			->add_rule('required')
			->add_rule('valid_date', 'Y-m-d');
		$validation->add('cost', '金額')
			->add_rule('required')
			->add_rule('max_length', 11)
			->add_rule('valid_string', array('numeric'));
		$validation->add('work_side_per', 'work_side_per')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'))
			->add_rule('numeric_min', 0)
			->add_rule('max_length', 3);
		$validation->add('use_type', 'use_type')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'))
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 3);
		$validation->add('paymethod_id', '支払い方法')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'));

		# not required
		$validation->add('shop', '購入店舗')
			->add_rule('max_length', 50);
		$validation->add('remark', '備考')
			->add_rule('max_length', 1000);
		$validation->add('sort', 'sort')
			->add_rule('max_length', 10)
			->add_rule('valid_string', array('numeric'));
		$validation->add('is_fix', 'is_fix')
			->add_rule('valid_string', array('numeric'))
			->add_rule('max_length', 1);
		$validation->add('payment_reserve_status', 'payment_reserve_status')
			->add_rule('valid_string', array('numeric'))
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 1);

		# paging request
		$validation->add('year', 'year')
			->add_rule('exact_length', 4)
			->add_rule('valid_string', array('numeric'));
		$validation->add('month', 'month')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('day', 'day')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('search', 'search')
			->add_rule('max_length', 100);
		$validation->add('sort_by', 'sort_by')
			->add_rule('max_length', 20);
		$validation->add('direction', 'direction')
			->add_rule('match_pattern', '/(asc)|(desc)/i');
		$validation->add('page', 'page')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));

		# ログイン情報も追加
		$arr_params = array_merge($arr_params, (array)UserService::get_login_user_from_property());
		if ( ! $validation->run($arr_params))
		{
			foreach ($validation->error() as $i => $error)
			{
				switch ($i)
				{
					case "fix_id":
						static::$arr_validation_error[$i] = "カテゴリーを選択してください。";
						break;
					case "id":
						throw new \Exception($error->get_message(). '['. $i. ']');
						break;
					default:
						static::$arr_validation_error[$i] = $error->get_message();
				}
			}
			return false;
		}
		return true;
	}

	public static function validation_for_remove(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		# required
		$validation->add('id', 'id')
			->add_rule('required')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));

		# paging request
		$validation->add('year', 'year')
			->add_rule('exact_length', 4)
			->add_rule('valid_string', array('numeric'));
		$validation->add('month', 'month')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('day', 'day')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('search', 'search')
			->add_rule('max_length', 100);
		$validation->add('sort_by', 'sort_by')
			->add_rule('max_length', 20);
		$validation->add('direction', 'direction')
			->add_rule('match_pattern', '/(asc)|(desc)/i');
		$validation->add('page', 'page')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));

		# ログイン情報も追加
		$arr_params = array_merge($arr_params, (array)UserService::get_login_user_from_property());
		if ( ! $validation->run($arr_params))
		{
			foreach ($validation->error() as $i => $error)
			{
				switch ($i)
				{
					case "id":
						throw new \Exception($error->get_message(). '['. $i. ']');
						break;
					default:
						static::$arr_validation_error[$i] = $error->get_message();
				}
			}
			return false;
		}
		return true;
	}


	public static function validation_for_reservelist(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		$validation->add('year', 'year')
		->add_rule('valid_string', array('numeric'))
		->add_rule('exact_length', 4);
		$validation->add('month', 'month')
		->add_rule('valid_string', array('numeric'))
		->add_rule('exact_length', 2);
		$validation->add('day', 'day')
		->add_rule('valid_string', array('numeric'))
		->add_rule('exact_length', 2);
		$validation->add('search', 'search')
		->add_rule('max_length', 100);
		$validation->add('page', 'page')
		->add_rule('max_length', 19)
		->add_rule('valid_string', array('numeric'));
		$validation->add('sort_by', 'sort_by')
		->add_rule('valid_string', array('numeric', 'dashes', 'alpha'))
		->add_rule('max_length', 20);
		$validation->add('direction', 'direction')
		->add_rule('match_pattern', '/(asc)|(desc)/i');

		$arr_params = array_merge($arr_params, (array)UserService::get_login_user_from_property());
		if ( ! $validation->run($arr_params))
		{
			foreach ($validation->error() as $i => $error)
			{
				static::$arr_validation_error[$i] = $error->get_message();
			}
			return false;
		}
		return true;
	}


	public static function validation_for_reserveadddone(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		# required
		$validation->add('fix_id', 'カテゴリー')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'))
			->add_rule('numeric_min', '1')
			->add_rule('max_length', 19);
		$validation->add('name', 'その他のカテゴリー')
			->add_rule('required')
			->add_rule('max_length', 50);
		$validation->add('detail', '内訳')
			//			->add_rule('required')
			->add_rule('max_length', 50);
		$validation->add('every_type', '定期間隔')
			->add_rule('required')
			->add_rule('valid_string', 'numeric')
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 12);
		$validation->add('every_month_selected', '定期月選択')
			->add_rule('valid_string', 'numeric')
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 12);
		$validation->add('every_day_selected', '定期日選択')
			->add_rule('valid_string', 'numeric')
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 31);
		$validation->add('every_dayofweek_selected', '定期曜日選択')
			->add_rule('valid_string', 'numeric')
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 6);
		$validation->add('date_from', '有効期限開始日')
			->add_rule('required')
			->add_rule('valid_date', 'Y-m-d');
		$validation->add('date_to', '有効期限終了日')
			->add_rule('required')
			->add_rule('valid_date', 'Y-m-d');
		$validation->add('cost', '金額')
			->add_rule('required')
			->add_rule('max_length', 11)
			->add_rule('valid_string', array('numeric'));
		$validation->add('work_side_per', 'work_side_per')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'))
			->add_rule('numeric_min', 0)
			->add_rule('max_length', 3);
		$validation->add('use_type', 'use_type')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'))
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 3);
		$validation->add('paymethod_id', '支払い方法')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'));

		# not required
		$validation->add('shop', '購入店舗')
			->add_rule('max_length', 50);
		$validation->add('remark', '備考')
			->add_rule('max_length', 1000);
		$validation->add('sort', 'sort')
			->add_rule('max_length', 10)
			->add_rule('valid_string', array('numeric'));
		$validation->add('is_fix', 'is_fix')
			->add_rule('valid_string', array('numeric'))
			->add_rule('max_length', 1);

		# paging request
		$validation->add('year', 'year')
			->add_rule('exact_length', 4)
			->add_rule('valid_string', array('numeric'));
		$validation->add('month', 'month')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('day', 'day')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('search', 'search')
			->add_rule('max_length', 100);
		$validation->add('sort_by', 'sort_by')
			->add_rule('max_length', 20);
		$validation->add('direction', 'direction')
			->add_rule('match_pattern', '/(asc)|(desc)/i');
		$validation->add('page', 'page')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));

		$arr_params = array_merge($arr_params, (array)UserService::get_login_user_from_property());
		$obj_date_from = new \DateTime($arr_params['date_from']);
		$obj_date_to   = new \DateTime($arr_params['date_to']);
		if ($obj_date_from->getTimestamp() > $obj_date_to->getTimestamp())
		{
			static::$arr_validation_error['date_from'] = "期間の設定を確認してください";
			static::$arr_validation_error['date_to'] = "期間の設定を確認してください";
		}

		switch ($arr_params['every_type'])
		{
			case static::EVERY_TYPE_YEAR:
				if (empty($arr_params['every_month_selected']))
				{
					$error_message_every = "定期月を選択してください";
					static::$arr_validation_error['every_month_selected'] = $error_message_every;
				}
				if (empty($arr_params['every_day_selected']))
				{
					$error_message_every= "定期日を選択してください";
					static::$arr_validation_error['every_day_selected'] = $error_message_every;
				}
				break;
			case static::EVERY_TYPE_MONTH:
				if (empty($arr_params['every_day_selected']))
				{
					$error_message_every= "定期日を選択してください";
					static::$arr_validation_error['every_day_selected'] = $error_message_every;
				}
				break;
			case static::EVERY_TYPE_WEEK:
				if ($arr_params['every_dayofweek_selected'] === "" or
					$arr_params['every_dayofweek_selected'] === NULL)
				{
					$error_message_every= "定期曜日を選択してください";
					static::$arr_validation_error['every_dayofweek_selected'] = $error_message_every;
				}
				break;
		}

		if ( ! $validation->run($arr_params))
		{
			foreach ($validation->error() as $i => $error)
			{
				switch ($i)
				{
					case "fix_id":
						static::$arr_validation_error[$i] = "カテゴリーを選択してください。";
						break;
					default:
						static::$arr_validation_error[$i] = $error->get_message();
				}
			}
			return false;
		}

		if ( ! empty($error_message_every))
		{
			return false;
		}


		return true;
	}


	public static function validation_for_reserveedit(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		# required
		$validation->add('id', 'id')
			->add_rule('required')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));

		# paging request
		$validation->add('year', 'year')
			->add_rule('exact_length', 4)
			->add_rule('valid_string', array('numeric'));
		$validation->add('month', 'month')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('day', 'day')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('search', 'search')
			->add_rule('max_length', 100);
		$validation->add('sort_by', 'sort_by')
			->add_rule('max_length', 20);
		$validation->add('direction', 'direction')
			->add_rule('match_pattern', '/(asc)|(desc)/i');
		$validation->add('page', 'page')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));

		$arr_params = array_merge($arr_params, (array)UserService::get_login_user_from_property());
		if ( ! $validation->run($arr_params))
		{
			foreach ($validation->error() as $i => $error)
			{
				if ($i === 'id')
				{
					throw new \Exception($error->get_message(). '['. $i. ']');
				}
				static::$arr_validation_error[$i] = $error->get_message();
			}
			return false;
		}
		return true;
	}

	public static function validation_for_reserveeditdone(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		# required
		$validation->add('id', 'id')
			->add_rule('required')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));
		$validation->add('fix_id', 'カテゴリー')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'))
			->add_rule('numeric_min', '1')
			->add_rule('max_length', 19);
		$validation->add('name', 'その他のカテゴリー')
			->add_rule('required')
			->add_rule('max_length', 50);
		$validation->add('detail', '内訳')
			//			->add_rule('required')
			->add_rule('max_length', 50);
		$validation->add('every_type', '定期間隔')
			->add_rule('required')
			->add_rule('valid_string', 'numeric')
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 12);
		$validation->add('every_month_selected', '定期月選択')
			->add_rule('valid_string', 'numeric')
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 12);
		$validation->add('every_day_selected', '定期日選択')
			->add_rule('valid_string', 'numeric')
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 31);
		$validation->add('every_dayofweek_selected', '定期曜日選択')
			->add_rule('valid_string', 'numeric')
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 6);
		$validation->add('date_from', '定期期間開始日')
			->add_rule('required')
			->add_rule('valid_date', 'Y-m-d');
		$validation->add('date_to', '定期期間終了日')
			->add_rule('required')
			->add_rule('valid_date', 'Y-m-d');
		$validation->add('cost', '金額')
			->add_rule('required')
			->add_rule('max_length', 11)
			->add_rule('valid_string', array('numeric'));
		$validation->add('work_side_per', 'work_side_per')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'))
			->add_rule('numeric_min', 0)
			->add_rule('max_length', 3);
		$validation->add('use_type', 'use_type')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'))
			->add_rule('numeric_min', 0)
			->add_rule('numeric_max', 3);
		$validation->add('paymethod_id', '支払い方法')
			->add_rule('required')
			->add_rule('valid_string', array('numeric'));

		# not required
		$validation->add('shop', '購入店舗')
			->add_rule('max_length', 50);
		$validation->add('remark', '備考')
			->add_rule('max_length', 1000);
		$validation->add('sort', 'sort')
			->add_rule('max_length', 10)
			->add_rule('valid_string', array('numeric'));
		$validation->add('is_fix', 'is_fix')
			->add_rule('valid_string', array('numeric'))
			->add_rule('max_length', 1);

		# paging request
		$validation->add('year', 'year')
			->add_rule('exact_length', 4)
			->add_rule('valid_string', array('numeric'));
		$validation->add('month', 'month')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('day', 'day')
			->add_rule('exact_length', 2)
			->add_rule('valid_string', array('numeric'));
		$validation->add('search', 'search')
			->add_rule('max_length', 100);
		$validation->add('sort_by', 'sort_by')
			->add_rule('max_length', 20);
		$validation->add('direction', 'direction')
			->add_rule('match_pattern', '/(asc)|(desc)/i');
		$validation->add('page', 'page')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));

		# ログイン情報も追加
		$arr_params = array_merge($arr_params, (array)UserService::get_login_user_from_property());
		$obj_date_from = new \DateTime($arr_params['date_from']);
		$obj_date_to   = new \DateTime($arr_params['date_to']);
		if ($obj_date_from->getTimestamp() > $obj_date_to->getTimestamp())
		{
			static::$arr_validation_error['date_from'] = "期間の設定を確認してください";
			static::$arr_validation_error['date_to'] = "期間の設定を確認してください";
		}

		if ( ! $validation->run($arr_params))
		{
			foreach ($validation->error() as $i => $error)
			{
				switch ($i)
				{
					case "fix_id":
						static::$arr_validation_error[$i] = "カテゴリーを選択してください。";
						break;
					case "id":
						throw new \Exception($error->get_message(). '['. $i. ']');
						break;
					default:
						static::$arr_validation_error[$i] = $error->get_message();
				}
			}
			return false;
		}
		return true;
	}



	public static function get_count_from_api()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_login_user = (array)UserService::get_login_user_from_property();

			$url = \Config::get('journal.api_host'). '/payment/count/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'    => $arr_login_user['user_id'],
					'login_hash' => $arr_login_user['login_hash'],
					'oauth_type' => $arr_login_user['oauth_type'],
					'year'       => static::$year,
					'month'      => static::$month,
					'day'        => static::$day,
					'search'     => static::$search,
			)));
			$curl->set_options(array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_CONNECTTIMEOUT => 60,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			));
			$curl->execute();
			$json_response = $curl->response();
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response, $obj_response->code);
			}
			return $obj_response->result->count;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}


	public static function get_list_from_api($offset=0, $limit=30, $is_ajax=false)
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_login_user = (array)UserService::get_login_user_from_property();

			if ($is_ajax)
			{
				$url = \Config::get('journal.api_host'). '/payment/list/true/.json';
			}
			else
			{
				$url = \Config::get('journal.api_host'). '/payment/list/.json';
			}
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'    => $arr_login_user['user_id'],
					'login_hash' => $arr_login_user['login_hash'],
					'oauth_type' => $arr_login_user['oauth_type'],
					'year'       => static::$year,
					'month'      => static::$month,
					'day'        => static::$day,
					'search'     => static::$search,
					'sort_by'    => static::$sort_by,
					'direction'  => static::$direction,
					'offset'     => $offset,
					'limit'      => $limit,
			)));
			$curl->set_options(array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_CONNECTTIMEOUT => 60,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			));
			$curl->execute();
			$json_response = $curl->response();
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response, $obj_response->code);
			}
			return $obj_response->result;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}


	public static function get_reservecount_from_api()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_login_user = (array)UserService::get_login_user_from_property();

			$url = \Config::get('journal.api_host'). '/payment/reservecount/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'    => $arr_login_user['user_id'],
					'login_hash' => $arr_login_user['login_hash'],
					'oauth_type' => $arr_login_user['oauth_type'],
					'year'       => static::$year,
					'month'      => static::$month,
					'day'        => static::$day,
					'search'     => static::$search,
			)));
			$curl->set_options(array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_CONNECTTIMEOUT => 60,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			));
			$curl->execute();
			$json_response = $curl->response();
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response, $obj_response->code);
			}
			return $obj_response->result->count;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}


	public static function get_reservelist_from_api($offset=0, $limit=30, $is_ajax=false)
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_login_user = (array)UserService::get_login_user_from_property();

			if ($is_ajax)
			{
				$url = \Config::get('journal.api_host'). '/payment/reservelist/true/.json';
			}
			else
			{
				$url = \Config::get('journal.api_host'). '/payment/reservelist/.json';
			}
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'    => $arr_login_user['user_id'],
					'login_hash' => $arr_login_user['login_hash'],
					'oauth_type' => $arr_login_user['oauth_type'],
					'year'       => static::$year,
					'month'      => static::$month,
					'day'        => static::$day,
					'search'     => static::$search,
					'sort_by'    => static::$sort_by,
					'direction'  => static::$direction,
					'offset'     => $offset,
					'limit'      => $limit,
			)));
			$curl->set_options(array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_CONNECTTIMEOUT => 60,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			));
			$curl->execute();
			$json_response = $curl->response();
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response, $obj_response->code);
			}
			return $obj_response->result;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}


	public static function get_detail_from_api($id)
	{
		try
		{
			$arr_login_user = (array)UserService::get_login_user_from_property();

			$url = \Config::get('journal.api_host'). '/payment/detail/'. $id. '/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'    => $arr_login_user['user_id'],
					'login_hash' => $arr_login_user['login_hash'],
					'oauth_type' => $arr_login_user['oauth_type'],
			)));
			$curl->set_options(array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_CONNECTTIMEOUT => 60,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			));
			$curl->execute();
			$json_response = $curl->response();
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response);
			}
			return $obj_response->result->detail;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}


	public static function send_api_add_data()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_login_user = (array)UserService::get_login_user_from_property();

			$url = \Config::get('journal.api_host'). '/payment/add/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'       => $arr_login_user['user_id'],
					'login_hash'    => $arr_login_user['login_hash'],
					'oauth_type'    => $arr_login_user['oauth_type'],
					'name'          => static::$name,
					'detail'        => static::$detail,
					'date'          => static::$date,
					'fix_id'        => static::$fix_id,
					'is_fix'        => static::$is_fix,
					'cost'          => static::$cost,
					'work_side_per' => static::$work_side_per,
					'use_type'       => static::$use_type,
					'sort'          => static::$sort,
					'shop'          => static::$shop,
					'remark'        => static::$remark,
					'paymethod_id'  => static::$paymethod_id,
			)));
			$curl->set_options(array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_CONNECTTIMEOUT => 60,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			));
			$curl->execute();
			$json_response = $curl->response();
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response);
			}
			return true;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}

	public static function send_api_edit_data()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! empty(static::$arr_validation_error))
			{
				return false;
			}

			$arr_login_user = (array)UserService::get_login_user_from_property();

			$url = \Config::get('journal.api_host'). '/payment/edit/'. static::$id.'/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'       => $arr_login_user['user_id'],
					'login_hash'    => $arr_login_user['login_hash'],
					'oauth_type'    => $arr_login_user['oauth_type'],
					'name'          => static::$name,
					'detail'        => static::$detail,
					'date'          => static::$date,
					'fix_id'        => static::$fix_id,
					'is_fix'        => static::$is_fix,
					'cost'          => static::$cost,
					'work_side_per' => static::$work_side_per,
					'use_type'      => static::$use_type,
					'sort'          => static::$sort,
					'shop'          => static::$shop,
					'remark'        => static::$remark,
					'paymethod_id'  => static::$paymethod_id,
					'payment_reserve_status' => static::$payment_reserve_status,
			)));
			$curl->set_options(array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_CONNECTTIMEOUT => 60,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			));
			$curl->execute();
			$json_response = $curl->response();
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response);
			}
			return true;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}

	public static function send_api_remove_data()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_login_user = (array)UserService::get_login_user_from_property();

			$url = \Config::get('journal.api_host'). '/payment/remove/'. static::$id. '/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'    => $arr_login_user['user_id'],
					'login_hash' => $arr_login_user['login_hash'],
					'oauth_type' => $arr_login_user['oauth_type'],
					'id'         => static::$id,
			)));
			$curl->set_options(array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_CONNECTTIMEOUT => 60,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			));
			$curl->execute();
			$json_response = $curl->response();
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response);
			}
			return true;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}


	public static function send_api_reserveadd_data()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_login_user = (array)UserService::get_login_user_from_property();

			$url = \Config::get('journal.api_host'). '/payment/reserveadd/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'       => $arr_login_user['user_id'],
					'login_hash'    => $arr_login_user['login_hash'],
					'oauth_type'    => $arr_login_user['oauth_type'],
					'name'          => static::$name,
					'detail'        => static::$detail,
					'date_from'     => static::$date_from,
					'date_to'       => static::$date_to,
					'every_type'    => static::$every_type,
					'every_month_selected'     => static::$every_month_selected,
					'every_day_selected'       => static::$every_day_selected,
					'every_dayofweek_selected' => static::$every_dayofweek_selected,
					'fix_id'        => static::$fix_id,
					'is_fix'        => static::$is_fix,
					'cost'          => static::$cost,
					'work_side_per' => static::$work_side_per,
					'use_type'       => static::$use_type,
					'sort'          => static::$sort,
					'shop'          => static::$shop,
					'remark'        => static::$remark,
					'paymethod_id'  => static::$paymethod_id,
			)));
			$curl->set_options(array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_CONNECTTIMEOUT => 60,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			));
			$curl->execute();
			$json_response = $curl->response();
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response);
			}
			return true;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}


	public static function send_api_reserveedit_data()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! empty(static::$arr_validation_error))
			{
				return false;
			}

			$arr_login_user = (array)UserService::get_login_user_from_property();

			$url = \Config::get('journal.api_host'). '/payment/reserveedit/'. static::$id.'/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'       => $arr_login_user['user_id'],
					'login_hash'    => $arr_login_user['login_hash'],
					'oauth_type'    => $arr_login_user['oauth_type'],
					'name'          => static::$name,
					'detail'        => static::$detail,
					'date_from'     => static::$date_from,
					'date_to'       => static::$date_to,
					'every_type'    => static::$every_type,
					'every_month_selected'     => static::$every_month_selected,
					'every_day_selected'       => static::$every_day_selected,
					'every_dayofweek_selected' => static::$every_dayofweek_selected,
					'fix_id'        => static::$fix_id,
					'is_fix'        => static::$is_fix,
					'cost'          => static::$cost,
					'work_side_per' => static::$work_side_per,
					'use_type'      => static::$use_type,
					'sort'          => static::$sort,
					'shop'          => static::$shop,
					'remark'        => static::$remark,
					'paymethod_id'  => static::$paymethod_id,
			)));
			$curl->set_options(array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_CONNECTTIMEOUT => 60,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			));
			$curl->execute();
			$json_response = $curl->response();
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response);
			}
			return true;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}


	public static function send_api_reserveremove_data()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_login_user = (array)UserService::get_login_user_from_property();

			$url = \Config::get('journal.api_host'). '/payment/reserveremove/'. static::$id. '/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'    => $arr_login_user['user_id'],
					'login_hash' => $arr_login_user['login_hash'],
					'oauth_type' => $arr_login_user['oauth_type'],
					'id'         => static::$id,
			)));
			$curl->set_options(array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_CONNECTTIMEOUT => 60,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			));
			$curl->execute();
			$json_response = $curl->response();
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response);
			}
			return true;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}


	public static function get_reservedetail_from_api($id)
	{
		try
		{
			$arr_login_user = (array)UserService::get_login_user_from_property();

			$url = \Config::get('journal.api_host'). '/payment/reservedetail/'. $id. '/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'    => $arr_login_user['user_id'],
					'login_hash' => $arr_login_user['login_hash'],
					'oauth_type' => $arr_login_user['oauth_type'],
			)));
			$curl->set_options(array(
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_CONNECTTIMEOUT => 60,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			));
			$curl->execute();
			$json_response = $curl->response();
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response);
			}
			return $obj_response->result->detail;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}

}
