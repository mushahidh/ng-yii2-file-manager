/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var mainControllers = angular.module('mainControllers', ['ngCookies', 'ui.bootstrap', 'angular-loading-bar', 'angular.filter']);
mainControllers.controller('RegisterController', ['$scope', '$http', '$routeParams', '$location', '$cookies', '$rootScope', function ($scope, $http, $routeParams, $location, $cookies, $rootScope) {

        /*Login */
        $scope.loginData = {};
        $scope.login = function () {
            $http({
                method: 'POST',
                url: $rootScope.base_url + 'api/web/v1/user/login',
                data: $.param($scope.loginData),
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            })
                    .success(function (data) {
                        $cookies.put('auth_token', data.access_token);
                        $rootScope.auth_token = $cookies.get('auth_token');
                        console.log(data);
                        if ($cookies.get('auth_token')) {
                            $rootScope.keyword_auth_token = $rootScope.auth_token;
                            $rootScope.is_loggedin = true;
                            $location.path('/home');

                        }
                    })
                    .error(function (data) {
                        if (data) {
                            $scope.error_response_text = data.password;
                            console.log($scope.error_response_text);
                        }
                    });
        };
        /* Login ends here */


    }]);

mainControllers.controller('DirectoryController', ['$scope', '$http', '$routeParams', '$location', '$cookies', '$rootScope', '$uibModal', '$log', '$document', '$route', 'Upload', '$timeout', function ($scope, $http, $routeParams, $location, $cookies, $rootScope, $uibModal, $log, $document, $route, Upload, $timeout) {

        $scope.folders_name = [];
        $scope.files_name = [];
        $scope.back_button = [];
        $scope.new_folder = [];
        /* Back */
        $scope.next = function (folders)
        {
            $scope.back_button.push(folders); //This array is used in upload image as foldename
            var last_index_for_upload_file = $scope.back_button.length - 1; // get the previous item
            $scope.last_index_upload = $scope.back_button[last_index_for_upload_file]; // use the previous item index
            console.log($scope.last_index_upload);
        };
        /*Back ends here*/

        /*Create folder modal */

        $scope.create_folder_modal = function () {
            $('#add_folder').modal('toggle');
        };
        /*Create folder modal ends here*/

        $scope.upload_files_modal = function () {
            $('#upload_files').modal('toggle');
        };
        /*Upload Files*/


        /* Upload files ends here */


        /* Root directory  */

        $scope.root = function () {
            $http({
                url: $rootScope.base_url + 'api/web/v1/software/list-directory',
                method: "GET",
                params: {'access-token': $rootScope.keyword_auth_token}
            })
                    .success(function (data) {
                        $scope.folders_name = [];
                        $scope.files_name = [];
                        str_files = "";
                        splitted_str_files = "";
                        console.log(data);
                        for (var i = data.current_directory.length - 1; i >= 0; i--) { //Loop for folders
                            var str = data.current_directory[i];
                            splitted_str = "";
                            splitted_str_files = "";
                            splitted_str = str.split('/');
                            var last_item = splitted_str.length - 1;
                            //console.log(splitted_str[last_item]);
                            $scope.folders_name.push(splitted_str[last_item]); // As I know the folder name is coming in the 7 index so storing it
                            //console.log($scope.folders_name);

                        }
                        for (var i = data.files.length - 1; i >= 0; i--) { //Loop for files
                            var str_files = data.files[i];
                            splitted_str_files = str_files.split('/');
                            var last_item_files = splitted_str_files.length - 1;
                            $scope.files_name.push(splitted_str_files[last_item_files]); // As I know the folder name is coming in the 7 index so storing it
                            //console.log($scope.files_name);
                        }

                    })
                    .error(function (data) {
                        if (data) {
                            //console.log(data);
                        }
                    }
                    );
        };
        $scope.root();
        /* Root directory ends here */

        /* Sub Folders */

        $scope.sub_folders = function (subfolder) {
            $http({
                url: $rootScope.base_url + 'api/web/v1/software/list-directory?foldername=' + subfolder,
                method: "GET",
                params: {'access-token': $rootScope.keyword_auth_token}
            })
                    .success(function (data) {
//                        console.log(data);
                        $scope.folders_name = [];
                        $scope.files_name = [];
                        str_files = "";
                        splitted_str_files = "";
                        for (var i = data.current_directory.length - 1; i >= 0; i--) {
//                            console.log(data.current_directory);
                            var str = data.current_directory[i];
                            var splitted_str = "";
                            splitted_str = str.split('/upload/');
//                            console.log(splitted_str);
                            $scope.folders_name.push(splitted_str[1]); // As I know the folder name is coming in the 7 index so storing it
//                            console.log($scope.folders_name);
                        }
                        for (var i = data.files.length - 1; i >= 0; i--) { //Loop for files
                            var str_files = data.files[i];
                            splitted_str_files = str_files.split('/');
                            var last_item_files = splitted_str_files.length - 1;
                            $scope.files_name.push(splitted_str_files[last_item_files]); // As I know the folder name is coming in the 7 index so storing it
                            //console.log($scope.files_name);
                        }

                    })
                    .error(function (data) {
                        if (data) {
                            console.log(data);
                        }
                    });
        };
        /* Sub Folders ends here*/

        /* Back */

        $scope.back = function (folders)
        {
            if (($scope.back_button.length - 2) > -1)
            {
                var folder_name = $scope.back_button[$scope.back_button.length - 2];
                $scope.back_button.pop();
                $scope.sub_folders(folder_name);
            }
            else
            {
                $scope.back_button = [];
                $scope.root();
                $scope.last_index_upload = "";
                console.log($scope.last_index_upload);
            }
        };
        /*Back ends here*/

        /* Create a New folder*/

        $scope.folderData = {};
        $scope.create_folder = function () {
            console.log($scope.last_index_upload);
            console.log($scope.folderData);
            if ($scope.last_index_upload)
            {
                $scope.folderData.foldername = $scope.last_index_upload + '/' + $scope.folderData.foldername;
                console.log($scope.folderData.foldername);
            }
            $http({
                method: 'POST',
                url: $rootScope.base_url + 'api/web/v1/software/create-directory?access-token=' + $rootScope.keyword_auth_token + '&foldername=' + $scope.folderData.foldername

            })
                    .success(function (data) {
                        $('#add_folder').modal('hide');
                        $scope.folderData = [];
                        if (($scope.back_button.length - 1) > -1)
                        {
                            $scope.sub_folders($scope.back_button[$scope.back_button.length - 1]);
                            $scope.upload_type = []; //empty the foldername as if root comes nothing goes in foldername
                        }
                        else
                        {
                            $scope.root();
                        }

                    }).error(function (data) {
                console.log(data);
            });
        };
        /*Create a new folder ends here*/

        /* Modal folder starts here */

        var ctrl = this;
        ctrl.animationsEnabled = true;
        ctrl.open = function (size, parentSelector) {
            var parentElem = parentSelector ?
                    angular.element($document[0].querySelector('.modal-demo ' + parentSelector)) : undefined;
            var modalInstance = $uibModal.open({
                animation: ctrl.animationsEnabled,
                ariaLabelledBy: 'modal-title',
                ariaDescribedBy: 'modal-body',
                templateUrl: 'myModalContent.html',
                controller: 'FolderCtrl',
                controllerAs: 'ctrl',
                size: size,
                appendTo: parentElem
//                resolve: {
//                    filters: function () {
//                        return  $scope.filters; //passing data to modal controller
//
//                    }
//                }

            });
            modalInstance.result.then(function () {

            }, function () {
                $log.info('Modal dismissed at: ' + new Date());
            });
        };
        ctrl.toggleAnimation = function () {
            ctrl.animationsEnabled = !ctrl.animationsEnabled;
        };
        /* Modal ends here */

        /*Upload files*/


        //      $scope.upload_type = {}; //declaring object type array
//        $scope.uploadFiles = function (file, errFiles) {
//            $scope.fileselected = true;
//            $scope.f = file;
//            $scope.errFile = errFiles && errFiles[0];
//            $scope.upload_type.data = file; //object {profilepic : file} will be the object
//            console.log( $scope.upload_type)
//            if ($scope.last_index_upload)
//                $scope.upload_type.foldername = $scope.last_index_upload + '/'; //static
//
//            if (file) {
//                file.upload = Upload.upload({
//                    url: '$rootScope.base_url + /api/web/v1/upload/upload',
//                    params: {'access-token': $rootScope.keyword_auth_token},
//                    data: $scope.upload_type
//                });
//                file.upload.then(function (response) {
//                    $timeout(function () {
//                        console.log(response);
//
//                        if (($scope.back_button.length - 1) > -1)
//                            $scope.sub_folders($scope.back_button[$scope.back_button.length - 1]);
//                        else
//                        {
//                            $scope.root();
//                        }
//                    });
//                }, function (response) {
//                    if (response.status > 0)
//                        $scope.errorMsg = response.status + ': ' + response.data;
//                }, function (evt) {
//                    file.progress = Math.min(100, parseInt(100.0 *
//                            evt.loaded / evt.total));
//                });
//            }
//        };
//
//

        $scope.fileData = {};
        $scope.upload_type = {}; //declaring object type array
        $scope.upload_type.click = "";

        /*Pre check for button click*/
        /*A check, if upload files are not uploaded and upload button is clicked then check it and then call upload files button*/
        $scope.button_click = function ()
        {
            console.log($scope.upload_type);
            if ($scope.upload_type.data && $scope.upload_type.data.length && $scope.upload_type.softwarename && $scope.upload_type.version)
                $scope.upload_type.click = 1;
            console.log($scope.upload_type.click);
            $scope.uploadFiles();

        };
        /*button click ends here*/

        $scope.uploadFiles = function (files) {
            if (files)    // As $scope.upload_type.data was become empty if click is not = 1 and upload files were uploaded and submit button is pressed
                $scope.upload_type.data = files;
            console.log($scope.upload_type.click);
            $scope.upload_type.foldername = $scope.last_index_upload;
            if ($scope.last_index_upload)
                $scope.upload_type.foldername = $scope.last_index_upload + '/'; //sending the fodler name
            console.log($scope.upload_type);
            if (!$scope.upload_type.softwarename && !$scope.upload_type.version)
                alert("Please fill out the version and software name filed");
            if ($scope.upload_type.data && $scope.upload_type.data.length && $scope.upload_type.softwarename && $scope.upload_type.version && $scope.upload_type.click) {
                Upload.upload({
                    url: $rootScope.base_url + 'api/web/v1/upload/upload',
                    data: $scope.upload_type,
                    headers: {'Authorization': 'Bearer' + ' ' + $rootScope.keyword_auth_token}
                    }).then(function (response) {
                        $timeout(function () {
                            console.log(response);
                            $('#upload_files').modal('hide');
                            if (($scope.back_button.length - 1) > -1)
                            {
                                $scope.sub_folders($scope.back_button[$scope.back_button.length - 1]);
                                $scope.upload_type = {}; //empty the foldername as if root comes nothing goes in foldername
                                console.log($scope.upload_type);
                                $scope.progress = 0;
                                console.log($scope.progress);
                            }
                            else
                            {
                                $scope.root();
                                $scope.upload_type = {}; //empty the foldername as if root comes nothing goes in foldername
                                console.log($scope.upload_type);
                                $scope.progress = 0;
                                console.log($scope.progress);

                            }
                        });
                    }, function (response) {
                        if (response.status > 0) {
                            $scope.errorMsg = response.status + ': ' + response.data;
                        }
                    }, function (evt) {
                        $scope.progress =
                                Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
                    });
                }
            };
            /*Upload files ends here*/

            $scope.selected = 'None';
            $scope.menuOptions = [];


            window.oncontextmenu = function (e)
            {
                $('document').removeClass('context');
                $('.dropdown-menu').remove();
            }
            $(document).on('click', function (e) {
                //console.log($(this));
                if ($(this).parent().hasClass('context')) {
                    //console.log('menu opened');
                }
                else {
                    $('a').removeClass('context');
                    $('.dropdown-menu').remove();
                }
            });



            $scope.menuOptions = [
                ['Download', function ($itemScope, $event, modelValue, text, $li) {
                        $scope.selected = $itemScope.files;
                        //console.log(base_url.upload+$scope.selected);
                        setTimeout(function () {
                            window.open(
                                    base_url.upload + $scope.selected,
                                    '_blank' // <- This is what makes it open in a new window.
                                    );
                            //document.getElementById("download").href = base_url.upload+$scope.selected;
                        }, 400);
                    }],
                null, // Dividier
                        /*
                         ['Delete', function ($itemScope, $event, modelValue, text, $li) {
                         console.log($itemScope);
                         //$('#delet_file').modal('toggle');
                         $scope.files_name.splice($itemScope.$index, 1);
                         }]*/
            ];

            /*
             $scope.menuOptions = function (item) {
             console.log(item);
             //if (item.name == 'John') { return []; }
             return [
             [function ($itemScope) {
             return $itemScope.item;
             }, function ($itemScope) {
             // Action
             }]
             ];
             };
             */

        }]);
    angular.module('mainControllers').controller('FolderCtrl', function ($scope, $uibModalInstance) {
        var ctrl = this;
        ctrl.ok = function () {
            $uibModalInstance.close();
        };
        ctrl.cancel = function () {
            $uibModalInstance.dismiss('cancel');
        };
    });
    fileManager.filter('replaceName', function () {

        return function (text) {
            var ar = text.split("\\");   //want to split a string after back slash in javascript 
            return ar[ar.length - 1];
        };
    });