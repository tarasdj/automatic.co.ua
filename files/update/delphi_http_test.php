<?php 
if (isset($_GET['appversion'])):
	$version = '4.2.0.0';
	$appversion = $_GET['appversion'];
	if ($version != $appversion):
		print ($version);
	else:
		print ('');
	endif;
endif;	
?>