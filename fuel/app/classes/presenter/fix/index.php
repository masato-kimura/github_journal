<?php

use service\FixService;
class Presenter_Fix_Index extends Presenter
{
	public function view()
	{
		\Log::debug('[start]'. __METHOD__);

		if (isset($this->error_message))
		{
			return false;
		}

		if (isset($this->arr_validation_error))
		{
			if (preg_match('/^edit/', $this->error_from))
			{
				$id = isset($this->arr_params['id'])? $this->arr_params['id']: '';
				$arr_new_validation_error = array();
				foreach ($this->arr_validation_error as $i => $val)
				{
					$arr_new_validation_error[$i. '_'. $id] = $val;
				}
				$this->arr_validation_error = $arr_new_validation_error;
			}

			\Log::error($this->arr_validation_error);
		}
	}
}