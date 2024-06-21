<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="middle_wrapper">
    <div class="col-left div-padd">
        <div class="container-fluid hide-on-sm no-padding"> 
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                    <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
                    <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner inner-height" role="listbox">

                    <div class="item active">
                        <img src="<?= base_url('asset/APTEL_files/banner7.jpg');?>" alt="...">
                        <div class="banner_caption">Welcome to Securities Appellate Tribunal</div>
                    </div>

                    <div class="item">
                        <img src="<?= base_url('asset/APTEL_files/banner7.jpg');?>" alt="..." >
                        <div class="banner_caption">Welcome to Securities  Appellate Tribunal</div>
                    </div>

                    <div class="item">
                        <img src="<?= base_url('asset/APTEL_files/banner3.jpg');?>" alt="..." >
                        <div class="banner_caption">Welcome to Securities  Appellate Tribunal</div>
                    </div>
                </div>
            </div>
        </div>
        
        
    </div>
    <div class="col-right">
     <div class="container_inner form_wrapper">
          <div class="responseMsg hide">
            <div class="alert alert-danger btn-xs" id='responseMsg'></div>
            <i class="fas fa-times-circle" style="position: absolute; top: 20px;"></i>
          </div>
        <?php 
              echo form_open(false,['id'=>'userLogin', 'autocomplete'=>'off']).
                    '<div class="form-group mbottom-none">'.
                    form_label('&nbsp;&nbsp;User Name','username',['class'=>'fa fa-user','style'=>'']).
                      form_input(['id'=>'loginId','placeholder'=>'Username',  'maxlength'=>'15','class'=>'form_field','pattern'=>'[A-Za-z0-9_]{6,50}','required'=>'true']).
                    '</div>
                    <div class="form-group mbottom-none">'.
                      form_label('&nbsp;&nbsp;Password','pwd',['class'=>'fa fa-lock','style'=>'']).
                      form_password(['id'=>'pwd','placeholder'=>'Password', 'class'=>'form_field','required'=>'true']).
                      '<div class="btn_viewPass" style="position: absolute; margin: 4px 0px 0px -27px; font-size: 20px; display: inline-block;"><i class="fa fa-eye" id="togglePwd" onclick="myFunction();"></i></div>
                    </div>
                    <div class="form-group imgcaptcha">
                      <div id="captcha_data" style="display: inline-block">'.
                            $captcha_data["image"].
                      '</div>
                        <a href="javascript:void(0);" id="refresh" style="position: absolute; margin: 5px 5px 0px; font-size: 19px; display: inline-block;">
                         <i class="fa fa-sync-alt " id="toggle-password"></i>
                        </a>                         
                      <div style="display: inline-block; float: right; width: 116px;">'.
                          form_input(['id'=>'skey','placeholder'=>'Enter Captcha','class'=>'form_field','required'=>'true','maxlength'=>'6']).
                      '</div>
                    </div>
                    <div class="form-group">'.
                      form_submit(['class'=>'btn btn-primary btn-lg btn-block','value'=>'Login','id'=>'login-btn','disabled'=>'true']).
                    '</div>
                    <div class="row no-margin">
                      <div class="col-md-6 no-gutter text-left">
                        <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#forgotpassModal">Forgot password?</a>
                      </div>
                    </div>'.
                    form_close();
        ?>
        </div>
    </div>
    
 <script>   
function myFunction() {
    var x = document.getElementById("pwd");
    if (x.type === "password") {
      x.type = "text";
      //alert('ccc');
      $('#togglePwd').removeClass('fa-eye').addClass('fa-eye-slash'); 
    } else {
      x.type = "password";
      $('#togglePwd').removeClass('fa-eye-slash').addClass('fa-eye'); 
    }
  }
   </script>    

</div>
<!--================= slider and Chairman message section end ===================== -->