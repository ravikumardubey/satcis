<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Caveate extends CI_Controller {
    function __construct() {error_reporting(0);
        parent::__construct();
        $this->load->model('Admin_model','admin_model');
        $this->load->model('Efiling_model','efiling_model');
		$this->userData = $this->session->userdata('login_success');
		$userLoginid = $this->userData[0]->username;
		if(empty($userLoginid)){
			redirect(base_url(),'refresh');
		}
		
		$uri_request=$_SERVER['REQUEST_URI'];
		$url_array=explode('?',$uri_request);
		$parameters=@$url_array[1];
		$parameters_array=explode('&',$parameters);
		$spcl_char=['<'=>'','>'=>'','/\/'=>'','\\'=>'','('=>'',')'=>'','!'=>'','^'=>'',"'"=>''];
		for($i=0; $i < count($parameters_array); $i++) :;
		$getPara_array=explode("=",$parameters_array[$i]);
		$paraName=@$getPara_array[0];
		$getPvalue=@$getPara_array[1];
		$_REQUEST[$paraName]=htmlspecialchars($getPvalue);
		endfor;
		foreach($_REQUEST as $key=>$val):;
		if(is_array($val)){
		    foreach($val as $key1=>$val1):;
		    if(is_array($val1)){
		        foreach($val1 as $key2=>$val2):;
		        if(is_array($val2)) {
		            $innerData[$key1][$key2]=$val2;
		        }
		        else    $innerData[$key1][$key2]=htmlspecialchars(strtr($val2, $spcl_char));
		        endforeach;
		    }
		    else $innerData[$key1]=htmlspecialchars(strtr($val1, $spcl_char));
		    endforeach;
		    $_REQUEST[$key]=$innerData;
		}
		else $_REQUEST[$key]=htmlspecialchars(strtr($val, $spcl_char));
		endforeach;
		
		$logvvalidate=$this->admin_model->logvalidate();
		if($logvvalidate==false){
		    $this->session->unset_userdata('login_success');
		    redirect(base_url());
		}
    }
    


    

    function cav_checklist(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('salt');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['checklist']= $this->efiling_model->data_list_where('master_checklist','status','1');
            $this->load->view('caveat/cav_checklist',$data);
        }
    }
    function cav_payment(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('salt');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['datacomm']='';
            $this->load->view('caveat/cav_payment',$data);
        }
    }
    function cav_finalprivew(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('salt');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['datacomm']='';
            $this->load->view('caveat/cav_finalprivew',$data);
        }
    }
    function cav_receipt(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('salt');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['datacomm']= '';
            $this->load->view('caveat/cav_receipt',$data);
        }
    }
    
    
    
    function cavdetailsave(){
        $saltval='';
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $commission=htmlentities($_REQUEST['commission']);
        $case_type=htmlentities($_REQUEST['case_type']);
        $case_no=htmlentities($_REQUEST['case_no']);
        $type=htmlentities($_REQUEST['subtype']);
        $case_year=htmlentities($_REQUEST['case_year']);
        $decision_date=htmlentities($_REQUEST['decision_date']);
        $cudate=date('Y-m-d');
        $subtoken=$this->session->userdata('submittoken');
        $token=htmlentities($_REQUEST['token']);
        $this->form_validation->set_rules('decision_date', 'Please select date');
        
        $this->form_validation->set_rules('decision_date','Please select date ','trim|required|min_length[1]|max_length[11]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('commission','Please select commission','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('case_type','Select case type','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('case_no','Enter case number','trim|required|min_length[1]|max_length[4]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('case_year','Enter case year.','trim|required|min_length[1]|max_length[4]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $salt='';
        if($saltval==''){
            $verify_salt=$this->db->select('salt')
            ->where(['user_id'=>$user_id,'year'=>date('Y')])
            ->get('salt_tbl')
            ->row()
            ->salt;
            $verify_salt=(int)$verify_salt;
            if($verify_salt == 0) {
                $data=['salt'=>1,'year'=>date('Y'),'user_id'=>$user_id];
                $this->db->insert('salt_tbl',$data);
            }
            elseif($verify_salt > 0) {
                $verify_salt=$verify_salt + 1;
                $data=['salt'=>$verify_salt];
                $wcond=['year'=>date('Y'),'user_id'=>$user_id];
                $this->db->set($data)->where($wcond)->update('salt_tbl');
            }
            $salt=$verify_salt.date('Y');
            $this->session->set_userdata('cavsalt',$salt);
        }
        if($subtoken==$token){
            $postdata=array(
                'salt'=>$salt,
                'commission'=>$commission,
                'case_no'=>$case_no,
                'case_type'=>$case_type,
                'case_year'=>$case_year,
                'decision_date'=>date('Y-m-d',strtotime($decision_date)),
                'user_id'=>$user_id,
                'type'=>$type,
            );
            $st=$this->efiling_model->insert_query('temp_caveat', $postdata);
            if($st){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>$massage,'massage'=>$massage,'error'=>'1']);
        }
    }

    
    function caveatorsave(){
        $cudate=date('Y-m-d');
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $salt=$this->session->userdata('cavsalt');
        $caveatorName=htmlentities($_REQUEST['select_org_res']);
        $caveatoraddress=htmlentities($_REQUEST['addressTparty']);
        $caveatorState=htmlentities($_REQUEST['dstate']);
        $caveatorDistrict=htmlentities($_REQUEST['district']);
        $caveatorPin=htmlentities($_REQUEST['pin']);
        $caveatorEmail=htmlentities($_REQUEST['email']);
        $caveatorPhone=htmlentities($_REQUEST['phone']);
        $caveatorMob=htmlentities($_REQUEST['mob']);
        $caveatorPrayer=htmlentities($_REQUEST['Prayer']);
        $caveatorType=htmlentities($_REQUEST['partyType']);
        $caveatorSubType=htmlentities($_REQUEST['type']);
        $caveatorid=htmlentities($_REQUEST['caveatorid']);
        $subtoken=$this->session->userdata('submittoken');
        $token=htmlentities($_REQUEST['token']);
        $tab_no=htmlentities($_REQUEST['tab_no']);
        if($caveatorid!=''){
            $caveatorName=htmlentities($_REQUEST['caveatorid']);
        }
        $this->form_validation->set_rules('select_org_res','Please select date ','trim|required|min_length[1]|max_length[250]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('dstate','Please select state','trim|required|min_length[1]|max_length[4]|htmlspecialchars|regex_match[/^[0-9]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('district','Please select district','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('pin','Enter Pin number','trim|required|min_length[1]|max_length[6]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('mob','Enter mobile number.','trim|required|min_length[1]|max_length[10]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('email','Enter email address.','trim|required|min_length[1]|max_length[50]|htmlspecialchars');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        if($subtoken==$token){
            $postdata=array(
                'caveat_name'=>$caveatorName,
                'caveat_address'=>$caveatoraddress,
                'caveat_state'=>$caveatorState,
                'caveat_district'=>$caveatorDistrict,
                'caveat_pin'=>$caveatorPin,
                'caveat_email'=>$caveatorEmail,
                'caveat_phone'=>$caveatorPhone,
                'caveat_mobile'=>$caveatorMob,
                'prayer'=>$caveatorPrayer,
                'tab_no'=>$tab_no,
                'partyType'=>$caveatorType,
                'entry_date'=>$cudate,
                'user_id'=>$user_id,
            );
            $st1=$this->efiling_model->update_data('temp_caveat', $postdata,'salt', $salt);  
            if($st1){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>$massage,'massage'=>$massage,'error'=>'1']);
        }
    }
    
    
    function caveateesave(){
        $cudate=date('Y-m-d');
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $salt=$this->session->userdata('cavsalt');
        $caveatorName=htmlentities($_REQUEST['select_org_res']);
        $caveatoraddress=htmlentities($_REQUEST['addressTparty']);
        $caveatorState=htmlentities($_REQUEST['dstate']);
        $caveatorDistrict=htmlentities($_REQUEST['district']);
        $caveatorPin=htmlentities($_REQUEST['pin']);
        $caveatorEmail=htmlentities($_REQUEST['email']);
        $caveatorPhone=htmlentities($_REQUEST['phone']);
        $caveatorMob=htmlentities($_REQUEST['mob']);
        $caveatorType=htmlentities($_REQUEST['partyType']);
        $caveatorid=htmlentities($_REQUEST['caveatorid']);
        $subtoken=$this->session->userdata('submittoken');
        $token=htmlentities($_REQUEST['token']);
        $tab_no=htmlentities($_REQUEST['tab_no']);
        if($caveatorid!=''){
            $caveatorName=htmlentities($_REQUEST['caveatorid']);
        }
        $this->form_validation->set_rules('select_org_res','Please select date ','trim|required|min_length[1]|max_length[250]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('dstate','Please select state','trim|required|min_length[1]|max_length[4]|htmlspecialchars|regex_match[/^[0-9]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('district','Please select district','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('pin','Enter Pin number','trim|required|min_length[1]|max_length[6]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('mob','Enter mobile number.','trim|required|min_length[1]|max_length[10]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('email','Enter email address.','trim|required|min_length[1]|max_length[50]|htmlspecialchars');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        if($subtoken==$token){
            $postdata=array(
                'caveatee_name'=>$caveatorName,
                'caveatee_address'=>$caveatoraddress,
                'caveatee_state'=>$caveatorState,
                'caveatee_district'=>$caveatorDistrict,
                'caveatee_pin'=>$caveatorPin,
                'caveatee_email'=>$caveatorEmail,
                'caveatee_phone'=>$caveatorPhone,
                'caveatee_mobile'=>$caveatorMob,
                'tab_no'=>$tab_no,
                'partyTypecaveatee'=>$caveatorType,
                'entry_date'=>$cudate,
                'user_id'=>$user_id,
            );
            $st1=$this->efiling_model->update_data('temp_caveat', $postdata,'salt', $salt);
            if($st1){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>$massage,'massage'=>$massage,'error'=>'1']);
        }
    }
    
    
    function addCouncelCaveate(){
        $advName='';
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $salt=$this->session->userdata('cavsalt');
        $subtoken=$this->session->userdata('submittoken');
        $token=isset($_REQUEST['token'])?$_REQUEST['token']:'';
        $id=isset($_REQUEST['id'])?$_REQUEST['id']:'';
        $partyType=isset($_REQUEST['partyType'])?$_REQUEST['partyType']:'';
        $advType=isset($_REQUEST['advType'])?$_REQUEST['advType']:'';
        $councilCode=isset($_REQUEST['councilCode'])?$_REQUEST['councilCode']:'';
        $edittype=isset($_REQUEST['action'])?$_REQUEST['action']:'';
        $advType=isset($_REQUEST['advType'])?$_REQUEST['advType']:'';
        
        if($salt!=''){
            $hscquery = $this->efiling_model->data_list_where('temp_caveat','salt',$salt);
            $petadvName = $hscquery[0]->council_code;
        }
        
         if($advType=='1'){
            if(is_numeric($councilCode)){
                $hscquery = $this->efiling_model->data_list_where('master_advocate','adv_code',$councilCode);
                $advName = $hscquery[0]->adv_name;
            }
         }
         if($advType=='2'){
            if(is_numeric($councilCode)){
                $hscquery = $this->efiling_model->data_list_where('efiling_users','id',$councilCode);
                $advName = $hscquery[0]->fname.' '.$hscquery[0]->lname;
            }
         }
         
         
         
          if($token==$subtoken){
              if($edittype=='add' && $petadvName!=''){
                $array = array(
                    'salt'=>$salt,
                    'adv_name'=>$advName,
                    'counsel_add'=>$_REQUEST['counselAdd'],
                    'counsel_pin'=>$_REQUEST['counselPin'],
                    'counsel_mobile'=>$_REQUEST['counselMobile'],
                    'counsel_email'=>$_REQUEST['counselEmail'],
                    'council_code'=>$councilCode,
                    'counsel_fax'=>$_REQUEST['counselFax'],
                    'counsel_phone'=>$_REQUEST['counselPhone'],
                    'user_id'=>$user_id,
                    'adv_district'=>$_REQUEST['cddistrict'],
                    'adv_state'=>$_REQUEST['cdstate'],
                    'entry_time'=>date('Y-m-d'),
                    'advType'=>$advType,
                );
                $st = $this->efiling_model->insert_query('sat_temp_add_advocate',$array);
                $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
                $getAdvocatelist=$this->getAdvocatelist($advocatelist,$salt);
                if($st){
                    echo json_encode(['data'=>'success','display'=>$getAdvocatelist,'error'=>'0']);die;
                }
            }

            if($edittype=='add' && $petadvName==''){
                $array = array(
                    'salt'=>$salt,
                    'council_name'=>$advName,
                    'council_address'=>$_REQUEST['counselAdd'],
                    'counsel_pin'=>$_REQUEST['counselPin'],
                    'council_mobile'=>$_REQUEST['counselMobile'],
                    'council_email'=>$_REQUEST['counselEmail'],
                    'council_code'=>$councilCode,
                    'counsel_fax'=>$_REQUEST['counselFax'],
                    'council_phone'=>$_REQUEST['counselPhone'],
                    'user_id'=>$user_id,
                    'adv_district'=>$_REQUEST['cddistrict'],
                    'adv_state'=>$_REQUEST['cdstate'],
                    'entry_time'=>date('Y-m-d'),
                    'advType'=>$advType,
                );
               
                $st1=$this->efiling_model->update_data('temp_caveat', $array,'salt', $salt);
                $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
                $getAdvocatelist=$this->getAdvocatelist($advocatelist,$salt);
                if($st1){
                    echo json_encode(['data'=>'success','display'=>$getAdvocatelist,'error'=>'0']);die;
                }
            }
         }
    }
    

    
    function deleteAdvocateCaveate(){
        $msg='';
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $salt= $this->session->userdata('cavsalt');
        $subtoken=$this->session->userdata('submittoken');
        $token=$_REQUEST['token'];
        $id=$_REQUEST['id'];
        if($token==$subtoken){
            $delete= $this->efiling_model->delete_event('sat_temp_add_advocate', 'id', $id);
            if($delete){
                $msg="Record successfully  deleted !";
                $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
                $getAdvocatelist=$this->getAdvocatelist($advocatelist,$salt);
                echo json_encode(['data'=>'success','display'=>$getAdvocatelist,'error'=>'0']);die;
            }
        }else{
            $msg="Something went wrong";
            echo json_encode(['data'=>'','display'=>'','error'=>'1','massage'=>$msg]);die;
        }
    }
    
    
    function getAdvocatelist($advocatelist,$salt){
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
        	        <th>Delete</th>
    	        </tr>
	        </thead>
	        <tbody>';
        $html.='</tbody>';
        $vals=$this->efiling_model->data_list_where('temp_caveat','salt',$salt);
        $advType=$vals[0]->advType;
        if($vals[0]->council_code){
            $counseladd=$vals[0]->council_code;
            if($vals[0]->advType=='1'){
                if (is_numeric($vals[0]->council_code)) {
                    $orgname=$this->efiling_model->data_list_where('master_advocate','adv_code',$counseladd);
                    $adv_name=$orgname[0]->adv_name;
                    $adv_reg=$orgname[0]->adv_reg;
                    $adv_mobile=$orgname[0]->adv_mobile;
                    $email=$orgname[0]->email;
                    $address=$orgname[0]->address;
                    $pin_code=$orgname[0]->adv_pin;
                    if($vals[0]->adv_state!=''){
                        $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$vals[0]->adv_state);
                        $statename= $st2[0]->state_name;
                    }
                    if($vals[0]->adv_district!=''){
                        $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$vals[0]->adv_district);
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
        	        <td>'.$adv_mobile.'</td>
        	        <td>'.$email.'</td>
        	        <td></td>
                </tr>';
        }
        //  $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
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
                        if($orgname[0]->state!=''){
                            $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$orgname[0]->state);
                            $statename= $st2[0]->state_name;
                        }
                        if($orgname[0]->district!=''){
                            $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$orgname[0]->district);
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
                        <td><center><input type="button" name="nextsubmit" id="nextsubmit" value="Delete" class="btn1" onclick="deletePartyadv('.$id.')"></center></td>
        	        </tr>';
                $i++;
            }
        }
        return $html;
    }
    //END RPEPCP add Councel 
    
    function caveateadvsave(){
        $msg='';
        date_default_timezone_set('Asia/Calcutta');
        $post_array=$this->input->post();
        $salt= $this->session->userdata('cavsalt');
        $token=$_REQUEST['token'];
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $subtoken=$this->session->userdata('submittoken');
        if($subtoken!=$token){
            echo json_encode(['data'=>'error','error'=>'DB Error found in line no '.__LINE__]);die;
        }
        $query_params=array(
            'tab_no'=>$_REQUEST['tabno']
        );
        $data_app=$this->efiling_model->update_data('temp_caveat', $query_params,'salt', $salt);
        if($data_app){
            echo json_encode(['data'=>'success','display'=>'','error'=>'0']);
        } else{
            echo json_encode(['data'=>'error','error'=>'DB Error found in line no '.__LINE__]);
        }
    }
 
    function doc_save_nextCaveate(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $token=htmlentities($_REQUEST['token']);
        $tabno=htmlentities($_REQUEST['tabno']);
        $untak=htmlentities($_REQUEST['untak']);
        $salt=$this->session->userdata('cavsalt');
        $cudate=date('Y-m-d');
        $subtoken=$this->session->userdata('submittoken');
        if($subtoken==$token){
            $array=array(
                'tab_no'=>$tabno,
                'undertaking'=>$untak,
            );
            $where=array('salt'=>$salt);
            $st = $this->efiling_model->update_data_where('temp_caveat',$where,$array);
            if($st){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);
        }
    }
    
    
    
    function chk_listdataCave(){
        if($this->session->userdata('login_success') && $this->input->post()) {
            $salt=$this->session->userdata('cavsalt');
            $userdata=$this->session->userdata('login_success');
            $user_id=$userdata[0]->id;
            $token=htmlentities($this->input->post('token'));
            $type=htmlentities($this->input->post('type'));
            $subtoken=$this->session->userdata('submittoken');
            $tabno=htmlentities($this->input->post('tabno'));
            if($subtoken==$token){
                $wcond=['salt'=>$salt,'user_id'=>$user_id];
                $exest_clist=$this->db->where($wcond)->get('checklist_temp');
                if($exest_clist->num_rows() > 0) {
                    $this->db->where($wcond)->delete('checklist_temp');
                }
                for($i=1; $i<=9; $i++){
                    $data=[
                        'user_id'=>$user_id,
                        'salt'=>$salt,
                        'serial_no'=>$i,
                        'action'=>htmlentities($this->input->post('check'.$i)),
                        'values'=>'0',
                        'type'=>$type,//htmlentities($this->input->post('value'.$i))
                    ];
                    $db=$this->db->insert('checklist_temp',$data);
                }
                
                $array=array(
                    'tab_no'=>$tabno,
                );
                $where=array('salt'=>$salt);
                $st = $this->efiling_model->update_data_where('temp_caveat',$where,$array);
                if($db) echo json_encode(['data'=>'success','error'=>'0']);
                else 	echo json_encode(['data'=>'Qyery error, try again','error'=>'1']);
            }
        }
        else echo json_encode(['data'=>'Permission deny!','error'=>'1']);
    }
    
    function cavefpsave(){
        $salt=$this->session->userdata('cavsalt');
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $token=htmlentities($this->input->post('token'));
        $type=htmlentities($this->input->post('type'));
        $subtoken=$this->session->userdata('submittoken');
        if($subtoken==$token){
            $tabno=$_REQUEST['tabno'];
            $datatab=array(
                'tab_no'    =>$tabno,
            );
            $st1=$this->efiling_model->update_data('temp_caveat', $datatab,'salt', $salt);
            echo json_encode(['data'=>'success','display'=>'','error'=>'0']);die;
        }else{
            echo json_encode(['data'=>'error','display'=>'Request not valid','error'=>'1']);die;
        }
    }
    
    function addMoreddcaveate(){
        $payid=isset($_REQUEST['payid'])?$_REQUEST['payid']:'';
        if($payid!=''){
            $st=$this->efiling_model->delete_event('sat_temp_payment', 'id', $payid);
        }
        $salt=isset($_REQUEST['salt'])?$_REQUEST['salt']:'';
        $dbankname=isset($_REQUEST['dbankname'])?$_REQUEST['dbankname']:'';
        $amountRs=isset($_REQUEST['amountRs'])?$_REQUEST['amountRs']:'';
        $totalamount=isset($_REQUEST['totalamount'])?$_REQUEST['totalamount']:'';
        $remainamount=isset($_REQUEST['remainamount'])?$_REQUEST['remainamount']:'';
        $type=isset($_REQUEST['type'])?$_REQUEST['type']:'';
        $bd=isset($_REQUEST['bd'])?$_REQUEST['bd']:'';
        $ddno=isset($_REQUEST['ddno'])?$_REQUEST['ddno']:'';
        $ddate=isset($_REQUEST['dddate'])?$_REQUEST['dddate']:'';
        $bd=isset($_REQUEST['bd'])?$_REQUEST['bd']:'';
        
        $this->form_validation->set_rules('bd','Payment mode not valid','trim|required|numeric|min_length[1]|max_length[1]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('ddno','Please enter NTRP number ','trim|required|min_length[1]|max_length[13]|is_unique[sat_temp_payment.dd_no]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('dddate','Please Enter Date','trim|required');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('amountRs','Please amount','trim|required');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }  
        
        
        if($bd==3){
            $ddno=isset($_REQUEST['ddno'])?$_REQUEST['ddno']:'';
        }
        if($payid =='') {
            $query_params=array(
                'salt'=>$salt,
                'payment_mode'=>$bd,
                'branch_name'=>$dbankname,
                'dd_no'=>$ddno,
                'dd_date'=>$ddate,
                'amount'=>$amountRs,
                'fee_type'=>$type,
                'total_fee'=>$totalamount,
            );
            $st=$this->efiling_model->insert_query('sat_temp_payment',$query_params);
        }
        if($bd==3){
            $bankname="Name";
            $dd="Transaction Detail";
            $date="Date of Transction";
            $amount="Aomunt in Rs.";
        }
        $html='';

        $html.='<p> <font color="#510812" size="3">Transaction Detail</font></p>
                <table  class="table">
                      <tr>
                          <th>'.$bankname.'</th>
                          <th>'.htmlspecialchars($dd).'</th>
                          <th>'.htmlspecialchars($date).'</th>
                          <th>'.htmlspecialchars($amount).'</th>
                          <th>Delete</th>
                      </tr> ';
        
        $sum=0;
        $feesd=$this->efiling_model->data_list_where('sat_temp_payment','salt',$salt);
        foreach($feesd as $row){
            $id=$row->id;
            $sum=$sum+$row->amount;
            $html.='<tr>
                            <td>'.$row->branch_name.'</td>
                            <td>'.$row->dd_no.'</td>
                            <td>'.$row->dd_date.'</td>
                            <td>'.$row->amount.'</td>
                            <td><input type="button" value="Delete"  class="btn1" onclick="deletePayrpepcp('.$id.')"/></td>
                         </tr>';
        }
        $remain='';
        if($payid =='') {
            $remain= $totalamount-$sum;
        }else{
            $remain= $totalamount-$sum;
        }
        $html.='</table>
                		<div class="Cell" style="margin-left: 980px;">
                            <p><font color="#510812" size="3">Total Rs</font></p>
                            <p class="custom"><font color="#510812" size="3">'.htmlspecialchars($sum).'</font></p>
                        </div>
                        <input type="hidden" id="payamount" name="payamount" value="'.$sum.'">
                        ';
        echo json_encode(['data'=>'success','value'=>'','paid'=>$sum,'remain'=>$remain,'display'=>$html,'error'=>'0']);
    }

    function caveatsave(){
        $msg_err='';
        $Namecav = '';
        $addresscav='';
        $dstate=1;
        $ddistrict=1;
        $pincav='';
        $emailcav='';
        $phonecav='';
        $mobcav='';
        $curdate=date('Y-m-d');
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('cavsalt');
        $data=$_REQUEST;
        $this->form_validation->set_rules('bd','Payment mode not valid','trim|required|numeric|min_length[1]|max_length[1]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('ddno','Please enter NTRP number ','trim|required|min_length[1]|max_length[13]|is_unique[sat_temp_payment.dd_no]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('dddate','Please Enter Date','trim|required');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('amountRs','Please amount','trim|required');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }  
        
        $this->form_validation->set_rules('dbankname','Please amount','trim|required');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }  
        $user_id=$userdata[0]->id;
        if($user_id && $_REQUEST["dbankname"]!=''){
                $curYear=date('Y');
                if ($_REQUEST['bd'] == '3') {
                    $ddno = $_REQUEST["ddno"];
                    $amountRs = $_REQUEST["amountRs"];
                    $dddate = $_REQUEST["dddate"];
                    $dbankname = $_REQUEST["dbankname"];
                }
                $bname = $dbankname;
                $ddno = $ddno;
                $ddate = $dddate;
                $fee_amount = $amountRs;
                $payMode = $_REQUEST['bd'];
                $amountRs =$_REQUEST['amountRs'];
                $row = $this->efiling_model->data_list_where('temp_caveat','salt',$salt);
                if(!empty($row) && is_array($row)){
                        $Namecav = $row[0]->caveatee_name;
                        $addresscav=$row[0]->caveatee_address;
                        $dstate=$row[0]->caveatee_state;
                        $ddistrict=$row[0]->caveatee_district;
                        $pincav=$row[0]->caveatee_pin;
                        $emailcav=$row[0]->caveatee_email;
                        $phonecav=$row[0]->caveatee_phone;
                        $mobcav=$row[0]->caveatee_mobile;
                        $comm = $row[0]->commission;
                        $order = $row[0]->nature_of_order;
                        $benchCode = htmlspecialchars(str_pad($comm, 3, '0', STR_PAD_RIGHT));          
                        $subBenchCode = htmlspecialchars(str_pad($order, 2, '0', STR_PAD_LEFT));
                        $year_ins = $this->efiling_model->data_list_where('chamber_initialization','year',$curYear);
                        $iaFiling = $year_ins[0]->caveat_filing;
                        $ia_filing_no = $iaFiling;
                        if ($ia_filing_no == 0) {
                            $iaFilingNo = 1;
                            $filno = $ia_filing_no.'00001';
                        }
                        if ($ia_filing_no != 0) {
                            $iaFilingNo = $iaFiling + 1;
                            $filno = $ia_filing_no = (int)$ia_filing_no + 1;
                            $len = strlen($ia_filing_no);
                            $length = 6 - $len;
                            for ($i = 0; $i < $length; $i++) {
                                $filno = "0" . $filno;
                            }
                        }
                        $caveat_filing_no = $benchCode . $subBenchCode . $filno . $curYear;
                        $aray=array(
                            'caveat_filing_no'=>$caveat_filing_no,
                            'caveat_name'=>$row[0]->caveat_name,
                            'caveat_address'=>$row[0]->caveat_address,
                            'caveat_state'=>$row[0]->caveat_state,
                            'caveat_district'=>$row[0]->caveat_district,
                            'caveat_pin'=>$row[0]->caveat_pin,
                            'caveat_email'=>$row[0]->caveat_email,
                            'caveat_phone'=>$row[0]->caveat_phone,
                            'caveat_mobile'=>$row[0]->caveat_mobile,
                            'prayer'=>$row[0]->prayer,
                            'commission'=>$row[0]->commission,
                            'nature_of_order'=>$row[0]->nature_of_order,
                            'case_no'=>$row[0]->case_no,
                            'case_year'=>$row[0]->case_year,
                            'decision_date'=>$row[0]->decision_date,
                            'council_name'=>$row[0]->council_name,
                            'council_address'=>$row[0]->council_address,
                            'council_email'=>$row[0]->council_email,
                            'council_phone'=>$row[0]->council_phone,
                            'council_mobile'=>$row[0]->council_mobile,
                        );
                        $this->efiling_model->insert_query('caveat_detail',$aray);
                        
                        $order1 = 0;
                        $cavete_update = "update additional_commision set filing_no = '" . $caveat_filing_no . "'  where filing_no = '" . $salt . "'";
                        $this->db->query($cavete_update,false);
                        $query_params = array(
                            'caveat_filing_no' => $caveat_filing_no,
                            'caveatee_name' => $Namecav,
                            'caveatee_address' => $addresscav,
                            'caveatee_state' => $dstate,
                            'caveatee_district' => $ddistrict,
                            'caveatee_pin' => $pincav,
                            'caveatee_email' => $emailcav,
                            'caveatee_phone' => $phonecav,
                            'caveatee_mobile' => $mobcav,
                            'caveat_filing_date' => $curdate,
                            'case_type' => $order,
                            'nature_of_order' => $order1,
                            'filed_user_id' => $user_id,
                        );
                        $where=array(
                            'caveat_filing_no'=>$caveat_filing_no,
                        );
                        $this->efiling_model->update_data_where('caveat_detail',$where,$query_params);
              
                $row = $this->efiling_model->data_list_where('sat_temp_payment','salt',$salt);
                foreach($row as $val){
                    $aray=array(
                        'filing_no'=>$caveat_filing_no,
                        'dt_of_filing'=>$curdate,
                        'fee_amount'=>$val->amount,
                        'payment_mode'=>$val->payment_mode,
                        'branch_name'=>$val->branch_name,
                        'dd_no'=>$val->dd_no,
                        'dd_date'=>$val->dd_date,
                        'ia_fee'=>'',
                    );
                    $this->efiling_model->insert_query('aptel_account_details',$aray);
                }
                $account = "insert into aptel_account_details(filing_no,dt_of_filing,fee_amount,payment_mode,branch_name,dd_no,dd_date,ia_fee)
            values('$caveat_filing_no','$curdate','$fee_amount','$payMode','$bname','$ddno','$ddate','$fee_amount')";
                $this->db->query($account,false);
                $sql1=$this->efiling_model->delete_event('temp_caveat','salt',$salt);
                $where=array('year'=>$curYear,  );
                $data=array('caveat_filing'=>$iaFilingNo);
                $resupeate = $this->efiling_model->update_data_where('chamber_initialization',$where,$data);
                $this->session->unset_userdata('cavsalt');
                $data['msg']="Successfully submited";
                $data['iaFilingNo']=$caveat_filing_no;
                $this ->session->set_userdata('cavedetail',$data);
                echo json_encode(['data'=>'success','display'=>'','error'=>'User Not Valid']);
                }
        }else{
            echo json_encode(['data'=>'error','display'=>'','error'=>'User Not Valid']);
        }
    }
 
}