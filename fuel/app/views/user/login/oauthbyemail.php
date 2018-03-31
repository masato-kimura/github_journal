<?php if (isset($error_message)):?>
<div class="container">
	<br />
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else:?>
<script type="text/javascript">
jQuery(function() {
	$('form').submit(function() {
		var radio_cnt = $('.radio').length;
		for (var i=0; i<radio_cnt; i++) {
			if ($('.radio input[type=radio]').eq(i).prop('checked')) {
				return true;
			}
		}
		alert('ログイン認証先を一つ選択してください');
		return false;
	});
});
</script>
<style>
</style>

<div class="container">

	<br />

	<h5 class="header">
		<i class="material-icons prefix" style="display:inline-block; margin-right: 5px; vertical-align: middle; font-size: xx-large; color: #aaa;">account_circle</i>ログイン
	</h5>

	<br />

	<h6>■このメールアドレスとパスワードの組み合わせは次のログイン方法で使われています。<br />どれか一つ選択してログインしてください。</h6>

	<br />

	<h5><?php echo \Input::post('email');?></h5>

	<br />

	<div class="row" style="margin-left: 3px;">
		<?php echo Form::open('/user/login/email/', array('class' => 'col s12'));?>
			<?php foreach ($this->arr_regist_user as $i => $val):?>
				<?php if ($val->oauth_type == 'email'):?>
					<div class="radio">
						<?php echo Form::radio('oauth_type', 'email', \Input::post('oauth_type'), array('id' => 'form_oauth_type_email')); ?>
						<?php echo Form::label('ペイジャーナル', 'oauth_type_email');?>
					</div>
				<?php endif;?>
				<?php if ($val->oauth_type == 'facebook'):?>
					<div class="radio">
						<?php echo Form::radio('oauth_type', 'facebook', \Input::post('oauth_type'), array('id' => 'form_oauth_type_facebook')); ?>
						<?php echo Form::label('Facebook', 'oauth_type_facebook');?>
					</div>
				<?php endif;?>
				<?php if ($val->oauth_type == 'line'):?>
					<div class="radio">
						<?php echo Form::radio('oauth_type', 'line', \Input::post('oauth_type'), array('id' => 'form_oauth_type_line')); ?>
						<?php echo Form::label('LINE', 'oauth_type_line');?>
					</div>
				<?php endif;?>
				<?php if ($val->oauth_type == 'google'):?>
					<div class="radio">
						<?php echo Form::radio('oauth_type', 'google', \Input::post('oauth_type'), array('id' => 'form_oauth_type_google')); ?>
						<?php echo Form::label('Google', 'oauth_type_google');?>
					</div>
				<?php endif;?>
				<?php if ($val->oauth_type == 'twitter'):?>
					<div class="radio">
						<?php echo Form::radio('oauth_type', 'twitter', \Input::post('oauth_type'), array('id' => 'form_oauth_type_twitter')); ?>
						<?php echo Form::label('Twitter', 'oauth_type_twitter');?>
					</div>
				<?php endif;?>
				<?php if ($val->oauth_type == 'yahoo'):?>
					<div class="radio">
						<?php echo Form::radio('oauth_type', 'yahoo', \Input::post('oauth_type'), array('id' => 'form_oauth_type_yahoo')); ?>
						<?php echo Form::label('Yahoo!', 'oauth_type_yahoo');?>
					</div>
				<?php endif;?>
			<?php endforeach;?>

			<?php echo Form::hidden('is_auto_login', \Input::post('is_auto_login'));?>
			<?php echo Form::hidden('email', \Input::post('email'), array('type' => 'email', 'class' => 'email form-control', 'id' => 'email', 'maxlength' => '100'));?>
			<?php echo Form::hidden('password', \Input::post('password'), array('type' => 'password', 'class' => 'password form-control', 'id' => 'password', 'maxlength' => '16'));?>
			<br />
			<br />
			<button type="submit" name="to_submit" id="to_submit" class="btn waves-effect waves-light" style="width: 96%; height: 58px;">
				<i class="material-icons right">send</i>ログイン
			</button>
		<?php echo Form::close(). PHP_EOL; ?>
	</div>

<?php endif;?>
	<br /><br />
</div>
</body>
</html>