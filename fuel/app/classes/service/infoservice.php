<?php
namespace service;

use service\UserService;

class InfoService extends Service
{
	private static $user_name;
	private static $email;
	private static $contact;

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

	public static function validation_for_contact(array $arr_params)
	{
		\Log::debug('[start]'. __METHOD__);

		$validation = static::Validation();
		$validation->add('user_name_contact', 'お名前')
			->add_rule('required')
			->add_rule('max_length', 50);
		$validation->add('email_contact', 'メールアドレス')
			->add_rule('required')
			->add_rule('valid_email');
		$validation->add('contact_contact', '本文')
			->add_rule('required')
			->add_rule('max_length', 3000);

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

}
