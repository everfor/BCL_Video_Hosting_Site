var router = angular.module('searchRouter', 
	[
		'ngRoute',
		'searchControllers'
	]
);

router.config(
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
				.otherwise(
				);

			if (window.history && window.history.pushState) {
				$locationProvider.html5Mode(true);
			}
		}
	]
);

angular.bootstrap(document.getElementById("search"),['searchRouter']);