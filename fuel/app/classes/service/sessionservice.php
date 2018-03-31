<?php
namespace service;

class SessionService
{
	public static function set($name, $value)
	{
		\Log::debug('[start]'. __METHOD__);

		return \Session::set($name, $value);
	}

	public static function get($name)
	{
		\Log::debug('[start]'. __METHOD__);

		return \Session::get($name);
	}

	public static function delete($name, $use_flash_flag=false)
	{
		\Log::debug('[start]'. __METHOD__);

		return \Session::delete($name);
	}

	public static function destroy()
	{
		\Log::debug('[start]'. __METHOD__);

		return \Session::destroy();
	}
}