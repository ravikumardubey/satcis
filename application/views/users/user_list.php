<?php $this->load->view("admin/header"); ?>
<?php $this->load->view("admin/sidebar"); ?>                           
    <div class="container margin-top-30" ng-app="my_app">
    
        <div ng-controller="users" data-ng-init="usersInformation()" class="container">
        
                                
           <div class="col-md-12">
                <div class="row add_panel">
                    <a ng-click="AddUserModal();" class="model_form btn btn-primary" style="margin-bottom: 18px;">
                        <i class="glyphicon glyphicon-plus"></i> Add User</a>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-12">
                <div ng-if="success_msg" class="success_pop alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong> {{success_msg}} </strong>
                </div>
                <div class="table-responsive">
                    <table datatable="ng" id="examples" class="table table-striped table-bordered" cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>User Name</th>
                                <th>Short Name</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>Menu Access</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="user in users_list">
                                <td>{{$index + 1}}</td>
                                <td>{{user.username}}</td>
                                <td>{{user.short_name}}</td>
                                <td>{{user.fname}} {{user.lname}}</td>
                                <td>{{user.email}}</td>
                                <td>{{user.email}}</td>
                                <td>
                                    <a href="javascript:void(0);" ng-click="menu_access(user.id);">
                                        <i class="glyphicon glyphicon-pencil">Menu Role</i>
                                    </a>
                                </td>
                                <td>
                                    <a href="javascript:void(0);" ng-click="EditModal(user);" id="user.phone_no">
                                        <i class="fa fa-user" style="margin-left: -4;"></i>
                                    </a> &nbsp;&nbsp;&nbsp;
                                    <a href="javascript:void(0);" ng-click="DeleteModal(user)" class="delete">
                                        <i class="fa fa-trash" style="margin-left: -10;"></i>
                                    </a> &nbsp;&nbsp;&nbsp;
				    				<a href="javascript:void(0);" ng-click="unclock(user.id)">
                                        <i class="fa fa-unlock"   style="margin-left: -1;"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>



            <!---- Menu Model --->

            <div id="menu_form_modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="icon-paragraph-justify2"></i>
                                Menu Access</h4>


                                <h2 id="update_div_msg" style="color:green"> </h2>
                        </div>
                        <!-- Form inside modal -->
                        <form method="post" id="menu_users_form_id">

                            <!-- <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                                <button type="submit" onclick="fn_menu_access_data()" class="btn btn-primary">Update
                                    Menu</button>
                            </div> -->
                            <div class="modal-body with-padding">
                                <input type="hidden" name="user_id" id="user_id" value="">
                                <div id="menu_access_id"> </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                            <button type="submit" onclick="fn_menu_access_data()" class="btn btn-primary">Update Menu</button>
                        </div> 
                        </form>
                    </div>
                </div>
            </div>


            <!-- End Menu Access -->



            <!-- Form modal -->
            <div id="form_modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="icon-paragraph-justify2"></i>
                                {{form_name}}</h4>
                        </div>
                        <!-- Form inside modal -->
                        <form method="post" ng-submit="UserAddUpdate(users_form);" id="users_form_id">
                            <div class="modal-body with-padding">
                                <input type="hidden" name="action_text" id="action_text"
                                    ng-model="users_form.action" value="update">
                                <input type="hidden" name="id" ng-model="users_form.id" id="id">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Short Name :</label>
                                            <input type="text" name="short_name" ng-model="users_form.short_name"
                                                id="short_name" required="required" class="form-control">
                                        </div>
                                        <div class="col-sm-6 org_name_div_id">
                                            <label>First Name :</label>
                                            <input type="text" name="fname" ng-model="users_form.fname" id="fname"
                                                required="required" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Last Name :</label>
                                            <input type="text" name="lname" ng-model="users_form.lname" id="lname"
                                                class="form-control">
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Email Id :</label>
                                            <input type="email" name="email" ng-model="users_form.email" id="email"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Mobile No:</label>
                                            <input type="tel" name="mobile_no" ng-model="users_form.mobile_no"
                                                id="mobile_no" required="required" class="form-control"  maxlength="10" value="" onkeypress="return isNumberKey(event)" >
                                        </div>
                                        <div class="col-sm-6">
                                            <label> Gender : </label>
                                            <input type="text" name="gender" ng-model="users_form.gender" id="gender"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label> Address :</label>
                                            <input type="text" name="address" ng-model="users_form.address" id="address"
                                                class="form-control">
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Country:</label>
                                            <input type="text" name="country" ng-model="users_form.country" id="country"
                                                required="required" class="form-control">
                                        </div>
                                    </div>
                                </div>
                          

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                                <button type="submit" name="form_data" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /form modal -->
            <!-- --------password change -->
                <!-- Form modal -->
            <div id="form_Password_modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="icon-paragraph-justify2"></i>
                                {{form_name}}</h4>
                        </div>
                        <!-- Form inside modal -->
                        <form method="post" ng-submit="ChangepasswordModal(users_form);" id="users_form_id">
                            <div class="modal-body with-padding">
                                <input type="hidden" name="action_password_text" id="action_password_text"   ng-model="users_form.action" value="password_update">
                                <input type="hidden" name="id" ng-model="users_form.id" id="id">  
                                  <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Short Name :</label>
                                            <input type="text" name="short_name" ng-model="users_form.short_name"   id="short_name" required="required" class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Password :</label>
                                            <input type="text" name="new_password"  ng-model="users_form.new_password"
                                                id="password" required="required" class="form-control">
                                        </div>
                                        <div class="col-sm-6 org_name_div_id">
                                            <label>Confirm Password :</label>
                                            <input type="text" name="con_password" ng-model="users_form.con_password"  id="con_password"
                                                required="required" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                                <button type="submit" name="form_data" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- --------End password -->
            
            
            
            
            
            
            
            
            
            
            
            
          <!-----Add User------->
            <div id="form_Adduser_modal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog">
                <div class="modal-dialog ">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"><i class="icon-paragraph-justify2"></i>
                                {{form_name}}</h4>
                        </div>
                          <!-- Form inside modal -->
                        <form method="post" ng-submit="AdduserModal(users_form);" id="users_form_id">
                            <div class="modal-body with-padding">
                                <input type="hidden" name="action_adduser_text" id="action_adduser_text"   ng-model="users_form.action" value="insert">
                                <input type="hidden" name="id" ng-model="users_form.id" id="id">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Short Name :</label>
                                            <input type="text" name="short_name" ng-model="users_form.short_name"
                                                id="short_name" required="required" class="form-control">
                                        </div>
                                        <div class="col-sm-6 org_name_div_id">
                                            <label>First Name :</label>
                                            <input type="text" name="fname" ng-model="users_form.fname" id="fname"
                                                required="required" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Last Name :</label>
                                            <input type="text" name="lname" ng-model="users_form.lname" id="lname"
                                                class="form-control">
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Email Id :</label>
                                            <input type="email" name="email" ng-model="users_form.email" id="email"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Mobile No:</label>
                                            <input type="tel" name="mobile_no" ng-model="users_form.mobile_no"
                                                id="mobile_no" required="required" class="form-control"  maxlength="10" value="" onkeypress="return isNumberKey(event)" >
                                        </div>
                                        <div class="col-sm-6">
                                            <label> Gender : </label>
                                            <input type="text" name="gender" ng-model="users_form.gender" id="gender"
                                                class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label> Address :</label>
                                            <input type="text" name="address" ng-model="users_form.address" id="address"
                                                class="form-control">
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Country:</label>
                                            <input type="text" name="country" ng-model="users_form.country" id="country"
                                                required="required" class="form-control">
                                        </div>
                                    </div>
                                </div>
                          

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
                                <button type="submit" name="form_data" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- --------End Add user -->
        </div>
    </div>



 <script>

	function isNumberKey(evt){
	    var charCode = (evt.which) ? evt.which : event.keyCode
	    if (charCode > 31 && (charCode < 48 || charCode > 57))
	        return false;
	    return true;
	}

	
    function fn_menu_access_data() {
        var user_id = $("#menu_users_form_id #user_id").val();
        var menu_arrary = {};
        $('input:checkbox[name="menu_name[]"]:checked').each(function(i) {
            var menu_id = $(this).val();
            var sun_menu_arr = {};
            $('input:checkbox[name="sub_menu_name_' + menu_id + '[]"]:checked').each(function(i) {
                var sub_menu_id = $(this).val();
                sun_menu_arr[i] = sub_menu_id;
            });
            menu_arrary[menu_id] = sun_menu_arr;
        });
        var sub_menu_arrary = {};
            var tem_arr = {};
        $('input:checkbox[name="sub_sub_menu_name_2[]"]:checked').each(function(i) {
            var sub_sub_menu_id = $(this).val();
           
                tem_arr[i] = sub_sub_menu_id;
        });
        sub_menu_arrary[2] = tem_arr;
        $.ajax({
            type: "POST",
            url: "update_menu/"+user_id,
            data: {
                menu: menu_arrary,
                sub_menu: sub_menu_arrary
            },
            success: function(data) {
               $("#update_div_msg").html(data);
               alert(data);
               location.reload(true);	
            },
            error: function(textStatus, errorThrown) {
                alert("error");
            }
        });
    }
    </script>
    
    <?php $this->load->view("admin/footer"); ?>