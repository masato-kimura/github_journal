<?php if (isset($error_message)):?>
<div class="container">
	<br />
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else: ?>
<script type="text/javascript">
</script>
<style>
.modal-list-value {
  background: #eee;
  padding: 9px 9px 9px 14px;
  margin-bottom: 20px;
}
</style>

<div class="container">

	<br />

	<h5 class="header"><i class="material-icons prefix" style="display:inline-block; margin-right: 5px; vertical-align: middle; font-size: xx-large; color: #aaa;">autorenew</i>パスワード再発行</h5>

	<br />

	<div>ご登録のメールアドレスにパスワード再発行方法を記載したメールを送信いたします。<br />
	有効期限は送信後３０分間ですので時間内にご確認よろしくお願いいたします。</div>

	<div class="row">

		<?php echo Form::open(array('data-ajax' => false, 'action' => '/user/password/reissueexecutelogined', 'method' => 'post', 'id' => 'passwordreissuerequestform', 'class' => 'col s12'));?>

		<div class="row">
			<div class="input-field col s12">
				<p><?php echo $email;?></p>
				<?php echo Form::hidden('email', isset($email)? $email: '', array('type' => 'email', 'class' => 'form-control'));?>
				<?php if (isset($arr_validation_error['email'])):?>
					<div class="alert"><?php echo $arr_validation_error['email'];?></div>
				<?php endif;?>
			</div>
		</div>

		<div class="row">
			<div class="input-field col s12">
				<?php if (isset($arr_validation_error['email'])):?>
					<?php echo Form::button('to_submit', '送信<i class="material-icons right">send</i>', array('class' => 'btn btn-submit waves-effect waves-light', 'disabled' => 'disabled'));?>
				<?php else:?>
					<?php echo Form::button('to_submit', '送信<i class="material-icons right">send</i>', array('class' => 'btn btn-submit waves-effect waves-light'));?>
				<?php endif;?>
			</div>
		</div>
		<?php echo Form::close();?>


	</div>

</div>
<?php endif;?>
</body>
</html>