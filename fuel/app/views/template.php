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
<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.bundle.min.js"></script>
<?php echo Asset::js('jquery.ui.touch-punch.min.js');?>
<?php echo Asset::css('bootstrap.min.css');?>
<?php echo Asset::css('bootstrap-datepicker.min.css');?>
<?php echo Asset::js('bootstrap.min.js');?>
<?php echo Asset::js('bootstrap-datepicker.min.js');?>
<?php echo Asset::js('bootstrap-datepicker.ja.js');?>
<script src="//gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<link href="//gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">

<?php echo Asset::css('journal.css');?>

<script type="text/javascript">
jQuery(function($) {
	$('.datepicker').datepicker({
		format: "yyyy-mm-dd",
		language: "ja",
		autoclose: true,
	});
	$('#logout').on('click', function() {
		if (confirm('ログアウトしますが、よろしいですか？')) {
			return true;
		}
		return false;
	});
});
</script>
</head>
<body>
<nav class="navbar navbar-default" style="margin-bottom: 3px;">
<div class="container">
<?php if (empty($user_id)):?>
	<div class="navbar-header navbar-left">
		<?php echo Html::anchor('/', 'PayJournal', array('class' => 'navbar-brand logo logo_header'));?>
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#top_nav">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	<div class="collapse navbar-collapse" id="top_nav">
		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<?php echo Html::anchor('/user/login', 'ログイン');?>
			</li>
		</ul>
	</div>
<?php else:?>
	<div class="navbar-header navbar-left">
		<?php echo Html::anchor('/', 'PayJournal', array('class' => 'navbar-brand logo logo_header'));?>
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#top_nav">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	<div class="collapse navbar-collapse" id="top_nav">
		<ul class="nav navbar-nav navbar-left">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					支出情報<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><?php echo Html::anchor('/payment/list/', '支出データ一覧');?></li>
					<li class="divider"></li>
					<li><?php echo Html::anchor('/payment/add/', '支出データ入力');?></li>
					<li class="divider"></li>
					<li><?php echo Html::anchor('/fix/', '登録済みカテゴリー一覧');?></li>
				</ul>
			</li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					アカウント情報<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
					<li><?php echo Html::anchor('/user/edit/', 'ユーザ情報変更');?></li>
					<li class="divider"></li>
					<li><?php echo Html::anchor('/user/logout', 'ログアウト', array('id' => 'logout'));?></li>
				</ul>
			<li>
		</ul>
	</div>
<?php endif;?>
</div>
</nav>
<?php if ( ! empty($user_name)):?>
<div class="container" style="padding: 0px; text-align: right; margin: 5px auto 10px; padding-right: 20px; color: #555;">
	Welcome!! &nbsp;<?php echo $user_name; ?>
</div>
<?php endif;?>
<?php if ( ! empty($message)):?>
	<div class="container">
		<br /><div class="alert alert-success"><?php echo $message;?></div>
	</div>
<?php endif;?>

<?php if (isset($contents)):?>
<?php echo $contents; ?>
<?php endif;?>

