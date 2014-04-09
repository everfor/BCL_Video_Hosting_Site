angular.module('main').controller('searchCntrl', 
    [
        '$scope',
        '$http',
        '$location',
        '$routeParams',
        function($scope, $http, $location, $routeParams) {
            // Always remember that member function definitions goes before
            // The real constructor

            // Fetch the video from url
            $scope.fetch = function() {
                // Model $scope.searchTerm is binded with the input text
                // Will implement search by category as well
                if ($scope.searchTerm) {
                    var data = { keyword: $scope.searchTerm };
                    $http({method: 'POST', url: 'api/search/video/keyword', data: data})
                        .success(function(response) {
                            // Used for debugging. Will remove later
                            console.log(response);
                            if (response.success) {
                                $scope.searchResult = response.result;
                            }
                        });
                }
            }

            // Whenever user submits the searching
            // Navigate to /search/ + keywords to do the real searching
            $scope.search = function() {
                if ($scope.searchTerm) {
                    $location.url('/search/' + encodeURIComponent($scope.searchTerm));
                }
            }
            
            // Constructor
            if ($routeParams.term) {
                // Search for videos using keywords from url
                $scope.searchTerm = $routeParams.term;
                $scope.fetch();
            }

            // JQuery
            (function($) {
                $('#searchTerm').val($scope.searchTerm);
            })(jQuery);
            // Message is only used for testing purposes
            $scope.message = "Hello World!";
        }
        
    ]
);