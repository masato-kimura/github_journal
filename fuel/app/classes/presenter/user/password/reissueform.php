<?php

use service\UserService;
/**
 * The welcome hello presenter.
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_User_Password_Reissueform extends Presenter
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
	}
}
