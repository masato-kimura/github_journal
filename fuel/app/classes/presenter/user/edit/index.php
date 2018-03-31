<?php

use service\UserService;
/**
 * The welcome hello presenter.
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_User_Edit_Index extends Presenter
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

		$this->user_name  = $this->arr_user_info['user_name'];
		$this->email      = $this->arr_user_info['email'];
		$this->oauth_type = $this->arr_user_info['oauth_type'];
		$this->password_digits = '';
		for ($i=0; $i<$this->arr_user_info['password_digits']; $i++)
		{
			$this->password_digits .= '*';
		}
		switch ($this->oauth_type)
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
			default:
				$this->oauth_type_disp = $this->oauth_type;
		}
	}
}
