<?php
use Fuel\Core\Presenter;
use service\UserService;
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
class Controller_User_Password extends Controller_TemplateJournalMaterialize
{
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_reissuerequest()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック（していなかったらログイン画面にリダイレクト）
			if (UserService::is_login())
			{
				UserService::session_destroy();
			}
			# プレゼンタ生成
			$presenter = Presenter::forge('user/password/reissuerequest');
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/password/reissuerequest');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
		}
	}

	public function action_reissueexecute()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック（してたらログアウト画面にリダイレクト）
			if (UserService::is_login())
			{
				UserService::session_destroy();
			}
			# リクエストを配列に格納
			$arr_params = array(
					'email' => \Input::param('email'),
					'oauth_type' => \Input::param('oauth_type', 'email'),
			);
			# バリデーション
			UserService::validation_for_passwordreissuerequest($arr_params);

			# リクエストをサービスメンバ変数にセット
			UserService::set_request($arr_params);

			# API送信
			UserService::send_api_for_passwordreissuerequest();

			if ( ! UserService::is_validation_error())
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('user/password/reissuerequest');
				$presenter->set('arr_validation_error', UserService::get_validation_error());
				$presenter->set('arr_params', $arr_params);
				$this->template->set_safe('contents', $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
				return true;
			}
			# プレゼンタ生成
			$presenter = Presenter::forge('user/password/reissueexecute');
			$presenter->set('arr_validation_error', array());
			$presenter->set('arr_params', array());
			$this->template->set_safe('contents', $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
			return true;
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getCode());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			$presenter = Presenter::forge('user/password/reissuerequest');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
		}
	}

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_reissuerequestlogined()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック（してなかったらログイン画面にリダイレクト）
			if ( ! UserService::is_login())
			{
				\Response::redirect('/user/login');
			}
			# プレゼンタ生成
			$presenter = Presenter::forge('user/password/reissuerequestlogined');
			$presenter->set('arr_user_info', $this->arr_user_info);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/password/reissuerequestlogined');
			$presenter->set('arr_user_info', $this->arr_user_info);
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
		}
	}

	public function action_reissueexecutelogined()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック（してなかったらログイン画面にリダイレクト）
			if ( ! UserService::is_login())
			{
				\Response::redirect('/user/login');
			}
			# リクエストを配列に格納
			$arr_params = array(
				'email' => \Input::param('email'),
			);
			# バリデーション
			UserService::validation_for_passwordreissuerequestlogined($arr_params);

			# リクエストをサービスメンバ変数にセット
			UserService::set_request($arr_params);

			# API送信
			UserService::send_api_for_passwordreissuerequest();

			if ( ! UserService::is_validation_error())
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('user/password/reissuerequestlogined');
				$presenter->set('arr_user_info', $this->arr_user_info);
				$presenter->set('arr_validation_error', UserService::get_validation_error());
				$presenter->set('arr_params', $arr_params);
				$this->template->set_safe('contents', $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
				return true;
			}
			# プレゼンタ生成
			$presenter = Presenter::forge('user/password/reissueexecutelogined');
			$presenter->set('arr_user_info', $this->arr_user_info);
			$presenter->set('arr_validation_error', array());
			$presenter->set('arr_params', array());
			$this->template->set_safe('contents', $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
			return true;
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getCode());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			$presenter = Presenter::forge('user/password/reissuerequestlogined');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
		}
	}


	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_reissueform($oauth_type='email', $reissue_hash)
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック（していなかったらログイン画面にリダイレクト）
			if (UserService::is_login())
			{
				UserService::session_destroy();
			}
			# プレゼンタ生成
			$presenter = Presenter::forge('user/password/reissueform');
			$presenter->set('reissue_hash', $reissue_hash);
			$presenter->set('oauth_type', $oauth_type);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/password/reissueform');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
		}
	}

	public function action_reissuedone()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック（してたらログアウト画面にリダイレクト）
			if (UserService::is_login())
			{
				UserService::session_destroy();
			}
			# リクエストを配列に格納
			$arr_params = array(
				'password'      => \Input::param('password'),
				'passwordchk'   => \Input::param('passwordchk'),
				'reissue_hash'  => \Input::param('reissue_hash'),
				'oauth_type'    => \Input::param('oauth_type'),
			);
			# バリデーション
			UserService::validation_for_passwordreissuedone($arr_params);

			# リクエストをサービスメンバ変数にセット
			UserService::set_request($arr_params);

			# API送信
			UserService::send_api_for_passwordreissuedone();

			# セッションに格納
			UserService::set_user_info_to_session();

			if ( ! UserService::is_validation_error())
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('user/password/reissueform');
				$presenter->set('arr_validation_error', UserService::get_validation_error());
				$presenter->set('oauth_type', \Input::param('oauth_type'));
				$presenter->set('reissue_hash', \Input::param('reissue_hash'));
				$this->template->set_safe('contents', $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
				return true;
			}
			\Session::set_flash('message', 'パスワード再発行登録が完了しました。今後は新パスワードでログイン可能です。');
			\Response::redirect('/payment/list');
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getCode());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			$presenter = Presenter::forge('user/password/reissueform');
			$presenter->set('oauth_type', \Input::param('oauth_type'));
			$presenter->set('reissue_hash', \Input::param('reissue_hash'));
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			if ($e->getCode() == '7022' or $e->getCode() == '7021')
			{
				$presenter->set('error_message', $e->getMessage());
			}
			else
			{
				$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			}
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -パスワード再発行");
		}
	}
}
