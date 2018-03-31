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
<?php echo Asset::css('index/index.css');?>
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/js/materialize.min.js"></script>
<?php echo Asset::js('init.js');?>
<?php echo Asset::css('journal_material.css');?>
<script type="text/javascript">
jQuery(function() {
	$(".dropdown-button").dropdown();
});
</script>
</head>
<body>

	<!-- Dropdown Structure PC -->
	<ul id="dropdown_account_pc" class="dropdown-content">
		<li><?php echo Html::anchor('/user/edit/', 'ユーザ情報変更');?></li>
		<li class="divider"></li>
		<li><?php echo Html::anchor('/user/logout', 'ログアウト', array('id' => 'logout'));?></li>
	</ul>

	<!-- Dropdown Structure SmartPhone -->
	<ul id="dropdown_account_smartphone" class="dropdown-content">
		<li><?php echo Html::anchor('/user/edit/', 'ユーザ情報変更');?></li>
		<li class="divider"></li>
		<li><?php echo Html::anchor('/user/logout', 'ログアウト', array('id' => 'logout'));?></li>
	</ul>

	<nav class="white" role="navigation">
		<div class="nav-wrapper container">
			<a id="logo-container" href="<?php echo \Config::get('journal.www_host');?>/payment/list/" class="brand-logo">PayJournal</a>

			<!-- PC -->
			<ul class="hide-on-med-and-down right">
				<li><?php echo Html::anchor('payment/list', '家計簿コンテンツ')?></li>
				<li><?php echo Html::anchor('info/contact', 'お問い合わせ');?></li>
				<li><?php echo Html::anchor('info/question', 'よくある質問');?></li>
				<?php if (empty($user_id)):?>
					<li><?php echo Html::anchor('payment/list', 'ログイン')?></li>
				<?php else:?>
					<!-- Dropdown Trigger -->
					<li>
						<a class="dropdown-button" href="#!" data-activates="dropdown_account_pc">
							アカウント情報<i class="material-icons right">arrow_drop_down</i></a>
					</li>
 				<?php endif;?>
			</ul>

			<!-- SmartPhone -->
			<a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
			<ul id="nav-mobile" class="side-nav">
				<li><?php echo Html::anchor('payment/list', '家計簿コンテンツ')?></li>
				<li><?php echo Html::anchor('info/contact', 'お問い合わせ');?></li>
				<li><?php echo Html::anchor('info/question', 'よくある質問');?></li>
				<?php if (empty($user_id)):?>
					<li><?php echo Html::anchor('payment/list', 'ログイン')?></li>
				<?php else:?>
					<li>
						<a class="dropdown-button" href="#!" data-activates="dropdown_account_smartphone">
							アカウント情報<i class="material-icons right">arrow_drop_down</i></a>
					</li>
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

