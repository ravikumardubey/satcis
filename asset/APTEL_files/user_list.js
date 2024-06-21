var myapp = angular.module('my_app', ['datatables']);
myapp.controller('users', function ($scope, $http) {
    $scope.master = {};
    $scope.usersInformation = function () {
        $http({
            method: 'GET',
            url: 'organization_list'
        })
            .then(function (success) {
                $scope.users_list = [];
                $scope.users_list = success.data;
            }, function (error) {
                console.log(error);
            });
    };
    $scope.addModal = function () {
        $scope.users_form = angular.copy($scope.master);
        $scope.form_name = 'Add Users';
        $("#users_form_id #action_text").val('insert');
        $('#form_modal').modal('show');
    };


    $scope.menu_access = function (user_id) {
        $("#menu_users_form_id #user_id").val(user_id);
        $http({
            method: 'POST',
            url: 'menu_access/'+user_id,
            data: '',
        }).then(function (response) {
            console.log(response.data);
            $("#menu_access_id").html(response.data);
           // $scope.success_msg = response.data;
        }, function (error) {
            console.log(error);
        });
        $('#menu_form_modal').modal('show');
    };

    $scope.EditModal = function (user) {
        $scope.form_name = 'Edit Users';
        var edit_form = {};
        angular.copy(user, edit_form);
        $scope.users_form = edit_form;
        $('#form_modal').modal('show');
    };
    


    $scope.unclock = function (user_id) {
    	  $http({
              method: 'POST',
              url: 'unlockuser/'+user_id,
              data: '',
          }).then(function (response) {
              console.log(response.data);
              $scope.success_msg = response.data;
          }, function (error) {
              console.log(error);
          });
    };
    
    

    $scope.UserAddUpdate = function (users_form) {
        var users_information = users_form;
        var action_value = $("#users_form_id #action_text").val();
        $http({
            method: 'POST',
            url: 'userupdate',
            data: users_information,
        }).then(function (response) {
            $scope.usersInformation();
            $scope.success_msg = response.data;
        }, function (error) {
            console.log(error);
        });
        $('#form_modal').modal('hide');
    };




    $scope.DeleteModal = function (user) {
        var r = confirm("Are you sure want to delete ?");
        if (r == true) {
            var users_record_id = user.id;
            $http({
                method: 'POST',
                url: 'deleteUser',
                data: users_record_id,
            }).then(function (response) {
                var index = $scope.users_list.indexOf(user);
                $scope.users_list.splice(index, 1);
                $scope.success_msg = response.data;
            }, function (error) {
                console.log(error);
            });
        }
    };
    
    
    
    
    
    $scope.EditPasswordModal = function (user) {
        $scope.form_name = 'Edit User Password';
        var edit_form = {};
        angular.copy(user, edit_form);
        $scope.users_form = edit_form;
        $('#form_Password_modal').modal('show');
    };
    
    
    $scope.ChangepasswordModal = function (users_form) {
        var users_information = users_form;
        var action_value = $("#users_form_id #action_password_text").val();
        $http({
            method: 'POST',
            url: 'chnagepassword',
            data: users_information,
        }).then(function (response) {
            $scope.usersInformation();
            $scope.success_msg = response.data;
        }, function (error) {
            console.log(error);
        });
        $('#form_Password_modal').modal('hide');
    };
    
    
    
    $scope.AddUserModal = function (user) {
        $scope.form_name = 'Add User';
        var edit_form = {};
        angular.copy(user, edit_form);
        $scope.users_form = edit_form;
        $('#form_Adduser_modal').modal('show');
    };
    
    
    $scope.AdduserModal = function (users_form) {
        var users_information = users_form;
        var action_value = $("#users_form_id #action_adduser_text").val();
        $http({
            method: 'POST',
            url: 'adduser/'+action_value,
            data: users_information,
        }).then(function (response) {
            $scope.usersInformation();
            $scope.success_msg = response.data;
        }, function (error) {
            console.log(error);
        });
        $('#form_Password_modal').modal('hide');
        ///form_Adduser_modal
    };
    


});

