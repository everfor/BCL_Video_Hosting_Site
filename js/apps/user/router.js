var router = angular.module('userRouter', 
	[
		'ngRoute',
		'userAccountControllers'
	]
);

router.config(
	[
		'$routeProvider',
		'$locationProvider',
		function($routeProvider, $locationProvider) {
			$routeProvider
				.when('/login', 
					{
						templateUrl: 'templates/loginForm.html',
						controller: 'loginCntrl', 
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

angular.bootstrap(document.getElementById("user"),['userRouter']);