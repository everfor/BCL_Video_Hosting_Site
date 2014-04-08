var main = angular.module('main', 
    [
        'ngRoute'
    ]
);

main.config(
    [
        '$routeProvider',
        '$locationProvider',
        function($routeProvider, $locationProvider) {
            // DONT forget to remove '/video' part in urls
            // This is only used in Ubuntu test environment
            $routeProvider
                .when('/video/search', 
                    {
                        templateUrl: 'templates/searchResults.html',
                        controller: 'searchCntrl', 
                    }
                )
                .when('/video/search/:term',
                    {
                        templateUrl: 'templates/searchResults.html',
                        controller: 'searchCntrl', 
                    }
                )
                .when('/video/login',
                    {
                        templateUrl: 'templates/loginForm.html',
                        controller: 'loginCntrl', 
                    }
                )
                .when('/video/register',
                    {
                        templateUrl: 'templates/registrationForm.html',
                        controller: 'regCntrl',
                    }
                )
                .when('/video/watch',
                    // It is just a testing template now
                    {
                        templateUrl: 'templates/watchVideo.html',
                    }
                )
                .otherwise(
                    {
                        templateUrl: 'templates/index.html',
                    }
                );

            if (window.history && window.history.pushState) {
                $locationProvider.html5Mode(true);
            }
        }
    ]
);