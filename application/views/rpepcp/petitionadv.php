<?php 
defined('BASEPATH') OR exit('No direct script access allowed'); 
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepsrpepcp");
$salt=$this->session->userdata('rpepcpsalt');
$token= $this->efiling_model->getToken();
$partytype='';
$filingno='';
$tab_no='';
$type='';
if($salt!=''){
    $basicrp= $this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
    $iano=isset($basicrp[0]->totalNoia)?$basicrp[0]->totalNoia:'';
    $anx=isset($basicrp[0]->totalNoAnnexure)?$basicrp[0]->totalNoAnnexure:'';
    $type=isset($basicrp[0]->type)?$basicrp[0]->type:'';
    $order_date=isset($basicrp[0]->order_date)?$basicrp[0]->order_date:'';
    if($order_date!=''){
        $orderd=date('d-m-Y',strtotime($order_date));
    }
    $filingno=isset($basicrp[0]->filing_no)?$basicrp[0]->filing_no:'';
    $tab_no=isset($basicrp[0]->tab_no)?$basicrp[0]->tab_no:'';
    $type=isset($basicrp[0]->type)?$basicrp[0]->type:'';
}
$partyType=isset($_REQUEST['partyType'])?$_REQUEST['partyType']:'';
if($partyType=='1'){
    $partytype='1';
}
if($partyType=='2'){
    $partytype='2';
}
if($partyType=='3'){
    $partytype='3';
}
?>
<script src="<?=base_url('asset/admin_js_final/customs.js')?>"></script> 
<style>
.autosuggest {
    list-style: none;
    margin: 0;
    padding: 0;
    position: absolute;
    left: 15px;
    top: 65px;
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
</style>
<div id="rightbar"> 

<!------------------------council Name-------------------->
<form action="#" id="frmCounsel" autocomplete="off">    
        <input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
        <input type="hidden" name="saltNo" id="saltNo" value="<?php echo $salt; ?>">
        <input type="hidden" name="filingno" id="filingno" value="<?php echo $filingno; ?>">
        <input type="hidden" name="type" id="type" value="<?php echo $type; ?>">
        <input type="hidden" name="tabno" id="tabno" value="<?php echo '4'; ?>">
       <div class="content" style="padding-top:0px;">
        <div class="row">
            <div class="card" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px; border-top-right-radius: 0px; border-top-left-radius: 0px;">
                <fieldset id="condetail" style="display: block; margin-top:12px;border: 2px solid #4cb060;"> 
                <div class="row alert alert-warning" style="padding: 8px 0px 0px; font-weight: 600;font-size: 18px; margin: -20px auto 12px; max-width: 100%;">
                            <div class="col-md-4">
                                <label class="text-danger">Select Mode</label>
                            </div>
                            <div class="col-md-6 md-offset-2">
                                <label for="org" class="form-check-label font-weight-semibold">
                                    <input type="radio" name="org" value="1" checked="checked" id="bd1" onclick="addCouncelType(this.value);">
                                    Add Counsel &nbsp;&nbsp;
                                </label>
                                <label for="indv" class="form-check-label font-weight-semibold">
                                    <input type="radio" name="org" value="2" id="po1" onclick="addCouncelType(this.value);">
                                    In-person/ Representative&nbsp;&nbsp;
                                </label>
                                <label for="inp" class="form-check-label font-weight-semibold">
                                                                </label>
                            </div>
                        </div>
                    <legend class="customlavelsub">Counsel/ Representative Details</legend>
                    <div class="row">
                    <?php 
                    $app= $this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
                    $pet_council_adv=isset($app[0]->councilcode)?$app[0]->councilcode:''; 
                    
                    $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
                    $val=0;
                    if($pet_council_adv!=''){
                        $val='1';
                    }
                    $count=count($advocatelist)+$val;
                    ?>
                        <input type="hidden" name="id" id="id" value="">
                    	<input type="hidden" name="action" id="action" value="add">
                    	<input type="hidden" name="typeadv" id="typeadv" value="">
                    	<input type="hidden"  id="count" name="count" value="<?php echo $count; ?>">
                        <div class="col-md-4">
                            <div class="form-group required" id="div-add">
                                <label><span class="text-danger">*</span>Name :</label>
                                <input type="hidden" name="councilCode" value="" id="councilCode" class="txt">
                            	<input type="text" name="advname" value="" id=advname  class="form-control" onkeypress="serchrecordvaladvrpepcp(this.value);">
                    			<ul class="autosuggest" id="regnum_autofill">
                    			</ul>
                            </div>
                            
                            <div class="form-group required" id="divin-p" style="display:none">
                                <label><span class="text-danger">*</span>Name :</label>
                                <?php
                                $pet_council_adv=isset($app[0]->pet_council_adv)?$app[0]->pet_council_adv:''; 
                                $pet_council =isset($app[0]->pet_adv)?$app[0]->pet_adv:''; 
                                if($pet_council_adv=='' && $pet_council!=''){
                                    $pet_council_adv=$pet_council;
                                }
                                $councilname= $this->efiling_model->data_list('efiling_users');
                                $councilname1adv[]='- Please Select state-';
                                foreach ($councilname as $val)
                                    $councilname1adv[$val->id] = $val->fname.' '.$val->lname; 
                                    echo form_dropdown('adv_inperson',$councilname1adv,$pet_council_adv,['class'=>'form-control','onchange'=>'showinperson(this.value)', 'id'=>'adv_inperson','required'=>'true','required'=>'true']); 
                                ?> 
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label><span class="text-danger">*</span>District</label>   
                                    <?php
                                    $c_pet_district=  $this->efiling_model->data_list('master_advocate','adv_code',$pet_council_adv); 
                                    $cdisrName= $c_pet_district[0]->adv_dist; 
                                    $cpetdis=  $this->efiling_model->data_list_where('master_psdist','district_code',$cdisrName); 
                                    ?>
                                <input type="hidden" name="cddistrict" readonly="" id="cddistrict" class="txt" maxlength="50" value="<?php echo $c_pet_district[0]->adv_dist; ?>">                                 
                                <?= form_input(['name'=>'ddistrictname','value'=>$cpetdis[0]->district_name,'class'=>'form-control','id'=>'ddistrictname','placeholder'=>'ddistrictname','maxlength'=>'100', 'readonly' => 'readonly','title'=>'District Name should be Alfa numeric only.']) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>Phone Number:</label>
                                    <?php  
                                    $counsel_phone= isset($app[0]->counsel_phone)?$app[0]->counsel_phone:''; 
                                    $pet_counsel_phone= isset($app[0]->pet_counsel_phone)?$app[0]->pet_counsel_phone:'';
                                    if($counsel_phone==''  && $pet_counsel_phone!=''){
                                        $counsel_phone=   $pet_counsel_phone;
                                    }
                                    ?>
                                <?= form_input(['name'=>'counselPhone','value'=>'','class'=>'form-control', 'readonly' => 'readonly','id'=>'counselPhone','placeholder'=>'Counsel Phone','pattern'=>'[0-9]{1,12}','maxlength'=>'10','title'=>'Counsel Phone should be numeric only.']) ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>Address:</label>
                                <?php  
                                $counsel_add= isset($app[0]->counsel_add)?$app[0]->counsel_add:''; 
                                $pet_counsel_address= isset($app[0]->pet_counsel_address)?$app[0]->pet_counsel_address:'';
                                if($counsel_add==''  && $pet_counsel_address!=''){
                                    $counsel_add=   $pet_counsel_address;
                                }
                                ?>
                                <?= form_textarea(['name'=>'counselAdd','value'=>$counsel_add, 'class'=>'form-control','id'=>'counselAdd', 'readonly' => 'readonly','rows' => '2','cols'=>'2','placeholder'=>'Counsel Address','maxlength'=>'200','title'=>' Counsel Add only alphanumeric ']) ?>
                            </div>
                        </div>
                    
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>Pincode:</label>
                                <?php  
                                $counsel_pin= isset($app[0]->counsel_pin)?$app[0]->counsel_pin:'';
                                $pet_counsel_pin= isset($app[0]->pet_counsel_pin)?$app[0]->pet_counsel_pin:'';
                                if($counsel_pin==''  && $pet_counsel_pin!=''){
                                    $counsel_pin=   $pet_counsel_pin;
                                }
                                ?>
                                <?= form_input(['name'=>'counselPin','value'=>$counsel_pin,'class'=>'form-control','id'=>'counselPin','placeholder'=>'Counsel Pin', 'readonly' => 'readonly','maxlength'=>'6','title'=>'Counsel Pin allowed only alphanumeric ']) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Email ID:</label>
                                <?php  
                                $counsel_email= isset($app[0]->counsel_email)?$app[0]->counsel_email:'';
                                $pet_counsel_email= isset($app[0]->pet_counsel_email)?$app[0]->pet_counsel_email:'';
                                if($counsel_email==''  && $pet_counsel_email!=''){
                                    $counsel_email=   $pet_counsel_email;
                                }
                                ?>
                                <?= form_input(['name'=>'counselEmail','value'=>$counsel_email,'class'=>'form-control', 'readonly' => 'readonly','id'=>'counselEmail','placeholder'=>'Counsel Email','maxlength'=>'25','title'=>'Counsel Email allowed only alphanumeric ']) ?>
                            </div>
                        </div>
                    </div>
                    
                        <div class="row">
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>State Name  :</label>
                                <?php   
                                $c_pet_state=  $this->efiling_model->data_list('master_advocate','adv_code',$pet_council_adv); 
                                $statecode= isset($c_pet_state[0]->state_code)?$c_pet_state[0]->state_code:''; 
                                $pet_state= isset($app[0]->pet_state)?$app[0]->pet_state:''; 
                                if($statecode=='' && $pet_state!=''){
                                    $statecode= $pet_state;
                                }
                                $cstate=  $this->efiling_model->data_list_where('master_psstatus','state_code',$statecode);
                                ?>
                                <input type="hidden" name="cdstate" readonly="" id="cdstate" class="txt" maxlength="50" value="<?php echo $cstate[0]->state_code?>">
                                <?= form_input(['name'=>'dstatename','value'=> $cstate[0]->state_name,'class'=>'form-control', 'readonly' => 'readonly','id'=>'dstatename', 'placeholder'=>'State name','maxlength'=>'5','title'=>' Counsel District name should be alphanumeric.']) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>Mobile Number:</label>
                                    <?php  
                                    $counsel_mobile= isset($app[0]->counsel_mobile)?$app[0]->counsel_mobile:''; 
                                    $pet_council_mobile =isset($app[0]->pet_counsel_mobile)?$app[0]->pet_counsel_mobile:'';
                                    if($counsel_mobile=='' && $pet_council_mobile!=''){
                                        $counsel_mobile=$pet_council_mobile;
                                    }
                                    ?>                                
                                <?= form_input(['name'=>'counselMobile','value'=>'','class'=>'form-control',  'readonly' => 'readonly','id'=>'counselMobile', 'placeholder'=>'Mobile No','pattern'=>'[0-9]{1,12}','maxlength'=>'10','title'=>' Counsel Mobile Number should be numeric only.']) ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group required">
                                <label>Fax No:</label>
                                    <?php  
                                    $counsel_fax= isset($app[0]->counsel_fax)?$app[0]->counsel_fax:''; 
                                    $pet_council_fax =isset($app[0]->pet_counsel_fax)?$app[0]->pet_counsel_fax:'';
                                    if($counsel_fax=='' && $pet_council_fax!=''){
                                        $counsel_fax=$pet_council_fax;
                                    }
                                    ?>       
                                <?= form_input(['name'=>'counselFax','value'=>$counsel_fax,'class'=>'form-control', 'readonly' => 'readonly','id'=>'counselFax','placeholder'=>'Fax No','pattern'=>'[0-9]{1,12}','maxlength'=>'12','title'=>' Counsel Fax No should be numeric only.']) ?>
                            </div>
                        </div>
                        <input type="button" name="" class="btn btn-success" onclick="addCouncel()" value="Add">
                    </div>
                </fieldset>
            </div>
            </div>
           <?php  
           $html='';
           $html.='
            <table id="example" class="display" cellspacing="0" border="1" width="100%">
	        <thead>
    	        <tr>
        	        <th>Sr. No. </th>
        	        <th>Name</th>
        	        <th>Registration No.</th>
                    <th>Address</th>
        	        <th>Mobile</th>
        	        <th>Email</th>
                    <th>Edit</th>
        	        <th>Delete</th>
    	        </tr>
	        </thead>
	        <tbody>';
           $html.='</tbody>';
           $vals=$this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
           if(empty($vals)){
               echo "Record not found";
           }else{
           $advType=$vals[0]->advType;
           if($vals[0]->councilcode){
               $counseladd=$vals[0]->councilcode;
               if($vals[0]->advType=='1'){
                   if (is_numeric($vals[0]->councilcode)) {
                       $orgname=$this->efiling_model->data_list_where('master_advocate','adv_code',$counseladd);
                       $adv_name=$orgname[0]->adv_name;
                       $adv_reg=$orgname[0]->adv_reg;
                       $adv_mobile=$orgname[0]->adv_mobile;
                       $email=$orgname[0]->email;
                       $address=$orgname[0]->address;
                       $pin_code=$orgname[0]->adv_pin;
                       if($vals[0]->cou_state!=''){
                           $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$vals[0]->cou_state);
                           $statename= $st2[0]->state_name;
                       }
                       if($vals[0]->cou_dist!=''){
                           $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$vals[0]->cou_dist);
                           $ddistrictname= $st1[0]->district_name;
                       }
                   }
               }
                $ddistrictname='';
			 $statename='';
               if($vals[0]->advType=='2'){
                   if (is_numeric($vals[0]->councilcode)) {
                       $orgname=$this->efiling_model->data_list_where('efiling_users','id',$counseladd);
                       $adv_name=$orgname[0]->fname.' '.$orgname[0]->lname;
                       $adv_reg=$orgname[0]->id_number.' <span style="color:red">'.$orgname[0]->idptype.'</span>';
                       $adv_mobile=$orgname[0]->mobilenumber;
                       $email=$orgname[0]->email;
                       $address=$orgname[0]->address;
                       $pin_code=$orgname[0]->pincode;
                       if($vals[0]->cou_state!=''){
                           $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$vals[0]->cou_state);
						    if(!empty($st2)){
								$statename= $st2[0]->state_name;
						   }
                       }
                       if($vals[0]->cou_dist!=''){
                           $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$vals[0]->cou_dist);
						   if(!empty($st1)){
								$ddistrictname= $st1[0]->district_name;
						   }
                       }
                   }
               }
               $type="'main'";
               $html.='
                <tr style="color:green">
                    <td>1</td>
        	        <td>'.$adv_name.'</td>
        	        <td>'.$adv_reg.'</td>
                    <td>'.$address.' '.$ddistrictname.' ('.$statename.')  '.$pin_code.'</td>
        	        <td>'.$adv_mobile.'</td>
        	        <td>'.$email.'</td>
        	        <td><center><input type="button" name="nextsubmit" id="nextsubmit" value="Edit" class="btn1" data-toggle="modal" data-target="#exampleModal"  onclick="editPartyAdv('.$vals[0]->salt.','.$type.','.$advType.')"></center></td>
                    <td></td>
                </tr>';
           }
           //  $advocatelist=$this->efiling_model->data_list_where('aptel_temp_add_advocate','salt',$salt);
           if(!empty($advocatelist)){
               $i=2;
               foreach($advocatelist as $val){
                   $counselmobile='';
                   $counselemail='';
                   $counseladd=$val->council_code;
                   $advType=$val->advType;
                   if($advType=='1'){
                       if (is_numeric($val->council_code)) {
                           $orgname=$this->efiling_model->data_list_where('master_advocate','adv_code',$counseladd);
                           $adv_name=$val->adv_name;
                           $adv_reg=$orgname[0]->adv_reg;
                           $address=$val->counsel_add;
                           $pin_code=$val->counsel_pin;
                           $counselmobile=$val->counsel_mobile;
                           $counselemail=$val->counsel_email;
                           $id=$val->id;
                           if($val->adv_state!=''){
                               $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$val->adv_state);
                               $statename= $st2[0]->state_name;
                           }
                           if($val->adv_district!=''){
                               $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$val->adv_district);
                               $ddistrictname= $st1[0]->district_name;
                           }
                       }
                   }
                   if($advType=='2'){
                       if (is_numeric($val->council_code)) {
                           $orgname=$this->efiling_model->data_list_where('efiling_users','id',$counseladd);
                           $adv_name=$orgname[0]->fname.' '.$orgname[0]->lname;
                           $adv_reg=$orgname[0]->id_number.' <span style="color:red">'.$orgname[0]->idptype.'</span>';
                           $counselmobile=isset($orgname[0]->mobilenumber)?$orgname[0]->mobilenumber:'';
                           $counselemail=isset($orgname[0]->email)?$orgname[0]->email:'';
                           $address=$orgname[0]->address;
                           $pin_code=$orgname[0]->pincode;
                           $id=$val->id;
                           if($vals[0]->cou_state!=''){
                               $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$vals[0]->cou_state);
                               $statename= $st2[0]->state_name;
                           }
                           if($vals[0]->cou_dist!=''){
                               $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$vals[0]->cou_dist);
                               $ddistrictname= $st1[0]->district_name;
                           }
                       }
                   }
                   $type="'add'";
                   $html.='<tr>
            	        <td>'.$i.'</td>
            	        <td>'.$adv_name.'</td>
            	        <td>'.$adv_reg.'</td>
                        <td>'.$address.' '.$ddistrictname.' ('.$statename.')  '.$pin_code.'</td>
            	        <td>'.$counselmobile.'</td>
            	        <td>'.$counselemail.'</td>
            	        <td><center><input type="button" name="nextsubmit" id="nextsubmit" value="Edit" class="btn1"  data-toggle="modal" data-target="#exampleModal" onclick="editPartyAdv('.$id.','.$type.','.$advType.')"></center></td>
                        <td><center><input type="button" name="nextsubmit" id="nextsubmit" value="Delete" class="btn1" onclick="deletePartyadv('.$id.')"></center></td>
        	        </tr>';
                   $i++;
               }
           }
           echo  $html;
           }
	         ?>
	        
        </div>
        <div class="row">
            <div class="offset-md-8 col-md-4 text-right">
                <?php   
                echo '<i class="icon-file-plus" style="position: absolute; margin: 9px 4px; z-index: 9; color: white;"></i>';
                if((int)$salt > 0)
                    echo form_submit(['value'=>'Save & Next','class'=>'btn btn-success','id'=>'counsel_save','style'=>'padding-left:24px;']).'<i class="icon-trash-alt" style="position: absolute; z-index: 9; margin: 10px 4px; color:white;"></i>';
                else 
                    echo form_submit(['value'=>'Save & Next','class'=>'btn btn-success','id'=>'counsel_save','style'=>'padding-left:24px;','disabled'=>'disabled']).'<i class="icon-trash-alt" style="position: absolute; z-index: 9; margin: 10px 4px; color:white;"></i>';
                echo form_reset(['value'=>'Reset/Clear','class'=>'btn btn-danger','style'=>'padding-left: 24px;']);
                    //  .form_button(['id'=>'','value'=>'false','content'=>'&nbsp;Next','class'=>'icon-arrow-right8 btn btn-primary']);
                ?>
            </div>
        </div>
    </div>
</form>

<script>


function addCouncel(str) {
	 var advtype='';
     var checkboxes = document.getElementsByName('org');
     for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            advtype = checkboxes[i].value;
        }
     }
     if(advtype=='1'){
        var councilCode= document.getElementById("councilCode").value;
     } 
     if(advtype=='2'){
        var councilCode= document.getElementById("adv_inperson").value;
     }
     var count= document.getElementById("count").value;
     var partyType= document.getElementById("typeadv").value;
     var ddistrictname= document.getElementById("ddistrictname").value;
     var cddistrict= document.getElementById("cddistrict").value;
	 
	if(ddistrictname=='' && cddistrict ==''){
		 alert("Please Enter district name ! ");
		 return fasle;
	}
	 
     var counselPhone= document.getElementById("counselPhone").value;
     var counselAdd= document.getElementById("counselAdd").value;
     var counselPin= document.getElementById("counselPin").value;
     var counselEmail= document.getElementById("counselEmail").value;
     var dstatename= document.getElementById("dstatename").value;
     var cdstate= document.getElementById("cdstate").value;
     var counselMobile= document.getElementById("counselMobile").value;
     var counselFax=  document.getElementById("counselFax").value;
     var action=  document.getElementById("action").value;
     var id=  document.getElementById("id").value;
     var salt = document.getElementById('saltNo').value;    
     var filingno = document.getElementById('filingno').value;
	 var type = document.getElementById('type').value;
     var dataa = {};
     dataa['filingno'] = filingno;
     dataa['type'] = type;
     dataa['councilCode'] = councilCode;
     dataa['ddistrictname'] = ddistrictname;
     dataa['cddistrict'] = cddistrict;
     dataa['counselPhone'] = counselPhone;
     dataa['counselAdd'] = counselAdd;
     dataa['counselPin'] = counselPin;
     dataa['counselEmail'] = counselEmail;
     dataa['dstatename'] = dstatename;
     dataa['cdstate'] = cdstate;
     dataa['counselMobile'] = counselMobile;
     dataa['counselFax'] = counselFax;
     dataa['action'] = action;
     dataa['id'] = id;
     dataa['salt'] = salt;
     dataa['token'] = '<?php echo $token; ?>';
     dataa['advType'] =advtype;
     dataa['partyType'] =partyType;
     $.ajax({
        type: "POST",
        url: base_url+'RPEPCPaddCouncel',
        data: dataa,
        cache: false,
        success: function (petSection) {
             var data2 = JSON.parse(petSection);
             if (data2.data=='success') {  
                  $.alert({
    					title: '<i class="fa fa-check-circle text-success"></i>&nbsp;</b>Success</b>',
    					content: '<p class="text-success">Successfully Added</p>',
    					animationSpeed: 2000
    				});
				  document.getElementById("count").value = count+1;
                $('#example').html(data2.display);
                document.getElementById("counselAdd").value = "";
                document.getElementById("counselMobile").value = "";
                document.getElementById("counselEmail").value = "";
                document.getElementById("counselPhone").value = "";
                document.getElementById("counselPin").value = "";
                document.getElementById("counselFax").value = "";
                document.getElementById("cdstate").value = "";
                document.getElementById("dstatename").value = "";
                document.getElementById("cddistrict").value = "";
                document.getElementById("ddistrictname").value = "";
                document.getElementById("advname").value = "";
             }
             if (data2.error=='1') {  
             	$.alert({
					title: '<i class="fa fa-check-circle text-danger"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-success">'+data2.massage+'</p>',
					animationSpeed: 2000
				});
             }
        }
    });
}




function addCouncelType(val){
  if(val=='2'){
 	$('#divin-p').show();
 	$('#div-add').hide();
 	var dataa = {};
	dataa['token'] = '<?php echo $token; ?>';
	dataa['type'] = val;
    if (val) {
        document.getElementById("counselAdd").value = "";
        document.getElementById("counselMobile").value = "";
        document.getElementById("counselEmail").value = "";
        document.getElementById("counselPhone").value = "";
        document.getElementById("counselPin").value = "";
        document.getElementById("counselFax").value = "";
        document.getElementById("cdstate").value = "";
        document.getElementById("dstatename").value = "";
        document.getElementById("cddistrict").value = "";
        document.getElementById("ddistrictname").value = "";
    }
    $.ajax({
        type: "POST",
        url: base_url+'getAdvDetailinperson',
        data: dataa,
        cache: false,
        success: function (petSection) {
        	 var data1 = petSection;
             var data2 = JSON.parse(data1);
             if (data2.data =='success') {
                var val = JSON.parse(data2.display);
                 $("#adv_inperson option[value='"+val.adv_name+"']").attr("selected","selected");
                 document.getElementById("counselAdd").value = val.counsel_add;
                 document.getElementById("counselMobile").value = val.counsel_mobile;
                 document.getElementById("counselEmail").value = val.counsel_email;
                 document.getElementById("counselPhone").value = val.counsel_phone;
                 document.getElementById("counselPin").value = val.counsel_pin;
                 document.getElementById("counselFax").value = val.counsel_fax;
                 document.getElementById("cdstate").value = val.adv_state;
                 document.getElementById("dstatename").value = val.statename;
                 document.getElementById("cddistrict").value = val.adv_district;
                 document.getElementById("ddistrictname").value = val.ddistrictname;
             }
        }
    });
  }
  if(val=='1'){
      $('#div-add').show();
      $('#divin-p').hide();
  }	              	
}








function showinperson(val){
 	var dataa = {};
	dataa['token'] = '<?php echo $token; ?>';
	dataa['id'] = val;
	val
    if (val == '0') {
        document.getElementById("counselAdd").value = "";
        document.getElementById("counselMobile").value = "";
        document.getElementById("counselEmail").value = "";
        document.getElementById("counselPhone").value = "";
        document.getElementById("counselPin").value = "";
        document.getElementById("counselFax").value = "";
        document.getElementById("cdstate").value = "";
        document.getElementById("dstatename").value = "";
        document.getElementById("cddistrict").value = "";
        document.getElementById("ddistrictname").value = "";
    }
    $.ajax({
        type: "POST",
        url: base_url+'getAdvinpers',
        data: dataa,
        cache: false,
        success: function (petSection) {
        	 var data1 = petSection;
             var data2 = JSON.parse(data1);
             if (data2.data =='success') {
                var val = JSON.parse(data2.display);
                 $("#adv_inperson option[value='"+val.adv_name+"']").attr("selected","selected");
                 document.getElementById("counselAdd").value = val.counsel_add;
                 document.getElementById("counselMobile").value = val.counsel_mobile;
                 document.getElementById("counselEmail").value = val.counsel_email;
                 document.getElementById("counselPhone").value = val.counsel_phone;
                 document.getElementById("counselPin").value = val.counsel_pin;
                 document.getElementById("counselFax").value = val.counsel_fax;
                 document.getElementById("cdstate").value = val.adv_state;
                 document.getElementById("dstatename").value = val.statename;
                 document.getElementById("cddistrict").value = val.adv_district;
                 document.getElementById("ddistrictname").value = val.ddistrictname;
             }
        }
    });             	
}














$('#frmCounsel').submit(function(e){ 
	e.preventDefault();
	var salt = $("#saltNo").val();
    var tabno = '4';
    var org='';
    
    var checkboxes = document.getElementsByName('org');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            org = checkboxes[i].value;
        }
    }
    
    var count= document.getElementById("count").value;
    if(count=='0'){
     	alert("Please add councel");
     	return false;
    }
    
    var dataa = {};
	dataa['salt'] = salt,
	dataa['tabno']=tabno;
	dataa['token']='<?php echo $token; ?>';
	$.ajax({
        type: "POST",
        url: base_url+'orgshowresrpepcp',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#petitioner_save').prop('disabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
        		setTimeout(function(){
                    window.location.href = base_url+'petitionIa';
                 }, 250);
        	    document.getElementById("loading_modal").style.display = 'none';
			}
			else if(resp.error != '0') {
				$.alert(resp.error);
			}
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
			$('#petitioner_save').prop('disabled',false).val("Submit");
		}
	 }); 
});

function showUserOrgrpepcp(str) {
	var dataa = {};
	dataa['q'] = str;
    if (str == '0') {
        document.getElementById("counselAdd").value = "";
        document.getElementById("counselMobile").value = "";
        document.getElementById("counselEmail").value = "";
        document.getElementById("counselPhone").value = "";
        document.getElementById("counselPin").value = "";
        document.getElementById("counselFax").value = "";
        document.getElementById("cdstate").value = "";
        document.getElementById("dstatename").value = "";
        document.getElementById("cddistrict").value = "";
        document.getElementById("ddistrictname").value = "";
    }
    $.ajax({
        type: "POST",
        url: base_url+'getAdvDetail',
        data: dataa,
        cache: false,
        success: function (petSection) {
        	 var data1 = petSection;
             console.log(data1);
             var data2 = JSON.parse(data1);
             if (str != 0) {
                 document.getElementById("councilCode").value = data2[0].adv_code;
                 document.getElementById("advname").value = data2[0].adv_name;
                 document.getElementById("counselAdd").value = data2[0].address;
                 document.getElementById("counselMobile").value = data2[0].mob;
                 document.getElementById("counselEmail").value = data2[0].mail;
                 document.getElementById("counselPhone").value = data2[0].ph;
                 document.getElementById("counselPin").value = data2[0].pin;
                 document.getElementById("counselFax").value = data2[0].fax;
                 document.getElementById("cdstate").value = data2[0].stcode;
                 document.getElementById("dstatename").value = data2[0].stname;
                 document.getElementById("cddistrict").value = data2[0].dcode;
                 document.getElementById("ddistrictname").value = data2[0].dname;
                 $('#regnum_autofill').show();
             }
        },
        error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
		    $('#regnum_autofill').hide();
			$('#loading_modal').modal('hide');
		}
    });
}



function deletePartyadv(e) {
    var salt = document.getElementById("saltNo").value;
    var dataa = {};
	dataa['id'] =e,
	dataa['salt'] =salt,
	dataa['token'] ='<?php echo $token; ?>',
	$.ajax({
        type: "POST",
        url: base_url+'deleteAdvocateEPRPCP',
        data: dataa,
        cache: false,
		beforeSend: function(){
			$('#deletesubmit').prop('enabled',true).val("Under proccess....");
		},
        success: function (resp) {
        	 var data2 = JSON.parse(resp);
             if (data2.data=='success') {  
                 $('#example').html(data2.display);
             }
             if (data2.error=='1') {  
             	$.alert({
					title: '<i class="fa fa-check-circle text-danger"></i>&nbsp;</b>Error</b>',
					content: '<p class="text-success">'+data2.massage+'</p>',
					animationSpeed: 2000
				});
             }
        },
        error: function(){
			$.alert("Surver busy,try later.");
		},
		complete: function(){
			
		}
	 }); 
}   



function editPartyAdv(id,type,advType) {
    var dataa = {};
    dataa['id'] = id;
    dataa['type'] = type;
    dataa['token'] = '<?php echo $token; ?>';
    dataa['advType'] = advType;
    if (id =='') {
        document.getElementById("counselAdd").value = "";
        document.getElementById("counselMobile").value = "";
        document.getElementById("counselEmail").value = "";
        document.getElementById("counselPhone").value = "";
        document.getElementById("counselPin").value = "";
        document.getElementById("counselFax").value = "";
        document.getElementById("cdstate").value = "";
        document.getElementById("dstatename").value = "";
        document.getElementById("cddistrict").value = "";
        document.getElementById("ddistrictname").value = "";
    }
    $.ajax({
        type: "POST",
        url: base_url+'getAdvDetailEdit',
        data: dataa,
        cache: false,
        success: function (petSection) {
        	 var data1 = petSection;
             console.log(data1);
             var data2 = JSON.parse(data1);
             var val = JSON.parse(data2.display);
             if (val.salt != '') {
                 var data2 = JSON.parse(data2.display);
                 if(val.advType=='2'){
                 	 $("#po1").prop("checked", true);
                 	 $('#div-add').hide();
                     $('#divin-p').show();
                     $("#adv_inperson option[value='"+val.council_code+"']").attr("selected","selected");
                 }
                 
                 if(val.advType=='1'){
                 	 $("#bd1").prop("checked", true);
                 	 $('#div-add').show();
                     $('#divin-p').hide();
                     document.getElementById("action").value = val.action;
                     $("#councilCode option[value='"+val.council_code+"']").attr("selected","selected");
                 }
                 
                 document.getElementById("councilCode").value = val.council_code;
                 document.getElementById("advname").value = val.adv_name;

                 document.getElementById("counselAdd").value = val.counsel_add;
                 document.getElementById("counselMobile").value = val.counsel_mobile;
                 document.getElementById("counselEmail").value = val.counsel_email;
                 document.getElementById("counselPhone").value = val.counsel_phone;
                 document.getElementById("counselPin").value = val.counsel_pin;
                 document.getElementById("counselFax").value = val.counsel_fax;
                 document.getElementById("cdstate").value = val.adv_state;
                 document.getElementById("dstatename").value = val.statename;
                 document.getElementById("cddistrict").value = val.adv_district;
                 document.getElementById("ddistrictname").value = val.ddistrictname;
                 document.getElementById("action").value = val.action;
                 document.getElementById("id").value = val.id;
                 document.getElementById("typeadv").value = type;
                 
             }
        }
    });
}


function serchrecordvaladvrpepcp(val){
	$.ajax({
		type: 'post',
		url: base_url+'getAdvrpepcp',
		data: {key:val},
		dataType: 'html',
		cache: false,
		beforeSend: function(){
		 	//$('#loading_modal').modal();
		},
		success: function(retn){
		    $('#regnum_autofill').show();
			$('#regnum_autofill').html(retn);
		},
		error: function(){
			$.alert('Error : server busy, try later');
		},
		complete: function(){
			document.getElementById("loading_modal").style.display = 'none';
			$('#loading_modal').modal('hide');
		}
	});	
}
</script>
<?php $this->load->view("admin/footer"); ?>