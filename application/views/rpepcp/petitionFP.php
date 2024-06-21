<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view("admin/header");
$this->load->view("admin/sidebar");
$this->load->view("admin/stepsrpepcp");
$salt=$this->session->userdata('rpepcpsalt');
$token= $this->efiling_model->getToken();
$userdata=$this->session->userdata('login_success');
$user_id=$userdata[0]->id;
$partytype='';
$filingno='';
$tab_no='';
$type='';
$party_ids='';
$mainparty = '';
$app_party_id='';
$app_priority='';
$res_partyid='';
$res_priority='';
$partflagres='';
$partflagpet='';
if($salt!=''){
    $basicrp= $this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
    $app_party_id=isset($basicrp[0]->app_party_id)?$basicrp[0]->app_party_id:'';
    $app_priority=isset($basicrp[0]->app_priority)?$basicrp[0]->app_priority:'';
    $res_partyid=isset($basicrp[0]->res_partyid)?$basicrp[0]->res_partyid:'';
    $res_priority=isset($basicrp[0]->res_priority)?$basicrp[0]->res_priority:'';
    $subject=isset($basicrp[0]->subject)?$basicrp[0]->subject:'';
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
    $partyType=isset($basicrp[0]->partyType)?$basicrp[0]->partyType:'';
    $party_ids=isset($basicrp[0]->party_ids)?$basicrp[0]->party_ids:'';
    $filingno=isset($basicrp[0]->filing_no)?$basicrp[0]->filing_no:'';
    $tab_no=isset($basicrp[0]->tab_no)?$basicrp[0]->tab_no:'';
    $type=isset($basicrp[0]->type)?$basicrp[0]->type:'';
    $partyidval=isset($basicrp[0]->party_ids)?$basicrp[0]->party_ids:'';
    $pid = rtrim($partyidval, ',');
    $partyid=isset($basicrp[0]->party_ids)?$basicrp[0]->party_ids:'';
    $partyType=isset($basicrp[0]->partyType)?$basicrp[0]->partyType:'';
    if($partyid!=''){
        $partyid=explode(',', $partyid);
    }
    
}
$partyType=isset($_REQUEST['partyType'])?$_REQUEST['partyType']:$partyType;
if($partyType=='1'){
    $partytype='1';
}
if($partyType=='2'){
    $partytype='2';
}
if($partyType=='3'){
    $partytype='3';
}
$flag='';
$flag1='';
if($partytype!='3'){
    if($partyid[0]==""){
        echo "please Select party type";die;
    }
    if ($partyid[0] != "" ) {
        if ($partytype == 2) {
            $nameparty = 'Applicant';
            $flag = 1;
        }if ($partytype == 1) {
            $nameparty = 'Respondent';
            $flag = 2;
        }
    }
}

?>
<div class="content" style="padding-top:0px;">
<div class="card"  id="dvContainer" style="width: 100%; padding: 0px 12px;border-top: 0px;margin-top: 0px;  border-top-left-radius: 0px;">
    <form action="<?php echo base_url(); ?>/pay_page" target="_blank" class="wizard-form steps-basic wizard clearfix" id="finalsubmit" autocomplete="off" method="post" accept-charset="utf-8">
       <div class="content clearfix" id="mainDiv1">
        <input type="hidden" id="saltNo" name="saltNo" value="<?php echo $salt; ?>">
        <input type="hidden" name="token" value="<?php echo $token; ?>" id="token">
        <input type="hidden" id="tabno" name="tabno" value="9"> 
        <input type="hidden" id="type" name="type" value="<?php echo $type; ?>"> 
        <?= form_fieldset().
        '<div class="date-div text-success">'.date('D M d, Y H:i:s').'&nbsp;IST</small></div>'; ?>
            <?php 
            $dfrval='';
            $val= $this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
            foreach($val as $row) {
                // Basic Detail section 
                $totalNoAnnexure=$row->totalNoAnnexure;
                $totalNoia=$row->totalNoia;
                $orderdate=date('d/m/Y',strtotime($row->order_date));
                $filing_no=$row->filing_no;
                if($filing_no!=''){
                    $filing_No = substr($filing_no, 5, 6);
                    $filing_No = ltrim($filing_No, 0);
                    $filingYear = substr($filing_no, 11, 4);
                    $dfrval= "DFR/$filing_No/$filingYear";
                }
                $entry_date=date('d/m/Y',strtotime($row->entry_date));
                $type=$row->type;
                // Basic Detail section 
            }
        ?>
        <div class="row">
          <div class="card w-100">
      	<FIELDSET> 
          <legend><b>Basic Detail</b></legend>
            <table id="example" class="display"  border="1" width="100%">
              <tbody>
                    <tr>
                        <td width="16%"><b>Filing No</b></td>
                        <td width="16%"><?php echo $dfrval; ?></td>
                        <td width="16%"><b>Case Type</b></td>
                        <td width="16%"><?php echo $type; ?></td>
                        <td width="16%"><b>Filing Date</b></td>
                        <td width="16%"><?php echo $entry_date; ?></td>
                    </tr>
                    <tr>
                        <td width="16%"><b>Order date</b></td>
                        <td width="16%"><?php echo $orderdate; ?></td>
                        <td width="16%"><b>Total No IA</b></td>
                        <td width="16%"><?php echo $totalNoia; ?></td>
                        <td width="16%"><b>Total Annexure</b></td>
                        <td width="16%"><?php echo $totalNoAnnexure; ?></td>
                    </tr>   
                </table>
                <div>
                <lable><b>Subject-</b>&nbsp;&nbsp;</lable> <?php echo $subject; ?>
            </FIELDSET>
            
            
            <FIELDSET> 
         	 <legend><b>Petitioner Details</b></legend>
            	<div class="">
                    <table datatable="ng" id="examples"   class="table table-striped table-bordered" cellspacing="0"  width="100%">
                        <tbody>
                   			 <tr>
                   			 <?php 
                   			    $petName='';
                   			    $partyids=explode(',',$party_ids);
                   			    $count=count($partyids);
                   			    $i=0;
                   			    for($i=0;$i<=$count;$i++){
                   			        $petName='';
                   			        if(@$partyids[$i]!=""){
                       			        if($partyids[$i]=='1'){
                                            $st =$this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
                                            $petName = isset($st[0]->pet_name)?$st[0]->pet_name:'';
                                        } 
                                        if($partyids[$i]!='1'){
                                            $st =$this->efiling_model->data_list_where('additional_party','party_id',$partyids[$i]);
                                            foreach($st as $valt){
                                                $petName=  $valt->pet_name;
                                            }
                                        }
                                    ?>
                        			<td><?php echo $petName;  ?></td>
                    		<?php }
                   			    }?>
                    		</tr>
                        </tbody>
                    </table>
                </div>
            </FIELDSET>
            
            .
        
         <FIELDSET> 
            <legend><b>Priority</b></legend>
               <div class="col-md-12" >
                         <div class="table-responsive" id="va1">
                                <table id="examples"   class="table table-striped table-bordered"  width="100%">
                                        <thead>
                                            <tr style="background-color: #de8181;color: #fafafa;">
                                                <th width="800px">Applicant Name</th>
                                                <th width="100px">#</th>
                                                <th width="100px">Priority No</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                <?php
                                 $apid=explode(',', $app_party_id);
                                 $apri=explode(',', $app_priority);
                                 $valxxa=array_combine($apid, $apri);
                                 
                                 $srn='1';
                                  $len = sizeof($partyid);
                                  $len = $len - 1;
                                  for ($k = 0; $k < $len; $k++) {
                                    if ($partyid[$k] == 1) {
                                        $appsql =$this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
                                        $checked='';
                                        $sr='';
                                        foreach($appsql as $row){
                                            if($partytype == 2) {
                                                $mainparty = $row->res_name;
                                                $partflagres = 999;
                                                if($apid[$k]==1){
                                                    $checked='checked';
                                                    $sr=$apri[$k];
                                                }
                                            }
                                            if ($partytype == 1) {
                                                $partflagpet = 999;
                                                $mainparty = $row->pet_name;
                                                if($apid[$k]==1){
                                                    $checked='checked';
                                                    $sr=$apri[$k];
                                                }
                                            }
                                            if ($partytype == 3) {
                                                $partflagpet = 999;
                                                $mainparty = $row->pet_name;
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo $mainparty; ?></td>
                                            <td><input type="checkbox" name="patyAddIdmain"  value="<?php echo '1'; ?>" <?php echo $checked; ?> disabled></td>
                                            <td><input type="text" size='3' value="<?php echo $sr; ?>" name="numbermian" onkeyup="valcheck();" readonly></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                
                                $sqladd1 = "select * from additional_party where filing_no='$filingno' and  party_id IN($pid)";
                                $query=$this->db->query($sqladd1);
                                $data = $query->result();
                                foreach($data as $rval){
                                    $srv='';
                                    $id = $rval->party_id;
                                    $pet_name11 = $rval->pet_name;
                                    $checked='';
                                    if(in_array($id, $apid)){
                                        $checked='checked';
                                        $srv=$valxxa[$id];
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($pet_name11); ?></td>
                                         <td><input type="checkbox" name="patyAddIdmain" value="<?php echo $id; ?>" <?php echo $checked; ?> disabled></td>
                                        <td><input type="text" size='3' value="<?php echo $srv; ?>" name="numbermian" onkeyup="valcheck();" readonly></td>
                                    </tr>
                                    <?php  } ?>
                                    </tbody>
                                </table>
                            </div>
                            
        
        
        
        
        
        
        
        
                            <div class="table-responsive" id="va2">
                                <table class="table table-bordered" width="100%">
                                    <thead>
                                        <tr style="background-color: #de8181;color: #fafafa;">
                                            <th width="800px">Respondent Name</th>
                                            <th width="100px">#</th>
                                            <th width="100px">Priority No</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $rpid=explode(',', $res_partyid);
                                    $rpri=explode(',', $res_priority);
                                    $valxx=array_combine($rpid, $rpri);
                                    
                                    if ($partflagpet != 999) {
                                        $sqlr = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
                                        foreach($sqlr as $rval){
                                            $checked='';
                                            if ($partytype != 2) {
                                                $mainparty1 = $rval->res_name;
                                                $checked='checked';
                                                $srnv=$valxx['1P'];
                                            }
                                            if ($partytype != 1) {
                                                $mainparty1 =$rval->pet_name;
                                                $checked='checked';
                                                $srnv=$valxx['1P'];
                                            }
                                        }
                                        ?>
                                        <tr> 
                                            <td><?php echo htmlspecialchars($mainparty1); ?></td>
                                            <td><input type="checkbox" name="patyAddId1" value="<?php echo '1P'; ?>" <?php echo $checked; ?> disabled></td>
                                            <td><input type="text" size='3' value="<?php echo $srnv; ?>" name="number1" onkeyup="valcheck1();" readonly></td>
                                        </tr>
                                        <?php
                                    }
                                    
                                    
                                    $sqladd1 = "select * from additional_party where filing_no='$filingno' and  party_flag ='$flag'";
                                    $query=$this->db->query($sqladd1);
                                    $data = $query->result();
                                    foreach($data as $row){
                                        $checked='';
                                        $srnv='';
                                        $id = $row->party_id;
                                        $pet_name11 = $row->pet_name;
                                        if(in_array($id, $rpid)){
                                            $checked='checked';
                                            $srnv=$valxx[$id];
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($pet_name11); ?></td>
                                              <td><input type="checkbox" name="patyAddId1" value="<?php echo $id; ?>" <?php echo $checked; ?> disabled></td>
                                            <td><input type="text" size='3' value="<?php echo $srnv; ?>" name="number1" onkeyup="valcheck1();" readonly></td>
                                        </tr>
                                        <?php
                                    }
                                    
                                    if ($partflagres != 999) {
                                        $sql = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);    
                                        foreach($sql as $rowr){
                                            $checked='';
                                            $srnv='';
                                            $mainpartyres1 = $rowr->res_name;
                                            if(in_array('1R',$rpid)){
                                                $checked='checked';
                                                $srnv=$valxx['1R'];
                                            }
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($mainpartyres1); ?></td>
                                            <td><input type="checkbox" name="patyAddId1" value="<?php echo '1R'; ?>" <?php echo $checked; ?> disabled></td>
                                            <td><input type="text" size='3' value="<?php echo $srnv; ?>" name="number1" onkeyup="valcheck1();" readonly></td>
                                        </tr>
                                        <?php
                                    }
                                    
                                    
                                    if ($partytype == 1)
                                        $flag1 = 1;
                                        $checked='checked';
                                        $sr='1';
                                     if ($partytype == 2)
                                        $flag1 = 2;
                                        $checked='checked';
                                        $sr='1';
                                    
                                    $sqladd1 = "select * from additional_party where filing_no='$filingno' and party_flag='$flag1' and  party_id NOT IN($pid)";
                                    $query=$this->db->query($sqladd1);
                                    $data = $query->result();
                                    foreach($data as $row){
                                        $id = $row->party_id;
                                        $pet_name11 =$row->pet_name;
                                        ?>          
                                        <tr>
                                            <td><?php echo htmlspecialchars($pet_name11); ?></td>
                                             <td><input type="checkbox" name="patyAddId1" value="<?php echo $id; ?>" <?php echo $checked; ?> disabled></td>
                                            <td><input type="text" size='3' value="<?php echo $sr; ?>" name="number1" onkeyup="valcheck1();" readonly></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
     			</FIELDSET>    
        






















	<FIELDSET> 
   		 <legend><b>Advocate</b></legend>
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
    	        </tr>
	        </thead>
	        <tbody>';
           $html.='</tbody>';
           $vals=$this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
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
                           $statename= $st2[0]->state_name;
                       }
                       if($vals[0]->cou_dist!=''){
                           $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$vals[0]->cou_dist);
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
        	        <td>'.$adv_mobile.'</td>
        	        <td>'.$email.'</td>
                </tr>';
           }
            $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
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
        	        </tr>';
                   $i++;
               }
           }
           echo  $html;
	         ?>
         </table>
		</FIELDSET>





        <FIELDSET> 
        	<legend><b>IA Details</b></legend>
        	<table  id="examples"  class="table table-striped table-bordered" cellspacing="0"  width="100%">
                <thead>
                    <tr><th>#</th>                  
                        <th>IA Nature Nam</th>
                        <th>Fees</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if($salt!=''){
                        $basicrp= $this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
                        $ianature=isset($basicrp[0]->iaNature)?$basicrp[0]->iaNature:'';
                    }
                    $ianocc=explode(',', $ianature);
                    $i=1;
                    foreach($ianocc as $row) {
                        if($row!=''){
                        $aDetail= $this->efiling_model->data_list_where('moster_ma_nature','nature_code',$row);
                      
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo htmlspecialchars($aDetail[0]->nature_name);?></td>
                        <td><?php echo htmlspecialchars($aDetail[0]->fee);?></td>
                    </tr>
                <?php  $i++;} }?>
                </tbody>
      	    </table>
        </FIELDSET>


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
            
                $warr=array('salt'=>$salt,'user_id'=>$user_id,'display'=>'Y');
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

        <FIELDSET> 
        	<legend><b>Court Fee Detail</b></legend>
        	<table id="examples"  class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th>Sr.</th>
                    <th>Fee Document Name</th>
                    <th>Fee</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $aDetail = $this->efiling_model->data_list('master_fee_detail');
                $fgfg = 1;
                $i = 0;
                foreach($aDetail as $row) {
                    $fee='';
                    if($row->doc_code=='11'){
                        $fee=$row->doc_fee*$anx;
                    }
                    if($row->doc_code=='9'){
                        $fee=$row->doc_fee;
                    }                                       
                    if($row->doc_code=='7'){
                        $fee=$row->doc_fee*1;
                    }
                    $val='Checked';
                    ?>
                    <tr>
                        <td> <?php echo $fgfg; ?></td>
                        <td> <?php echo htmlspecialchars($row->doc_name); ?></td>
                        <td> <?php echo '0'; ?></td>
                    </tr>
                    <?php $fgfg++;
                    $i++;
                } ?>
                </tbody>
            </table>
        </FIELDSET>
	</div>
	<?= form_close();?>    
 </div>
<div class="row">
    <div class="offset-md-8 col-md-4" style="margin-left: 80.66667%;">
        <input  type="button" value="Next" class="btn btn-success" onclick="fpsubmit();">
		&nbsp;&nbsp;<input type="reset" value="Reset/Clear" class="btn btn-danger">
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
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" id="frame">                   
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
                 var valurl= dicript(rtn.data);
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


function fpsubmit(){
    var salt = document.getElementById("saltNo").value;
    var token = document.getElementById("token").value;
    var tabno= document.getElementById("tabno").value;
    var type= document.getElementById("type").value;
    var dataa = {};
	dataa['salt']  =salt;
	dataa['token'] =token;
	dataa['tabno'] =tabno;
	dataa['type'] =type;
	$.ajax({
        type: "POST",
        url: base_url+'fpsave',
        data: dataa,
        cache: false,
        beforeSend: function(){
        	$('#other_feesave').prop('disabled',true).val("Under proccess....");
        },
        success: function (resp) {
        	var resp = JSON.parse(resp);
        	if(resp.data=='success') {
        		setTimeout(function(){
                    window.location.href = base_url+'petitionPay';
                 }, 250);
        	}
        	else if(resp.error != '0') {
        		$.alert(resp.error);
        	}
        },
        error: function(){
        	$.alert("Surver busy,try later.");
        },
        complete: function(){
        	$('#other_feesave').prop('disabled',false).val("Submit");
        }
    }); 
}

function printPage(){
    var divContents = $("#dvContainer").html();
    var printWindow = window.open('', '', 'height=400,width=800');

    printWindow.document.write(divContents);

    printWindow.document.close();
    printWindow.print();
}
</script>
<?php $this->load->view("admin/footer"); ?>