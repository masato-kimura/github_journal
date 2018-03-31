<?php
use Fuel\Core\Presenter;
use Email\Email;
use service\UserService;
use service\InfoService;

/**
 *
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
class Controller_Info extends Controller_TemplateJournalMaterializeIndex
{
	public function action_terms()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('info/terms');

			$presenter->set('arr_params', $arr_params);

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル 利用規約");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('info/terms');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル 利用規約");
		}
	}

	public function action_question()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('info/question');

			$presenter->set('arr_params', $arr_params);

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル よくある質問");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('info/terms');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル よくある質問");
		}
	}


	public function action_privacy()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('info/privacy');

			$presenter->set('arr_params', $arr_params);

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル プライバシーポリシー");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('info/privacy');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル プライバシーポリシー");
		}
	}

	public function action_contract()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('info/contract');

			$presenter->set('arr_params', $arr_params);

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル 特定商取引に基づく表記");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('info/contract');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル 特定商取引に基づく表記");
		}
	}

	public function action_us()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('info/us');

			$presenter->set('arr_params', $arr_params);

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル 運営管理者");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('info/us');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル 運営管理者");
		}
	}

	public function action_contact()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('info/contact');
			$presenter->set('arr_user_info', $this->arr_user_info);
			$presenter->set('arr_params', $arr_params);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル お問い合わせ");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('info/contact');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル お問い合わせ");
		}
	}

	public function action_contactconfirm()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			# リクエスト配列
			$arr_params = array();

			# プレゼンタ生成
			$presenter = Presenter::forge('info/contactconfirm');

			$presenter->set('arr_params', $arr_params);

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル お問い合わせ");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('contact/contactconfirm');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル お問い合わせ");
		}
	}

	public function action_contactdone()
	{
		try
		{
			\Log::debug('[start]'. __METHOD__);

			if (\Session::get_flash('contact_send'))
			{
				\Response::redirect('/');
			}
			# リクエスト配列
			$arr_params = array(
				'email_contact'     => trim(\Input::post('email_contact')),
				'user_name_contact' => trim(\Input::post('user_name_contact')),
				'contact_contact'   => trim(\Input::post('contact_contact')),
			);

			InfoService::validation_for_contact($arr_params);

			if ( ! InfoService::is_validation_error())
			{
				# プレゼンタ生成
				$presenter = Presenter::forge('info/contact');
				$presenter->set('arr_user_info', $this->arr_user_info);
				$presenter->set('arr_params', $arr_params);
				$presenter->set('arr_validation_error', InfoService::get_validation_error());
				$this->template->set_safe("contents", $presenter->render());
				$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル お問い合わせ");
				return true;
			}

			$login_txt = '';
			if (UserService::is_login())
			{
				$login_txt = $login_txt. 'email: '. $this->arr_user_info['email']. PHP_EOL;
				$login_txt = $login_txt. 'login_hash: '. $this->arr_user_info['login_hash']. PHP_EOL;
				$login_txt = $login_txt. 'oauth_type: '. $this->arr_user_info['oauth_type']. PHP_EOL;
				$login_txt = $login_txt. 'oauth_id: '. $this->arr_user_info['oauth_id']. PHP_EOL;
			}

			$body = $arr_params['contact_contact']. PHP_EOL. PHP_EOL. $login_txt;

			$obj_email = \Email::forge('jis');
			$obj_email->from($arr_params['email_contact'], $arr_params['user_name_contact']);
			$obj_email->to(\Config::get('journal.email.to_address'));
			$obj_email->priority(\Email::P_HIGH);
			$obj_email->subject('ペイジャーナル お問い合わせフォームから');
			$obj_email->body($body);
			$obj_email->send();

			\Session::set_flash('contact_send', true);

			# プレゼンタ生成
			$presenter = Presenter::forge('info/contactdone');

			$presenter->set('arr_params', $arr_params);

			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル お問い合わせ");
		}
		catch (\Exception $e)
		{
			\Log::error($e);
			$presenter = Presenter::forge('info/contact');
			$exception_error_message = (Fuel::$env == "production")? '': $e->getMessage();
			$presenter->set('error_message', \Config::get('journal.system_error_message'). $exception_error_message);
			$this->template->set_safe("contents", $presenter->render());
			$this->template->set_safe("title", "家計簿アプリ -ペイジャーナル お問い合わせ");
		}
	}


}
