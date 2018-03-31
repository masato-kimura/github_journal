<?php if (isset($error_message)):?>
<div class="container" style="margin: 18px auto;">
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else:?>
<script type="text/javascript">
jQuery(function() {
	var sort_switch_status = false;

	// モーダル初期設定
	$('.modal').modal();

	$('.to_sort_anchor, .to_default_anchor').css('display', 'none');

	// remove
	$('.to_remove_anchor').on('click', function() {
		$(this).attr('disabled', 'disabled');
		if (confirm('データを削除しますが、よろしいですか？')) {
			return true;
		}
		$(this).attr('disabled', false);
		return false;
	});

	// 一覧クリック時
	$('.fix_name').on('click', function() {
		var id = $(this).attr('id').match(/[\d]+$/);
		$('#modal_display_' + id).modal('open');
		return false;
	});

	// 新規、更新ボタン
	$('.to_add_submit,.to_edit_submit').on('click', function() {
		$(this).attr('disabled', 'disabled');
		$(this).parents('form').submit();
	});

	$('.modal-close').on('click', function() {
		$(this).modal('close');
		return false;
	});

	// sort_switch
	$('.sort_switch').on('change', function() {
		sort_switch_status = $(this).prop('checked');
		if (sort_switch_status == false) {
			$('.sort_switch').prop('checked', false);
			$('#journal_list_table tbody').sortable({
				disabled: true
			});
		} else {
			$('.sort_switch').prop('checked', true);
			$('#journal_list_table tbody').sortable({
				disabled: false
			});
		}
	});

	// sort
	$('#journal_list_table tbody').sortable({
		disabled: true,
		start: function() {
			//$('.fix_name').off('click touchend');
		},
		update: function(){
			$('.to_sort_anchor, .to_default_anchor').fadeIn('slow');
			$('.fix_name').on('click', function() {
				var id = $(this).attr('id').match(/[\d]+$/);
				$('#modal_display_' + id).modal();
			});
		},
		axis: "y"
	});

	// sort実行送信
	$('.to_sort_anchor').on('click', function() {
		$(this).parents('form').submit();
	});
	// 元に戻す
	$('.to_default_anchor').on('click', function() {
		var tr = [];
		var sort = 0;
		var html = "";
		$('#journal_list_table tbody tr').each(function() {
			sort = $(this).children('td.journal_list_no').html().match(/^[\d]+/)[0];
			tr[sort] = this;
		});
		$('#journal_list_table tbody').empty();
		tr.forEach(function(val, index, ar) {
			$('#journal_list_table tbody').append(val);
		});
		$('.to_sort_anchor, .to_default_anchor').fadeOut('fast');
		$('.fix_name').on('click', function() {
			var id = $(this).attr('id').match(/[\d]+$/);
			$('#modal_display_' + id).modal('open');
		});
		return false;
	});
	// 全て読み込み後
	$(window).load(function() {
		var error_from = $('#error_from').val();
		if (error_from === 'add') {
			$('#modal_display').modal('open');
		}
		if (error_from.match(/^edit_/)) {
			var match = error_from.match(/[0-9]+$/);
			$('#modal_display_' + match).modal('open');
		}
		var table_height = $('#journal_list_table').height();
		var table_th_height = $('#journal_list_table thead').eq(0).height();
		$('#journal_left_table').height(table_height);
		$('#journal_left_table thead').height(table_th_height);
	});
});
</script>
<style>
.table_wrap {
    box-sizing: border-box;
    border-right: 0px;
    overflow-x: scroll;
    -webkit-overflow-scrolling: touch;
}
</style>

<?php echo Form::hidden('error_from', isset($error_from)? $error_from: '', array('id' => 'error_from'));?>

<div class="container">

	<br />

	<h6 class="header"><i class="material-icons prefix" style="display:inline-block; margin-right: 5px; vertical-align: middle; font-size: xx-large; color: #aaa;">class</i>登録済みカテゴリー</h6>

	<div class="row" style="margin-top: 20px;">
		<div class="col s12">
			カテゴリーを更新および削除する場合は名前をクリックまたはタップしてください。
		</div>
	</div>

	<div class="row">
		<div class="switch col s12 center">
			<div>並べ替えモード</div>
			<label>
				OFF
				<input data-toggle="toggle" data-onstyle="" data-size="small" type="checkbox" class="sort_switch" id="sort_switch_1">
				<span class="lever"></span>
				ON
			</label>
		</div>
	</div>

	<div class="row">
		<?php echo Form::open(array('action' => '/fix/sort/', 'id' => 'form_sort', 'class' => 'col s12', 'style' => 'text-align: center;'));?>
			<?php if ( ! empty($list) and count($list) > 1):?>
			<p class="center">
				<?php echo Html::anchor('#', '並び替えを反映', array('class' => 'btn btn-success btn-lg to_sort_anchor'));?>
				<?php echo Html::anchor('#', '<i class="material-icons prefix">settings_backup_restore</i>元に戻す', array('class' => 'btn btn-default btn-lg to_default_anchor'));?>
			</p>
			<?php endif;?>

			<div class="table_wrap">

				<table id="journal_list_table" class="bordered striped highlight centered journal_list_table" style="width:85%; border: 0px; margin: auto;" >
					<thead style="border-bottom: 0px;">
						<tr>
							<th scope="row">No</th>
							<th scope="row" style="min-width: 100px;">名前</th>
							<th scope="row">種別</th>
							<th scope="row">リスト<br />表示</th>
							<th scope="row">集計<br />対象</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($list as $i => $val):?>
						<tr id="to_edit_<?php echo $val->id;?>" class="to_edit_anchor_tr">
							<td class="journal_list_no to_edit_anchor"><?php echo ++$i;?></td>
							<td style="word-wrap: break-word;" class="to_edit_anchor">
								<span style="text-decoration: underline;" class="fix_name" id="fix_name_<?php echo $val->id;?>"><?php echo $val->name;?></span>
								<?php echo Form::hidden('sorted[]', $val->id, array('id' => 'sort_'. $val->id, 'name' => 'sort[]'));?>
							</td>
							<td>
								<?php echo ($val->is_fix)? '固定費': '変動費';?>
							</td>
							<td>
								<?php echo ($val->is_disp)? '○': '-';?>
							</td>
							<td>
								<?php echo ($val->to_aggre)? '○': '-';?>
							</td>
						</tr>
					<?php endforeach;?>
					</tbody>
				</table>

			</div>

			<?php if ( ! empty($list) and count($list) > 1):?>
			<p class="center">
				<?php echo Html::anchor('#', '並び替えを反映', array('class' => 'btn btn-success btn-lg to_sort_anchor'));?>
				<?php echo Html::anchor('/fix/', '<i class="material-icons prefix">settings_backup_restore</i>元に戻す', array('class' => 'btn btn-default btn-lg to_default_anchor'));?>
			</p>
			<?php endif;?>

		<?php echo Form::close();?>
	</div>

	<br />

	<div class="row">
		<?php echo Html::anchor('#modal_display', '<i class="material-icons prefix">mode_edit</i>カテゴリーを新規登録', array('class' => 'btn waves-effect waves-light', 'style' => 'width: 100%; height: 42px;'));?>
		<br />
		<br />
		<br />
		<?php if (\Input::param('link_from') == "reservelist"):?>
			<?php echo html::anchor('/payment/reservelist/', '<i class="material-icons left">arrow_back</i>定期支出一覧へ');?>
		<?php else:?>
			<?php echo html::anchor('/payment/list/', '<i class="material-icons left">arrow_back</i>支出データ一覧へ');?>
		<?php endif;?>
	</div>


	<br /><br />


	<!-- モーダルの配置 -->
	<!-- 新規登録 -->
	<div class="modal modal-fixed-footer container" id="modal_display">

		<?php echo Form::open(array('action' => '/fix/add', 'id' => 'edit_form'));?>

		<div class="modal-content" style="margin-top: -15px;">
			<div class="row" style="vertical-align: top;">
				<h6 class="left" style="display: inline-block;"><i class="material-icons prefix">mode_edit</i>カテゴリーを登録</h6>
				<div class="right">
					<?php echo Form::button('to_update', '<i class="material-icons center">close</i>', array('class' => 'modal-close btn-flat waves-effect waves-light'));?>
				</div>
			</div>

			<div class="row">
				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['name'])):?>
						<?php echo Form::input('name', '', array('class' => 'name validate invalid', 'maxlength' => '50', 'placeholder' => ''));?>
						<?php echo Form::label('名前', 'name', array('class' => 'active'));?>
						<div class="form-error"><?php echo $arr_validation_error['name'];?></div>
					<?php else:?>
						<?php echo Form::input('name', '', array('class' => 'name validate', 'maxlength' => '50', 'placeholder' => ''));?>
						<?php echo Form::label('名前', 'name');?>
					<?php endif;?>
				</div>

				<br />
				<br />

				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['remark'])):?>
						<?php echo Form::textarea('remark', '', array('class' => 'remark validate invalid materialize-textarea', 'maxlength' => '1000', 'placeholder' => ''));?>
						<?php echo Form::label('備考', 'remark');?>
						<div class="form-error"><?php echo $arr_validation_error['remark'];?></div>
					<?php else:?>
						<?php echo Form::textarea('remark', '', array('class' => 'remark validate materialize-textarea', 'maxlength' => '1000', 'placeholder' => ''));?>
						<?php echo Form::label('備考', 'remark');?>
					<?php endif;?>
				</div>

				<div class="input-field col s12" style="margin-top: -10px;">
					<?php echo Form::checkbox('is_fix', true, true, array('class' => 'filled-in'));?>
					<?php echo Form::label('固定費とする場合チェック', 'is_fix');?>
				</div>
				<div class="input-field col s12">
					<?php echo Form::checkbox('is_disp', true, true, array('class' => 'filled-in'));?>
					<?php echo Form::label('リストに表示する場合はチェック', 'is_disp');?>
				</div>
				<div class="input-field col s12">
					<?php echo Form::checkbox('to_aggre', true, true, array('class' => 'filled-in'));?>
					<?php echo Form::label('集計対象とする場合はチェック', 'to_aggre');?>
				</div>
			</div>
		</div>

		<div class="modal-footer">
			<?php echo Form::button('to_submit', '<i class="material-icons right">send</i>登録', array('type' => 'submit', 'class' => 'btn to_add_submit'));?>
		</div>

		<?php echo Form::close();?>

	</div>


	<!-- モーダルの配置 -->
	<!-- 更新 -->
	<?php if (isset($list)):?>
		<?php foreach ($list as $i => $val):?>
		<div class="modal modal-fixed-footer container" id="modal_display_<?php echo $val->id;?>">

			<?php echo Form::open(array('action' => '/fix/edit', 'id' => 'edit_form_'. $val->id));?>

			<div class="modal-content">
				<div class="row">
					<h6 class="left" style="display: inline-block;"><i class="material-icons prefix">update</i>カテゴリーを編集</h6>
					<div class="right">
						<?php echo Form::button('to_update_'. $val->id, '<i class="material-icons center">close</i>', array('class' => 'modal-close btn-flat waves-effect waves-light'));?>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s12">
							<?php if (isset($arr_validation_error['name_'. $val->id])):?>
								<?php echo Form::input('name', $val->name, array('id' => 'form_name_'. $val->id, 'class' => 'name validate invalid', 'maxlength' => '50', 'placeholder' => ''));?>
								<?php echo Form::label('名前', 'name_'. $val->id, array('class' => 'active'));?>
								<div class="form-error"><?php echo $arr_validation_error['name_'. $val->id];?></div>
							<?php else:?>
								<?php echo Form::input('name', $val->name, array('id' => 'form_name_'. $val->id, 'class' => 'name validate', 'maxlength' => '50', 'placeholder' => ''));?>
								<?php echo Form::label('名前', 'name_'. $val->id, array('class' => 'active'));?>
							<?php endif;?>
					</div>

					<br />
					<br />

					<div class="input-field col s12">
						<?php if (isset($arr_validation_error['remark_'. $val->id])):?>
							<?php echo Form::textarea('remark', $val->remark, array('id' => 'form_remark_'. $val->id, 'class' => 'remark validate invalid materialize-textarea', 'maxlength' => '1000', 'placeholder' => ''));?>
							<?php echo Form::label('備考', 'remark', array('class' => 'active'));?>
							<div class="form-error"><?php echo $arr_validation_error['remark'];?></div>
						<?php else:?>
							<?php echo Form::textarea('remark', $val->remark, array('id' => 'form_remark_'. $val->id, 'class' => 'remark validate materialize-textarea', 'maxlength' => '1000', 'placeholder' => ''));?>
							<?php echo Form::label('備考', 'remark', array('class' => 'active'));?>
						<?php endif;?>
					</div>


					<div class="input-field col s12" style="margin-top: -10px;">
						<?php echo Form::checkbox('is_fix', true, $val->is_fix, array('id' => 'form_is_fix_'. $val->id, 'class' => 'filled-in'));?>
						<?php echo Form::label('固定費とする場合チェック', 'is_fix_'. $val->id);?>
					</div>
					<div class="input-field col s12">
						<?php echo Form::checkbox('is_disp', true, $val->is_disp, array('id' => 'form_is_disp_'. $val->id, 'class' => 'filled-in'));?>
						<?php echo Form::label('リストに表示する場合はチェック', 'is_disp_'. $val->id);?>
					</div>
					<div class="input-field col s12">
						<?php echo Form::checkbox('to_aggre', true, $val->to_aggre, array('id' => 'form_to_aggre_'. $val->id, 'class' => 'filled-in'));?>
						<?php echo Form::label('集計対象とする場合はチェック', 'to_aggre_'. $val->id);?>
					</div>
				</div>
			</div>

			<div class="modal-footer">
				<?php echo Form::hidden('id', $val->id);?>
				<?php echo Form::hidden('sort', $val->sort);?>
				<?php echo Html::anchor('/fix/remove/'. $val->id. '/', '削除', array('class' => 'btn to_remove_anchor'));?>
				<?php echo Html::anchor('#', '更新', array('type' => 'btn', 'class' => 'btn to_edit_submit', 'style' => 'margin-right: 3px;'));?>
			</div>

			<?php echo Form::close();?>

			</div>
		<?php endforeach;?>
	<?php endif;?>
<?php endif;?>
</div>
</body>
</html>
