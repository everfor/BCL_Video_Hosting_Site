angular.module('main').controller('searchCntrl', 
	[
		'$scope',
		'$routeParams',
		function($scope, $routeParams) {
			if ($routeParams.term) {
				$scope.searchTerm = $routeParams.term;
				// JQuery
				(function($) {
					$('#searchTerm').val($scope.searchTerm);
				})(jQuery);
			}
			$scope.message = "Hello World!";
			$scope.submit = function() {
				// TODO: Implement submit functions
			}
		}
	]
);