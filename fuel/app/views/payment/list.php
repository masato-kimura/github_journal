<?php use Fuel\Core\Pagination;
if (isset($error_message)):?>
<div class="container">
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else:?>
<style>
  select {
    display: inline;
    font-size: 16px;
  }
  #payment_table_div {
    box-sizing: border-box;
    border-right: 0px;
    overflow-x: scroll;
    -webkit-overflow-scrolling: touch;
  }
  #journal_list_table {
    width: 1190px;
    border: 0px solid #ddd;
  }
  #journal_list_table th {
    font-size: 14px;
    padding-left: 5px;
  }
  #journal_list_table td {
    padding: 5px;
    font-size: 14px;
    line-height: 15px;
  }
  #journal_list_table .btn, .btn-large {
    padding: 0 1.3em;
    font-size: small;
    height: 30px;
    line-height: 24px;
  }
  .collection-item blockquote {
    margin: 0px;
    border-left: 0px;
  }
  .modal .modal-close {
    width: 35px;
    height: 23px;
    line-height: 13px;
    padding: 5px;
  }
  .modal .modal-content {
    padding: 10px;
  }
  .modal-content .collection .collection-item {
    padding: 5px 10px;
    line-height: 1.4em;
    font-size: small;
  }
  .list_reserve_mark_commited {
    display: inline-block;
    padding: 2px;
    font-size: normal;
    color: #E37979;
    border: 1px solid #E37979;
  }
  .list_reserve_mark {
    display: inline-block;
    padding: 2px;
    color: #fff;
    background: red;
    border: 1px solid red;
  }
  .modal_reserve_mark_commited {
    display: inline-block;
    padding: 1px 5px;
    font-size: normal;
    color: #E37979;
    border: 1px solid #E37979;
  }
  .modal_reserve_mark {
    display: inline-block;
    padding: 1px 5px;
    color: #fff;
    background: red;
    border: 1px solid red;
  }
</style>
<script type="text/javascript">

$(window).load(function() {
	$('.modal').modal();
	//$('select').material_select();
});

jQuery(function($) {
	// graph1
	var obj_graph = {};
	obj_graph.load = function() {
		var chart_width = $(window).width();
		if (chart_width >= 500) {
			chart_width = 120;
		} else {
			chart_width = chart_width * 0.75;
		}
		$('#fix_graph1').attr('height', chart_width);
		$('#fix_graph2').attr('height', chart_width * 0.8);
		var ctx1 = $("#fix_graph1");
		var ctx2 = $("#fix_graph2");
		var data1 = {
			labels: [<?php echo $this->fix_per_label;?>],
			datasets: [
				{
					data: [<?php echo $this->fix_per_data;?>],
					backgroundColor: [<?php echo $this->fix_per_color;?>]
				}
			]
		};
		var data2 = {
				labels: [<?php echo $this->use_type_label;?>],
				datasets: [
					{
						data: [<?php echo $this->use_type_data;?>],
						backgroundColor: [<?php echo $this->use_type_color;?>]
					}
				]
			};
		var options1 = {
				title: {
					display: false,
					text: '領収別'
				}
		};
		var options2 = {
				title: {
					display: false,
					text: '目的別'
				}
		};
		var myPieChart1 = new Chart(ctx1,{
			type: 'pie',
			data: data1,
			options: options1
		});
		var myPieChart2 = new Chart(ctx2,{
			type: 'pie',
			data: data2,
			options: options2
		});
	};

	// 一覧操作
	var obj_list = {};
	obj_list.load = function() {
		// modal
		$('.modal_call_td').on('click', function() {
			var id = $(this).parent('tr').attr('id').match(/[\d]+$/);
			$('#modal_display_' + id).modal('open');
		});
		// remove
		$('.to_remove_anchor').on('click', function() {
			if ( ! confirm('このデータを削除しますがよろしいですか？')) {
				return false;
			}
		});
		$('#form_to_submit').on('click', function() {
			$(this).prop('disabled', true);
			$(this).parents('form').submit();
		});
	};

	// ページネーション
	var obj_pagination = {};
	obj_pagination.load = function() {
		// pagination ajax
		var font_color = $('#journal_list_table tbody td').css('color');
		$('.pagination a').on('click', function() {
			if ($(this).attr('href') == '#') {
				return false;
			}
			function number_format(num) {
				return num.toString().replace(/([0-9]+?)(?=(?:[0-9]{3})+$)/g , '$1,');
			}

			setTimeout(function() {
				//$('#journal_list_table tbody td').css('color', '#fff');
			}, 300);
			var parameter = $(this).attr('href').match(/[?].+$/);
			$('.pagination .active').children('a').attr('href', '');
			$('.pagination .active').removeClass('active');
			$(this).parent('li').addClass('active');
			$(this).attr('href', '#');
			var params = {};
			params['year']      = $('#form_year_tmp').val();
			params['month']     = $('#form_month_tmp').val();
			params['search']    = $('#form_search_tmp').val();
			params['sort_by']   = $('#form_sort_by_tmp').val();
			params['direction'] = $('#form_direction_tmp').val();
			params['count']     = $('#pagination_count').val();
			var page = parameter[0].match(/page=[^&]+/i);
			params['page'] = parseInt(page[0].match(/[0-9]+/));
			var sendAjax = function(params) {
				return $.ajax({
					type: 'post',
					url: '<?php echo \Config::get('journal.www_host');?>/payment/listajax.json',
					datatype: 'json',
					data: JSON.stringify(params),
					cache: false,
					success: function(res, ans) {
						if (res.success === false) {
							return false
						}
					},
					error: function() {
						alert('申し訳ございません。ネットワーク通信に失敗しました。');
						return true;
					}
				});
			};
			var setList = function(list) {
				$('#journal_list_table tbody').html('');
				var html = '';
				var tx = '';
				jQuery.each(list, function(i, v) {
					var name = v.name.length > 0 ? v.name: "-";
					var detail = v.detail.length > 0 ? v.detail: "-";
					var shop   = v.shop.length > 0 ? v.shop: "-";
					var reserve_name = '';
					switch (v.is_fix) {
						case '0':
							var is_fix = '変動費';
							break;
						case '1':
							var is_fix = '固定費';
							break;
					}
					switch (v.use_type) {
						case '0':
							var use_type = '-';
							break;
						case '1':
							var use_type = '消費';
							break;
						case '2':
							var use_type = '投資';
							break;
						case '3':
							var use_type = '浪費';
							break;
					}
					if (v.payment_reserve_id > 0)
					{
						reserve_name = "定期";
					}

					tx = tx + '<tr id="modal_call_tr_id_' + v.id + '" class="modal_call_tr">';
					if (v.payment_reserve_id > 0)
					{
						if (v.payment_reserve_status == 0)
						{
							tx = tx + '<td class="modal_call_td"><span class="list_reserve_mark">' + reserve_name + '</span></td>';
						}
						else
						{
							tx = tx + '<td class="modal_call_td"><span class="list_reserve_mark_commited">' + reserve_name + '</span></td>';
						}
					}
					else
					{
						tx = tx + '<td class="modal_call_td">' + reserve_name + '</td>';
					}
					tx = tx + '<td class="modal_call_td">' + v.date + '</td>';
					tx = tx + '<td class="modal_call_td">' + name + '</td>';
					tx = tx + '<td class="modal_call_td">' + detail + '</td>';
					tx = tx + '<td class="modal_call_td">' + shop + '</td>';
					tx = tx + '<td class="modal_call_td right-align">' + number_format(v.cost) + '円</td>';
					tx = tx + '<td class="modal_call_td right-align">' + v.work_side_per + '％</td>';
					tx = tx + '<td class="modal_call_td">' + is_fix + '</td>';
					//tx = tx + '<td class="modal_call_td">' + use_type + '</td>';
					tx = tx + '<td>';
					tx = tx + '<a class="btn to_edit_anchor" href="<?php echo \Config::get('journal.www_host');?>/payment/edit/'+ v.id +'/' + parameter + '"><i class="material-icons">update</i>更新</a>&nbsp;';
					tx = tx + '<a class="btn to_remove_anchor" href="<?php echo \Config::get('journal.www_host');?>/payment/remove/' + v.id + '/' + parameter + '"><i class="material-icons">delete</i>削除</a>';
					tx = tx + '</td>';
					tx = tx + '</tr>';
				});
				$('#journal_list_table tbody').html(tx);
				//$('#journal_list_table tbody td').css('color', '#333');
				$('#journal_list_table tbody').css('height', '');
				obj_list.load();
			}
			var setModal = function(list) {
				$('#set_modal').empty();
				var html = '';
				jQuery.each(list, function(i, v) {
					var name = v.name.length > 0 ? v.name: "-";
					var detail = v.detail.length > 0 ? v.detail: "-";
					var shop   = v.shop.length > 0 ? v.shop: "-";
					switch (v.is_fix) {
						case '0':
							var is_fix = '変動費';
							break;
						case '1':
							var is_fix = '固定費';
							break;
					}
					switch (v.use_type) {
						case '0':
							var use_type = '-';
							break;
						case '1':
							var use_type = '消費';
							break;
						case '2':
							var use_type = '投資';
							break;
						case '3':
							var use_type = '浪費';
							break;
					}
					switch (v.paymethod_id) {
						case '0':
							var paymethod = '指定なし';
							break;
						case '1':
							var paymethod = '現金';
							break;
						case '2':
							var paymethod = 'クレジットカード';
							break;
						case '3':
							var paymethod = 'プリペイドカード';
							break;
						default:
							var paymethod = '-';
					}
					html = html + '<div id="modal_display_' + v.id + '" class="modal modal-fixed-footer">';
					html = html + '<div class="container right">';
					html = html + '<div class="right" style="margin: 10px 15px 0px 0px;">';
					html = html + '<button class="modal-close btn waves-effect waves-light red lighten-1" name="to_update"><i class="material-icons">close</i></button>';
					html = html + '</div>';
					html = html + '</div>';
					html = html + '<div class="modal-content">';

					html = html + '<p><i class="material-icons left">details</i>' + v.date + '  ' + detail + '</p>';

					html = html + '<ul class="collection">';

					if (v.payment_reserve_id > 0)
					{
						if (v.payment_reserve_status == 0)
						{
							html = html + '<li class="collection-item">';
							html = html + '<span class="title">定期</span>';
							html = html + '<blockquote><span class="modal_reserve_mark">未確定</span></blockquote>';
							html = html + '</li>';
						}
						else
						{
							html = html + '<li class="collection-item">';
							html = html + '<span class="title">定期</span>';
							html = html + '<blockquote><span class="modal_reserve_mark_commited">確定済み</span></blockquote>';
							html = html + '</li>';
						}
					}

					html = html + '<li class="collection-item">';
					html = html + '<span class="title">金額</span>';
					html = html + '<blockquote>' + number_format(v.cost) + '円</blockquote>';
					html = html + '</li>';

					html = html + '<li class="collection-item">';
					html = html + '<span class="title">カテゴリー</span>';
					html = html + '<blockquote>' + name + '</blockquote>';
					html = html + '</li>';

					html = html + '<li class="collection-item">';
					html = html + '<span class="title">支払い先</span>';
					html = html + '<blockquote>' + shop + '</blockquote>';
					html = html + '</li>';

					html = html + '<li class="collection-item">';
					html = html + '<span class="title">支払い方法</span>';
					html = html + '<blockquote>' + paymethod + '</blockquote>';
					html = html + '</li>';

					html = html + '<li class="collection-item">';
					html = html + '<span class="title">経費割合</span>';
					html = html + '<blockquote>' + v.work_side_per + '％</blockquote>';
					html = html + '</li>';

					html = html + '<li class="collection-item">';
					html = html + '<span class="title">使用目的</span>';
					html = html + '<blockquote>' + use_type + '</blockquote>';
					html = html + '</li>';

					html = html + '<li class="collection-item">';
					html = html + '<span class="title">形態</span>';
					html = html + '<blockquote>' + is_fix + '</blockquote>';
					html = html + '</li>';

					html = html + '<li class="collection-item">';
					html = html + '<span class="title">備考</span>';
					html = html + '<blockquote>' + v.remark + '&nbsp;</blockquote>';
					html = html + '</li>';

					html = html + '</ul>';

					html = html + '</div>';

					html = html + '<div class="modal-footer">';
					html = html + '<a class="modal-action waves-effect waves-light to_remove_anchor btn" href="<?php echo \Config::get('journal.www_host');?>/payment/remove/' + v.id + '/' + parameter + '">';
					html = html + '<i class="material-icons">delete</i>削除</i>';
					html = html + '</a>';
					html = html + '<a class="modal-action waves-effect waves-light to_edit_anchor btn" style="margin-right: 3px;" href="<?php echo \Config::get('journal.www_host');?>/payment/edit/' + v.id + '/' + parameter + '">';
					html = html + '<i class="material-icons">update</i>更新';
					html = html + '</a>';
					html = html + '</div>';
					html = html + '</div>';
				});
				$('#set_modal').html(html);
				$('.modal').modal();
			}
			var setPagination = function(pagination) {
				$('ul.pagination').addClass('pagination_tmp');
				$('ul.pagination_tmp').removeClass('pagination');
				$('ul.pagination_tmp').after(pagination);
				$('ul.pagination_tmp').remove();
				obj_pagination.load();
			};
			sendAjax(params).done(function(res) {
				setList(res.result.list);
				setModal(res.result.list);
				setPagination(res.result.pagination);
			});
			return false;
		});
	};

	// init
	obj_graph.load();
	obj_list.load();
	obj_pagination.load();

});
</script>


<div class="container">

	<br />

	<h5 class="header"><i class="material-icons prefix" style="display:inline-block; margin-right: 5px; vertical-align: middle; font-size: xx-large; color: #ccc;">view_list</i>支出データ一覧</h5>
	<br />

	<div><?php echo Html::anchor('/payment/add/?'. http_build_query($arr_params), '<i class="material-icons prefix">mode_edit</i>支出データ入力へ', array('class' => 'btn waves-effect waves-light', 'style' => 'width: 100%; height: 40px;'));?></div>

	<br />

	<div class="row">
		<?php echo Form::open(array('action' => '/payment/list/', 'class' => 'col s12', 'id' => 'search_form'));?>
		<div class="row">
			<div class="col s6 m6 l2">
				<?php echo Form::label('表示年', 'year');?>
				<?php echo Form::select('year', \Input::param('year', \Date::forge()->format('%Y')), $arr_year, array('placeholder' => ''));?>
				<?php if (isset($arr_validation_error['year'])):?>
					<div class="alert alert-danger"><?php echo $arr_validation_error['year'];?></div>
				<?php endif;?>
			</div>
			<div class="col s6 m6 l2">
				<?php echo Form::label('表示月', 'month');?>
				<?php echo Form::select('month', \Input::param('month', \Date::forge()->format('%m')), $arr_month, array('style' => 'margin-bottom: 20px;'));?>
				<?php if (isset($arr_validation_error['month'])):?>
					<div class="alert alert-danger"><?php echo $arr_validation_error['month'];?></div>
				<?php endif;?>
			</div>
			<div class="input-field col s12 m3 l2">
				<?php echo Form::input('search', \Input::param('search'), array('class' => 'validate', 'maxlength' => '100', 'placeholder' => ''));?>
				<?php echo Form::label('キーワード検索', 'search', array('class' => 'active'));?>
				<?php if (isset($arr_validation_error['search'])):?>
					<div class="alert alert-danger"><?php echo $arr_validation_error['search'];?></div>
				<?php endif;?>
			</div>
			<div class="col s6 m2 l2">
				<?php echo Form::label('並べ替え', 'sort_by');?>
				<?php echo Form::select('sort_by', \Input::param('sort_by', 'date'), $arr_sort, array('class' => 'form-control'));?>
				<?php if (isset($arr_validation_error['sort_by'])):?>
					<div class="alert alert-danger"><?php echo $arr_validation_error['sort_by'];?></div>
				<?php endif;?>
			</div>
			<div class="col s6 m2 l2">
				<?php echo Form::label('&nbsp;', 'direction');?>
				<?php echo Form::select('direction', \Input::param('direction', 'DESC'), $arr_direction, array('class' => 'form-control'));?>
				<?php if (isset($arr_validation_error['direction'])):?>
					<div class="alert alert-danger"><?php echo $arr_validation_error['direction'];?></div>
				<?php endif;?>
			</div>
			<div class="input-field col s12 m3 l2 right-align">
				<?php echo Form::hidden('year_tmp', \Input::param('year', \Date::forge()->format('%Y')));?>
				<?php echo Form::hidden('month_tmp', \Input::param('month', \Date::forge()->format('%m')));?>
				<?php echo Form::hidden('search_tmp', \Input::param('search'));?>
				<?php echo Form::hidden('sort_by_tmp', \Input::param('sort_by', 'date'));?>
				<?php echo Form::hidden('direction_tmp', \Input::param('direction', 'DESC'));?>

				<?php echo Form::button('to_submit', '<i class="material-icons">search</i>検索', array('type' => 'submit', 'class' => 'btn waves-effect waves-light', 'style' => 'margin-top: 7px; height: 43px;'));?>
			</div>
		</div>
		<?php echo Form::close();?>
	</div>


	<?php if ( ! empty($this->fix_per_data)):?>
		<div class="row  hide-on-med-and-down">
			<div class="col s12 m6 l6">
				<canvas id="fix_graph1" class="fix_graph"></canvas>
			</div>
			<div class="col s12 m6 l6">
				<canvas id="fix_graph2" class="fix_graph"></canvas>
			</div>
		</div>

		<br />
	<?php endif;?>

	<div class="row" style="margin-top: -20px; margin-bottom: 2px;">
		<div class="col s12">
			<h5 style="margin: 3px 0px;">
				<span>合計</span>
				<span><?php echo number_format($all_cost);?>円</span>
			</h5>
		</div>
	</div>

	<div class="row">
		<div class="col s12 l4">
			<?php if (\Input::param('month', \Date::forge()->format('%m') > 0)):?>
				<b style="font-size: medium;">
					<span>月支出予定</span>
					<span><?php echo ( ! empty($all_cost))? number_format($outof_fix_cost + $average_fix_cost): '-';?>円</span>
				</b>
				<br />
				<span class="total_title">月平均固定費</span>
				<span class="total_cost"><?php echo ( ! empty($all_cost))? number_format($average_fix_cost): '-';?>円</span>
			<?php endif;?>
		</div>
		<div class="col s12 l4">
			<span class="total_title">内固定費</span>
			<span class="total_cost"><?php echo ( ! empty($fix_cost))? number_format($fix_cost): '-';?>円</span>
			<br />
			<span class="total_title">内変動費</span>
			<span class="total_cost"><?php echo ( ! empty($all_cost))? number_format($all_cost - $fix_cost): '-';?>円</span>
		</div>
		<div class="col s12 l4">
			<span class="total_title">内業務経費</span>
			<span class="total_cost"><?php echo ( ! empty($work_side_cost))? number_format($work_side_cost): '-';?>円</span>
		</div>
	</div>

	<?php if (Pagination::instance('list_pagination')):?>
		<?php echo Pagination::instance('list_pagination')->render(); ?>
		<?php echo Form::hidden('pagination_count', Pagination::instance('list_pagination')->total_items, array('id' => 'pagination_count'));?>
	<?php endif;?>

	<div class="payment_count_disp">全<?php echo number_format(Pagination::instance('list_pagination')->total_items);?>件</div>
	<div class="payment_table_div" id="payment_table_div">
		<table id="journal_list_table" class="bordered striped highlight journal_list_table centered">
		<thead>
		<tr>
			<th scope="row" style="width: 70px;"></th>
			<th scope="row" style="width: 120px;">日付</th>
			<th scope="row" style="min-width: 100px; max-width: 150px;">カテゴリー</th>
			<th scope="row" style="min-width: 100px; max-width: 280px;">内訳</th>
			<th scope="row" style="min-width: 100px; max-width: 300px;">店舗名</th>
			<th scope="row" style="width: 130px;">金額</th>
			<th scope="row" style="width: 90px;">経費割合</th>
			<th scope="row" style="width: 90px;">形態</th>
			<th scope="row" style="width: 240px;">更新/削除</th>
		</tr>
		</thead>
		<tbody>
		<?php if (isset($list)):?>
		<?php foreach ($list as $i => $val):?>
		<tr class="modal_call_tr" id="modal_call_tr_id_<?php echo $val->id;?>">
			<?php if ( ! empty($val->payment_reserve_id) and  ! empty($val->payment_reserve_status)):?>
			<td class="modal_call_td">
				<span class="list_reserve_mark_commited" title="定期データ確定済み">定期</span>
				<span class="list_reserve_mark_commited" title="定期データ確定済み">済</span>
			</td>
			<?php elseif ( ! empty($val->payment_reserve_id) and  empty($val->payment_reserve_status)):?>
			<td class="modal_call_td">
				<span class="list_reserve_mark" title="定期データ未確定">定期</span>
				<span class="list_reserve_mark" title="定期データ未確定">未</span>
			</td>
			<?php else:?>
			<td class="modal_call_td"></td>
			<?php endif;?>

			<td class="modal_call_td"><?php echo $val->date;?></td>
			<td class="modal_call_td"><?php echo $val->name;?></td>
			<td class="modal_call_td"><?php echo empty($val->detail)? '-': $val->detail;?></td>
			<td class="modal_call_td"><?php echo empty($val->shop)? '-': $val->shop;?></td>
			<td class="modal_call_td right-align"><?php echo empty($val->cost)? '-': number_format($val->cost);?>円</td>
			<td class="modal_call_td right-align"><?php echo $val->work_side_per;?>%</td>
			<td class="modal_call_td"><?php echo $val->is_fix == '1'? '固定費': '変動費';?></td>
			<td>
			<?php echo Html::anchor('/payment/edit/'. $val->id. '/?'. http_build_query($arr_params),'<i class="material-icons">update</i>更新', array('class' => 'btn to_edit_anchor'));?>
			<?php echo Html::anchor('/payment/remove/'. $val->id. '/?'. http_build_query($arr_params), '<i class="material-icons">delete</i>削除', array('class' => 'btn to_remove_anchor'));?>
			</td>
		</tr>
		<?php endforeach;?>
		<?php endif;?>
		</tbody>
		</table>
	</div>

	<div class="row">
		<div class="col s12">
			<?php if (Pagination::instance('list_pagination')):?>
			<?php echo Pagination::instance('list_pagination')->render(); ?>
			<?php endif;?>
		</div>
	</div>

	<br />
	<br />
	<div><?php echo Html::anchor('/payment/add/?'. http_build_query($arr_params), '<i class="material-icons prefix">mode_edit</i>支出データ入力へ', array('class' => 'btn waves-effect waves-light', 'style' => 'width: 100%; height: 40px;'));?></div>
	<br />
	<div><?php echo Html::anchor('/fix/?'. http_build_query($arr_params), '<i class="material-icons prefix">class</i>カテゴリー登録へ', array('class' => 'btn waves-effect waves-light', 'style' => 'width: 100%; height: 40px;'));?></div>
	<br />
	<div><?php echo Html::anchor('/payment/reservelist/', '<i class="material-icons prefix">repeat</i>定期支出データ登録へ', array('class' => 'btn waves-effect waves-light pink darken-3', 'style' => 'width: 100%; height: 40px;'));?></div>

	<br /><br />

	<!-- モーダルの配置 -->
	<div id="set_modal">
	<?php if (isset($list)):?>
		<?php foreach ($list as $i => $val):?>
		<div class="modal modal-fixed-footer" id="modal_display_<?php echo $val->id;?>">
			<div class="container right">
				<div class="right" style="margin: 7px 23px 0px 0px;">
					<?php echo Form::button('to_update', '<i class="material-icons">close</i>', array('class' => 'modal-close btn-flat waves-effect waves-light'));?>
				</div>
			</div>
			<div class="modal-content">

				<p><i class="material-icons left">details</i><?php echo $val->date;?>&nbsp;&nbsp;<?php echo $val->detail;?></p>

				<ul class="collection">
					<?php if ( ! empty($val->payment_reserve_id)):?>
						<?php if (empty($val->payment_reserve_status)): ?>
						<li class="collection-item">
							<span class="title">定期</span>
							<blockquote><span class="modal_reserve_mark">未確定</span></blockquote>
						</li>
						<?php else:?>
						<li class="collection-item">
							<span class="title">定期</span>
							<blockquote><span class="modal_reserve_mark_commited">確定済み</span></blockquote>
						</li>
						<?php endif;?>

						<li class="collection-item">
							<span class="title">定期間隔</span>
							<blockquote>
								<?php echo $arr_every_type[$val->every_type];?>
								<?php if ( ! empty($val->every_month_selected)):?>
									<?php echo $val->every_month_selected;?>月
								<?php endif;?>
								<?php if ( ! empty($val->every_day_selected)):?>
									<?php echo $val->every_day_selected;?>日
								<?php endif;?>
								<?php if ( ! empty($val->every_dayofweek_selected) and $val->every_dayofweek_selected != ""):?>
									<?php echo $arr_every_dayofweek_selected[$val->every_dayofweek_selected];?>曜日
								<?php endif;?>
							</blockquote>
						</li>

						<li class="collection-item">
							<span class="title">定期対象期間</span>
							<blockquote>
								<span><?php echo $val->date_from;?></span> ～ <span><?php echo $val->date_to;?></span>
							</blockquote>
						</li>
					<?php endif;?>



					<li class="collection-item">
						<span class="title">金額</span>
						<blockquote><?php echo number_format($val->cost);?>円</blockquote>
					</li>
					<li class="collection-item">
						<span class="title">カテゴリー</span>
						<blockquote><?php echo empty($val->name)? '-': $val->name;?></blockquote>
					</li>
					<li class="collection-item">
						<span class="title">支払い先</span>
						<blockquote><?php echo empty($val->shop)? '-': $val->shop;?></blockquote>
					</li>
					<li class="collection-item">
						<span class="title">支払い方法</span>
						<blockquote>
							<?php if ($val->paymethod_id == '0'):?>
								指定なし
							<?php elseif ($val->paymethod_id == '1'):?>
								現金
							<?php elseif ($val->paymethod_id == '2'):?>
								クレジットカード
							<?php elseif ($val->paymethod_id == '3'):?>
								プリペイドカード
							<?php else:?>
								-
							<?php endif;?>
						</blockquote>
					</li>
					<li class="collection-item">
						<span class="title">経費割合</span>
						<blockquote><?php echo $val->work_side_per;?>%</blockquote>
					</li>
					<li class="collection-item">
						<span class="title">使用目的</span>
						<blockquote><?php echo $arr_use_type_label[$val->use_type];?></blockquote>
					</li>
					<li class="collection-item">
						<span class="title">形態</span>
						<blockquote><?php echo $val->is_fix == '1'? '固定費': '変動費';?></blockquote>
					</li>
					<li class="collection-item">
						<span class="title">備考</span>
						<blockquote><?php echo ! empty($val->remark)? $val->remark: '-';?></blockquote>
					</li>
				</ul>
			</div> <!-- modal-content -->
			<div class="modal-footer">
				<?php echo Html::anchor('/payment/remove/'. $val->id. '/?'. http_build_query($arr_params), '<i class="material-icons">delete</i>削除', array('class' => 'modal-action waves-effect waves-light to_remove_anchor btn'));?>
				<?php echo Html::anchor('/payment/edit/'. $val->id. '/?'. http_build_query($arr_params), '<i class="material-icons">update</i>更新', array('class' => 'modal-action waves-effect waves-light to_edit_anchor btn', 'style' => 'margin-right: 3px;'));?>
			</div>
		</div>
		<?php endforeach;?>
	<?php endif;?>
	</div>
</div>
<?php endif;?>
</body>
</html>
