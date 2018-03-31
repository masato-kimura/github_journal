<?php if (isset($error_message)):?>
<div class="container">
	<br />
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else:?>
<script type="text/javascript">
jQuery(function() {
	$('.oauth_login_btn').on('click', function() {
		var id = $(this).attr('id');
		var query = "";
		if ($('#form_auto_login_oauth').prop('checked')) {
			query = "?auto_login=1"
		}
		var host = document.URL.replace(/user\/login.*/i, '');
		switch (id) {
			case 'facebook_login_btn':
				location.href = host + "user/login/facebook/" + query;
				break;
			case 'line_login_btn':
				location.href = host + "user/login/line/" + query;
				break;
			case 'twitter_login_btn':
				location.href = host + "user/login/twitter/" + query;
				break;
			case 'google_login_btn':
				location.href = host + "user/login/google/" + query;
				break;
			case 'yahoo_login_btn':
				location.href = host + "user/login/yahoo/" + query;
				break;
			default:
				return false;
		}
		return false;
	});
	$(window).load(function() {
		if (typeof($('.email_error').html()) != 'undefined') {
			var speed = 500;
			var target = $('#form_login_email');
			var position = target.offset().top;
			$("html, body").animate({scrollTop:position}, speed, "swing");
			$('#email').focus();
			return false;
		}
	});
});
</script>
<style>
.collection, input, button {
  margin-left: 11px;
}
.collection .collection-item {
  font-size: medium;
  padding: 15px 20px;
  line-height: 30px;
}
.collection .collection-item.facebook_login_btn {
  background: #4267b2;
  color: #fff;
}
.collection .collection-item.line_login_btn {
  background: #00c402;
  color: #fff;
}
.collection .collection-item.google_login_btn {
  background: #db4437;
  color: #fff;
}
.collection .collection-item.twitter_login_btn {
  background: #1da1f2;
  color: #fff;
}
.collection .collection-item.yahoo_login_btn {
  background: #ffffee;
  color: red;
}
button[type=submit] {
  height: 58px;
  width: 96%;
}


</style>

<div class="container">

	<br />

	<h5 class="header">
		<i class="material-icons prefix" style="display:inline-block; margin-right: 5px; vertical-align: middle; font-size: xx-large; color: #aaa;">account_circle</i>ログイン
	</h5>

	<br />

	<?php $checked = ( ! empty($is_auto_login)) ? 'checked' : 'checked';?>

	<h6>■次の認証機関のアカウントをお持ちの方はこちらから</h6>
	<div style="font-size: small; margin-bottom: 10px;">当アプリは本人以外が閲覧できるSNSへの自動投稿はいたしません。</div>

	<div class="row">
		<div class="input-field col s12">
			<?php echo Form::input('auto_login', true, array('type' => 'checkbox', 'checked' => $checked, 'id' => 'form_auto_login_oauth', 'class' => 'filled-in')). PHP_EOL;?>
			<label for="form_auto_login_oauth">次回からのログインを自動化します</label>
		</div>
	</div>

	<div class="row">
		<div class="col s12">
			<div class="collection">
				<?php echo Html::anchor('#!', '<i class="material-icons right">send</i>Facebook でログイン', array('class' => 'collection-item oauth_login_btn facebook_login_btn waves-effect waves-light', 'id' => 'facebook_login_btn','type' => 'button')); ?>
				<?php echo Html::anchor('#!', '<i class="material-icons right">send</i>Twitter でログイン',  array('class' => 'collection-item oauth_login_btn twitter_login_btn waves-effect waves-light', 'id' => 'twitter_login_btn', 'type' => 'button')); ?>
				<?php echo Html::anchor('#!', '<i class="material-icons right">send</i>LINE でログイン',     array('class' => 'collection-item oauth_login_btn line_login_btn waves-effect waves-light', 'id' => 'line_login_btn', 'type' => 'button')); ?>
				<?php echo Html::anchor('#!', '<i class="material-icons right">send</i>Yahoo!<span style="color: black;"> でログイン</span>',   array('class' => 'collection-item oauth_login_btn yahoo_login_btn waves-effect waves-light', 'id' => 'yahoo_login_btn', 'type' => 'button'));?>
				<?php echo Html::anchor('#!', '<i class="material-icons right">send</i>Google でログイン',   array('class' => 'collection-item oauth_login_btn google_login_btn waves-effect waves-light', 'id' => 'google_login_btn', 'type' => 'button')); ?>
			</div>
		</div>

	</div>

	<br />
	<br />

	<h6 class="main_title">■メールアドレスとパスワードでログイン</h6>
	<div style="font-size: small; margin-bottom: 25px;">未登録の方は<span class="underline blue-text"><?php echo Html::anchor('/user/regist/email/', 'こちら');?></span>からユーザ登録を行ってください。</div>

	<?php echo Form::open(array('data-ajax' => 'false', 'action' => '/user/login/email', 'method' => 'post', 'id' => 'form_login_email'));?>
		<div class="row" style="margin-left: 2px;">
			<div class="input-field col s12">
				<?php if ( ! empty($arr_validation_error['email'])):?>
					<?php echo Form::input('email', \Input::post('email'), array('type' => 'email', 'class' => 'email validate invalid', 'id' => 'email', 'maxlength' => '100'));?>
					<label for="email" class="active" data-error="<?php echo $arr_validation_error['email'];?>">メールアドレス</label>
					<?php echo Form::hidden('email_error', $arr_validation_error['email'], array('class' => 'email_error'));?>
				<?php else:?>
					<?php echo Form::input('email', \Input::post('email'), array('type' => 'email', 'class' => 'email validate', 'id' => 'email', 'maxlength' => '100'));?>
					<label for="email" data-error="wrong" data-success="">メールアドレス</label>
				<?php endif;?>
			</div>
		</div>

		<div class="row" style="margin-left: 2px;">
			<div class="input-field col s12">
				<?php if ( ! empty($arr_validation_error['password'])):?>
					<?php echo Form::input('password', \Input::post('password'), array('type' => 'password', 'class' => 'password validate invalid', 'id' => 'password', 'maxlength' => '16'));?>
					<?php echo Form::input('password_dummy', null, array('type' => 'password', 'style' => "width:2px;height:2px;position:absolute;opacity:0"));?>
					<label for="password" class="active" data-error="<?php echo $arr_validation_error['password'];?>" data-success="">パスワード</label>
					<?php echo Form::hidden('password_error', $arr_validation_error['password'], array('class' => 'email_error'));?>
				<?php else:?>
					<?php echo Form::input('password', \Input::post('password'), array('type' => 'password', 'class' => 'password validate', 'id' => 'password', 'maxlength' => '16'));?>
					<?php echo Form::input('password_dummy', null, array('type' => 'password', 'style' => "width:2px;height:2px;position:absolute;opacity:0"));?>
					<label for="password" data-error="wrong" data-success="">パスワード</label>
				<?php endif;?>
			</div>
		</div>

		<div class="row">
			<div class="input-field col s12">
				<?php echo Form::input('is_auto_login', true, array('type' => 'checkbox', 'checked' => $checked, 'id' => 'is_auto_login', 'class' => 'filled-in')). PHP_EOL;?>
				<label for="is_auto_login">次回からのログインを自動化します</label>
			</div>

			<div class="input-field col s12" style="margin-top: 27px;">
				<button type="submit" class="btn waves-effect waves-light large">
					<i class="material-icons right">send</i>
					メールとパスワードでログイン
				</button>
			</div>
		</div>
	<?php echo Form::close(). PHP_EOL; ?>

	<br />

	<h6 style="margin-top: 10px; margin-left: 14px;">パスワードをお忘れの方は<span class="underline blue-text"><?php echo Html::anchor('/user/password/reissuerequest/', 'こちら');?></span></h6>
	<h6 style="margin-top: 10px; margin-left: 14px;">新規登録する方は<span class="underline blue-text"><?php echo Html::anchor('/user/regist/email/', 'こちら');?></span></h6>


<?php endif;?>
	<br /><br />
</div>
</body>
</html>