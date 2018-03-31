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

	<h5 class="header"><i class="material-icons" style="display:inline-block; margin-right: 5px; vertical-align: middle; font-size: xx-large; color: #aaa;">send</i>メール送信しました</h5>
	<br />

	<p>ご指定のメールアドレスにパスワード再発行方法を記載したメールを送信しました。<br />有効期限は<?php echo \Config::get('journal.decide_time_min');?>分間ですので時間内に、ご確認ご対応ください。</p>

</div>
<?php endif;?>
</body>
</html>