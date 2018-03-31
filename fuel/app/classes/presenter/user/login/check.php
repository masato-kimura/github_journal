<?php

/**
 * The welcome hello presenter.
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_User_Login_Check extends Presenter
{
	/**
	 * Prepare the view data, keeping this in here helps clean up
	 * the controller.
	 *
	 * @return void
	 */
	public function view()
	{
		if (isset($this->error_message))
		{
			return false;
		}
		$this->email = $this->arr_user_info['email'];
		$this->oauth_type  = isset($this->arr_user_info['oauth_type'])? $this->arr_user_info['oauth_type']: '';
		switch($this->oauth_type)
		{
			case 'email':
				$this->oauth_type_disp = 'メールアドレス';
				break;
			case 'facebook':
				$this->oauth_type_disp = 'Facebook';
				break;
			case 'line':
				$this->oauth_type_disp = 'LINE';
				break;
			case 'google':
				$this->oauth_type_disp = 'Google';
				break;
			case 'twitter':
				$this->oauth_type_disp = 'Twitter';
				break;
			case 'yahoo':
				$this->oauth_type_disp = 'Yahoo!';
				break;
		}
	}
}
