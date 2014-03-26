var main = angular.module('main', 
	[
		'ngRoute'
	]
);

main.config(
	[
		'$routeProvider',
		'$locationProvider',
		function($routeProvider, $locationProvider) {
			$routeProvider
				.when('/search', 
					{
						templateUrl: '/templates/searchResults.html',
						controller: 'searchCntrl', 
					}
				)
				.when('/search/:term',
					{
						templateUrl: '/templates/searchResults.html',
						controller: 'searchCntrl', 
					}
				)
				.when('/login',
					{
						templateUrl: '/templates/loginForm.html',
						controller: 'loginCntrl', 
					}
				)
				.otherwise(
					{
						templateUrl: '/templates/index.html',
					}
				);

			if (window.history && window.history.pushState) {
				$locationProvider.html5Mode(true);
			}
		}
	]
);