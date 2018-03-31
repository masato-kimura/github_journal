<?php

/**
 * The welcome hello presenter.
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_User_Regist_Google extends Presenter
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

		$arr_user_info       = $this->arr_params;
		$this->user_name     = $arr_user_info['user_name'];
		$this->email         = $arr_user_info['email'];
		$this->oauth_type    = $arr_user_info['oauth_type'];
		$this->oauth_id      = $arr_user_info['oauth_id'];
	}
}
