<?php
namespace service;

class OauthLoginFacebookConcreteStrategy implements OauthLoginStrategy
{
	private $app_id = "";
	private $secret = "";
	private $redirect_url = "";
	private $access_token = null;

	public function __construct()
	{
		$this->app_id       = \Config::get('oauth.facebook.app_id');
		$this->secret       = \Config::get('oauth.facebook.secret_id');
		$this->redirect_url = \Config::get('oauth.facebook.redirect_url');
	}

	public function get_login_url()
	{
		\Log::debug('[start]'. __METHOD__);

		$login_url = "https://www.facebook.com/v2.8/dialog/oauth?client_id={$this->app_id}&redirect_uri={$this->redirect_url}";

		return $login_url;
	}

	public function get_user_info()
	{
		\Log::debug('[start]'. __METHOD__);

		$code = \Input::param('code');
		if (empty($code))
		{
			throw new \Exception('google response code is empty');
		}

		$this->access_token = $this->get_access_token($code);
		if ($this->access_token)
		{
			$arr_oauth_info = (array)$this->get_profile_from_api();
			$arr_user_info  = array();
			$arr_user_info['oauth_type'] = 'facebook';
			if (isset($arr_oauth_info['id']))
			{
				$arr_user_info['oauth_id'] = $arr_oauth_info['id'];
				$arr_user_info['picture_url'] = 'https://graph.facebook.com/'.$arr_oauth_info['id']. '/picture';
			}
			if (isset($arr_oauth_info['name']))
			{
				$arr_user_info['user_name'] = $arr_oauth_info['name'];
			}
			if (isset($arr_oauth_info['email']))
			{
				$arr_user_info['email'] = $arr_oauth_info['email'];
			}
			if (isset($arr_oauth_info['gender']))
			{
				$arr_user_info['gender'] = $arr_oauth_info['gender'];
			}
			if (isset($arr_oauth_info['link']))
			{
				$arr_user_info['link'] = $arr_oauth_info['link'];
			}
			if (isset($arr_oauth_info['localte']))
			{
				$arr_user_info['locale'] = $arr_oauth_info['locale'];
			}

			UserService::set_login_user($arr_user_info);

			return true;
		}
		else
		{
			\Log::debug('アクセストークン：'. $this->access_token);
			exit;
			return false;
		}
	}

	public function logout()
	{
		\Log::debug('[start]'. __CLASS__. '::'. __FUNCTION__);

		$this->facebook->destroy_session();
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

		$url = "https://graph.facebook.com/v2.8/oauth/access_token";
		$curl = \Request::forge($url, 'curl');
		$curl->set_method('get');
		$curl->set_params(array(
				'client_id'     => $this->app_id,
				'client_secret' => $this->secret,
				'code'          => $code,
				'redirect_uri'  => $this->redirect_url
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
			throw new \Exception('facebook oauth access_token get error');
		}
		return  $obj_response->access_token;
	}

	private function get_profile_from_api()
	{
		$url  = "https://graph.facebook.com/v2.8/me?fields=id,name,email";
		$curl = \Request::forge($url, 'curl');
		$curl->set_method('get');
		$curl->set_params(array(
				'access_token' => $this->access_token,
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
			throw new \Exception('facebook oauth get profile error');
		}
		return  $obj_response;
	}
}