var app = angular.module('mainApp', 
	[
		'ngRoute',
		'searchControllers',
	]
);

app.config(
	[
		'$routeProvider',
		'$locationProvider',
		function($routeProvider, $locationProvider) {
			$routeProvider
				.when('/search', 
					{
						templateUrl: 'js/templates/searchResults.html',
						controller: 'searchCntrl', 
					}
				)
				.otherwise(
					{
						redirectTo: '/search'
					}
				);

			if (window.history && window.history.pushState) {
				$locationProvider.html5Mode(true);
			}
		}
	]
);