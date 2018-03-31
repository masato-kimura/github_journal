<?php if (isset($error_message)):?>
<div class="container">
	<br />
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else: ?>
<script type="text/javascript">
jQuery(function() {
	$(window).load(function() {
		$('input[type=password]').val('');
		//$('#form_password').focus();
	});
});
</script>
<style>
</style>

<div class="container">

	<br />

	<h5 class="header"><i class="material-icons prefix" style="display:inline-block; margin-right: 5px; vertical-align: middle; font-size: xx-large; color: #aaa;">autorenew</i>パスワード再発行</h5>

	<br />

	<div class="row">
		<?php echo Form::open(array('data-ajax' => false, 'action' => '/user/password/reissuedone', 'method' => 'post', 'id' => 'passwordreissuedoneform', 'class' => 'col s12'));?>
		<div class="row">
			<div class="input-field col s12">
				<?php if (isset($arr_validation_error['password'])):?>
					<?php echo Form::input('password', '', array('type' => 'password', 'class' => 'validate invalid'));?>
					<?php echo Form::input('password_dummy', null, array('type' => 'password', 'style' => "width:2px;height:2px;position:absolute;opacity:0"));?>
					<?php echo Form::label('新しいパスワードを入力してください', 'password', array('data-error' => $arr_validation_error['password']));?>
				<?php else: ?>
					<?php echo Form::input('password', '', array('type' => 'password', 'class' => 'validate'));?>
					<?php echo Form::input('password_dummy', null, array('type' => 'password', 'style' => "width:2px;height:2px;position:absolute;opacity:0"));?>
					<?php echo Form::label('新しいパスワードを入力してください', 'password', array('data-error' => 'wrong'));?>
				<?php endif; ?>
				<div class="grey-text">（半角英数4〜16文字）</div>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s12">
				<?php if (isset($arr_validation_error['passwordchk'])):?>
					<?php echo Form::input('passwordchk', '', array('type' => 'password', 'class' => 'validate invalid'));?>
					<?php echo Form::input('passwordchk_dummy', null, array('type' => 'password', 'style' => "width:2px;height:2px;position:absolute;opacity:0"));?>
					<?php echo Form::label('確認のためもう一度パスワードを入力してください', 'passwordchk', array('data-error' => $arr_validation_error['passwordchk'])); ?>
				<?php else: ?>
					<?php echo Form::input('passwordchk', '', array('type' => 'password', 'class' => 'validate'));?>
					<?php echo Form::input('passwordchk_dummy', null, array('type' => 'password', 'style' => "width:2px;height:2px;position:absolute;opacity:0"));?>
					<?php echo Form::label('確認のためもう一度パスワードを入力してください', 'passwordchk', array('data-error' => 'wrong')); ?>
				<?php endif; ?>
				<?php echo Form::hidden('reissue_hash', $reissue_hash);?>
				<?php echo Form::hidden('oauth_type', $oauth_type);?>
				<div class="grey-text">（半角英数4〜16文字）</div>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s12">
				<?php echo Form::button('to_submit', '送信', array('class' => 'btn btn-submit waves-effect waves-light', 'type' => 'submit'));?>
			</div>
		</div>
		<?php echo Form::close();?>
	</div>


</div>
<?php endif;?>
</body>
</html>