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
	<h5 class="header center">プライバシーポリシー</h5>
	<br />
	<div>
		ペイジャーナルではご利用の皆様が安心してご利用いただけるよう最低限の個人情報を提供いただいております。
		私共は利用者様の個人情報の保護について最大限に注意を払い万全を尽くしていきます。
		<br />
		<br />
		<h6>■ 取得項目について</h6>
		<ul>
			<li>利用者のメールアドレス</li>
			<li>外部SNS認証機関から発行されるID</li>
			<li>お問い合わせ内容</li>
			<li>利用者のログ情報</li>
			<li>クッキー情報</li>
		</ul>
		<br />

		<h6>■ 取得方法</h6>
		<ul>
			<li>利用者による提供</li>
			<li>利用者の利用動向（ログ収集）</li>
		</ul>
		<br />

		<h6>■ 個人情報の利用目的</h6>
		<ul>
			<li>本人確認</li>
			<li>アプリケーション内でのユーザー認証</li>
			<li>お問い合わせ対応時</li>
		</ul>
		<br />

		<h6>■ 個人情報の管理について</h6>
		<div>当サービスは、ご利用者から取得した情報を、最大限の努力のもと厳重に保護します。当サービスの運営に当たるものは、セキュリティや個人情報保護への意識の向上、啓発に努めます。
			なお取得した情報を、サービスの管理・運営を行う委託先業者以外と共有することはありません。委託先業者と個人情報を共有する際にはご利用者の個人情報の流出や不正アクセスなどが起こらないよう、
			業務委託先とは機密保持契約を締結してから業務を執行いたします。
		</div>
		<br />
		<div>
			アプリケーションで使用されるネットワーク通信においてはSSLを利用し情報を暗号化し、情報の送受信を行い外部からプライバシーな情報が傍受されることのないよう配慮します。
		</div>
		<br />

		<h6>■ 個人情報の開示・提供</h6>
		<div>当サービスは、下記の場合を除いて、取得した情報を本人の許可なく第三者に提供することはございません。</div>
		<dl>
			<dd>法令に基づく場合</dd>
			<dd>当サービスが行う業務の全部または一部を第三者に委託する場合</dd>
		</dl>
		<br />

		<h6>■ プライバシーポリシーの変更</h6>
		<div>ペイジャーナルでは利用者の許可なくして、プライバシーポリシーの変更をすることができます</div>
		<br />

		<h6>■ 免責</h6>
		<div>以下の場合は、第三者による個人情報の取得に関し、私共は何らの責任を負いません。</div>
		<dl>
			<dd>利用者本人が第三者に個人情報を明らかにした場合</dd>
			<dd>第三者が当サービス外(当サービスからリンクの張られている他のサイトを含む)で個人情報を取得した場合</dd>
			<dd>利用者本人以外が利用者を識別できる情報(メールアドレス、ID, パスワード等)を入手した場合</dd>
		</dl>
		<br />

		<h6>■ 個人情報に関するお問い合わせ先について</h6>
		<div>
			<?php echo \Config::get('journal.company.address');?><br />
			<?php echo \Config::get('journal.company.name');?> 個人情報対応窓口<br />
			<?php echo Html::anchor('info/contact', 'お問い合わせ');?>
		</div>
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
