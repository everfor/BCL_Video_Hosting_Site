<!--Header of all pages-->
<!DOCTYPE html>
<html>
<head>
	<meta char-set="utf-8">
	<title><?=$pageTitle ?></title>
	<?=$styles?>
	<script src="js/lib/angular.min.js" type="text/javascript"></script>
	<script src="js/app/search/controller.js" type="text/javascript"></script>
	<?=$scripts?>
</head>
<body>
	<div class="header" ng-app="searchApp" ng-controller="searchController">
		Search : <input ng-model="username">
		<p>
			Hello, <span ng-bind="username"></span>
		</p>
		<ul>
			<li ng-repeat="_result in results | filter: query">
				{{_result.id}}
				<br/>
				{{_result.name}}
			</li>
		</ul>
	</div>