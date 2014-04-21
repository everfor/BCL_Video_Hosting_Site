angular.module('main').controller('watchCntrl', 
    [
        '$scope',
        '$sce',
        '$http',
        '$routeParams',
        function($scope, $sce, $http, $routeParams) {
            // Make Angular trust external source
            // When passing a url for player
            $scope.trustSrc = function(src) {
                return $sce.trustAsResourceUrl(src);
            }

            $scope.fetch = function() {
                if ($scope.videoId) {
                    var data = { id : $scope.videoId };
                    $http({method: 'POST', url: '/api/search/video/id', data: data})
                        .success(function(response) {
                            if (response.success) {
                                $scope.videoResult = response.result;

                                $scope.videoPlayer = 'http://player.vimeo.com/video/' 
                                                    + $scope.videoResult.vimeo_id;

                                $scope.recommendations = response.recommendations;
                            }
                        });
                }
            }

            // Constructor
            if ($routeParams.id) {
                // fecth a video by id
                $scope.videoId = $routeParams.id;
                $scope.fetch();
            }
        }
    ]
);