<?php

/**
 * The welcome hello presenter.
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_User_Regist_Email extends Presenter
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

		$this->user_name = "";
		$this->email     = "";
		if ( ! empty($this->arr_params))
		{
			$this->user_name = $this->arr_params['user_name'];
			$this->email     = $this->arr_params['email'];
		}
	}
}
