<?php
namespace service;

use service\UserService;

class FixService extends Service
{
	private static $id;
	private static $user_id;
	private static $name;
	private static $remark;
	private static $sort;
	private static $is_fix;
	private static $is_disp;
	private static $to_aggre;

	private static $sorted = array(); // 並べ替え用配列 array([sort] => id)

	public static function set_request(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		foreach ($arr_params as $key => $val)
		{
			if (property_exists('service\FixService', $key))
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
		return true;
	}

	public static function validation_for_index()
	{
		\Log::debug('[start]'. __METHOD__);

		return true;
	}

	public static function validation_for_sort(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		if (empty($arr_params['sorted']) or ! is_array($arr_params['sorted']))
		{
			static::$arr_validation_error['sorted'] = "ソート情報が存在しません。";
			return false;
		}

		foreach ($arr_params['sorted'] as $i => $val)
		{
				$validation->add('sorted.'. $i)
					->add_rule('required')
					->add_rule('valid_string', array('numeric'))
					->add_rule('max_length', 19);
		}

		# ログイン情報も追加
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
		$validation->add('name', 'name')
			->add_rule('required')
			->add_rule('max_length', 50);
		$validation->add('remark', 'remark')
			->add_rule('max_length', 1000);
		$validation->add('is_fix', 'is_fix')
			->add_rule('max_length', 1)
			->add_rule('valid_string', array('numeric'));
		$validation->add('is_disp', 'is_disp')
			->add_rule('max_length', 1)
			->add_rule('valid_string', array('numeric'));
		$validation->add('to_aggre', 'to_aggre')
			->add_rule('max_length', 1)
			->add_rule('valid_string', array('numeric'));
		$validation->add('sort', 'sort')
			->add_rule('max_length', 3)
			->add_rule('valid_string', array('numeric'));

		# ログイン情報も追加
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

	public static function validation_for_edit(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		$validation->add('id', 'id')
			->add_rule('required')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));
		$validation->add('name', 'name')
			->add_rule('required')
			->add_rule('max_length', 50);
		$validation->add('remark', 'remark')
			->add_rule('max_length', 1000);
		$validation->add('is_fix', 'is_fix')
			->add_rule('max_length', 1)
			->add_rule('valid_string', array('numeric'));
		$validation->add('is_disp', 'is_disp')
			->add_rule('max_length', 1)
			->add_rule('valid_string', array('numeric'));
		$validation->add('sort', 'sort')
			->add_rule('max_length', 3)
			->add_rule('valid_string', array('numeric'));

		# ログイン情報も追加
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

	public static function validation_for_remove(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		$validation->add('id', 'id')
			->add_rule('required')
			->add_rule('max_length', 19)
			->add_rule('valid_string', array('numeric'));

		# ログイン情報も追加
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

	public static function get_list()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_login_user = (array)UserService::get_login_user_from_property();

			// fixマスタ取得
			$url = \Config::get('journal.api_host'). '/fix/list/.json';
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
			if (isset($obj_response) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response);
			}
			return $obj_response->result->list;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}


	public static function send_api_for_sort()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_user_info = (array)UserService::get_login_user_from_property();

			$arr_params = array(
					'user_id'    => $arr_user_info['user_id'],
					'login_hash' => $arr_user_info['login_hash'],
					'oauth_type' => $arr_user_info['oauth_type'],
					'sorted'     => static::$sorted,
			);

			$url = \Config::get('journal.api_host'). '/fix/sort/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode($arr_params));
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

	public static function send_api_for_add()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_user_info = (array)UserService::get_login_user_from_property();

			$arr_params = array(
					'user_id'    => $arr_user_info['user_id'],
					'login_hash' => $arr_user_info['login_hash'],
					'oauth_type' => $arr_user_info['oauth_type'],
					'name'       => \Input::post('name'),
					'remark'     => \Input::post('remark'),
					'is_fix'     => \Input::post('is_fix'),
					'is_disp'    => \Input::post('is_disp'),
					'to_aggre'   => \Input::post('to_aggre'),
					'sort'       => 999,
			);

			$url = \Config::get('journal.api_host'). '/fix/add/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode($arr_params));
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

	public static function send_api_for_edit()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_user_info = (array)UserService::get_login_user_from_property();

			$arr_params = array(
					'user_id'    => $arr_user_info['user_id'],
					'login_hash' => $arr_user_info['login_hash'],
					'oauth_type' => $arr_user_info['oauth_type'],
					'id'         => static::$id,
					'name'       => static::$name,
					'remark'     => static::$remark,
					'is_fix'     => static::$is_fix,
					'is_disp'    => static::$is_disp,
					'to_aggre'   => static::$to_aggre,
					'sort'       => static::$sort,
			);

			$url = \Config::get('journal.api_host'). '/fix/edit/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode($arr_params));
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

	public static function send_api_for_remove()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_user_info = (array)UserService::get_login_user_from_property();

			$arr_params = array(
					'user_id'    => $arr_user_info['user_id'],
					'login_hash' => $arr_user_info['login_hash'],
					'oauth_type' => $arr_user_info['oauth_type'],
					'id'         => static::$id,
			);

			$url = \Config::get('journal.api_host'). '/fix/remove/.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode($arr_params));
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
}
