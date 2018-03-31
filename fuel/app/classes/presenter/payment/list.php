<?php

/**
 * The welcome hello presenter.
 *
 * @package  app
 * @extends  Presenter
 */
class Presenter_Payment_List extends Presenter
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
				'date'   => '日付',
				'name'   => 'カテゴリー',
				'detail' => '内訳',
				'shop'   => '店舗名',
				'cost'   => '金額',
		);
		$this->arr_direction = array(
				'ASC'  => '昇順',
				'DESC' => '降順',
		);

		# 一覧
		$this->list = $this->obj_list_data->list;
		# 種別
		$arr_fix_per_list = array();
		foreach ($this->obj_list_data->fix_per_list as $i => $val)
		{
			if ($val->fix_id != "999999999999999999")
			{
				$arr_fix_per_list[] = $val;
			}
			else
			{
				$other_fix_val = new stdClass();
				$other_fix_val = $val;
				$other_fix_val->fix_name = "その他";
			}
		} // endforeach
		if (isset($other_fix_val))
		{
			$arr_fix_per_list[] = $other_fix_val;
		}
		$arr_fix_per_label = array();
		$arr_fix_per_color = array();
		$arr_fix_per_data  = array();
		foreach ($arr_fix_per_list as $i => $val)
		{
			$arr_fix_per_label[] = '"'. $val->fix_name. '"';
			if ($val->fix_id == "999999999999999999")
			{
				$arr_fix_per_color[] = '"#cccccc"';
			}
			else
			{
				$arr_fix_per_color[] = '"#'. substr(md5($val->fix_name), 10, 6). '"';
			}

			$arr_fix_per_data[]  = $val->cost;
		}
		$this->fix_per_label = implode(',', $arr_fix_per_label);
		$this->fix_per_color = implode(',', $arr_fix_per_color);
		$this->fix_per_data  = implode(',', $arr_fix_per_data);

		# 使い道
		$arr_use_type_label = array();
		$arr_use_type_color = array();
		$arr_use_type_data  = array();
		foreach ($this->obj_list_data->use_type_list as $i => $val)
		{
			$arr_use_type_data[]  = $val->cost;
			switch ($val->use_type)
			{
				case '0':
					$arr_use_type_label[] = '"目的未指定"';
					$arr_use_type_color[] = '"#cccccc"';
					break;
				case '1':
					$arr_use_type_label[] = '"消費"';
					$arr_use_type_color[] = '"#65dc31"';
					break;
				case '2':
					$arr_use_type_label[] = '"投資"';
					$arr_use_type_color[] = '"#37609d"';
					break;
				case '3':
					$arr_use_type_label[] = '"浪費"';
					$arr_use_type_color[] = '"#ed2b6d"';
					break;
			}
		}
		$this->use_type_label = implode(',', $arr_use_type_label);
		$this->use_type_color = implode(',', $arr_use_type_color);
		$this->use_type_data  = implode(',', $arr_use_type_data);
		$this->arr_use_type_label = array(
				0 => '-',
				1 => '消費',
				2 => '投資',
				3 => '浪費',
		);

		$this->arr_reserve_type = array(
				'0' => '',
				'1' => '定期',
		);
		$this->arr_every_type = array(
				'3' => '年毎',
				'2' => '月毎',
				'1' => '週毎',
				'0' => '日毎'
		);
		$this->arr_every_dayofweek_selected = array(
				''  => '選択してください',
				'0' => '日',
				'1' => '月',
				'2' => '火',
				'3' => '水',
				'4' => '木',
				'5' => '金',
				'6' => '土',
		);

		# 月別合計値
		// 合計金額
		$this->all_cost         = $this->obj_list_data->all_cost;
		// 業務使用金額
		$this->work_side_cost   = $this->obj_list_data->work_side_cost;
		// 固定費金額
		$this->fix_cost         = $this->obj_list_data->fix_cost;
		// 変動費金額
		$this->outof_fix_cost   = $this->obj_list_data->outof_fix_cost;
		// 過去の平均固定費金額
		$this->average_fix_cost = $this->obj_list_data->average_fix_cost;
	}
}
