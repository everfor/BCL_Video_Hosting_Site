var searchControllers = angular.module('searchControllers', []);

searchControllers.controller('searchCntrl', 
	[
		'$scope',
		function($scope) {
			$scope.message = "Hello World!";
		}
	]
);