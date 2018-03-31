<?php
namespace service;

use Fuel\Core\Validation;
class Service
{
	protected static $validation;
	protected static $arr_validation_error = array();

	public static function Validation()
	{
		\Log::debug('[start]'. __METHOD__);

		if ( ! isset(static::$validation))
		{
			static::$validation = Validation::forge();
		}
		return static::$validation;
	}

	public static function get_validation_error()
	{
		\Log::debug('[start]'. __METHOD__);

		return static::$arr_validation_error;
	}

	/**
	 * エラー存在時：false, 未存在: true
	 * @return boolean
	 */
	public static function is_validation_error()
	{
		\Log::debug('[start]'. __METHOD__);

		if (empty(static::$arr_validation_error))
		{
			return true;
		}
		return false;
	}

}