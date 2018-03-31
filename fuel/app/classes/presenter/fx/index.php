<?php

class Presenter_Fx_Index extends Presenter
{
	public function view()
	{
		\Log::debug('[start]'. __METHOD__);

		if (isset($this->error_message))
		{
			return false;
		}
	}
}