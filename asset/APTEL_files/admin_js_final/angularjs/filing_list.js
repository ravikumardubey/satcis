var myapp = angular.module('my_app', ['datatables']);
	alert("asdasd");
myapp.controller('users', function ($scope, $http) {
    $scope.master = {};
    $scope.usersInformation = function () {
        $http({
            method: 'GET',
            url: 'filing_ajax.php?action=filing_list'
        }).then(function (success) {
            $scope.users_list = [];
            $scope.users_list = success.data;
            $('.load_container').hide();
        }, function (error) {
            console.log(error);
        });
    };
    $scope.search_filing= function () {
        var dfr_no = $("#search_filing_data #search_dfr_no").val();
        var case_no = $("#search_filing_data #search_case_no").val();
        var year = $("#search_filing_data #search_year").val();
        var pet_name = $("#search_filing_data #search_pet_name").val();
        var res_name = $("#search_filing_data #search_res_name").val();
        var daterange = $("#search_filing_data #daterange").val();
        var data = {};
        data['action']='filing_list';
        data['dfr_no'] = dfr_no;
        data['case_no'] = case_no;
        data['year'] = year;
        var data_arr = 'action=filing_list&dfr_no=' + dfr_no + '&case_no=' + case_no + '&year=' + year+ '&pet_name=' + pet_name+ '&res_name=' + res_name +'&daterange='+daterange;
        $http({
            method: 'GET',
            url: 'filing_ajax.php?'+data_arr
        }).then(function (success) {
          $scope.users_list = [];
           $scope.users_list = success.data;
           $('.load_container').hide();
        }, function (error) {
           console.log(error);
        });
    };

    $scope.addModal = function () {
        $scope.users_form = angular.copy($scope.master);
        $scope.form_name = 'Add filing';
        $("#filing_form_id #action_text").val('insert');
        $('#form_modal').modal('show');
    };
    $scope.UserAddUpdate = function (users_form) {
        var users_information = users_form;
        var action_value = $("#filing_form_id #action_text").val();
        var pet_state = $("#filing_form_id #pet_state").val();
        var pet_district = $("#filing_form_id #pet_district").val();
        var res_state = $("#filing_form_id #res_state").val();
        var res_district = $("#filing_form_id #res_district").val()
        var pet_adv = $("#filing_form_id #pet_adv").val()
        var res_adv = $("#filing_form_id #res_adv").val();
        var data_arr = '&pet_state=' + pet_state + '&pet_district=' + pet_district + '&res_state=' + res_state + '&res_district=' + res_district + '&pet_adv=' + pet_adv + '&res_adv=' + res_adv;
		data_arr += '&pet_name=' + $("#pet_name").val();
        data_arr += '&pet_address=' + $("#pet_address").val();
        data_arr += '&pet_mobile=' + $("#pet_mobile").val();
        data_arr += '&pet_email=' +  $("#pet_email").val();
        data_arr += '&pet_phone=' +  $("#pet_phone").val();
        data_arr += '&pet_pin=' +  $("#pet_pin").val();
        data_arr += '&pet_fax=' +  $("#pet_fax").val();
        data_arr += '&pet_degingnation=' +  $("#pet_degingnation").val();
        data_arr += '&res_name=' +  $("#res_name").val();
        data_arr += '&res_address=' +  $("#res_address").val();
        data_arr += '&res_mobile=' +  $("#res_mobile").val();
        data_arr += '&res_email=' +   $("#res_email").val();
        data_arr += '&res_phone=' +   $("#res_phone").val();
        data_arr += '&res_pin=' +   $("#res_pin").val();
        data_arr += '&res_fax=' +  $("#res_fax").val();
        data_arr += '&res_degingnation=' +  $("#res_degingnation").val();
        data_arr += '&pet_counsel_address=' +  $("#filing_form_id #pet_counsel_address").val();
        data_arr += '&pet_counsel_mobile=' +  $("#filing_form_id #pet_counsel_mobile").val();
        data_arr += '&pet_counsel_email=' +  $("#filing_form_id #pet_counsel_email").val();
        data_arr += '&pet_counsel_phone=' +  $("#filing_form_id #pet_counsel_phone").val();
        data_arr += '&pet_counsel_pin=' +  $("#filing_form_id #pet_counsel_pin").val();
        data_arr += '&pet_counsel_fax=' + $("#filing_form_id #pet_counsel_fax").val();
        data_arr += '&pet_counsel_address=' + $("#filing_form_id #pet_counsel_address").val();
        data_arr += '&pet_counsel_mobile=' + $("#filing_form_id #pet_counsel_mobile").val();
        data_arr += '&pet_counsel_email=' + $("#filing_form_id #pet_counsel_email").val();
        data_arr += '&pet_counsel_phone=' + $("#filing_form_id #pet_counsel_phone").val();
        data_arr += '&pet_counsel_pin=' + $("#filing_form_id #pet_counsel_pin").val();
        data_arr += '&pet_counsel_fax=' +  $("#filing_form_id #pet_counsel_fax").val();
	  $http({
            method: 'POST',
            url: 'filing_ajax.php?action=' + action_value + data_arr,
            data: users_information,
        }).then(function (response) {
            $scope.usersInformation();
            $scope.success_msg = response.data;
        }, function (error) {
            console.log(error);
        });
        $('#form_modal').modal('hide');
    };

    $scope.additionla_party = function (user) {
        show_application_applicent('');
        $("#filin_gadditionla_party_form_id #filing_no").val(user.filing_no);
        $('#form_modal_additionla_party').modal('show');
        $scope.form_name = 'Add / Edit Additional Party';
    };


    $scope.additionla_advocate = function (user) {
        $("#filin_gadditionla_advocate_form_id #filing_no").val(user.filing_no);
        diary_change(1);
        $('#form_modal_additionla_advocate').modal('show');
        $scope.form_name = 'Add / Edit Additional Advocate';
    };

    $scope.document_filing = function (user) {
        $("#additionla_partyy").empty();
        $("#document_filing_form_id #filing_no").val(user.filing_no);
        $("#document_filing_form_id #filingOn").val(user.filing_no);
        $("#document_filing_div_id").show();
        $("#document_filing_div_id_text_print").empty();
        paperDetails();
        $('#form_modal_document_filing').modal('show');
        showparty('2','');
        $scope.form_name = 'Document Filing';
    };
    $scope.ia_details_filing = function (user) {
        $("#ia_details_filing_form_id #filing_no").val(user.filing_no);
        $('#form_modal_ia_filing').modal('show');
        $scope.form_name = 'IA Details';
    };
    
    $scope.review_petition_filing = function (user) {
        $("#ia_details_review_petition_filing #filing_no").val(user.filing_no);
        $("#div_id_applent_respodent").empty();
        $("#iapage").empty();
        $("#partyparityshow").empty();
        $("#payment_reviw_petition").empty();
        $("#filingaction11").empty();
        $("#main_div_main_reviw_petition_div").show();
        $("#main_div_main_reviw_petition").show();
        $("#ia_details_review_petition_filing #pet"). prop("checked", true);
        load_app_respodent('1');
        $('#form_modal_review_petition_filing').modal('show');
        $scope.form_name = 'Review Petition';
    };

    $scope.execution_petition_filing = function (user) {
        $("#execution_petition_filing_form_id #filing_no").val(user.filing_no);
        $("#div_id_applent_respodent_execution_petition").empty();
        $("#partyparityshow_execution_petition").empty();
        $("#payment_execution_petition").empty();
        $("#filingaction11_execution_petition").empty();
        $("#main_div_main_execution_petition_div").show();
        $("#main_div_main_execution_petition").show();
        $("#execution_petition_filing_form_id #pet"). prop("checked", true);
        load_app_respodent_execution_petition('1');
        $('#form_modal_execution_petition_filing').modal('show');
        $scope.form_name = 'Execution Petition';
    };

    $scope.contempt_petition_filing = function (user) {
        $("#contempt_petition_filing_form_id #filing_no").val(user.filing_no);
        $("#div_id_applent_respodent_contempt_petition").empty();
        $("#partyparityshow_econtempt_petition").empty();
        $("#payment_contempt_petition").empty();
        $("#filingaction11_contempt_petition").empty();
        $("#main_div_main_econtempt_petition_div").show();
        $("#main_div_main_contempt_petition").show();
        $("#econtempt_petition_filing_form_id #pet"). prop("checked", true);
        load_app_respodent_contempt_petition('1');
        $('#form_modal_contempt_petition_filing').modal('show');
        $scope.form_name = 'Contempt Petition';
    };

    $scope.EditModal = function (user) {
   
        console.log(user);
        loadDistict(user.pet_state, user.pet_district);
        loadDistict_res(user.res_state, user.res_district);
        $scope.form_name = 'Edit filing';
        var edit_form = {};
        angular.copy(user, edit_form);
        $scope.users_form = edit_form;
        $("#filing_form_id #action_text").val('update');
        $("#filing_form_id #pet_state").val(user.pet_state);
        $("#filing_form_id #pet_district").val(user.pet_district);
        $("#filing_form_id #pet_adv").val(user.pet_adv);
        $("#filing_form_id #res_adv").val(user.res_adv);
        $("#filing_form_id #res_state").val(user.res_state);
        $("#filing_form_id #res_district").val(user.res_district);
        $('#form_modal').modal('show');
    };

    $scope.get_city_name = function (state_id) {
        var state_id = $("#filing_form_id #state").val();
        if (state_id != '') {
            $http({
                method: 'POST',
                url: 'filing_ajax.php?action=city_list',
                data: state_id,
            }).then(function (response) {
                $("#district").text(response.data);
            }, function (error) {
                console.log(error);
            });
        }
    };


    $scope.DeleteModal = function (user) {
        var r = confirm("Are you sure want to delete ?");
        if (r == true) {
            var users_record_id = user.adv_code;
            $http({
                method: 'POST',
                url: 'filing_ajax.php?action=delete',
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

    $scope.loadstate = function (statew_ss) {
        $http({
            method: 'GET',
            url: 'load_state.php'
        }).then(function (success) {
            $scope.state_list = [];
            $scope.state_list = success.data;
        }, function (error) {
            console.log(error);
        });
    };
});