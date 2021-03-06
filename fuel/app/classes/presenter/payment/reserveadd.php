<?php

/**
 * The welcome hello presenter.
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_Payment_Reserveadd extends Presenter
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

		$this->arr_month_list = array('0' => '選択してください');
		for ($i=1; $i<=12; $i++)
		{
			$this->arr_month_list[$i] = $i. "月";
		}

		$this->arr_day_list = array('0' => '選択してください');
		for ($i=1; $i<=31; $i++)
		{
			$this->arr_day_list[$i] = $i. "日";
		}

		$this->arr_dayofweek_list = array(
				''  => '選択してください',
				'0' => '日',
				'1' => '月',
				'2' => '火',
				'3' => '水',
				'4' => '木',
				'5' => '金',
				'6' => '土',
		);

		$this->arr_fix_list = array('0' => '選択してください');
		$this->arr_is_fixies = array();
		foreach ($this->obj_fix_list as $i => $val)
		{
			if ($val->is_disp != "1")
			{
				continue;
			}
			$this->arr_fix_list[$val->id] = $val->name;
			if ($val->is_fix == '1')
			{
				$this->arr_is_fixies[] = $val->id;
			}
		}
		$this->arr_fix_list['999999999999999999'] = "その他";

		$this->arr_paymethod = array(
				'0' => '指定なし',
				'1' => '現金',
				'2' => 'クレジットカード',
				'3' => 'プリペイドカード',
		);
	}
}
