<script type="text/javascript">
</script>
<style>
</style>

<?php if (isset($error_message)):?>
	<div class="container" style="margin: 18px auto;">
		<div class="alert alert-danger"><?php echo $error_message;?></div>
	</div>
<?php else:?>
	<div class="container">
		<br />
		<h5 class="header center">送信完了</h5>
		<br />
		<div style="height: 300px;">
			<h6 style="line-height: 1.6rem;" class="center">お問い合わせいただきありがとうございます。<br />ただいま送信が完了しました。<br />どうぞ今後ともペイジャーナルをよろしくお願いいたします。</h6>
			<div class="center"><?php echo Html::anchor('/', 'トップページへ', array('class' => 'waves-effect waves-light btn'));?></div>
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
