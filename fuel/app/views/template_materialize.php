<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title><?php  echo isset($title)? $title: ''; ?></title>
<?php
echo html_tag('link', array(
	'rel' => 'icon',
	'href' => Asset::get_file('favicon.ico', 'img'),
));
echo html_tag('link', array(
	'rel' => 'apple-touch-icon-precomposed',
	'href' => Asset::get_file('favicon.png', 'img'),
	'sizes' => '57x57',
));
?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css">
<?php echo Asset::css('journal_material.css');?>
<?php echo Asset::css('index/index.css');?>

<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<?php echo Asset::js('jquery.ui.touch-punch.min.js');?>
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<?php echo Asset::js('init.js');?>
<script type="text/javascript">
jQuery(function() {
	$('#logout').on('click', function() {
		if (confirm('ログアウトしますが、よろしいですか？')) {
			return true;
		}
		return false;
	});
	$(".dropdown-button").dropdown();
	$('.datepicker').pickadate({
		//closeOnSelect: true,
		format: "yyyy-mm-dd",
		language: "ja",
		selectMonths: true, // Creates a dropdown to control month
		selectYears: 15, // Creates a dropdown of 15 years to control year
		onSet: function( arg ){
			if ( 'select' in arg ){ //prevent closing on selecting month/year
				this.close();
			}
		}
	});
});
</script>
</head>
<body>

	<!-- Dropdown Structure PC -->
	<ul id="dropdown_account_pc" class="dropdown-content">
		<li><?php echo Html::anchor('/user/edit/', 'ユーザ情報変更', array('style' => 'font-size: small;'));?></li>
		<li class="divider"></li>
		<li><?php echo Html::anchor('/user/logout', 'ログアウト', array('id' => 'logout', 'style' => 'font-size: small;'));?></li>
	</ul>
	<ul id="dropdown_content_pc" class="dropdown-content">
		<li><?php echo Html::anchor('/payment/list/', '支出データ', array('style' => 'font-size: small;'));?></li>
		<li class="divider"></li>
		<li><?php echo Html::anchor('/payment/reservelist/', '定期支出データ登録', array('style' => 'font-size: small;'));?></li>
		<li class="divider"></li>
		<li><?php echo Html::anchor('/fix/', 'カテゴリー登録', array('style' => 'font-size: small;'));?></li>
	</ul>
	<ul id="dropdown_menu_pc" class="dropdown-content">
		<li><?php echo Html::anchor('/', 'トップページ', array('style' => 'font-size: small;'));?></li>
		<li class="divider"></li>
		<li><?php echo Html::anchor('info/question', 'よくある質問', array('style' => 'font-size: small;'));?></li>
		<li class="divider"></li>
		<li><?php echo Html::anchor('info/contact', 'お問い合わせ', array('style' => 'font-size: small;'));?></li>
	</ul>

	<!-- Dropdown Structure SmartPhone -->
	<ul id="dropdown_account_smartphone" class="dropdown-content">
		<li><?php echo Html::anchor('/user/edit/', 'ユーザ情報変更', array('style' => 'font-size: small;'));?></li>
		<li class="divider"></li>
		<li><?php echo Html::anchor('/user/logout', 'ログアウト', array('id' => 'logout', 'style' => 'font-size: small;'));?></li>
	</ul>
	<ul id="dropdown_content_smartphone" class="dropdown-content">
		<li><?php echo Html::anchor('/payment/list/', '支出データ', array('style' => 'font-size: small;'));?></li>
		<li class="divider"></li>
		<li><?php echo Html::anchor('/payment/reservelist/', '定期支出データ登録', array('style' => 'font-size: small;'));?></li>
		<li class="divider"></li>
		<li><?php echo Html::anchor('/fix/', 'カテゴリー登録', array('style' => 'font-size: small;'));?></li>
	</ul>
	<ul id="dropdown_menu_smartphone" class="dropdown-content">
		<li><?php echo Html::anchor('/', 'トップページ', array('style' => 'font-size: small;'));?></li>
		<li class="divider"></li>
		<li><?php echo Html::anchor('info/question', 'よくある質問', array('style' => 'font-size: small;'));?></li>
		<li class="divider"></li>
		<li><?php echo Html::anchor('info/contact', 'お問い合わせ', array('style' => 'font-size: small;'));?></li>
	</ul>

	<nav class="white" role="navigation">
		<div class="nav-wrapper container">
			<a id="logo-container" href="<?php echo \Config::get('journal.www_host');?>/payment/list/" class="brand-logo">PayJournal</a>

			<!-- PC -->
			<ul class="right hide-on-med-and-down">
				<?php if (empty($user_id)):?>
					<li><?php echo Html::anchor('payment/list', 'ログイン')?></li>
				<?php else:?>
					<li><?php echo Html::anchor('#!', '<i class="material-icons right">arrow_drop_down</i>家計簿コンテンツ', array('class' => 'dropdown-button', 'data-activates' => 'dropdown_content_pc'));?></li>
					<li><?php echo Html::anchor('#!', '<i class="material-icons right">arrow_drop_down</i>アカウント情報', array('class' => 'dropdown-button', 'data-activates' => 'dropdown_account_pc'));?></li>
					<li><?php echo Html::anchor('#!', '<i class="material-icons right">arrow_drop_down</i>メニュー', array('class' => 'dropdown-button', 'data-activates' => 'dropdown_menu_pc'));?></li>
 				<?php endif;?>
			</ul>

			<!-- SmartPhone -->
			<a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
			<ul id="nav-mobile" class="side-nav">
				<?php if (empty($user_id)):?>
					<li><?php echo Html::anchor('payment/list', 'ログイン')?></li>
				<?php else:?>
					<li><?php echo Html::anchor('#!', '<i class="material-icons right">arrow_drop_down</i>家計簿コンテンツ', array('class' => 'dropdown-button', 'data-activates' => 'dropdown_content_smartphone'));?></li>
					<li><?php echo Html::anchor('#!', '<i class="material-icons right">arrow_drop_down</i>アカウント情報', array('class' => 'dropdown-button', 'data-activates' => 'dropdown_account_smartphone'));?></li>
					<li><?php echo Html::anchor('#!', '<i class="material-icons right">arrow_drop_down</i>メニュー', array('class' => 'dropdown-button', 'data-activates' => 'dropdown_menu_smartphone'));?></li>
				<?php endif;?>
			</ul>
		</div>
	</nav>

<?php if ( ! empty($message)):?>
	<div class="container">
		<br /><div class="alert alert-success"><?php echo $message;?></div>
	</div>
<?php endif;?>

<?php if (isset($contents)):?>
	<?php echo $contents; ?>
<?php endif;?>

