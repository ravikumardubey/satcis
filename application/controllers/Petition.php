<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Petition extends CI_Controller {
    function __construct() {
        error_reporting(0);
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
		$_POST[$paraName]=htmlspecialchars($getPvalue);
		endfor;
		foreach($_POST as $key=>$val):;
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
		    $_POST[$key]=$innerData;
		}
		else $_REQUEST[$key]=htmlspecialchars(strtr($val, $spcl_char));
		endforeach;
    }
    
    function petitiondetail(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('rpepcpsalt');
        $user_id=$userdata[0]->id;
        if($user_id && $salt){
            $data['datacomm']=  '';
            $this->load->view('rpepcp/petitiondetail',$data);
        }
    }
    
    function petitionparty(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('rpepcpsalt');
        $user_id=$userdata[0]->id;
        if($user_id!='' && $salt!=''){
            $this->load->view('rpepcp/petitionparty');
        }else{
            redirect(base_url(),'refresh');
        }
    }

    
    function petitionadv(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('rpepcpsalt');
        $user_id=$userdata[0]->id;
        if($user_id!='' && $salt!=''){
            $this->load->view('rpepcp/petitionadv');
        }else{
            redirect(base_url(),'refresh');
        }
    }
    
    function petitionIa(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('rpepcpsalt');
        $user_id=$userdata[0]->id;
        if($user_id!='' && $salt!=''){
            $this->load->view('rpepcp/petitionIa');
        }else{
            redirect(base_url(),'refresh');
        }
    }
    
    
    function petitionDoc(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('rpepcpsalt');
        $user_id=$userdata[0]->id;
        if($user_id!='' && $salt!=''){
            $this->load->view('rpepcp/petitionDoc');
        }else{
            redirect(base_url(),'refresh');
        }
    }
    
    function petitionCheck(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('rpepcpsalt');
        $user_id=$userdata[0]->id;
        if($user_id!='' && $salt!=''){
            $data['checklist']= $this->efiling_model->data_list_where('master_checklist','status','1');
            $this->load->view('rpepcp/petitionCheck',$data);
        }else{
            redirect(base_url(),'refresh');
        }
    }
    
    function petitionCfee(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('rpepcpsalt');
        $user_id=$userdata[0]->id;
        if($user_id!='' && $salt!=''){
            $this->load->view('rpepcp/petitionCfee');
        }else{
            redirect(base_url(),'refresh');
        }
    }
    
    function petitionFP(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('rpepcpsalt');
        $user_id=$userdata[0]->id;
        if($user_id!='' && $salt!=''){
            $this->load->view('rpepcp/petitionFP');
        }else{
            redirect(base_url(),'refresh');
        }
    }
    
    
    
    
    function createfilingNo($filing_no_old,$curYear){
        $filing_no333='';
        $subBench='';
        $bench='';
        $fil_no='';
        if($filing_no_old!=''){
            $sql = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no_old);
            if(!empty($sql)){
                foreach($sql as $row) {
                    $bench = '100';
                    $filing_no333 = $row->filing_no;
                    $subBench = '1';
                }
                if ($bench == '') {
                    $bench = substr($filing_no333, 0, 3);
                }
                if ($subBench == '') {
                    $subBench = substr($filing_no333, 3, 2);
                }
                $benchCode = htmlspecialchars(str_pad($bench, 3, '0', STR_PAD_RIGHT));
                $subBenchCode = htmlspecialchars(str_pad($subBench, 2, '0', STR_PAD_LEFT));
                $st =$this->efiling_model->data_list_where('year_initialization','year',$curYear);
                $filing_no = $st[0]->filing_no;
                if ($filing_no == 0) {
                    $fil_no = $filing_no = '000001';
                }
                if ($filing_no != 0) {
                    $fil_no = $filing_no = (int)$filing_no + 1;
                    $len = strlen($filing_no);
                    $length = 6 - $len;
                    for ($i = 0; $i < $length; $i++) {
                        $filing_no = "0" . $filing_no;
                    }
                }
               $news_filing_no= $newfiling_no = $benchCode . $subBenchCode . $filing_no . $curYear;
               return   array('new_filing_no'=>$news_filing_no,'xcount'=>$fil_no,'benchCode'=>$bench,'subBenchCode'=>$subBench);
            }
        }else{
            echo "record not found";die;
        }
    }
    
    function getcaseType($caseType){
        $val='';
        if($caseType=='rp'){
            $val='6';
        }
        if($caseType=='ep'){
            $val='5';
        }
        if($caseType=='cp'){
            $val='7';
        }
        return $val;
    }
    
    function petitionPay(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $salt=$this->session->userdata('rpepcpsalt');
        if($user_id!='' && $salt!=''){
            $this->load->view('rpepcp/petitionPay');
        }else{
            redirect(base_url(),'refresh');
        }
    }
    
    function petitionReceipt(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($user_id!=''){
            $this->load->view('rpepcp/petitionReceipt');
        }else{
            redirect(base_url(),'refresh');
        }
    }
    
    
    function petitionPriority(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('rpepcpsalt');
        $user_id=$userdata[0]->id;
        if($user_id!='' && $salt!=''){
            $this->load->view('rpepcp/petitionPriority');
        }else{
            redirect(base_url(),'refresh');
        }
    }
    
    
    function saveNextRPEPCbasic(){
        //echo "dddd";die;
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $token=htmlentities($_REQUEST['token']);
        $tabno=htmlentities($_REQUEST['tab_no']);
        $filing_no=htmlentities($_REQUEST['filing_no']);
        $saltval='';
        $toalannexure=htmlentities($_REQUEST['toalannexure']);
        $orderdate=htmlentities($_REQUEST['orderdate']);
        $totalnoIa=htmlentities($_REQUEST['totalnoIa']);
        $rpepcptype=htmlentities($_REQUEST['rpepcptype']);
        $subject=htmlentities($_REQUEST['subject']);
        $cudate=date('y-d-m');
        $subtoken=$this->session->userdata('submittoken');
       
        $this->form_validation->set_rules('rpepcptype','Please select rp ep cp','trim|required|min_length[1]|max_length[2]|htmlspecialchars|regex_match[/^[a-z,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('toalannexure','Please select rp ep cp','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('orderdate','please enter remark','trim|required|htmlspecialchars');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        } 
        $this->form_validation->set_rules('totalnoIa','status not valid','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('filing_no','enter valid filing no','trim|required|min_length[15]|max_length[15]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
     //   echo "dddd";die;
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
            $this->session->set_userdata('rpepcpsalt',$salt);
        }
        if($subtoken==$token){
            $postdata=array(
                'salt'=>$salt,
                'totalNoAnnexure'=>$toalannexure,
                'totalNoia'=>$totalnoIa,
                'filing_no'=>$filing_no,
                'order_date'=>date('Y-m-d',strtotime($orderdate)),
                'tab_no'=>$tabno,
                'entry_date'=>$cudate,
                'user_id'=>$user_id,
                'type'=>$rpepcptype,
                'subject'=>$subject,
            );
            $st=$this->efiling_model->insert_query('rpepcp_reffrence_table', $postdata);
            if($st){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);
        }
    }
    
    
    function petitionpartySubmit(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $saltsession=$this->session->userdata('rpepcpsalt');
        $salt=htmlentities($_REQUEST['salt']);
        if($saltsession!=$salt){
            echo json_encode(['data'=>'error','value'=>'','massage'=>'Not a valid request','error'=>'1']); die;
        }
        $token=htmlentities($_REQUEST['token']);
        $tabno=htmlentities($_REQUEST['tabno']);
        $filing_no=htmlentities($_REQUEST['filingno']);
        $patyAddId=htmlentities($_REQUEST['patyAddId']);
        $rpepcptype=htmlentities($_REQUEST['type']);
        $partyType=htmlentities($_REQUEST['partyType']);
        
        $cudate=date('y-d-m');  
        $subtoken=$this->session->userdata('submittoken');
        $this->form_validation->set_rules('type','Please select rp ep cp','trim|required|min_length[1]|max_length[2]|htmlspecialchars|regex_match[/^[a-z,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('tabno','Tab no not valid ','trim|required|min_length[1]|max_length[3]|htmlspecialchars|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('filingno','enter valid filing no','trim|required|min_length[15]|max_length[15]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('partyType','Please enter party type','trim|required|min_length[1]|max_length[2]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('patyAddId','Please enter party type','trim|required|min_length[1]|max_length[50]|htmlspecialchars');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        
        
        if($subtoken==$token){
            $array = array(
                'tab_no' =>$tabno,
                'party_ids'=>$patyAddId,
                'partyType'=>$partyType,
                'user_id'=>$user_id,
            );
            $where=array('salt'=>$salt);
            $st = $this->efiling_model->update_data_where('rpepcp_reffrence_table',$where,$array);
            if($st){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);
        }
    }
    
    
    function  petitionpartyPrioritySubmit(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $saltsession=$this->session->userdata('rpepcpsalt');
        $salt=htmlentities($_REQUEST['salt']);
        if($saltsession!=$salt){
            echo json_encode(['data'=>'error','value'=>'','massage'=>'Not a valid request','error'=>'1']); die;
        }
        $token=$this->input->post('token'); 
        $tabno=$this->input->post('tabno');  
        $filing_no=$this->input->post('filingno'); 
        $rpepcptype=$this->input->post('type'); 

        $appparty=$this->input->post('appparty'); 
        $apppriority=$this->input->post('apppriority');  
        $resparty=$this->input->post('resparty');
        $respriority=$this->input->post('respriority');  
        $subtoken=$this->session->userdata('submittoken');
        $this->form_validation->set_rules('type','Please select rp ep cp','trim|required|min_length[1]|max_length[2]|htmlspecialchars|regex_match[/^[a-z,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('tabno','Tab no not valid ','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('filingno','enter valid filing no','trim|required|min_length[15]|max_length[15]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('apppriority','Please app priority','trim|required|min_length[1]|max_length[50]|htmlspecialchars');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        
        $this->form_validation->set_rules('resparty','Please enter party','trim|required|min_length[1]|max_length[50]|htmlspecialchars');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('appparty','Please enter applicant party','trim|required|min_length[1]|max_length[50]|htmlspecialchars');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }

        if($subtoken==$token){
            $array = array(
                'tab_no' =>$tabno,
                'app_party_id'=>$appparty,
                'app_priority'=>$apppriority,
                'res_partyid'=>$resparty,
                'res_priority'=>$respriority,
                'user_id'=>$user_id,
            );
            $where=array('salt'=>$salt);
            $st = $this->efiling_model->update_data_where('rpepcp_reffrence_table',$where,$array);
            if($st){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);
        }
    }
    //Prioriry Submit 
    
    //RPEPCP add Councel 
    function RPEPCPaddCouncel(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('rpepcpsalt');
        $subtoken=$this->session->userdata('submittoken');
        $token=$_REQUEST['token'];
        $user_id=$userdata[0]->id;
        $id=$_REQUEST['id'];
        $partyType=$_REQUEST['partyType'];
        $advType=$_REQUEST['advType'];
        $petadvName='';

        $filingno=$_REQUEST['filingno'];
        $type=$_REQUEST['type'];

        $this->form_validation->set_rules('type','Please select rp ep cp','trim|required|min_length[1]|max_length[2]|htmlspecialchars|regex_match[/^[a-z,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('type','Please Select Type','trim|required|min_length[1]|max_length[2]|htmlspecialchars|regex_match[/^[a-z,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('filingno','enter valid filing no','trim|required|min_length[15]|max_length[15]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        

        if($salt!=''){
            $hscquery = $this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
            $petadvName = $hscquery[0]->councilcode;
        }

        if($advType=='1'){
            $councilCode= htmlspecialchars($_REQUEST['councilCode']);
            if(is_numeric($councilCode)){
                $hscquery = $this->efiling_model->data_list_where('master_advocate','adv_code',$councilCode);
                $advName = $hscquery[0]->adv_name;
            }
        }
        if($advType=='2'){
            $councilCode= htmlspecialchars($_REQUEST['councilCode']);
            if(is_numeric($councilCode)){
                $hscquery = $this->efiling_model->data_list_where('efiling_users','id',$councilCode);
                $advName = $hscquery[0]->fname.' '.$hscquery[0]->lname;
            }
        }
        
        
        $edittype=$_REQUEST['action'];
        $advType=$_REQUEST['advType'];
        
        if($token==$subtoken){
            //Add Advocate Main
            if($edittype=='add' && $petadvName==''){
                $this->form_validation->set_rules('counselMobile', 'Choose council mobile', 'trim|required|numeric|max_length[200]');
                if($this->form_validation->run() == TRUE) {
                    $query_params=array(
                        'counselAdd'=>$_REQUEST['counselAdd'],
                        'counselpin'=>$_REQUEST['counselPin'],
                        'counselmobile'=>$_REQUEST['counselMobile'],
                        'counselemail'=>$_REQUEST['counselEmail'],
                        'councilcode'=>$councilCode,
                        'counselfax'=>$_REQUEST['counselFax'],
                        'counsel_phone'=>$_REQUEST['counselPhone'],
                        'advType'=>$advType,
                        'cou_dist'=>$_REQUEST['cddistrict'],
                        'cou_state'=>$_REQUEST['cdstate'],
                        
                    );
                    $data_app=$this->efiling_model->update_data('rpepcp_reffrence_table', $query_params,'salt', $salt);
                    if($data_app){
                        $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
                        $getAdvocatelist=$this->getAdvocatelist($advocatelist,$salt);
                    }
                    if($data_app){
                        echo json_encode(['data'=>'success','display'=>$getAdvocatelist,'error'=>'0']); die;
                    } else{
                        echo json_encode(['data'=>'','error'=>'1','massage'=>'DB Error found in line no '.__LINE__]); die;
                    }
                }else{
                    echo json_encode(['data'=>'','error'=>'1','massage'=>strip_tags(validation_errors())]); die;
                }
            }
            
            //Add Advocate List
            if($edittype=='add' && $petadvName!='' && $partyType!='add'){
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
                    'patitiontype'=>$type,
                );
                $st = $this->efiling_model->insert_query('sat_temp_add_advocate',$array);
                $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
                $getAdvocatelist=$this->getAdvocatelist($advocatelist,$salt);
                if($st){
                    echo json_encode(['data'=>'success','display'=>$getAdvocatelist,'error'=>'0']);die;
                }
            }

            //Edit Advocate List
            if($edittype=='edit' ){
                if($partyType=='main'){
                    $id=$_REQUEST['id'];
                    $advid=$_REQUEST['councilCode'];
                    if(is_numeric($councilCode)){
                        $hscquery = $this->efiling_model->data_list_where('master_advocate','adv_code',$advid);
                        $advName = $hscquery[0]->adv_name;
                    }
                    $query_params=array(
                        'counselAdd'=>$_REQUEST['counselAdd'],
                        'counselpin'=>$_REQUEST['counselPin'],
                        'counselmobile'=>$_REQUEST['counselMobile'],
                        'counselemail'=>$_REQUEST['counselEmail'],
                        'councilcode'=>$councilCode,
                        'counselfax'=>$_REQUEST['counselFax'],
                        'counsel_phone'=>$_REQUEST['counselPhone'],
                        'advType'=>$advType,
                        'type'=>$type,
                        'cou_dist'=>$_REQUEST['cddistrict'],
                        'cou_state'=>$_REQUEST['cdstate'],
                    );
                    $data_app=$this->efiling_model->update_data('rpepcp_reffrence_table', $query_params,'salt', $id);
                    $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
                    // $this->db->last_query();
                    $getAdvocatelist=$this->getAdvocatelist($advocatelist,$salt);
                    if($data_app){
                        echo json_encode(['data'=>'success','display'=>$getAdvocatelist,'error'=>'0']);die;
                    }
                }
                
                
                if($partyType=='add'){
                    $id=$_REQUEST['id'];
                    $advid=$_REQUEST['councilCode'];
                    
                    if($salt!=''){
                        $hscquery = $this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
                        $petadvName = $hscquery[0]->councilcode;
                    }
                    if($advType=='1'){
                        $councilCode= htmlspecialchars($_REQUEST['councilCode']);
                        if(is_numeric($councilCode)){
                            $hscquery = $this->efiling_model->data_list_where('master_advocate','adv_code',$councilCode);
                            $advName = $hscquery[0]->adv_name;
                        }
                    }
                    if($advType=='2'){
                        $councilCode= htmlspecialchars($_REQUEST['councilCode']);
                        if(is_numeric($councilCode)){
                            $hscquery = $this->efiling_model->data_list_where('efiling_users','id',$councilCode);
                            $advName = $hscquery[0]->fname.' '.$hscquery[0]->lname;
                        }
                    }
                    
                    
                    $array = array(
                        'adv_name'=>$advName,
                        'counsel_add'=>$_REQUEST['counselAdd'],
                        'counsel_pin'=>$_REQUEST['counselPin'],
                        'counsel_mobile'=>$_REQUEST['counselMobile'],
                        'counsel_email'=>$_REQUEST['counselEmail'],
                        'council_code'=>$_REQUEST['councilCode'],
                        'counsel_fax'=>$_REQUEST['counselFax'],
                        'counsel_phone'=>$_REQUEST['counselPhone'],
                        'user_id'=>$user_id,
                        'adv_district'=>$_REQUEST['cddistrict'],
                        'adv_state'=>$_REQUEST['cdstate'],
                        'entry_time'=>date('Y-m-d'),
                        'advType'=>$advType,
                        'patitiontype'=>$type,
                    );
                    $where=array('id'=>$id);
                    $st = $this->efiling_model->update_data_where('sat_temp_add_advocate',$where,$array);
                    $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
                    // $this->db->last_query();
                    $getAdvocatelist=$this->getAdvocatelist($advocatelist,$salt);
                    if($st){
                        echo json_encode(['data'=>'success','display'=>$getAdvocatelist,'error'=>'0']);die;
                    }
                }
                
            }
        }
    }
    
    
    
    
    // Delete Afvocate 
    function deleteAdvocateEPRPCP(){
        $msg='';
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('rpepcpsalt');
        $subtoken=$this->session->userdata('submittoken');
        $token=$_REQUEST['token'];
        $user_id=$userdata[0]->id;
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
                    <th>Edit</th>
        	        <th>Delete</th>
    	        </tr>
	        </thead>
	        <tbody>';
        $html.='</tbody>';
        $adv_mobile='';
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
            $statename='';
            $ddistrictname='';
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
                        if(!empty($st1)){
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
        return $html;
    }	
    //END RPEPCP add Councel 

    
    
    function orgshowresrpepcp(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $token=$_REQUEST['token'];
        $salt=$_REQUEST['salt'];
        $this->form_validation->set_rules('salt','Please enter salt ','trim|required|min_length[1]|max_length[9]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('tabno','Please enter tab number','trim|required|min_length[1]|max_length[2]|htmlspecialchars|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        $query_params=array(
            'tab_no'=>$_REQUEST['tabno']
        );
        $data_app=$this->efiling_model->update_data('rpepcp_reffrence_table', $query_params,'salt', $salt);
        if($data_app){
            echo json_encode(['data'=>'success','display'=>'','error'=>'0']);
        } else{
            echo json_encode(['data'=>'error','error'=>'DB Error found in line no '.__LINE__]);
        }
    }
    
    
    function rpepcpIAsubmit(){
        $salt=$_REQUEST['salt'];
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $this->form_validation->set_rules('salt','Please enter salt ','trim|required|min_length[1]|max_length[9]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('tabno','Please enter tab number','trim|required|min_length[1]|max_length[2]|htmlspecialchars|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('iaNature','Please enter ia Nature','trim|required|htmlspecialchars');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        
        $query_params=array(
            'tab_no'=>$_REQUEST['tabno'],
            'iaNature'=>$_REQUEST['iaNature'],
        );
        $data_app=$this->efiling_model->update_data('rpepcp_reffrence_table', $query_params,'salt', $salt);
        if($data_app){
            echo json_encode(['data'=>'success','display'=>'','error'=>'0']);
        } else{
            echo json_encode(['data'=>'error','error'=>'DB Error found in line no '.__LINE__]);
        }
    }
    
    function doc_save_nextrpepcp(){
        $token=htmlentities($this->input->post('token'));
        $subtoken=$this->session->userdata('submittoken');
        $salt=$this->session->userdata('rpepcpsalt');
        
        $this->form_validation->set_rules('salt','Please enter salt ','trim|required|min_length[1]|max_length[9]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('tabno','Please enter tab number','trim|required|min_length[1]|max_length[2]|htmlspecialchars|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        
        if($subtoken==$token){
            if($this->input->post()){
                $salt=htmlentities($this->input->post('salt'));
                $tabno=(int)$this->input->post('tabno');
                $ut=htmlentities($this->input->post('untak'));
                if($ut=='0'){
                    echo json_encode(['data'=>'Please check undertaking','error'=>'1']);die;
                }
                $feesd=$this->efiling_model->data_list_where('temp_documents_upload','salt',$salt);
                $count=count($feesd);
                $st=$this->efiling_model->data_list_where('rpepcp_reffrence_table','salt', $salt);
                $advytpe=$st[0]->advType;
                if($ut=='0'){
                    echo json_encode(['data'=>'Please check undertaking','error'=>'1']);die;
                }
                $subdoc=$this->efiling_model->data_list_where('temp_documents_upload','salt',$salt);
                foreach($subdoc as $subdocval){
                    if($subdocval->docid!=0){
                        $subdocvalue[]=$subdocval->docid;
                    }
                }
                if($advytpe==2){
                    $subdocvalue[]=13;
                }
                $doctype=$this->efiling_model->data_list_where('master_document_efile','doctype','rpepcp');
                foreach($doctype as $doc){
                    $doctypearr[]=$doc->id;
                }
                $result=array_diff($doctypearr,$subdocvalue);
                if($advytpe=='1'){
                    if(!empty($result)){
                        echo json_encode(['data'=>'Please upload all mandatory document','error'=>'1']);die;
                    }
                }
                if($advytpe=='2'){
                    if(!empty($result)){
                        echo json_encode(['data'=>'Please upload all mandatory document','error'=>'1']);die;
                    }
                }
                $datatab=array('tab_no'=>$tabno,'is_undertaking'=>$ut);
                $st1=$this->efiling_model->update_data('rpepcp_reffrence_table', $datatab,'salt', $salt);
                if($st1)  	echo json_encode(['data'=>'success','error'=>'0']);
                else  		echo json_encode(['data'=>'Query error found in line no '.__LINE__,'error'=>'1']);die;
            }
            else echo json_encode(['data'=>'Invalid request found.','error'=>'1']);die;
        }
        echo json_encode(['data'=>'Invalid request found.','error'=>'1']);die;
    }
    
    
    function chk_listdatarpepcp(){
        if($this->session->userdata('login_success') && $this->input->post()) {
            $salt=$this->session->userdata('rpepcpsalt');
            $userdata=$this->session->userdata('login_success');
            $user_id=$userdata[0]->id;
            $token=htmlentities($this->input->post('token'));
            $type=htmlentities($this->input->post('type'));
            $subtoken=$this->session->userdata('submittoken');
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
                if($db) echo json_encode(['data'=>'success','error'=>'0']);
                else 	echo json_encode(['data'=>'Qyery error, try again','error'=>'1']);
            }
        }
        else echo json_encode(['data'=>'Permission deny!','error'=>'1']);
    }
    
    
    
    function otherFeesaveRPEPCP(){
        $salt=$this->session->userdata('rpepcpsalt');
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $token=htmlentities($this->input->post('token'));
        $type=htmlentities($this->input->post('type'));
        $subtoken=$this->session->userdata('submittoken');
        if($subtoken==$token){
            $tabno=$_REQUEST['tabno'];
            $fee=$_REQUEST['fee'];
            $datatab=array(
                'other_fee' =>$fee,
                'otherFeeCode' =>$fee,
                'tab_no'    => $_REQUEST['tabno'],
            );
            $st1=$this->efiling_model->update_data('rpepcp_reffrence_table', $datatab,'salt', $salt);  
            echo json_encode(['data'=>'success','display'=>'','error'=>'0']);die;
        }else{
             echo json_encode(['data'=>'error','display'=>'Request not valid','error'=>'1']);die;
        }
    }
    
    
    
    function fpsave(){
        $salt=$this->session->userdata('rpepcpsalt');
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
            $st1=$this->efiling_model->update_data('rpepcp_reffrence_table', $datatab,'salt', $salt);
            echo json_encode(['data'=>'success','display'=>'','error'=>'0']);die;
        }else{
            echo json_encode(['data'=>'error','display'=>'Request not valid','error'=>'1']);die;
        }
    }
    
    
    function rpepcpefiling($salt,$tab){
        $userdata=$this->session->userdata('login_success');
        $this->session->set_userdata('refiling','refile');
        $user_id=$userdata[0]->id;
        if($user_id!=''){
            $this->session->set_userdata('rpepcpsalt',$salt);
            if($tab=='0'){
                $this->petitiondetail();
            }
            if($tab=='1'){
                $this->petitionparty();
            }
            if($tab=='2'){
                $this->petitionPriority();
            }
            if($tab=='3'){
                $this->petitionadv();
            }
            if($tab=='4'){
                $this->petitionIa();
            }
            if($tab=='5'){
                $this->petitionDoc();
            }
            if($tab=='6'){
                $this->petitionCheck();
            }
            if($tab=='7'){
                $this->petitionCfee();
            }
            if($tab=='8'){
                $this->petitionFP();
            }
            if($tab=='9'){
                $this->petitionPay();
            }
            
        }else{
            echo json_encode(['data'=>'failed','display'=>'','error'=>'User Not Valid']);
        }
    }
    
    
    function rpcpeppayment(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data= $this->input->post();
            $this->load->view("rpepcp/paymentrpepcp",$data);
        }
    }
    
    
    
    function rpepcpsave(){
        $printIAno='';
        $resName='';
        $total='';
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('rpepcpsalt');
        $user_id=$userdata[0]->id;
        $submittype=isset($_REQUEST['submittype'])?$_REQUEST['submittype']:'';
        if($submittype=='finalsave'){
            $record = $this->efiling_model->data_list_where('rpepcp_reffrence_table','salt',$salt);
            if($user_id){
                $curdate = date('Y-m-d');
                $curYear=date('Y');
                $caseT=isset($_REQUEST['type'])?$_REQUEST['type']:''; 
                $st=$this->efiling_model->data_list_where('rpepcp_reffrence_table','salt', $salt);
                $ianature=isset($st[0]->iaNature)?$st[0]->iaNature:'';
                $anx=isset($st[0]->totalNoAnnexure)?$st[0]->totalNoAnnexure:'';
                $iano=isset($st[0]->totalNoia)?$st[0]->totalNoia:'';
                $totalann = $anx;
                $valia=explode(',', $ianature);
                $iawa='0';
                $totalIA=0;
                if(in_array('3', $valia)){
                    $iawa='3';
                }
                if ($caseT == 'rp') {
                    $court_fee = 30000;
                    $processfee = 2000;
                }
                $nature = explode(",", $ianature);
                $len = count($nature) - 1;
                $annFee=0;
                $atotal=0;
                for ($i = 0; $i < $len; $i++) {
                    if ($nature[$i] == 11) {
                        $atotal = $totalann * 25;
                    } if ($nature[$i] == 7) {
                        $annFee = $annFee + 25;
                    }
                }
                if ($iano != "") {
                    $totalIA = $iano * 1000;
                }
                if($totalann!=''){
                    $atotal=$totalann*25;
                }
                $atotal1 = $atotal + $annFee;
                $anxtotal = $totalIA + $atotal1+25;
                $total = $anxtotal + $court_fee + $processfee;
                $waver=$anxtotal + $processfee;
                 //Type   : RP/EP/CP
                $caseType=$this->getcaseType($caseT);  //Get new case type
                $filing_no_old = isset($record[0]->filing_no)?$record[0]->filing_no:'';
                $partyType =isset($record[0]->partyType)?$record[0]->partyType:'';
                $order_date =isset($record[0]->order_date)?$record[0]->order_date:'';
                $matter =isset($record[0]->matter)?$record[0]->matter:''; 
                $advType=isset($record[0]->advType)?$record[0]->advType:''; 
                //IA party detai;
                $iaNature = isset($record[0]->iaNature)?$record[0]->iaNature:'';
                $feecode = explode(",", $iaNature); //IA list
                //main party detai;
                $addparty = isset($record[0]->app_party_id)?$record[0]->app_party_id:'';
                $par = isset($record[0]->app_priority)?$record[0]->app_priority:'';
                $partyidmain = explode(",", $addparty);
                $paritymain = explode(",", $par);

                $resid = isset($record[0]->res_partyid)?$record[0]->res_partyid:'';
                $parityres =isset($record[0]->res_priority)?$record[0]->res_priority:'';
                $subject =isset($record[0]->subject)?$record[0]->subject:'';
                $councilcode =isset($record[0]->councilcode)?$record[0]->councilcode:'';

                $resid1 = explode(",", $resid);
                $parityres1 = explode(",", $parityres);
                $newfiling_noss=$this->createfilingNo($filing_no_old,$curYear);
                $newfiling_no=$newfiling_noss['new_filing_no'];
                $fil_no=$newfiling_noss['xcount'];
                $benchCode=$newfiling_noss['benchCode'];
                $subBenchCode=$newfiling_noss['subBenchCode'];
                if ($partyidmain[0] != "" and $paritymain[0] != "") {
                    $updateflag = 'No';
                    $len1 = sizeof($paritymain);
                    $len1 = $len1 - 1;
                    $adv=0;
                    $counsel_address='';
                    $counsel_email='';
                    $counsel_fax='';
                    $counsel_pin='';
                    $counsel_mobile='';
                    $counsel_phone='';
                    if($advType==1){
                        if($councilcode!=''){
                            $rowvalvc=$this->efiling_model->data_list_where('master_advocate','adv_code',$councilcode);
                            if(!empty($rowvalvc) && is_array($rowvalvc)){
                                foreach($rowvalvc as $rowval){
                                    $adv=$councilcode;
                                    $advname=$rowval->adv_name;
                                    $counsel_address=$rowval->address;
                                    $counsel_email=$rowval->email;
                                    $counsel_fax=$rowval->adv_fax;
                                    $counsel_pin=$rowval->adv_pin;
                                    $counsel_mobile=$rowval->adv_mobile;
                                    $counsel_phone=$rowval->adv_phone;
                                }
                            }
                        }
                    }

                    if($advType==2){
                        if($councilcode!=''){
                            $rowvalvc=$this->efiling_model->data_list_where('efiling_users','id',$councilcode);
                            if(!empty($rowvalvc) && is_array($rowvalvc)){
                                foreach($rowvalvc as $rowval){
                                    $adv=$rowval->id;
                                    $advname=$rowval->fname.' '.$rowval->lname;
                                    $counsel_address=$rowval->address;
                                    $counsel_email=$rowval->email;
                                    $counsel_fax='';
                                    $counsel_pin='';
                                    $counsel_mobile=$rowval->mobilenumber;
                                    $counsel_phone='';
                                }
                            }
                        }
                    }
                    
                    $foriamailparty='';
                    $partytypeforia='';
                    for ($ii = 0; $ii < $len1; $ii++) {
                        if ($partyidmain[$ii] == 1) {
                            //sat_case_detail;
                            if ($paritymain[$ii] == 1) {
                                $sql = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no_old);
                                foreach ($sql as $row) {
                                    $partytypeforia='1';
                                    $fs='1';
                                    if ($partyType == 1) {
                                        $foriamailparty.=$fs.',';
                                        $updateflag = 'yes';
                                        $pet_name = $row->pet_name;
                                        $pet_address=$row->pet_address;
                                        $pet_pin=$row->pet_pin;
                                        $pet_phone=$row->pet_phone;
                                        $pet_fax=$row->pet_fax;
                                        $pet_district=isset($row->pet_district)?$row->pet_district:0;
                                        $pet_mobile=$row->pet_mobile;
                                        $pet_state=isset($row->pet_state)?$row->pet_state:0;
                                        $data=array(
                                            'filing_no'=>$newfiling_no,
                                            'case_type'=>$caseType,
                                            'dt_of_filing'=>$curdate,
                                            'pet_name'=>$pet_name,
                                            'user_id'=>$user_id,
                                            'order_date'=>$order_date,
                                            'filed_user_id'=>$user_id,
                                            'ref_filing_no'=>$filing_no_old,
                                            'status'=>'P',
                                            'order_date'=>$order_date,
                                            'pet_address'=>$pet_address,
                                            'pet_pin'=>$pet_pin,
                                            'pet_phone'=>$pet_phone,
                                            'pet_fax'=>$pet_fax,
                                            'pet_district'=>$pet_district,
                                            'pet_mobile'=>$pet_mobile,
                                            'pet_state'=>$pet_state, 
                                            'pet_adv'=>$adv,
                                            'pet_counsel_address'=>$counsel_address,
                                            'pet_counsel_email'=>$counsel_email,
                                            'pet_counsel_fax'=>$counsel_fax,
                                            'pet_counsel_pin'=>$counsel_pin,
                                            'pet_counsel_mobile'=>$counsel_mobile,
                                            'pet_counsel_phone'=>$counsel_phone,
                                            'status'=>'P',
                                        );
                                        $sqlpet2 = $this->efiling_model->insert_query('sat_case_detail',$data);
                                    }
                                    
                                    if ($partyType == 2) {
                                        $updateflag = 'yes';
                                        $foriamailparty.=$fs.',';
                                        $updateflag = 'yes';
                                        $res_name = $row->res_name;
                                        $res_address=$row->res_address;
                                        $res_pin=$row->res_pin;
                                        $res_phone=$row->res_phone;
                                        $res_fax=$row->res_fax;
                                        $res_district=isset($row->res_district)?$row->res_district:'0';
                                        $res_mobile=$row->res_mobile;
                                        $res_state=isset($row->res_state)?$row->res_state:'0';
                                        $data=array(
                                            'filing_no'=>$newfiling_no,
                                            'case_type'=>$caseType,
                                            'dt_of_filing'=>$curdate,
                                            'pet_name'=>$res_name,
                                            'pet_address'=>$res_address,
                                            'pet_pin'=>$res_pin,
                                            'pet_phone'=>$res_phone,
                                            'pet_fax'=>$res_fax,
                                            'pet_district'=>$res_district,
                                            'pet_mobile'=>$res_mobile,
                                            'pet_state'=>$res_state,
                                            'pet_adv'=>$adv,
                                            'pet_counsel_address'=>$counsel_address,
                                            'pet_counsel_email'=>$counsel_email,
                                            'pet_counsel_fax'=>$counsel_fax,
                                            'pet_counsel_pin'=>$counsel_pin,
                                            'pet_counsel_mobile'=>$counsel_mobile,
                                            'pet_counsel_phone'=>$counsel_phone,
                                            'user_id'=>$user_id,
                                            'ref_filing_no'=>$filing_no_old,
                                            'matter'=>$matter,
                                            'order_date'=>$order_date,
                                            'status'=>'P',
                                        );
                                        $sqlpet2 = $this->efiling_model->insert_query('sat_case_detail',$data);
                                    }
                                }
                            } else {
                                $sql =$this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no_old);
                                foreach ($sql as $row) {
                                    if ($partyType == 1) {
                                        $pet_name = $row->pet_name;
                                        $flagid = 1;
                                    }
                                    if ($partyType == 2) {
                                        $pet_name = $row->res_name;
                                        $flagid = 1;
                                    }
                                    $data=array(
                                        'filing_no'=>$newfiling_no,
                                        'party_flag'=>$flagid,
                                        'pet_name'=>$pet_name,
                                        'partysrno'=>$paritymain[$ii],
                                    );
                                    $sqlpet2 = $this->efiling_model->insert_query('additional_party',$data);
                                }
                            }
                        } else {
                            if ($partyidmain[$ii] != 1) {
                                if ($paritymain[$ii] == 1 and $updateflag == 'No') {
                                    $sqladd1 = $this->efiling_model->data_list_where('additional_party','party_id',$partyidmain[$ii]);
                                    foreach ($sqladd1 as $row) {
                                        $fs='1';
                                        $foriamailparty.=$fs.',';
                                        $pet_name11 = $row->pet_name;
                                        $foriasrno= $row->partysrno;
                                        $pet_address =  isset($row->pet_address)?$row->pet_address:'';
                                        $pin_code =  isset($row->pin_code)?$row->pin_code:0;
                                        $pet_state =  isset($row->pet_state)?$row->pet_state:0;
                                        $pet_dis = isset($row->pet_dis)?$row->pet_dis:0;
                                        $pet_mobile = isset($row->pet_mobile)?$row->pet_mobile:'';
                                        $pet_phone = isset($row->pet_phone)?$row->pet_phone:'';
                                        $pet_email =  isset($row->pet_email)?$row->pet_email:'';
                                        $pet_fax = isset($row->pet_fax)?$row->pet_fax:'0';
                                        if ($partyType == 1) {
                                            $data=array(
                                                'filing_no'=>$newfiling_no,
                                                'case_type'=>$caseType,
                                                'dt_of_filing'=>$curdate,
                                                'pet_name'=>$pet_name11,
                                                'pet_address'=>$pet_address,
                                                'pet_pin'=>$pin_code,
                                                'pet_phone'=>$pet_phone,
                                                'pet_fax'=>$pet_fax,
                                                'pet_district'=>$pet_dis,
                                                'pet_mobile'=>$pet_mobile,
                                                'pet_state'=>$pet_state,
                                                'pet_email'=>$pet_email,
                                                'pet_adv'=>$adv,
                                                'pet_counsel_address'=>$counsel_address,
                                                'pet_counsel_email'=>$counsel_email,
                                                'pet_counsel_fax'=>$counsel_fax,
                                                'pet_counsel_pin'=>$counsel_pin,
                                                'pet_counsel_mobile'=>$counsel_mobile,
                                                'pet_counsel_phone'=>$counsel_phone,
                                                'user_id'=>$user_id,
                                                'ref_filing_no'=>$filing_no_old,
                                                'matter'=>$matter,
                                                'order_date'=>$order_date,
                                                'status'=>'P',
                                            );
                                            $sqlpet = $this->efiling_model->insert_query('sat_case_detail',$data);
                                        }
                                        if ($partyType == 2) {
                                            $fs='1';
                                            $foriamailparty.=$fs.',';
                                            $data=array(
                                                'filing_no'=>$newfiling_no,
                                                'case_type'=>$caseType,
                                                'dt_of_filing'=>$curdate,
                                                'pet_name'=>$pet_name11,
                                                'pet_address'=>$pet_address,
                                                'pet_pin'=>$pin_code,
                                                'pet_phone'=>$pet_phone,
                                                'pet_fax'=>$pet_fax,
                                                'pet_district'=>$pet_dis,
                                                'pet_mobile'=>$pet_mobile,
                                                'pet_state'=>$pet_state,
                                                'pet_email'=>$pet_email,
                                                'pet_adv'=>$adv,
                                                'pet_counsel_address'=>$counsel_address,
                                                'pet_counsel_email'=>$counsel_email,
                                                'pet_counsel_fax'=>$counsel_fax,
                                                'pet_counsel_pin'=>$counsel_pin,
                                                'pet_counsel_mobile'=>$counsel_mobile,
                                                'pet_counsel_phone'=>$counsel_phone,
                                                'user_id'=>$user_id,
                                                'ref_filing_no'=>$filing_no_old,
                                                'matter'=>$matter,
                                                'order_date'=>$order_date,
                                                'status'=>'P',
                                            );
                                            $sqlpet = $this->efiling_model->insert_query('sat_case_detail',$data);
                                        }
                                    }
                                } else {
                                    $sqladd1 = $this->efiling_model->data_list_where('additional_party','party_id',$partyidmain[$ii]);
                                    foreach ($sqladd1 as $row) {
                                        $pet_name11 = $row->pet_name;
                                        if ($partyType == 1) {
                                            $flagid1 = 1;
                                        }
                                        if ($partyType == 2) {
                                            $flagid1 = 1;
                                        }
                                        if ($partyType == 3) {
                                            $flagid1 = 1;
                                        }
                                        $data=array(
                                            'filing_no'=>$newfiling_no,
                                            'party_flag'=>$flagid1,
                                            'pet_name'=>$pet_name11,
                                            'partysrno'=>$paritymain[$ii],
                                        );
                                        $sqlpet = $this->efiling_model->insert_query('additional_party',$data);
                                        $partyidmainaa= $this->db->insert_id();
                                        $foriamailparty.=$partyidmainaa.',';
                                    }
                                }
                            } 
                        }
                    }
                }
                $data=array('filing_no' =>$fil_no);
                $st1 = $this->efiling_model->update_data('year_initialization',$data,'year',$curYear);
                $flagres = 'No';
                if ($parityres1[0] != "" and $resid1[0] != "") {
                    $flagidres = 2;
                    $lenres = sizeof($resid1);
                    $lenres = $lenres - 1;
                    for ($j = 0; $j < $lenres; $j++) {
                        if ($resid1[$j] == '1P' or $resid1[$j] == '1R') {
                            $sql = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no_old);
                            foreach ($sql as $row) {
                                if ($resid1[$j] == '1P')
                                        $resName = $row->pet_name;
                                        $pet_address=$row->pet_address;
                                        $pet_pin=$row->pet_pin;
                                        $pet_phone=$row->pet_phone;
                                        $pet_fax=$row->pet_fax;
                                        $pet_district=isset($row->pet_district)?$row->pet_district:'0';
                                        $pet_mobile=$row->pet_mobile;
                                        $pet_state=isset($row->pet_state)?$row->pet_state:'0';
                                        $pet_email=isset($row->pet_email)?$row->pet_email:'0';
                                        $pet_adv=isset($row->pet_adv)?$row->pet_adv:'0';
                                    if ($resid1[$j] == '1R')
                                        $resName = $row->res_name;
                                        $res_address=$row->res_address;
                                        $res_pin=$row->res_pin;
                                        $res_phone=$row->res_phone;
                                        $res_email=$row->res_email;
                                        $res_fax=$row->res_fax;
                                        $res_district=isset($row->res_district)?$row->res_district:'0';
                                        $res_mobile=$row->res_mobile;
                                        $res_state=isset($row->res_state)?$row->res_state:'0';
                            }
                            if ($parityres1[$j] == 1) {
                                $ipaddress = $_SERVER['REMOTE_ADDR'];
                                $user_id = $user_id;
                                $update_type = 'review_filing_action';
                                
                                $where=array(
                                    'filing_no'=>$newfiling_no,
                                );
                                $data=array(
                                    'res_name'=>$resName,
                                    'res_address'=>$res_address,
                                    'res_pin'=>$res_pin,
                                    'res_phone'=>$res_phone,
                                    'res_email'=>$res_email,
                                    'res_fax'=>$res_fax,
                                    'res_district'=>$res_district,
                                    'res_mobile'=>$res_mobile,
                                    'res_state'=>$res_state,
                                    'user_id'=>$user_id,
                                    'ip'=>$ipaddress, 
                                    'update_type'=>$update_type,
                                );
                                $st = $this->efiling_model->update_data_where('sat_case_detail',$where,$data);
                                $flagres = 'yes';
                            } else {
                                $data=array(
                                    'filing_no'=>$newfiling_no,
                                    'party_flag'=>$flagidres,
                                    'pet_name'=>$resName,
                                    'partysrno'=>$parityres1[$j],
                                );
                                $sqlpet = $this->efiling_model->insert_query('additional_party',$data);
                                $partyidmaineee= $this->db->insert_id();
                                $foriamailparty.=$partyidmaineee.',';
                            }
                        } else {
                            if ($resid1[$j] != '1P' or $resid1[$j] != '1R') {
                                if ($parityres1[$j] == 1 and $flagres == 'No') {
                                    $sqladd11 =  $this->efiling_model->data_list_where('additional_party','party_id',$resid1[$j]);
                                    $resName = '';
                                    $pet_address='';
                                    $pet_email='';
                                    $pet_mobile='';
                                    $pet_phone='';
                                    $pet_fax='';
                                    $pet_state='';
                                    $pet_dis='';
                                    foreach ($sqladd11 as $row) {
                                        $resName = $row->pet_name;
                                        $pet_address=$row->pet_address;
                                        $pet_email=$row->pet_email;
                                        $pet_pin=$row->pin_code;
                                        $pet_mobile=$row->pet_mobile;
                                        $pet_phone=$row->pet_phone;
                                        $pet_fax=$row->pet_fax;
                                        $pet_state=$row->pet_state;
                                        $pet_dis=$row->pet_dis;
                                    }
                                    $ipaddress = $_SERVER['REMOTE_ADDR'];
                                    $user_id = $user_id;
                                    $update_type = 'review_filing_action';
                                    $where=array('filing_no'=>$newfiling_no,  );
                                    $data=array(
                                        'res_name'=>$resName,
                                        'res_address'=>$pet_address,
                                        'res_pin'=>$pet_pin,
                                        'res_phone'=>$pet_phone,
                                        'res_email'=>$pet_email,
                                        'res_fax'=>$pet_fax,
                                        'res_district'=>$pet_dis,
                                        'res_mobile'=>$pet_mobile,
                                        'res_state'=>$pet_state,
                                        'user_id'=>$user_id, 
                                        'ip'=>$ipaddress, 
                                        'update_type'=>$update_type
                                    );
                                    $resupeate = $this->efiling_model->update_data_where('sat_case_detail',$where,$data);
                                    $flagres = 'yes';
                                } else {
                                    $sqladd1 = $this->efiling_model->data_list_where('additional_party','party_id',$resid1[$j]);
                                    foreach ($sqladd1 as $row) {
                                        $pet_name11 = $row->pet_name;
                                        $pet_address=$row->pet_address;
                                        $pet_email=$row->pet_email;
                                        $pet_mobile=$row->pet_mobile;
                                        $pet_phone=$row->pet_phone;
                                        $pet_fax=$row->pet_fax;
                                        $pet_state=$row->pet_state;
                                        $pet_dis=$row->pet_dis;
                                        $data=array(
                                            'filing_no'=>$newfiling_no,
                                            'party_flag'=>$flagidres,
                                            'pet_name'=>$resName,
                                            'pet_address'=>$pet_address,
                                            'pet_email'=>$pet_email,
                                            'pet_mobile'=>$pet_mobile,
                                            'pet_phone'=>$pet_phone,
                                            'pet_fax'=>$pet_fax,
                                            'pet_state'=>$pet_state,
                                            'pet_dis'=>$pet_dis,
                                            'partysrno'=>$parityres1[$j],
                                        );
                                        $sqlpet = $this->efiling_model->insert_query('additional_party',$data);
                                        $partyidmaineee= $this->db->insert_id();
                                        $foriamailparty.=$partyidmaineee.',';
                                    }
                                }
                            }
                        }
                    }
                }
                //RP Document Upload
                //Document Filing
                $st=$this->efiling_model->data_list_where('temp_documents_upload','salt',$salt);
                if(!empty($st)){
                    foreach($st as $vals){
                        $data12=array(
                            'filing_no'=>$newfiling_no,
                            'user_id'=>$user_id,
                            'valumeno'=>$vals->valumeno,
                            'document_filed_by'=>$vals->document_filed_by,
                            'document_type'=>$vals->document_type,
                            'no_of_pages'=>$vals->no_of_pages,
                            'file_url'=>$vals->file_url,
                            'display'=>$vals->display,
                            'update_on'=>$vals->update_on,
                            'matter'=>$vals->matter,
                            'doc_type'=>$vals->doc_type,
                            'submit_type'=>$vals->submit_type,
                            'docid'=>$vals->docid,
                            'doc_name'=>$vals->doc_name,
                        );
                        $st=$this->efiling_model->insert_query('efile_documents_upload',$data12);
                    }
                }
                
                $iaFilingNossssss = '';
                //print_r($iaNature);
                if ($feecode[0] != "") {
                    $len = sizeof($feecode);
                    $len = $len - 1;
                    for ($k = 0; $k < $len; $k++) {
                        $ia_nature = $feecode[$k];
                        $year_ins =$this->efiling_model->data_list_where('ia_initialization','year',$curYear);
                        $ia_filing_no1 = $year_ins[0]->ia_filing;
                        if ($ia_filing_no1 == 0) {
                            $iaFilingNo = 1;
                            $ia_filing_no = '000001';
                        }
                        if ($ia_filing_no1 != 0) {
                            $iaFilingNo = $ia_filing_no = (int)$ia_filing_no1 + 1;
                            $length = 6 - strlen($ia_filing_no);
                            for ($i = 0; $i < $length; $i++) {
                                $ia_filing_no = "0" . $ia_filing_no;
                            }
                        }
                        $iaFilingNossssss .= $iaFilingNo . ',';
                        $iaFiling_no1 = $benchCode . $subBenchCode . $ia_filing_no . $curYear;
                        $printIAno = $printIAno . "IA/" . $iaFilingNo . "/" . $curYear . "<br>";
                        $data=array(
                            'ia_no'=>$iaFilingNo,
                            'filing_no'=>$newfiling_no,
                            'filed_by'=>$partyType,
                            'entry_date'=>$curdate,
                            'display'=>'Y',
                            'ia_filing_no'=>$iaFiling_no1,
                            'ia_nature'=>$ia_nature,
                            'status'=>'P',
                        );
                        $sqlpet = $this->efiling_model->insert_query('satma_detail',$data);
                        $where=array('year'=>$curYear,  );
                        $data=array('ia_filing'=>$iaFilingNo);
                        $resupeate = $this->efiling_model->update_data_where('ia_initialization',$where,$data);
                    }
                }
  
                if($newfiling_no!=''){
                    $datyw=array(
                        'filing_no'=>$newfiling_no,
                        'dt_of_filing'=>$curdate,
                        'bench'=>'1',
                        'sub_bench'=>'1',
                        'case_type'=>$caseType,
                        'case_year'=>'',
                        'decision_date'=>$order_date,
                        'commission'=>'32',
                        'nature_of_order'=>'22',
                        'matter_description'=>'',
                        'communication_date'=>$order_date,
                        'old_filing_no'=>$filing_no_old,
                    );
                    $st=$this->efiling_model->insert_query('lower_court_detail',$datyw);
                }
                
                //additional advocate insert
                $stadv=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
                if(!empty($stadv)){
                    foreach($stadv as $stadv){
                        $sqlAdditionalAdv=array(
                            'filing_no'=>$newfiling_no,
                            'party_flag'=>'P',
                            'adv_code'=>$stadv->council_code,
                            'adv_address'=>$stadv->counsel_add,
                            'adv_email'=>$stadv->counsel_email,
                            'adv_fax_no'=>$stadv->counsel_fax,
                            'adv_mob_no'=>$stadv->counsel_mobile,
                            'adv_phone_no'=>$stadv->counsel_phone,
                            'district'=>$stadv->adv_district,
                            'state'=>$stadv->adv_state,
                            'advType'=>$stadv->advType,
                            'pin_code'=>$stadv->counsel_pin,
                            'user_id'=>$user_id,
                            'entry_date'=>date('Y-d-m'),
                        );
                    }
                    $st=$this->efiling_model->insert_query('additional_advocate',$sqlAdditionalAdv);
                } 
                
                
                if($newfiling_no!=''){
                    $data=array('filing_no'=>$newfiling_no,);
                    $sqlpet = $this->efiling_model->insert_query('scrutiny',$data);
                    $hscquery = $this->efiling_model->delete_event('sat_temp_payment','salt',$salt);
                    $pet = $this->efiling_model->delete_event('rpepcp_reffrence_table','salt',$salt);
                    $hscquery = $this->efiling_model->delete_event('sat_temp_add_advocate','salt',$salt);
                    $hscquery = $this->efiling_model->delete_event('temp_documents_upload','salt',$salt); 
                    $this->session->unset_userdata('rpepcpsalt');
                    if ($caseType != "") {
                        $filedName =$caseT;
                    }
                    $data['filing_no']= $newfiling_no;
                    $data['ia_no']= $printIAno;
                    $data['iaFilingNossssss']= $iaFilingNossssss;
                    $data['curYear']= $curYear;
                    $data['caseType']= $filedName;
                    $this ->session->set_userdata('rpepcpdetail',$data);
                    echo json_encode(['data'=>'success','display'=>'','error'=>'0']);
                }else{
                    echo json_encode(['data'=>'error','display'=>'Something went wrong','error'=>'1']);die;
                }
	        }
        }
    }

    function dfrdetailrpepcp($filing_no){
        if(!is_numeric($filing_no)){
            echo "Request not valid";die;
        }
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
            $this->load->view("rpepcp/dfrdetailrpepcp",$data);
        }
    }
    
    function getAdvrpepcp(){
        $key=$this->input->post();
        $rs=$this->admin_model->getAdv($this->input->post());
        $html='';
        foreach($rs as $vals){
            $html.='<li value="'.$vals->adv_code.'" onclick="showUserOrgrpepcp('.$vals->adv_code.')">'.$vals->adv_name.'</li>';
        }
        echo $html;die;
    }
    
    
}