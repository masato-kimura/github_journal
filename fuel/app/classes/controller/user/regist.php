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
class Controller_User_Regist extends Controller_TemplateJournalMaterialize
{
	/**
	 * only email regist
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック（してたらログアウト画面にリダイレクト）
			if (UserService::is_login())
			{
				UserService::session_destroy();
			}
			# プレゼンタ生成
			$presenter = Presenter::forge('user/regist/index');
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/regist/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
	}

	/**
	 * only email regist
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_email()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック（してたらログアウト画面にリダイレクト）
			if (UserService::is_login())
			{
				UserService::session_destroy();
			}
			# プレゼンタ生成
			$presenter = Presenter::forge('user/regist/email');
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/regist/email');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
	}


	/**
	 * only facebook regist
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_facebook()
	{
		$oauth = "facebook";

		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック（してたらログアウト画面にリダイレクト）
			if (UserService::is_login())
			{
				UserService::session_destroy();
			}

			$arr_user_info = (array)\Session::get_flash('oauth_user_info', array());
			# バリデーション
			if ( ! UserService::validation_for_regist_facebook($arr_user_info))
			{
				throw new \Exception('不正なリクエストを検出');
			}

			# プレゼンタ生成
			$presenter = Presenter::forge('user/regist/'. $oauth);
			$presenter->set('arr_params', $arr_user_info);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/regist/'. $oauth);
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
	}

	/**
	 * only line regist
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_line()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$oauth = "line";

			# ログインチェック（してたらログアウト画面にリダイレクト）
			if (UserService::is_login())
			{
				UserService::session_destroy();
			}

			$arr_user_info = (array)\Session::get_flash('oauth_user_info', array());
			# バリデーション
			if ( ! UserService::validation_for_regist_line($arr_user_info))
			{
				throw new \Exception('不正なリクエストを検出');
			}

			# プレゼンタ生成
			$presenter = Presenter::forge('user/regist/'. $oauth);
			$presenter->set('arr_params', $arr_user_info);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/regist/'. $oauth);
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
	}

	/**
	 * only google regist
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_google()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$oauth = "google";

			# ログインチェック（してたらログアウト画面にリダイレクト）
			if (UserService::is_login())
			{
				UserService::session_destroy();
			}

			$arr_user_info = (array)\Session::get_flash('oauth_user_info', array());

			# バリデーション
			if ( ! UserService::validation_for_regist_google($arr_user_info))
			{
				throw new \Exception('不正なリクエストを検出');
			}

			# プレゼンタ生成
			$presenter = Presenter::forge('user/regist/'. $oauth);
			$presenter->set('arr_params', $arr_user_info);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/regist/'. $oauth);
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
	}

	/**
	 * only twitter regist
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_twitter()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$oauth = "twitter";

			# ログインチェック（してたらログアウト画面にリダイレクト）
			if (UserService::is_login())
			{
				UserService::session_destroy();
			}

			$arr_user_info = (array)\Session::get_flash('oauth_user_info', array());
			# バリデーション
			if ( ! UserService::validation_for_regist_twitter($arr_user_info))
			{
				throw new \Exception('不正なリクエストを検出');
			}

			# プレゼンタ生成
			$presenter = Presenter::forge('user/regist/'. $oauth);
			$presenter->set('arr_params', $arr_user_info);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/regist/'. $oauth);
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
	}

	/**
	 * only yahoo regist
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_yahoo()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$oauth = "yahoo";

			# ログインチェック（してたらログアウト画面にリダイレクト）
			if (UserService::is_login())
			{
				UserService::session_destroy();
			}

			$arr_user_info = (array)\Session::get_flash('oauth_user_info', array());
			# バリデーション
			if ( ! UserService::validation_for_regist_yahoo($arr_user_info))
			{
				throw new \Exception('不正なリクエストを検出');
			}

			# プレゼンタ生成
			$presenter = Presenter::forge('user/regist/'. $oauth);
			$presenter->set('arr_params', $arr_user_info);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/regist/'. $oauth);
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
	}

	/**
	 * only email regist
	 * @return boolean
	 */
	public function action_execute()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック（してたらログアウト画面にリダイレクト）
			if (UserService::is_login())
			{
				\Response::redirect('/user/login/');
			}

			# リクエストを配列に格納
			$arr_params = array(
					'user_name'     => \Input::param('user_name'),
					'email'         => \Input::param('email'),
					'password'      => \Input::param('password'),
					'oauth_type'    => \Input::param('oauth_type', 'email'),
					'oauth_id'      => \Input::param('oauth_id'),
					'is_auto_login' => \Input::param('is_auto_login'),
			);

			# バリデーション
			UserService::validation_for_regist($arr_params);

			# リクエストをサービスメンバ変数にセット
			UserService::set_request($arr_params);

			# API送信
			UserService::send_api_for_regist();

			# エラー存在時
			if ( ! UserService::is_validation_error())
			{
				# プレゼンタ生成
				$seg = $arr_params['oauth_type'];
				$presenter = Presenter::forge('user/regist/'. $seg);
				$presenter->set('arr_validation_error', UserService::get_validation_error());
				$presenter->set('arr_params', $arr_params);
				$this->template->set_safe('contents', $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
				return true;
			}

			# プレゼンタ生成
			$presenter = Presenter::forge('user/regist/execute');
			$presenter->set('arr_validation_error', array());
			$presenter->set('arr_params', array());
			$this->template->set_safe('contents', $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録確認");
			return true;
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getCode());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			# プレゼンタ生成
			$seg = $arr_params['oauth_type'];
			$presenter = Presenter::forge('user/regist/'. $seg);
			if ($e->getMessage() === 'email unique error')
			{
				$presenter->set('arr_validation_error', array('email' => 'このメールアドレスは登録済みです'));
				$presenter->set('arr_params', $arr_params);
			}
			else if ($e->getCode() == '7014')
			{
				$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
				$presenter->set('error_message', $exception_error_message);
			}
			else
			{
				$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
				$presenter->set('error_message', \Config::get('journal.system_error_message'). '[ '. $exception_error_message. ' ]');
			}
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
	}

	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_decide($oauth_type='email', $decide_hash)
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			$arr_params = array(
				'decide_hash' => $decide_hash,
				'oauth_type'  => $oauth_type,
			);
			UserService::validation_for_regist_decide($arr_params);
			UserService::set_request($arr_params);
			UserService::send_api_for_regist_decide($arr_params);
			if (UserService::get_validation_error())
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('user/regist/decideerror');
				$this->template->set_safe("contents", $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
				return true;
			}
			UserService::set_user_info_to_session();
			\Session::set_flash('message', 'ペイジャーナルへご登録いただきありがとうございます。');
			\Response::redirect('/payment/list');
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			\Log::error($e->getCode());
			$presenter = Presenter::forge('user/regist/decideerror');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			if ($e->getCode() == '7012')
			{
				$presenter->set('error_message', '有効時間が過ぎております。お手数おかけいたしますが、再度ユーザ登録の実施をよろしくお願いいたします。');
			}
			else if ($e->getCode() == '7013')
			{
				$presenter->set('error_message', \Config::get('journal.system_error_message'). 'すでに登録済みか退会済みの可能性があります。登録済みの場合はログインしてご利用ください。');
			}
			else
			{
				$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			}
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
	}

}
