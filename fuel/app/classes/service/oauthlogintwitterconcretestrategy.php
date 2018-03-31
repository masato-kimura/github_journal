<?php
namespace service;
require_once APPPATH. 'classes/service/sdk/twitteroauth-0.7.1/autoload.php';

use Abraham\TwitterOAuth\TwitterOAuth;

class OauthLoginTwitterConcreteStrategy implements OauthLoginStrategy
{
	private $app_id = "";
	private $secret = "";
	private $redirect_url = "";
	private $access_token = null;

	private $login_id = null;
	private $line;

	public function __construct()
	{
		$this->app_id = \Config::get('oauth.twitter.consumer_key');
		$this->secret = \Config::get('oauth.twitter.consumer_secret');
		$this->redirect_url = \Config::get('oauth.twitter.calback_url');
	}

	public function get_login_url()
	{
		\Log::debug('[start]'. __METHOD__);

		$connection = new TwitterOAuth($this->app_id, $this->secret);
		$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => $this->redirect_url));
		\Session::set('twitter_oauth_token', $request_token['oauth_token']);
		\Session::set('twitter_oauth_token_secret', $request_token['oauth_token_secret']);
		$url = $connection->url('oauth/authenticate', array('oauth_token' => $request_token['oauth_token']));

		return $url;
	}

	public function get_user_info()
	{
		\Log::debug('[start]'. __METHOD__);

		$oauth_token = \Input::param('oauth_token');
		if (empty($oauth_token))
		{
			throw new \Exception('twitter response code is empty');
		}

		if ($oauth_token != \Session::get('twitter_oauth_token'))
		{
			throw new \Exception('twitter_oauth_token_error');
		}
		$this->access_token = $this->get_access_token();
		session_regenerate_id();

		if ( ! empty($this->access_token))
		{
			$arr_oauth_info = (array)$this->get_profile_from_api();
			$arr_user_info  = array();
			$arr_user_info['oauth_type'] = 'twitter';

			if (isset($arr_oauth_info['id']))
			{
				$arr_user_info['oauth_id'] = $arr_oauth_info['id'];
			}
			if (isset($arr_oauth_info['name']))
			{
				$arr_user_info['user_name'] = $arr_oauth_info['name'];
			}
			if (isset($arr_oauth_info['profile_image_url_https']))
			{
				$arr_user_info['picture_url'] = $arr_oauth_info['profile_image_url_https'];
			}
			if (isset($arr_oauth_info['email']))
			{
				$arr_user_info['email'] = $arr_oauth_info['email'];
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

	private function get_access_token()
	{
		\Log::debug('[start]'. __METHOD__);

		$request_token = array();
		$request_token['oauth_token'] = \Session::get('twitter_oauth_token');
		$request_token['oauth_token_secret'] = \Session::get('twitter_oauth_token_secret');
		$connection = new TwitterOAuth($this->app_id, $this->secret, $request_token['oauth_token'], $request_token['oauth_token_secret']);
		return $connection->oauth('oauth/access_token', array('oauth_verifier' => \Input::param('oauth_verifier')));
	}

	private function get_profile_from_api()
	{
		\Log::debug('[start]'. __METHOD__);

		$connection = new TwitterOAuth($this->app_id, $this->secret, $this->access_token['oauth_token'], $this->access_token['oauth_token_secret']);
		return $connection->get("account/verify_credentials", ['include_entities'=> 'true', 'include_email'=> 'true']);
	}
}