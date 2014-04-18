var main = angular.module('main');

main.controller('logoutCntrl',
    [
        '$scope',
        '$http',
        function($scope, $http) {

            $scope.logout = function() {
                $http({method: 'POST', url: '/api/logout'})
                    .success(function(data) {
                        // Reload page
                        window.location.reload();
                    });
            }
        }
    ]
);

main.controller('loginCntrl',
    [
        '$scope',
        '$http',
        function($scope, $http) {
            
            $scope.submit = function() {
                if ($scope.username && $scope.password) {
                    $scope.autologin = ($scope.autologin === true) ? true : false;
                    
                    var userInfo = {
                        username    : $scope.username,
                        password    : $scope.password,
                        rememberme  : $scope.autologin
                    };

                    $http({method: 'POST', url: '/api/login', data: userInfo})
                        .success(function(data) {
                            $scope.message = 'yooooloooo!';
                            console.log(data);
                            // TODO: parse the login data

                            // Reload page
                            window.location.replace('/');
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
                    $http({method: 'POST', url: '/api/register', data: userInfo})
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
