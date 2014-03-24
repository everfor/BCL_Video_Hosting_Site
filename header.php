<!--Header of all pages-->
<!DOCTYPE html>
<html>
<head>
	<meta char-set="utf-8">
	<title><?=$pageTitle ?></title>
	<?=$styles?>
	<script src="js/lib/angular.js" type="text/javascript"></script>
	<script src="js/lib/angular-route.js" type="text/javascript"></script>
	<?=$scripts?>
</head>
<body ng-app="mainApp">
	<div class="header">
		<form action="POST">
			<input type="text" placeholder="hello!"></input>
			<button type="submit">submit</button>
		</form>
	</div>