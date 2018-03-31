<?php if (isset($error_message)):?>
<div class="container">
	<br />
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else: ?>
<script type="text/javascript">
</script>
<style>

</style>

<div class="container">

	<br />

	<h5 class="header"><i class="material-icons prefix" style="display:inline-block; margin-right: 5px; vertical-align: middle; font-size: xx-large; color: #aaa;">autorenew</i>パスワード再発行</h5>

	<br />

	<div>ご指定のメールアドレスにパスワード再発行方法を記載したメールを送信いたします。<br />
	（なおFacebook、LINE、Google、Twitter、Yahoo!の認証機関を利用したログインのパスワード再発行は、<br />ログイン後の「ユーザ情報変更」から行ってください。）
	</div>

	<br />

	<div class="row">
		<?php echo Form::open(array('data-ajax' => false, 'action' => '/user/password/reissueexecute', 'method' => 'post', 'id' => 'passwordreissuerequestform', 'class' => 'col s12'));?>
		<div class="row">
			<div class="input-field col s12">
				<?php if (isset($arr_validation_error['email'])):?>
					<?php echo Form::input('email', isset($email)? $email: null, array('type' => 'email', 'class' => 'validate invalid', 'placeholder' => ''));?>
					<?php echo Form::label('メールアドレス', 'email', array('data-error' => $arr_validation_error['email'], 'class' => 'active'));?>
				<?php else:?>
					<?php echo Form::input('email', isset($email)? $email: null, array('type' => 'email', 'class' => 'validate'));?>
					<?php echo Form::label('メールアドレス', 'email', array('data-error' => 'wrong'));?>
				<?php endif;?>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s12">
				<?php echo Form::button('to_submit', '送信<i class="material-icons right">send</i>', array('type' => 'submit', 'class' => 'btn btn-submit waves-effect waves-light'));?>
			</div>
		</div>
		<?php echo Form::close();?>
	</div>
</div>
<?php endif;?>
</body>
</html>