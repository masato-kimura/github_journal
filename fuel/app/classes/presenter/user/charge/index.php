<?php

use service\UserService;
/**
 * The welcome hello presenter.
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_User_Charge_Index extends Presenter
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

		$arr_user_info = UserService::get_login_user_from_property();
		$this->user_name   = $arr_user_info->user_name;
		$this->email       = $arr_user_info->email;
		$this->oauth_type  = $arr_user_info->oauth_type;

	}
}
