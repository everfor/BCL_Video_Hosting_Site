var main = angular.module('main');

main.controller('loginCntrl',
    [
        '$scope',
        '$http',
        function($scope, $http) {
            
            $scope.submit = function() {
                if ($scope.username && $scope.password) {
                    
                    var userInfo = {
                        username    : $scope.username,
                        password    : $scope.password
                    };

                    $http({method: 'POST', url: 'api/login', data: userInfo})
                        .success(function(data) {
                            $scope.message = 'yooooloooo!';
                            console.log(data);
                            // TODO: parse the login data
                        });
                }
            }

            $scope.message = '';
        }
    ]
);

main.controller('regCntrl',
    [
        '$scope',
        '$http',
        function($scope, $http) {
            
            $scope.submit = function() {
                if ($scope.username && $scope.password && $scope.email) {
                    var userInfo = {
                        username    : $scope.username,
                        password    : $scope.password,
                        email       : $scope.email
                    };
                    $http({method: 'POST', url: 'api/register', data: userInfo})
                        .success(function(data) {
                            $scope.message = 'That\'s amzing eh!';
                            console.log(data);
                            // TODO: parse the registration data
                        });
                }
            }

            $scope.message = '';
        }
    ]
);
