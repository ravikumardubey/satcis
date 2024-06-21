<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Backlog extends CI_Controller {
    function __construct() {
        error_reporting(0);
        parent::__construct();
        $this->load->model('Admin_model','admin_model');
        $this->load->model('Efiling_model','efiling_model');
        date_default_timezone_set('Asia/Calcutta');
        $this->log_file_name='./logfile/log.txt';        
        $config = array(
            'img_path'      => 'asset/captcha_images/',
            'img_url'       => base_url().'asset/captcha_images/',
            'font_path'     => FCPATH.'asset/fonts/texb.ttf',
            'img_width'     => 150,
            'img_height'    => 37,
            'word_length'   => 6,
            'font_size'     => 18,
            'pool'          => '0123456789',
            'captcha_case_sensitive' => TRUE,
            'colors' => array(
                'background' => array(79, 255, 255),
                'border' => array(79, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(79, 255, 255)
            )
        );
        $captcha = create_captcha($config);
        $this->captcha_data=$captcha;
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
        else $_POST[$key]=htmlspecialchars(strtr($val, $spcl_char));
        endforeach;
        
        $logvvalidate=$this->admin_model->logvalidate();
        if($logvvalidate==false){
            $this->session->unset_userdata('login_success');
            redirect(base_url());
        }
    }
    
    function backlog_filing(){
        $userdata=$this->session->userdata('login_success'); 
        $user_id=$userdata[0]->id;
        $salt=$this->session->unset_userdata('salt');
        if($user_id){
            $this->load->view('admin/backlog_filing');
        }
    }
    
    function update_backlogfiling(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($user_id==''){
            $this->load->view('admin/notfound');
        }
        $petname = htmlspecialchars($_REQUEST['pet_name']);
        if(is_numeric($petname)){
            $hscquery = $this->efiling_model->data_list_where('master_org','org_id',$petname);
            $petname = $hscquery[0]->orgdisp_name;
        }
        
        $this->form_validation->set_rules('orgid','Please enter name','trim|required|min_length[1]|max_length[250]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter name','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('pet_name','Please enter name','trim|required|min_length[1]|max_length[250]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter name','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        
        $this->form_validation->set_rules('pet_mobile','Please enter mobile number','trim|required|min_length[1]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter mobile number','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('dstate','Please enter state','trim|required|min_length[1]|max_length[4]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter state','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('ddistrict','Please enter district','trim|required|min_length[1]|max_length[4]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter district','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('pet_email','Please enter email address','trim|required|valid_email|min_length[1]|max_length[50]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter email address','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('pet_phone','Please enter numeric  phone number','trim|min_length[10]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter phone number','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('pet_pin','Please enter numeric  pin code','trim|min_length[6]|max_length[6]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter numeric  pin code','display'=>validation_errors(),'error'=>'1']);die;
        }

        $this->form_validation->set_rules('pet_fax','Please enter numeric Fax','trim|min_length[1]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter numeric Fax','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('idtype','Please select document type','trim|min_length[1]|max_length[10]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter numeric Fax','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $postdata=array(
            'apppan'=>$this->input->post('apppan'), 
            'pet_name'=>$this->input->post('pet_name'),
            'appage'=>$this->input->post('appage'),
            'appfather'=>$this->input->post('appfather'),
            'pet_state'=>$this->input->post('dstate'),
            'pet_district'=>$this->input->post('ddistrict'),
            'pet_pin'=>$this->input->post('pet_pin'), 
            'pet_email'=>$this->input->post('pet_email'),
            'pet_mobile'=>$this->input->post('pet_mobile'),
            'pet_phone'=>$this->input->post('pet_phone'), 
            'pet_fax'=>$this->input->post('pet_fax'),
            'pet_address'=>$this->input->post('pet_address'),
            'pet_id'=>$this->input->post('pet_id'),
            'idtype'=>$this->input->post('idtype'),
        );
        $where=array('filing_no'=>$_REQUEST['filing_no']);
        $st = $this->efiling_model->update_data_where('sat_case_detail',$where,$postdata);
        if($st){
            $massage="Successfully Updated.";
            echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
        }else{
            echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
        }
    }
    
    
    
    
    
    
    
    
    
    
    function additionalbacklogPet(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($user_id==''){
            $this->load->view('admin/notfound');
        }
        $petname = htmlspecialchars($_REQUEST['pet_name']);
        if(is_numeric($petname)){
            $hscquery = $this->efiling_model->data_list_where('master_org','org_id',$petname);
            $petname = $hscquery[0]->orgdisp_name;
        }
        
        $this->form_validation->set_rules('orgid','Please enter name','trim|required|min_length[1]|max_length[250]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter name','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('pet_name','Please enter name','trim|required|min_length[1]|max_length[250]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter name','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        
        $this->form_validation->set_rules('pet_mobile','Please enter mobile number','trim|required|min_length[1]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter mobile number','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('dstate','Please enter state','trim|required|min_length[1]|max_length[4]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter state','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('ddistrict','Please enter district','trim|required|min_length[1]|max_length[4]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter district','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('pet_email','Please enter email address','trim|required|valid_email|min_length[1]|max_length[50]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter email address','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('pet_phone','Please enter numeric  phone number','trim|min_length[10]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter phone number','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('pet_pin','Please enter numeric  pin code','trim|min_length[6]|max_length[6]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter numeric  pin code','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('pet_fax','Please enter numeric Fax','trim|min_length[10]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter numeric Fax','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('idtype','Please select document type','trim|min_length[1]|max_length[10]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please select document type','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $postdata=array(
            'apppan'=>$this->input->post('apppan'),
            'pet_name'=>$this->input->post('pet_name'),
            'appage'=>$this->input->post('appage'),
            'appfather'=>$this->input->post('appfather'),
            'pet_state'=>$this->input->post('dstate'),
            'pet_dis'=>$this->input->post('ddistrict'),
            'pin_code'=>$this->input->post('pet_pin'),
            'pet_email'=>$this->input->post('pet_email'),
            'pet_mobile'=>$this->input->post('pet_mobile'),
            'pet_phone'=>$this->input->post('pet_phone'),
            'pet_fax'=>$this->input->post('pet_fax'),
            'pet_address'=>$this->input->post('pet_address'),
            'party_id'=>$this->input->post('orgid'),
            'filing_no'=>$this->input->post('filing_no'),
            'partyType'=>$this->input->post('partytype'),
            'party_flag'=>'1',
            'entry_date'=>date('Y-m-d'),
            'user_id'=>$user_id,
            'idtype'=>$this->input->post('idtype'),
        );
        $st = $this->efiling_model->insert_query('additional_party',$postdata);
        if($st){
            $massage="Successfully Updated.";
            echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
        }else{
            echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
        }
    }
    
    
    function deletePartyPet(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($user_id==''){
            $this->load->view('admin/notfound');
        }
        $id=$this->input->post('id');
        $this->form_validation->set_rules('id','Please enter valid id','trim|min_length[1]|max_length[8]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter numeric Fax','display'=>validation_errors(),'error'=>'1']);die;
        }
        if($user_id){
            $delete= $this->efiling_model-> delete_event('additional_party', 'id', $id);
            $massage="Deleted succesfully";
            if($delete){
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }
    }
    
    
    
    
    
    
    
    
    
    
    function update_backlogfilingresRes(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $this->form_validation->set_rules('res_mobile','Please enter mobile number','trim|required|min_length[1]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter mobile number','display'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('res_state','Please enter state','trim|required|min_length[1]|max_length[4]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter state','display'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('res_district','Please enter district','trim|required|min_length[1]|max_length[4]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter district','display'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('res_email','Please enter email','trim|valid_email|required|min_length[1]|max_length[50]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter email','display'=>validation_errors(),'error'=>'1']);die;
        }
        if($_REQUEST['org']=='1'){
            $this->form_validation->set_rules('orgid','Please enter name','trim|required|min_length[1]|max_length[4]');
            if($this->form_validation->run() == FALSE){
                echo json_encode(['data'=>'error','value'=>'Please enter name','display'=>validation_errors(),'error'=>'1']);die;
            }
        }
        if($_REQUEST['org']=='2'){
            $this->form_validation->set_rules('res_name','Please enter name','trim|required|min_length[1]|max_length[100]');
            if($this->form_validation->run() == FALSE){
                echo json_encode(['data'=>'error','value'=>'Please enter name','display'=>validation_errors(),'error'=>'1']);die;
            }
        }
        $this->form_validation->set_rules('res_phone','Please enter numeric  phone number','trim|min_length[10]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter phone number','display'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('res_pin','Please enter numeric  pin code','trim|min_length[6]|max_length[6]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter numeric  pin code','display'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('res_fax','Please enter numeric Fax','trim|min_length[10]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter numeric Fax','display'=>validation_errors(),'error'=>'1']);die;
        }
        $postdata=array(
            'pet_name'=>$this->input->post('res_name'),
            'pet_state'=>$this->input->post('res_state'),
            'pet_dis'=>$this->input->post('res_district'),
            'pin_code'=>$this->input->post('res_pin'),
            'pet_email'=>$this->input->post('res_email'),
            'pet_mobile'=>$this->input->post('res_mobile'),
            'pet_phone'=>$this->input->post('res_phone'),
            'pet_fax'=>$this->input->post('res_fax'),
            'pet_address'=>$this->input->post('res_address'),
            'party_id'=>$this->input->post('resorgid'),
            'filing_no'=>$this->input->post('filing_no'),
            'partyType'=>$this->input->post('partytype'),
            'party_flag'=>'2',
            'entry_date'=>date('Y-m-d'),
            'user_id'=>$user_id,
        );
        $st = $this->efiling_model->insert_query('additional_party',$postdata);
        if($st){
            $massage="Successfully Updated.";
            echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            
        }else{
            echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
        }
    }
    
    
    
    
    
    
    
    
    function update_backlogfilingres(){
        $this->form_validation->set_rules('res_mobile','Please enter mobile number','trim|required|min_length[1]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter mobile number','display'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('res_state','Please enter state','trim|required|min_length[1]|max_length[4]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter state','display'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('res_district','Please enter district','trim|required|min_length[1]|max_length[4]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter district','display'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('res_email','Please enter email','trim|valid_email|required|min_length[1]|max_length[50]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter email','display'=>validation_errors(),'error'=>'1']);die;
        }
        if($_REQUEST['org']=='1'){
            $this->form_validation->set_rules('orgid','Please enter name','trim|required|min_length[1]|max_length[4]');
            if($this->form_validation->run() == FALSE){
                echo json_encode(['data'=>'error','value'=>'Please enter name','display'=>validation_errors(),'error'=>'1']);die;
            }
        }
        if($_REQUEST['org']=='2'){
            $this->form_validation->set_rules('res_name','Please enter name','trim|required|min_length[1]|max_length[100]');
            if($this->form_validation->run() == FALSE){
                echo json_encode(['data'=>'error','value'=>'Please enter name','display'=>validation_errors(),'error'=>'1']);die;
            }
        }
        $this->form_validation->set_rules('res_phone','Please enter numeric  phone number','trim|min_length[10]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter phone number','display'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('res_pin','Please enter numeric  pin code','trim|min_length[6]|max_length[6]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter numeric  pin code','display'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('res_fax','Please enter numeric Fax','trim|min_length[10]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter numeric Fax','display'=>validation_errors(),'error'=>'1']);die;
        }
        $postdata=array(
            'res_name'=>$this->input->post('res_name'),
            'res_state'=>$this->input->post('res_state'),  
            'res_district'=>$this->input->post('res_district'),
            'res_pin'=>$this->input->post('res_pin'), 
            'res_email'=>$this->input->post('res_email'), 
            'res_mobile'=>$this->input->post('res_mobile'), 
            'res_phone'=>$this->input->post('res_phone'),  
            'res_fax'=>$this->input->post('res_fax'), 
            'res_address'=>$this->input->post('res_address'),
        );
        $where=array('filing_no'=>$_REQUEST['filing_no']);
        $st = $this->efiling_model->update_data_where('sat_case_detail',$where,$postdata);
        if($st){
            $massage="Successfully Updated.";
            echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            
        }else{
            echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
        }
    }
    
    
    function getorg(){
        $rs=$this->admin_model->getAppRecord($this->input->post());
        $html='';
        foreach($rs as $vals){
            $html.='<li value="'.$vals->adv_code.'" onclick="showorg('.$vals->org_id.')">'.$vals->org_name.'</li>';
        }
        echo $html;die;
    }
    
    
    function getorgPet(){
        $key=$this->input->post();
        $rs=$this->admin_model->getAppRecord($this->input->post());
        $html='';
        foreach($rs as $vals){
            $html.='<li value="'.$vals->adv_code.'" onclick="showorgPet('.$vals->org_id.')">'.$vals->org_name.'</li>';
        }
        echo $html;die;
    }
    
    
    
    
    
    
    
    function getorg_resp(){
        $key=$this->input->post();
        $rs=$this->admin_model->getAppRecord($this->input->post());
        $html='';
        foreach($rs as $vals){
            $html.='<li value="'.$vals->adv_code.'" onclick="showorg_resp('.$vals->org_id.')">'.$vals->org_name.'</li>';
        }
        echo $html;die;
    }
    
    
    function getorg_respRes(){
        $key=$this->input->post();
        $rs=$this->admin_model->getAppRecord($this->input->post());
        $html='';
        foreach($rs as $vals){
            $html.='<li value="'.$vals->adv_code.'" onclick="showorg_respRes('.$vals->org_id.')">'.$vals->org_name.'</li>';
        }
        echo $html;die;
    }
    
    
    
    
    function  districtselectedres(){
        $state = $this->input->post('stateid',true);
        $this->form_validation->set_rules('stateid','Please state  id','trim|required|min_length[1]|max_length[8]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        $districtid= $this->input->post('districtid');
        $this->form_validation->set_rules('districtid','Please district id','trim|required|min_length[1]|max_length[4]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        
        
        $st=$this->efiling_model->data_list_where('master_psdist','state_code',$state);
        $val='';
        if(!empty($st)){
            foreach($st as $row){
                $selected='';
                if($districtid==$row->district_code){
                    $selected="selected";
                }
                $val.='<option value="'.$row->district_code.'" '.$selected.'>'.$row->district_name.'</option>';
            }
        }
        echo  $val;
    }
    
    
    
    function orgdataRes(){
        $q = $this->input->post('q');
        $this->form_validation->set_rules('q','Please select id','trim|required|min_length[1]|max_length[4]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        if ($q != 0) {
            $output = array();
            $sql1 = $this->efiling_model->data_list_where('master_org','org_id',$q);
            foreach($sql1 as $row) {
                $add = $row->org_address;
                $org_name = $row->org_name;
                $mob = $row->mobile_no;
                $mail = $row->email;
                $ph = $row->phone_no;
                $pin = $row->pin;
                $fax = $row->fax;
                $stateCode = $row->state;
                $distcode = $row->district;
                $orgdesg = $row->org_desg;
                $st =$this->efiling_model->data_list_where('master_psstatus','state_code',$stateCode);
                $statename = $st[0]->state_name;
                $distname = '';
                if ($distcode != "") {
                    $stdit = $this->efiling_model->data_list_where('master_psdist','district_code',$distcode);
                    $distname = $stdit[0]->district_name;
                }
                if ($distname != '') {
                    $distname = $distname;
                }
                if ($mob == '0') {
                    $mob = '';
                }
                if ($fax == '0') {
                    $fax = '';
                }
                if ($ph == '0') {
                    $ph = '';
                }
                $users_arr[] = array("orgid"=>$q,"org_name"=>$org_name,"address" => $add, "mob" => $mob, "mail" => $mail, "ph" => $ph, "pin" => $pin, "fax" => $fax, "stcode" => $stateCode, "stname" => $statename, "dcode" => $distcode, "dname" => $distname, "desg" => $orgdesg);
            }
            echo json_encode($users_arr);
        }
    }
    
    
    
    function orgdata(){
        $q = $this->input->post('q');
        $this->form_validation->set_rules('q','Please select id','trim|required|min_length[1]|max_length[4]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        if ($q != 0) {
            $output = array();
            $sql1 = $this->efiling_model->data_list_where('master_org','org_id',$q);
            foreach($sql1 as $row) {
                $add = $row->org_address;
                $org_name = $row->org_name;
                $mob = $row->mobile_no;
                $mail = $row->email;
                $ph = $row->phone_no;
                $pin = $row->pin;
                $fax = $row->fax;
                $stateCode = $row->state;
                $distcode = $row->district;
                $orgdesg = $row->org_desg;
                $st =$this->efiling_model->data_list_where('master_psstatus','state_code',$stateCode);
                $statename = $st[0]->state_name;
                $distname = '';
                if ($distcode != "") {
                    $stdit = $this->efiling_model->data_list_where('master_psdist','district_code',$distcode);
                    $distname = $stdit[0]->district_name;
                }
                if ($distname != '') {
                    $distname = $distname;
                }
                if ($mob == '0') {
                    $mob = '';
                }
                if ($fax == '0') {
                    $fax = '';
                }
                if ($ph == '0') {
                    $ph = '';
                }
                $users_arr[] = array("orgid"=>$q,"org_name"=>$org_name,"address" => $add, "mob" => $mob, "mail" => $mail, "ph" => $ph, "pin" => $pin, "fax" => $fax, "stcode" => $stateCode, "stname" => $statename, "dcode" => $distcode, "dname" => $distname, "desg" => $orgdesg);
            }
            echo json_encode($users_arr);
        }
    }
    
   
    function backlogbasicdetailsave(){
        if($this->session->userdata('login_success') && $this->input->post()) {
           $userdata=$this->session->userdata('login_success');
           $user_id=$userdata[0]->id;
           $impugneddate=$this->input->post('impugneddate');
           $receiptimpdate=$this->input->post('receiptimpdate');
           $filingno= $this->input->post('filing_no');
           
           $this->form_validation->set_rules('appeal_type','Please apeal type ','trim|required|min_length[1]|max_length[4]');
           if($this->form_validation->run() == FALSE){
               echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
           }
           
           $this->form_validation->set_rules('relevantacts','Please select ACTS/ Rules','trim|required|min_length[1]|max_length[4]|numeric|htmlspecialchars|regex_match[/^[0-9]+$/]');
           if($this->form_validation->run() == FALSE){
               echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
           }
           
           $this->form_validation->set_rules('orderpassing','Please select  order passing','trim|required|min_length[1]|numeric|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
           if($this->form_validation->run() == FALSE){
               echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
           }
           
           $this->form_validation->set_rules('penalty','Enter Penalty','trim|required|numeric|min_length[1]|max_length[10]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
           if($this->form_validation->run() == FALSE){
               echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
           }
           
           $this->form_validation->set_rules('impugneddate','Enter Impugned Order Date.','trim|required|min_length[1]|max_length[15]|htmlspecialchars');
           if($this->form_validation->run() == FALSE){
               echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
           }
           
           $this->form_validation->set_rules('receiptimpdate','Enter Date of receipt of Impugned Order.','trim|required|min_length[10]|htmlspecialchars');
           if($this->form_validation->run() == FALSE){
               echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
           }
           
           $this->form_validation->set_rules('impugnedno','Enter impugned no.','trim|required|min_length[25]|max_length[25]|numeric|htmlspecialchars|regex_match[/^[0-9,]+$/]');
           if($this->form_validation->run() == FALSE){
               echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
           }
           
           
           $this->form_validation->set_rules('delayfiling','Enter delay filing.','trim|required|min_length[1]|max_length[15]|numeric|htmlspecialchars|regex_match[/^[0-9,]+$/]');
           if($this->form_validation->run() == FALSE){
               echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
           }
           
           $this->form_validation->set_rules('filing_no','Enter filing number.','trim|required|min_length[15]|htmlspecialchars|numeric|regex_match[/^[0-9,]+$/]');
           if($this->form_validation->run() == FALSE){
               echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
           }
           
           $data=[
                'opauthority'=>$this->input->post('orderpassing'),
                'ipenalty'=>$this->input->post('penalty'),
                'iorderdate'=>date('Y-m-d',strtotime($impugneddate)),
                'act'=>$this->input->post('relevantacts'),
                'rimpugnedorder'=>date('Y-m-d',strtotime($receiptimpdate)),
                'user_id'=>$user_id,
                'iordernumber'=>$this->input->post('impugnedno'),
                'delayinfiling'=>$this->input->post('delayfiling'),
                'apeal_type'=>$this->input->post('appeal_type'),
            ];
            $rs=$this->efiling_model->update_data('sat_case_detail', $data,'filing_no', $filingno);
            if($rs) 	echo json_encode(['data'=>'success','error'=>'0']);
            else 		echo json_encode(['data'=>'Query error found!','error'=>'1']);
        }
        else echo json_encode(['data'=>'Permission deny!','error'=>'1']);
    }
    
    
    
    function backlogcouncelsave(){
        $this->form_validation->set_rules('dstatename','Please select state','trim|required|min_length[1]|max_length[4]|htmlspecialchars|regex_match[/^[0-9]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'display'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('ddistrictname','Please select district','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'display'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('conpincode','Enter Pin number','trim|required|min_length[6]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'display'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('conmobile','Enter mobile number.','trim|required|min_length[10]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'display'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('filing_no','Enter filing number.','trim|required|min_length[15]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'display'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('conPhone','Please enter numeric  phone number','trim|min_length[10]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter phone number','display'=>validation_errors(),'error'=>'1']);die;
        }

        $this->form_validation->set_rules('conFax','Please enter numeric Fax','trim|min_length[10]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter numeric Fax','display'=>validation_errors(),'error'=>'1']);die;
        }
        $filing_no=$this->input->post('filing_no');
        
        if($this->form_validation->run() == TRUE) {
            $query_params=array(
                'pet_counsel_address'=>$this->input->post('conAddress'), 
                'pet_counsel_pin'=>$this->input->post('counselPin'),
                'pet_counsel_phone'=>$this->input->post('conmobile'),
                'pet_counsel_email'=>$this->input->post('conEmail'),
                'pet_adv'=>$this->input->post('councilCode'),   
                'pet_counsel_fax'=>$this->input->post('conFax'), 
                'pet_counsel_phone'=>$this->input->post('conPhone'), 
            );
            $data_app=$this->efiling_model->update_data('sat_case_detail', $query_params,'filing_no', $filing_no);
            if($data_app){
                echo json_encode(['data'=>'success','display'=>'Update successfully','error'=>'0']); die;
            } else{
                echo json_encode(['data'=>'','error'=>'1','massage'=>'DB Error found in line no '.__LINE__]); die;
            }
        }else{
            echo json_encode(['data'=>'','error'=>'1','massage'=>strip_tags(validation_errors())]); die;
        }
    }
    
    
    
    function caseupdatestatus(){
        $this->form_validation->set_rules('status','Please enter status','trim|min_length[1]|max_length[1]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter status','display'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('disposeddate','Please enter disposed date','trim|min_length[1]|max_length[20]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter disposed date','display'=>validation_errors(),'error'=>'1']);die;
        }
        $filing_no=$this->input->post('filing_no');
        
        if($this->form_validation->run() == TRUE) {
            $query_params=array(
                'status'=>$this->input->post('status'),
                'disposel_date'=>$this->input->post('disposeddate'),
            );
            $data_app=$this->efiling_model->update_data('sat_case_detail', $query_params,'filing_no', $filing_no);
            if($data_app){
                echo json_encode(['data'=>'success','display'=>'Update successfully','error'=>'0']); die;
            } else{
                echo json_encode(['data'=>'','error'=>'1','massage'=>'DB Error found in line no '.__LINE__]); die;
            }
        }else{
            echo json_encode(['data'=>'','error'=>'1','massage'=>strip_tags(validation_errors())]); die;
        }
    }
    
    
    function dropdown_party_details(){
        $filingNo =$this->input->post('faling_no'); 
        $selected_radio1 = $this->input->post('radio_selected');
        $option_value='';
        $query=$this->db->query("select * from sat_case_detail where filing_no='$filingNo'");
        $rowval= $query->result();
        foreach ($rowval as $row) {
            $filing_no = htmlspecialchars($row->filing_no);
            $petName = $row->pet_name;
            $resName = $row->res_name;
        }
        $option_value .='<select style="width: 100%;" name="additionla_partyy" class="select_box" id="additionla_partyy" multiple="">';
        if ($selected_radio1 == 1) {
            $party_tags = 'A';
            $option_value .= '<option value="1">'.$petName. '(A-1) </option>';
        } else {
            $party_tags = 'R';
            $option_value .= '<option value="1">'.$resName. '(R-1) </option>';
        }
        $addParty = $this->db->query("select * from additional_party where filing_no='$filingNo' and party_flag='$selected_radio1' ORDER BY party_id");
        $rowval= $addParty->result();
        $ii = 2;
        foreach ($rowval as $row) {
            $option_value .= '<option value='.$row->party_id.'>'.$row->pet_name . '(' . $party_tags .'-'. $row->partysrno . ')'.'</option>';
            $ii++;
        }
        $option_value .='</select>';
        echo $option_value;
    }
    
    
    function i_ia_insert(){
        if ($_POST['action'] == 'add_ia_module') {
            $salt =$this->input->post('salt');   
            if (!preg_match('/^[0-9]*$/', $salt)) {
                $msg= "<font color='red' size='4'>Error: You did not enter numbers only. Please enter only numbers.</font></center>";
                echo json_encode(['data'=>'error','error'=>'1','massage'=>$msg]); die;
            }
            if (strlen($salt) > 15) {
                echo "<font color='red' size='4'>Error: Please enter 15 Digit Salt .</font></center>";
                echo json_encode(['data'=>'error','error'=>'1','massage'=>$msg]); die;
            }
            $iayear = $this->input->post('iaYear'); 
            if ($iayear != "") {
                if (!preg_match('/^[0-9]*$/', $iayear)) {
                    $msg= "<font color='red' size='4'>Error: You did not enter numbers only. Please enter only numbers.</font></center>";
                    echo json_encode(['data'=>'error','error'=>'1','massage'=>$msg]); die;
                }
                
                if (strlen($iayear) < 4 || strlen($iayear) > 4) {
                    $msg= "<font color='red' size='4'>Error: Please enter 4 digit Year number .</font></center>";
                    echo json_encode(['data'=>'error','error'=>'1','massage'=>$msg]); die;
                }
            }
            $iaNo = $this->input->post('iaNo'); 
            if ($iaNo != "") {
                if (!preg_match('/^[0-9]*$/', $iaNo)) {
                    $msg= "<font color='red' size='4'>Error: You did not enter numbers only. Please enter only numbers.</font></center>";
                    echo json_encode(['data'=>'error','error'=>'1','massage'=>$msg]); die;
                }
                if (strlen($$iaNo) > 7) {
                    $msg= "<font color='red' size='4'>Error: Please enter 7 digit IA  number .</font></center>";
                    echo json_encode(['data'=>'error','error'=>'1','massage'=>$msg]); die;
                }
            }
            
            $payid = $this->input->post('payid'); 
            if ($payid != '') {
                $delete= $this->efiling_model->delete_event('satma_detail', 'ia_no', $payid);
            }
            $nature =  $this->input->post('nat');  
            $status = $this->input->post('status'); 
            $curYear = date('Y');
            $curMonth = date('m');
            $curDay = date('d');
            $curdate = "$curYear-$curMonth-$curDay";
            $filedby = $this->input->post('filedby'); 
            $matter = $this->input->post('matter');  
            $ia_filing_no = $iaNo;
            if ($ia_filing_no != '0') {
                $iaFilingNo = $ia_filing_no = (int)$ia_filing_no;
                $length = 6 - strlen($ia_filing_no);
                for ($i = 0; $i < $length; $i++) {
                    $ia_filing_no = "0" . $ia_filing_no;
                }
            }
            $iafilingdate = explode("-", $this->input->post('iafilingdate'));
            $iafd = $iafilingdate[2] . '-' . $iafilingdate[1] . '-' . $iafilingdate[0];
            
            $disdate = '';
            if (!empty($_REQUEST['iadisdate'])) {
                $explode = explode('-',$this->input->post('iadisdate'));
                $disdate = $explode[2].'-'.$explode[1].'-'.$explode[0];
            }
            $iaFiling_no1 = '100' . '07' . rand(100000,999999) . $iayear;
            $iaNo = '';
            if (!empty($this->input->post('iaNo'))) {
                $iaNo = $this->input->post('iaNo');
            }
            $filed_by = '';
            if (!empty($filedby)) {
                $filed_by = $filedby;
            }
            $matter = '';
            if (!empty($matter)) {
                $matter =$matter;
            }
            $status = '';
            if (!empty($this->input->post('iastatus'))) {
                $status =$this->input->post('iastatus');
            }
            
            $nature = '';
            if (!empty($this->input->post('ianature'))) {
                $nature = $this->input->post('ianature');
            }
            
            $additional_party = '';
            if(!empty($this->input->post('additionla_partyy')) && is_array($this->input->post('additionla_partyy'))) {
                $additional_party =$this->input->post('additionla_partyy');
                $additional_party = implode(',',$additional_party);
            }
            
            $query=$this->db->query("select * from satma_detail where filing_no= '".$salt."' and ia_no = '".$iaNo."'");
            $rowval= $query->result();
            if(empty($rowval)){
                $data=array(
                    'additional_party'=>$additional_party,
                    'ia_no'=>$iaNo,
                    'filing_no'=>$salt,
                    'filed_by'=>$filed_by,
                    'entry_date'=>$iafd,
                    'display'=>'Y',
                    'ia_filing_no'=>$iaFiling_no1,
                    'ia_nature'=>$nature,
                    'status'=>$status,
                    'matter'=>$matter,
                    'disposal_date'=>$disdate,
                );
                $st = $this->efiling_model->insert_query('satma_detail',$data);
                if ($st) {
                    $msg = "Successfully Updated Your Record";
                }
                echo json_encode(['data'=>'success','error'=>'0','massage'=>$msg]); die;
            }else{
                $msg=  "$iaNo IA  Number Already exit.";
                echo json_encode(['data'=>'success','error'=>'0','massage'=>$msg]); die;
            }
        }
    }
    
    
    
    function delete_edit_ia(){
        if ($_POST['action'] == 'ia_party_delete') {
            $delete= $this->efiling_model->delete_event('satma_detail', 'ia_filing_no', $_POST['ia_faling_no']);
            if ($delete) {
                $msg="Sucessfully Deleted";
                echo json_encode(['data'=>'success','error'=>'0','massage'=>$msg]); die;
            }
        }else{
            $msg="Record not found";
            echo json_encode(['data'=>'error','error'=>'1','massage'=>$msg]); die;
        }
    }
    

    function createdfr(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($this->input->post('type')=='dfrwise'){
            $fno=$this->input->post('dfr');
            $year=$this->input->post('year');
            $diaryYear1 = $year;
            $bench = 100;
            $subBench = 1;
            $subBenchCode = htmlspecialchars(str_pad($subBench, 2, '0', STR_PAD_LEFT));
          
            $len = strlen($fno);
            if($len==2){
                $diaryNo='0000'.$fno;
            }
            if($len==3){
                $diaryNo='000'.$fno;
            }
            if($len==4){
                $diaryNo='00'.$fno;
            }
            $filing_no_old = $bench . $subBenchCode . $diaryNo . $diaryYear1;  
            $query_str="SELECT * FROM sat_case_detail  where filing_no like '%$filing_no_old'";
            $query=$this->db->query($query_str);
            $rowval= $query->result();
            if(empty($rowval)){
                $data=array(
                    'filing_no'=>$filing_no_old,
                    'filed_user_id'=>$user_id,
                    'ip'=>$_SERVER['REMOTE_ADDR'],
                    'user_id'=>$user_id,
                    'status'=>'P',
                );
                $st = $this->efiling_model->insert_query('sat_case_detail',$data);
                if ($st) {
                    $msg = "Successfully Created Appeal Lodging No";
                }
                echo json_encode(['data'=>'success','error'=>'0','filingno'=>$filing_no_old,'massage'=>$msg]); die;
            }else{
                $msg = "Filing no. already created";
                echo json_encode(['data'=>'error','error'=>'1','filingno'=>'','massage'=>$msg]); die;
            }      
	  }
    }
    
    
    
    function createdfrcasewisecase(){
        $filing_no_old='';
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($this->input->post('type')=='casewise'){
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
            $caseno=$this->input->post('caseno');
            $year=$this->input->post('year');
            $case_type=$this->input->post('casetype');
            $cnoss=$caseno;
            $cnolength= strlen($caseno);
            $cno='';
            if($cnolength==1){
                $cno='000';
            }
            if($cnolength==2){
                $cno='00';
            }
            if($cnolength==3){
                $cno='0';
            }
            $case1='400';
            $ct='';
            if($case_type==1){
                $ct='1000';
            }
            if($case_type==2){
                $ct='2000';
            }
            if($case_type==3){
                $ct='3000';
            }
            $cno=$case1.$ct.$cno.$cnoss.$year;
            $query_str="SELECT * FROM sat_case_detail  where case_no like '%$cno%' limit 1";
            $query=$this->db->query($query_str);
            $rowval=  $query->result();
            if(empty($rowval)){
                $st =$this->efiling_model->data_list_where('year_initialization','year',$year);
                $fno=$st[0]->filing_no+1;
                $diaryYear1 = $year;
                $bench = 100;
                $subBench = 1;
                $subBenchCode = htmlspecialchars(str_pad($subBench, 2, '0', STR_PAD_LEFT));
                $len = strlen($fno);
                $diaryNo='';
                if($len==1){
                    $diaryNo='00000'.$fno;
                }
                if($len==2){
                    $diaryNo='0000'.$fno;
                }
                if($len==3){
                    $diaryNo='000'.$fno;
                }
                if($len==4){
                    $diaryNo='00'.$fno;
                }
                $filing_no_old = $bench . $subBenchCode . $diaryNo . $diaryYear1;  
                $st = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no_old);
                if(empty($st)){
                    $data=array(
                        'filing_no'=>$filing_no_old,
                        'case_no'=>$cno,
                        'case_type'=>$case_type,
                        'filed_user_id'=>$user_id,
                        'ip'=>$_SERVER['REMOTE_ADDR'],
                        'user_id'=>$user_id,
                        'status'=>'P',
                    );
                    $st = $this->efiling_model->insert_query('sat_case_detail',$data);
                    if ($st) {
                        $msg = "Successfully Created Appeal Lodging No";
                    }
                    $dat=array('filing_no'=>$filing_no_old);
                    $st=$this->efiling_model->insert_query('scrutiny',$dat);
                    $this->db->update('year_initialization', ['filing_no'=>$fno], ['year'=>$year]);
                    echo json_encode(['data'=>'success','error'=>'0','filingno'=>$filing_no_old,'massage'=>$msg]); die;
                }else{
                    $msg = "Filing no. already created filing number is $filing_no_old";
                    echo json_encode(['data'=>'error','error'=>'1','filingno'=>'','massage'=>$msg]); die;
                }
            }else{
                $msg = "Filing no. already created filing number is $filing_no_old";
                echo json_encode(['data'=>'error','error'=>'1','filingno'=>'','massage'=>$msg]); die;
            }
        }
    }
    
    
    
    
    
    function getAdv(){
        $key=$this->input->post();
        $rs=$this->admin_model->getAdv($this->input->post());
        $html='';
        foreach($rs as $vals){
            $html.='<li value="'.$vals->adv_code.'" onclick="showUserOrg1('.$vals->adv_code.')">'.$vals->adv_name.'</li>';
        }
        echo $html;die;
    }
    
    function getAdvdoc(){
        $key=$this->input->post();
        $rs=$this->admin_model->getAdv($this->input->post());
        $html='';
        foreach($rs as $vals){
            $html.='<li value="'.$vals->adv_code.'" onclick="showUserOrg1doc('.$vals->adv_code.')">'.$vals->adv_name.'</li>';
        }
        echo $html;die;
    }
    
    
    
    
    function getAdvAA(){
        $key=$this->input->post();
        $rs=$this->admin_model->getAdv($this->input->post());
        $html='';
        foreach($rs as $vals){
            $html.='<li value="'.$vals->adv_code.'" onclick="showUserOrg1AA('.$vals->adv_code.')">'.$vals->adv_name.'</li>';
        }
        echo $html;die;
    }
    
    
    function backlogcouncelsaveAA(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $this->form_validation->set_rules('dstatename','Please select state','trim|required|min_length[1]|max_length[4]|htmlspecialchars|regex_match[/^[0-9]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'display'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('ddistrictname','Please select district','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'display'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('conpincode','Enter Pin number','trim|required|min_length[6]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'display'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('conmobile','Enter mobile number.','trim|required|min_length[10]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'display'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('filing_no','Enter filing number.','trim|required|min_length[15]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'display'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('conPhone','Please enter numeric  phone number','trim|min_length[0]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter phone number','display'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('conFax','Please enter numeric Fax','trim|min_length[0]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter numeric Fax','display'=>validation_errors(),'error'=>'1']);die;
        }
        $filing_no=$this->input->post('filing_no');
        
        if($this->form_validation->run() == TRUE) {
            $query_params=array(
                'party_flag'=>'',
                'filing_no'=>$this->input->post('filing_no'),
                'state'=>$this->input->post('dstatename'),
                'district'=>$this->input->post('ddistrictname'),
                'entry_date'=>date('Y-m-d'),
                'adv_address'=>$this->input->post('conAddress'),
                'pin_code'=>$this->input->post('counselPin'),
                'adv_mob_no'=>$this->input->post('conmobile'),
                'adv_email'=>$this->input->post('conEmail'),
                'adv_code'=>$this->input->post('councilCode'),
                'adv_fax_no'=>$this->input->post('conFax'),
                'adv_phone_no'=>$this->input->post('conPhone'),
                'user_id'=>$user_id,
            );
            
            $data_app = $this->efiling_model->insert_query('additional_advocate',$query_params);
            if($data_app){
                echo json_encode(['data'=>'success','display'=>'Update successfully','error'=>'0']); die;
            } else{
                echo json_encode(['data'=>'','error'=>'1','massage'=>'DB Error found in line no '.__LINE__]); die;
            }
        }else{
            echo json_encode(['data'=>'','error'=>'1','massage'=>strip_tags(validation_errors())]); die;
        }
    }
    
    
    
    function deleteAdvocateAA(){
        $this->form_validation->set_rules('id','Please enter valid id','trim|min_length[0]|max_length[10]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'Please enter valid id','display'=>validation_errors(),'error'=>'1']);die;
        }
        $id=$this->input->post('id');
        $delete= $this->efiling_model->delete_event('additional_advocate', 'id', $id);
        if ($delete) {
            $msg="Sucessfully Deleted";
            echo json_encode(['data'=>'success','error'=>'0','massage'=>$msg]); die;
        }
    }
    
    
    function  district(){
        $state =$this->input->post('state_id',true);
        $this->form_validation->set_rules('state_id','Please state id','trim|required|min_length[1]|max_length[8]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        $st=$this->efiling_model->data_list_where('master_psdist','state_code',$state);
        $val='';
        if(!empty($st)){
            foreach($st as $row){
                $val.='<option value="'.$row->district_code.'">'.$row->district_name.'</option>';
            }
        }
        echo  $val;
    }
    
    
    
    
}
?>