/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var base_url = {};
base_url.api = 'http://project-demos.com/file-uploader/api/web/v1/';
base_url.upload = 'http://project-demos.com/file-uploader/common/upload/';


var fileManager = angular.module('fileManager', [
    'ngRoute',
    'mainControllers',
    'ngSanitize', 'ngFileUpload', 'ngAnimate', 'ui.bootstrap.contextMenu'

]);
//now we need to configure how partials are gonna run
fileManager.config(['$routeProvider', function ($routeProvider) {    //calling route provider service and pass along in the function which is just like $http

        $routeProvider.//handling service

                when('/home', {
                    templateUrl: 'partials/home.html',
                    controller: 'DirectoryController',
                    resolve: {
                        fileManager: function ($q, checkAuth) {
                            $q.defer();
                            return checkAuth.loggedin();
                        }
                    }

                }).
                when('/login', {
                    templateUrl: 'partials/login.html',
                    controller: 'RegisterController',
                    resolve: {
                        fileManager: function ($q, checkAuth) {
                            $q.defer();
                            return checkAuth.loggedin();
                        }
                    }
                }).
                otherwise({//when somebody visit the home page
                    redirectTo: '/login'

                });
        ;

    }]);
fileManager.run(function ($cookies, $rootScope, $http, $route) {

    /*Change the base url here*/
    $rootScope.base_url = 'http://localhost/yii-angular-file-manager/';

    if ($cookies.get('auth_token')) {
        //console.log("Successfully Getting Aut role status and ID");
        $rootScope.auth_token = $cookies.get('auth_token');
        $rootScope.keyword_auth_token = $rootScope.auth_token; //Need to store all of them 
        $rootScope.is_loggedin = true;

    }
});

/*Pre check user auth, if user is authorized to open page*/
fileManager.factory('checkAuth', function ($rootScope, $location) {
    return {
        loggedin: function () {
            if ($rootScope.is_loggedin === true) {
                $location.url('/home');
            }
            else
            {
                $location.url('/login'); //if user is not logged in and wanst to see signin page
            }
        }
    };
});