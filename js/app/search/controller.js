var searchApp = angular.module('searchApp', []);

searchApp.controller('searchController', ['$scope', 
	function($scope){
		$scope.results = [
			{
				'id': 1,
				'name': "Hello"
			},
			{
				'id': 2,
				'name': "World"
			},
			{
				'id': 3,
				'name': "Yooooloooo"
			}
		];

		$scope.username = "Alan";
}]);

angular.bootstrap(document.getElementsByClassName("header")[0], ['searchApp']);