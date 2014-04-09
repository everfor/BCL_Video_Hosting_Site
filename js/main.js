var main = angular.module('main', 
    [
        'ngRoute',
        'ngSanitize'
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
                .when('/search', 
                    {
                        templateUrl: '/templates/searchResults.html',
                        controller: 'searchCntrl', 
                    }
                )
                .when('/search/:term',
                    {
                        templateUrl: '/templates/searchResults.html',
                        controller: 'searchCntrl', 
                    }
                )
                .when('/login',
                    {
                        templateUrl: '/templates/loginForm.html',
                        controller: 'loginCntrl', 
                    }
                )
                .when('/register',
                    {
                        templateUrl: '/templates/registrationForm.html',
                        controller: 'regCntrl',
                    }
                )
                .when('/watch',
                    // It is just a testing template now
                    {
                        templateUrl: '/templates/watchVideo.html'
                    }
                )
                .when('/watch/:id',
                    // It is just a testing template now
                    {
                        templateUrl: '/templates/watchVideo.html',
                        controller: 'watchCntrl',
                    }
                )
                .otherwise(
                    {
                        templateUrl: '/templates/index.html',
                    }
                );

            if (window.history && window.history.pushState) {
                $locationProvider.html5Mode(true);
            }
        }
    ]
);