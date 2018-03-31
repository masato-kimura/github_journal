<script type="text/javascript">
</script>
<style>
.form_title {
  color: #777;
  font-size: 0.8rem;
}
.form_ans {
  line-height: 3rem;
  border-bottom: 1px solid #aaa;
  font-size: 16px;
}
#form_contact_contact {
  padding-bottom: 0.6rem;
}
input:not([type]), input[type="text"], input[type="password"], input[type="email"], input[type="url"], input[type="time"], input[type="date"], input[type="datetime"], input[type="datetime-local"], input[type="tel"], input[type="number"], input[type="search"], textarea.materialize-textarea {
  font-size: 16px;
}
</style>

<?php if (isset($error_message)):?>
	<div class="container" style="margin: 18px auto; height: 200px;">
		<div class="alert alert-danger"><?php echo $error_message;?></div>
	</div>
<?php else:?>
	<div class="container">

		<br />

		<h5 class="header center">お問い合わせ</h5>

		<br />

		<div class="row">
				<?php echo Form::open('/info/contactdone', array('class' => 'col s12'));?>

				<?php if (empty($this->arr_user_info)):?>
					<div class="row">
						<div class="input-field col s12">
						<?php if (isset($arr_validation_error['user_name_contact'])):?>
							<?php echo Form::input('user_name_contact', \Input::post('user_name_contact', isset($arr_params['user_name_contact'])? $arr_params['user_name_contact']: null), array('class' => 'validate invalid'));?>
							<?php echo Form::label('お名前', 'user_name_contact', array('data-error' => $arr_validation_error['user_name_contact']));?>
						<?php else:?>
							<?php echo Form::input('user_name_contact', \Input::post('user_name_contact', isset($arr_params['user_name_contact'])? $arr_params['user_name_contact']: null));?>
							<?php echo Form::label('お名前', 'user_name_contact');?>
						<?php endif;?>
						</div>
					</div>
					<div class="row">
						<div class="input-field col s12">
						<?php if (isset($arr_validation_error['email_contact'])):?>
							<?php echo Form::input('email_contact', \Input::post('email_contact', isset($arr_params['email_contact'])? $arr_params['email_contact']: null), array('type' => 'email', 'class' => 'validate invalid'));?>
							<?php echo Form::label('メールアドレス', 'email_contact', array('data-error' => $arr_validation_error['email_contact']));?>
						<?php else:?>
							<?php echo Form::input('email_contact', \Input::post('email_contact', isset($arr_params['email_contact'])? $arr_params['email_contact']: null), array('type' => 'email'));?>
							<?php echo Form::label('メールアドレス', 'email_contact');?>
						<?php endif;?>
						</div>
					</div>
				<?php else:?>
					<div class="form_title">お名前</div>
					<div class="form_ans"><?php echo $arr_user_info['user_name'];?></div>
					<?php echo Form::hidden('user_name_contact', \Input::post('user_name_contact', $arr_user_info['user_name']));?>
					<br />
					<div class="form_title">メールアドレス</div>
					<div class="form_ans"><?php echo $arr_user_info['email'];?></div>
					<?php echo Form::hidden('email_contact', \Input::post('email_contact', $arr_user_info['email']));?>
					<br />
				<?php endif;?>

				<div class="row">
					<div class="input-field col s12">
						<?php if (isset($arr_validation_error['contact_contact'])):?>
							<?php echo Form::textarea('contact_contact', \Input::post('contact_contact', isset($arr_params['contact_contact'])? $arr_params['contact_contact']: null), array('class' => 'materialize-textarea validate invalid')); ?>
							<?php echo Form::label('本文', 'contact_contact', array('data-error' => $arr_validation_error['contact_contact']));?>
						<?php else:?>
							<?php echo Form::textarea('contact_contact', \Input::post('contact_contact', isset($arr_params['contact_contact'])? $arr_params['contact_contact']: null), array('class' => 'materialize-textarea validate')); ?>
							<?php echo Form::label('本文', 'contact_contact');?>
						<?php endif;?>
					</div>
				</div>
				<?php echo Form::submit('submit', '送信', array('class' => 'btn waves-effect waves-light'));?>

				<br />
				<br />

				<div class="blue-grey-text text-lighten-1">お問い合わせの内容によっては返信にお時間いただくことがございます。</div>
				<div class="blue-grey-text text-lighten-1">ご意見ご要望は拝見いたしますが、内容によっては個々に返信せずサイト内掲載で対応させていただく場合がございます。</div>

				<br />
				<br />

			</div>

		</div>

<?php endif;?>

<footer class="page-footer teal">
	<div class="container">
		<div class="row">
			<div class="col l6 s12">
				<h5 class="white-text">運営管理</h5>
				<p class="grey-text text-lighten-4"><?php echo Html::anchor('info/us', \Config::get('journal.company.name'), array('class' => 'white-text'));?></p>
			</div>
			<div class="col l3 s12">
				<h5 class="white-text">ご利用案内</h5>
				<ul>
					<li><?php echo Html::anchor('info/privacy', 'プライバシーポリシー', array('class' => 'white-text'))?></li>
					<li><?php echo Html::anchor('info/terms', '利用規約', array('class' => 'white-text'))?></li>
					<li><?php echo Html::anchor('info/contract', '特定商取引に基づく表記', array('class' => 'white-text'))?></li>
				</ul>
			</div>
			<div class="col l3 s12">
				<h5 class="white-text">サービス</h5>
				<ul>
					<li><a class="white-text" href="#!">使い方</a></li>
					<li><a class="white-text" href="#!">よくある質問</a></li>
					<li><?php echo Html::anchor('info/contact', 'お問い合わせ', array('class' => 'white-text'))?></li>
				</ul>
			</div>
		</div>
	</div>
	<div class="footer-copyright">
		<div class="container">
			Copyright © RoundAbout All Rights Reserved.
		</div>
	</div>
</footer>

</body>
</html>
