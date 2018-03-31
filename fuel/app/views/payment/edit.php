<?php if (isset($error_message)):?>
<div class="container">
	<div class="alert alert-danger"><?php echo $error_message;?></div>
	<div><?php echo Html::anchor('/payment/list/', '一覧へ戻る', array('class' => 'to_list btn btn-info btn-block btn-lg'));?></div>
</div>
<?php else:?>
<script type="text/javascript">
jQuery(function() {
	var FIX_OTHER = '999999999999999999';
	var is_fixies = [];
	<?php foreach($this->arr_is_fixies as $i => $val):?>
		is_fixies[<?php echo $val; ?>] = true;
	<?php endforeach;?>
	$('#form_fix_id').on('change', function() {
		$('.alert_fix_id').remove();
		if (($(this).val() != FIX_OTHER) && ($(this).val() != '0')) {
			var option_text = $('#form_fix_id option:selected').text();
			$('#form_name').val(option_text);
		} else {
			$('#form_name').val('');
		}

		if (is_fixies[$(this).val()]) {
			$('#form_is_fix').prop('checked', true);
		} else {
			$('#form_is_fix').prop('checked', false);
		}

		if ($(this).val() == FIX_OTHER) {
			$('.form-hidden').show('slow');
		} else {
			$('.form-hidden').hide('fast');
		}
	});

	$('#form_to_submit').on('click', function() {
		$(this).prop('disabled', true);
		$(this).parents('form').submit();
		return true;
	});

	$('form input, form select').on('change focus', function() {
		$(this).removeClass('invalid');
		$(this).nextAll('.error').remove();
		$(this).parent().nextAll('.error').remove();
	});

	$('#form_payment_reserve_status').on('change', function() {
		func_reserve_status();
	});

	$(window).load(function() {
		if ($('#form_fix_id').val() == FIX_OTHER) {
			$('.form-hidden').show('fast');
		} else {
			$('.form-hidden').hide('fast');
		}
		func_reserve_status();
	});

	var func_reserve_status = function() {
		var chk = $('#form_payment_reserve_status').prop('checked');
		if (chk)
		{
			$('#reserve_status_uncommited').removeClass('reserve_mark');
			$('#reserve_status_commited').addClass('reserve_mark_commited');
		}
		else
		{
			$('#reserve_status_commited').removeClass('reserve_mark_commited');
			$('#reserve_status_uncommited').addClass('reserve_mark');
		}
		return true;
	};

});
</script>
<style>
select {
  display: inline;
  font-size: 16px;
}
.reserve_mark_base {
  display: inline-block;
  padding: 2px 3px;
  font-size: normal;
  border: 1px solid #fff;
}
.reserve_mark_commited {
  display: inline-block;
  padding: 2px 3px;
  font-size: normal;
  color: #E37979;
  border: 1px solid #E37979;
}
.reserve_mark {
  display: inline-block;
  padding: 2px 3px;
  color: #fff;
  background: red;
  border: 1px solid red;
}

</style>

<div class="container">

	<div style="margin: 30px 0px;"><?php echo Html::anchor('/payment/list/?'. http_build_query($arr_params), '<i class="material-icons prefix left">arrow_back</i>一覧へ戻る', array('class' => 'to_back_btn_top'));?></div>

	<h5 class="header"><i class="material-icons prefix" style="color: #ccc;">update</i>支出データ更新</h5>

	<br />

	<div class="row">
		<?php echo Form::open(array('action' => '/payment/editdone/?'. http_build_query($arr_params), 'class' => 'col s12'));?>
			<?php if ( ! empty($arr_detail->payment_reserve_id)):?>
			<div class="row">
				<div class="col switch s12">
					<label>
					<span id="reserve_status_uncommited" class="reserve_mark_base reserve_mark">定期未確定</span>
					<?php echo Form::checkbox('payment_reserve_status', '1', \Input::post('payment_reserve_status', $arr_detail->payment_reserve_status), array('class' => 'validate invalid', 'placeholder' => ''));?>
					<span class="lever"></span>
					<span id="reserve_status_commited" class="reserve_mark_base reserve_mark_commited">定期確定済み</span>
					</label>
				</div>
			</div>

			<br />

			<?php endif;?>

			<div class="row">
				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['date'])):?>
						<?php echo Form::input('date', \Input::post('date', $arr_detail->date), array('class' => 'date datepicker validate invalid', 'placeholder' => '')); ?>
						<?php echo Form::label('日時<span class="form-required">* 必須</span>', 'date');?>
						<div class="form-error"><?php echo $arr_validation_error['date'];?></div>
					<?php else:?>
						<?php echo Form::input('date', \Input::post('date', $arr_detail->date), array('class' => 'datepicker validate', 'placeholder' => '')); ?>
						<?php echo Form::label('日時<span class="form-required">* 必須</span>', 'date');?>
					<?php endif;?>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<?php if (isset($arr_validation_error['fix_id'])):?>
						<?php echo Form::label('登録済みのカテゴリー<span class="form-required">* 必須</span><br />（カテゴリーリストの登録・編集は'. Html::anchor('/fix/', 'こちら').'）</span>', 'fix_id', array('style' => 'margin-bottom: 12px; display: inline-block;'));?>
						<?php echo Form::select('fix_id', \Input::post('fix_id', $arr_detail->fix_id), $arr_fix_list, array('class' => 'fix_id'));?>
						<div class="form-error" style="margin: 0px; border-top: 2px solid;"><?php echo $arr_validation_error['fix_id'];?></div>
					<?php else:?>
						<?php echo Form::label('登録済みのカテゴリー<span class="form-required">* 必須</span><br />（カテゴリーリストの登録・編集は'. Html::anchor('/fix/', 'こちら').'）</span>', 'fix_id', array('style' => 'margin-bottom: 12px; display: inline-block;'));?>
						<?php echo Form::select('fix_id', \Input::post('fix_id', $arr_detail->fix_id), $arr_fix_list, array('class' => 'fix_id'));?>
					<?php endif;?>
				</div>

				<div class="input-field col s12 form-hidden" style="margin-top: 30px;">
					<?php if (isset($arr_validation_error['name'])):?>
						<?php echo Form::input('name', \Input::post('name', $arr_detail->name), array('class' => 'name validate invalid', 'maxlength' => '50', 'placeholder' => ''));?>
						<?php echo Form::label('その他カテゴリー<span class="form-required">* 必須</span>', 'name'); ?>
						<div class="form-error"><?php echo $arr_validation_error['name'];?></div>
					<?php else:?>
						<?php echo Form::input('name', \Input::post('name', $arr_detail->name), array('class' => 'name validate', 'maxlength' => '50', 'placeholder' => ''));?>
						<?php echo Form::label('その他カテゴリー<span class="form-required">* 必須</span>', 'name'); ?>
					<?php endif;?>
					<div style="padding: 0px; margin-top: -10px; margin-left: 15px;">
						<?php echo Form::checkbox('is_fix', true, \Input::post('is_fix', $arr_detail->is_fix));?>
						<?php echo Form::label('固定費とする', 'is_fix', array('style' => 'font-size: x-small; color: #aaa;'));?>
					</div>
				</div>
			</div>

			<br />

			<div class="row">
				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['cost'])):?>
						<?php echo Form::input('cost', \Input::post('cost', $arr_detail->cost), array('type' => 'tel', 'class' => 'cost validate invalid', 'placeholder' => ''));?>
						<?php echo Form::label('支払い金額<span class="form-required">* 必須</span>', 'cost');?>
						<div class="form-error"><?php echo $arr_validation_error['cost'];?></div>
					<?php else:?>
						<?php echo Form::input('cost', \Input::post('cost', $arr_detail->cost), array('type' => 'tel', 'class' => 'cost validate', 'placeholder' => ''));?>
						<?php echo Form::label('支払い金額<span class="form-required">* 必須</span>', 'cost');?>
					<?php endif;?>
				</div>
			</div>

			<div class="row">
				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['detail'])):?>
						<?php echo Form::input('detail', \Input::post('detail', $arr_detail->detail), array('class' => 'detail validate invalid', 'placeholder' => 'カテゴリーが飲食費の場合　　例）昼食'));?>
						<?php echo Form::label('内訳', 'detail');?>
						<div class="form-error"><?php echo $arr_validation_error['detail'];?></div>
					<?php else:?>
						<?php echo Form::input('detail', \Input::post('detail', $arr_detail->detail), array('class' => 'detail validate', 'placeholder' => 'カテゴリーが飲食費の場合　　例）昼食'));?>
						<?php echo Form::label('内訳', 'detail');?>
					<?php endif;?>
				</div>
			</div>

			<div class="row">
				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['shop'])):?>
						<?php echo Form::input('shop', \Input::post('shop', $arr_detail->shop), array('class' => 'shop validate invalid', 'placeholder' => '店舗名など'));?>
						<?php echo Form::label('支払い先', 'shop');?>
						<div class="form-error"><?php echo $arr_validation_error['shop'];?></div>
					<?php else:?>
						<?php echo Form::input('shop', \Input::post('shop', $arr_detail->shop), array('class' => 'shop validate', 'placeholder' => '店舗名など'));?>
						<?php echo Form::label('支払い先', 'shop');?>
					<?php endif;?>
				</div>
			</div>

			<div class="row">
				<div class="col s12">
					<?php if (isset($arr_validation_error['paymethod_id'])):?>
						<?php echo Form::label('支払い方法', 'paymethod_id');?>
						<?php echo Form::select('paymethod_id', \Input::post('paymethod_id', $arr_detail->paymethod_id), $this->arr_paymethod, array('class' => 'paymethod_id invalid'));?>
						<div class="form-error" style="margin: 0px;"><?php echo $arr_validation_error['paymethod_id'];?></div>
					<?php else:?>
						<?php echo Form::label('支払い方法', 'paymethod_id');?>
						<?php echo Form::select('paymethod_id', \Input::post('paymethod_id', $arr_detail->paymethod_id), $this->arr_paymethod);?>
					<?php endif;?>
				</div>
			</div>

			<div class="row">
				<div class="input-field col s12">
					<?php echo Form::label('使用目的', '', array('style' => 'font-size: smaller;'));?>
					<div class="row" style="margin-top: 10px;">
						<div class="input-field col s12 l2">
							<?php echo Form::radio('use_type', '0', \Input::post('use_type', $arr_detail->use_type), array('id' => 'form_use_type_0', 'class' => 'with-gap'));?>
							<?php echo Form::label('未指定', 'use_type_0');?>
						</div>
						<div class="input-field col s12 l2">
							<?php echo Form::radio('use_type', '1', \Input::post('use_type', $arr_detail->use_type), array('id' => 'form_use_type_1', 'class' => 'with-gap'));?>
							<?php echo Form::label('消費', 'use_type_1');?>
						</div>
						<div class="input-field col s12 l2">
							<?php echo Form::radio('use_type', '2', \Input::post('use_type', $arr_detail->use_type), array('id' => 'form_use_type_2', 'class' => 'with-gap'));?>
							<?php echo Form::label('投資', 'use_type_2');?>
						</div>
						<div class="input-field col s12 l2">
							<?php echo Form::radio('use_type', '3', \Input::post('use_type', $arr_detail->use_type), array('id' => 'form_use_type_3', 'class' => 'with-gap'));?>
							<?php echo Form::label('浪費', 'use_type_3');?>
						</div>
					</div>
					<?php if (isset($arr_validation_error['use_type'])):?>
						<div class="form-error"><?php echo $arr_validation_error['use_type'];?></div>
					<?php endif;?>
				</div>
			</div>

			<div class="row">
				<div class="input-field col s12">
					<?php echo Form::label('経費割合', '', array('style' => 'font-size: smaller; margin-bottom: 10px;'));?>
					<div class="row" style="margin-top: 10px;">
						<div class="input-field col s12 l2">
							<?php echo Form::radio('work_side_per', '0', \Input::post('work_side_per', $arr_detail->work_side_per), array('id' => 'form_work_side_per_0', 'class' => 'with-gap'));?>
							<?php echo Form::label('0%', 'work_side_per_0');?>
						</div>
						<div class="input-field col s12 l2">
							<?php echo Form::radio('work_side_per', '25', \Input::post('work_side_per', $arr_detail->work_side_per), array('id' => 'form_work_side_per_25', 'class' => 'with-gap'));?>
							<?php echo Form::label('25%', 'work_side_per_25');?>
						</div>
						<div class="input-field col s12 l2">
							<?php echo Form::radio('work_side_per', '50', \Input::post('work_side_per', $arr_detail->work_side_per), array('id' => 'form_work_side_per_50', 'class' => 'with-gap'));?>
							<?php echo Form::label('50%', 'work_side_per_50');?>
						</div>
						<div class="input-field col s12 l2">
							<?php echo Form::radio('work_side_per', '75', \Input::post('work_side_per', $arr_detail->work_side_per), array('id' => 'form_work_side_per_75', 'class' => 'with-gap'));?>
							<?php echo Form::label('75%', 'work_side_per_75');?>
						</div>
						<div class="input-field col s12 l4">
							<?php echo Form::radio('work_side_per', '100', \Input::post('work_side_per', $arr_detail->work_side_per), array('id' => 'form_work_side_per_100', 'class' => 'with-gap'));?>
							<?php echo Form::label('100%', 'work_side_per_100');?>
						</div>
					</div>
					<?php if (isset($arr_validation_error['work_side_per'])):?>
						<div class="form-error"><?php echo $arr_validation_error['work_side_per'];?></div>
					<?php endif;?>
				</div>
			</div>

			<div class="row">
				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['remark'])):?>
						<?php echo Form::textarea('remark', \Input::post('remark', $arr_detail->remark), array('class' => 'materialize-textarea validate invalid', 'maxlength' => 1000, 'placeholder' => ''));?>
						<?php echo Form::label('備考', 'remark');?>
						<div class="form-error"><?php echo $arr_validation_error['remark'];?></div>
					<?php else:?>
						<?php echo Form::textarea('remark', \Input::post('remark', $arr_detail->remark), array('class' => 'materialize-textarea validate', 'maxlength' => 1000));?>
						<?php echo Form::label('備考', 'remark');?>
					<?php endif;?>
				</div>
			</div>

			<div class="row">
				<div class="input-field col s12">
					<?php echo Form::hidden('id', \Input::post('id', $arr_detail->id));?>
					<?php echo Form::button('to_submit', '<i class="material-icons right">send</i>更新する', array('type' => 'submit', 'class' => 'btn btn-submit waves-effect waves-light'));?>
				</div>
			</div>
		<?php echo Form::close();?>
	</div>

	<div style="margin: 30px 0px;"><?php echo Html::anchor('/payment/list/?'. http_build_query($arr_params), '<i class="material-icons prefix left">arrow_back</i>一覧へ戻る', array('class' => 'to_back_btn_top'));?></div>

	<br /><br />

</div>
<?php endif;?>
</body>
</html>