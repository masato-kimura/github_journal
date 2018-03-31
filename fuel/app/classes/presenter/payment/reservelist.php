<?php

/**
 * The welcome hello presenter.
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_Payment_Reservelist extends Presenter
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

		/**
		 * 検索フォーム
		 */
		 // 年
		$this->arr_year = array();
		for ($i=2013; $i<=(\Date::forge()->format('%Y') + 1); $i++)
		{
			$this->arr_year[$i] = $i. '年';
		}
		// 月
		$this->arr_month = array('' => '全て');
		for ($i=1; $i<=12; $i++)
		{
			$this->arr_month[sprintf('%02d', $i)] = $i. '月';
		}
		// ソート
		$this->arr_sort = array(
				'every_type' => '定期間隔',
				'name'   => 'カテゴリー',
				'detail' => '内訳',
				'shop'   => '店舗名',
				'cost'   => '金額',
		);
		$this->arr_direction = array(
				'ASC'  => '昇順',
				'DESC' => '降順',
		);
		$this->arr_use_type_label = array(
				0 => '-',
				1 => '消費',
				2 => '投資',
				3 => '浪費',
		);
		$this->arr_every_type = array(
				'3' => '年毎',
				'2' => '月毎',
				'1' => '週毎',
				'0' => '日毎'
		);
		$this->arr_every_type_display = array(
				''  => 'すべて表示',
				'3' => '年毎',
				'2' => '月毎',
				'1' => '週毎',
				'0' => '日毎'
		);
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

		# 一覧
		$this->list = $this->obj_list_data->list;
	}
}
