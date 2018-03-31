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
	<h5 class="header center">よくある質問</h5>
	<br />
	<div class="row">
		<div class="col s12">
			<p>Q. 料金体制を教えてください</p>
			<blockquote>A. 現在開発ベータ版のため全て無料で試用することができます。(2017/10/10時点)</blockquote>
			<p>Q. facebookやtwitterなどのソーシャルログインを利用する場合に自動でSNSのタイムラインなどに投稿することはありませんか？</p>
			<blockquote>A. ソーシャル認証機関ログインをご利用の場合は各ソーシャルアプリの認証機能のみ利用いたしますので、当アプリから本人以外が閲覧できるような投稿をすることはございません。</blockquote>
			<p>Q. 開発ベータ版で入力したデータは正式リリース版で引き継げますか？</p>
			<blockquote>A. はい、開発ベータ版でも入力していただいたデータは慎重扱いますので、利用者の意向や不慮の事故等による事態以外でデータを廃棄することはございません。なお今後追加機能や仕様変更が発生した場合も都度アナウンスしていく予定です。</blockquote>
			<p>Q. 収入データの入力はできないのですか？</p>
			<blockquote>A. はい、申し訳ございませんが現在当アプリは支出データのみを扱っております。今後収入データも扱えるようにしキャッシュフローや家計のバランスシート等も可視化できるようにアップデートしていく予定でございます。</blockquote>
			<p>Q. 突然閉鎖してしまうことはないのですか？</p>
			<blockquote>A. はい、現時点で開発ベータ版ですが私達も支出を管理するために日常利用しているため当面閉鎖予定はございません。</blockquote>
			<p>Q. 退会したいのですが？</p>
			<blockquote>A. ただいま退会フォームを設置開発中でございます。今しばらくお待ちください。
			なお現在開発ベータ版のためこちらからメールでのご案内や広告営業を行うことはございませんが、
			早急にアカウントデータの削除をご希望する場合には、お問い合わせフォームにてメールアドレス、お名前と退会希望の旨を記載してご連絡していただければご利用者に関わる全てのデータを廃棄対応いたします。
			</blockquote>
		</div>
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
<!--
					<li><a class="white-text" href="#!">使い方</a></li>
 -->
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
