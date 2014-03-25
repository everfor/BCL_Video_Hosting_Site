var app = angular.module('mainApp', 
	[
		'ngRoute'
	]
);

app.config(
	[
		'$routeProvider',
		'$locationProvider',
		function($routeProvider, $locationProvider) {
			$routeProvider
				.when('/login',
					{
						templateUrl: 'templates/loginForm.html'
					}
				)
				.when('/search', 
					{
						templateUrl: 'templates/searchResults.html'
					}
				)
				.otherwise(
				);

			if (window.history && window.history.pushState) {
				$locationProvider.html5Mode(true);
			}
		}
	]
);