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

	$('#form_agree').prop('checked', false);

	$('#form_agree').on('change', function() {
		if ($(this).prop('checked') == true) {
			$('#form_submit').attr('disabled', false);
		} else {
			$('#form_submit').attr('disabled', 'disabled');
		}
	});

	$('#form_submit').on('click', function() {
		if ($(this).attr('disabled') == 'disabled') {
			if ($('#form_agree').prop('checked') == false) {
				alert('利用規約と個人情報の取り扱いに同意する場合はチェックを入れてください。');
			}
			return false;
		}
		var user_name = sanitaize($('#form_user_name').val());
		var email = sanitaize($('#form_email').val());
		var password = $('#form_password').val().replace(/./g, '*');
		if (user_name.length < 1 || user_name.length > 30 ) {
			alert('お名前欄をご確認ください。');
			$('#form_user_name').focus();
			return false;
		}
		if ( ! email.match(/^.+@.+$/g)) {
			alert('メールアドレス欄をご確認ください。');
			$('#form_email').focus();
			return false;
		}
		if (password.length < 4 || password.length > 16) {
			alert('パスワード欄をご確認ください。');
			$('#form_password').focus();
			return false;
		}
		$('#form_regist').submit();
		return false;
	});
});
</script>
<style>
</style>

<div class="container">

	<br />

	<h5 class="header">ユーザ登録（<?php echo $oauth_type;?>ログイン）</h5>

	<br />

	<div>ペイジャーナルにログインするための設定をお願いします。<br />登録完了後はメールアドレスとパスワードでログインすることも可能になります。</div>

	<br />

	<div class="row">
		<?php echo Form::open(array('data-ajax' => 'false', 'action' => \Config::get('host.base_url_https'). '/user/regist/execute', 'method' => 'post', 'id' => 'form_regist', 'class' => 'col s12'));?>

		<div class="row">
			<div class="input-field col s12">
				<?php if (isset($arr_validation_error['user_name'])):?>
					<?php echo Form::input('user_name', isset($user_name)? $user_name: null, array('type' => 'text', 'class' => 'user_name validate invalid', 'placeholder' => '', 'maxlength' => '30'));?>
					<?php echo Form::label('■お名前', 'user_name', array('data-error' => $arr_validation_error['user_name'], 'class' => 'active'));?>
				<?php else:?>
					<?php echo Form::input('user_name', isset($user_name)? $user_name: null, array('type' => 'text', 'class' => 'user_name validate', 'placeholder' => '', 'maxlength' => '30'));?>
					<?php echo Form::label('■お名前', 'user_name', array('data-error' => 'wrong'));?>
				<?php endif;?>
				<span class="grey-text" style="font-size: x-small;">（ニックネーム可、３０文字まで）</span>
			</div>
		</div>

		<br />

		<div class="row">
			<div class="input-field col s12">
				<?php if (isset($arr_validation_error['email'])):?>
					<?php echo Form::input('email', isset($email)? $email: null, array('type' => 'text', 'class' => 'email validate invalid', 'placeholder' => '', 'maxlength' => '100'));?>
					<?php echo Form::label('■メールアドレス', 'email', array('data-error' => $arr_validation_error['email'], 'class' => 'active'));?>
				<?php else:?>
					<?php echo Form::input('email', isset($email)? $email: null, array('type' => 'text', 'class' => 'email validate', 'placeholder' => '', 'maxlength' => '100'));?>
					<?php echo Form::label('■メールアドレス', 'email', array('data-error' => 'wrong', 'class' => 'active'));?>
				<?php endif;?>
			</div>
		</div>

		<br />

		<div class="row">
			<div class="input-field col s12">
				<?php if (isset($arr_validation_error['password'])):?>
					<?php echo Form::input('password', isset($password)? $password: null, array('type' => 'password', 'class' => 'password validate invalid', 'placeholder' => '', 'maxlength' => '16'));?>
					<?php echo Form::label('■パスワード', 'password', array('data-error' => $arr_validation_error['password'], 'class' => 'active'));?>
				<?php else:?>
					<?php echo Form::input('password', isset($password)? $password: null, array('type' => 'password', 'class' => 'password validate', 'placeholder' => '', 'maxlength' => '16'));?>
					<?php echo Form::label('■パスワード', 'password', array('data-error' => 'wrong', 'class' => 'active'));?>
				<?php endif;?>
				<span class="grey-text" style="font-size: x-small;">（半角英数４～１６文字）</span>
			</div>
		</div>

		<div class="row">
			<div class="input-field col s12 center">
				<?php echo Form::checkbox('agree', true);?>
				<?php echo Form::label('以下の利用規約とプライバシーポリシーに同意する', 'agree');?>
			</div>
		</div>

		<div class="row">
			<div class="input-field col s12 center">
				<div><?php echo Html::anchor('info/terms', '利用規約', array('target' => 'new'));?></div>
				<div><?php echo Html::anchor('info/privacy', '個人情報の取り扱いについて', array('target' => 'new'));?></div>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s12">
				<?php echo Form::button('to_submit', '送信<i class="material-icons right">send</i>', array('class' => 'btn btn-submit waves-effect waves-light', 'id' => 'form_submit', 'disabled' => 'disabled'));?>
			</div>
		</div>

		<?php echo Form::hidden('oauth_type', $oauth_type);?>
		<?php echo Form::hidden('oauth_id', $oauth_id);?>

		<?php echo Form::close(). PHP_EOL; ?>
	</div>

	<br />

	<?php echo Html::anchor('/user/login/', '<i class="material-icons left">arrow_back</i>ログイン選択画面に戻る');?>

	<br />
	<br />
	<br />

<?php endif;?>
</div>
</body>
</html>