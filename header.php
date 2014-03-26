<!--Header of all pages-->
<?php
	session_start();
	// Help to check the autologin stuff
	require_once('/lib/User/User.php');
	$userAgent = new User();
?>
<!DOCTYPE html>
<html>
<head>
	<meta char-set="utf-8">
	<title><?=$pageTitle ?></title>
	<?=$styles ?>
	<script src="/js/lib/jquery-2.1.0.min.js" type="text/javascript"></script>
	<script src="/js/lib/angular.js" type="text/javascript"></script>
	<script src="/js/lib/angular-route.js" type="text/javascript"></script>
	<!--Main AngularJS app-->
	<script src="/js/main.js" type="text/javascript"></script>
	<!--Controllers for searching-->
	<script src="/js/controllers/search.js" type="text/javascript"></script>
	<?=$scripts ?>
</head>
<body ng-app="main">
	<div class="header">
		<div id="searchBar" ng-controller="searchCntrl">
			<form ng-submit="submit()">
				<input id="searchTerm" type="text" placeholder="hello!" ng-model="searchTerm" ng-bind="searchTerm"></input>
				<button type="submit">Search</button>
			</form>
		</div>
	</div>