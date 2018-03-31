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
  .alert {
    background: #f2dede;
    color: #a94442;
    border-color: #a94442;
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
</style>
<script type="text/javascript">

$(window).load(function() {
	$('.modal').modal();
	//$('select').material_select();
});

jQuery(function($) {

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
					url: '<?php echo \Config::get('journal.www_host');?>/payment/reservelistajax.json',
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
					tx = tx + '<tr id="modal_call_tr_id_' + v.id + '" class="modal_call_tr">';
					tx = tx + '<td class="modal_call_td">' + v.date + '</td>';
					tx = tx + '<td class="modal_call_td">' + name + '</td>';
					tx = tx + '<td class="modal_call_td">' + detail + '</td>';
					tx = tx + '<td class="modal_call_td">' + shop + '</td>';
					tx = tx + '<td class="modal_call_td right-align">' + number_format(v.cost) + '円</td>';
					tx = tx + '<td class="modal_call_td right-align">' + v.work_side_per + '％</td>';
					tx = tx + '<td class="modal_call_td">' + is_fix + '</td>';
					tx = tx + '<td class="modal_call_td">' + use_type + '</td>';
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
					html = html + '<span class="title">業務割合</span>';
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
	obj_list.load();
	obj_pagination.load();

});
</script>


<div class="container">

	<br />

	<h5 class="header"><i class="material-icons prefix" style="display:inline-block; margin-right: 5px; vertical-align: middle; font-size: xx-large; color: #ccc;">view_list</i>定期支出データ登録</h5>

	<br />

	<div><?php echo Html::anchor('/payment/reserveadd/?'. http_build_query($arr_params), '<i class="material-icons prefix">mode_edit</i>定期支出データ入力へ', array('class' => 'btn waves-effect waves-light pink darken-3', 'style' => 'width: 100%; height: 40px;'));?></div>

	<br />

	<div class="row">
		<?php echo Form::open(array('action' => '/payment/reservelist/', 'class' => 'col s12', 'id' => 'search_form'));?>
		<div class="row">
			<div class="col s6 m6 l2">
				<?php echo Form::label('表示年', 'year');?>
				<?php echo Form::select('year', \Input::param('year', \Date::forge()->format('%Y')), $arr_year, array('placeholder' => ''));?>
				<?php if (isset($arr_validation_error['year'])):?>
					<div class="alert alert-danger"><?php echo $arr_validation_error['year'];?></div>
				<?php endif;?>
			</div>
			<div class="col s6 m6 l2">
				<?php echo Form::label('定期間隔', 'every_type');?>
				<?php echo Form::select('every_type', \Input::param('every_type', ''), $arr_every_type_display, array('style' => 'margin-bottom: 20px;'));?>
				<?php if (isset($arr_validation_error['every_type'])):?>
					<div class="alert alert-danger"><?php echo $arr_validation_error['every_type'];?></div>
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

				<?php echo Form::button('to_submit', '<i class="material-icons">search</i>検索', array('type' => 'submit', 'class' => 'btn waves-effect waves-light pink darken-3', 'style' => 'margin-top: 7px; height: 43px;'));?>
			</div>
		</div>
		<?php echo Form::close();?>
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
			<th scope="row" style="width: 105px;">定期間隔</th>
			<th scope="row" style="min-width: 100px; max-width: 150px;">カテゴリー</th>
			<th scope="row" style="min-width: 100px; max-width: 280px;">内訳</th>
			<th scope="row" style="min-width: 100px; max-width: 300px;">店舗名</th>
			<th scope="row" style="width: 130px;">金額</th>
			<th scope="row" style="width: 90px;">業務割合</th>
			<th scope="row" style="width: 90px;">形態</th>
			<th scope="row" style="width: 70px;">目的</th>
			<th scope="row" style="width: 120px;">定期対象開始日</th>
			<th scope="row" style="width: 120px;">定期対象終了日</th>
			<th scope="row" style="width: 240px;">更新/削除</th>
		</tr>
		</thead>
		<tbody>
		<?php if (isset($list)):?>
		<?php foreach ($list as $i => $val):?>
		<tr class="modal_call_tr" id="modal_call_tr_id_<?php echo $val->id;?>">
			<td class="modal_call_td">
				<?php echo $arr_every_type[$val->every_type];?>
				<?php echo  ! empty($val->every_month_selected)? $val->every_month_selected. "月": "";?><?php echo isset($val->every_dayofweek_selected) && $val->every_dayofweek_selected != '' ? $arr_dayofweek_list[$val->every_dayofweek_selected]. "曜": "";?><?php echo  ! empty($val->every_day_selected)? $val->every_day_selected. "日": "";?>
				</td>
			<td class="modal_call_td"><?php echo $val->name;?></td>
			<td class="modal_call_td"><?php echo empty($val->detail)? '-': $val->detail;?></td>
			<td class="modal_call_td"><?php echo empty($val->shop)? '-': $val->shop;?></td>
			<td class="modal_call_td right-align"><?php echo empty($val->cost)? '-': number_format($val->cost);?>円</td>
			<td class="modal_call_td right-align"><?php echo $val->work_side_per;?>%</td>
			<td class="modal_call_td"><?php echo $val->is_fix == '1'? '固定費': '変動費';?></td>
			<td class="modal_call_td"><?php echo $arr_use_type_label[$val->use_type];?></td>
			<td class="modal_call_td"><?php echo $val->date_from;?></td>
			<td class="modal_call_td"><?php echo $val->date_to;?></td>
			<td>
			<?php echo Html::anchor('/payment/reserveedit/'. $val->id. '/?'. http_build_query($arr_params),'<i class="material-icons">update</i>更新', array('class' => 'btn to_edit_anchor pink darken-3'));?>
			<?php echo Html::anchor('/payment/reserveremove/'. $val->id. '/?'. http_build_query($arr_params), '<i class="material-icons">delete</i>削除', array('class' => 'btn to_remove_anchor pink darken-3'));?>
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
	<div><?php echo Html::anchor('/payment/reserveadd/?'. http_build_query($arr_params), '<i class="material-icons prefix">mode_edit</i>定期支出データ入力へ', array('class' => 'btn waves-effect waves-light pink darken-3', 'style' => 'width: 100%; height: 40px;'));?></div>
	<br />
	<hr style="box-sizing: inherit; color: #ddd;"></hr>
	<br />
	<div><?php echo Html::anchor('/payment/list/', '<i class="material-icons prefix">view_list</i>支出データ一覧', array('class' => 'btn waves-effect waves-light', 'style' => 'width: 100%; height: 40px;'));?></div>
	<br />
	<div><?php echo Html::anchor('/fix/?'. http_build_query($arr_params), '<i class="material-icons prefix">class</i>カテゴリー登録', array('class' => 'btn waves-effect waves-light', 'style' => 'width: 100%; height: 40px;'));?></div>
	<br />

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

				<p><i class="material-icons left">details</i>定期支出内訳 &nbsp;&nbsp;<?php echo $val->detail;?></p>

				<ul class="collection">
					<li class="collection-item">
						<span class="title">定期間隔</span>
						<blockquote>
							<?php echo $arr_every_type[$val->every_type];?>
							<?php echo  ! empty($val->every_month_selected)? $val->every_month_selected. "月": "";?>
							<?php echo isset($val->every_dayofweek_selected) && $val->every_dayofweek_selected != NULL ? $arr_dayofweek_list[$val->every_dayofweek_selected]. "曜": "";?>
							<?php echo  ! empty($val->every_day_selected)? $val->every_day_selected. "日": "";?>
						</blockquote>
					</li>
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
						<span class="title">業務割合</span>
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
					<li class="collection-item">
						<span class="title">定期対象期間開始日</span>
						<blockquote><?php echo $val->date_from;?></blockquote>
					</li>
					<li class="collection-item">
						<span class="title">定期対象期間終了日</span>
						<blockquote><?php echo $val->date_to;?></blockquote>
					</li>
				</ul>
			</div> <!-- modal-content -->
			<div class="modal-footer">
				<?php echo Html::anchor('/payment/reserveremove/'. $val->id. '/?'. http_build_query($arr_params), '<i class="material-icons">delete</i>削除', array('class' => 'modal-action waves-effect waves-light to_remove_anchor btn pink darken-3'));?>
				<?php echo Html::anchor('/payment/reserveedit/'. $val->id. '/?'. http_build_query($arr_params), '<i class="material-icons">update</i>更新', array('class' => 'modal-action waves-effect waves-light to_edit_anchor btn pink darken-3', 'style' => 'margin-right: 3px;'));?>
			</div>
		</div>
		<?php endforeach;?>
	<?php endif;?>
	</div>
</div>
<?php endif;?>
</body>
</html>
