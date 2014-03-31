angular.module('main').controller('searchCntrl', 
	[
		'$scope',
		'$http',
		'$routeParams',
		function($scope, $http, $routeParams) {
			if ($routeParams.term) {
				$scope.searchTerm = $routeParams.term;
				// JQuery
				(function($) {
					$('#searchTerm').val($scope.searchTerm);
				})(jQuery);
			}
			
			// Message is only used for testing purposes
			$scope.message = "Hello World!";
			
			$scope.fetch = function($update) {
				// Model $scope.searchTerm is binded with the input text
				// Will implement search by category as well
				$data = { keyword: $scope.searchTerm };
				$http({method: 'POST', url: 'api/search/video/keyword', data: data})
					.sccuess(function(response) {
						if (response.success === 'true') {
							$scope.searchResult = response.result;
						}
					});
			}
		}
	]
);