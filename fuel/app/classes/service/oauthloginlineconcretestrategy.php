<?php
namespace service;

class OauthLoginLineConcreteStrategy implements OauthLoginStrategy
{
	private $app_id = "";
	private $secret = "";
	private $redirect_url = "";
	private $access_token = null;


	private $login_id = null;
	private $line;

	public function __construct()
	{
		$this->app_id = \Config::get('oauth.line.app_id');
		$this->secret = \Config::get('oauth.line.secret_id');
		$this->redirect_url = \Config::get('oauth.line.redirect_url');
	}

	public function get_login_url()
	{
		\Log::debug('[start]'. __METHOD__);

		$login_url = "https://access.line.me/dialog/oauth/weblogin?response_type=code&client_id={$this->app_id}&redirect_uri={$this->redirect_url}&state=";

		return $login_url;
	}

	public function get_user_info()
	{
		\Log::debug('[start]'. __METHOD__);

		$code = \Input::param('code');
		if (empty($code))
		{
			throw new \Exception('line response code is empty');
		}

		$this->access_token = $this->get_access_token($code);

		if ( ! empty($this->access_token))
		{
			$arr_oauth_info = (array)$this->get_profile_from_api();
			$arr_user_info  = array();
			$arr_user_info['oauth_type'] = 'line';

			if (isset($arr_oauth_info['mid']))
			{
				$arr_user_info['oauth_id'] = $arr_oauth_info['mid'];
			}
			if (isset($arr_oauth_info['displayName']))
			{
				$arr_user_info['user_name'] = $arr_oauth_info['displayName'];
			}
			if (isset($arr_oauth_info['pictureUrl']))
			{
				$arr_user_info['picture_url'] = $arr_oauth_info['pictureUrl'];
			}

			UserService::set_login_user($arr_user_info);

			return true;
		}
		else
		{
			exit;
			return false;
		}
	}

	public function logout()
	{
		\Log::debug('[start]'. __CLASS__. '::'. __FUNCTION__);

		$this->line->destroy_session();
		session_destroy();
		return true;
	}

	public function get_request_token()
	{
		\Log::debug('[start]'. __CLASS__. '::'. __FUNCTION__);

		return false;
}

	private function get_access_token($code)
	{
		\Log::debug('[start]'. __METHOD__);

		$url = "https://api.line.me/v1/oauth/accessToken";
		$curl = \Request::forge($url, 'curl');
		$curl->set_method('post');
		$curl->set_header('Content-type', 'application/x-www-form-urlencoded');
		$curl->set_params(array(
				'grant_type'    => 'authorization_code',
				'client_id'     => $this->app_id,
				'client_secret' => $this->secret,
				'code'          => $code,
				'redirect_url'  => $this->redirect_url
		));
		$curl->set_options(array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_TIMEOUT => 60,
				CURLOPT_CONNECTTIMEOUT => 60,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		));
		$curl->execute();
		$obj_response = json_decode($curl->response());
		if (empty($obj_response))
		{
			throw new \Exception('line oauth access_token get error');
		}
		return  $obj_response->access_token;
	}

	private function get_profile_from_api()
	{
		$url = "https://api.line.me/v1/profile";
		$curl = \Request::forge($url, 'curl');
		$curl->set_method('get');
		$curl->set_header('Authorization', "Bearer {$this->access_token}");
		$curl->set_params(array());
		$curl->set_options(array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_TIMEOUT => 60,
				CURLOPT_CONNECTTIMEOUT => 60,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		));
		$curl->execute();
		$obj_response = json_decode($curl->response());
		if (empty($obj_response))
		{
			throw new \Exception('line oauth get profile error');
		}
		return  $obj_response;

	}
}