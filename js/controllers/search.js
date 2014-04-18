angular.module('main').controller('searchCntrl', 
    [
        '$scope',
        '$rootScope',
        '$http',
        '$location',
        '$routeParams',
        function($scope, $rootScope, $http, $location, $routeParams) {
            // Always remember that member function definitions goes before
            // The real constructor

            // Fetch the video from url
            $scope.fetch = function() {
                // Model $scope.searchTerm is binded with the input text
                // Will implement search by category as well
                if ($scope.searchTerm) {
                    var data = { keyword: $scope.searchTerm };
                    // The front slash in the request url is super important
                    $http({method: 'POST', url: '/api/search/video/keyword', data: data})
                        .success(function(response) {
                            // Used for debugging. Will remove later
                            console.log(response);
                            if (response.success) {
                                $scope.errorInfo = null;
                                $scope.searchResult = response.result;
                            } else {
                                $scope.errorInfo = response.message;
                            }
                        });
                }
            }

            // Whenever user submits the searching
            // Navigate to /search/ + keywords to do the real searching
            $scope.search = function() {
                if ($scope.searchTerm) {
                    // When the broswer is not in '/search' subdomain
                    // AngularJS has a bug where the page is not reloaded
                    // Therefore I had to use the low-level js way to force reload
                    // $location.path('/search/' + encodeURIComponent($scope.searchTerm));
                    window.location.replace('/search/' + encodeURIComponent($scope.searchTerm));
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