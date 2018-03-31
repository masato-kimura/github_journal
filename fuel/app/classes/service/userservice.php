<?php
namespace service;

use Fuel\Core\Format;

class UserService extends Service
{
	private static $user_id;
	private static $user_name;
	private static $picture_url;
	private static $email;
	private static $password;
	private static $passwordchk;
	private static $gender;
	private static $link;
	private static $locale;
	private static $oauth_type;
	private static $oauth_id;
	private static $login_hash;
	private static $first_name;
	private static $last_name;
	private static $password_digits;
	private static $birthday;
	private static $birthday_year;
	private static $birthday_month;
	private static $birthday_day;
	private static $birthday_secret;
	private static $old;
	private static $old_secret;
	private static $country;
	private static $postal_code;
	private static $pref;
	private static $locality;
	private static $street;
	private static $profile_fields;
	private static $facebook_url;
	private static $twitter_url;
	private static $google_url;
	private static $member_type = 0;
	private static $is_auto_login = false;
	private static $decide_hash;
	private static $reissue_hash;
	private static $is_email_change;
	private static $is_password_change;
	private static $last_login;

	public static function set_request(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		foreach ($arr_params as $key => $val)
		{
			if (property_exists('service\UserService', $key))
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

	/**
	 * 引数のユーザ情報配列をメンバ変数にセット
	 * @param array $arr_user_info
	 * @return boolean
	 */
	public static function set_login_user(array $arr_user_info)
	{
		\Log::debug('[start]'. __METHOD__);

		foreach ($arr_user_info as $i => $val)
		{
			if (property_exists('service\UserService', $i))
			{
				static::$$i = $val;
			}
		}
		return true;
	}

	/**
	 * メンバ変数からセッションにユーザ情報をセット
	 * @return boolean
	 */
	public static function set_user_info_to_session()
	{
		\Log::debug('[start]'. __METHOD__);

		$arr_user_info = array(
				'user_id'        => static::$user_id,
				'user_name'      => static::$user_name,
				'picture_url'    => static::$picture_url,
				'email'          => static::$email,
				'password'       => static::$password,
				'password_digits'=> static::$password_digits,
				'gender'         => static::$gender,
				'link'           => static::$link,
				'locale'         => static::$locale,
				'oauth_type'     => static::$oauth_type,
				'oauth_id'       => static::$oauth_id,
				'is_auto_login'  => static::$is_auto_login,
				'login_hash'     => static::$login_hash,
				'first_name'     => static::$first_name,
				'last_name'      => static::$last_name,
				'birthday'       =>	static::$birthday,
				'birthday_year'  => static::$birthday_year,
				'birthday_month' => static::$birthday_month,
				'birthday_day'   => static::$birthday_day,
				'birthday_secret'=> static::$birthday_secret,
				'old'            => static::$old,
				'old_secret'     => static::$old_secret,
				'country'        => static::$country,
				'postal_code'    => static::$postal_code,
				'pref'           => static::$pref,
				'locality'       => static::$locality,
				'street'         => static::$street,
				'profile_fields' => static::$profile_fields,
				'facebook_url'   => static::$facebook_url,
				'twitter_url'    => static::$twitter_url,
				'google_url'     => static::$google_url,
				'member_type'    => static::$member_type,
				'last_login'     => static::$last_login,
		);
		SessionService::set('user_info', $arr_user_info);
		return true;
	}

	/**
	 * セッションにser_info.auto_login値にtrueを設定
	 * @return boolean
	 */
	public static function set_auto_login_to_session()
	{
		\Log::debug('[start]'. __METHOD__);

		\Log::info('セッションauto_login値にtrueを設定');
		SessionService::set('auto_login', true);
		return true;
	}

	/**
	 * セッション情報を削除
	 */
	public static function session_destroy()
	{
		\Log::debug('[start]'. __METHOD__);

		return SessionService::destroy();
	}


	/**
	 * メンバ変数をオブジェクトで返す
	 * @return \stdClass
	 */
	public static function get_user_info_from_property()
	{
		\Log::debug('[start]'. __METHOD__);

		$obj_user_info = new \stdClass();
		$obj_user_info->user_name  = static::$user_name;
		$obj_user_info->user_id    = static::$user_id;
		$obj_user_info->picture_url = static::$picture_url;
		$obj_user_info->email      = static::$email;
		$obj_user_info->gender     = static::$gender;
		$obj_user_info->link       = static::$link;
		$obj_user_info->locale     = static::$locale;
		$obj_user_info->oauth_type = static::$oauth_type;
		$obj_user_info->oauth_id   = static::$oauth_id;
		$obj_user_info->is_auto_login = static::$is_auto_login;
		$obj_user_info->login_hash = static::$login_hash;
		$obj_user_info->first_name = static::$first_name;
		$obj_user_info->last_name  = static::$last_name;
		$obj_user_info->password_digits = static::$password_digits;
		$obj_user_info->birthday   = static::$birthday;
		$obj_user_info->birthday_year = static::$birthday_year;
		$obj_user_info->birthday_month = static::$birthday_month;
		$obj_user_info->birthday_day = static::$birthday_day;
		$obj_user_info->birthday_secret = static::$birthday_secret;
		$obj_user_info->old        = static::$old;
		$obj_user_info->old_secret = static::$old_secret;
		$obj_user_info->country    = static::$country;
		$obj_user_info->postal_code = static::$postal_code;
		$obj_user_info->pref       = static::$pref;
		$obj_user_info->locality   = static::$locality;
		$obj_user_info->street = static::$street;
		$obj_user_info->profile_fields = static::$profile_fields;
		$obj_user_info->facebook_url = static::$facebook_url;
		$obj_user_info->twitter_url = static::$twitter_url;
		$obj_user_info->google_url  = static::$google_url;
		$obj_user_info->member_type = static::$member_type;

		return $obj_user_info;
	}

	/**
	 * メンバ変数をオブジェクトで返す
	 * @return \stdClass
	 */
	public static function get_login_user_from_property()
	{
		\Log::debug('[start]'. __METHOD__);

		return static::get_user_info_from_property();
	}




	/**
	 * default validation
	 * これは独立させたい
	 * @return boolean
	 */
	public static function validation_for_login_user(array $arr_user_info)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();
		/* 個別バリデート設定 */
		# user_id
		$v = $validation->add('user_id', 'user_id');
		$v->add_rule('required');
		$v->add_rule('valid_string', array('numeric'));
		$v->add_rule('max_length', '19');
		# login_hash
		$v = $validation->add('login_hash', 'login_hash');
		$v->add_rule('required');
		$v->add_rule('max_length', '32'); // md5
		# oauth_type
		$v = $validation->add('oauth_type', 'oauth_type');
		$v->add_rule('required');
		$v->add_rule('match_pattern', '/(email)|(line)|(facebook)|(google)|(twitter)|(yahoo)/');
		if ( ! $validation->run($arr_user_info))
		{
			foreach ($validation->error() as $i => $error)
			{
				UserService::session_destroy();
				throw new \Exception($i. ':'. $error->get_message());
			}
		}
		return true;
	}

	public static function validation_for_regist(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();
		$v = $validation->add('user_name', 'お名前');
		$v->add_rule('required');
		$v->add_rule('max_length', 30);

		$v = $validation->add('email', 'メールアドレス');
		$v->add_rule('valid_email');
		$v->add_rule('required');

		$v = $validation->add('password', 'パスワード');
		$v->add_rule('valid_string', array('numeric', 'alpha'));
		$v->add_rule('min_length', 4);
		$v->add_rule('max_length', 16);
		$v->add_rule('required');

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

	public static function validation_for_regist_facebook(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();
		$v = $validation->add('user_name', 'お名前');
		$v->add_rule('required');
		$v->add_rule('max_length', 30);

		$v = $validation->add('email', 'メールアドレス');
		$v->add_rule('valid_email');

		$v = $validation->add('oauth_type', 'oauth_type');
		$v->add_rule('required');
		$v->add_rule('match_pattern', '/(facebook)/');

		$v = $validation->add('oauth_id', 'oauth_id');
		$v->add_rule('required');
		$v->add_rule('max_length', 200);
		$v->add_rule('valid_string', array('numeric', 'alpha', 'dashes'));

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

	public static function validation_for_regist_line(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();
		$v = $validation->add('user_name', 'お名前');
		$v->add_rule('required');
		$v->add_rule('max_length', 30);

		$v = $validation->add('email', 'メールアドレス');
		$v->add_rule('valid_email');

		$v = $validation->add('oauth_type', 'oauth_type');
		$v->add_rule('required');
		$v->add_rule('match_pattern', '/(line)/');

		$v = $validation->add('oauth_id', 'oauth_id');
		$v->add_rule('required');
		$v->add_rule('max_length', 200);
		$v->add_rule('valid_string', array('numeric', 'alpha', 'dashes'));

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

	public static function validation_for_regist_google(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();
		$v = $validation->add('user_name', 'お名前');
		$v->add_rule('required');
		$v->add_rule('max_length', 30);

		$v = $validation->add('email', 'メールアドレス');
		$v->add_rule('valid_email');

		$v = $validation->add('oauth_type', 'oauth_type');
		$v->add_rule('required');
		$v->add_rule('match_pattern', '/(google)/');

		$v = $validation->add('oauth_id', 'oauth_id');
		$v->add_rule('required');
		$v->add_rule('max_length', 200);
		$v->add_rule('valid_string', array('numeric', 'alpha', 'dashes'));

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

	public static function validation_for_regist_twitter(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();
		$v = $validation->add('user_name', 'お名前');
		$v->add_rule('required');
		$v->add_rule('max_length', 30);

		$v = $validation->add('email', 'メールアドレス');
		$v->add_rule('valid_email');

		$v = $validation->add('oauth_type', 'oauth_type');
		$v->add_rule('required');
		$v->add_rule('match_pattern', '/(twitter)/');

		$v = $validation->add('oauth_id', 'oauth_id');
		$v->add_rule('required');
		$v->add_rule('max_length', 200);
		$v->add_rule('valid_string', array('numeric', 'alpha', 'dashes'));

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

	public static function validation_for_regist_yahoo(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();
		$v = $validation->add('user_name', 'お名前');
		$v->add_rule('required');
		$v->add_rule('max_length', 30);

		$v = $validation->add('email', 'メールアドレス');
		$v->add_rule('valid_email');

		$v = $validation->add('oauth_type', 'oauth_type');
		$v->add_rule('required');
		$v->add_rule('match_pattern', '/(yahoo)/');

		$v = $validation->add('oauth_id', 'oauth_id');
		$v->add_rule('required');
		$v->add_rule('max_length', 200);
		$v->add_rule('valid_string', array('numeric', 'alpha', 'dashes'));

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

		$v = $validation->add('user_name', 'お名前');
		$v->add_rule('max_length', 30);

		$v = $validation->add('email', 'メールアドレス');
		$v->add_rule('valid_email');

		$v = $validation->add('password', 'パスワード');
		$v->add_rule('valid_string', array('numeric', 'alpha'));
		$v->add_rule('min_length', 4);
		$v->add_rule('max_length', 16);

		if ($arr_params['oauth_type'] != static::$oauth_type)
		{
			\Log::error('不正なoauth_typeデータが検出されました。');
			throw new \Exception('不正なデータが検出されました');
		}
		if (empty($arr_params['password']))
		{
			if (empty($arr_params['user_name']) or trim($arr_params['user_name']) == static::$user_name)
			{
				if (empty($arr_params['email']) or trim($arr_params['email']) == static::$email)
				{
					static::$arr_validation_error['all'] = '変更された項目がありません。もう一度ご確認お願いいたします。';
				}
			}
		}

		$arr_params['user_id']    = static::$user_id;
		$arr_params['login_hash'] = static::$login_hash;

		if ( ! $validation->run($arr_params))
		{
			foreach ($validation->error() as $i => $error)
			{
				static::$arr_validation_error[$i] = $error->get_message();
			}
			return false;
		}
		if ( ! empty(static::$arr_validation_error))
		{
			return false;
		}
		return true;
	}

	public static function validation_for_passwordreissuerequest(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();
		$v = $validation->add('email', 'メールアドレス');
		$v->add_rule('valid_email');
		$v->add_rule('required');

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

	public static function validation_for_passwordreissuerequestlogined(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();
		$v = $validation->add('email', 'メールアドレス');
		$v->add_rule('valid_email');
		$v->add_rule('required');

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

	public static function validation_for_passwordreissuedone(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		$v = $validation->add('password', 'パスワード');
		$v->add_rule('valid_string', array('numeric', 'alpha'));
		$v->add_rule('min_length', 4);
		$v->add_rule('max_length', 16);
		$v->add_rule('required');

		$v = $validation->add('passwordchk', 'パスワード(確認)');
		$v->add_rule('valid_string', array('numeric', 'alpha'));
		$v->add_rule('min_length', 4);
		$v->add_rule('max_length', 16);
		$v->add_rule('required');

		# メールアドレス
		$v = $validation->add('reissue_hash', 'ハッシュ値');
		$v->add_rule('required');
		$v->add_rule('exact_length', 32);
		$v->add_rule('valid_string', array('numeric', 'alpha'));

		# oauth_type
		$v = $validation->add('oauth_type', 'oauth_type');
		$v->add_rule('required');
		$v->add_rule('match_pattern', '/(email)|(line)|(facebook)|(google)|(twitter)|(yahoo)/');

		$arr_params = array_merge((array)UserService::get_login_user_from_property(), $arr_params);
		if ( ! $validation->run($arr_params))
		{
			foreach ($validation->error() as $i => $error)
			{
				static::$arr_validation_error[$i] = $error->get_message();
			}
			\Log::error(static::$arr_validation_error);
			return false;
		}
		if ($arr_params['password'] != $arr_params['passwordchk'])
		{
			$error_message = 'パスワード確認用との違いがあります';
			static::$arr_validation_error['password'] = $error_message;
			\Log::error($error_message);
			return false;
		}

		return true;
	}

	public static function validation_for_login(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();
		# メールアドレス
		$validation->add('email', 'メールアドレス')
			->add_rule('required')
			->add_rule('valid_email');
		# 入力パスワード
		$validation->add('password', 'パスワード')
			->add_rule('required')
			->add_rule('min_length', 4)
			->add_rule('max_length', 16)
			->add_rule('valid_string', array('alpha', 'numeric'));
		# 認証タイプ
		$validation->add('oauth_type', 'oauth_type')
			->add_rule('match_pattern', '/(email)|(line)|(facebook)|(google)|(twitter)|(yahoo)/');
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

	public static function validation_for_logout()
	{
		\Log::debug('[start]'. __METHOD__);

		// ログインしていること
		$arr_user_info = SessionService::get('user_info');
		if (empty($arr_user_info['user_id']) or empty($arr_user_info['login_hash']))
		{
			\Log::error('validation error: ログインしていません');
			\Log::error($arr_user_info);
			throw new \Exception('validation error: ログインしていません'. print_r($arr_user_info));
		}
		return true;
	}

	public static function validation_for_login_check(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();

		# メールアドレス
		$validation->add('email', 'メールアドレス')
			->add_rule('required')
			->add_rule('valid_email');
		# 入力パスワード
		$validation->add('password', 'パスワード')
			->add_rule('required')
			->add_rule('min_length', 4)
			->add_rule('max_length', 16)
			->add_rule('valid_string', array('alpha', 'numeric'));
		# リダイレクト
		$validation->add('redirect', 'redirect')
			->add_rule('match_pattern', '/(user\/edit)/');
		# ログイン情報も追加
		$arr_params['user_id']    = static::$user_id;
		$arr_params['login_hash'] = static::$login_hash;
		$arr_params['oauth_type'] = static::$oauth_type;

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

	public static function validation_for_regist_decide(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();
		# 仮ログインハッシュ値
		$validation->add('decide_hash', 'ハッシュ値')
			->add_rule('required')
			->add_rule('exact_length', 32)
			->add_rule('valid_string', array('numeric', 'alpha'));
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

	public static function validation_for_edit_decide(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();
		# メールアドレス
		$validation->add('decide_hash', 'ハッシュ値')
			->add_rule('required')
			->add_rule('exact_length', 32)
			->add_rule('valid_string', array('numeric', 'alpha'));
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




	public static function regist_check_api()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! empty(static::$arr_validation_error))
			{
				return false;
			}

			$arr_params = array();
			$arr_params['email' ]     = static::$email;
			$arr_params['password']   = static::$password;
			$arr_params['oauth_type'] = static::$oauth_type;
			$arr_params['oauth_id']   = static::$oauth_id;

			# CURLにてAPI送信
			$url = \Config::get('journal.api_host'). '/user/regist/check.json';
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
				\Log::warning($obj_response->response);
				throw new \Exception('response error');
			}
			if (empty($obj_response->result))
			{
				\Log::warning($obj_response->response);
				static::$arr_validation_error['email'] = "認証に失敗しました。メールアドレス、パスワードをもう一度ご確認ください。";
				return false;
			}
			$result = current($obj_response->result);
			static::$oauth_type = $result->oauth_type;
			static::$oauth_id   = $result->oauth_id;
			static::$user_id    = $result->user_id;
			return $obj_response->result;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}

	public static function send_api_for_login()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! empty(static::$arr_validation_error))
			{
				return false;
			}

			$arr_params = array();
			$arr_params['email' ]     = static::$email;
			$arr_params['password']   = static::$password;
			$arr_params['oauth_type'] = static::$oauth_type;
			$arr_params['oauth_id']   = static::$oauth_id;

			# CURLにてAPI送信
			$url = \Config::get('journal.api_host'). '/user/login/index.json';
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
				\Log::warning($obj_response->response);
				throw new \Exception('ログイン認証に失敗しました。');
			}
			static::$user_id         = $obj_response->result->user_id;
			static::$user_name       = $obj_response->result->user_name;
			static::$oauth_type      = $obj_response->result->oauth_type;
			static::$oauth_id        = $obj_response->result->oauth_id;
			static::$email           = $obj_response->result->email;
			static::$password_digits = $obj_response->result->password_digits;
			static::$login_hash      = $obj_response->result->login_hash;
			static::$member_type     = $obj_response->result->member_type;
			static::$last_login      = $obj_response->result->last_login;

			return true;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}

	public static function send_api_for_regist()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! empty(static::$arr_validation_error))
			{
				\Log::error(static::$arr_validation_error);
				return false;
			}

			$url = \Config::get('journal.api_host'). '/user/regist/index.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_name'  => static::$user_name,
					'email'      => static::$email,
					'oauth_type' => static::$oauth_type,
					'oauth_id'   => static::$oauth_id,
					'password'   => static::$password,
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
			# CURL送信レスポンス
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				if ($obj_response->code == '7014')
				{
					$message = '現在登録申請を受け付け済みです。メールが受け取れていない場合は恐れ入りますが、再度メールアドレスをご確認していただき'. \Config::get('journal.decide_time_min'). '分後にもう一度ユーザ登録の実施をよろしくお願いいたします。';
					throw new \Exception($message, $obj_response->code);
				}
				else if ($obj_response->code == '7015')
				{
					$message = 'すでにユーザ登録済みです。ログインしてご利用ください。';
					throw new \Exception($message, $obj_response->code);
				}
				else
				{
					\Log::error($obj_response->response);
					throw new \Exception($obj_response->response);
				}
			}
			# メンバ変数にユーザID, login_hashを格納
			static::$user_id    = $obj_response->result->user_id;
			static::$login_hash = $obj_response->result->login_hash;
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

			if ( ! empty(static::$arr_validation_error))
			{
				return false;
			}

			$url = \Config::get('journal.api_host'). '/user/edit/index.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'    => static::$user_id,
					'user_name'  => static::$user_name,
					'login_hash' => static::$login_hash,
					'email'      => static::$email,
					'oauth_type' => static::$oauth_type,
					'oauth_id'   => static::$oauth_id, // イルカ？
					'password'   => static::$password,
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
			# CURL送信レスポンス
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response, $obj_response->code);
			}
			# メンバ変数にユーザID, login_hashを格納
			static::$user_id            = $obj_response->result->user_id;
			static::$user_name          = $obj_response->result->user_name;
			static::$email              = $obj_response->result->email;
			static::$password_digits    = $obj_response->result->password_digits;
			static::$login_hash         = $obj_response->result->login_hash;
			static::$is_email_change    = $obj_response->result->is_email_change;
			static::$is_password_change = $obj_response->result->is_password_change;
			return true;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}

	public static function send_api_for_logincheck(array $arr_params)
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! empty(static::$arr_validation_error))
			{
				return false;
			}

			$url = \Config::get('journal.api_host'). '/user/login/check.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'user_id'    => static::$user_id,
					'email'      => $arr_params['email'],
					'password'   => $arr_params['password'],
					'login_hash' => static::$login_hash,
					'oauth_type' => static::$oauth_type,
					'oauth_id'   => static::$oauth_id,
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
			# CURL送信レスポンス
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				\Log::error($obj_response->response);
				throw new \Exception($obj_response->response);
			}
			if ($obj_response->result == false)
			{
				static::$arr_validation_error['password'] = 'パスワードをご確認ください。';
			}
			return true;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}

	public static function send_api_for_regist_decide(array $arr_params)
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! empty(static::$arr_validation_error))
			{
				return false;
			}

			$url = \Config::get('journal.api_host'). '/user/regist/decide.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'decide_hash' => static::$decide_hash,
					'oauth_type'  => static::$oauth_type,
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
			# CURL送信レスポンス
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				$message = 'ユーザ登録に失敗しました。'. $obj_response->response. $obj_response->code;
				throw new \Exception($message, $obj_response->code);
			}
			# メンバ変数にユーザID, login_hashを格納
			static::$user_id     = $obj_response->result->user_id;
			static::$user_name   = $obj_response->result->user_name;
			static::$login_hash  = $obj_response->result->login_hash;
			static::$oauth_type  = $obj_response->result->oauth_type;
			static::$oauth_id    = $obj_response->result->oauth_id;
			static::$email       = $obj_response->result->email;
			static::$password_digits = $obj_response->result->password_digits;

			return true;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}

	public static function send_api_for_edit_decide(array $arr_params)
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! empty(static::$arr_validation_error))
			{
				return false;
			}

			$url = \Config::get('journal.api_host'). '/user/edit/editdecide.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'decide_hash' => static::$decide_hash,
					'oauth_type'  => $arr_params['oauth_type'],
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
			# CURL送信レスポンス
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				static::$arr_validation_error['email'] = 'ユーザ更新に失敗しました。';
				return false;
			}
			# メンバ変数にユーザID, login_hashを格納
			static::$user_id     = $obj_response->result->user_id;
			static::$user_name   = $obj_response->result->user_name;
			static::$login_hash  = $obj_response->result->login_hash;
			static::$oauth_type  = $obj_response->result->oauth_type;
			static::$email       = $obj_response->result->email;

			return true;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e);
		}
	}

	public static function send_api_for_passwordreissuerequest()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if (static::$arr_validation_error)
			{
				return false;
			}

			$url = \Config::get('journal.api_host'). '/user/password/reissuerequest.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'email'      => static::$email,
					'oauth_type' => static::$oauth_type,
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
			# CURL送信レスポンス
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				if ($obj_response->code == '7006')
				{
					$error_message = 'このメールアドレスは有効ではありません。';
					\Log::error($error_message);
					static::$arr_validation_error['email'] = $error_message;
					return false;
				}
				if ($obj_response->code == '7021')
				{
					$error_message = 'すでにメール送信済みです。ご確認ください。';
					\Log::error($error_message);
					static::$arr_validation_error['email'] = $error_message;
					return false;
				}
				throw new \Exception($obj_response->response, $obj_response->code);
			}
			return true;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			throw new \Exception($e);
		}
	}

	public static function send_api_for_passwordreissuedone()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if (static::$arr_validation_error)
			{
				return false;
			}

			$url = \Config::get('journal.api_host'). '/user/password/reissuedone.json';
			$curl = \Request::forge($url, 'curl');
			$curl->http_login(\Config::get('journal.api_authorized_username'), \Config::get('journal.api_authorized_password'), \Config::get('journal.api_authorized_type'));
			$curl->set_method('post');
			$curl->set_header('Content-type', 'application/json; charset=UTF-8');
			$curl->set_params(json_encode(array(
					'password'     => static::$password,
					'reissue_hash' => static::$reissue_hash,
					'oauth_type'   => static::$oauth_type,
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
			# CURL送信レスポンス
			$obj_response = json_decode($json_response);
			if (isset($obj_response->success) and $obj_response->success == false)
			{
				if ($obj_response->code == '7022')
				{
					$error_message = "[有効時間が過ぎております。恐れ入りますが再度パスワード変更手続きを行ってください。]";
					\Log::error($error_message);
					throw new \Exception($error_message, '7022');
				}
				if ($obj_response->code == '7021')
				{
					$error_message = "パスワード再発行に失敗しました。すでに一度変更済みではありませんか？もしその場合は恐れ入りますが再度パスワード変更続きを行ってください。";
					\Log::error($error_message);
					throw new \Exception($error_message, '7021');
				}
				throw new \Exception($obj_response->response, $obj_response->code);
			}
			static::$user_id         = $obj_response->result->user_id;
			static::$user_name       = $obj_response->result->user_name;
			static::$email           = $obj_response->result->email;
			static::$login_hash      = $obj_response->result->login_hash;
			static::$oauth_type      = $obj_response->result->oauth_type;
			static::$oauth_id        = $obj_response->result->oauth_id;
			static::$password_digits = $obj_response->result->password_digits;
			return true;
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			throw new \Exception($e);
		}
	}


	public static function logout()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# セッションからユーザ情報を取得
			$arr_user_info = SessionService::get('user_info');

			# APIにログアウト情報を送信します
			$arr_params = array(
				'user_id'    => $arr_user_info['user_id'],
				'login_hash' => $arr_user_info['login_hash'],
				'oauth_type' => $arr_user_info['oauth_type'],
			);

			$url = \Config::get('journal.api_host'). '/user/logout/index.json';

			# CURLにてAPI送信
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
			if (isset($obj_response->success) and $obj_response->success == true)
			{
				return true;
			}
			if (isset($obj_response))
			{
				\Log::error('APIログアウト処理が失敗しました。'. $obj_response->response);
				\Log::error(__FILE__. '['. __LINE__. ']');
				throw new \Exception($obj_response->response);
			}
		}
		catch (\RequestStatusException $e)
		{
			\Log::error($e);
			throw new \Exception($e->getMessage(), $e->getCode());
		}
	}

	/**
	 * 現在ログイン状態であるかチェック
	 * @return boolean
	 */
	public static function is_login()
	{
		\Log::debug('[start]'. __METHOD__);

		$arr_user_info = SessionService::get('user_info');
		if (empty($arr_user_info))
		{
			return false;
		}
		$arr_check_keys = array(
				'user_id', 'login_hash', 'oauth_type'
		);
		foreach ($arr_user_info as $i => $val)
		{
			if (isset($arr_check_keys[$i]))
			{
				if (empty($val))
				{
					\Log::error($i. '=>'. $val);
					return false;
				}
			}
		}

		return true;
	}

	public static function is_email_change()
	{
		\Log::debug('[start]'. __METHOD__);

		return static::$is_email_change;
	}

	public static function is_password_change()
	{
		\Log::debug('[start]'. __METHOD__);

		return static::$is_password_change;
	}

	/**
	 * ログインチェック済みの場合はtrue, 再度ログインチェックが必要な場合はfalse
	 * @return boolean
	 */
	public static function is_valid_check_login()
	{
		\Log::debug('[start]'. __METHOD__);

		$session_login_check = \Session::get('login_check');
		if (empty($session_login_check))
		{
			return false;
		}
		if ($session_login_check['expired'] < \Date::forge()->get_timestamp())
		{
			return false;
		}
		return true;
	}

}