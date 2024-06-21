<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Filingaction extends CI_Controller {
	    function __construct() {error_reporting(0);
	        parent::__construct();
	        $this->load->model('Admin_model','admin_model');
	        $this->load->model('Efiling_model','efiling_model');
	        $this->load->model('Efilingaction_model','efilingaction_model');
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
			else $_REQUEST[$key]=htmlspecialchars(strtr($val, $spcl_char));
			endforeach;
			
			$logvvalidate=$this->admin_model->logvalidate();
			if($logvvalidate==false){
			    $this->session->unset_userdata('login_success');
			    redirect(base_url());
			}
	    }
	    
	    
	    function review_petition_filing(){
	        $userdata=$this->session->userdata('login_success');
	        $this->session->unset_userdata('rpepcpsalt');
	        $filingno='';
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
	            $this->load->view("admin/review_petition_filing",$data);
	        }
	    }
	    
	    
	    function editapplant_respondent(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
	            $this->load->view("admin/edit_applantrespondent",$data);
	        }
	    }
	    
	    
	   
	    function edit_additonalparty(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
	            $this->load->view("admin/edit_additonalparty",$data);
	        }
	    }
	    
	    function additionla_advocate(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
	            $this->load->view("admin/additionla_advocate",$data);
	        }
	    }
	    
	  
	    
	    
	    function edit_document_filing(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
	            $this->load->view("admin/edit_document_filing",$data);
	        }
	    }
	    
	    function edit_ia_details_filing(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
	            $this->load->view("admin/edit_ia_details_filing",$data);
	        }
	    }
	    
	 
	    
	    

	    


	 function review_petition_filing1(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	       
	        if($user_id){
	            $data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
	            $this->load->view("admin/review_petition_filing",$data);
	        }
	    }

	    
	    function load_app_respodent(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=isset($_REQUEST['filingno'])?$_REQUEST['filingno']:'';
	        $party_flag=isset($_REQUEST['party_flag'])?$_REQUEST['party_flag']:'';
	        $type=isset($_REQUEST['type'])?$_REQUEST['type']:'';
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['party_flag']= $party_flag;
	            $data['filing_no']=$filingno;
	            $data['type']= $type;
	            $this->load->view("admin/load_app_respodent",$data);
	        }
	    }

	    function edit_execution_petition_filing(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
		$reffrenceno= $this->session->unset_userdata('reffrenceno');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
	            $this->load->view("admin/edit_execution_petition_filing",$data);
	        }
	    }

  	function edit_execution_petition_filing1(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
	            $this->load->view("admin/edit_execution_petition_filing",$data);
	        }
	    }

	    function edit_contempt_petition_filing(){
	        $userdata=$this->session->userdata('login_success');
		$reffrenceno= $this->session->unset_userdata('reffrenceno');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
	            $this->load->view("admin/edit_contempt_petition_filing",$data);
	        }
	    }   

  	function edit_contempt_petition_filing1(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingno);
	            $this->load->view("admin/edit_contempt_petition_filing",$data);
	        }
	    } 

	    
	    
	    function findrecord(){
	       if($_REQUEST['type']=='1'){
	           $this->form_validation->set_rules('filing_no', 'Enter Valid filing number', 'trim|required|numeric|max_length[4]');
	           if($this->form_validation->run() == false) {
	               echo json_encode(['data'=>'','error'=>strip_tags(validation_errors())]);die;
	           }
	           $this->form_validation->set_rules('dfr_year', 'Choose valid filing Year', 'trim|required|numeric|max_length[4]');
	           if($this->form_validation->run() == false) {
	               echo json_encode(['data'=>'','error'=>strip_tags(validation_errors())]);die;
	           }
	       }
	       
	       if($_REQUEST['type']=='2'){
	           $this->form_validation->set_rules('case_no', 'Enter valid case number', 'trim|required|numeric|max_length[4]');
	           if($this->form_validation->run() == false) {
	               echo json_encode(['data'=>'','error'=>strip_tags(validation_errors())]);die;
	           }
	           $this->form_validation->set_rules('year', 'Choose valid year', 'trim|required|numeric|max_length[4]');
	           if($this->form_validation->run() == false) {
	               echo json_encode(['data'=>'','error'=>strip_tags(validation_errors())]);die;
	           }

                   $this->form_validation->set_rules('case_type', 'Choose valid Case type', 'trim|required|numeric|max_length[4]');
	           if($this->form_validation->run() == false) {
	               echo json_encode(['data'=>'','error'=>strip_tags(validation_errors())]);die;
	           }
	       }
	       
	       if($this->input->post()) {
	            $data=$this->efiling_model->findrecord($this->input->post());
	            echo $data;die;
	       }
	    }
	    
	    function org(){
	        $q =$this->input->post('q',true);
	        $this->form_validation->set_rules('q','Please enter valid id ','trim|required|min_length[1]|max_length[9]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        //$q=1;
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
	    
	    
	    function getAdvDetailrp(){
	        $q =$this->input->post('q',true);
	        $this->form_validation->set_rules('q','Please enter valid id ','trim|required|min_length[1]|max_length[9]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        if($q!=0) {
	            $output = array();
	            $sql1 =$this->efiling_model->data_list_where('master_advocate','adv_code',$q);
	            foreach ($sql1 as $row){
	                $add= $row->address;
	                $mob=$row->adv_mobile;
	                $mail=$row->email;
	                $ph=$row->adv_phone;
	                $pin=$row->adv_pin;
	                $fax=$row->adv_fax;
	                $stateCode=$row->state_code;
	                $distcode=$row->adv_dist;
	                $st =$this->efiling_model->data_list_where('master_psstatus','state_code',$stateCode);
	                $statename=$st[0]->state_name;
	                if($distcode!=""){
	                    $arr=array('state_code'=>$stateCode,'district_code'=>$distcode);
	                    $stdit = $this->efiling_model->select_in('master_psdist',$arr);
	                    $distname=$stdit[0]->district_name;
	                }
	                $users_arr[] =array("address"=>$add,"mob"=>$mob,"mail"=>$mail,"ph"=>$ph,"pin"=>$pin,"fax"=>$fax,"stcode"=>$stateCode,"stname"=>$statename,"dcode"=>$distcode,"dname"=>$distname);
	            }
	            echo json_encode($users_arr);
	        }
	    }
	    
	    function districtrp(){
	        $state =$this->input->post('state_id',true);
	        $this->form_validation->set_rules('state_id','Please enter state id ','trim|required|min_length[1]|max_length[9]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        
	        if( $state!="" AND  $city==""){ 
	            if($state==''){
	                echo '<option selected="selected" value="999">Not Available</option>';
	            }else{
	                echo '<option selected="selected" value="999">Not Available</option>';
	                $sql1 = $this->efiling_model->data_list_where('master_psdist','state_code',$state);
	                foreach($sql1 as $row){
	                    echo '<option value="'.$row->district_code.'">'.$row->district_name.'</option>';
	                }
	            }
	        }
	    }
	    
	    
	    function execution_party_parity(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data= $this->input->post();
	            $this->load->view("admin/execution_party_parity",$data);
	        }
	    }
	    
	    
	    function reviewpayment(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data= $this->input->post();
	            $this->load->view("admin/reviewpayment",$data);
	        }
	    }
	    
	    
	    function postalOrderOthrer_rpcpep(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data= $this->input->post();
	            $this->load->view("admin/postalOrderOthrer_rpcpep",$data);
	        }
	    }
	    
	    
	    
	    function reviewfiling_action(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
                $salt1=$_REQUEST['saltNo'];
	        if($user_id){
	            $data= $this->input->post();
	            $curYear = date('Y');
	            $curMonth = date('m');
	            $curDay = date('d');
	            $curdate = "$curYear-$curMonth-$curDay";
	            $filing_no_old = $_REQUEST['filingNo'];
	            $partyType = $_REQUEST['type'];
	            $addparty = $_REQUEST['addparty'];
	            $partyidmain = explode(",", $addparty);
                   $order_date = $_REQUEST['order_date'];
	            $matter = $_REQUEST['matter'];
	            $iaNature = $_REQUEST['iaNature'];
	            $feecode = explode(",", $iaNature);
	            $par = $_REQUEST['p'];
	            $paritymain = explode(",", $par);
	            $caseType = '6'; 
	            $resid = $_REQUEST['res'];
	            $parityres = $_REQUEST['pp'];
	            $resid1 = explode(",", $resid);
	            $parityres1 = explode(",", $parityres);
	            $sql = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no_old);
	            foreach($sql as $row) {
	                $bench = $row->bench;
	                $filing_no333 = $row->filing_no;
	                $subBench = $row->sub_bench;
	            }  
	            if ($bench == '') {
	                $bench = substr($filing_no333, 0, 3);
	            }
	            if ($subBench == '') {
	                $subBench = substr($filing_no333, 3, 2);
	            } 
				$curYear='2021';
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
	            $newfiling_no = $benchCode . $subBenchCode . $filing_no . $curYear; 
	            if ($partyidmain[0] != "" and $paritymain[0] != "") {
	                $updateflag = 'No';
	                $len1 = sizeof($paritymain);
	                $len1 = $len1 - 1;         
	                // Changes  Ravi
	                $row_third_party_pet='';
	                $row_third_party_pet_adv='';
	                if($partyType=='3'){
	                    $arr=array('filing_no'=>$filing_no_old,'type'=>'RP','status'=>'1');
	                    $sql =$this->efiling_model->select_in('certified_copy_thirdparty',$arr);
	                    foreach ($sql as $rowtp) {
	                        $row_third_party_pet=$rowtp->petname;
	                        $row_third_party_pet_adv=$rowtp->councilcode;
	                    }
	                }

	                for ($ii = 0; $ii < $len1; $ii++) {
	                    if ($partyidmain[$ii] == 1) {
	                        //sat_case_detail;
	                        if ($paritymain[$ii] == 1) {
	                            $sql = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no_old);
	                            foreach ($sql as $row) {
	                                if ($partyType == 1) {
	                                    $updateflag = 'yes';
	                                    $pet_name = $row->pet_name;
	                                    $data=array(
	                                        'filing_no'=>$newfiling_no,
	                                        'case_type'=>$caseType,
	                                        'dt_of_filing'=>$curdate,
	                                        'pet_name'=>$pet_name,
	                                        'user_id'=>$user_id,
						'order_date'=>$order_date,
	                                        'filed_user_id'=>$user_id,
	                                        'ref_filing_no'=>$filing_no_old,
	                                    );
	                                    $sqlpet2 = $this->efiling_model->insert_query('sat_case_detail',$data);
	                                }
	                                
	                                if ($partyType == 2) {
	                                    $updateflag = 'yes';
	                                    $res_name = $row->res_name;
	                                    $data=array(
	                                        'filing_no'=>$newfiling_no,
	                                        'case_type'=>$caseType,
	                                        'dt_of_filing'=>$curdate,
	                                        'pet_name'=>$res_name,
	                                        'user_id'=>$user_id,
						'order_date'=>$order_date,
	                                        'filed_user_id'=>$user_id,
	                                        'ref_filing_no'=>$filing_no_old,
	                                    );
	                                    $sqlpet2 = $this->efiling_model->insert_query('sat_case_detail',$data);
	                                }
	                                
	                                // change  ravi Kumar
	                                if ($partyType ==3) {
	                                    $updateflag = 'yes';
	                                    $res_name = $row->res_name;
	                                    $data=array(
	                                        'filing_no'=>$newfiling_no,
	                                        'case_type'=>$caseType,
	                                        'dt_of_filing'=>$curdate,
	                                        'pet_name'=>$row_third_party_pet,
	                                        'user_id'=>$user_id,
						'order_date'=>$order_date,
	                                        'filed_user_id'=>$user_id,
	                                        'ref_filing_no'=>$filing_no_old,
	                                        'pet_adv'=>$row_third_party_pet_adv,
	                                        'res_name'=>$res_name,
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
	                                );
	                                $sqlpet2 = $this->efiling_model->insert_query('additional_party',$data);
	                            }
	                        }
	                    } else {
	                        if ($partyidmain[$ii] != 1) {
	                            if ($paritymain[$ii] == 1 and $updateflag == 'No') {
	                                $sqladd1 = $this->efiling_model->data_list_where('additional_party','party_id',$partyidmain[$ii]);
	                                foreach ($sqladd1 as $row) {
	                                    $pet_name11 = $row->pet_name;    
	                                    if ($partyType == 1) {	                                        
	                                        $data=array(
	                                            'filing_no'=>$newfiling_no,
	                                            'case_type'=>$caseType,
	                                            'dt_of_filing'=>$curdate,
	                                            'pet_name'=>$pet_name11,
	                                            'user_id'=>$user_id,
						    'order_date'=>$order_date,
	                                            'filed_user_id'=>$user_id,

	                                            'ref_filing_no'=>$filing_no_old,
	                                        );
	                                        $sqlpet = $this->efiling_model->insert_query('sat_case_detail',$data);
	                                    }
	                                    if ($partyType == 2) {
	                                        
	                                        $data=array(
	                                            'filing_no'=>$newfiling_no,
	                                            'case_type'=>$caseType,
	                                            'dt_of_filing'=>$curdate,
	                                            'pet_name'=>$pet_name11,
	                                            'user_id'=>$user_id,
						    'order_date'=>$order_date,
	                                            'filed_user_id'=>$user_id,

	                                            'ref_filing_no'=>$filing_no_old,
	                                        );
	                                        $sqlpet = $this->efiling_model->insert_query('sat_case_detail',$data);
	                                    }
	                                    
	                                    if ($partyType == 3) {
	                                        $data=array(
	                                            'filing_no'=>$newfiling_no,
	                                            'case_type'=>$caseType,
	                                            'dt_of_filing'=>$curdate,
	                                            'pet_name'=>$row_third_party_pet,
	                                            'user_id'=>$user_id,
						    'order_date'=>$order_date,
	                                             'filed_user_id'=>$user_id,

	                                            'ref_filing_no'=>$filing_no_old,
	                                            'pet_adv'=>$row_third_party_pet_adv,
	                                            'res_name'=>$res_name,
	                                        );
	                                        $sqlpet = $this->efiling_model->insert_query('sat_case_detail',$data);
	                                        $updateflag = 'yes';
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
	                                    );
	                                    $sqlpet = $this->efiling_model->insert_query('additional_party',$data);
	                                }
	                            }
	                        }
	                        
	                    }
	                }
	            }
	            
	            $where=array(
	                'filing_no'=>$newfiling_no,
	                'type'=>'RP',
	            );
	            $data=array('status'=>'0');
	            $st = $this->efiling_model->update_data_where('certified_copy_thirdparty',$where,$data);
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
	                                if ($resid1[$j] == '1R')
	                                    $resName = $row->res_name;
	                        }
	                        if ($parityres1[$j] == 1) {
	                            $ipaddress = $_SERVER['REMOTE_ADDR'];
	                            $user_id = $user_id;
	                            $update_type = 'review_filing_action';
	                            
	                            $where=array(
	                                'filing_no'=>$newfiling_no,
	                            );
	                            $data=array('res_name'=>$resName,'user_id'=>$user_id,
						'order_date'=>$order_date,
	                                        'filed_user_id'=>$user_id,
'ip'=>$ipaddress,'update_type'=>$update_type);
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
	                        }
	                    } else {
	                        if ($resid1[$j] != '1P' or $resid1[$j] != '1R') {
	                            if ($parityres1[$j] == 1 and $flagres == 'No') {
	                                $sqladd11 =  $this->efiling_model->data_list_where('additional_party','party_id',$resid1[$j]);         
	                                foreach ($sqladd11 as $row) {
	                                    $resName = $row->pet_name;   
	                                }
	                                $ipaddress = $_SERVER['REMOTE_ADDR'];
	                                $user_id = $user_id;
	                                $update_type = 'review_filing_action';
	                                $where=array('filing_no'=>$newfiling_no,  );
	                                $data=array('res_name'=>$resName,'user_id'=>$user_id,
						'order_date'=>$order_date,
	                                        'filed_user_id'=>$user_id,'ip'=>$ipaddress,'update_type'=>$update_type);
	                                $resupeate = $this->efiling_model->update_data_where('sat_case_detail',$where,$data);
	                                $flagres = 'yes';
	                            } else {
	                                $sqladd1 = $this->efiling_model->data_list_where('additional_party','party_id',$resid1[$j]);  
	                                foreach ($sqladd1 as $row) {
	                                    $pet_name11 = $row->pet_name;
	                                  
	                                    $data=array(
	                                        'filing_no'=>$newfiling_no,
	                                        'party_flag'=>$flagidres,
	                                        'pet_name'=>$resName,
	                                        'partysrno'=>$parityres1[$j],
	                                    );
	                                    $sqlpet = $this->efiling_model->insert_query('additional_party',$data);
	                                }
	                            }
	                        }
	                    }
	                }
	            }
	            
	            $hscquery = $this->efiling_model->data_list_where('aptel_temp_payment','salt',$salt1);       
	            foreach ($hscquery as $row) {
	                $data=array(
	                    'filing_no'=>$newfiling_no,
	                    'dt_of_filing'=>$curdate,
	                    'payment_mode'=>$row->payment_mode,
	                    'branch_name'=>$row->branch_name,
	                    'dd_no'=>$row->dd_no,
	                    'dd_date'=>$row->dd_date,
	                    'amount'=>$row->amount,
	                );
	                $sqlpet = $this->efiling_model->insert_query('aptel_account_details',$data);
	            }
	            
	            //RP Document Upload
	            $salt=$this->session->userdata('filesalt');
	            $hscquery = $this->efiling_model->data_list_where('temp_document','salt',$salt);
	            foreach ($hscquery as $row) {
	                $data=array(
	                    'filing_no'=>$newfiling_no,
	                    'type'=>$row->type,
	                    'doc_name'=>$row->fileName,
	                    'path'=>$row->doc_url,
	                    'entry_date'=>$curdate,
	                    'user_id'=>$user_id,
	                );
	                $sqlpet = $this->efiling_model->insert_query('aptel_ma_document',$data);
	                $this->session->unset_userdata('filesalt');
	            }
	            
	            
	            
	            $payment_mode = $_REQUEST['bd'];
	            $branch_name = $_REQUEST['dbankname'];
	            $dd_no = $_REQUEST['ddno'];
	            $dd_date = $_REQUEST['dddate'];
	            $dd_date1 = explode("-", $dd_date);
	            $ddate = $dd_date1[2] . '-' . $dd_date1[1] . '-' . $dd_date1[0];
	            $amount = $_REQUEST['amountRs'];
	            $tttotal_feee_amount = $_REQUEST['tttotal_feee_amount'];
	            $data=array(
	                'fee_amount'=>$tttotal_feee_amount,
	                'filing_no'=>$newfiling_no,
	                'dt_of_filing'=>$curdate,
	                'payment_mode'=>$payment_mode,
	                'branch_name'=>$branch_name,
	                'dd_no'=>$dd_no,
	                'dd_date'=>$ddate,
	                'amount'=>$amount,
	            );
	            $sqlpet = $this->efiling_model->insert_query('aptel_account_details',$data);
	            $iaFilingNossssss = '';
	            //print_r($iaNature);
	            if ($feecode[0] != "") {
	                $len = sizeof($feecode);
	                $len = $len - 1;
	                for ($k = 0; $k < $len; $k++) {
	                    $ia_nature = $feecode[$k];
	                    $year_ins =$this->efiling_model->data_list_where('ia_initialization','year',$curYear);      
	                    $ia_filing_no1 = $year_ins[0]->ia_filing;
	                    if ($ia_filing_no1 == '0') {
	                        $iaFilingNo = 1;
	                        $ia_filing_no = '000001';
	                    }
	                    if ($ia_filing_no1 != '0') {
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
	            
	            $data=array(
	                'filing_no'=>$newfiling_no,
	            );
	            $sqlpet = $this->efiling_model->insert_query('scrutiny',$data);
                    $hscquery = $this->efiling_model->delete_event('aptel_temp_payment','salt',$salt1);
	            $pet = $this->efiling_model->delete_event('aptel_temp_payment','salt',$filing_no_old);
	            if ($caseType != "") {
	                $filedName = "RP";
	            }
	            ?>
            <div>
            	<a href="void:javascript(0);" style="color: #3F33FF" data-toggle="modal" onclick="return popitup('<?php echo $newfiling_no; ?>','<?php echo $iaFilingNossssss; ?> ','<?php echo $curYear; ?>')"><b><?php echo "Print"; ?></b></a>
            </div>
            
            <fieldset>
                <legend style="color: red;font-size:15"><?php echo $filedName; ?> Diary Number :</legend>
                <div class="col-md-12"><font color="#0000FF" size="5">
                        Case is successfully registered With <?php echo $filedName; ?> Diary No :
                        <?php echo $newfiling_no . "</br>";
                        echo $printIAno;
                        ?>
                    </font></div>
            
            </fieldset>
            <?php             
	        }
	    }
	    
	    function iaprint_rp_cp_ep(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $data=$_REQUEST;
	        $filing_no=$data['filing_no'];
	        $this->load->library('ciqrcode');
	        $filing_No = substr($filing_no, 5, 6);
	        $filing_No = ltrim($filing_no, 0);
	        $filingYear = substr($filing_no, 11, 4);
	        $val= "DFR/$filing_No/$filingYear";
	        $url= "https://cis.aptel.gov.in/";
	        
	        $row =$this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);   
	        $dt_of_filing= $row[0]->dt_of_filing; 
	        $params['data'] = "$val , Filing Date $dt_of_filing  $url";
	        $params['level'] = 'H';
	        $params['size'] = 10;
	        //  $path= FCPATH.'qrcodeimg/'.$filing_no.'.png';
	        $params['savename'] = FCPATH.'qrcodeci/'.$filing_no.'.png';
	        
	        $path = './qrcodeci/';
	        if (!file_exists($path)) {
	            mkdir($path, 0777, true);
	        }
	        $this->ciqrcode->generate($params);
	        $data['image']= $filing_no.'.png';
	        
	        if($user_id){
	            $this->load->view("admin/iaprint_rp_cp_ep",$data);
	        }
	    }



	    function addparty_ia_details(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $data=$_REQUEST;
	        if($user_id){
	            $this->load->view("admin/addparty_ia_details",$data);
	        }
	    }
	    
	    function check_valaltanama(){
	        $party_id = $_REQUEST['party_id'];
	        $filing_no = $_REQUEST['filing_no'];
	        $partyType = $_REQUEST['partyType'];
	        if ($partyType == '2') {
	            $flags = 'R';
	        } else if ($partyType == '1') {
	            $flags = 'P';
	        }
	        
	        
	        $where =array('filing_no'=>$filing_no,'party_flag'=>$partyType);
	        $qu_caveat_detail =  $this->efiling_model->select_in('vakalatnama_detail',$where);
	        $qu_caveat_detail_data = $qu_caveat_detail;
	        $party_ids = '';
	        if (!empty($qu_caveat_detail_data)) {
	            foreach ($qu_caveat_detail_data as $key => $value) {
	                $party_ids .= $value->add_party_code . ',';
	            }
	        }
	        
	        $where =array('filing_no'=>$filing_no,'party_flag'=>$flags);
	        $qu_caveat_detail =  $this->efiling_model->select_in('additional_advocate',$where);
	        $advocate_data = $qu_caveat_detail;
	        if (!empty($advocate_data)) {
	            foreach ($advocate_data as $key => $value) {
	                $party_ids .= $value->party_code . ',';
	            }
	        }
	        if ($flags == 'P') {
	            $stha20_sat_case_detail = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
	            $pet_adv_code = $stha20_sat_case_detail[0]->pet_adv;
	            if($pet_adv_code != '') {
	                $party_ids .=  '1,';
	            }
	        }
	        if ($flags == 'R') {
	            $stha20_sat_case_detail =$this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
	            $pet_adv_code = $stha20_sat_case_detail[0]->res_adv;
	            if($pet_adv_code != '') {
	                $party_ids .=  '1,';
	            }
	        }
	        ?>
   		<table class="table">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Party Name</th>
                    <th>Vakalatnama(YES/NO)</th>
                </tr>
            </thead>
          <tbody>
 <?php
    if (!empty($party_ids)) {
        $party_ids = explode(',', rtrim($party_ids, ','));
        if (!empty($party_id)) {
            $sr = 1;
            foreach ($party_id as $key => $value) {
                if ($value == '1') {
                    $st = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
                    foreach ($st as $row) {
                        $filing_no = htmlspecialchars($row->filing_no);
                        if ($partyType == '1') {
                            $pet_name = $row->pet_name;
                        } else {
                            $pet_name = $row->res_name;
                        }

                    }
                } else {
                    $addParty = $this->efiling_model->data_list_where('additional_party','party_id',$value);
                    $pet_name = $addParty[0]->pet_name;
                }
                $var_name = '';
                if (in_array($value, $party_ids)) {
                    $var_name = 'YES ';
                } else {
                    $var_name = 'NO';
                }?>
         <tr> <td> <?php echo $sr; ?></td>
            <td> <?php echo $pet_name; ?></td>
            <td><?php echo $var_name; ?> </td>
        </tr>

        <?php $sr++;
            }
        }
    } else {
        echo '<tr> <td> NA</td><td> NA</td><td>NA </td></tr>';
    }
    ?>
</tbody>
</table>
<?php
    }  
    
    
    function paymentDetail(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $data=$_REQUEST;
        if($user_id){
            $this->form_validation->set_rules('natuer', 'Enter valid natuer', 'trim|required|numeric|max_length[2]');
            $this->form_validation->set_rules('anx', 'Enter valid anxature.', 'trim|required|numeric|max_length[2]');      
            $this->form_validation->set_rules('iaNature', 'Enter valid ia Nature', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('patyAddId', 'Enter valid Paty Id.', 'trim|required|max_length[200]');
            $this->form_validation->set_rules('partyType', 'Enter valid party Type', 'trim|required|numeric|max_length[3]');
            $this->form_validation->set_rules('filing_no', 'Enter valid filing no.', 'trim|required|numeric|max_length[16]');
            $this->form_validation->set_rules('refsalt', 'ref salt not valid', 'trim|required|numeric|max_length[10]');
            if($this->form_validation->run() == TRUE) {
                $this->load->view("admin/paymentDetail",$data);
            }else{
                echo json_encode(['data'=>'success','error'=>validation_errors()]);
            }
        }
    }
    
    
    function postalOrderia(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $data=$_REQUEST;
        if($user_id){
            $this->load->view("admin/postalOrderia",$data);
        }
    }
    
    function ia_action(){
        $sucessvals='';
        $msg='';
        $html='';
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $this->form_validation->set_rules('bname', 'Enter valid Bank Name', 'trim|required|max_length[40]');
        $this->form_validation->set_rules('dno', 'Enter valid NTRP Number.', 'trim|required|numeric|max_length[13]');
        $this->form_validation->set_rules('ddate', 'Enter valid transaction date', 'trim|required|max_length[15]');
        $this->form_validation->set_rules('amount', 'Enter valid amount.', 'trim|required|numeric|max_length[5]');    
        $this->form_validation->set_rules('ia_nature', 'Enter valid ia Nature', 'trim|required|max_length[200]');
        $this->form_validation->set_rules('partyadd', 'Enter valid Paty Id.', 'trim|required|max_length[200]');
        $this->form_validation->set_rules('bd', 'Enter valid payment Type', 'trim|required|numeric|max_length[3]');
        $this->form_validation->set_rules('fNo', 'Enter valid filing no.', 'trim|required|numeric|max_length[16]');
        $this->form_validation->set_rules('saltNo', 'ref salt not valid', 'trim|required|numeric|max_length[10]');
        if($user_id!='' && $this->form_validation->run() == TRUE){
            $curYear = date('Y');
            $curMonth = date('m');
            $curDay = date('d');
            $salt1=$_REQUEST['saltNo'];   
            $curdate="$curYear-$curMonth-$curDay";     
            $filed_by=isset($_REQUEST['party'])?$_REQUEST['party']:'';
            $filingNo=$_REQUEST['fNo'];
            $iaNature=$_REQUEST['ia_nature'];
            $bname=$_REQUEST['bname'];
            $ddno=$_REQUEST['dno'];
            $ddate=$_REQUEST['ddate'];
            $dateOfFiling=explode("/",$ddate);
            $ddate1=$dateOfFiling[2].'-'.$dateOfFiling[1].'-'.$dateOfFiling[0];
            $fee_amount=$_REQUEST['amount'];
            $payMode=$_REQUEST['bd'];
            $party=$_REQUEST['partyadd'];   
            $feecode=explode(",",$iaNature);
            if($feecode[0]!=""){
                $sql=$this->efiling_model->data_list_where('sat_case_detail','filing_no',$filingNo);
                foreach ($sql as $row) {
                    $bench=$row->bench;
                    $subBench=$row->sub_bench;
                }
                $benchCode= htmlspecialchars(str_pad($bench,3,'0',STR_PAD_LEFT));
                $subBenchCode= htmlspecialchars(str_pad($subBench,2,'0',STR_PAD_LEFT));  
            }
             $curYear='2021';      
            if($feecode[0]!="") {
                $printIAno='';
                $iaNo='';
                $len=sizeof($feecode)-1;                               
                for($k=0;$k<$len;$k++){
                    $ia_nature=$feecode[$k];     
                    $year_ins=$this->efiling_model->data_list_where('ia_initialization','year',$curYear);
                    $ia_filing_no=$year_ins[0]->ia_filing;
                    if($ia_filing_no =='0'){
                        $iaFilingNo=1;
                        $filno = $ia_filing_no ='000001';
                    }
                    if($ia_filing_no!='0'){
                        $iaFilingNo = $ia_filing_no=(int)$ia_filing_no+1;
                        $length =6-strlen($ia_filing_no);
                        for($i=0;$i<$length;$i++){
                            $ia_filing_no= "0".$ia_filing_no;
                        }  
                    }
                    $matter='';
                    if($ia_nature==12) {
                        $matter=$_REQUEST['matt'];
                    }                   
                    $iaFiling_no1=$benchCode.$subBenchCode.$ia_filing_no.$curYear;
                    $printIAno=$printIAno."IA/".$iaFilingNo."/".$curYear."<br>";
                    $iaNo=$iaNo.$iaFilingNo.','; 
              
                    $data=array(
                        'ia_no'=>$iaFilingNo,
                        'filing_no'=>$filingNo,
                        'filed_by'=>$filed_by,
                        'entry_date'=>$curdate,
                        'display'=>'Y',
                        'ia_filing_no'=>$iaFiling_no1,
                        'ia_nature'=>$ia_nature,
                        'status'=>'P',
                        'additional_party'=>$party,
                        'matter'=>$matter,
                        'user_id'=>$user_id,
                    );
                    $sqlpet2 = $this->efiling_model->insert_query('satma_detail',$data);
                    $where=array('year'=>$curYear );
                    $data=array('ia_filing'=>$iaFilingNo);
                    $resupeate = $this->efiling_model->update_data_where('ia_initialization',$where,$data); 
                }
                
                
                $hscquery = $this->efiling_model->data_list_where('aptel_temp_payment','salt',$salt1);
                foreach($hscquery as $val){
                    $data=array(
                        'filing_no'=>$filingNo,
                        'dt_of_filing'=>$curdate,
                        'amount'=>$val->amount,
                        'payment_mode'=>$val->payment_mode,
                        'branch_name'=>$val->branch_name,
                        'dd_no'=>$val->dd_no,
                        'dd_date'=>$val->dd_date,
                        'ia_fee'=>$fee_amount,
                        'status'=>'1',
                        
                    );
                    $sqlpet2 = $this->efiling_model->insert_query('aptel_account_details',$data);
                }
                
                $data=array(
                    'filing_no'=>$filingNo,
                    'dt_of_filing'=>$curdate,
                    'fee_amount'=>$fee_amount,
                    'payment_mode'=>$payMode,
                    'branch_name'=>$bname,
                    'dd_no'=>$ddno,
                    'dd_date'=>$ddate,
                    'ia_fee'=>$fee_amount,
                    'status'=>'1',   
                );
                $sqlpet2 = $this->efiling_model->insert_query('aptel_account_details',$data);
                $this->efiling_model->delete_event('aptel_temp_payment','salt',$salt1);
                //IA Document Upload
                $salt=$this->session->userdata('filesalt');
                $hscquery = $this->efiling_model->data_list_where('temp_document','salt',$salt);
                foreach ($hscquery as $row) {
                    $data=array(
                        'filing_no'=>$filingNo,
                        'type'=>$row->type,
                        'doc_name'=>$row->fileName,
                        'path'=>$row->doc_url,
                        'entry_date'=>$curdate,
                        'user_id'=>$user_id,
                    );
                    $sqlpet = $this->efiling_model->insert_query('aptel_ma_document',$data);
                    $this->session->unset_userdata('filesalt');
                }
                
            if($filed_by==3) {
                $flag=3;
                $petname=$_REQUEST['petName'];
                $petdeg=$_REQUEST['deg'];
                $state=$_REQUEST['dstate'];
                $dist=$_REQUEST['ddistrict'];
                $petAdv=$_REQUEST['petAddress'];
                $pin=$_REQUEST['pincode'];
                $petMob=$_REQUEST['petmobile'];
                $petph=$_REQUEST['petPhone'];
                $petemail=$_REQUEST['petEmail'];
                $petfax=$_REQUEST['petFax']; 
                $strsrno="Select max(partysrno :: INTEGER) as partysrno from additional_party where filing_no='$filingNo'	and party_flag='$flag'";                            
                $partno=$strsrno[0]->partysrno;                                
                if($partno ==""){
                    $partcount=2;
                } else{
                    $partcount=$partno+1;
                } 
                if($petname != '') {
                    $query_params=array(
                        'filing_no'=>$filingNo,
                        'party_flag'=>$flag,
                        'pet_name'=>$petname,
                        'pet_degingnation'=>$petdeg,
                        'pet_address'=>$petAdv,
                        'pin_code'=>$pin,
                        'pet_state'=>$state,
                        'pet_dis'=>$dist,
                        'pet_mobile'=>$petMob,
                        'pet_phone'=>$petph,
                        'pet_email'=>$petemail,
                        'pet_fax'=>$petfax,
                        'partysrno'=>$partcount,
                    );   
                    $sqlpet = $this->efiling_model->insert_query('additional_party',$query_params);
                }
            }
            
            if($feecode[0]!=0){
                $vasl= $printIAno;
            }     
            $vals=base_url().'iaprint/'.$filingNo.'/'.base64_encode($iaNo).'/'.$curYear;

            $html='<td colspan="1">
             <div><a target="_blank" href="'.$vals.'" style="color: #3F33FF" data-toggle="modal" ><b>Print</b></a></div>
              </td>
             <fieldset><legend style="color: red;font-size:15">IA Number :</legend>
                 <div class="col-md-12">
                 <font color="#0000FF" size="5"> IA is successfully registered:  '.$vasl.'</font>
                 </div>            
             </fieldset>'; 
            $sucessvals= json_encode(['data'=>'success','msg'=>'','display'=>$html]);
            }else{
                $sucessvals= json_encode(['data'=>'error','msg'=>$msg,'display'=>$html]);
            }
        }else{
            $sucessvals= json_encode(['data'=>'error','msg'=>validation_errors()]);
        }
        echo $sucessvals;die;
    }  
            
            
      function documentUpload(){
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
                date_default_timezone_set('Asia/Calcutta');
                $post_array=$this->input->post();
                $token=$this->session->userdata('tokenno');
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                $salt=$_REQUEST['salt'];
                $this->session->set_userdata('filesalt',$salt);
                //  $token1=$_REQUEST['token'];
                $type=$_REQUEST['type'];
                if($token!=$token){
                    $msg='You are not valid';
                }else{
                    $valid_extensions = array('JPEG','jpeg','JPG','jpg','png','PNG','pdf'); // valid extensions
                    $doctype='efiling';
                    $schemas='delhi';
                    $Url=base_url();
                    $path = './upload_doc/efiling/'.$type.'/'.$schemas.'/';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    
                    if(!empty($_FILES['file'])){
                        $img = $_FILES['file']['name'];
                        $tmp = $_FILES['file']['tmp_name'];
                        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                        $count = explode(".", $img);
                        $final_image = rand(1000,1000000).$img;
                        if(in_array($ext, $valid_extensions)) {
                            $save_path = $path.strtolower($final_image);
                            if(move_uploaded_file($tmp,$save_path)) {
                                $path11 = './upload_doc/efiling/'.$type.'/'.$schemas.'/';
                                $save_path = $path11.strtolower($final_image);
                                $flag = 'Y';
                                $data=array(
                                    'salt'=>$_REQUEST['salt'],
                                    'doc_url'=>$save_path,
                                    'file_type'=>$doctype,
                                    'fileName'=>$img,
                                    'type'=>$type,
                                    'user_id'=>$user_id,
                                );
                                $st=$this->efiling_model->insert_query('temp_document',$data);
                                $msg='Successfully upload';
                                if($st){
                                    echo json_encode(['data'=>'success','msg'=>$msg,'display'=>'','error'=>'0']); die;
                                }
                            }else{
                                $msg=  'Something Error. Please try again.';
                                echo json_encode(['data'=>'','msg'=>$msg,'display'=>'','error'=>$msg]);die;
                            }
                        }else{
                            $msg= 'invalid Document. Only upload PDF file.';
                            echo json_encode(['data'=>'','msg'=>$msg,'display'=>'','error'=>$msg]);die;
                        }
                    }
                }
            }
            
            
            
            function iaprint($filing_no,$iano,$iayear){
                $filing_noss=strlen($filing_no);
                if(is_numeric($filing_no) && $filing_noss==15){
                $iano=base64_decode($iano);
                if(is_numeric($iano) && is_numeric($iayear)){
                    $userdata=$this->session->userdata('login_success');
                    $user_id=$userdata[0]->id;
                    if($user_id){
                        $ias=$iano;
                        $data['filing_no']=$filing_no;
                        $data['iano']=$iano;
                        $data['year']=$iayear;
                        $data['image']=$this->getQrfilingIA($filing_no,$ias);
                        $this->load->view("admin/iaprint",$data);
                    }
                }else{
                    echo "Request not valid ";die;
                }
                }else{
                    echo "Request not valid ";die;
                }
            }       



            function maprint($filingNo,$docFilingNo,$doctype,$did){
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                if($user_id){
                    $data['doctype']=$doctype;
                    $data['filingNo']=$filingNo;
                    $data['pid']=$did;
                    $data['docFilingNo']=$docFilingNo;
                    $this->load->view("admin/maprint",$data);
                }
            }

            
            
             function postalOrderOthrer(){
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                if($user_id){
                    $data= $this->input->post();
                    $this->load->view("admin/postalOrderOthrer",$data);
                }
             }


           function additionalAdvocate_action_new(){
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                $data=$_REQUEST;
                if($user_id){
                    $this->load->view("admin/additionalAdvocate_action_new",$data);
                }
            }
            
            
            function delete_additional_advocate(){
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                $data=$_REQUEST;
                if($user_id){
                    $this->load->view("admin/delete_additional_advocate",$data);
                }
            }

            function addMorecouncel(){
                $cadd = htmlspecialchars($_REQUEST['cadd']);
                $cpin = htmlspecialchars($_REQUEST['cpin']);
                $cmob = htmlspecialchars($_REQUEST['cmob']);
                $cemail = htmlspecialchars($_REQUEST['cemail']);
                $cfax = htmlspecialchars($_REQUEST['cfax']);
                $salt = htmlspecialchars($_REQUEST['salt']);
                $counselpho = htmlspecialchars($_REQUEST['counselpho']);
                $state = htmlspecialchars($_REQUEST['st']);
                $dist = htmlspecialchars($_REQUEST['dist']);
                $councilCode = htmlspecialchars($_REQUEST['councilCode']);
                $userdata=$this->session->userdata('login_success');
                $userid=$userdata[0]->id;
                $bd = $_REQUEST['bd'];
                if ($bd == 1){
                    $pflag = 'P';
                }
                if ($bd == 2){
                   $pflag = 'R';
                }
                $query_params = array(
                    'filing_no' => $salt,
                    'party_flag' => $pflag,
                    'adv_code' => $councilCode,
                    'adv_mob_no' => $cmob,
                    'adv_phone_no' => $counselpho,
                    'adv_fax_no' => $cfax,
                    'adv_email' => $cemail,
                    'adv_address' => $cadd,
                    'user_id' => $userid,
                    'pin_code' => $cpin,
                    'party_code' => $pcodeAll[$i],
                    'state' => $state,
                    'district' => $dist
                );
                $st=$this->efiling_model->insert_query('additional_advocate_ia',$query_params);  
                $hscquery = "SELECT id,adv_code,party_flag,adv_mob_no,adv_email,party_code as partysrno  FROM additional_advocate_ia  WHERE  filing_no='$salt'";
                $query=$this->db->query($hscquery);
                $data = $query->result();
                $ii=1;
                $html='<table id="advdetails_dfgdgdf" class="display" cellspacing="0" border="1" width="100%">
                <thead>
                <tr>
                <th>Sr. No.</th>
                <th>Advcate name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Delete</th>
                </tr>
                </thead>';
                foreach ($data as $row) {
                    $advId = $row->id;
                    $acode = $row->adv_code;
                    $advId="'$advId'";
                    $adv_name = $this->efiling_model->data_list_where('master_advocate','adv_code',$acode);
                        $html.='<tr>
                            <td>'.$ii.'</td>
                            <td>'.htmlspecialchars($adv_name[0]->adv_name).'</td>
                            <td>'.htmlspecialchars($row->adv_mob_no).'</td>
                            <td>'.htmlspecialchars($adv_name[0]->email).'</td>
                            <td><input type="button" name="nextsubmit" id="nextsubmit" value="Delete" class=".btn-info"   onclick="deletePay('.$advId.')"/>
                        </tr>';
                        $ii++;
                 }
                 echo $html;die;
            }
     
            
            function delete_advocate(){
                $id=$_REQUEST['id'];
                $party=$_REQUEST['party'];
                $salt=$_REQUEST['salt'];
                $bd = $_REQUEST['ptype'];
                $query =$this->efiling_model->delete_event('additional_advocate_ia','id',$_REQUEST['payid']);
                $salt = $_REQUEST['salt'];
                $hscquery = "SELECT id,adv_code,party_flag,adv_mob_no,adv_email,party_code as partysrno  FROM additional_advocate_ia  WHERE  filing_no='$salt'";
                $query=$this->db->query($hscquery);
                $data = $query->result();
                $html='<table id="advdetails_dfgdgdf" class="display" cellspacing="0" border="1" width="100%">
                <thead>
                <tr>
                <th>Sr. No.</th>
                <th>Advcate name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Delete</th>
                </tr>
                </thead>';
                $ii=1;
                foreach ($data as $row) {
                    $advId = $row->id;
                    $acode = $row->adv_code;
                    $advId="'$advId'";
                    $adv_name = $this->efiling_model->data_list_where('master_advocate','adv_code',$acode);
                    $html.='<tr>
                            <td>'.$ii.'</td>
                            <td>'.htmlspecialchars($adv_name[0]->adv_name).'</td>
                            <td>'.htmlspecialchars($row->adv_mob_no).'</td>
                            <td>'.htmlspecialchars($adv_name[0]->email).'</td>
                            <td><input type="button" name="nextsubmit" id="nextsubmit" value="Delete" class=".btn-info"   onclick="deletePay('.$advId.')"/>
                        </tr>';
                    $ii++;
                }
                echo $html;die;
            }

   
            function filingPrintSlip(){
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                $filing_no=$_REQUEST['filingno'];
                $this->load->library('ciqrcode');
                $sql22 = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
                $user = $this->efiling_model->data_list_where('efiling_users','id',$user_id);
                $name=$user[0]->fname.' '.$user[0]->lname;
                $filing_no=$sql22[0]->filing_no;
                $filingNo=$sql22[0]->filing_no;
                $pet_adv=$sql22[0]->pet_adv;
                $dt_of_filing=$sql22[0]->dt_of_filing;
                $dt_of_filing=date('d-m-y',strtotime($dt_of_filing));
                $pet_email=$user[0]->email;
                $pet_mobile=$user[0]->mobilenumber;
                $filing_No = substr($filingNo, 5, 6);
                $filing_No = ltrim($filing_No, 0);
                $filingYear = substr($filingNo, 11, 4);
                $val= "DFR/$filing_No/$filingYear";
                $url= "http://sat.gov.in/";
                $params['data'] = "$val , Filing Date $dt_of_filing filed by $name , $pet_email ,Mobile No. $pet_mobile  $url";
                $params['level'] = 'H';
                $params['size'] = 10;
                //  $path= FCPATH.'qrcodeimg/'.$filing_no.'.png';
                $params['savename'] = FCPATH.'qrcodeci/'.$filing_no.'.png';
                
                $path = './qrcodeci/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $this->ciqrcode->generate($params);
                $data['image']= $filing_no.'.png';
                
                $data['filing_no']=$filing_no;
                if($user_id){
                    $this->load->view("admin/filingPrintSlip",$data);
                } 
            }
            
            function payslip($id){
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                $this->load->library('ciqrcode');
                $sql22 = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$id);
                
                $user = $this->efiling_model->data_list_where('efiling_users','id',$user_id);
                $name=$user[0]->fname.' '.$user[0]->lname;
                $filing_no=$sql22[0]->filing_no;
                $filingNo=$sql22[0]->filing_no;
                $pet_adv=$sql22[0]->pet_adv;
                $dt_of_filing=$sql22[0]->dt_of_filing;
                $dt_of_filing=date('d-m-y',strtotime($dt_of_filing));
                $pet_email=$user[0]->email;
                $pet_mobile=$user[0]->mobilenumber;
                $filing_No = substr($filingNo, 5, 6);
                $filing_No = ltrim($filing_No, 0);
                $filingYear = substr($filingNo, 11, 4);
                $val= "DFR/$filing_No/$filingYear";
                $url= "http://sat.gov.in/";
                $params['data'] = "$val , Filing Date $dt_of_filing filed by $name , $pet_email ,Mobile No. $pet_mobile  $url";
                $params['level'] = 'H';
                $params['size'] = 10;
              //  $path= FCPATH.'qrcodeimg/'.$filing_no.'.png';
                $params['savename'] = FCPATH.'qrcodeci/'.$filing_no.'.png';
                
                $path = './qrcodeci/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $this->ciqrcode->generate($params);
                $data['image']= $filing_no.'.png';
              //  $base64 = 'data:image/png;base64,'.base64_encode($img);
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                $data['filing_no']=$id;
                if($user_id){
                    $this->load->view("admin/filingPrintSlip",$data);
                } 
            }

            function rpepcp_pay_slip($filingNo,$ia,$year){
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                $qr=$this->getQrfilingIA($filingNo,$ia);
                $data['filing_no']=$filingNo;
                $data['ia']=$ia;
                $data['year']=$year;
                $data['image']=$this->getQrfiling($filingNo);
                if($user_id){
                    $this->load->view("admin/iaprint_rp_cp_ep",$data);
                }
            }


            function addCetifiedCopy(){
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                if($user_id){
                    $this->load->view("admin/addCetifiedCopy",$data);
                }
            }
            
            function certifiedpostalOrder(){
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                if($user_id){
                    $this->load->view("admin/certifiedpostalOrder",$data);
                }
            }
            
            function addMoredd_for_cartify(){
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                if($user_id){
                    $this->load->view("admin/addMoredd_for_cartify",$data);
                }
            }
            
            
            function copycertifiedCopysave(){
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id; 
                $this->form_validation->set_rules('patyAddId','please enter party type','trim|required|numeric|htmlspecialchars|regex_match[/^[0-9,]+$/]');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                } 
                $this->form_validation->set_rules('filingNo',' filing no not valid','trim|required|htmlspecialchars|regex_match[/^[0-9,]+$/]');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                } 
                $this->form_validation->set_rules('matterId','please enter matter id','trim|required|htmlspecialchars');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                } 
                $this->form_validation->set_rules('bd','please enter payment type','trim|required|htmlspecialchars|regex_match[/^[0-9,]+$/]');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                } 
                $this->form_validation->set_rules('ntrp','please enter ntrp number','trim|required|htmlspecialchars');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                } 
                $this->form_validation->set_rules('ntrpdate','please enter date','trim|required|htmlspecialchars');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                } 
                $this->form_validation->set_rules('ntrpamount','please enter amount','trim|required|htmlspecialchars|regex_match[/^[0-9,]+$/]');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                } 
                $this->form_validation->set_rules('orderdate','please enter order date','trim|required|htmlspecialchars');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                } 
                $this->form_validation->set_rules('no_page','please enter number of page','trim|required|htmlspecialchars');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                } 
                $this->form_validation->set_rules('no_set','please enter number of set.','trim|required|htmlspecialchars');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                } 
                $this->form_validation->set_rules('total_amount','please enter total amount','trim|required|htmlspecialchars');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                } 
                $this->form_validation->set_rules('cnt','please enter count','trim|required|htmlspecialchars');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                } 
                $this->form_validation->set_rules('total','Total Amount not valid','trim|required|htmlspecialchars');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
                } 
                if($user_id){
                  $this->load->view("admin/copycertifiedCopysave");
                }
            }
            
            
            function certifyreceiptview($cid,$filing_no){
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                
                $filingcc=base64_decode($filing_no);
                if(!is_numeric($filingcc) && strlen($filingcc)!=15){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>'Filing number not valid.','error'=>'1']); die;
                }
                
                $cidxxs=base64_decode($cid);
                if(!is_numeric($cidxxs)){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>'doc id not valid.','error'=>'1']); die;
                }

                $data['cid']=base64_decode($cid);
                $data['filing_no']=base64_decode($filing_no);
                if($user_id){
                    $this->load->view("admin/certifyreceiptview",$data);
                }
            }
            
            function certifyreceipt($cid,$filing_no){
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                $data['cid']=$cid;
                $data['filing_no']=$filing_no;
                if($user_id){
                    $this->load->view("admin/certifyreceipt",$data);
                }
            }
            
            function receipt_certify_matters($cid,$filing_no){

                $filingcc=$filing_no;
                if(!is_numeric($filingcc) && strlen($filingcc)!=15){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>'Filing number not valid.','error'=>'1']); die;
                }
                
                $cidxxs=$cid;
                if(!is_numeric($cidxxs)){
                    echo json_encode(['data'=>'error','value'=>'','massage'=>'doc id not valid.','error'=>'1']); die;
                }


                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                $data['cid']=$cid;
                $data['filing_no']=$filing_no;
                if($user_id){
                    $this->load->view("admin/receipt_certify_matters",$data);
                }
            }
     
            
            
            function getQrfiling($filing_no){
                $this->load->library('ciqrcode');
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                $user = $this->efiling_model->data_list_where('efiling_users','id',$user_id);
                $name=$user[0]->fname.' '.$user[0]->lname;
                $sql22 = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
                $filing_no=$sql22[0]->filing_no;
                $filingNo=$sql22[0]->filing_no;
                $dt_of_filing=$sql22[0]->dt_of_filing;
                $dt_of_filing=date('d-m-y',strtotime($dt_of_filing));
                $pet_email=$user[0]->email;
                $pet_mobile=$user[0]->mobilenumber;
                $filing_No = substr($filing_no, 5, 6);
                $filing_No = ltrim($filing_No, 0);
                $filingYear = substr($filing_no, 11, 4);
                $val= "DFR/$filing_No/$filingYear";
                $url= "http://sat.gov.in/";
                $params['data'] = "$val , Filing Date $dt_of_filing filed by $name , $pet_email ,Mobile No. $pet_mobile  $url";
                $params['level'] = 'H';
                $params['size'] = 10;
                $params['savename'] = FCPATH.'qrcodeci/'.$filing_no.'.png';
                $path = './qrcodeci/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $this->ciqrcode->generate($params);
                return  $filing_no.'.png';
            }
            
            function getQrfilingIA($filing_no,$ias){
                $this->load->library('ciqrcode');
                $userdata=$this->session->userdata('login_success');
                $user_id=$userdata[0]->id;
                $user = $this->efiling_model->data_list_where('efiling_users','id',$user_id);
                $name=$user[0]->fname.' '.$user[0]->lname;
                $sql22 = $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
                $filing_no=$sql22[0]->filing_no;
                $filingNo=$sql22[0]->filing_no;
                $dt_of_filing=$sql22[0]->dt_of_filing;
                $dt_of_filing=date('d-m-y',strtotime($dt_of_filing));
                $pet_email=$user[0]->email;
                $pet_mobile=$user[0]->mobilenumber;
                $filing_No = substr($filing_no, 5, 6);
                $filing_No = ltrim($filing_No, 0);
                $filingYear = substr($filing_no, 11, 4);
                $val= "DFR/$filing_No/$filingYear";
                $url= "http://sat.gov.in/";
                $params['data'] = "$val , in IAS $ias Filing Date $dt_of_filing filed by $name , $pet_email ,Mobile No. $pet_mobile  $url";
                $params['level'] = 'H';
                $params['size'] = 10;
                $params['savename'] = FCPATH.'qrcodeci/'.$filing_no.'.png';
                $path = './qrcodeci/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $this->ciqrcode->generate($params);
                return  $filing_no.'.png';
            }
	}