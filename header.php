<!--Header of all pages-->
<?php
	session_start();
	// Help to check the autologin stuff
	// require_once('lib/User/User.php');
	// $userAgent = new User();
?>
<!DOCTYPE html>
<html>
<head>
	<meta char-set="utf-8">
	<title><?=$pageTitle ?></title>
	<?=$styles?>
	<script src="js/lib/angular.js" type="text/javascript"></script>
	<script src="js/lib/angular-route.js" type="text/javascript"></script>
	<script src="js/main.js" type="text/javascript"></script>
	<?=$scripts?>
</head>
<body ng-app="main">
	<div id="header">
		<div id="searchBar">
			<form action="POST">
				<input type="text" placeholder="hello!"></input>
				<button type="submit">submit</button>
			</form>
		</div>
	</div>