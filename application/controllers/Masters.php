<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Masters extends CI_Controller {
	    function __construct() {error_reporting(0);
	        parent::__construct();
	        $this->load->model('Admin_model','admin_model');
	        $this->load->model('Efiling_model','efiling_model');
	        error_reporting(0);
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
	    }


	    function checkslists(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['checklist']=$this->efiling_model->data_list_where('master_checklist','status','1');
	            $this->load->view("master/checks_lists",$data);
	        }
	    }

	    
	    
	    function master_dash(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
    	        $data['adv_varified']= $this->efiling_model->data_list_where('master_advocate','status','1');
    	        $data['org_varified']= $this->efiling_model->data_list_where('master_org','status','1');
    	        $data['arg_nonvarified']= $this->efiling_model->data_list_where('master_org','status','0');
    	        $data['euser_varified']= $this->efiling_model->data_list_where('efiling_users','verified','1');
    	        $data['euser_nonvarified']= $this->efiling_model->data_list_where('efiling_users','verified','0');
    	        $this->load->view("master/master_dash",$data);
	        }
	    }
	    
	    
	    function advocate_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
    	        $data['adv']= $this->efiling_model->data_list('master_advocate');
    	        $this->load->view("master/advocate_list",$data);
	        }
	    }
	    
	    function euser_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['users']= $this->efiling_model->data_list('efiling_users');
	            $this->load->view("master/euser_list",$data);
	        }
	    }
	    
	    
	    function org_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
    	        $data['org']= $this->efiling_model->data_list('master_org');
    	        $this->load->view("master/org_list",$data);
	        }
	    }
	    
	    
	    function addchecklist(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $subtoken=$this->session->userdata('submittoken');
	        $token=$_REQUEST['token'];
	        $this->form_validation->set_rules('status', 'Choose status', 'trim|required|numeric|max_length[2]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        } 
	        $this->form_validation->set_rules('shortname', 'Please Enter short name', 'trim|required|max_length[500]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        } 
	        $this->form_validation->set_rules('c_name', 'Please Enter check list name', 'trim|required|max_length[500]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        } 
	        $this->form_validation->set_rules('action_one', 'Please Enter action one', 'trim|required|alpha|max_length[5]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        } 
	        $this->form_validation->set_rules('action_two', 'Please Enter action two', 'trim|required|alpha|max_length[5]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        } 
	        $this->form_validation->set_rules('action_three', 'Please Enter action three', 'trim|required|alpha|max_length[5]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        } 
	        if($this->form_validation->run() == TRUE) {
	            if($subtoken==$token){
    	            $postdata=array(
    	                'status'=>$_REQUEST['status'],
    	                'shortname'=>$_REQUEST['shortname'],
    	                'c_name'=>$_REQUEST['c_name'],
    	                'entry_time'=>date('Y-m-d'),
    	                'userid'=>$user_id,
    	                'action_one'=>$_REQUEST['action_one'],
    	                'action_two'=>$_REQUEST['action_two'],
    	                'action_three'=>$_REQUEST['action_three'],
    	            );
    	            $st=$this->efiling_model->insert_query('master_checklist',$postdata);
    	            if($st){
    	                echo json_encode(['data'=>'success','value'=>'1','massage'=>'Check List added..','error'=>'0']); die;
    	            }
	            }
	        }else{
	            echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	        }
	    }

	    
	    function deletecheck(){
	        $id=$this->input->post('id');
	        $this->form_validation->set_rules('id','Please enter valid id ','trim|required|min_length[1]|max_length[6]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	       $token=$_REQUEST['token'];
	       $subtoken=$this->session->userdata('submittoken');
	       if($id!='' && ($subtoken==$token)){
	           $st=$this->efiling_model->delete_event('master_checklist', 'id', $id);
	           if($st){
	               echo json_encode(['data'=>'success','value'=>'1','massage'=>'Check List Deleted..','error'=>'0']); die;
	           }
	       }else{
	           echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	       }
	    }

	    function doc_master(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['mdocs']=$this->efiling_model->data_list_where('master_document_efile','status','1');
	            $this->load->view("master/master_lists",$data);
	        }
	    }
	    
	    
	    
	    function  adddocmaster(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $subtoken=$this->session->userdata('submittoken');
	        $token=$_REQUEST['token'];
	        $this->form_validation->set_rules('status', 'Choose status', 'trim|required|numeric|max_length[2]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('d_name', 'Please Enter document name', 'trim|required|max_length[500]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('d_type', 'Please Enter document type Name', 'trim|required|max_length[500]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        if($this->form_validation->run() == TRUE) {
	            if($subtoken==$token){
	                $postdata=array(
	                    'status'=>$this->input->post('status'),
	                    'docname'=>$this->input->post('d_name'),
	                    'doctype'=>$this->input->post('d_type'),
	                    'entry_date'=>date('Y-m-d'),
	                    'userid'=>$user_id,
	                );
	                $st=$this->efiling_model->insert_query('master_document_efile',$postdata);
	                if($st){
	                    echo json_encode(['data'=>'success','value'=>'1','massage'=>'Check List added..','error'=>'0']); die;
	                }
	            }
	        }else{
	            echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	        }
	    }
	    
	    
	    
	    
	    function deletedocmaster(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
    	        $id=$this->input->post('id');
    	        $this->form_validation->set_rules('id','Please enter valid id ','trim|required|min_length[1]|max_length[9]|numeric');
    	        if($this->form_validation->run() == FALSE){
    	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
    	        }
    	        $token=$_REQUEST['token'];
    	        $subtoken=$this->session->userdata('submittoken');
    	        if($id!='' && ($subtoken==$token)){
    	            $st=$this->efiling_model->delete_event('master_document_efile', 'id', $id);
    	            if($st){
    	                echo json_encode(['data'=>'success','value'=>'1','massage'=>'Check List Deleted..','error'=>'0']); die;
    	            }
    	        }else{
    	            echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
    	        }
	        }
	    }
	    
	    
	    function regu_master(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['checklist']=$this->efiling_model->data_list_where('master_regulator','status','1');
	            $this->load->view("master/regu_master",$data);
	        }
	    }
	    
	    
	    function addregulator(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $subtoken=$this->session->userdata('submittoken');
	        $token=$_REQUEST['token'];
	        $this->form_validation->set_rules('status', 'Choose status', 'trim|required|numeric|max_length[2]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('op_name', 'Please Enter order passing authority name', 'trim|required|max_length[500]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('rtypename', 'Please Enter document type Name', 'trim|required|max_length[500]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('rtype', 'Choose type', 'trim|required|numeric|max_length[1]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }

	        if($this->form_validation->run() == TRUE) {
	            if($subtoken==$token){
	                if($this->input->post('action')=='add'){
    	                $postdata=array(
    	                    'status'=>$this->input->post('status'),
    	                    'order_passing_authority'=>$this->input->post('op_name'),
    	                    'regulator_type'=>$this->input->post('rtypename'),
    	                    'id'=>$this->input->post('rtype'),
    	                    'entry_date'=>date('Y-m-d'),
    	                    'fee'=>$this->input->post('rfee'),
    	                    'user_id'=>$user_id,
    	                );
    	                $st=$this->efiling_model->insert_query('master_regulator',$postdata);
    	                if($st){
    	                    echo json_encode(['data'=>'success','value'=>'1','massage'=>'Regulator  added..','error'=>'0']); die;
    	                }
	                }
	                if($this->input->post('action')=='edit'){
	                    $idval=$this->input->post('idval');
	                    $postdata=array(
	                        'status'=>$this->input->post('status'),
	                        'order_passing_authority'=>$this->input->post('op_name'),
	                        'regulator_type'=>$this->input->post('rtypename'),
	                        'id'=>$this->input->post('rtype'),
	                        'entry_date'=>date('Y-m-d'),
	                        'fee'=>$this->input->post('rfee'),
	                        'user_id'=>$user_id,
	                    );
	                    $where=array('order_pass_auth_id'=>$idval);
	                    $st = $this->efiling_model->update_data_where('master_regulator',$where,$postdata);
	                    if($st){
	                        $massage="Successfully Submit.";
	                        echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
	                    }
	                }
	            }
	        }else{
	            echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	        }
	 }
	 
	 function deleteregulator(){
	     $id=$this->input->post('id');
	     $this->form_validation->set_rules('id','Please enter valid id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $token=$_REQUEST['token'];
	     $subtoken=$this->session->userdata('submittoken');
	     if($id!='' && ($subtoken==$token)){
	         $st=$this->efiling_model->delete_event('master_regulator', 'order_pass_auth_id', $id);
	         if($st){
	             echo json_encode(['data'=>'success','value'=>'1','massage'=>'Regulator Deleted..','error'=>'0']); die;
	         }
	     }else{
	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	     }
	 }
	 
	 function editregulator(){
	     $id=$this->input->post('id');
	     $this->form_validation->set_rules('id','Please enter valid id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $token=$_REQUEST['token'];
	     $subtoken=$this->session->userdata('submittoken');
	     if($id!='' && ($subtoken==$token)){
	        $da=$this->efiling_model->data_list_where('master_regulator','order_pass_auth_id',$id);
	        foreach($da as $val){
	            $data['idval']=$val->order_pass_auth_id;
	            $data['regulator_type']=$val->regulator_type;
	            $data['recordid']=$val->id;
	            $data['order_passing_authority']=$val->order_passing_authority;
	            $data['status']=$val->status;
	            $data['rfee']=$val->fee;
	        }
	        echo json_encode($data); die;
	     }
	     echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	 }
	 
	 
	 
	 
	 function ma_master(){
	     $userdata=$this->session->userdata('login_success');
	     $user_id=$userdata[0]->id;
	     if($user_id){
	         $data['ma']=$this->efiling_model->data_list_where('moster_ma_nature','status','1');
	         $this->load->view("master/ma_master",$data);
	     }
	 }
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 
	 function addmanature(){
	     $userdata=$this->session->userdata('login_success');
	     $user_id=$userdata[0]->id;
	     $subtoken=$this->session->userdata('submittoken');
	     $token=$_REQUEST['token'];
	     $this->form_validation->set_rules('status', 'Choose status', 'trim|required|numeric|max_length[2]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $this->form_validation->set_rules('ma_name', 'Please Enter ma name', 'trim|required|max_length[500]');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $this->form_validation->set_rules('ma_fee', 'Please Enter ma fee', 'trim|required|max_length[5]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     if($user_id){
    	     if($this->form_validation->run() == TRUE) {
    	         if($subtoken==$token){
    	             if($this->input->post('action')=='add'){
    	                 $postdata=array(
    	                     'status'=>$this->input->post('status'),
    	                     'nature_name'=>$this->input->post('ma_name'),
    	                     'fee'=>$this->input->post('ma_fee'),
    	                     'entry_date'=>date('Y-m-d'),
    	                     'user_id'=>$user_id,
    	                 );
    	                 $st=$this->efiling_model->insert_query('moster_ma_nature',$postdata);
    	                 if($st){
    	                     echo json_encode(['data'=>'success','value'=>'1','massage'=>'Regulator  added..','error'=>'0']); die;
    	                 }
    	             }
    	             if($this->input->post('action')=='edit'){
    	                 $idval=$this->input->post('idval');
    	                 $postdata=array(
    	                     'status'=>$this->input->post('status'),
    	                     'nature_name'=>$this->input->post('ma_name'),
    	                     'fee'=>$this->input->post('ma_fee'),
    	                     'entry_date'=>date('Y-m-d'),
    	                     'user_id'=>$user_id,
    	                 );
    	                 $where=array('id'=>$idval);
    	                 $st = $this->efiling_model->update_data_where('moster_ma_nature',$where,$postdata);
    	                 if($st){
    	                     $massage="Successfully Submit.";
    	                     echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
    	                 }
    	             }
    	         }
    	     }else{
    	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
    	     }
	     }else{
	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	     }
	 }
	 
	 
	 function deletema(){
	     $this->form_validation->set_rules('id','Please enter valid id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $id=$this->input->post('id');
	     $token=$_REQUEST['token'];
	     $subtoken=$this->session->userdata('submittoken');
	     if($id!='' && ($subtoken==$token)){
	         $st=$this->efiling_model->delete_event('moster_ma_nature', 'id', $id);
	         if($st){
	             echo json_encode(['data'=>'success','value'=>'1','massage'=>'Regulator Deleted..','error'=>'0']); die;
	         }
	     }else{
	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	     }
	 }
	 
	 
	 function editma(){
	     $this->form_validation->set_rules('id','Please enter valid id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $id=$this->input->post('id');
	     $token=$_REQUEST['token'];
	     $subtoken=$this->session->userdata('submittoken');
	     if($id!='' && ($subtoken==$token)){
	         $da=$this->efiling_model->data_list_where('moster_ma_nature','id',$id);
	         if(!empty($da)){
    	         foreach($da as $val){
    	             $data['idval']=$val->id;
    	             $data['naturename']=$val->nature_name;
    	             $data['fee']=$val->fee;
    	             $data['status']=$val->status;
    	         }
	         echo json_encode($data); die;
	         }
	     }
	     echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	 }
	 
	 
	 function act_master(){
	     $userdata=$this->session->userdata('login_success');
	     $user_id=$userdata[0]->id;
	     if($user_id){
	         $data['act']=$this->efiling_model->data_list_where('master_act','status','1');
	         $this->load->view("master/act_master",$data);
	     }
	 }
	 
	 
	 function actdeletema(){
	     $id=$this->input->post('id',true); 
	     $this->form_validation->set_rules('id','Please enter valid id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $token=$this->input->post('token'); 
	     $subtoken=$this->session->userdata('submittoken');
	     if($id!='' && ($subtoken==$token)){
	         $st=$this->efiling_model->delete_event('master_act', 'id', $id);
	         if($st){
	             echo json_encode(['data'=>'success','value'=>'1','massage'=>'Regulator Deleted..','error'=>'0']); die;
	         }
	     }else{
	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	     }
	 }
	 
	 
	 function addact(){
	     $userdata=$this->session->userdata('login_success');
	     $user_id=$userdata[0]->id;
	     $subtoken=$this->session->userdata('submittoken');
	     $token=$_REQUEST['token'];
	     $this->form_validation->set_rules('status', 'Choose status', 'trim|required|numeric|max_length[2]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $this->form_validation->set_rules('act_name', 'Please Enter ma name', 'trim|required|max_length[500]');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $this->form_validation->set_rules('act_fee', 'Please Enter ma fee', 'trim|required|max_length[5]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     
	     if($this->form_validation->run() == TRUE) {
	         if($subtoken==$token){
	             if($this->input->post('action')=='add'){
	                 $postdata=array(
	                     'status'=>$this->input->post('status',true),
	                     'act_full_name'=>$this->input->post('act_name',true),
	                     'fee'=>$this->input->post('act_fee',true),
	                     'entry_date'=>date('Y-m-d'),
	                     'user_id'=>$user_id,
	                 );
	                 $st=$this->efiling_model->insert_query('master_act',$postdata);
	                 if($st){
	                     echo json_encode(['data'=>'success','value'=>'1','massage'=>'Regulator  added..','error'=>'0']); die;
	                 }
	             }
	             if($this->input->post('action')=='edit'){
	                 $idval=$this->input->post('idval',true);
	                 $postdata=array(
	                     'status'=>$this->input->post('status',true),
	                     'act_full_name'=>$this->input->post('act_name',true),
	                     'fee'=>$this->input->post('act_fee',true),
	                     'entry_date'=>date('Y-m-d'),
	                     'user_id'=>$user_id,
	                 );
	                 $where=array('id'=>$idval);
	                 $st = $this->efiling_model->update_data_where('master_act',$where,$postdata);
	                 if($st){
	                     $massage="Successfully Submit.";
	                     echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
	                 }
	             }
	         }
	     }else{
	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	     }
	 }
	 
	 
	 function actedit(){
	     $id=$this->input->post('id',true);
	     $this->form_validation->set_rules('id','Please enter valid id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $id=$this->input->post('id',true);
	     $token=$_REQUEST['token'];
	     $subtoken=$this->session->userdata('submittoken');
	     if($id!='' && ($subtoken==$token)){
	         $da=$this->efiling_model->data_list_where('master_act','id',$id);
	         foreach($da as $val){
	             $data['idval']=$val->id;
	             $data['act_full_name']=$val->act_full_name;
	             $data['act_fee']=$val->fee;
	             $data['status']=$val->status;
	         }
	         echo json_encode($data); die;
	     }
	     echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	 }
	 
	 
	 function deleteadv(){
	     $id=$this->input->post('adv_id');
	     $this->form_validation->set_rules('adv_id','Please enter valid advocate id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $id=$this->input->post('adv_id',true);
	     $token=$this->input->post('token');
	     $subtoken=$this->session->userdata('submittoken');
	     if($id!='' && ($subtoken==$token)){
	         $st=$this->efiling_model->delete_event('master_advocate', 'id', $id);
	         if($st){
	             echo json_encode(['data'=>'success','value'=>'1','massage'=>'Advocate Deleted..','error'=>'0']); die;
	         }
	     }else{
	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	     }
	 }
	 
	 
	 function deleteorg(){
	     $id=$this->input->post('org_id',true);
	     $this->form_validation->set_rules('org_id','Please enter valid org id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $token=$this->input->post('token');
	     $subtoken=$this->session->userdata('submittoken');
	     if($id!='' && ($subtoken==$token)){
	         $st=$this->efiling_model->delete_event('master_org', 'id', $id);
	         if($st){
	             echo json_encode(['data'=>'success','value'=>'1','massage'=>'Record  Deleted..','error'=>'0']); die;
	         }
	     }else{
	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	     }
	 }
	 
	 
	 function judgelist(){
	     $userdata=$this->session->userdata('login_success');
	     $user_id=$userdata[0]->id;
	     if($user_id){
	         $data['judge']=$this->efiling_model->data_list('master_judge');
	         $this->load->view("master/judgelist",$data);
	     }
	 }
	 
	 
	 function addjudge($csrf=null){
	     $userdata=$this->session->userdata('login_success');
	     $user_id=$userdata[0]->id;
	     $token=hash('sha512',trim($this->input->post('token',true)).'addj');
	     
	     $this->form_validation->set_rules('judgename','Please enter judge name','trim|required|min_length[1]|max_length[100]|htmlspecialchars');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	     }
	     
	     $this->form_validation->set_rules('status','Please select status','trim|required|min_length[1]|max_length[10]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     
	     $this->form_validation->set_rules('prefix','Enter prefix','trim|required|min_length[1]|max_length[15]|htmlspecialchars');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     
	     $this->form_validation->set_rules('display','Select display.','trim|required|min_length[1]|max_length[3]');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     
	     $this->form_validation->set_rules('designation','Select Designation.','trim|required|min_length[1]|max_length[50]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $status=false;
	     if($this->input->post('status')=='1'){
	         $status=TRUE;
	     }
	     $data= $this->efiling_model->data_list('master_judge');
	     $count=count($data)+1;
	     if($csrf==$token){
	         if($this->input->post('action')=='add'){
    	         $data=array(
    	             'judge_name'=>$this->input->post('judgename'),
    	             'retired'=>$status,
    	             'hon_text'=>$this->input->post('prefix'),
    	             'display'=>$status,
    	             'added_by'=>$user_id,
    	             'entry_date'=>date('Y-m-d'),
    	             'judge_code'=>$count,
    	             'ipaddress'=>$this->input->ip_address(),
    	             'judge_desg_code'=>$this->input->post('designation'),
    	         );
    	         $st=$this->efiling_model->insert_query('master_judge',$data);
    	         if($st){
    	             echo json_encode(['data'=>'success','value'=>'1','massage'=>'Judje added..','error'=>'0']); die;
    	         }
	         }else{
	             $id=$this->input->post('jujid');
	             $this->form_validation->set_rules('jujid','enter judje id.','trim|required|min_length[1]|max_length[4]|numeric');
	             if($this->form_validation->run() == FALSE){
	                 echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	             }
	             $data=array(
	                 'judge_name'=>$this->input->post('judgename'),
	                 'retired'=>$status,
	                 'hon_text'=>$this->input->post('prefix'),
	                 'display'=>$status,
	                 'judge_code'=>0,
	                 'update_by'=>$user_id,
	                 'update_date'=>date('Y-m-d'),
	                 'ipaddress'=>$this->input->ip_address(),
	                 'judge_desg_code'=>$this->input->post('designation'),
	             );
	             $where=array('id'=>$id);
	             $st = $this->efiling_model->update_data_where('master_judge',$where,$data);
	             if($st){
	                 $massage="Successfully Updated.";
	                 echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
	             }
	         }
	     }else{
	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	     }
	 }
	 
	 
	 function getjudje($csrf=null){
	     $id=$this->input->post('jujid',true);
	     $this->form_validation->set_rules('jujid','Please enter valid  id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     
	     $token=hash('sha512',trim($this->input->post('token',true)).'getdata'); 
	     if($csrf==$token){
	         $st=$this->efiling_model->data_list_where('master_judge','id',$id);
	         if(!empty($st) && is_array($st)){
	             echo json_encode($st); die;
	         }
	     }else{
	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	     }
	 }
	 
	 
	 
	 function deletejudge($csrf=null){
	     $id=$this->input->post('org_id',true);
	     $this->form_validation->set_rules('org_id','Please enter valid org id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $token=hash('sha512',trim($this->input->post('token',true)).'deletej');   
	     if($csrf==$token){
	         $st=$this->efiling_model->delete_event('master_judge', 'id', $id);
	         if($st){
	             echo json_encode(['data'=>'success','value'=>'1','massage'=>'Record  Deleted..','error'=>'0']); die;
	         }
	     }else{
	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	     }
	 }
	 
	 
	 function judgeview(){
	     $id=$this->input->post('id');
	     $this->form_validation->set_rules('id','Please enter valid id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $token=$this->input->post('token');
	     $subtoken=$this->session->userdata('submittoken'); 
	     if($id!='' && ($subtoken==$token)){
	         $st=$this->efiling_model->data_list_where('master_judge','id',$id);
	         if(!empty($st) && is_array($st)){
	             echo json_encode($st); die;
	         }
	     }
	 }
	 
	 
	 
	 function usrole_master(){
	     $userdata=$this->session->userdata('login_success');
	     $user_id=$userdata[0]->id;
	     if($user_id){
	         $data['checklist']=$this->efiling_model->data_list_where('efiling_users','verified','1');
	         $this->load->view("master/usrole_master",$data);
	     }
	 }
	 
	 function editusrole(){
	     $id=$this->input->post('id',true);
	     $this->form_validation->set_rules('id','Please enter valid id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $token=$_REQUEST['token'];
	     $subtoken=$this->session->userdata('submittoken');
	     if($id!='' && ($subtoken==$token)){
	         $da=$this->efiling_model->data_list_where('efiling_users','id',$id);
	         foreach($da as $val){
	             $data['role']=$val->role;
	             $data['status']=$val->status;
	             $data['name']=$val->fname;
	             $data['idval']=$val->id;
	             
	         }
	         echo json_encode($data); die;
	     }
	     echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	 }
	 
	 function updaterole(){
	     $userdata=$this->session->userdata('login_success');
	     $user_id=$userdata[0]->id;
	     $subtoken=$this->session->userdata('submittoken');
	     $token=$_REQUEST['token'];
	     $this->form_validation->set_rules('status', 'Choose status', 'trim|required|numeric|max_length[2]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $this->form_validation->set_rules('rtype', 'Choose type', 'trim|required|numeric|max_length[1]');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     
	     if($this->form_validation->run() == TRUE) {
	         if($subtoken==$token){
	             
	             if($this->input->post('action')=='edit'){
	                 $idval=$this->input->post('idval');
	                 $postdata=array(
	                     'verified'=>$_REQUEST['status'],
	                     'role'=>$_REQUEST['rtype'],
	                     'dupdate_date'=>date('Y-m-d'),
	                     'loginid'=>$user_id,
	                 );
	                 $where=array('id'=>$idval);
	                 $st = $this->efiling_model->update_data_where('efiling_users',$where,$postdata);
	                 if($st){
	                     $massage="Successfully Submit.";
	                     echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
	                 }
	             }
	         }
	     }else{
	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	     }
	 }
	 
	 
	 
	 function master_objection(){
	     $userdata=$this->session->userdata('login_success');
	     $user_id=$userdata[0]->id;
	     if($user_id){
	         $data['checklist']=$this->efiling_model->data_list_where('master_objection','verified','1');
	         $this->load->view("master/master_objection",$data);
	     }
	 }
	 
	 function addobjection(){
	     $userdata=$this->session->userdata('login_success');
	     $user_id=$userdata[0]->id;
	     $subtoken=$this->session->userdata('submittoken');
	     $token=$_REQUEST['token'];
	     $this->form_validation->set_rules('status', 'Choose status', 'trim|required|numeric|max_length[2]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $this->form_validation->set_rules('ob_sname', 'Please Enter objection short name', 'trim|required|max_length[500]');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $this->form_validation->set_rules('ob_name', 'Please Enter objection  name', 'trim|required|max_length[500]');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     
	     
	     if($this->form_validation->run() == TRUE) {
	         if($subtoken==$token){
	             if($this->input->post('action')=='add'){
	                 $postdata=array(
	                     'verified'=>$this->input->post('status',true),
	                     'objection_short_name'=>$this->input->post('ob_sname',true),
	                     'objection_name'=>$this->input->post('ob_name',true),
	                     'user_id'=>$user_id,
	                 );
	                 $st=$this->efiling_model->insert_query('master_objection',$postdata);
	                 if($st){
	                     echo json_encode(['data'=>'success','value'=>'1','massage'=>'Objection  added..','error'=>'0']); die;
	                 }
	             }
	             if($this->input->post('action')=='edit'){
	                 $idval=$this->input->post('idval');
	                 $userdata=$this->session->userdata('login_success');
	                 $user_id=$userdata[0]->id;
	                 $postdata=array(
	                     'verified'=>$this->input->post('status',true),
	                     'objection_short_name'=>$this->input->post('ob_sname',true),
	                     'objection_name'=>$this->input->post('ob_name',true),
	                     'user_id'=>$user_id,
	                 );
	                 $where=array('id'=>$idval);
	                 $st = $this->efiling_model->update_data_where('master_objection',$where,$postdata);
	                 if($st){
	                     $massage="Successfully Submit.";
	                     echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
	                 }
	             }
	         }
	     }else{
	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	     }
	 }
	 
	 function deleteobjection(){
	     $id=$this->input->post('id',true);
	     $this->form_validation->set_rules('id','Please enter valid id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $token=$_REQUEST['token'];
	     $subtoken=$this->session->userdata('submittoken');
	     if($id!='' && ($subtoken==$token)){
	         $st=$this->efiling_model->delete_event('master_objection', 'id', $id);
	         if($st){
	             echo json_encode(['data'=>'success','value'=>'1','massage'=>'Regulator Deleted..','error'=>'0']); die;
	         }
	     }else{
	         echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	     }
	 }
	 
	 
	 function editobjection(){
	     $id=$this->input->post('id',true);
	     $this->form_validation->set_rules('id','Please enter valid id ','trim|required|min_length[1]|max_length[6]|numeric');
	     if($this->form_validation->run() == FALSE){
	         echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	     }
	     $token=$_REQUEST['token'];
	     $subtoken=$this->session->userdata('submittoken');
	     if($id!='' && ($subtoken==$token)){
	         $da=$this->efiling_model->data_list_where('master_objection','id',$id);
	         foreach($da as $val){
	             $data['idval']=$val->id;
	             $data['objection_name']=$val->objection_name;
	             $data['objection_short_name']=$val->objection_short_name;
	             $data['status']=$val->status;
	         }
	         echo json_encode($data); die;
	     }
	     echo json_encode(['data'=>'error','value'=>'','massage'=>'Request not valid','error'=>'1']); die;
	 }
	 
	 
	 
}//**********END Main function ************/