<?php
use service\Oauthloginfactory;
use service\UserService;
use Fuel\Core\Presenter;
/**
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
class Controller_User_Login extends Controller_TemplateJournalMaterialize
{
	public function action_index()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if (UserService::is_login())
			{
				UserService::session_destroy();
			}
			$presenter = Presenter::forge('user/login/index');
			$presenter->set('is_auto_login', \Input::param('auto_login', false));
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -PayJournal- ログイン");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/login/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -PayJournal- ログイン");
		}
	}

	public function action_check()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! UserService::is_login())
			{
				\Response::redirect('/user/login');
			}
			$presenter = Presenter::forge('user/login/check');
			$presenter->set('arr_user_info', $this->arr_user_info);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -PayJournal- ログイン確認");
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			$presenter = Presenter::forge('user/login/check');
			$presenter->set('arr_user_info', $this->arr_user_info);
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -PayJournal- ログイン確認");
		}
	}

	public function action_checkexecute()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if ( ! UserService::is_login())
			{
				\Response::redirect('/user/login');
			}

			$arr_params = array(
				'email'      => \Input::post('email'),
				'password'   => \Input::post('password'),
				'oauth_type' => \Input::post('oauth_type'),
				'redirect'   => \Input::post('redirect', '/'),
			);
			# バリデーション
			UserService::validation_for_login_check($arr_params);
			# API送信
			UserService::send_api_for_logincheck($arr_params);
			# エラー存在確認
			$arr_validation_error = UserService::get_validation_error();
			if ( ! empty($arr_validation_error))
			{
				$presenter = Presenter::forge('user/login/check');
				$presenter->set('arr_user_info', $this->arr_user_info);
				$presenter->set('arr_validation_error', $arr_validation_error);
				$this->template->set_safe("contents", $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -PayJournal -ログイン確認");
				return true;
			}

			# セッションにセット
			$expired = \Date::forge()->get_timestamp() + 60 * 60 * 3; // 3時間
			\Session::set('login_check', array('result' => true, 'expired' => $expired));

			# リダイレクト
			\Response::redirect($arr_params['redirect']);
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			\Response::redirect('/user/login/check');
		}
	}

	public function action_email()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック（ログイン済みの場合はログアウト）
			if (UserService::is_login())
			{
				UserService::session_destroy();
			}
			# リクエスト格納配列
			$arr_params = array(
				'email'      => \Input::post('email'),
				'password'   => \Input::post('password'),
				'oauth_type' => \Input::post('oauth_type'),
			);
			# バリデーション
			UserService::validation_for_login($arr_params);
			# ログインしていないことが前提なのでユーザセッション情報廃棄
			UserService::session_destroy();
			# リクエストをサービスメンバ変数に格納
			UserService::set_request($arr_params);
			# ユーザ登録されているかチェック
			$arr_regist_user = (array)UserService::regist_check_api();

			if ( ! UserService::is_validation_error())
			{
				$presenter = Presenter::forge('user/login/index');
				$presenter->set('arr_validation_error', UserService::get_validation_error());
				$presenter->set('arr_params', $arr_params);
				$presenter->set('is_auto_login', \Input::post('is_auto_login', true));
				$this->template->set_safe("contents", $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -PayJournal -ログイン");
				\Log::debug('[end]'. PHP_EOL);
				return true;
			}

			if (count($arr_regist_user) > 1)
			{
				# oauth_typeを選択する画面に遷移
				$presenter = Presenter::forge('user/login/oauthbyemail');
				$presenter->set('arr_params', $arr_params);
				$presenter->set('arr_regist_user', $arr_regist_user);
				$presenter->set('is_auto_login', \Input::post('is_auto_login', true));
				$this->template->set_safe("contents", $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -PayJournal -ログイン");
				\Log::debug('[end]'. PHP_EOL);
				return true;
			}

			# API送信（レスポンス情報がプロパティへ格納される）
			UserService::send_api_for_login();

			# ログイン情報をセッションへの格納
			UserService::set_user_info_to_session();
			if (\Input::post('is_auto_login', true))
			{
				\Log::info('自動ログインフラグを受け取りました');
				UserService::set_auto_login_to_session();
			}
			$oauth_type_response = current($arr_regist_user)->oauth_type;
			switch ($oauth_type_response)
			{
				case 'email':
					\Session::set_flash('message', 'メールアドレスでログインしました。');
					break;
				case 'facebook':
					\Session::set_flash('message', 'Facebookでログインしました。');
					break;
				case 'line':
					\Session::set_flash('message', 'LINEでログインしました。');
					break;
				case 'google':
					\Session::set_flash('message', 'Googleでログインしました。');
					break;
				case 'twitter':
					\Session::set_flash('message', 'Twitterでログインしました。');
					break;
				case 'yahoo':
					\Session::set_flash('message', 'Yahoo!でログインしました。');
					break;
				default:
					\Session::set_flash('message', $oauth_type_response. 'でログインしました。');
			}
			\Log::debug('[end]'. PHP_EOL);
			\Response::redirect("/payment/list");
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			$presenter = Presenter::forge('user/login/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -PayJournal -ログイン");
			\Log::debug('[end]'. PHP_EOL);
		}
	}

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_facebook()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if (UserService::is_login())
			{
				UserService::session_destroy();
			}
			$obj_oauth_login = new Oauthloginfactory();
			$obj_oauth_context = $obj_oauth_login->set_oauth_type("facebook");
			// -----------------------------------------------
			// ログイン画面からの遷移
			// Oauth認証URLへのリダイレクト要求
			// -----------------------------------------------
			$code = \Input::param('code');
			if (empty($code)) // # oauth認証からのリダイレクトではない
			{
				# ログインしていないことが前提なので一旦セッション情報を削除
				UserService::session_destroy();
				# auto_loginチェックボックスの確認
				if (\Input::param('auto_login') == true)
				{
					# セッションへ自動ログインを設定
					UserService::set_auto_login_to_session();
				}
				\Response::redirect($obj_oauth_context->get_login_url());
			}
			// -----------------------------------------------
			// Oauth認証からのリターン遷移
			// グルーヴオンライン・ユーザ登録およびログイン処理
			//------------------------------------------------
			if ( ! $obj_oauth_context->get_user_info())
			{
				throw new \Exception('facebook_oauth_response error');
			}

			if (UserService::regist_check_api())
			{
				# ログイン処理
				UserService::send_api_for_login();
				# ログイン情報をセッションに格納
				UserService::set_user_info_to_session();
				\Session::set_flash('message', 'Facebookでログインしました。');
				\Response::redirect('/payment/list');
			}
			else
			{
				\Session::set_flash('oauth_user_info', UserService::get_user_info_from_property());
				\Response::redirect('/user/regist/facebook');
			}
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			$presenter = Presenter::forge('user/login/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -PayJournal -ログイン");
		}
	}


	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_line()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if (UserService::is_login())
			{
				UserService::session_destroy();
			}
			$obj_oauth_login = new Oauthloginfactory();
			$obj_oauth_context = $obj_oauth_login->set_oauth_type("line");
			// -----------------------------------------------
			// ログイン画面からの遷移
			// Oauth認証URLへのリダイレクト要求
			// -----------------------------------------------
			$code = \Input::param('code');
			if (empty($code)) // # oauth認証からのリダイレクトではない
			{
				# ログインしていないことが前提なので一旦セッション情報を削除
				UserService::session_destroy();
				# auto_loginチェックボックスの確認
				if (\Input::param('auto_login') == true)
				{
					# セッションへ自動ログインを設定
					UserService::set_auto_login_to_session();
				}
				\Response::redirect($obj_oauth_context->get_login_url());
			}

			// -----------------------------------------------
			// Oauth認証からのリターン遷移
			// グルーヴオンライン・ユーザ登録およびログイン処理
			//------------------------------------------------
			if ( ! $obj_oauth_context->get_user_info())
			{
				throw new \Exception('line_oauth_response error');
			}

			if (UserService::regist_check_api())
			{
				# ログイン処理
				UserService::send_api_for_login();
				# ログイン情報をセッションに格納
				UserService::set_user_info_to_session();
				\Session::set_flash('message', 'LINEでログインしました。');
				\Response::redirect('/payment/list');
			}
			else
			{
				\Session::set_flash('oauth_user_info', UserService::get_user_info_from_property());
				\Response::redirect('/user/regist/line');
			}
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			$presenter = Presenter::forge('user/login/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -PayJournal -ログイン");
		}
	}

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_google()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if (UserService::is_login())
			{
				UserService::session_destroy();
			}
			$obj_oauth_login = new Oauthloginfactory();
			$obj_oauth_context = $obj_oauth_login->set_oauth_type("google");
			// -----------------------------------------------
			// ログイン画面からの遷移
			// Oauth認証URLへのリダイレクト要求
			// -----------------------------------------------
			$code = \Input::param('code');
			if (empty($code)) // # oauth認証からのリダイレクトではない
			{
				# ログインしていないことが前提なので一旦セッション情報を削除
				UserService::session_destroy();
				# auto_loginチェックボックスの確認
				if (\Input::param('auto_login') == true)
				{
					# セッションへ自動ログインを設定
					UserService::set_auto_login_to_session();
				}
				\Response::redirect($obj_oauth_context->get_login_url());
			}

			// -----------------------------------------------
			// Oauth認証からのリターン遷移
			// グルーヴオンライン・ユーザ登録およびログイン処理
			//------------------------------------------------
			if ( ! $obj_oauth_context->get_user_info())
			{
				throw new \Exception('google_oauth_response error');
			}

			if (UserService::regist_check_api())
			{
				# ログイン処理
				UserService::send_api_for_login();
				# ログイン情報をセッションに格納
				UserService::set_user_info_to_session();
				\Session::set_flash('message', 'Googleでログインしました。');
				\Response::redirect('/payment/list');
			}
			else
			{
				\Session::set_flash('oauth_user_info', UserService::get_user_info_from_property());
				\Response::redirect('/user/regist/google');
			}
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			$presenter = Presenter::forge('user/login/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -PayJournal -ログイン");
		}
	}

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_twitter()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if (UserService::is_login())
			{
				UserService::session_destroy();
			}
			$obj_oauth_login = new Oauthloginfactory();
			$obj_oauth_context = $obj_oauth_login->set_oauth_type("twitter");
			// -----------------------------------------------
			// ログイン画面からの遷移
			// Oauth認証URLへのリダイレクト要求
			// -----------------------------------------------
			$oauth_token = \Input::param('oauth_token');
			if (empty($oauth_token)) // # oauth認証からのリダイレクトではない
			{
				# ログインしていないことが前提なので一旦セッション情報を削除
				UserService::session_destroy();
				# auto_loginチェックボックスの確認
				if (\Input::param('auto_login') == true)
				{
					# セッションへ自動ログインを設定
					UserService::set_auto_login_to_session();
				}
				\Response::redirect($obj_oauth_context->get_login_url());
			}

			// -----------------------------------------------
			// Oauth認証からのリターン遷移
			// グルーヴオンライン・ユーザ登録およびログイン処理
			//------------------------------------------------
			if ( ! $obj_oauth_context->get_user_info())
			{
				throw new \Exception('twitter_oauth_response error');
			}

			if (UserService::regist_check_api())
			{
				# ログイン処理
				UserService::send_api_for_login();
				# ログイン情報をセッションに格納
				UserService::set_user_info_to_session();
				\Session::set_flash('message', 'Twitterでログインしました。');
				\Response::redirect('/payment/list');
			}
			else
			{
				\Session::set_flash('oauth_user_info', UserService::get_user_info_from_property());
				\Response::redirect('/user/regist/twitter');
			}
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			$presenter = Presenter::forge('user/login/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -PayJournal -ログイン");
		}
	}

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_yahoo()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if (UserService::is_login())
			{
				UserService::session_destroy();
			}
			$obj_oauth_login = new Oauthloginfactory();
			$obj_oauth_context = $obj_oauth_login->set_oauth_type("yahoo");
			// -----------------------------------------------
			// ログイン画面からの遷移
			// Oauth認証URLへのリダイレクト要求
			// -----------------------------------------------
			$code = \Input::param('code');
			if (empty($code)) // # oauth認証からのリダイレクトではない
			{
				# ログインしていないことが前提なので一旦セッション情報を削除
				UserService::session_destroy();
				# auto_loginチェックボックスの確認
				if (\Input::param('auto_login') == true)
				{
					# セッションへ自動ログインを設定
					UserService::set_auto_login_to_session();
				}
				\Response::redirect($obj_oauth_context->get_login_url());
			}

			// -----------------------------------------------
			// Oauth認証からのリターン遷移
			// グルーヴオンライン・ユーザ登録およびログイン処理
			//------------------------------------------------
			if ( ! $obj_oauth_context->get_user_info())
			{
				throw new \Exception('yahoo_oauth_response error');
			}

			if (UserService::regist_check_api())
			{
				# ログイン処理
				UserService::send_api_for_login();
				# ログイン情報をセッションに格納
				UserService::set_user_info_to_session();
				\Session::set_flash('message', 'Yahoo!でログインしました。');
				\Response::redirect('/payment/list');
			}
			else
			{
				\Session::set_flash('oauth_user_info', UserService::get_user_info_from_property());
				\Response::redirect('/user/regist/yahoo');
			}
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			$presenter = Presenter::forge('user/login/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -PayJournal -ログイン");
		}
	}
}
