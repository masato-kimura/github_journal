<?php if (isset($error_message)):?>
<div class="container">
	<br />
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else:?>
<script type="text/javascript">
</script>
<style>
</style>

<div class="container">

	<br />

	<h5 class="header">ログインパスワード確認</h5>

	<br />

	<?php $checked = ( ! empty($is_auto_login)) ? 'checked' : null;?>

	<div class="row">
		<?php echo Form::open(array('data-ajax' => 'false', 'action' => \Config::get('host.base_url_https'). '/user/login/checkexecute', 'method' => 'post', 'id' => 'form_login_email', 'class' => 'col s12'));?>
		<div class="row">
			<div class="input-field col s12">
				<input disabled value="<?php echo $oauth_type_disp;?>" type="text" class="validate" id="login_type">
				<label for="login_type">■ログイン方法</label>
			</div>
		</div>

		<div class="row">
			<div class="input-field col s12">
				<input disabled value="<?php echo \Input::post('email', $email);?>" type="text" class="validate" id="login_email">
				<?php echo Form::hidden('email', \Input::post('email', $email), array('type' => 'email', 'class' => 'email', 'id' => 'email'));?>
				<label for="login_email" data-error="<?php echo isset($arr_validation_error['email'])? $arr_validation_error['email']: null;?>">■登録メールアドレス</label>
			</div>
		</div>

		<div class="row">
			<div class="input-field col s12">
				<?php if ( ! empty($arr_validation_error['password'])):?>
					<?php echo Form::input('password', null, array('type' => 'password', 'class' => 'password validate invalid', 'placeholder' => '', 'maxlength' => '16'));?>
					<?php echo Form::input('password_dummy', null, array('type' => 'password', 'class' => 'invalid', 'style' => 'width:2px; height:2px; position:absolute; opacity:0;', 'placeholder' => ''));?>
					<?php echo Form::label('■確認のためパスワードを入力してください', 'password', array('data-error' => $arr_validation_error['password'], 'class' => 'active'));?>
				<?php else:?>
					<?php echo Form::input('password', null, array('type' => 'password', 'class' => 'password validate', 'maxlength' => '16'));?>
					<?php echo Form::input('password_dummy', null, array('type' => 'password', 'style' => 'width:2px; height:2px; position:absolute; opacity:0;'));?>
					<?php echo Form::label('■確認のためパスワードを入力してください', 'password', array('data-error' => 'wrong'));?>
				<?php endif; ?>
			</div>
		</div>

		<div class="row">
			<p class="center" style="margin: 0px 0px 10px 0px;">パスワードをお忘れの方は<span style="text-decoration: underline;"><?php echo Html::anchor('/user/password/reissuerequestlogined/', 'こちら');?></span></p>
			<div class="input-field col s12">
				<button type="submit" class="btn btn-submit large waves-effect waves-light">送信<i class="material-icons right">send</i></button>
			</div>
		</div>

		<?php echo Form::hidden('redirect', 'user/edit');?>

		<?php echo Form::close(). PHP_EOL; ?>
	</div>

<?php endif;?>
	<br /><br />
</div>
</body>
</html>