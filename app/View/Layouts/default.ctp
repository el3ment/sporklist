<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
        echo $this->Html->css(array('http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600', 'reset', 'base'));
	?>
</head>
<body id='page-home'>
    <div id='container-header'>
        <div class='logo'><span class='subdomain'>byu</span>.sporklist.com</div>
    </div>
    <div id='container-content'>
        <?php echo $this->Session->flash(); ?>

		<?php echo $this->fetch('content'); ?>
    </div>
	<div class='testing-sqlDump'>
        <?php echo $this->element('sql_dump'); ?>
    </div>
</body>
</html>

