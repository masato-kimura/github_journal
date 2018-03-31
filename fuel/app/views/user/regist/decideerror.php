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

	<h5 class="header"><i class="material-icons" style="display:inline-block; margin-right: 5px; vertical-align: middle; font-size: xx-large; color: #aaa;">error_outline</i>エラーが発生しました。</h5>

	<br />

	<div class="row">
		<div class="col s12">
			<div class="alert alert-danger">
			エラーが発生しました。<br />
			すでにユーザ登録が確定しているか有効期限が過ぎているかもしれません。<br />
			メール送信から時間が<?php echo \Config::get('journal.decide_time_min');?>分以上経過している場合は、お手数おかけしますが<?php echo Html::anchor('/user/regist/', 'こちら');?>から再度ユーザ登録お願いします。
			</div>
		</div>
	</div>
</div>
<?php endif;?>
</body>
</html>