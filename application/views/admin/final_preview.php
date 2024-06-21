<?php defined('BASEPATH') OR exit('No direct script access allowed'); error_reporting();
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$token= $this->efiling_model->getToken();
$salt=$this->session->userdata('salt');
error_reporting(0);
 

function emailmask($email){
    if($email!=''){
        $email = $email;
        $em = explode("@",$email);
        $name = $em[0];
        $len = strlen($name);
        $showLen = floor($len/2);
        $str_arr = str_split($name);
        for($ii=$showLen;$ii<$len;$ii++){
            $str_arr[$ii] = '*';
        }
        $em[0] = implode('',$str_arr);
        $new_name = implode('@',$em);
        return  $new_name;
    }else{
        return  'NA';
    }
}
?>
<script src="<?=base_url('asset/admin_js_final/customs.js')?>"></script> 
<div id="rightbar"> 
<?php  include 'steps.php';?>
<div class="content" style="padding-top:0px;">
<input type="button" value="Print" id="btnPrint" onclick="printPage();">
	<div class="row">
		<div class="card"  id="dvContainer" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px;  border-top-left-radius: 0px;">
            <form action="<?php echo base_url(); ?>pay_page" class="wizard-form steps-basic wizard clearfix" id="finalsubmit" autocomplete="off" method="post" accept-charset="utf-8">
                <div class="content clearfix" id="mainDiv1">
                    <?= form_fieldset().
                        '<div class="date-div text-success">'.date('D M d, Y H:i:s').'&nbsp;IST</small></div>'; ?>
                        <?php 
                        $org=$this->session->userdata('org');
                        $orgres='1';
                        ?>
					    <input type="hidden" id="salt" name="salt" value="<?php echo $salt; ?>">
					   <?php 
                        $st=$this->efiling_model->data_list_where('sat_temp_appellant','salt', $salt);
                        $bench=$st[0]->bench;
                        $subbench=$st[0]->sub_bench;
                        $caseType=$st[0]->l_case_type;
                        ?>
                        <input type="hidden" name="org" value="<?php echo $org; ?>" id="org">
    					<input type="hidden" name="orgres" value="<?php echo $orgres; ?>" id="orgres">
    					<input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
    					<input type="hidden" name="bench" value="<?php echo $bench; ?>" id="bench">
    					<input type="hidden" name="sub_bench" value="<?php echo $subbench; ?>" id="sub_bench">
    					<input type="hidden" name="caseType" value="<?php echo $caseType; ?>" id="caseType">
    					<input type="hidden" name="tabno" value="9" id="tabno">
    					tabno
                        <?php 
                            $UiD=(int)$this->session->userdata('login_success')[0]->id;
                            $where_cond=[
                                'salt'          =>  $salt,
                                'created_user'  =>  $UiD
                            ];
                            $dataArray= '';
                            $val= $this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
                            foreach($val as $row) {
                                $opauthority= $row->opauthority;
                                $apealtype= $row->apeal_type;
                                $ipenalty= $row->ipenalty;
                                $act= $row->act;
                                $stfee=$this->efiling_model->data_list_where('master_regulator','order_pass_auth_id', $opauthority);
                                if(!empty($stfee)){
                                    $regulatorfeeval=$stfee[0]->fee;
                                    $orderpassingauthority=$stfee[0]->order_passing_authority;
                                }
                                $petno = $row->no_of_app;
                                $resno = $row->no_of_res;
                                $iano = $row->no_of_ia;
                                $iorderdate = $row->iorderdate;
                                $rimpugnedorder = $row->rimpugnedorder;
                                $delayinfiling= $row->delayinfiling;
                        }
               
                        ?>
                         <div class="row">
                            <div class="card w-100" style="padding: 0px 12px;">
                            <FIELDSET>
                                <LEGEND><b>Basic Detail</b></legend>
                                <table id="example" class="display" cellspacing="0" border="1" width="100%">
                                    <tbody>
                                    <tr>
                                        <td width="16%">Regulator</td>
                                        <td width="16%"><?php echo $apealtype; ?></td>
                                        <td width="16%">Order passing Authority</td>
                                        <td width="16%"><?php echo $orderpassingauthority; ?></td>
                                        <td width="16%">Penalty</td>
                                        <td width="16%"><?php echo $ipenalty; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Impugned Date</td>
                                        <td><?php echo $iorderdate; ?></td>
                                        <td>Impugned Receipt Date</td>
                                        <td><?php echo $rimpugnedorder; ?></td>
                                        <td>Act</td>
                                        <td><?php echo $act; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Total No of Appellant</td>
                                        <td><?php echo $petno; ?></td>
                                        <td>Total No Of MA</td>
                                        <td><?php echo $iano; ?></td>
                                        <td>Delay Day</td>
                                        <td><?php echo $delayinfiling; ?></td>
                                    </tr>
                                </table>
                            </FIELDSET>
                            </br>
                                <?php 
                                    echo form_fieldset('Appellants  Details',['style'=>'margin-top:12px;border: 2px solid #4cb060;']).
                                    '<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 21px 6px;"></i>'; ?>

                                    <div class="d-block text-center text-warning">
                                <div class="table-responsive text-secondary" id="add_petitioner_list">
                                    <span class="fa fa-spinner fa-spin fa-3x" style="display:none"></span>
                                    <table id="example" class="display" cellspacing="0" border="1" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Sr. No. </th>
                                                <th>Appellant Name</th>
                                                <th>Designation</th>
                                                <th>Mobile</th>
                                                <th>Email</th>
                                                <th>Address</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    <?php 
                                    $salt=$this->session->userdata('salt');
                                    $vals=$this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
                                    if(empty($vals)){
                                        $vals=$this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
                                    }
                                    if(@$vals[0]->pet_name!=''){
                                        $petName=$vals[0]->pet_name;
                                        if (is_numeric($vals[0]->pet_name)) {
                                            $orgname=$this->efiling_model->data_list_where('master_org','org_id',$vals[0]->pet_name);
                                            $petName=$orgname[0]->org_name;
                                        }
                                        
                                        if($vals[0]->pet_state!=''){
                                            $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$vals[0]->pet_state);
                                            $statename= $st2[0]->state_name;
                                        }
                                        if($vals[0]->pet_dist!=''){
                                            $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$vals[0]->pet_dist);
                                            $ddistrictname= $st1[0]->district_name;
                                        }
              
                                        
                                        ?>
                                            <tr style="color:green">
                                                <td>1</td>
                                                <td><?php echo $petName; ?>(A-1)</td>
                                                <td><?php echo $vals[0]->pet_degingnation ?></td>
                                                <td><?php echo '###'.substr(isset($vals[0]->petmobile)?$vals[0]->petmobile:'',6,4).'###'; ?></td>
                                                <td><?php echo  emailmask($vals[0]->pet_email); ?></td>
                                                <td><?php echo $vals[0]->pet_address ?>&nbsp;<?php echo $ddistrictname ?>
                                                &nbsp;<?php echo $statename; ?>&nbsp;<?php echo $vals[0]->pincode ?></td>
                                            </tr>
                                        <?php } 
                                        $additionalparty=$this->efiling_model->data_list_where('sat_temp_additional_party','salt',$salt); 
                                        if(empty($additionalparty)){
                                            $additionalparty=$this->efiling_model->data_list_where('additional_party','filing_no',$filing_no);
                                        }
                                        $i=2;
                                        if(!empty($additionalparty)){
                                            foreach($additionalparty as $val){
                                                $petName=$val->pet_name;
                                                if (is_numeric($val->pet_name)) {
                                                    $orgname=$this->efiling_model->data_list_where('master_org','org_id',$val->pet_name);
                                                    $petName=$orgname[0]->org_name;
                                                }
                                            ?>
                                            <tr>
                                            <td><?php echo $i; ?></td>
                                                <td><?php echo $petName; ?>(A-<?php echo $i; ?>)</td>
                                                <td><?php echo $val->pet_degingnation ?></td>
                                                <td><?php echo '###'.substr(isset($val->pet_mobile)?$val->pet_mobile:'',6,4).'###'; ?></td>
                                                <td> <?php echo  emailmask($val->pet_email ); ?></td>
                                                <td><?php echo $val->pet_address ?></td>
                                               
                                            </tr>
                                            <?php 
                                            $i++; }
                                        }else{
                                            $val= "<span style='color:Red'>Reccord Not found";
                                        }
                                             ?>
                                        </tbody>
                                    </table>
                                </div>
                         <?php  echo form_fieldset_close(); ?>
                        <FIELDSET>
                         <LEGEND><b>Counsel/ Representative </b></legend>        
                         <?php   $html='';
                           $html.='
                            <table id="example" class="display" cellspacing="0" border="1" width="100%">
                	        <thead>
                    	        <tr>
                        	        <th>Sr. No. </th>
                        	        <th>Advocate name</th>
                        	        <th>Registraction</th>
                                    <th>Address</th>
                        	        <th>Mobile</th>
                        	        <th>Email</th>
                                    
                    	        </tr>
                	        </thead>
                	        <tbody>';
                           $html.='</tbody>';
                           $vals=$this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
                           $advType=$vals[0]->advType;
                           if($vals[0]->pet_council_adv){
                               $counseladd=$vals[0]->pet_council_adv;
                               if($vals[0]->advType=='1'){
                                     if (is_numeric($vals[0]->pet_council_adv)) {
                                           $orgname=$this->efiling_model->data_list_where('master_advocate','adv_code',$counseladd);
                                           $adv_name=$orgname[0]->adv_name;
                                           $adv_reg=$orgname[0]->adv_reg;
                                           $adv_mobile=$orgname[0]->adv_mobile;
                                           $email=$orgname[0]->email;
                                           $address=$orgname[0]->address;
                                           $pin_code=$orgname[0]->adv_pin;
                                           
                                           if($vals[0]->pet_state!=''){
                                               $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$vals[0]->pet_state);
                                               $statename= $st2[0]->state_name;
                                           }
                                           if($vals[0]->pet_dist!=''){
                                               $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$vals[0]->pet_dist);
                                               $ddistrictname= $st1[0]->district_name;
                                           }
                                      }
                               }
                               
                               if($vals[0]->advType=='2'){
                                   if (is_numeric($vals[0]->pet_council_adv)) {
                                       $orgname=$this->efiling_model->data_list_where('efiling_users','id',$counseladd);
                                       $adv_name=$orgname[0]->fname.' '.$orgname[0]->lname;
                                       $adv_reg=$orgname[0]->id_number.' <span style="color:red">'.$orgname[0]->idptype.'</span>';
                                       $adv_mobile=$orgname[0]->mobilenumber;
                                       $email=$orgname[0]->email;
                                       $address=$orgname[0]->address;
                                       $pin_code=$orgname[0]->pincode;
                                       
                                       if($vals[0]->pet_state!=''){
                                           $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$vals[0]->pet_state);
                                           $statename= $st2[0]->state_name;
                                       }
                                       if($vals[0]->pet_dist!=''){
                                           $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$vals[0]->pet_dist);
                                           $ddistrictname= $st1[0]->district_name;
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
                        	        <td>"###'.substr(isset($adv_mobile)?$adv_mobile:'',6,4).'###"</td>
                        	        <td>'.emailmask($email).'</td>
                        	        
                                </tr>';
                           }
                           $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
                
                           if(!empty($advocatelist)){
                               $i=2;
                               foreach($advocatelist as $val){
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
                                           $counselmobile=$orgname[0]->mobilenumber;
                                           $counselemail=$orgname[0]->email;
                                           $address=$orgname[0]->address;
                                           $pin_code=$orgname[0]->pincode;
                                           $id=$val->id;
                                           if($vals[0]->pet_state!=''){
                                               $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$vals[0]->pet_state);
                                               $statename= $st2[0]->state_name;
                                           }
                                           if($vals[0]->pet_dist!=''){
                                               $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$vals[0]->pet_dist);
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
                            	        <td>"###'.substr(isset($counselmobile)?$counselmobile:'',6,4).'###"</td>
                            	        <td>'.emailmask($counselemail).'</td>
                        	        </tr>';
                                   $i++;
                               }
                           }
                           echo $html;
                	         ?>
                	         </table>
                     <?php  echo form_fieldset_close(); ?>
                        <?php 
                            echo form_fieldset('Respondents Details',['style'=>'margin-top:12px;border: 2px solid #4cb060;']).
                            '<i class="icon-plus-circle2 text-danger" style="position: absolute;padding: 21px 6px;"></i>'; ?>
                                 <div class="d-block text-center text-warning">
                                    <div class="table-responsive text-secondary" id="add_petitioner_list">
                                        <span class="fa fa-spinner fa-spin fa-3x" style="display:none"></span>
                                        <table id="example" class="display" cellspacing="0" border="1" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>Sr. No. </th>
                                                    <th>Respondent Name</th>
                                                    <th>Designation</th>
                                                    <th>Mobile</th>
                                                    <th>Email</th>
                                                    <th>Address</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              <?php 
                                            $salt=$this->session->userdata('salt'); 
                                            $vals=$this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
                                            if(@$vals[0]->resname!=''){
                                            $petName=$vals[0]->resname;
                                            if (is_numeric($vals[0]->resname)) {
                                                $orgname=$this->efiling_model->data_list_where('master_org','org_id',$vals[0]->resname);
                                                    $petName=$orgname[0]->org_name;
                                                }
                                                
                                                if($vals[0]->res_state!=''){
                                                    $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$vals[0]->res_state);
                                                    $statename= $st2[0]->state_name;
                                                }
                                                if($vals[0]->res_dis!=''){
                                                    $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$vals[0]->res_dis);
                                                    $ddistrictname= $st1[0]->district_name;
                                                }
                                                
                                            ?>
                                            <tr style="color:green">
                                                <td>1</td>
                                                    <td><?php echo $petName; ?>(R-1)</td>
                                                    <td><?php echo $vals[0]->pet_degingnation ?></td>
                                                    <td> <?php echo  '###'.substr(isset($vals[0]->res_mobile)?$vals[0]->res_mobile:'',6,4).'###';  ?></td>
                                                    <td><?php echo emailmask($vals[0]->res_email); ?></td>
                                                    <td><?php echo $vals[0]->res_address; ?>
                                                    &nbsp;<?php echo $ddistrictname; ?>
                                                    &nbsp;<?php echo $statename; ?>&nbsp;<?php echo $vals[0]->res_pin; ?></td>
                                                </tr>
                                            <?php } 
                                            $additionalresparty=$this->efiling_model->data_list_where('sat_temp_additional_res','salt',$salt);
                                            $i=2;
                                            if(!empty($additionalresparty)){
                                                foreach($additionalresparty as $val){
                                                    $resName=$val->res_name;
                                                    if (is_numeric($val->res_name)) {
                                                        $orgname=$this->efiling_model->data_list_where('master_org','org_id',$val->res_name);
                                                        $resName=$orgname[0]->orgdisp_name;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $i; ?></td>
                                                        <td><?php echo $resName; ?>(R-<?php echo $i; ?>)</td>
                                                        <td><?php echo $val->res_degingnation; ?></td>
                                                        <td><?php echo  '###'.substr(isset($val->res_mobile)?$val->res_mobile:'',6,4).'###';  ?></td>
                                                        <td><?php echo emailmask($val->res_email); ?></td>
                                                        <td><?php echo $val->res_address; ?></td>
                                                    </tr>
                                                    <?php 
                                                    $i++; }
                                                }else{
                                                    $val= "<span style='color:Red'>Reccord Not found";
                                                }
                                                
                                                
                                            
                                             ?> 
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php  echo form_fieldset_close();?>
                            </br>
                            <?php
                            $ia_nature1 = explode(',', $ia_nature);
                            if ($ia_nature != '' && !empty($ia_nature1)) {
                                ?>
                                <FIELDSET>
                                    <LEGEND><b>MA Details</b></legend>
                                    <table id="example" class="display" cellspacing="0" border="1" width="100%">
                                        <tbody> 
                                        <tr>
                                            <td width="80%">MA Nature Name</td>                 
                                            <td width="20%">Fees</td>
                                        </tr>
                                        <?php
                                        $fee='';
                                        $ia_nature1 = explode(',', $ia_nature);
                                        $len = sizeof($ia_nature1)-1;
                                        for ($i = 0; $i < $len; $i++) {
                                            if($ia_nature1[$i]!=''){
                                                if (is_numeric($ia_nature1[$i])) {
                                                    $aDetail = $this->efiling_model->data_list_where('moster_ma_nature','nature_code',$ia_nature1[$i]);
                                                }
                                             $ia_nature_name = $aDetail[0]->nature_name;
                                                if ($ia_nature != "") {
                                                    $fee = '1000';
                                                }
                                            }
                                            ?>
                                            <tr>               
                                                <td width="20%"><?php echo $ia_nature_name; ?></td>
                                                <td width="80%"><?php echo $fee; ?></td>
                                            </tr>
                                        <?php   
                                        }?>
                                        </tbody>
                                    </table>
                                </fieldset>
                                </br>
                            <?php  }  ?>
                            <FIELDSET>
                                <LEGEND><b class="fa fa-upload">&nbsp;&nbsp;Uploaded Documents Details :</b></legend>
                                <table id="example" class="display" cellspacing="0" border="1" width="100%">
                                    <thead>                    
                                        <tr>
                                            <th style="width:15%">Party Type</th>                    
                                            <th style="width:60%">Document Type</th>                    
                                            <th style="width:5%">No of Pages</th>                    
                                            <th style="width:15%">Last Update</th>                   
                                            <th style="width:5%">View</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $warr=array('salt'=>$salt,'user_id'=>$UiD,'display'=>'Y');
                                    $docData =$this->efiling_model->list_uploaded_docs('temp_documents_upload',$warr);
                                    if(@$docData) {
                                        foreach ($docData as $docs) {
                                            $document_filed_by = $docs->document_filed_by;
                                            $document_type = $docs->document_type;
                                            $no_of_pages = $docs->no_of_pages;
                                            $file_id = $docs->id;
                                            $update_on = $docs->update_on;
                                            
                                            echo'<tr>
                                                    <td>'.$document_filed_by.'</td>
                                                    <td>'.$document_type.'</td>
                                                    <td>'.$no_of_pages.'</td>
                                                    <td>'.date('d-m-Y H:i:s', strtotime($update_on)).'</td>
                                                    <td id="updDocId"><a href="javascript:void();" tagId="'.$file_id.'"><i class="fa fa-eye"></i></a></td>
                                            </tr>';
                                        }
                                    }
                                    else echo'<tr><td colspan=5 class="text-danger text-center h3">No document uploaded!</td></tr>';
                                    ?>
                                    </tbody>
                                </table>
                            </fieldset>
              

                            <fieldset>
                                <LEGEND><b>Fee Details</b></legend>
                                <table id="example" class="display" cellspacing="0" border="1" width="100%">
                                    <tbody>
                                    <?php
					    $courtfee=0;
					    $count=0;
					    $feeval=0;
					    $ipenalty='';
					    $apealtype='';
					    $opauthority='';
					    $regulatorname='';
					    $orderpassingauthority='';
                        $st=$this->efiling_model->data_list_where('sat_temp_appellant','salt', $salt);
                        if(!empty($st)){
                            $appname=isset($st[0]->pet_name)?$st[0]->pet_name:''; 
                            $ipenalty = $st[0]->ipenalty;
                            $apealtype = $st[0]->apeal_type;
                            $opauthority = $st[0]->opauthority; 
                            $stfee=$this->efiling_model->data_list_where('master_regulator','order_pass_auth_id', $opauthority);
                            if(!empty($stfee)){
                                $regulatorfeeval=$stfee[0]->fee;
                                $orderpassingauthority=$stfee[0]->order_passing_authority;
                                $regulatorname=$stfee[0]->order_passing_authority;
                            }
                        }

                        $additionalparty=$this->efiling_model->data_list_where('sat_temp_additional_party','salt',$salt);
                        $val=0;
                        if($appname!=''){
                            $val='1';
                        }
                        $count= count($additionalparty)+$val;
                        $applicantfee=$this->efiling_model->calculateapp($count);
                        
                        
                        $exclusiveamount='500';
                        if($regulatorname=='Adjudicating Officer'){
                            if($ipenalty<'10000'){
                                $courtfee='500';
                            }
                            
                            if($ipenalty>'10000'){  
                                $courtfee='1200';
                            }
                            
                            if($ipenalty=='100000'){
                                $courtfee=(int)$feeval;
                            }
                            
                            if($ipenalty>'100000'){
                                $var=$ipenalty-100000;
                                $var2=$var/100000;
                                $var3=ceil($var2);
                                $courtfee=($var3*500)+1200;
                            }
                        }
                        
                        if($regulatorname!='Adjudicating Officer'){
                            $courtfee=$applicantfee;
                        }
                        ?>
                					
                   
                                        <table class="table">
                                          <thead>
                                             <tr style="background-color: #ebdada">
                                              <th scope="col">S.NO</th>
                                              <th scope="col">Order passing Authority </th>
                                              <th scope="col">Fees</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr>
                                              <td><?php echo htmlspecialchars(1);?></td>
                                              <td><?php echo $orderpassingauthority;?></td>
                                              <td><?php echo $courtfee;?></td>
                                            </tr>
                                          </tbody>
                                        </table>
                            <input type="hidden" name="total_amount_amount" id="total_amount_amount" value="<?php echo $total; ?>">
               		 		<?= form_fieldset_close(); ?>
                            <div class="row">
                                <div class="offset-md-8 col-md-4">
                                	<?php
                        			echo '<i class="icon-file-plus" style="position: absolute; margin: 9px 4px; z-index: 9; color: white;"></i>'.
                                        form_submit(['value'=>'Save & Next','class'=>'btn btn-success','id'=>'finalsave','style'=>'padding-left:24px;']).
                                         '<i class="icon-trash-alt" style="position: absolute; z-index: 9; margin: 10px 4px; color:white;"></i>'.
                        			     form_reset(['value'=>'Reset/Clear','class'=>'btn btn-danger','style'=>'padding-left: 24px;']);
                                        //  .form_button(['id'=>'','value'=>'false','content'=>'&nbsp;Next','class'=>'icon-arrow-right8 btn btn-primary']);
                                	?>
                                </div>
                            </div>
                		</div>
                		<div class="content clearfix" id="secondDiv">
                		</div>
          			  <?= form_close();?>    
       			 </div>
			</div>
		</div>	
		
		 <div class="modal fade bd-example-modal-lg" id="getCodeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
             <div class="modal-dialog modal-lg">
                <!-- Modal content-->
                    <div class="modal-content">
                     <form action="certifiedsave.php" method="post">
                          <div class="modal-header" style="background-color: cadetblue;">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div id="viewsss">
                          </div>
                      </form>
                  </div>
             </div>
        </div>
        
<!-- Display Uploaded file PDF -->
<div class="modal fade" id="updPdf" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="min-width: 90%;">
        <div class="modal-content">
            <div class="modal-header">
            </div>
            <div class="modal-body">                   
                <iframe style="width: 100%; height: 560px" id="frameID" src=""></iframe>
            </div>
        </div>
    </div>
</div>
                
<script>
function dicript(textval){
    var DataEncrypt = textval;
    var DataKey = CryptoJS.enc.Utf8.parse("01234567890123456789012345678901");
    var DataVector = CryptoJS.enc.Utf8.parse("1234567890123412");
    var decrypted = CryptoJS.AES.decrypt(DataEncrypt, DataKey, { iv: DataVector });        
    return  CryptoJS.enc.Utf8.stringify(decrypted);
}

$('#updDocId > a').click(function(e){
    e.preventDefault();
    var updId='', href='';
    updId=$(this).attr('tagId');
    $.ajax({
        type: 'post',
        url: base_url+'uploaded_docs_display',
        data: {docId: updId},
        dataType: 'json',
        success: function(rtn){
            debugger;
            if(rtn.error == '0'){
               var valurl= rtn.data;
                href=base_url+'order_view/'+btoa(valurl);   
   
                $('#frameID').attr("src", href + "#view=VFit" + "&toolbar=0" + "&navpanes=0");   
            }
            else $.alert(rtn.error);
        }
    });
    $('#updPdf').modal('show');
});

function printPage() {
    change("testdiv", "true");
    window.print();
}
function popitup(filingno) {
  	 var dataa={};
       dataa['filingno']=filingno,
        $.ajax({
            type: "POST",
            url: base_url+"/filingaction/filingPrintSlip",
            data: dataa,
            cache: false,
            success: function (resp) {
          	  $("#getCodeModal").modal("show");
           	  document.getElementById("viewsss").innerHTML = resp; 
            },
            error: function (request, error) {
				$('#loading_modal').fadeOut(200);
            }
        }); 
  	  
  }
</script>

<script type="text/javascript">
       function printPage(){
            var divContents = $("#dvContainer").html();
            var printWindow = window.open('', '', 'height=400,width=800');

            printWindow.document.write(divContents);

            printWindow.document.close();
            printWindow.print();
       }
    </script>
    
    <?php $this->load->view("admin/footer"); ?>