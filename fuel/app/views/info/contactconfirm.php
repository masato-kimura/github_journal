<?php if (isset($error_message)):?>
	<div class="container" style="margin: 18px auto;">
		<div class="alert alert-danger"><?php echo $error_message;?></div>
	</div>
<?php else:?>

	<div class="container">
		<br />
		<h5 class="header center">サービス提供業者概要</h5>
		<br />
		<div>
			<h6>■ 業者名：<dl><dd><?php echo \Config::get('journal.company.name');?></dd></dl></h6>
			<h6>■ 運営統括責任者： <dl><dd>木村正人</dd></dl></h6>
			<h6>■ 所在地：<dl><dd><?php echo \Config::get('journal.company.address');?></dd></dl></h6>
			<h6>■ 電話番号：<dl><dd><?php echo \Config::get('journal.company.tel');?></dd></dl></h6>
			<h6>■ メールアドレス：<dl><dd><?php echo \Config::get('journal.company.email');?></dd></dl></h6>
			<h6>■ 事業内容：<dl><dd>ウェブアプリケーションサービス制作及び運営、業務システム開発請負業他</dd></dl></h6>
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
