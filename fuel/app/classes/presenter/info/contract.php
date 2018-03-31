<?php

class Presenter_Info_Contract extends Presenter
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