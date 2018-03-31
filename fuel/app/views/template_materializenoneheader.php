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
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>

<?php echo Asset::js('init.js');?>
<?php // echo Asset::css('journal.css');?>
<script type="text/javascript">
jQuery(function() {
	$(".dropdown-button").dropdown();
});
</script>
</head>
<body>


<?php if ( ! empty($message)):?>
	<div class="container">
		<br /><div class="alert alert-success"><?php echo $message;?></div>
	</div>
<?php endif;?>

<?php if (isset($contents)):?>
	<?php echo $contents; ?>
<?php endif;?>

