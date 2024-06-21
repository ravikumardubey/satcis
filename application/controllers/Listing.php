<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Listing extends CI_Controller {
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
    }
    
    
    
    function freshlisting(){
       $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('salt');
        $user_id=$userdata[0]->id;
        if($user_id){
            //$data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filed_user_id',$user_id);
            $data['filedcase']= $this->db->get_where('scrutiny',array('objection_status !=' => NULL))->result_array();
            $this->load->view("listing/freshlisting",$data);
        }
    }
    
    
    function asigndate($token){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $subtoken=$this->session->userdata('submittoken');
        if($user_id){
            if($subtoken==$token){
                $this->form_validation->set_rules('filingno', 'Please Enter Filing no', 'trim|required|numeric|max_length[15]');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                }
                $valdate=dob_check($this->input->post('dateas'));
                if($valdate == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>$valdate,'error'=>'1']); die;
                }
                $this->form_validation->set_rules('remarks', 'Please Enter Remarks', 'trim|required|max_length[500]');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                }
                $dateas=$this->input->post('dateas');
                $da=explode('/', $dateas);
                $currectdate=$da[2].'-'.$da[1].'-'.$da[0];
                if($this->form_validation->run() == TRUE) {
                    $array=array('listing_date'=>$currectdate,'filing_no'=>$this->input->post('filingno'));
                    $checkdatass=$this->efiling_model->data_list_mulwhere('case_allocation',$array);

                    if(!empty($checkdatass) && is_array($checkdatass)){
                        $massage='For This Filing number already date Assign';
                        echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);die;
                    }

		 	$dateas=$this->input->post('dateas');
                 	$da=explode('/', $dateas);
                 	$currectdate=$da[2].'-'.$da[1].'-'.$da[0];


                    $data=[
                        'filing_no'=>$this->input->post('filingno'),
                        'listing_date'=>$currectdate,
                        'remarks'=>$this->input->post('remarks'),
                        'user_id'=>$user_id,
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'entry_date'=>date('Y-m-d'),
                    ];
                    $db=$this->db->insert('case_allocation',$data);
                    $massage='Assign date successfully Diary number is '.$this->input->post('filingno').'';
                    if($db) echo json_encode(['data'=>'success','massage'=>$massage,'error'=>'0']);
                    else 	echo json_encode(['data'=>'Qyery error, try again','error'=>'1']);
                }
            }else{
                $massage='Request not valid!';
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'1']);
            }
        }else{
            $massage='User not valid Please contact admin !';
            echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'1']);
        }
    }
    
    function caseallocation(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['filedcase']= $this->efiling_model->data_list('case_allocation');
            $this->load->view("listing/case_allocated",$data);
        }
    }
    
    
    function causelist(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['filedcase']= $this->efiling_model->data_list('case_allocation');
            $this->load->view("listing/cause_list",$data);
        }
    }
    
    
    function caselisting(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['filedcase']= $this->efiling_model->data_list('case_allocation');
            $this->load->view("listing/caselisting",$data);
        }
    }
    
    
    function caselistingsubmit(){
            $userdata=$this->session->userdata('login_success');
            $user_id=$userdata[0]->id;
            $subtoken=$this->session->userdata('submittoken');
            $token=$this->input->post('token');
            if($user_id){
                if($subtoken==$token){
                    $this->form_validation->set_rules('filingno', 'Please Enter Filing no', 'trim|required|numeric|max_length[15]');
                    if($this->form_validation->run() == FALSE){
                        echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                    } 
                    $valdate=dob_checssss($this->input->post('listingdate'));
                    if($valdate == FALSE){
                        echo json_encode(['data'=>'error','value'=>'','massage'=>$valdate,'error'=>'1']); die;
                    }
                    $this->form_validation->set_rules('remark', 'Please Enter Remarks', 'trim|required|max_length[500]');
                    if($this->form_validation->run() == FALSE){
                        echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                    }
                    $this->form_validation->set_rules('time', 'Please Enter time', 'trim|required|max_length[2]|numeric');
                    if($this->form_validation->run() == FALSE){
                        echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                    }
                    $this->form_validation->set_rules('courtmaster', 'Select Court master', 'trim|required|max_length[500]');
                    if($this->form_validation->run() == FALSE){
                        echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                    }
                    
                    $this->form_validation->set_rules('judge1', 'Select judge 1', 'trim|required|max_length[4]|numeric');
                    if($this->form_validation->run() == FALSE){
                        echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                    }
                    
                    $this->form_validation->set_rules('judge2', 'Select judge 2', 'trim|required|max_length[4]|numeric');
                    if($this->form_validation->run() == FALSE){
                        echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                    }
                    
                    $this->form_validation->set_rules('purpose', 'Select purpose', 'trim|required|max_length[4]|numeric');
                    if($this->form_validation->run() == FALSE){
                        echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                    }
                    
                    $this->form_validation->set_rules('benchminut', 'Please Enter  bench minut', 'trim|required|max_length[2]|numeric');
                    if($this->form_validation->run() == FALSE){
                        echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                    }
                    
                    $this->form_validation->set_rules('benchtime', 'Please Enter   bench time', 'trim|required|max_length[2]');
                    if($this->form_validation->run() == FALSE){
                        echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                    }

                    $dateas=$this->input->post('listingdate');
                    $da=explode('-', $dateas);
                    $currectdate=$da[2].'-'.$da[1].'-'.$da[0];
                    $filingno=$this->input->post('filingno');
                    $time=$this->input->post('time');
                    $courtmaster=$this->input->post('courtmaster');
                    $purpose=$this->input->post('purpose');
                    $judge1=$this->input->post('judge1');
                    $judge2=$this->input->post('judge2');
                    $benchminut=$this->input->post('benchminut');
                    $benchtime=$this->input->post('benchtime');
                    if($this->form_validation->run() == TRUE) {
                        $array=array('listing_date'=>$currectdate,'filing_no'=>$this->input->post('filingno'));
                        $checkdatass=$this->efiling_model->data_list_mulwhere('case_allocation',$array);
                        $db='';
                        if(!empty($checkdatass) && is_array($checkdatass)){
                            $data=[
                                'filing_no'=>$this->input->post('filingno'),
                                'listing_date'=>$currectdate,
                                'remarks'=>$this->input->post('remark'),
                                'time'=>$time.'.'.$benchminut,
                                'ampm'=>$benchtime,
                                'judge1'=>$judge1,
                                'judge2'=>$judge2,
                                'purpose'=>$purpose,
                                'user_id'=>$user_id,
                                'ismaster'=>$courtmaster,
                                'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                'entry_date'=>date('Y-m-d'),
                            ];
                            $where=array('filing_no'=>$filingno);
                            $db= $this->efiling_model->update_data_where('case_allocation',$where,$data);
                        }else{
                            $data=[
                                'filing_no'=>$this->input->post('filingno'),
                                'listing_date'=>$currectdate,
                                'remarks'=>$this->input->post('remark'),
                                'time'=>$time.'.'.$benchminut,
                                'ampm'=>$benchtime,
                                'judge1'=>$judge1,
                                'judge2'=>$judge2,
                                'purpose'=>$purpose,
                                'user_id'=>$user_id,
                                'ismaster'=>$courtmaster,
                                'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                'entry_date'=>date('Y-m-d'),
                            ];
                            $db=$this->db->insert('case_allocation',$data);
                        }
                        $massage='Assign date successfully Diary number is '.$this->input->post('filingno').'';
                        if($db) echo json_encode(['data'=>'success','massage'=>$massage,'error'=>'0']);
                        else 	echo json_encode(['data'=>'Qyery error, try again','error'=>'1']);
                    }
                }else{
                    $massage='Request not valid!';
                    echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'1']);
                }
            }else{
                $massage='User not valid Please contact admin !';
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'1']);
            }
    }
    
    
    function draftcauselist($date){
        $vals=explode('-', $date);
        $listingdate=$vals['2'].'-'.$vals['1'].'-'.$vals['0'];
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['caseallo']= $this->efiling_model->data_list('case_allocation','listing_date',$listingdate);
            $this->load->view("listing/deaftcauselist",$data);
        }
    }
    
    
    
    function causelistupload(){
        $data = '';
        if ($this->input->post('submit1')) :
        $this->form_validation->set_rules('from_date', 'from_date', 'required|date|max_length[10]');
        if ($this->form_validation->run() === false) :
        $this->session->set_userdata('error', strip_tags(validation_errors()));
        redirect('causelistupload');
        endif;
        
        $data = [
            'benchNatures' => $this->db->get_where('bench_nature', [
                'display' => true
            ])->result_array(),
            'judges' => $this->db->get_where('master_judge', [
                'display' => true
            ])->result_array()
        ];
        $params = [
            'from_list_date =' => date('Y-m-d', strtotime($this->input->Post('from_date'))),
        ];
        $data['benches'] = $this->db->get_where('bench', $params)->result_array();
        endif;
        $this->load->view("listing/causelist_upload",$data);
 
    }
    
    function uploadcauselistdoc($benchid){
	if(!is_numeric($benchid)){
		echo json_encode(['data'=>'Permission deny!','error'=>'1']);die;
	}
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($user_id){ 
            $data['benchid']= $this->efiling_model->data_list_where('bench','id',$benchid);
            $this->load->view("listing/uploadcauselistdocs",$data);
        }
    }
    
    function ajaxuploadcauselist(){
        if($_REQUEST['action'] == 'uploadcauselist'){
            $userdata=$this->session->userdata('login_success');
            $user=$userdata[0]->id;
            $enterdate =$this->input->post('enterdate',true);
            $enterdate=date('Y');
            $date=date('d-m-Y');
            $ip=$_SERVER['REMOTE_ADDR'];
            $courtno=$this->input->post('court_no',true);
            $this->form_validation->set_rules('court_no','Please enter court number ','trim|required|min_length[1]|max_length[4]|numeric');
            if($this->form_validation->run() == FALSE){
                echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
            }
            $benchid=$this->input->post('benchid',true);
            $this->form_validation->set_rules('benchid','Please enter bench id ','trim|required|min_length[1]|max_length[6]|numeric');
            if($this->form_validation->run() == FALSE){
                echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
            }
            
            $msg='';
            $schemas = $this->input->post('schemas',true);
            $createddate=date('Y-m-d');
            $dval=explode('-', $_REQUEST['enterdate']);
            $enterdatev=$dval[2].'-'.$dval[1].'-'.$dval[0];
            $valid_extensions = array( 'pdf'); // valid extensions
            $path = '../causelist/'.$schemas.'/'.$enterdate.'/'.$date.'/';
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            
            $query =$this->db->query("select court_no from sat_causelist where court_no='$courtno' AND  created_date='$enterdatev'");
            $rowval= $query->result();
            if (count($rowval)>=1) {
                $msg= "Already causelist uploaded!";
                echo json_encode(['data'=>'error','display'=>$msg,'error'=>1]);die;
            }
            
            if(!empty($_FILES['file'])){
                $img = $_FILES['file']['name'];

                   $array=explode('.',$_FILES['file']['name']);
	            if(substr_count($_FILES['file']['name'],'.')>1){
	                echo json_encode(['data'=>'','error' =>'File should have only single dot (.) extenction.']);die;
	            }


                $tmp = $_FILES['file']['tmp_name'];
                $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                $final_image = $courtno.$img;
                if(in_array($ext, $valid_extensions)) {
                    $save_path = $path.strtolower($final_image);
                    if(move_uploaded_file($tmp,$save_path)) {
                        $path11 = '../causelist/'.$schemas.'/'.$enterdate.'/'.$date.'/';
                        $save_path = $path11.strtolower($final_image);
                        $array=array(
                            'bench_id'=>$benchid,
                            'entry_date'=>$enterdatev,
                            'url'=>$save_path,
                            'filename'=>$save_path,
                            'court_no'=>$courtno,
                            'created_date'=>$createddate,
                            'update_date'=>$createddate,
                            'upload_by'=>$user,
                            'deleted'=>'0',
                        );
                        $st=$this->efiling_model->insert_query('sat_causelist',$array);
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
    
    
    
    function removecauselist(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($user_id){
            $benchID=$this->input->post('id');
            $this->form_validation->set_rules('id','Please valid id','trim|required|min_length[1]|max_length[4]|numeric');
            if($this->form_validation->run() == FALSE){
                echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
            }
            $this -> db -> where('id', $benchID);
            $this -> db -> delete('sat_causelist');
        }
    }

   
}

function dob_check($str){
    if (!DateTime::createFromFormat('d/m/Y', $str)) { //yes it's YYYY-MM-DD
        $this->form_validation->set_message('dob_check', 'The {field} has not a valid date format');
        return FALSE;
    } else {
        return TRUE;
    }
}


function dob_checssss($str){
    if (!DateTime::createFromFormat('d-m-Y', $str)) { //yes it's YYYY-MM-DD
        $this->form_validation->set_message('dob_check', 'The {field} has not a valid date format');
        return FALSE;
    } else {
        return TRUE;
    }
}






