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

	$('.modal').modal();

	$('#form_edit .btn-submit').on('click', function() {
		$('.modal-content .user_name_disabled').css('display', 'block');
		$('.modal-content .email_disabled').css('display', 'block');
		$('.modal-content .password_disabled').css('display', 'block');

		var user_name = sanitaize($('#form_user_name').val());
		var email     = sanitaize($('#form_email').val());
		var password  = sanitaize($('#form_password').val().replace(/./g, '*'));

		if (user_name.length > 0 && (user_name.length < 1 || user_name.length > 30 )) {
			alert('お名前欄をご確認ください。');
			$('#form_user_name').focus();
			return false;
		}
		if (email.length > 0 && ! email.match(/^.+@.+$/g)) {
			alert('メールアドレス欄をご確認ください。');
			$('#form_email').focus();
			return false;
		}
		if (password.length > 0 && (password.length < 4 || password.length > 16)) {
			alert('パスワード欄をご確認ください。');
			$('#form_password').focus();
			return false;
		}
		if (user_name.length == 0) {
			user_name = '変更なし';
		}
		if (email.length == 0) {
			email = '変更なし';
		}
		if (password.length == 0) {
			password = '変更なし';
		}

		$('#modal_user_name').html(user_name);
		$('#modal_email').html(email);
		$('#modal_password').html(password);

		$('#modal_display').modal('open');
		return false;
	});

	$('#to_password_edit').on('click', function() {
		$('#form_password').attr('disabled', false);
		$('#form_password').focus();

	});

	$('#modal_to_submit').on('click', function() {
		$('#modal_to_submit button').attr('disabled', 'disabled');
		$('#form_edit').submit();
	});
});
</script>
<style>
</style>

<div class="container">

	<br />

	<h5 class="header"><i class="material-icons" style="display:inline-block; margin-right: 5px; vertical-align: middle; font-size: xx-large; color: #aaa;">edit</i>ユーザ情報変更</h5>

	<br />

	<?php if (isset($arr_validation_error['all'])):?>
		<div class="alert"><?php echo $arr_validation_error['all'];?></div>
	<?php endif;?>

	<div class="row">

		<?php echo Form::open(array('data-ajax' => 'false', 'action' => \Config::get('host.base_url_https'). '/user/edit/execute', 'method' => 'post', 'id' => 'form_edit', 'class' => 'col s12'));?>

			<div class="row">
				<div class="input-field col s12">
					<?php echo Form::input('oauth_type_disp', $oauth_type_disp, array('class' => 'validate', 'disabled' => 'disabled'));?>
					<?php echo Form::hidden('oauth_type', $oauth_type);?>
					<?php echo Form::label('■ログイン方法 (こちらは変更できません)', 'oauth_type_disp'); ?>
				</div>
			</div>

			<br />

			<div class="row">
				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['user_name'])):?>
						<?php echo Form::input('user_name', $user_name, array('type' => 'text', 'class' => 'validate invalid', 'placeholder' => '', 'maxlength' => '30'));?>
						<?php echo Form::input('user_name_dummy', null, array('type' => 'text', 'class' => 'invalid', 'style' => "width:2px;height:2px;position:absolute;opacity:0", 'placeholder' => ''));?>
						<?php echo Form::label('■お名前', 'user_name', array('data-error' => $arr_validation_error['user_name'], 'class' => 'active')); ?>
					<?php else:?>
						<?php echo Form::input('user_name', $user_name, array('type' => 'text', 'class' => 'validate', 'placeholder' => '', 'maxlength' => '30'));?>
						<?php echo Form::input('user_name_dummy', null, array('type' => 'text', 'style' => "width:2px;height:2px;position:absolute;opacity:0;", 'placeholder' => ''));?>
						<?php echo Form::label('■お名前', 'user_name', array('data-error' => 'wrong', 'class' => 'active')); ?>
					<?php endif;?>
					<?php if ($oauth_type !== 'email'):?>
						<div class="grey-text" style="font-size: x-small;">* 当アプリでのみ変更が反映されます。SNSでのユーザ名には影響しません。</div>
					<?php endif;?>
					<span class="grey-text" style="font-size: x-small;">（ニックネーム可、３０文字まで）</span>
				</div>
			</div>

			<br />

			<div class="row">
				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['email'])):?>
						<?php echo Form::input('email', $email, array('type' => 'email', 'class' => 'email validate invalid', 'placeholder' => '', 'maxlength' => '100'));?>
						<?php echo Form::input('email_dummy', null, array('type' => 'text', 'class' => 'invalid', 'style' => "width:2px;height:2px;position:absolute;opacity:0"));?>
						<?php echo Form::label('■メールアドレス', 'email', array('data-error' => $arr_validation_error['email']));?>
					<?php else:?>
						<?php echo Form::input('email', $email, array('type' => 'email', 'class' => 'email validate', 'placeholder' => '', 'maxlength' => '100'));?>
						<?php echo Form::input('email_dummy', null, array('type' => 'text', 'style' => "width:2px;height:2px;position:absolute;opacity:0;", 'placeholder' => ''));?>
						<?php echo Form::label('■メールアドレス', 'email', array('data-error' => 'wrong'));?>
					<?php endif;?>
				</div>
			</div>

			<br />

			<div class="row">
				<div class="input-field col s12">
					<?php if (isset($arr_validation_error['password'])):?>
						<?php echo Form::input('password', '', array('type' => 'password', 'class' => 'password validate invald', 'placeholder' => '', 'maxlength' => '16'));?>
						<?php echo Form::input('password_dummy', null, array('type' => 'password', 'class' => 'validate invalid', 'style' => "width:2px;height:2px;position:absolute;opacity:0", 'placeholder' => ''));?>
						<?php echo Form::label('■パスワード', 'password', array('data-error' => $arr_validation_error['password'], 'class' => 'active'));?>
					<?php else:?>
						<?php echo Form::input('password', $password_digits, array('type' => 'password', 'class' => 'password validate', 'placeholder' => '', 'maxlength' => '16', 'disabled'));?>
						<?php echo Form::input('password_dummy', null, array('type' => 'password', 'style' => "width:2px;height:2px;position:absolute;opacity:0", 'placeholder' => ''));?>
						<?php echo Form::label('■パスワード', 'password', array('data-error' => 'wrong'));?>
					<?php endif;?>
					<div style="margin-top: 2px;"><a href="#!" id="to_password_edit">[変更する]</a></div>
					<span class="grey-text" style="font-size: x-small;">（半角英数４～１６文字）</span>
				</div>
			</div>

			<br />

			<div class="row">
				<div class="input-field col s12">
					<?php echo Html::anchor('#!', '<i class="material-icons right">send</i>確認', array('type' => 'submit', 'class' => 'btn modal-trigger waves-effect waves-light btn-submit', 'style' => 'padding: 10px;'));?>
				</div>
			</div>

		<?php echo Form::close(). PHP_EOL; ?>

	</div>

	<br />

	<div><?php echo Html::anchor('/payment/list', '<i class="material-icons left">arrow_back</i>変更しないでホームへ戻る', array('class' => 'to_list'));?></div>

	<br />
	<br />

	<div class="modal modal-fixed-footer" id="modal_display">
		<div class="modal-content">

			<br />

			<h6 class="center">以下の内容で更新します。よろしいですか？</h6>

			<br />

			<div class="modal-list user_name_disabled">
				<h6>お名前</h6>
				<blockquote>
					<div id="modal_user_name"></div>
				</blockquote>
			</div>
			<div class="modal-list email_disabled">
				<h6>メールアドレス</h6>
				<blockquote>
					<div id="modal_email"></div>
				</blockquote>
			</div>
			<div class="modal-list password_disabled">
				<h6>パスワード</h6>
				<blockquote>
					<div id="modal_password"></div>
				</blockquote>
			</div>
		</div>

		<div class="modal-footer">
				<?php echo Html::anchor('#!', '<i class="material-icons center">close</i>', array('class' => 'modal-action modal-close waves-effect waves-green btn-flat left'));?>
				<?php echo Html::anchor('#!', '<i class="material-icons right">send</i>更新する', array('class' => 'btn modal-action waves-effect waves-green right', 'id' => 'modal_to_submit'));?>
		</div>
	</div>
</div>

<?php endif;?>

</body>
</html>