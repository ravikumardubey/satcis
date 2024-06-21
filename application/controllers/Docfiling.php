<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Docfiling extends CI_Controller {
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
		$_REQUEST[$paraName]=htmlspecialchars($getPvalue);
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
		else $_POST[$key]=htmlspecialchars(strtr($val, $spcl_char));
		endforeach;
		
		$logvvalidate=$this->admin_model->logvalidate();
		if($logvvalidate==false){
		    $this->session->unset_userdata('login_success');
		    redirect(base_url());
		}
    }
    
    function  doc_basic_detail(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('docsalt');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['datacomm']= '';
            $this->load->view('docfiling/doc_basic_detail',$data);
        }
    }
    
    
    function doc_partydetail(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $salt=$this->session->userdata('docsalt');
        $valurl= $this->router->fetch_method();
        if($salt=='' && $valurl=="doc_partydetail"){
            redirect(base_url('doc_basic_detail'));
        }
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['datacomm']= '';
            $this->load->view('docfiling/doc_partydetail',$data);
        }
    }
    
    
    function ia_detail_ia(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $salt=$this->session->userdata('docsalt');
        $valurl= $this->router->fetch_method();
        if($salt==''  && $valurl=="ia_detail_ia"){
            redirect(base_url('doc_basic_detail'));
        }
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['datacomm']= $this->efiling_model->data_commission_where($salt,$user_id);
            $this->load->view('docfiling/ia_detail_ia',$data);
        }
    }
    
    function doc_upload_doc(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $salt=$this->session->userdata('docsalt');
        $valurl= $this->router->fetch_method();
        if($salt==''  && $valurl=="doc_upload_doc"){
            redirect(base_url('doc_basic_detail'));
        }
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['datacomm']='';
            $this->load->view('docfiling/doc_upload_doc',$data);
        }
    }
    
    
    function doc_checklist(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $salt=$this->session->userdata('docsalt');
        $valurl= $this->router->fetch_method();
        if($salt==''  && $valurl=="doc_checklist"){
            redirect(base_url('doc_basic_detail'));
        }
        $user_id=$userdata[0]->id;
        if($salt==''){
            redirect(base_url(),'refresh');
        }
        if($user_id){
            $data['checklist']= $this->efiling_model->data_list_where('master_checklist','status','1');
            $this->load->view('docfiling/doc_checklist',$data);
        }
    }
    
    
    function doc_finalprivew(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $salt=$this->session->userdata('docsalt');
        $valurl= $this->router->fetch_method();
        if($salt==''  && $valurl=="doc_finalprivew"){
            redirect(base_url('doc_basic_detail'));
        }
        if($user_id){
            $data['datacomm']='';
            $this->load->view('docfiling/doc_finalprivew',$data);
        }
    }
    
    
    function doc_payment(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $salt=$this->session->userdata('docsalt');
        $valurl= $this->router->fetch_method();
        if($salt==''  && $valurl=="doc_payment"){
            redirect(base_url('doc_basic_detail'));
        }
        if($user_id){
            $data['datacomm']='';
            $this->load->view('docfiling/doc_payment',$data);
        }
    }
    
    function ia_finalreceipt(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('docsalt');
        $user_id=$userdata[0]->id;
        if($salt==''){
            redirect(base_url(),'refresh');
        }
        if($user_id){
            $this->load->view('docfiling/ia_finalreceipt');
        }
    }
    
    function doc_councel(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $salt=$this->session->userdata('docsalt');
        $valurl= $this->router->fetch_method();
        if($salt==''  && $valurl=="doc_councel"){
            redirect(base_url('doc_basic_detail'));
        }
        $user_id=$userdata[0]->id;
        if($user_id){
            $this->load->view('docfiling/doc_councel');
        }
    }
    


    function saveDocbasic($csrf=NULL){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $token=hash('sha512',trim($this->input->post('token',true)).'docbasicdetail');  
        $tabno=$this->input->post('tab_no',TRUE);
        $filing_no=$this->input->post('filing_no',TRUE);
        $saltval='';
        $type=$this->input->post('type',TRUE);
        $cudate=date('Y-m-d');

        $this->form_validation->set_rules('type','Please select type','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[a-z,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('tab_no','Enter tab number.','trim|required|min_length[1]|max_length[2]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('filing_no','enter valid filing no','trim|required|min_length[15]|max_length[15]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $salt='';
        if($csrf==$token){
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
                $this->session->set_userdata('docsalt',$salt);
            }
            $postdata=array(
                'salt'=>$salt,
                'filing_no'=>$filing_no,
                'tab_no'=>$tabno,
                'entry_date'=>$cudate,
                'user_id'=>$user_id,
                'type'=>$type,
            );
            $st=$this->efiling_model->insert_query('temp_docdetail', $postdata);
            if($st){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);
        }
    }
    
    
    function docpartysave($csrf=NULL){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $token=hash('sha512',trim($this->input->post('token',true)).'docparty');  
        $salt=$this->session->userdata('docsalt');
        $cudate=date('Y-m-d');
        $this->form_validation->set_rules('partyType','Please select type','trim|required|min_length[1]|max_length[2]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('tab_no','Please enter tab number','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('filing_no','enter valid filing no','trim|required|min_length[15]|max_length[15]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('docidval','enter valid doc value','trim|required|min_length[0]|max_length[2]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('partyids','Please select party ids','trim|required|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }

        $this->form_validation->set_rules('doctype','enter valid doc type','trim|required|min_length[0]|max_length[3]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        if($this->input->post('doctype')!='va' && $this->input->post('doctype')!='oth'){ 
            $this->form_validation->set_rules('toalannexure','Please toal annexure','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
            if($this->form_validation->run() == FALSE){
                echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
            }
        }

        if($csrf==$token){
            $array=array(
                'tab_no'=>$this->input->post('tab_no',TRUE),
                'filing_no'=>$this->input->post('filing_no',TRUE),
                'entry_date'=>$cudate,
                'user_id'=>$user_id,
                'type'=>$this->input->post('type',TRUE),
                'partyType'=>$this->input->post('partyType',TRUE),
                'partys'=>$this->input->post('partyids',TRUE),
                'docids'=>$this->input->post('docidval',TRUE),
                'doctype'=>$this->input->post('doctype',TRUE),
                'totalanx'=>$this->input->post('toalannexure',TRUE),
                'matter'=>$this->input->post('matter',TRUE),
            );           
            $where=array('salt'=>$salt);
            $st = $this->efiling_model->update_data_where('temp_docdetail',$where,$array);
            if($st){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);
        }
    }
    

    
    function doc_save_nextDoc($csrf=NULL){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $token=hash('sha512',trim($this->input->post('token',true)).'docsave'); 
        $tabno=$this->input->post('tabno',true);
        $untak=$this->input->post('untak',true);
        if($untak=='0'){
            echo json_encode(['data'=>'error','error'=>'Please Check undertaking  !']);die;
        }
        $salt=$this->session->userdata('docsalt');


        $this->form_validation->set_rules('tabno','Please enter tab number','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','error'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('untak','Enter undertaking.','trim|required|min_length[1]|max_length[2]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'error'=>validation_errors(),'error'=>'1']); die;
        }
        
        
        if($csrf==$token){
            $sts=$this->efiling_model->data_list_where('temp_docdetail','salt', $salt);
            $doctype=isset($sts[0]->doctype)?$sts[0]->doctype:'';
            $docvalidation=0;
            $st=$this->efiling_model->data_list_where('temp_documents_upload','salt', $salt);
            if(!empty($st)){
                foreach($st as $dval){
                    $stvadv=$dval->document_type;
                    if($stvadv!='Vakalatnama'){
                        if($stvadv=='Proof_of_Service'){
                            $docvalidation='1';
                        }
                    }
                }
            }else{
                echo json_encode(['data'=>'error','error'=>'Please select  mandatory documents !']);die;
            }
            
            if($doctype!='va'){
                if($docvalidation==0){
                    echo json_encode(['data'=>'error','error'=>'Please select Proof of Service it is mandatory !']);die;
                }
            }
            
            $array=array(
                'tab_no'=>$tabno,
                'undertaking'=>$untak,
            );
            $where=array('salt'=>$salt);
            $st = $this->efiling_model->update_data_where('temp_docdetail',$where,$array);
            if($st){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);
        }
    }
    
    
    
    
    function docadvsave($csrf=NULL){
        $msg='';
        date_default_timezone_set('Asia/Calcutta');
        $salt= $this->session->userdata('docsalt');
        $token=hash('sha512',trim($this->input->post('token')).'advsave');
        $subtoken=$this->session->userdata('submittoken');
        if($csrf!=$token){
            echo json_encode(['data'=>'error','error'=>'DB Error found in line no '.__LINE__]);die;
        }

        $this->form_validation->set_rules('tabno','Enter tab number.','trim|required|min_length[1]|max_length[2]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $query_params=array(
            'tab_no'=>$this->input->post('tabno',true),
        );
        $data_app=$this->efiling_model->update_data('temp_docdetail', $query_params,'salt', $salt);
        if($data_app){
            echo json_encode(['data'=>'success','display'=>'','error'=>'0']);
        } else{
            echo json_encode(['data'=>'error','error'=>'DB Error found in line no '.__LINE__]);
        }
    }
    
    
    function chk_listdataDoc(){
        if($this->session->userdata('login_success') && $this->input->post()) {
            $salt=$this->session->userdata('docsalt');
            $userdata=$this->session->userdata('login_success');
            $user_id=$userdata[0]->id;
            $token=htmlentities($this->input->post('token'));
            $type=htmlentities($this->input->post('type'));
            $subtoken=$this->session->userdata('submittoken');
            $tabno=htmlentities($this->input->post('tabno'));
            
            $this->form_validation->set_rules('tabno','Enter tab number.','trim|required|min_length[1]|max_length[2]|numeric');
            if($this->form_validation->run() == FALSE){
                echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
            }
            
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
                $st = $this->efiling_model->update_data_where('temp_docdetail',$where,$array);
                if($db) echo json_encode(['data'=>'success','error'=>'0']);
                else 	echo json_encode(['data'=>'Qyery error, try again','error'=>'1']);
            }
        }
        else echo json_encode(['data'=>'Permission deny!','error'=>'1']);
    }
    
    
    
    
    
    function docfpsave($csrf=NULL){
        $salt=$this->session->userdata('docsalt');
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $token=hash('sha512',trim($this->input->post('token',true)).'finals');  
        $type=htmlentities($this->input->post('type',true));
        $iatotalfee=htmlentities($this->input->post('totalfee',true));

        $this->form_validation->set_rules('tabno','Enter tab number.','trim|required|min_length[1]|max_length[2]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('totalfee','Enter total fee.','trim|required|min_length[1]|max_length[7]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        
     
        if($csrf==$token){
            if($salt!=''){
                $basicia= $this->efiling_model->data_list_where('temp_docdetail','salt',$salt);
                $anx=isset($basicia[0]->totalanx)?$basicia[0]->totalanx:0;
                $doctype=isset($basicia[0]->doctype)?$basicia[0]->doctype:'';
                if($doctype=='va'){
                    $anx=1;
                } 
                $toalfee=$anx*25;
                if($toalfee!=$iatotalfee){
                    echo json_encode(['data'=>'error','display'=>'Amount is not valid !','error'=>'1']);die;
                }
            }
            $tabno=$this->input->post('tabno');
            $datatab=array(
                'tab_no'    =>$tabno,
                'doctotal_fee'=>$iatotalfee,
            );
            $st1=$this->efiling_model->update_data('temp_docdetail', $datatab,'salt', $salt);
            echo json_encode(['data'=>'success','display'=>'','error'=>'0']);die;
        }else{
            echo json_encode(['data'=>'error','display'=>'Request not valid','error'=>'1']);die;
        }
    }
    
    
    function chekva(){
            $party_ids='';
            $valcheck='';
            $pid = $_REQUEST['party_id'];
            $filing_no = $_REQUEST['filing_no'];
            $partyType = $_REQUEST['partyType'];
            if ($partyType == '2') {
                $flags = 'R';
            } else if ($partyType == '1') {
                $flags = 'P';
            }
            if($partyType=='1'){
                $valcheck= "yes";
            }
            if($partyType!='1'){
                $array=array('party_flag'=>$partyType,'filing_no'=>$filing_no);
                $qu_caveat_detail_data = $this->efiling_model->data_list_mulwhere('vakalatnama_detail',$array);
                $party_ids = '';
                if (!empty($qu_caveat_detail_data)) {
                    foreach ($qu_caveat_detail_data as $key => $value) {
                        $party_ids .= $value->add_party_code . ',';
                    }
                }
                $array=array('party_flag'=>$flags,'filing_no'=>$filing_no);
                $advocate_data = $this->efiling_model->data_list_mulwhere('additional_advocate',$array);
                if (!empty($advocate_data)) {
                    foreach ($advocate_data as $key => $value) {
                        $party_ids .= $value->party_code . ',';
                    }
                }
            
                if (!empty($party_ids)) {
                    $party_id = explode(',',$party_ids);
                    if (!empty($party_id)) {
                        $sr = 1;
                        if (is_array($party_id)){
                            foreach ($party_id as $value) {
                                if(is_numeric($value)){
                                    if($value==$pid){
                                        $valcheck= "yes";
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if($valcheck=='yes'){
                echo json_encode(['data'=>'success','display'=>$valcheck,'error'=>'0']);die;
            }else{
                echo json_encode(['data'=>'error','display'=>'no','error'=>'1']);die;
            }
      }
      
      function iadetail($iafiling){
          $userdata=$this->session->userdata('login_success');
          $user_id=$userdata[0]->id;
          if($user_id){
              $arr=array('ref_filing_no'=>$iafiling,'submit_type'=>'ia');
              $data['iadetail']= $this->efiling_model->data_list_where('ia_detail','ia_filing_no', $iafiling);
              $data['docs']= $this->efiling_model->data_list_mulwhere('efile_documents_upload',$arr);
              $this->load->view('ia/iadetails',$data);
          }
      }
      
      
      function addCouncelDoc($csrf=NULL){
          $advName='';
          $userdata=$this->session->userdata('login_success');
          $user_id=$userdata[0]->id;
          $salt=$this->session->userdata('docsalt');
          $subtoken=$this->session->userdata('submittoken');
          $token=hash('sha512',trim($this->input->post('token')).'doccouncel');
          $id=$this->input->post('id',true);  
          $partyType=$this->input->post('partyType',true);  
          $advType=$this->input->post('advType',true);   
          $councilCode=$this->input->post('councilCode',true);   
          $edittype=$this->input->post('action',true);   
          
          
          $this->form_validation->set_rules('advType','please select advocate type.','trim|required|min_length[1]|max_length[2]|numeric');
          if($this->form_validation->run() == FALSE){
              echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
          }

          $this->form_validation->set_rules('cdstate','Please select state','trim|required|min_length[1]|max_length[4]|htmlspecialchars|regex_match[/^[0-9]+$/]');
          if($this->form_validation->run() == FALSE){
              echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
          }
          $this->form_validation->set_rules('cddistrict','Please select district','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
          if($this->form_validation->run() == FALSE){
              echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
          }
          
          $this->form_validation->set_rules('counselPin','Enter Pin number','trim|required|min_length[6]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
          if($this->form_validation->run() == FALSE){
              echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
          }
          $this->form_validation->set_rules('counselMobile','Enter mobile number.','trim|required|min_length[10]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
          if($this->form_validation->run() == FALSE){
              echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
          }

          $this->form_validation->set_rules('counselEmail','Enter email address.','trim|required|min_length[1]|max_length[50]|htmlspecialchars');
          if($this->form_validation->run() == FALSE){
              echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
          }
          
       
          if($salt!=''){
              $hscquery = $this->efiling_model->data_list_where('temp_docdetail','salt',$salt);
              $petadvName = $hscquery[0]->council_code;
          }
          
          if($advType=='1'){
              $this->form_validation->set_rules('councilCode','Please select councel .','trim|required|min_length[1]|max_length[6]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
              if($this->form_validation->run() == FALSE){
                  echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
              }
              
              if(is_numeric($councilCode)){
                  $hscquery = $this->efiling_model->data_list_where('master_advocate','adv_code',$councilCode);
                  $advName = $hscquery[0]->adv_name;
              }
          }
          if($advType=='2'){
              $this->form_validation->set_rules('councilCode','Please select councel .','trim|required|min_length[10]|max_length[6]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
              if($this->form_validation->run() == FALSE){
                  echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
              }
              if(is_numeric($councilCode)){
                  $hscquery = $this->efiling_model->data_list_where('efiling_users','id',$councilCode);
                  $advName = $hscquery[0]->fname.' '.$hscquery[0]->lname;
              }
          }
          if($token==$csrf){
              if($edittype=='add'){
                  $array = array(
                      'salt'=>$salt,
                      'adv_name'=>$advName,
                      'counsel_add'=>$this->input->post('counselAdd',true),
                      'counsel_pin'=>$this->input->post('counselPin',true),
                      'counsel_mobile'=>$this->input->post('counselMobile',true),
                      'counsel_email'=>$this->input->post('counselEmail',true), 
                      'council_code'=> $councilCode,
                      'counsel_fax'=>$this->input->post('counselFax',true), 
                      'counsel_phone'=>$this->input->post('counselPhone',true),
                      'user_id'=>$user_id,
                      'adv_district'=> $this->input->post('cddistrict',true),
                      'adv_state'=> $this->input->post('cdstate',true),
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
          }
      }
      
      
      
      function deleteAdvocatDoc(){
          $msg='';
          $userdata=$this->session->userdata('login_success');
          $user_id=$userdata[0]->id;
          $salt= $this->session->userdata('docsalt');
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
         $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
          if(!empty($advocatelist)){
              $i=1;
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
 
      
      function ma_action($csrf=NULL){

          $userdata=$this->session->userdata('login_success');
          $userid=$userdata[0]->id;
          $curYear = date('Y');
          $curMonth = date('m');
          $curDay = date('d');
          $curdate = date('Y-m-d');
          $salt= $this->session->userdata('docsalt');
          $this->form_validation->set_rules('filing_no', 'Enter valid filing no.', 'trim|required|numeric|max_length[16]');
          $vcak='sat123';
          $token=hash('sha512',trim($this->input->post('token',true)).$vcak);  
          if($token!=$csrf){
              echo json_encode(['data'=>'error','value'=>'Request not valid','massage'=>validation_errors(),'error'=>'1']); die;
          }
          $idval='';
          if($this->form_validation->run() == false){
              echo json_encode(['data'=>'error','msg'=>validation_errors(),'display'=>validation_errors(),'error'=>1]);die;
          }else{
              if($userid){

                  $docrow = $this->efiling_model->data_list_where('temp_docdetail','salt',$salt);
                  if(count($docrow)==0){
                      echo json_encode(['data'=>'error','value'=>'Request not valid','massage'=>validation_errors(),'error'=>'1']); die;
                  }
                  $curdate = "$curYear-$curMonth-$curDay";
                  $filingNo = $docrow[0]->filing_no;
                  $ptype = $this->input->post('type');
                  $addparty = $docrow[0]->partys;
                  $totalanx=$docrow[0]->totalanx;
                  $pid =$docrow[0]->docids;
                  $doctype = $docrow[0]->doctype;
                  $advType = $docrow[0]->advType;
                  $partyType = $docrow[0]->partyType;
                  $undertaking = $docrow[0]->undertaking;
                  $matter =  $docrow[0]->matter;
                  $party_flag = 'P';
                  if($partyType =='2') {
                      $party_flag = 'R';
                  }
                  if($doctype=='va'){
                      $totalanx=1;
                  }
                  //MA filing code
                  $maFilingNo =$this->getMAFilingno($doctype,$curYear);
                  if ($doctype == 'ma' && $maFilingNo!=0) {
                      $reffFilingNo=$maFilingNo;
                      $data=array(
                          'filing_no'=>$filingNo,
                          'main_party'=>$party_flag,
                          'additional_party'=>$addparty,
                          'doc'=>$pid,
                          'total_no_annexure'=>$totalanx,
                          'ma_filing_no'=>$reffFilingNo,
                          'dt_of_filing'=>$curdate,
                          'user_id'=>$userid,
                          'doc_type'=>$doctype,
                          'matter'=>$matter,
                          'entry_date'=>$curdate,
                          'undertaking'=>$undertaking,
                      );
                      $sqlpet2 = $this->efiling_model->insert_query('ma_detail',$data);
                      $idval= $this->db->insert_id();
                      if($sqlpet2){
                          $where=array('year'=>$curYear);
                          $data=array('ma_filing'=>$maFilingNo);
                          $resupeate = $this->efiling_model->update_data_where('ia_initialization',$where,$data);
                      }
                  }

                  //vakalatnam filing code 
                   $vaFilingNo =$this->getFilingno($doctype,$curYear);
                   if ($doctype == 'va' && $vaFilingNo!='') {
                      $reffFilingNo=$vaFilingNo;
              
                      //additional advocate insert
                      $valpart=explode(',', $addparty);
                      foreach($valpart as $cad){
                           if($cad!=''){
                              $stadv=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
                              if(!empty($stadv)){
                                  $i=1;
                                  foreach($stadv as $stadv){
                                      if($i==1){
                                          $councilCode= $stadv->council_code;
                                          $cadd = $stadv->counsel_add;
                                          $cpin =  $stadv->counsel_pin;
                                          $cmob =  $stadv->counsel_mobile;
                                          $cemail =  $stadv->counsel_email;
                                          $cfax =$stadv->counsel_fax;
                                          $counselpho =  $stadv->counsel_phone;
                                          $state = $stadv->adv_state;
                                          $dist = $stadv->adv_district;
                                      }
                                      $sqlAdditionalAdv=array(
                                          'filing_no'=>$filingNo,
                                          'party_flag'=>$party_flag,
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
                                          'user_id'=>$userid,
                                          'party_code'=>$cad,
                                          'entry_date'=>date('Y-d-m'),
                                      );
                                      $st=$this->efiling_model->insert_query('additional_advocate',$sqlAdditionalAdv);
                                  $i++;}
                              }
                          }
                      } 

                      $sql = array(
                          'filing_no'=>$filingNo,
                          'party_flag'=>$party_flag,
                          'adv_code'=>$councilCode,
                          'adv_mob_no'=>$cmob,
                          'adv_phone_no'=>$counselpho,
                          'adv_fax_no'=>$cfax,
                          'adv_email'=>$cemail,
                          'adv_address'=>$cadd,
                          'user_id'=>$userid,
                          'pin_code'=>$cpin,
                          'add_party_code'=>$addparty,
                          'district'=>$dist,
                          'state'=>$state,
                          'vakalatnama_no'=>$reffFilingNo,
                          'entry_date'=>$curdate,
                          'dt_of_filing'=>$curdate,
                          'doc_type'=>$doctype,
                          'matter'=>$matter,
                          'entry_date'=>$curdate,
                          'doc_id'=>$pid,
                          'undertaking'=>$undertaking,
                      );
                      $sqlpet2 = $this->efiling_model->insert_query('vakalatnama_detail',$sql);
                      $idval= $this->db->insert_id;
                      $where=array('year'=>$curYear);
                      $data=array('vakalatnama_filing'=>$vaFilingNo);
                      $resupeate = $this->efiling_model->update_data_where('ia_initialization',$where,$data);
                  }
                  
                  //IA Document Upload
                  $st=$this->efiling_model->data_list_where('temp_documents_upload','salt',$salt);
                  if(!empty($st)){
                      foreach($st as $vals){
                          $data12=array(
                              'filing_no'=>$filingNo,
                              'user_id'=>$userid,
                              'valumeno'=>$vals->valumeno,
                              'document_filed_by'=>$vals->document_filed_by,
                              'document_type'=>$vals->document_type,
                              'no_of_pages'=>$vals->no_of_pages,
                              'file_url'=>$vals->file_url,
                              'display'=>$vals->display,
                              'update_on'=>$vals->update_on,
                              'matter'=>$vals->matter,
                              'doc_type'=>$vals->doc_type,
                              'ref_filing_no'=>$reffFilingNo,
                              'submit_type'=>$vals->submit_type,
                              'docid'=>$vals->docid,
                              'doc_name'=>$vals->doc_name,
                          );
                          $st=$this->efiling_model->insert_query('efile_documents_upload',$data12);
                      }
                  }

                  $data['msg']="Successfully submited";
                  $data['doc_filing']=$reffFilingNo;
                  $data['filingNo']=$filingNo;
                  $data['doc_type']=$doctype;
                  $data['id']=$idval;
                  $this ->session->set_userdata('docdetail',$data);
                  $this->efiling_model->delete_event('temp_documents_upload','salt',$salt);
                  $this->efiling_model->delete_event('sat_temp_add_advocate','salt',$salt);
                  $this->efiling_model->delete_event('temp_docdetail','salt',$salt);
                  $this->efiling_model->delete_event('sat_temp_payment','salt',$salt); 
                  echo json_encode(['data'=>'success','display'=>'','msg'=>'']);die;
              }else{
                  $msg='Request not valid!';
                  echo json_encode(['data'=>'error','display'=>$msg,'error'=>'1','massage'=>$msg]);die;
              }
          }
      }
      
      
      function  othdocsave(){
          $userdata=$this->session->userdata('login_success');
          $userid=$userdata[0]->id;
          $curYear = date('Y');
          $curMonth = date('m');
          $curDay = date('d');
          $curdate = date('Y-m-d');
          $salt= $this->session->userdata('docsalt');
          $this->form_validation->set_rules('filing_no', 'Enter valid filing no.', 'trim|required|numeric|max_length[16]');
          if($this->form_validation->run() == false){
              echo json_encode(['data'=>'error','msg'=>validation_errors(),'display'=>'','error'=>validation_errors()]);die;
          }else{
              if($userid){
                  $docrow = $this->efiling_model->data_list_where('temp_docdetail','salt',$salt);
                  if(empty($docrow)){
                      echo json_encode(['data'=>'error','value'=>'Rwquest not valid','massage'=>validation_errors(),'error'=>'1']); die;
                  }
                  $matter = $this->input->post('matter');
                  $total_feeeee = $this->input->post('doctotal_fee');
                  $curdate = "$curYear-$curMonth-$curDay";
                  $filingNo = $docrow[0]->filing_no;
                  $ptype = $_REQUEST['type'];
                  $addparty = $docrow[0]->partys;
                  $totalanx='0';
                  $pid =$docrow[0]->docids;
                  $doctype = $docrow[0]->doctype;
                  $partyType = $docrow[0]->partyType;
                  $undertaking = $docrow[0]->undertaking;
                  $party_flag = 'P';
                  if($partyType =='2') {
                      $party_flag = 'R';
                  }
                  $valfee=0;
                  $totalfees=0;
                  //MA filing code
                  $maFilingNo =$this->getDocFilingno($doctype,$curYear);
                  if ($doctype == 'oth' && $maFilingNo!=0) {
                      $reffFilingNo=$maFilingNo;
                      $data=array(
                          'filing_no'=>$filingNo,
                          'main_party'=>$party_flag,
                          'additional_party'=>$addparty,
                          'doc'=>$pid,
                          'total_no_annexure'=>$totalanx,
                          'ma_filing_no'=>$reffFilingNo,
                          'dt_of_filing'=>$curdate,
                          'user_id'=>$userid,
                          'doc_type'=>$doctype,
                          'matter'=>$matter,
                          'entry_date'=>$curdate,
                          'total_fee'=>$total_feeeee,
                          'undertaking'=>$undertaking,
                      );
                      $sqlpet2 = $this->efiling_model->insert_query('ma_detail',$data);
                      if($sqlpet2){
                          $where=array('year'=>$curYear);
                          $data=array('ma_filing'=>$maFilingNo);
                          $resupeate = $this->efiling_model->update_data_where('ia_initialization',$where,$data);
                      }
                  }
                  
                  //IA Document Upload
                  $st=$this->efiling_model->data_list_where('temp_documents_upload','salt',$salt);
                  if(!empty($st)){
                      foreach($st as $vals){
                          $data12=array(
                              'filing_no'=>$filingNo,
                              'user_id'=>$userid,
                              'valumeno'=>$vals->valumeno,
                              'document_filed_by'=>$vals->document_filed_by,
                              'document_type'=>$vals->document_type,
                              'no_of_pages'=>$vals->no_of_pages,
                              'file_url'=>$vals->file_url,
                              'display'=>$vals->display,
                              'update_on'=>$vals->update_on,
                              'matter'=>$vals->matter,
                              'doc_type'=>$vals->doc_type,
                              'ref_filing_no'=>$reffFilingNo,
                              'submit_type'=>$vals->submit_type,
                              'docid'=>$vals->docid,
                              'doc_name'=>$vals->doc_name,
                          );
                          $st=$this->efiling_model->insert_query('efile_documents_upload',$data12);
                      }
                  }
                  $data['msg']="Successfully submited";
                  $data['doc_filing']=$reffFilingNo;
                  $data['filingNo']=$filingNo;
                  $data['doc_type']=$doctype;
                  $this ->session->set_userdata('docdetail',$data);
                  echo json_encode(['data'=>'success','display'=>'','msg'=>'']);die;
              }else{
                  $msg='Request not valid!';
                  echo json_encode(['data'=>'error','display'=>$msg,'error'=>'1','massage'=>$msg]);die;
              }
          }
      }
      
      
      
      //Other va upload
      function getFilingno($doctype,$curYear){
          $vaFilingNo=0;
          if ($doctype == 'va') {
              $year_ins = $this->efiling_model->data_list_where('ia_initialization','year',$curYear);
              $va_filing_no = $year_ins[0]->vakalatnama_filing;
              if ($va_filing_no == 0) {
                  $vaFilingNo = 1;
              }
              if ($va_filing_no != 0) {
                  $vaFilingNo = (int)$va_filing_no + 1;
              }
          }
          return $vaFilingNo;
      }

      
      
     
      //Other MA upload
      function getMAFilingno($doctype,$curYear){
          $maFilingNo=0;
          if ($doctype == 'ma') {
              $year_ins = $this->efiling_model->data_list_where('ia_initialization','year',$curYear);
              $ma_filing_no = $year_ins[0]->ma_filing;
              if ($ma_filing_no =='0') {
                  $maFilingNo = 1;
              }
              if ($ma_filing_no != 0) {
                  $maFilingNo = (int)$ma_filing_no + 1;
              }
          }
          return $maFilingNo;
      }
     
      
      //Other document upload
      function getDocFilingno($doctype,$curYear){
          $maFilingNo=0;
          if ($doctype == 'oth') {
              $year_ins = $this->efiling_model->data_list_where('ia_initialization','year',$curYear);
              $ma_filing_no = $year_ins[0]->doc_filing;
              if ($ma_filing_no =='0') {
                  $maFilingNo = 1;
              }
              if ($ma_filing_no != 0) {
                  $maFilingNo = (int)$ma_filing_no + 1;
              }
          }
          return $maFilingNo;
      }
      
      
      function doc_finalreceipt(){
          $userdata=$this->session->userdata('login_success');
          $user_id=$userdata[0]->id;
          if($user_id){
              $this->load->view('docfiling/doc_finalreceipt');
          }
      }
      
      
      
      function docfiledcase(){
          $userdata=$this->session->userdata('login_success');
          $user_id=$userdata[0]->id;
          if($user_id){
              $data['ma']=  $this->efiling_model->data_list_where('ma_detail','user_id',$user_id);
              $data['va']=  $this->efiling_model->data_list_where('vakalatnama_detail','user_id',$user_id);
              $this->load->view("admin/doc_case_filed_case",$data);
          }
      }
      
      function docdetail($docid){
          $userdata=$this->session->userdata('login_success');
          $user_id=$userdata[0]->id;
          if($user_id){
              $data['docs']=  $this->efiling_model->data_list_where('ma_detail','ma_id',$docid);
              $this->load->view("docfiling/docdetail",$data);
          }
      }
      
      
      
      function va_detail($docid){
          $userdata=$this->session->userdata('login_success');
          $user_id=$userdata[0]->id;
          if($user_id){
              $data['va']=  $this->efiling_model->data_list_where('vakalatnama_detail','id',$docid);
              $this->load->view("docfiling/va_detail",$data);
          }
      }
      
      
      
      function va_case_list(){
          $userdata=$this->session->userdata('login_success');
          $user_id=$userdata[0]->id;
          if($user_id){
              $data['va']=  $this->efiling_model->data_list_where('vakalatnama_detail','user_id',$user_id);
              $this->load->view("docfiling/va_case_list",$data);
          }
      }
      
      
      
 
    
}