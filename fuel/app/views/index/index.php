<?php if (isset($error_message)):?>
<div class="container" style="margin: 18px auto;">
	<div class="alert alert-danger"><?php echo $error_message;?></div>
</div>
<?php else:?>

  <div id="index-banner" class="parallax-container">
    <div class="section no-pad-bot">
      <div class="container">
        <br><br>
        <h2 class="header center white-text">いまはじめれば変われる</h2>
        <div class="row center">
          <h5 class="header col s12 grey-text text-darken-4">家計簿アプリのペイジャーナル</h5>
        </div>
        <div class="row center">
			<?php if (empty($arr_user_info['user_id'])):?>
				<?php echo Html::anchor('payment/list', '始めてみよう(開発ベータ版で無料です)', array('id' => 'download-button', 'class' => 'btn-large waves-effect waves-light teal lighten-1'));?>
          	<?php else: ?>
         		<?php echo Html::anchor('payment/list', 'スタート(開発ベータ版で無料です)', array('id' => 'download-button', 'class' => 'btn-large waves-effect waves-light teal lighten-1'));?>
			<?php endif;?>
        </div>
        <br><br>

      </div>
    </div>
    <div class="parallax"><?php echo Asset::img('index/coin.jpg');?></div>
  </div>

  <div class="container">
    <div class="section">

      <div class="row">
        <div class="col s12 center">
          <h3><i class="mdi-content-send brown-text"></i></h3>
          <h4>お金が残る</h4>
          <p class="left-align light">ついつい使いすぎた！なんて時はお財布の中身から目を背けたくなります。そこをグッと我慢して使った金額をメモしてみましょう。お金を残すにはそこが最大のポイントです。
          冷静になって後から見直してみると意外にもその時点ではセーフだったりします。問題なのは不安に駆られて自暴自棄になり浪費を繰り返してしまうこと、または必要以上に節約に努めてストレスを溜め込み衝動買いを誘発しまうことです。
          お金を使うことは楽しくて気持ちが高まります。でも時にはクールになる時間も必要です。身近なところに家計状況を確認できるツールがあれば、それは冷静になるための最大の武器です。ペイジャーナルはきっとあなたのお役にたちます。</p>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="section">

      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center brown-text"><i class="material-icons">smartphone</i><i class="material-icons">desktop_mac</i><i class="material-icons">create</i></h2>
            <h5 class="center">かんたん入力</h5>

            <p class="light">いつでもどのような場面でもサッと入力できるようスマートフォン、タブレット、PC等の端末に対応するレスポンシブデザインを採用してます。ストレスフリーな操作性を第一に考えてインターフェースを作り込んでいますので外出先では最低限の入力、帰宅後にPCやタブレットで詳細データを追加入力というような使い方もできます。入力データもログイン機能により瞬時に各端末に共有され連携されます。</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center brown-text"><i class="material-icons">search</i></h2>
            <h5 class="center">強力な検索機能</h5>

            <p class="light">支出データをグラフ表示する機能はもちろんのこと、入力時の文章や単語をキーワード検索し集計することができます。一年を通して近所のコンビニに使った金額を集計してみて驚愕するかもしれません。また入力時のメモ欄には自由に文章を入力できますので、
            友人と映画に行ったことや感想をちょっとした日記がわりに書き込んでおくと後から友人の名前や映画のタイトルで検索することもできます。</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center brown-text"><i class="material-icons">cloud_off</i><i class="material-icons">lock</i></h2>
            <h5 class="center">シンプル・安全</h5>

            <p class="light">当アプリケーションは銀行系システムに接続することはありません。多機能であることと使いやすさはトレードオフの関係であると私たちは考え『使いやすさ』『見やすさ』『わかりやすさ』に重点を置きました。また全てのネットワークにSSL暗号化通信を採用しプライバシーな情報が傍受されることのないよう配慮しております。データのバックアップも日毎に実施します。</p>
          </div>
        </div>
      </div>

    </div>
  </div>

  <div class="parallax-container valign-wrapper">
    <div class="section no-pad-bot">
      <div class="container">
        <div class="row center">
          <h5 class="header col s12 light grey-text text-lighten-3">消費と投資と浪費</h5>
          <h6 class="header col s12 light grey-text text-lighten-4">ペイジャーナルでは支出データを、”消費” ”投資” ”浪費” に分けて入力するとグラフに反映されます。この場合の投資とは『本を買った』『大事な人と食事に行った』などその人にとって価値のある行動は投資として構いません。
          浪費は決して悪いことではありません。日々の生活において豊かさを実感できる必要不可欠な行動です。</h6>
        </div>
      </div>
    </div>
    <div class="parallax"><?php echo Asset::img('index/coin.jpg');?></div>
  </div>

  <div class="parallax-container valign-wrapper">
    <div class="section no-pad-bot">
      <div class="container">
        <div class="row center">
          <h5 class="header col s12 light grey-text text-lighten-3">支出のバランス</h5>
          <h6 class="header col s12 light grey-text text-lighten-4">一ヶ月、半年、一年と振り返った時の支出バランスをふりかえってみましょう。一般的に消費が70%, 投資が20%, 浪費が10%が理想とされていますが、これは人それぞれの価値観で構いません。
          大事なことはふりかえってみて、これからをどうしたいのかを考えることです。そのお手伝いをペイジャーナルはできます。</h6>
        </div>
      </div>
    </div>
    <div class="parallax"><?php echo Asset::img('index/coin.jpg');?></div>
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
				<!--
					<li><a class="white-text" href="#!">使い方</a></li>
				 -->
					<li><a class="white-text" href="info/question">よくある質問</a></li>
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
