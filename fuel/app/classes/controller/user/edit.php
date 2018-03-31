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
class Controller_User_Edit extends Controller_TemplateJournalMaterialize
{
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック（していなかったらログイン画面にリダイレクト）
			if ( ! UserService::is_login())
			{
				\Response::redirect('/user/login');
			}
			# セッションチェック
			if ( ! UserService::is_valid_check_login())
			{
				\Response::redirect('/user/login/check');
			}
			# ユーザ情報取得
			$arr_user_info = (array)UserService::get_login_user_from_property();

			# プレゼンタ生成
			$presenter = Presenter::forge('user/edit/index');
			$presenter->set('arr_user_info', $arr_user_info);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ情報更新");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/edit/index');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ情報更新");
		}
	}

	public function action_execute()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# ログインチェック（してたらログアウト画面にリダイレクト）
			if ( ! UserService::is_login())
			{
				\Response::redirect('/user/login');
			}
			# リクエストを配列に格納
			$arr_params = array(
					'user_name'  => \Input::param('user_name'),
					'email'      => \Input::param('email'),
					'password'   => \Input::param('password'),
					'oauth_type' => \Input::param('oauth_type'),
			);
			# プレゼンタ生成
			$presenter = Presenter::forge('user/edit/index');
			# バリデーション
			if ( ! UserService::validation_for_edit($arr_params))
			{
				$arr_params['user_name'] = $this->arr_user_info['user_name'];
				$arr_params['email'] = $this->arr_user_info['email'];
				$presenter->set('arr_validation_error', UserService::get_validation_error());
				$presenter->set('arr_params', $arr_params);
				$presenter->set('arr_user_info', (array)UserService::get_login_user_from_property());
				$this->template->set_safe('contents', $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -ユーザ情報更新");
				return true;
			}
			# リクエストをサービスメンバ変数にセット
			UserService::set_request($arr_params);
			# API送信
			UserService::send_api_for_edit();
			# セッションに登録
			UserService::set_user_info_to_session();

			if (UserService::is_password_change())
			{
				# ログインセッション初期化
				\Session::set('login_check', false);
			}
			if (UserService::is_email_change())
			{
				# ログインセッション初期化
				\Session::set('login_check', false);
				# プレゼンタ生成
				$presenter = Presenter::forge('user/edit/execute');
				$presenter->set('arr_validation_error', array());
				$presenter->set('arr_params', array());
				$this->template->set_safe('contents', $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -メールアドレス変更確認");
				return true;
			}
			else
			{
				\Session::set_flash('message', 'ユーザ情報が更新されました');
				\Response::redirect('/payment/list');
			}
		}
		catch (\Exception $e)
		{
			\Log::error($e->getMessage());
			\Log::error($e->getCode());
			\Log::error($e->getFile(). '['. $e->getLine(). ']');
			$presenter = Presenter::forge('user/edit/index');
			$arr_user_info = (array)UserService::get_login_user_from_property();
			$arr_user_info['user_name'] = $this->arr_user_info['user_name'];
			$arr_user_info['email']     = $this->arr_user_info['email'];
			$presenter->set('arr_user_info', $arr_user_info);
			if ($e->getCode() == 7005)
			{
				$presenter->set('arr_validation_error', array('email' => 'このメールアドレスは登録済みです'));

				$presenter->set('arr_params', $arr_params);
			}
			else
			{
				if ($e->getCode() == 7014)
				{
					$message = 'すでにユーザ変更申請を受け付けております。変更の確定方法を記載したメールを送信しておりますのでそちらをご確認ください。';
					$exception_error_message = (Fuel::$env == "production")? $message: $message. ': '. $e->getFile(). '['. $e->getLine(). ']';
					$presenter->set('error_message', $exception_error_message);
				}
				else
				{
					$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
					$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
				}
			}
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ情報更新");
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
					'user_id'     => isset($this->arr_user_info['user_id'])? $this->arr_user_info['user_id']: '',
					'login_hash'  => isset($this->arr_user_info['login_hash'])? $this->arr_user_info['login_hash']: '',
					'oauth_type'  => $oauth_type,
			);
			UserService::validation_for_edit_decide($arr_params);
			UserService::set_request($arr_params);
			UserService::send_api_for_edit_decide($arr_params);
			if (UserService::get_validation_error())
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('user/edit/decideerror');
				$this->template->set_safe("contents", $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -ユーザ更新");
				return true;
			}
			UserService::set_user_info_to_session();
			\Session::set_flash('message', 'メールアドレスの更新が完了しました。');
			\Response::redirect('/payment/list');
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('user/edit/decideerror');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage(). ': '. $e->getFile(). '['. $e->getLine(). ']';
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ユーザ登録");
		}
	}

}
