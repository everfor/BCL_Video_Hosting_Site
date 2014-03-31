angular.module('main').controller('searchCntrl', 
    [
        '$scope',
        '$http',
        '$routeParams',
        function($scope, $http, $routeParams) {
            // Always remember that member function definitions goes before
            // The real constructor
            $scope.fetch = function() {
                // Model $scope.searchTerm is binded with the input text
                // Will implement search by category as well
                var data = { keyword: $scope.searchTerm };
                $http({method: 'POST', url: 'api/search/video/keyword', data: data})
                    .success(function(response) {
                        if (response.success === 'true') {
                            $scope.searchResult = response.result;
                        }
                    });
            }

            if ($routeParams.term) {
                $scope.searchTerm = $routeParams.term;
                // JQuery
                (function($) {
                    $('#searchTerm').val($scope.searchTerm);
                })(jQuery);

                $scope.fetch();
            }
            
            // Message is only used for testing purposes
            $scope.message = "Hello World!";
        }
    ]
);