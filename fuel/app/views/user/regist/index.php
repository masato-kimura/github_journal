<?php if (isset($error_message)):?>
<div class="container">
	<br />
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else: ?>
<script type="text/javascript">
jQuery(function() {
	var sanitaize = function (str) {
		return str.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#39;');
	};
	$('#form_submit').on('click', function() {
		var user_name = sanitaize($('#user_name').val());
		var email = sanitaize($('#email').val());
		var password = $('#password').val().replace(/./g, '*');
		if (user_name.length < 1 || user_name.length > 30 ) {
			alert('お名前欄をご確認ください。');
			$('#user_name').focus();
			return false;
		}
		if ( ! email.match(/^.+@.+$/g)) {
			alert('メールアドレス欄をご確認ください。');
			$('#email').focus();
			return false;
		}
		if (password.length < 4 || password.length > 16) {
			alert('パスワード欄をご確認ください。');
			$('#password').focus();
			return false;
		}
		$('#modal_user_name').html(user_name);
		$('#modal_email').html(email);
		$('#modal_password').html(password);
		$('#modal_display').modal();
		return false;
	});
	$('#modal_to_submit').on('click', function() {
		$('#form_regist').submit();
	});
});
</script>
<style>
.form-group {
  margin-bottom: 35px;
}
.modal-list-value {
  background: #eee;
  padding: 9px 9px 9px 14px;
  margin-bottom: 20px;
}
</style>

<div class="container">
	<h5 style="margin: 20px 0px 0px 0px; padding: 0px;">家計簿アプリのペイジャーナル</h5>
	<h2 style="margin: 2px 0px 20px 0px; padding-top: 0px;">ユーザ登録</h2>

	<?php echo Form::open(array('data-ajax' => 'false', 'action' => \Config::get('host.base_url_https'). '/user/regist/execute', 'method' => 'post', 'id' => 'form_regist'));?>
	<div class="form-group">
		<label for="email">お名前（ニックネーム可、３０文字まで）</label>
		<?php echo Form::input('user_name', isset($user_name)? $user_name: null, array('type' => 'text', 'class' => 'user_name form-control', 'id' => 'user_name', 'placeholder' => 'ミックジャガー', 'maxlength' => '30'));?>
		<?php if (isset($arr_validation_error['user_name'])):?>
			<div class="alert alert-danger"><?php echo $arr_validation_error['user_name'];?></div>
		<?php endif;?>
	</div>

	<div class="form-group">
		<label for="email">メールアドレス</label>
		<?php echo Form::input('email', isset($email)? $email: null, array('type' => 'email', 'class' => 'email form-control', 'id' => 'email', 'placeholder' => 'mick@jager.come', 'maxlength' => '100'));?>
		<?php if (isset($arr_validation_error['email'])):?>
			<div class="alert alert-danger"><?php echo $arr_validation_error['email'];?></div>
		<?php endif;?>
	</div>

	<div class="form-group">
		<label for="password">パスワード（半角英数字の組み合わせで４文字以上１６文字まで）</label>
		<?php echo Form::input('password', '', array('type' => 'password', 'class' => 'password form-control', 'id' => 'password', 'placeholder' => 'パスワード', 'maxlength' => '16'));?>
		<?php if (isset($arr_validation_error['password'])):?>
			<div class="alert alert-danger"><?php echo $arr_validation_error['password'];?></div>
		<?php endif;?>
	</div>
<!--
	<div class="checkbox">
		<?php $checked = ( ! empty($is_auto_login)) ? 'checked' : null;?>
		<label>
			<?php echo Form::input('is_auto_login', true, array('type' => 'checkbox', 'checked' => $checked, 'id' => 'is_auto_login')). PHP_EOL;?>
			<span>次回からのログインを自動化</span>
		</label>
	</div>
-->
	<?php echo Html::anchor('#', '確認', array('class' => 'btn btn-primary btn-block btn-lg', 'id' => 'form_submit'));?>
	<?php echo Form::close(). PHP_EOL; ?>

<?php endif;?>
	<br /><br />

	<div class="modal" id="modal_display" tabindex="-1">
		<div class="modal-dialog">
			<!-- 3.モーダルのコンテンツ -->
			<div class="modal-content">
				<!-- 4.モーダルのヘッダ -->
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="modal-label">以下の内容で登録します。よろしいですか？</h4>
				</div>
				<!-- 5.モーダルのボディ -->
				<div class="modal-body">
					<div class="modal-list">
						<h5>・お名前</h5>
						<div class="modal-list-value" id="modal_user_name"></div>
					</div>
					<div class="modal-list">
						<h5>・メールアドレス</h5>
						<div class="modal-list-value" id="modal_email"></div>
					</div>
					<div class="modal-list">
						<h5>・パスワード</h5>
						<div class="modal-list-value" id="modal_password"></div>
					</div>
				</div>
				<!-- 6.モーダルのフッタ -->
				<div class="modal-footer">
				<?php echo Form::button('to_update', '入力画面に戻る', array('class' => 'btn btn-secondary', 'data-dismiss' => 'modal', 'style' => 'float: left; border: solid 1px #ccc;'));?>
				<?php echo Html::anchor('#', Form::button('to_submit', '登録する', array('class' => 'btn btn-primary')), array('class' => 'to_remove_anchor', 'id' => 'modal_to_submit'));?>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>