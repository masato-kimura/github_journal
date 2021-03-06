<?php if (isset($error_message)):?>
<div class="container" style="margin: 18px auto;">
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else:?>
<script type="text/javascript">
</script>
<style>
</style>

<div class="container">
	<br />
	<h5 class="header center">特定商取引に基づく表記</h5>
	<br />
	<div>
		<h6>■ サービス提供者：<dl><dd><?php echo \Config::get('journal.company.name');?></dd></dl></h6>
		<h6>■ 運営統括責任者： <dl><dd>木村正人</dd></dl></h6>
		<h6>■ 所在地：<dl><dd><?php echo \Config::get('journal.company.address');?></dd></dl></h6>
		<h6>■ 電話番号：<dl><dd><?php echo \Config::get('journal.company.tel');?></dd></dl></h6>
		<h6>■ メールアドレス：<dl><dd><?php echo \Config::get('journal.company.email');?></dd></dl></h6>
		<h6>■ 有料サービス提供価格：<dl><dd>月額235円(消費税込)</dd></dl></h6>
		<h6>■ お支払い方法：<dl><dd>クレジットカード決済</dd></dl></h6>
		<h6>■ 当サービスを利用する上で必要となる利用者様が負担するその他の費用：<dl><dd>インターネット接続のための端末及び回線利用費等</dd></dl></h6>
		<h6>■ 有料サービス提供の時期<dl><dd>有料サービス手続き完了後直ちに提供</dd></dl>
		<h6>■ 返品・返金等の対応<dl><dd>サービス提供確定後のキャンセル・返金についてはお受けできません。</dd></dl></h6>
		<br />
		<br />
	</div>
</div>

<?php endif; ?>

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
