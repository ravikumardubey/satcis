<?php defined('BASEPATH') OR exit('No direct script access allowed');

 class Scrutiny extends CI_Controller {
	    function __construct() {
	        
	        error_reporting(0);
	        parent::__construct();
	        $this->load->model('Admin_model','admin_model');
	        $this->load->model('Efiling_model','efiling_model');
	        error_reporting(0);
	        date_default_timezone_set('Asia/Calcutta');
	        $this->log_file_name='./logfile/log.txt';
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


	    function scrutiny_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
    	        $this->load->view('scrutiny/scrutiny_list');
	        }
	    }
	    
	    function scrutinyform($filingno){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['filing_no']=$filingno;
	            $this->load->view('scrutiny/scrutinyform',$data);
	        }
	    }
	    
	    
	    function returnscrutinyform($msg){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['msg']=$msg;
	            $this->load->view('scrutiny/scrutinyform',$data);
	        }
	    }
	    
	    
	    

	    
	    
	function scrutinyaction(){
	    $userdata=$this->session->userdata('login_success');
	    $userid=$userdata[0]->id;
	    $entry_date = htmlspecialchars(date("F j, Y g:i a"));
	    $searchby = $this->input->post('searchby');
	    $filing_no =$this->input->post('filing_no');

	    $notification_date =$this->input->post('notification_date'); 
	    list($day, $month, $year) = explode('-', $notification_date);
	    $notification_date1 = $year . '-' . $month . '-' . $day;
	    $aDate = explode("-", $notification_date);
	    $rgyear = htmlspecialchars($aDate[2]);
	    $comment = $this->input->post('comment');
	    $sessionUserType = $userid;
	    $schemas = 'mumbai';
	    $date_data_data = date('Y-m-d');
	    $day_dayss = date('l', strtotime($date_data_data));
	    if ($day_dayss == 'Saturday' || $day_dayss == 'Sunday') {
	        $listed_date = date('Y-m-d', strtotime("+4 days"));
	    } else {
	        $listed_date = date('Y-m-d', strtotime("+2 days"));
	    }
	    $delete= $this->efiling_model-> delete_event('objection_details', 'filing_no', $filing_no);
	    $st_scrutiny_data =$this->efiling_model->data_list_where('scrutiny','filing_no',$filing_no);
	    if (is_array($st_scrutiny_data) && empty($st_scrutiny_data)) {
	        $objection = 'Y';
	        $defects = 'Y';
	        $new_insert_status = '1';
	        $dataa = date('Y-m-d');
	        $data=array(
	            'filing_no'=>$filing_no,
	            'notification_date'=>$dataa,
	            'user_id'=>$userid,
	            'objection_status'=>$objection,
	            'defects'=>$defects,
	            'new_insert_status'=>$new_insert_status,
	        );
	        $st=$this->efiling_model->insert_query('scrutiny',$data); 
	    }
	    
	    
	    if ($searchby == '2') {
	        $data=array(
	            'listed_date'=>$listed_date,
	            'compliance_date'=>$notification_date1,
	            'user_id'=>$sessionUserType,
	        );
	        $st=$this->efiling_model->update_data('scrutiny', $data,'filing_no', $filing_no);   
	        $obj =$this->db->query("select case_type from sat_case_detail where filing_no = '$filing_no'");
	        $objval= $obj->result();
	        if(!empty($objval)){
	            $case_type=$objval[0]->case_type;
	            $case_typevvs=$objval[0]->case_type;
	        }
	        $casety =$this->db->query("select short_name from master_case_type where case_type_code = '$case_type'");
	        $casetyv= $casety->result();
	        if(!empty($casetyv)){
	            $short_name=$casetyv[0]->short_name;
	        }
	        $clen = strlen($case_type);
	        $clength = 3 - $clen;
	        for ($c = 0; $c < $clength; $c++)
	            $case_type = "0" . $case_type;
	            $ste =$this->db->query("select reg_no from case_type_reg where case_type = '$case_type' AND reg_year='$rgyear'");
	            $sthe= $ste->result();
	            $regn_no=0;
	            if(!empty($sthe)){
	                $regn_no=$sthe[0]->reg_no;
	            }
	            if ($regn_no == 0) {
	                $regn_no = '0000001';
	            }else {
	                $regn_no = (int)$regn_no + 1;
	                $len = strlen($regn_no);
	                $length = 7 - $len;
	                for ($i = 0; $i < $length; $i++)
	                    $regn_no = "0" . $regn_no;
	            }
	            $len = strlen($regn_no);
	            $length = 7 - $len;
	            for ($i = 0; $i < $length; $i++)
	                $regn_no = "0" . $regn_no;
	                $registration_number = htmlspecialchars("4" . $case_type . $regn_no . $rgyear);
	                $ms = htmlspecialchars($short_name . "/" . $regn_no . "/" . $rgyear);
	                $dc= explode("-", $notification_date);
	                $noti = htmlspecialchars($dc[2] . "-" . $dc[1] . "-" . $dc[0]);
	                $status = $this->input->post('status');
	                $comment =$this->input->post('comment');
	                $code1 =$this->input->post('id_check'); 
	                $sessionUserType =$sessionUserType;
	                $len = htmlspecialchars(count($_REQUEST['id_check']));
	                for ($i = 0; $i < $len; $i++) { 
	                    $case_type1 = $case_typevvs;
	                    $code11 = htmlspecialchars($code1[$i]);
	                    $status1 = htmlspecialchars($status[$i]);
	                    $comment1 = htmlspecialchars($comment[$i]);
	                    $code11 = htmlspecialchars(addslashes($code11));
	                    $status1 = htmlspecialchars(addslashes($status1));
	                    $comment1 = htmlspecialchars(addslashes($comment1));
	                    $aa = '0';
	                    $yes = 'Y';
	                    $data=array(
	                        'filing_no'=>$filing_no,
	                        'objection_code'=>$code11,
	                        'comments'=>$comment1,
	                        'userid'=>$userid,
	                        'entry_dt'=>$noti,
	                        'status'=>$status1,
	                        'case_type'=>$case_type1,
	                        'objection_sub_code'=>$aa,
	                        'completed_flag'=>$yes,
	                    );
	                    $st=$this->efiling_model->insert_query('objection_details',$data); 
	                }
	                $obj_st = 'N';
	                $def = 'N';
	                $data=array(
	                    'objection_status'=>$obj_st,
	                    'notification_date'=>$notification_date1,
	                    'defects'=>$def,
	                    'compliance_date'=>$notification_date1,
	                );
	                $st=$this->efiling_model->update_data('scrutiny', $data,'filing_no', $filing_no); 
	                $data=array(
	                    'reg_no'=>$regn_no,
	                );
	                
	                $where =array('reg_year'=>$rgyear,'case_type'=>$case_type);
	                $st=$this->efiling_model->update_data_where('case_type_reg', $where,$data);
	                $def = 'P';
	                $ipaddress = $_SERVER['REMOTE_ADDR'];
	                $user_id = $sessionUserType;
	                $update_type = 'scrutiny_action';
	                $def = 'P';
	                $update_type = 'scrutiny_action';
	                 $data=array(
	                    'regis_date'=>$notification_date1,
	                    'case_no'=>$registration_number,
	                    'status'=>$def,
	                    'user_id'=>$userid,
	                    'ip'=>$ipaddress,
	                    'update_type'=>$update_type,
	                );
	                $st=$this->efiling_model->update_data('sat_case_detail', $data,'filing_no', $filing_no); 
	                $message = 'Case Number Generated Successfully';
	                $msg = htmlspecialchars($filing_no);
	                $ms = htmlspecialchars($short_name . "-" . $regn_no . "-" . $rgyear);
	                $msg1 = htmlspecialchars($msg . '/' . $message . '/' . $ms);
	                $hash = base64_encode($msg1);
	                redirect(base_url('returnscrutinyform/'.$hash),'refresh');
	    }

	    if ($searchby == '3') {
	        $data=array(
	            'listed_date'=>$listed_date,
	            'compliance_date'=>$notification_date1,
	            'user_id'=>$sessionUserType,
	        );
	        $st=$this->efiling_model->update_data('scrutiny', $data,'filing_no', $filing_no);
	        
	        $case_type =$this->efiling_model->getColumn('sat_case_detail','case_type','filing_no',$filing_no);
	        $short_name =$this->efiling_model->getColumn('master_case_type','short_name','case_type_code',$case_type);
	        
	        $clen = strlen($case_type);
	        $clength = 3 - $clen;
	        for ($c = 0; $c < $clength; $c++)
	            $case_type = "0" . $case_type;
	            $regn_no='';
	            $obj =$this->db->query("select reg_no from case_type_reg where case_type = '$case_type' and reg_year='$rgyear'");
	            $objval= $obj->result();
	            if(!empty($objval)){
	                $regn_no=$objval[0]->reg_no;
	            }
	            if ($regn_no == 0) {
	                $regn_no = '0000001';
	            } else {
	                $regn_no = (int)$regn_no + 1;
	                $len = strlen($regn_no);
	                $length = 7 - $len;
	                for ($i = 0; $i < $length; $i++)
	                    $regn_no = "0" . $regn_no;
	            }
	            $len = strlen($regn_no);
	            $length = 7 - $len;
	            for ($i = 0; $i < $length; $i++){
	                $regn_no = "0" . $regn_no;
	                $registration_number = htmlspecialchars("4" . $case_type . $regn_no . $rgyear);
	                $ms = htmlspecialchars($short_name . "/" . $regn_no . "/" . $rgyear);
	                list($dd, $mm, $yy) = explode("/", $notification_date);
	                $noti = htmlspecialchars($yy . "-" . $mm . "-" . $dd);
	                $status = $this->input->post('status');
	                $comment =$this->input->post('comment');
	                $code1 =$this->input->post('id_check');
	                $len = htmlspecialchars(count($this->input->post('id_check')));
	                for ($i = 0; $i < $len; $i++) {
	                    $st = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
	                    $case_type1 =$this->efiling_model->getColumn('sat_case_detail','case_type','filing_no',$filing_no);
	                    if($case_type1==''){
	                        $case_type1='1';
	                    }
	                    $code11 = htmlspecialchars($code1[$i]);
	                    $status1 = htmlspecialchars($status[$i]);
	                    $comment1 = htmlspecialchars($comment[$i]);
	                    $code11 = htmlspecialchars(addslashes($code11));
	                    $status1 = htmlspecialchars(addslashes($status1));
	                    $comment1 = htmlspecialchars(addslashes($comment1));
	                    $aa = '0';
	                    $yes = 'Y';
	                    $data=array(
	                        'filing_no'=>$filing_no,
	                        'objection_code'=>$code11,
	                        'comments'=>$comment1,
	                        'userid'=>$userid,
	                        'entry_dt'=>$notification_date1,
	                        'status'=>$status1,
	                        'case_type'=>$case_type1,
	                        'objection_sub_code'=>$aa,
	                        'completed_flag'=>$yes,
	                    );
	                    $st=$this->efiling_model->insert_query('objection_details',$data);
	                }
	            }
	            $obj_st = 'N';
	            $def = 'N';
	            $data=array(
	                'objection_status'=>$obj_st,
	                'notification_date'=>$notification_date1,
	                'defects'=>$def,
	                'compliance_date'=>$notification_date1,
	            );
	            $st=$this->efiling_model->update_data('scrutiny', $data,'filing_no', $filing_no);
	            $data=array(
	                'reg_no'=>$regn_no,
	            );
	            $where =array('reg_year'=>$rgyear,'case_type'=>$case_type);
	            $st=$this->efiling_model->update_data_where('case_type_reg', $where,$data);
	            $message = 'Case Number Generated Successfully';
	            $msg = htmlspecialchars($filing_no);
	            $ms = htmlspecialchars($short_name . "/" . $regn_no . "/" . $rgyear);
	            $msg1 = htmlspecialchars($msg . '/' . $message . '/' . $ms);
	            $hash = base64_encode($msg1);
	            redirect(base_url('returnscrutinyform/'.$hash),'refresh');
	    }
	    
	    if ($searchby == 1) {
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	        $vals=array('8','6','5');
	        $tabs= $_REQUEST['tabval'];
	        $tabs = array_merge($tabs, $vals);
	        $tabs= json_encode($tabs);
	        $entry_date=date('Y-m-d');
	        
	        
	        $obj =$this->db->query("select filing_no from table_defecttabopen where filing_no = '$filing_no' ");
	        $objval= $obj->result();
	        if(!empty($objval)){
	            $this->efiling_model-> delete_event('table_defecttabopen', 'filing_no', $filing_no);
	        }

	        $data=array(
	            'filing_no'=>$filing_no,
	            'tabvals'=>$tabs,
	            'entry_date'=>$entry_date,
	            'user_id'=>$userid,
	            'ip'=>$ipaddress
	        );
	        $st=$this->efiling_model->insert_query('table_defecttabopen',$data);

	        
	        list($dd, $mm, $yy) = explode("/", $notification_date);
	        $noti = htmlspecialchars($yy . "-" . $mm . "-" . $dd);
	        $status =  $this->input->post('status');
	        $comment = $this->input->post('comment');
	        $code1 = $this->input->post('id_check');
	        $sessionUserType = $_SESSION['id'];
	        $len = count($this->input->post('id_check'));
	        for ($i = 0; $i < $len; $i++) {
	            $status1 = htmlspecialchars($status[$i]);
    	            if ($status1 != 'YES') {
    	                $code11 = htmlspecialchars($code1[$i]);
    	                $comment1 = htmlspecialchars($comment[$i]); 
    	                $code11 = htmlspecialchars(addslashes($code11));
    	                $status1 = htmlspecialchars(addslashes($status1));
    	                $comment1 = htmlspecialchars(addslashes($comment1));
    	                $aa = '0';
    	                $data=array(
    	                    'filing_no'=>$filing_no,
    	                    'objection_code'=>$code11,
    	                    'comments'=>$comment1,
    	                    'userid'=>$userid,
    	                    'entry_dt'=>$notification_date1,
    	                    'status'=>$status1,
    	                    'case_type'=>$case_type1,
    	                    'objection_sub_code'=>$aa,
    	                );
    	                $st=$this->efiling_model->insert_query('objection_details',$data);
    	            }
	            }
	        $aaq = 'Y';
	        $kks = 'Y';
	        $data=array(
	            'objection_status'=>$kks,
	            'notification_date'=>$notification_date1,
	            'defects'=>$aaq,
	            'user_id'=>$sessionUserType,
	        );

	        $st=$this->efiling_model->update_data('scrutiny', $data,'filing_no', $filing_no);
	        $messaged = 'Scrutiny Done Successfully with defect';
	        $msgd = htmlspecialchars($filing_no);
	        $searchby = $searchby;
	        $msg1 = htmlspecialchars($msgd . '/' . $messaged . '/' . $searchby);
	        $hashd = base64_encode($msg1);
	        redirect(base_url('returnscrutinyform/'.$hashd),'refresh');
	    }
     }
     
     
     
     function createdefect($filingno){
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id;
         if($user_id){
             $data['filingno']=$filingno;
             $this->load->view('scrutiny/createdefect',$data);
         }
     }
     
     function pendingdefect($filingno){
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id;
         if($user_id){
             $data['filingno']=$filingno;
             $this->load->view("scrutiny/pendingdefect",$data);
         }
     }
     
     function defectLetteractions(){
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id;
         if($user_id){   
             $data['data']= $this->input->post();
             $data['filingno']='';
             $this->load->view('scrutiny/defectLetter_actions');
         }
     }
     
     function defectletterupload(){
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id;
         if($user_id){
             $this->load->view('scrutiny/defectlatterupload');
         }
     }
     
     function ajaxuploadefect(){
         if($_REQUEST['action'] == 'upload_judgment'){
             $userdata=$this->session->userdata('login_success');
             $user=$userdata[0]->id;
             $enterdate = $_REQUEST['enterdate'];
             $enterdate=date('Y');
             $date=date('d-m-Y');
             $filing_no='';
             $salt='';
             $ip=$_SERVER['REMOTE_ADDR'];
             $year=$this->input->post('year',true);
             $this->form_validation->set_rules('year','Please enter year ','trim|required|min_length[1]|max_length[4]|numeric');
             if($this->form_validation->run() == FALSE){
                 echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
             }
             
             if($_REQUEST['typeval']=='1'){
                 $diaryYear=$this->input->post('year',true);
                 $this->form_validation->set_rules('year','Please enter year ','trim|required|min_length[1]|max_length[4]|numeric');
                 if($this->form_validation->run() == FALSE){
                     echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                 }
                 $diaryNo=$this->input->post('filing_no',true);
                 $this->form_validation->set_rules('filing_no','Please enter  filing no ','trim|required|min_length[1]|max_length[4]|numeric');
                 if($this->form_validation->run() == FALSE){
                     echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                 }
                 if ( $diaryYear != "" and $diaryNo != "") {
                     $detail = "Case No Details";
                     $diaryYear1 = $diaryYear;
                     $bench = 100;
                     $subBench = 1;
                     $subBenchCode = htmlspecialchars(str_pad($subBench, 2, '0', STR_PAD_LEFT));
                     $len = strlen($diaryNo);
                     $length = 6 - $len;
                     for ($i = 0; $i < $length; $i++) {
                         $diaryNo = "0" . $diaryNo;
                     }
                     $filing_no_old = $bench . $subBenchCode . $diaryNo . $diaryYear;
                     $filing_no=$filing_no_old;
                     $salt= $filing_no_old;
                 }
             }
             
             if($_REQUEST['typeval']=='2'){
                 $caseYear=$this->input->post('caseyear',true);
                 $this->form_validation->set_rules('caseyear','Please enter  case year','trim|required|min_length[1]|max_length[4]|numeric');
                 if($this->form_validation->run() == FALSE){
                     echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                 }
                 $caseNo= $this->input->post('caseno',true);
                 $this->form_validation->set_rules('caseno','Please enter  case year','trim|required|min_length[1]|max_length[4]|numeric');
                 if($this->form_validation->run() == FALSE){
                     echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                 }
                 
                 $caseNo= $this->input->post('casetype',true);
                 $this->form_validation->set_rules('caseno','Please enter  case type','trim|required|min_length[1]|max_length[5]');
                 if($this->form_validation->run() == FALSE){
                     echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                 }
                 
                 $caseType= $this->input->post('casetype',true); 
                 $case_type1 = $caseType;
                 $detail = "DFR No Detail";
                 $diaryYear1 = $caseYear;
                 $clen = strlen($case_type1);
                 $clength = 3 - $clen;
                 for ($c = 0; $c < $clength; $c++)
                     $case_type1 = "0" . $case_type1;
                     $clen = strlen($caseNo);
                     $clength = 7 - $clen;
                     for ($c = 0; $c < $clength; $c++)
                         $caseNo = "0" . $caseNo;
                         if ($caseNo == 000000)
                             $caseNo = '';
                             $chr = 4;// char for first hard code digit of filing no
                             $c_no = $chr . $case_type1 . $caseNo . $caseYear;
                             $query =$this->db->query("SELECT filing_no FROM sat_case_detail where case_no='$c_no' AND deleted='0'");
                             $rowval= $query->result();
                             $row_iad = $rowval[0]->filing_no;
                             $filing_no=$row_iad;
                             $salt= $row_iad;                             
             }
             
             $msg='';
             $query =$this->db->query("select filing_no from sat_case_detail where filing_no='$filing_no' AND deleted='0'");
             $rowval= $query->result();
             if (empty($rowval)) {
                 $msg= "Filing no not exit ! please varify in backlog";
                 echo json_encode(['data'=>'error','display'=>$msg,'error'=>1]);die;
             }
             if($filing_no==''){
                 $msg= "Case number not exit ! please varify in backlog";
                 echo json_encode(['data'=>'error','display'=>$msg,'error'=>1]);die;
             }
             $schemas = $_REQUEST['schemas'];
             $createddate=date('Y-m-d');
             $dval=explode('-', $_REQUEST['enterdate']);
             $enterdatev=$dval[2].'-'.$dval[1].'-'.$dval[0];
             $valid_extensions = array( 'pdf'); // valid extensions
             $path = './defectlatter/'.$schemas.'/'.$enterdate.'/'.$date.'/';
             if (!file_exists($path)) {
                 mkdir($path, 0777, true);
             }
             
             $query =$this->db->query("select filing_no from sat_uploadeddefectlatter where filing_no='$filing_no'");
             $rowval= $query->result();
             if (count($rowval)>=1) {
                 $msg= "Already defect latter created please check!";
                 echo json_encode(['data'=>'error','display'=>$msg,'error'=>1]);die;
             }
             
             if(!empty($_FILES['file'])){
                 $img = $_FILES['file']['name'];
                 $tmp = $_FILES['file']['tmp_name'];
                 
                 $array=explode('.',$_FILES['file']['name']);
                 if(substr_count($_FILES['file']['name'],'.')>1){
                     echo json_encode(['data'=>'','error' =>'File should have only single dot (.) extenction.']);die;
                 }
                 
                 
                 
                 $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                 $final_image = $filing_no.$img;
                 if(in_array($ext, $valid_extensions)) {
                     $save_path = $path.strtolower($final_image);
                     if(move_uploaded_file($tmp,$save_path)) {
                         $path11 = './defectlatter/'.$schemas.'/'.$enterdate.'/'.$date.'/';
                         $save_path = $path11.strtolower($final_image);
                         $array=array(
                             'filing_no'=>$filing_no,
                             'entry_date'=>$enterdatev,
                             'userid'=>$user,
                             'created_ip'=>$ip,
                             'file_name'=>$save_path,
                             'year'=>$year,
                             'full_filing_no'=>$salt,
                             'created_date'=>$createddate
                         );
                         $st=$this->efiling_model->insert_query('sat_uploadeddefectlatter',$array);
                         if($st){
                             $msg= 'sucessfully Order Upload.';
                             echo json_encode(['data'=>'success','display'=>$msg,'error'=>'0']);die;
                         }
                     }else{
                         $msg= 'Something Error. Please try again.';
                         echo json_encode(['data'=>'error','display'=>$msg,'error'=>1]);die;
                     }
                 }else{
                     $msg= 'invalid Document. Only upload PDF file.';
                     echo json_encode(['data'=>'error','display'=>$msg,'error'=>1]);die;
                 }
             }
             echo json_encode(['data'=>'error','display'=>$msg,'error'=>1]);die;
         }
     }
     
     
     
     function compliance(){
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id;
         if($user_id){
             $data['defect']= $this->efiling_model->defective_list();
             $this->load->view('scrutiny/compliance',$data);
         }
     }
     
     
     function dailyorderreport(){
         $userdata=$this->session->userdata('login_success');
         $user_id=$userdata[0]->id;
         if($user_id){
             $data['defect']= $this->efiling_model->defective_list();
             $this->load->view('scrutiny/dailyorderreport',$data);
         }
     }
}//**********END Main function ************/