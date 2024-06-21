<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
.autosuggest {
    list-style: none;
    margin: 0;
    padding: 0;
    position: absolute;
    left: 15px;
    top: 57px;
    z-index: 1;
    background: #fff;
    width: 94%;
    box-shadow: 0px 5px 5px rgba(0, 0, 0, 0.2);
    overflow-y: auto;
    max-height: 280px;
}
.autosuggest li {
    padding: 8px 10px;
    font-size: 13px;
    color: #26c0d9;
    cursor: pointer;
}
.autosuggest li:hover {
    background: #f5f5f5;
}
#captcha_data img {
    width: 103px;
    height: 34px;
}
.carousel-inner>.item>a>img, .carousel-inner>.item>img, .img-responsive, .thumbnail a>img, .thumbnail>img {
    width: 100%;
}
</style>


<!-- Disclaimer --> 
<div class="modal fade modal_ngt" id="disclaimer" role="dialog">
  <div class="modal-dialog modal-dialog-centered" style="min-width: 800px;">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Want to Continue with Securities  Appellate Tribunal (SAT)</h4>
        <button type="button" class="close notagree">&times;</button>
      </div>
      <div class="modal-body">
        <div class="heading">Disclaimer</div>
        <div class="text-right" style="padding: 8px; background: #fff; border-radius: 5px; margin-bottom: 12px;"><a href="#" target="new_1" class="fa fa-eye text-danger">&nbsp;&nbsp;Disclaimer</a></div>
        <div class="container_inner">
            <p>I have read the contents of the site and the instructions given thereof as regards registration and e-filing of
             petitions/documents before the SAT  and agree with the same. I hereby declare that the information given in
              the petition/documents are true and correct to the best of my knowledge. I hereby acknowledge and certify that the 
              attachments/enclosures/appendix made along with the petitions/application are true and correct and are valid as per the original documents. 
              I further certify that I have personally or through my counsel/advocate completed the petitions/application and have e-filed the same.
               I understand that any misrepresentation, falsification or omission of information in the petition /application or any document used for
                registration shall be a valid ground for rejection of the petition/application apart from any other penalty for perjury.</p>
        </div>
            <div class="heading" style="margin-top: 12px; border-radius: 8px;">
              <?= form_input(['id'=>'lurole','disabled'=>'true','type'=>'hidden']).
                  form_label('Click to Agree','agree',['class'=>'form-control','style'=>'padding-left: 36px;']).
                  form_checkbox(['id'=>'agree','checked'=>false,'style'=>'position: absolute; margin: -29px 18px;']); 
              ?>
            </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Login -->
<div class="modal fade modal_ngt" id="loginModal" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><i class="fa fa-unlock"></i>&nbsp;&nbsp;e-Filing Login</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="container_inner">
          <div class="responseMsg hide">
            <div class="alert alert-danger btn-xs" id='responseMsg'></div>
            <i class="fas fa-times-circle" style="position: absolute; top: 20px;"></i>
          </div>
        <?php 
              echo form_open(false,['id'=>'userLogin', 'autocomplete'=>'off']).
                    '<div class="form-group mbottom-none">'.
                    form_label('&nbsp;&nbsp;User Name','username',['class'=>'fa fa-user','style'=>'font-size: 18px;color:#840f0f;']).
                      form_input(['id'=>'loginId','placeholder'=>'Username', 'class'=>'form_field','pattern'=>'[A-Za-z0-9_]{6,50}','required'=>'true']).
                    '</div>
                    <div class="form-group mbottom-none">'.
                      form_label('&nbsp;&nbsp;Password','pwd',['class'=>'fa fa-lock','style'=>'font-size: 18px;color:#840f0f;']).
                      form_password(['id'=>'pwd','placeholder'=>'Password', 'class'=>'form_field','required'=>'true']).
                      '<div class="btn_viewPass" style="position: absolute; margin: 4px 0px 0px -27px; font-size: 20px; display: inline-block;"><i class="fa fa-eye"></i></div>
                    </div>
                    <div class="form-group">
                      <div id="captcha_data" style="display: inline-block">'.
                            $captcha_data["image"].
                      '</div>
                        <a href="javascript:void(0);" id="refresh" style="position: absolute; margin: 5px 8px 0px; font-size: 22px; display: inline-block;"><i class="fa fa-sync-alt"></i>
                        </a>                         
                      <div style="display: inline-block; margin-left: 60px; float: right; width: 244px;">'.
                          form_input(['id'=>'skey','placeholder'=>'Enter Captcha','class'=>'form_field','required'=>'true','maxlength'=>'6']).
                      '</div>
                    </div>
                    <div class="form-group">'.
                      form_submit(['class'=>'btn btn-success btn-lg btn-block','value'=>'Login','id'=>'login-btn','disabled'=>'true']).
                    '</div>
                    <div class="row no-margin">
                      <div class="col-6 no-gutter text-right">
                        <a href="#" data-dismiss="modal" data-toggle="modal" data-target="#forgotpassModal">Forgot password?</a>
                      </div>
                    </div>'.
                    form_close();
        ?>
        </div>
      </div>
    </div>
  </div>
</div>





  
<!-- Modal Forgot Password -->
<div class="modal fade modal_ngt" id="forgotpassModal" role="dialog">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Forgot Password</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="container_inner">
          <!-- <div class="row text-center text-danger">
            <h2 class="fa fa-unlock" style="padding-bottom: 18px; margin: 0px -6px 14px; display: block; border-bottom: 2px solid #e5bf88;">&nbsp;Password recover?</h2>
          </div> -->
          
          <?= form_open(false,['id'=>'forgetForm','autocomplete'=>'off']) ?>
            <div class="row">
              <div class="col-md-12">
              <label for="username" class="fa fa-user text-primary" style="">&nbsp;&nbsp;User Name</label>
                <?= form_input(['name'=>'loginid','class'=>'form_field','placeholder'=>'Enter Username/Login ID','required'=>'true']) ?>
              </div>
              <div class="col-md-12 col-xs-12">
              <label for="email" class="fa fa-envelope text-primary" style="">&nbsp;&nbsp;Email Id</label>
                <?= form_input(['name'=>'email','type'=>'email','class'=>'form_field','placeholder'=>'Enter Your Registered Email- ID','required'=>'true']) ?>
              </div>
              
               <div class="col-md-12 col-xs-12" style="margin-top: 15px;">
                  <div id="captcha_data_pass" style="display: inline-block">
                       <?php echo $captcha_data["image"] ?>
                  </div>
                    <a href="javascript:void(0);" id="refreshcaptacha" style="position: absolute; margin: 5px 8px 0px; font-size: 22px; display: inline-block;"><i class="fa fa-sync-alt"></i>
                    </a>                         
                  <div style="display: inline-block; margin-left: 40px;">
                     <?php echo form_input(['id'=>'skey_pass','name'=>'skey_pass','placeholder'=>'Enter Captcha','class'=>'form_field','required'=>'true','maxlength'=>'6']);?>
                  </div>
                </div>
                
              <div class="col-md-12 col-xs-12" style="margin-top: 15px;">
                <?= form_submit(["class"=>"btn btn-primary btn-lg btn-block", "value"=>"Recover Password?",'id'=>'recover_pass']); ?>
              </div>         
            </div>
          <?= form_close(); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Registration Form -->
<style>
#enrolment_number {
    display:inline-block;
}
</style>

  <!---- Loading Modal ------------->
  <div class="modal fade bd-example-modal-lg" data-backdrop="static" data-keyboard="false" tabindex="-1" id="loading_modal">
      <div class="modal-dialog modal-sm" style="margin: 25% 50%; text-align: center;">
          <div class="modal-content" style="width: 90px; height: 90px; padding: 15px 25px;">
              <span class="fa fa-spinner fa-spin fa-3x"></span>
              <p class="text-danger" style="margin: 12px -12px;">Loading.....</p>
          </div>
      </div>
  </div>