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

	<h5 class="header"><i class="material-icons" style="display:inline-block; margin-right: 5px; vertical-align: middle; font-size: x-large; color: #aaa;">send</i>メール送信しました</h5>

	<br />

	<div class="row">
		<div class="col s12">
		メールアドレス変更を受付致しました。<br />
		ご登録いただいたメールアドレスに確定用のリンクを記載したメールを送信しました。<br /><br />
		そちらに記載されたリンクをクリックいただいた時点でメールアドレス変更が確定になります。
		<br />どうぞご確認よろしくお願い致します。<br />なお、ただいまから<?php echo \Config::get('journal.decide_time_min');?>分間が有効期限になりますことご注意ください。
		</div>
	</div>
</div>
<?php endif;?>
</body>
</html>