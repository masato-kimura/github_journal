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
	<?php
	foreach ($arr_is_fixies as $val)
	{
		 echo "is_fixies[". $val. "] = true;". PHP_EOL;
	}
	?>
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
			$('.form-hidden').show('fast');
		} else {
			$('.form-hidden').hide('fast');
		}
	});

	$('form input, form select').on('change focus', function() {
		$(this).removeClass('invalid');
		$(this).nextAll('.error').remove();
		$(this).parent().nextAll('.error').remove();
	});

	$(window).load(function() {
		if ($('#form_fix_id').val() == FIX_OTHER) {
			$('.form-hidden').show('fast');
		} else {
			$('.form-hidden').hide('fast');
		}
	});

});
</script>
<style type="text/css">
select {
  display: inline;
  font-size: 16px;
}
</style>

<div class="container">

	<div style="margin: 30px 0px;"><?php echo Html::anchor('/payment/list/?'. http_build_query($arr_params), '<i class="material-icons prefix left">arrow_back</i>一覧へ戻る', array('class' => 'to_back_btn_top'));?></div>

	<h5 class="header"><i class="material-icons prefix" style="color: #ccc;">mode_edit</i>支出データ入力</h5>

	<br />

	<div class="row">
		<?php echo Form::open(array('action' => '/payment/adddone/', 'class' => 'col s12'));?>
			<div class="row">
				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['date'])):?>
						<?php echo Form::input('date', \Input::param('date', \Date::forge(\Date::forge()->get_timestamp())->format('%Y-%m-%d')), array('class' => 'datepicker invalid', 'placeholder' => '')); ?>
						<?php echo Form::label('年月日<span class="form-required">* 必須</span>', 'date');?>
						<div class="form-error"><?php echo $arr_validation_error['date'];?></div>
					<?php else:?>
						<?php echo Form::input('date', \Input::param('date', \Date::forge(\Date::forge()->get_timestamp())->format('%Y-%m-%d')), array('class' => 'datepicker', 'placeholder' => '')); ?>
						<?php echo Form::label('年月日<span class="form-required">* 必須</span>', 'date');?>
					<?php endif;?>
				</div>
			</div>

			<div class="row">
				<div class="col s12">
					<?php if (isset($arr_validation_error['fix_id'])):?>
						<?php echo Form::label('登録済みのカテゴリー<span class="form-required">* 必須</span><br />（カテゴリーリストの登録・編集は'. Html::anchor('/fix/', 'こちら').'）', 'fix_id', array('style' => 'margin-bottom: 12px; display: inline-block;'));?>
						<?php echo Form::select('fix_id', \Input::post('fix_id'), $this->arr_fix_list, array('class' => 'fix_id'));?>
						<div class="form-error" style="margin: 0px; border-top: 2px solid;"><?php echo $arr_validation_error['fix_id'];?></div>
					<?php else:?>
						<?php echo Form::label('登録済みのカテゴリー<span class="form-required">* 必須</span><br />（カテゴリーリストの登録・編集は'. Html::anchor('/fix/', 'こちら').'）', 'fix_id', array('style' => 'margin-bottom: 12px; display: inline-block;'));?>
						<?php echo Form::select('fix_id', \Input::post('fix_id'), $this->arr_fix_list, array('class' => 'fix_id'));?>
					<?php endif;?>
				</div>

				<div class="input-field col s12 form-hidden" style="margin-top: 30px;">
					<?php if (isset($arr_validation_error['name'])):?>
						<?php echo Form::input('name', \Input::post('name'), array('class' => 'name validation invalid', 'maxlength' => '50', 'placeholder' => ''));?>
						<?php echo Form::label('その他のカテゴリー<span class="form-required">* 必須</span>', 'name'); ?>
						<div class="form-error"><?php echo $arr_validation_error['name'];?></div>
					<?php else:?>
						<?php echo Form::input('name', \Input::post('name'), array('class' => 'name', 'maxlength' => '50', 'placeholder' => ''));?>
						<?php echo Form::label('その他のカテゴリー<span class="form-required">* 必須</span>', 'name'); ?>
					<?php endif;?>
					<div style="padding: 0px; margin-top: -10px; margin-left: 15px;">
						<?php echo Form::checkbox('is_fix', true, \Input::post('is_fix'));?>
						<?php echo Form::label('固定費とする', 'is_fix', array('style' => 'font-size: x-small; color: #aaa;'));?>
					</div>
				</div>
			</div>

			<br />

			<div class="row">
				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['cost'])):?>
						<?php echo Form::input('cost', \Input::post('cost'), array('type' => 'tel', 'class' => 'cost validate invalid', 'maxlength' => 11, 'placeholder' => ''));?>
						<?php echo Form::label('支払い金額<span class="form-required">* 必須</span>', 'cost');?>
						<div class="form-error"><?php echo $arr_validation_error['cost'];?></div>
					<?php else:?>
						<?php echo Form::input('cost', \Input::post('cost'), array('type' => 'tel', 'class' => 'validate', 'maxlength' => 11, 'placeholder' => ''));?>
						<?php echo Form::label('支払い金額<span class="form-required">* 必須</span>', 'cost', array('class' => 'cost'));?>
					<?php endif;?>
				</div>
			</div>

			<div class="row">
				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['detail'])):?>
						<?php echo Form::input('detail', \Input::post('detail'), array('class' => 'detail validate invalid', 'maxlength' => '50', 'placeholder' => 'カテゴリーが飲食費の場合　　例）昼食'));?>
						<?php echo Form::label('内訳', 'detail');?>
						<div class="form-error"><?php echo $arr_validation_error['detail'];?></div>
					<?php else:?>
						<?php echo Form::input('detail', \Input::post('detail'), array('class' => 'detail', 'maxlength' => '50', 'placeholder' => 'カテゴリーが飲食費の場合　　例）昼食'));?>
						<?php echo Form::label('内訳', 'detail');?>
					<?php endif;?>
				</div>
			</div>

			<div class="row">
				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['shop'])):?>
						<?php echo Form::input('shop', \Input::post('shop'), array('class' => 'shop validate invalid', 'maxlength' => '50', 'placeholder' => '店舗名など'));?>
						<?php echo Form::label('支払い先', 'shop');?>
						<div class="form-error"><?php echo $arr_validation_error['shop'];?></div>
					<?php else:?>
						<?php echo Form::input('shop', \Input::post('shop'), array('class' => 'shop', 'maxlength' => '50', 'placeholder' => '店舗名など'));?>
						<?php echo Form::label('支払い先', 'shop');?>
					<?php endif;?>
				</div>
			</div>

			<div class="row">
				<div class="col s12">
					<?php if (isset($arr_validation_error['paymethod_id'])):?>
						<?php echo Form::label('支払い方法', 'paymethod_id');?>
						<?php echo Form::select('paymethod_id', \Input::post('paymethod_id'), $this->arr_paymethod, array('class' => 'paymethod_id invalid'));?>
						<div class="form-error" style="margin: 0px;"><?php echo $arr_validation_error['paymethod_id'];?></div>
					<?php else:?>
						<?php echo Form::label('支払い方法', 'paymethod_id');?>
						<?php echo Form::select('paymethod_id', \Input::post('paymethod_id'), $this->arr_paymethod);?>
					<?php endif;?>
				</div>
			</div>

			<div class="row">
				<div class="input-field col s12">
					<?php echo Form::label('使用目的', '', array('style' => 'font-size: smaller;'));?>
					<div class="row" style="margin-top: 10px;">
						<div class="input-field col s12 l2">
							<?php echo Form::radio('use_type', '0', \Input::post('use_type', '0'), array('id' => 'form_use_type_0', 'class' => 'with-gap'));?>
							<?php echo Form::label('未指定', 'use_type_0');?>
						</div>
						<div class="input-field col s12 l2">
							<?php echo Form::radio('use_type', '1', \Input::post('use_type'), array('id' => 'form_use_type_1', 'class' => 'with-gap'));?>
							<?php echo Form::label('消費', 'use_type_1');?>
						</div>
						<div class="input-field col s12 l2">
							<?php echo Form::radio('use_type', '2', \Input::post('use_type'), array('id' => 'form_use_type_2', 'class' => 'with-gap'));?>
							<?php echo Form::label('投資', 'use_type_2');?>
						</div>
						<div class="input-field col s12 l2">
							<?php echo Form::radio('use_type', '3', \Input::post('use_type'), array('id' => 'form_use_type_3', 'class' => 'with-gap'));?>
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
							<?php echo Form::radio('work_side_per', '0', \Input::post('work_side_per', '0'), array('id' => 'form_work_side_per_0', 'class' => 'with-gap'));?>
							<?php echo Form::label('0%', 'work_side_per_0');?>
						</div>
						<div class="input-field col s12 l2">
							<?php echo Form::radio('work_side_per', '25', \Input::post('work_side_per'), array('id' => 'form_work_side_per_25', 'class' => 'with-gap'));?>
							<?php echo Form::label('25%', 'work_side_per_25');?>
						</div>
						<div class="input-field col s12 l2">
							<?php echo Form::radio('work_side_per', '50', \Input::post('work_side_per'), array('id' => 'form_work_side_per_50', 'class' => 'with-gap'));?>
							<?php echo Form::label('50%', 'work_side_per_50');?>
						</div>
						<div class="input-field col s12 l2">
							<?php echo Form::radio('work_side_per', '75', \Input::post('work_side_per'), array('id' => 'form_work_side_per_75', 'class' => 'with-gap'));?>
							<?php echo Form::label('75%', 'work_side_per_75');?>
						</div>
						<div class="input-field col s12 l4">
							<?php echo Form::radio('work_side_per', '100', \Input::post('work_side_per'), array('id' => 'form_work_side_per_100', 'class' => 'with-gap'));?>
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
						<?php echo Form::textarea('remark', \Input::post('remark'), array('class' => 'materialize-textarea validate invalid', 'maxlength' => 1000, 'placeholder' => ''));?>
						<?php echo Form::label('備考', 'remark');?>
						<div class="form-error"><?php echo $arr_validation_error['remark'];?></div>
					<?php else:?>
						<?php echo Form::textarea('remark', \Input::post('remark'), array('class' => 'materialize-textarea validate', 'maxlength' => 1000));?>
						<?php echo Form::label('備考', 'remark');?>
					<?php endif;?>
				</div>
			</div>

			<div class="row">
				<div class="input-field col s12">
					<?php echo Form::button('to_submit', '<i class="material-icons right">send</i>登録する', array('type' => 'submit', 'class' => 'btn btn-submit waves-effect waves-light'));?>
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
