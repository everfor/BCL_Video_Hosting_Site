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
						templateUrl: 'templates/searchResults.html',
						controller: 'searchCntrl', 
					}
				)
				.when('/login',
					{
						templateUrl: 'templates/loginForm.html',
						controller: 'loginCntrl', 
					}
				);

			if (window.history && window.history.pushState) {
				$locationProvider.html5Mode(true);
			}
		}
	]
);