<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Efiling extends CI_Controller {
	    function __construct() {
	        error_reporting(0);
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
			$logvvalidate=$this->admin_model->logvalidate();
			if($logvvalidate==false){
			    $this->session->unset_userdata('login_success');
			    redirect(base_url());
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
	    


	    
	     function dashboard(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $checkcpass=$userdata[0]->is_password;
	        if($user_id){
	            if($checkcpass==0){
	                redirect(base_url('change_password'),refresh);
	                exit;
	            }
	            $array=array(
    	            'user_id'=>$user_id,
    	            'user_id'=>$user_id,
	            );
	            $data['draft']= $this->efiling_model->data_list('sat_temp_appellant');   
	            $data['apeeal']= $this->efiling_model->geRecorappeal('sat_case_detail');   
	            $data['rpepcp']= $this->efiling_model->gerpepcp('sat_case_detail','case_type','6');
	            $data['iadetail']=$this->efiling_model->data_list('satma_detail');
	            $data['draftiadetail']=$this->efiling_model->data_list('temp_iadetail');
				$data['defective']= $this->efiling_model->scrutiny();
    	        $this->load->view("admin/dashboardview",$data);
	        }   
	    } 

	    function undersection(){
	        if($this->input->post()){
	            $data=$this->efiling_model->undersection($this->input->post());
	            return $data;
	        }
	    }
        
	    function document_upload_epcprpia(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	           $this->load->view('admin/document_upload_epcprpia');
	        }
	    }
	
	    function orgdetail(){
	        $this->load->view('admin/orgdetail');
	    }
	    
	    function appellantDetail(){
	        $this->load->view('admin/appellantDetail');
	    }
	    
	    function orgNdetail(){
	        $users_arr=array();
            $q =$this->input->post('q',true);
            $this->form_validation->set_rules('q','Please enter id','trim|required|min_length[1]|max_length[2]|numeric');
            if($this->form_validation->run() == FALSE){
                echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
            }
            if ($q != 0) {
                $output = array();
                $data=$this->efiling_model->data_list_where('master_org','org_id',$q);
                foreach($data as $row){
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
                    $state=$this->efiling_model->data_list_where('master_psstatus','state_code',$stateCode);
                    $statename = $state[0]->state_name;
          
                    $distname = '';
                    if ($distcode != "") {
                        $stdit=$this->efiling_model->data_list_where('master_psdist','district_code',$distcode);
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
                    $users_arr[] = array("orgid"=>$q,'org_name'=>$org_name,"address" => $add, "mob" => $mob, "mail" => $mail, "ph" => $ph, "pin" => $pin, "fax" => $fax, "stcode" => $stateCode, "stname" => $statename, "dcode" => $distcode, "dname" => $distname, "desg" => $orgdesg);
                }
                echo json_encode($users_arr);
            }
	    }
	    
	    
	    function getAdvDetail(){
	        $q =$this->input->post('q',true);
	        $this->form_validation->set_rules('q','Please enter id','trim|required|min_length[1]|max_length[8]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        if($q!=0){
	            $output = array();
	            $data=$this->efiling_model->data_list_where('master_advocate','adv_code',$q);
	            foreach($data as $row){
	                $add= $row->address;
	                $adv_name= $row->adv_name;
	                $adv_code= $row->adv_code;
	                $mob=$row->adv_mobile;
	                $mail=$row->email;
	                $ph=$row->adv_phone;
	                $pin=$row->adv_pin;
	                $fax=$row->adv_fax;
	                $stateCode=$row->state_code;
	                $distcode=$row->adv_dist;
	                $st=$this->efiling_model->data_list_where('master_psstatus','state_code',$stateCode);
	                $statename=$st[0]->state_name;
	                if($distcode!=""){
	                    $stdit=$this->efiling_model->getDistrictlist($stateCode,$distcode);
	                    $distname=$stdit[0]->district_name;
	                }
	                $users_arr[] =array("address"=>$add,
	                    "adv_name"=>$adv_name,
	                    "adv_code"=>$adv_code,
	                    "mob"=>$mob,"mail"=>$mail,"ph"=>$ph,"pin"=>$pin,"fax"=>$fax,"stcode"=>$stateCode,"stname"=>$statename,"dcode"=>$distcode,"dname"=>$distname);
	            }
	            echo json_encode($users_arr);
	        }
	    }
	    
	    
	    
	    function getAdvDetail1(){
	        $q =$this->input->post('q',true);
	        $this->form_validation->set_rules('q','Please enter id','trim|required|min_length[1]|max_length[8]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        if($q!=0){
	            $output = array();
	            $data=$this->efiling_model->data_list_where('master_advocate','adv_code',$q);
	            foreach($data as $row){
	                $add= $row->address;
	                $adv_name= $row->adv_name;
	                $adv_code= $row->adv_code;
	                $mob=$row->adv_mobile;
	                $mail=$row->email;
	                $ph=$row->adv_phone;
	                $pin=$row->adv_pin;
	                $fax=$row->adv_fax;
	                $stateCode=$row->state_code;
	                $distcode=$row->adv_dist;
	                $st=$this->efiling_model->data_list_where('master_psstatus','state_code',$stateCode);
	                $statename=$st[0]->state_name;
	                if($distcode!=""){
	                    $stdit=$this->efiling_model->getDistrictlist($stateCode,$distcode);
	                    $distname=$stdit[0]->district_name;
	                }
	                $users_arr[] =array("address"=>$add,
	                    "adv_name"=>$adv_name,
	                    "adv_code"=>$adv_code,
	                    "mob"=>$mob,"mail"=>$mail,"ph"=>$ph,"pin"=>$pin,"fax"=>$fax,"stcode"=>$stateCode,"stname"=>$statename,"dcode"=>$distcode,"dname"=>$distname);
	            }
	            echo json_encode($users_arr);
	        }
	    }
	    
	    
	    
	    
	    function  district(){
	        $state=$this->input->post('state_id');
	        $this->form_validation->set_rules('state_id','Please enter state id ','trim|required|min_length[1]|max_length[9]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
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
	    
	    
	    function  districtselected(){
	        $state =$this->input->post('stateid',true);
	        $this->form_validation->set_rules('stateid','Please enter state id','trim|required|min_length[1]|max_length[8]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        $districtid =$this->input->post('districtid',true);
	        $this->form_validation->set_rules('districtid','Please enter district id','trim|required|min_length[1]|max_length[8]|numeric');
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
	    
	    
	    function orgdetailres(){
	        $this->load->view('admin/orgdetailres');
	    }
	    
	    function respondentDetail(){
	        $this->load->view('admin/respondentDetail');
	    }
	    
	    function postalOrder(){
	        $app['app']=$_REQUEST['app'];
	        $this->load->view('admin/postalOrder',$app);
	    }
	    

		function orgshow(){

		}
	    

		function orgshowres(){
			$token=$_REQUEST['token'];
			$userdata=$this->session->userdata('login_success');
			$user_id=$userdata[0]->id; 
			$this->form_validation->set_rules('tabno','Please enter tab no','trim|required|min_length[1]|max_length[4]|numeric');
			if($this->form_validation->run() == FALSE){
			    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
			}
			if($user_id){
    			$subtoken=$this->session->userdata('submittoken');
    			if($subtoken==$token){
    				$msg='You are not valid';
    			}
    			$query_params=array(
    				'tab_no'=>$_REQUEST['tabno']
    			);
    			$data_app=$this->efiling_model->update_data('sat_temp_appellant', $query_params,'salt', $salt);
    			if($data_app){
    			    echo json_encode(['data'=>'success','display'=>$msg,'error'=>'0']);
    			} else{
    				echo json_encode(['data'=>'error','error'=>'DB Error found in line no '.__LINE__]);
    			}
			}
		}
	    
		public function saveNextcheck(){
			if($this->session->userdata('login_success') && $this->input->post()) {
				$post=$this->input->post();
				$this->form_validation->set_rules('orderpassing','Please  order passing','trim|required|min_length[1]|max_length[4]|htmlspecialchars|numeric');
				if($this->form_validation->run() == FALSE){
				    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
				}
				$this->form_validation->set_rules('penalty','Please  penalty','trim|required|min_length[1]|max_length[12]|htmlspecialchars|numeric');
				if($this->form_validation->run() == FALSE){
				    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
				}
				$this->form_validation->set_rules('relevantacts','Please  relevant acts','trim|required|min_length[1]|max_length[2]|htmlspecialchars|numeric');
				if($this->form_validation->run() == FALSE){
				    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
				}
				$this->form_validation->set_rules('impugnedno','Please impugned no','trim|required|min_length[1]|max_length[25]');
				if($this->form_validation->run() == FALSE){
				    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
				}
				$this->form_validation->set_rules('delayfiling','Please delay filing','trim|required|min_length[1]|max_length[4]|htmlspecialchars|numeric');
				if($this->form_validation->run() == FALSE){
				    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
				}
				$this->form_validation->set_rules('appeal_type','Please apeal type','trim|required|min_length[1]|max_length[8]|htmlspecialchars');
				if($this->form_validation->run() == FALSE){
				    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
				}
				$decision = htmlspecialchars($_REQUEST['receiptimpdate']);
				if (!preg_match("/[0-9]{2}\-[0-9]{2}\-[0-9]{4}/", $decision)) {
				    $msg=  'Your receiptimp date entry does not match the DD-MM-YYYY required format.';
				    echo json_encode(['data'=>'error','value'=>$msg,'display'=>$msg,'error'=>'1']);die;
				}
				$comDate = htmlspecialchars($_REQUEST['impugneddate']);
				if (!preg_match("/[0-9]{2}\-[0-9]{2}\-[0-9]{4}/", $comDate)) {
				    $msg=  'Your impugned date entry does not match the DD-MM-YYYY required format.';
				    echo json_encode(['data'=>'error','value'=>$msg,'display'=>$msg,'error'=>'1']);die;
				} 
				$userdata=$this->session->userdata('login_success');
				$user_id=$userdata[0]->id;
				$saltval=$this->session->userdata('salt');
				$impugneddate=$this->input->post('impugneddate');
				$receiptimpdate=$this->input->post('receiptimpdate');
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
				    $saltval=$verify_salt.date('Y');
				    $this->session->set_userdata('salt',$saltval);
				    $data=[
				        'salt'=>$saltval,
				        'opauthority'=>$this->input->post('orderpassing',true),
				        'ipenalty'=>$this->input->post('penalty',true),
				        'iorderdate'=>date('Y-m-d',strtotime($impugneddate)),
				        'act'=>$this->input->post('relevantacts'),
				        'rimpugnedorder'=>date('Y-m-d',strtotime($receiptimpdate)),
				        'user_id'=>$user_id,
				        'tab_no'=>1,
				        'iordernumber'=>$this->input->post('impugnedno',true),
				        'delayinfiling'=>$this->input->post('delayfiling',true),
				        'apeal_type'=>$this->input->post('appeal_type',true),
				    ];
				    $rs=$this->db->insert('sat_temp_appellant',$data);
				}else{
				    $data=[
				        'opauthority'=>$this->input->post('orderpassing',true),
				        'ipenalty'=>$this->input->post('penalty',true),
				        'iorderdate'=>date('Y-m-d',strtotime($impugneddate)),
				        'act'=>$this->input->post('relevantacts',true),
				        'rimpugnedorder'=>date('Y-m-d',strtotime($receiptimpdate)),
				        'user_id'=>$user_id,
				        'tab_no'=>1,
				        'iordernumber'=>$this->input->post('impugnedno',true),
				        'delayinfiling'=>$this->input->post('delayfiling',true),
				        'apeal_type'=>$this->input->post('appeal_type',true),
				    ];
				    $rs=$this->efiling_model->update_data('sat_temp_appellant', $data,'salt', $saltval);
				}
				if($rs) 	echo json_encode(['data'=>'success','error'=>'0']);
				else 		echo json_encode(['data'=>'Query error found!','error'=>'1']);
			}
			else echo json_encode(['data'=>'Permission deny!','error'=>'1']);
		}	    
	    
		
		
		public function save_next(){
		    $userdata=$this->session->userdata('login_success');
		    $user_id=$userdata[0]->id;
		    $this->form_validation->set_rules('tab_no','Please enter tab no ','trim|required|min_length[1]|max_length[2]|numeric');
		    if($this->form_validation->run() == FALSE){
		        echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
		    }
		    $this->form_validation->set_rules('partyType','Please enter tab party Type','trim|required|min_length[1]|max_length[2]|numeric');
		    if($this->form_validation->run() == FALSE){
		        echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
		    }
		    if($this->session->userdata('login_success') && $this->input->post()) {
		        $salt=$this->session->userdata('salt');
		        $table_name='sat_temp_appellant'; 
		        $tab_no=$this->input->post('tab_no',true);
		        $partytype=$this->input->post('partyType',true);
		        $rs=$this->db->where(['salt'=>$salt,'user_id'=>$user_id])
		        ->set(['tab_no'=>$tab_no,'filed_by'=>$partytype])
		        ->update($table_name);
		        if($rs) 	echo json_encode(['data'=>'success','error'=>'0']);
		        else 		echo json_encode(['data'=>'Query error found!','error'=>'1']);
		    }
		    else echo json_encode(['data'=>'Permission deny!','error'=>'1']);
		}
		
		
		
		
	    
	  function addMoreAppellant(){
    	    $appage= null;
    	    $appfather= null;
    	    $apppan= null;
    	    $userdata=$this->session->userdata('login_success');
    	    $user_id=$userdata[0]->id;
    	    $salt=$this->session->userdata('salt');
	        if($salt==''){
	            echo json_encode(['data'=>'error','massage'=>'Please Enter Appellant Pan card number ','error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('org','Please select date ','trim|required|min_length[1]|max_length[1]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('dstate','Please select state','trim|required|min_length[1]|max_length[4]|htmlspecialchars|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('ddistrict','Please select district','trim|required|min_length[1]|max_length[3]|htmlspecialchars|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        
	        $this->form_validation->set_rules('pin','Enter Pin number','trim|min_length[6]|htmlspecialchars|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('petMob','Enter mobile number.','trim|required|min_length[10]|htmlspecialchars|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        
	        $this->form_validation->set_rules('petemail','Enter email address.','trim|required|valid_email|min_length[1]|max_length[50]|htmlspecialchars');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        
	        $this->form_validation->set_rules('petAdv','Enter address.','trim|min_length[1]|max_length[250]|htmlspecialchars');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        
	        $this->form_validation->set_rules('petfax','Enter fax.','trim|min_length[1]|max_length[10]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        
	        $this->form_validation->set_rules('idtype','Enter id proof type.','trim|min_length[1]|max_length[10]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        
	        
	        $tokenno=$this->session->userdata('tokenno');
	        
	        if($salt==''){
	            echo json_encode(['data'=>'error','display'=>'Please Enter Appellant Pan card number ','error'=>'1']);die;
	        }
	        $token=$this->input->post('token'); 
	        if($tokenno==$token){
	            echo json_encode(['data'=>'error','display'=>'You are not valid','error'=>'1']);die;
	        }
	        if($this->input->post('org')=='2'){
    	        $appage= $this->input->post('appage');
    	        if($appage==''){
    	            echo json_encode(['data'=>'error','display'=>'Please Enter Appellant Age ','error'=>'1']);die;
    	        }
    	        
    	        $appfather=$this->input->post('appfather');
    	        $apppan=$this->input->post('apppan');
    	        if($apppan==''){
    	            echo json_encode(['data'=>'error','display'=>'Please Enter Appellant Pan card number ','error'=>'1']);die;
    	        }
	        }
	        
	        $query=$this->db->query("select pet_name from sat_temp_appellant where salt='$salt'");
	        $partno= $query->result();
	        $valpetname=$partno[0]->pet_name;
	        $petname =$this->input->post('patname'); 
	        if(is_numeric($petname)){
	            $hscquery = $this->efiling_model->data_list_where('master_org','org_id',$petname);
	            $petname = $hscquery[0]->orgdisp_name;
	        }
	        if($valpetname!=''){
	            $query=$this->db->query("Select max(partysrno :: INTEGER) as partysrno from sat_temp_additional_party where salt='$salt'");
	            $partno= $query->result();
	            if($partno[0]->partysrno ==""){
	                $partcount=2;
	            }else{
	                $partcount=$partno[0]->partysrno+1;
	            }
	            $partyid='';
	            if($this->input->post('org')=='1'){
	                $partyid=$this->input->post('orgid');
	            }
	            if($this->input->post('org')=='2'){
	                $partyid=$this->input->post('patname');
	                $this->form_validation->set_rules('patname','Enter applicant name.','trim|min_length[1]|max_length[100]');
	                if($this->form_validation->run() == FALSE){
	                    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	                }
	                if($partyid==''){
	                    echo json_encode(['data'=>'error','display'=>'Please Enter Appellant Name ','error'=>'1']);die;
	                }
	            }
    	        $query_params = array(
    	            'salt' =>$this->input->post('salt'),
    	            'pet_name' =>$petname,
    	            'pet_degingnation' =>$this->input->post('petdeg'),
    	            'pet_address' =>$this->input->post('petAdv'),
    	            'pin_code' =>$this->input->post('pin'),
    	            'pet_state' =>$this->input->post('dstate'),
    	            'pet_dis' =>$this->input->post('ddistrict'),
    	            'pet_mobile' =>$this->input->post('petMob'),
    	            'pet_phone' =>$this->input->post('petph'),
    	            'pet_email' =>$this->input->post('petemail'),
    	            'pet_fax' =>$this->input->post('petfax'),
    	            'appage' =>$appage,
    	            'appfather' =>$appfather,
    	            'apppan' =>$apppan,
    	            'party_id'=>$partyid,
    	            'partysrno'=>$partcount,
    	            'user_id'=>$user_id,
    	            'partyType'=>$this->input->post('org'),
    	            'idtype'=>$this->input->post('idtype'),
    	        );
				
    	        $st=$this->efiling_model->insert_query('sat_temp_additional_party',$query_params);
    	        $additionalparty=$this->efiling_model->data_list_where('sat_temp_additional_party','salt',$salt);
    	        $htmladditonalparty=$this->htmaladditionalparty($additionalparty,$salt);
    	        if($st){
    	            echo json_encode(['data'=>'success','display'=>$htmladditonalparty,'error'=>'0','3 last_query'=>$this->db->last_query()]);
    	        }  
	        }else{
	            $petname =$this->input->post('patname');
	            if(is_numeric($petname)){
	                $hscquery = $this->efiling_model->data_list_where('master_org','org_id',$petname);
	                $petname = $hscquery[0]->orgdisp_name;
	            }
	            $org=$this->input->post('org');
	            $this->session->set_userdata('org',$org);
	            $query_params=array(
	                'pet_name'=>$petname,
	                'pet_address'=>$this->input->post('petAdv'),
	                'pincode'=>$this->input->post('pin'),
	                'petmobile'=>$this->input->post('petMob'),
	                'petphone'=>$this->input->post('petph'),
	                'pet_email'=>$this->input->post('petemail'),
	                'pet_fax'=>$this->input->post('petfax'),
	                'appage' =>$appage,
	                'appfather' =>$appfather,
	                'apppan' =>$apppan,
	                'tab_no'=>2,
	                'pet_state'=>$this->input->post('dstate'),
	                'pet_dist'=>$this->input->post('ddistrict'),
	                'pet_council_adv'=>$this->input->post('councilCode'),
	                'pet_id'=>isset($_REQUEST['orgid'])?$_REQUEST['orgid']:0,
	                'pet_type'=>$org,
	                'idtype'=>$this->input->post('idtype'),
	            );
	            $data_app=$this->efiling_model->update_data('sat_temp_appellant', $query_params,'salt', $salt);
                $additionalparty=$this->efiling_model->data_list_where('sat_temp_additional_party','salt',$salt);
	            $htmladditonalparty=$this->htmaladditionalparty($additionalparty,$salt);
	            if($data_app){
	                echo json_encode(['data'=>'success','display'=>$htmladditonalparty,'error'=>'0','4 last_query'=>$this->db->last_query()]);
	            } 
	        }
	        
	    }
        
	    
	   function htmaladditionalparty($additionalparty,$salt){
            $html='';
            $appleant="'appleant'";
	        $html.='
            <table id="example" class="display" cellspacing="0" border="1" width="100%">
	        <thead>
    	        <tr>
        	        <th>Sr. No. </th>
        	        <th>Appellant Name</th>
        	        <th>Mobile</th>
        	        <th>Email</th>
                    <th>Edit</th>
        	        <th>Delete</th>
    	        </tr>
	        </thead>
	        <tbody>';
	        
	        $html.='</tbody>
	        </table>';
	        $vals=$this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
	        if($vals[0]->pet_name){
	            $petName=$vals[0]->pet_name;
	            if (is_numeric($vals[0]->pet_name)) {
	                $orgname=$this->efiling_model->data_list_where('master_org','org_id',$vals[0]->pet_name);
	                $petName=$orgname[0]->org_name;
	            }
	            $type="'main'";
	            $html.='<tr style="color:green">
                           <td> 1</td>
                	        <td>'.$petName.'(A-1)</td>
                	        <td>'. $vals[0]->petmobile.'</td>
                	        <td>'.$vals[0]->pet_email.'</td>
                	        <td><input type="button" name="nextsubmit" id="nextsubmit" value="Edit" class="btn1" data-toggle="modal" data-target="#exampleModal"  onclick="editParty('.$vals[0]->salt.','.$appleant.','.$type.')"></td>
                            <td></td>
                        </tr>';
	        }
             if(!empty($additionalparty)){
    	        $i=2;
    	        foreach($additionalparty as $val){
    	            $app="'appleant'";
    	            $petName=$val->pet_name;
    	            if (is_numeric($val->pet_name)) {
    	               $orgname=$this->efiling_model->data_list_where('master_org','org_id',$val->pet_name);
    	               $petName=$orgname[0]->org_name;
    	            }
    	            
    	            $type="'add'";
    	        $html.='<tr>
            	        <td>'.$i.'</td>
            	        <td>'.$petName.'(A-'.$i.')</td>
            	        <td>'.$val->pet_mobile.'</td>
            	        <td>'.$val->pet_email.'</td>
            	        <td><input type="button" name="nextsubmit" id="nextsubmit" value="Edit" class="btn1"  data-toggle="modal" data-target="#exampleModal" onclick="editParty('.$val->id.','.$appleant.','.$type.')"></td>
                        <td><input type="button" name="nextsubmit" id="nextsubmit" value="Delete" class="btn1" onclick="deleteParty('.$val->id.','.$appleant.')"></td>
            	        </td>
        	        </tr>';
    	        $i++;
    	        }
             } 
       
	        return $html;
	    }	    
	    
	    function respondentSubmit(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $this->form_validation->set_rules('tabno','Please enter tab number','trim|required|min_length[1]|max_length[2]|htmlspecialchars|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        if($user_id){
	            $salt=$this->session->userdata('salt');
    			$query_params = array(
    				'user_id'=>$user_id,
    			    'tab_no'=>$this->input->post('tabno'),
    			);
    			$st=$this->efiling_model->update_data('sat_temp_appellant', $query_params,'salt', $salt);
    			if($st){
    				echo json_encode(['data'=>'success','display'=>'','error'=>'0']);
    			}
    			else {
    				echo json_encode(['data'=>'','display'=>'','error'=>'Query error in line no '.__LINE__]);
    			}
	        }
	    }	    

	  function addMoreRes(){
	        $tokenno=$this->session->userdata('tokenno');
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $salt=$this->session->userdata('salt');
	        $token=$this->input->post('token');
	        if($tokenno==$token){
	            echo json_encode(['data'=>'error','display'=>'You are not valid','error'=>'1']);
	        }
	        $this->form_validation->set_rules('salt','Please enter salt ','trim|required|min_length[1]|max_length[9]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        
	        $this->form_validation->set_rules('org','Please select date ','trim|required|min_length[1]|max_length[1]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('resState','Please select state','trim|required|min_length[1]|max_length[4]|htmlspecialchars|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('resDis','Please select district','trim|required|min_length[1]|max_length[3]|htmlspecialchars|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        
	        $this->form_validation->set_rules('respincode','Enter Pin number','trim|min_length[6]|htmlspecialchars|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('resMobile','Enter mobile number.','trim|required|min_length[10]|htmlspecialchars|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        
	        $this->form_validation->set_rules('resEmail','Enter email address.','trim|required|valid_email|min_length[1]|max_length[50]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('resPhone','Enter phone.','trim|min_length[1]|max_length[11]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('resAddress','Enter  address.','trim|min_length[1]|max_length[150]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('resFax','Enter fax.','trim|min_length[1]|max_length[11]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        
	        $petname =$this->input->post('resName');
	        if(is_numeric($petname)){
	            $hscquery = $this->efiling_model->data_list_where('master_org','org_id',$petname);
	            $petname = $hscquery[0]->orgdisp_name;
	        }
	        $query=$this->db->query("select resname from sat_temp_appellant where salt='$salt'");
	        $partno= $query->result();
	        $pval=$partno[0]->resname;
	        if($pval!=''){
    	        $query=$this->db->query("Select max(partysrno :: INTEGER) as partysrno from sat_temp_additional_res where salt='$salt'");
    	        $partno= $query->result();
    	        if($partno[0]->partysrno ==""){
    	            $partcount=2;
    	        }else{
    	            $partcount=$partno[0]->partysrno+1;
    	        }
    	        $partyid='';
    	        if($this->input->post('org')=='1'){
    	            $partyid= $this->input->post('orgid');
    	        }
    	        if($this->input->post('org')=='2'){
    	            $partyid=$this->input->post('resName'); 
    	        }
	            $query_params = array(
	                'salt' =>$this->input->post('salt'),
	                'res_name' =>$petname,
	                'res_type' =>$this->input->post('org'),
	                'res_address' =>$this->input->post('resAddress'),
	                'res_code' =>$this->input->post('respincode'),
	                'res_state' =>$this->input->post('resState'),
	                'res_dis' =>$this->input->post('resDis'),
	                'res_mobile' =>$this->input->post('resMobile'),
	                'res_phone' =>$this->input->post('resPhone'),
	                'res_email' =>$this->input->post('resEmail'),
	                'res_fax' =>$this->input->post('resFax'),
	                'partysrno'=>$partcount,
	                'party_id'=>$partyid,
	                'partysrno'=>$partcount,
	                'user_id'=>$user_id,
	                'partyType'=>$this->input->post('org'),
	            );
	            $st=$this->efiling_model->insert_query('sat_temp_additional_res',$query_params);
	            $additionalresparty=$this->efiling_model->data_list_where('sat_temp_additional_res','salt',$salt);
	            $htmlrespondentparty=$this->htmaladditionalrespondentparty($additionalresparty,$salt);
	            if($st){
	                echo json_encode(['data'=>'success','display'=>$htmlrespondentparty,'error'=>'0']);
	            }
	        }else{
	            $orgres=$this->input->post('orgres');
	            $this->session->set_userdata('orgres',$orgres);
	            $query_params = array(
	                'salt' =>$this->input->post('salt'),
	                'resname'=>$this->input->post('resName'),
	                'res_address'=>$this->input->post('resAddress'),
	                'res_pin' =>$this->input->post('respincode'),
	                'res_state' =>$this->input->post('resState'),
	                'res_dis' =>$this->input->post('resDis'),
	                'res_mobile' =>$this->input->post('resMobile'),
	                'res_phone' =>$this->input->post('resPhone'),
	                'res_email' =>$this->input->post('resEmail'),
	                'res_fax' =>$this->input->post('resFax'),
	                'user_id'=>$user_id,
	                'tab_no'=>$this->input->post('tabno'),
	                'res_id'=>$this->input->post('orgid'),
	            );
	            $st=$this->efiling_model->update_data('sat_temp_appellant', $query_params,'salt', $salt);
                    $additionalresparty=$this->efiling_model->data_list_where('sat_temp_additional_res','salt',$salt);
	            $htmlrespondentparty=$this->htmaladditionalrespondentparty($additionalresparty,$salt);
	            if($st){
	                echo json_encode(['data'=>'success','display'=>$htmlrespondentparty,'error'=>'0']);
	            }
	        }
	  }
	   

	    
	    function htmaladditionalrespondentparty($additionalresparty,$salt){
           $html='';  
            $html.='
            <table id="example" class="display" cellspacing="0" border="1" width="100%">
	        <thead>
    	           <th>Sr. No.</th>
                    <th>Respondent Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Edit</th>
                    <th>Delete</th>
	        </thead>
	         <tbody>';
            
		    $vals=$this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
            if($vals[0]->resname!=''){
                $petName=$vals[0]->resname;
                if (is_numeric($vals[0]->resname)) {
                    $orgname=$this->efiling_model->data_list_where('master_org','org_id',$vals[0]->resname);
                    $petName=$orgname[0]->org_name;
                }
                $type="'main'";           
                     $html.='<tr style="color:green">
                          <td>1</td>
                	        <td>'.$petName.'(R-1)</td>
                	        <td>'.$vals[0]->res_mobile.'</td>
                	        <td>'.$vals[0]->res_email.'</td>
                	        <td><input type="button" name="nextsubmit" id="nextsubmit" value="Edit" class="btn1" data-toggle="modal" data-target="#exampleModal"  onclick="editParty('.$val->id.','.$appleant.','.$type.')"></td>
                            <td></td>
                        </tr>';
                  }
               $html.='</tbody>
	        </table>';
               
               if(!empty($additionalresparty)){
                   $i=2;
                   foreach($additionalresparty as $val){
                       $appleant="'res'";
                       $type="'add'";
                       $resName=$val->res_name;
                       if (is_numeric($val->res_name)) {
                           $orgname=$this->efiling_model->data_list_where('master_org','org_id',$val->res_name);
                           $resName=$orgname[0]->orgdisp_name;
                       }
                       $html.='<tr>
            	        <td>'.$i.'</td>
            	        <td>'.$resName.'(R-'.$i.')</td>
            	        <td>'.$val->res_mobile.'</td>
            	        <td>'.$val->res_email.'</td>
            	        <td><input type="button" name="nextsubmit" id="nextsubmit" value="Edit" class="btn1" data-toggle="modal" data-target="#exampleModal"  onclick="editParty('.$val->id.','.$appleant.','.$type.')"></td>
                        <td><input type="button" name="nextsubmit" id="nextsubmit" value="Delete" class="btn1" onclick="deleteParty('.$val->id.','.$appleant.')"></td>
            	        </td>
                    </tr>';
                       $i++;
                   }
               }
               
	        return $html;
	    }

	    
	    function iasubmit(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $salt=$this->input->post('salt',true);
	        
	        $this->form_validation->set_rules('salt','Please enter salt ','trim|required|min_length[1]|max_length[9]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        
	        $this->form_validation->set_rules('tabno','Please enter tab number','trim|required|min_length[1]|max_length[2]|htmlspecialchars|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        $this->form_validation->set_rules('totalNoIA','Please enter  total No IA','trim|required|min_length[1]|max_length[2]|htmlspecialchars|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        
	        $this->form_validation->set_rules('natuer','Please enter  natuer','trim|required|min_length[1]|max_length[150]|htmlspecialchars');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        if($user_id){
    	        $this->form_validation->set_rules('filed', 'Choose valid filed ', 'trim|required|numeric|max_length[200]');
    	        $data=array(
    	            'filed_by'=>$this->input->post('filed',true),
	                'ia_nature'=>$this->input->post('natuer',true),
	                'tab_no'=>$this->input->post('tabno',true),
	                'no_of_ia'=>$this->input->post('totalNoIA',true),
    	        );
    	        if($this->form_validation->run() == TRUE) {
    	            $st=$this->efiling_model->update_data('sat_temp_appellant', $data,'salt', $salt);
        	        if($st){
        	            echo json_encode(['data'=>'success','display'=>'','error'=>'0']);
        	        }
        	    }else{
        	        echo json_encode(['data'=>'','error'=>strip_tags(validation_errors())]);
        	    }
	        }
	    }
	    
	    
	    function otherFeesave(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $salt=$this->input->post('salt',true);
	        $this->form_validation->set_rules('salt','Please enter salt ','trim|required|min_length[1]|max_length[9]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        
	        $this->form_validation->set_rules('tabno','Please enter tab number','trim|required|min_length[1]|max_length[2]|htmlspecialchars|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        if($user_id){
	            $data=array(
	                'tab_no'=>$this->input->post('tabno',true),
	            );
	            if($this->form_validation->run() == TRUE) {
	                $st=$this->efiling_model->update_data('sat_temp_appellant', $data,'salt', $salt);
	                if($st){
	                    echo json_encode(['data'=>'success','display'=>'','error'=>'0']);
	                }
	            }else{
	                echo json_encode(['data'=>'','error'=>strip_tags(validation_errors())]);
	            }
	        }
	    }
	    
	    
	    
	    function  payment_mode(){
	        $this->load->view('admin/payment_mode');
	    }
	    


		public function doc_save_next() {
			if($this->input->post()){
				$salt=htmlentities($this->input->post('salt'));
				$token=htmlentities($this->input->post('token'));
				$tabno=(int)$this->input->post('tabno');
				$ut=htmlentities($this->input->post('untak'));
				$st=$this->efiling_model->data_list_where('sat_temp_appellant','salt', $salt);
				$advytpe=$st[0]->advType;
				
				
				$this->form_validation->set_rules('salt','Please enter salt ','trim|required|min_length[1]|max_length[9]|numeric');
				if($this->form_validation->run() == FALSE){
				    echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
				}
				$this->form_validation->set_rules('tabno','Please enter tab number','trim|required|min_length[1]|max_length[2]|htmlspecialchars|numeric');
				if($this->form_validation->run() == FALSE){
				    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
				}
				
				
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
				    $subdocvalue[]=6;
				}
				$doctype=$this->efiling_model->data_list_where('master_document_efile','doctype','app');
				foreach($doctype as $doc){
				    $doctypearr[]=$doc->id;
				}
				$result=array_diff($doctypearr,$subdocvalue);
				$datatab=array('tab_no'=>$tabno,'is_undertaking'=>$ut);
	            $st1=$this->efiling_model->update_data('sat_temp_appellant', $datatab,'salt', $salt);
				if($st1)  	echo json_encode(['data'=>'success','error'=>'0']);
				else  		echo json_encode(['data'=>'Query error found in line no '.__LINE__,'error'=>'1']);
			}
			else echo json_encode(['data'=>'Invalid request found.','error'=>'1']);
		}

		
		
		
		
		
		
		
		
		public function upd_required_docs($csrf=NULL) {
			$token=hash('sha512',trim($_REQUEST['token']).'upddoc');
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $matter=$_REQUEST['matter'];
			if($_FILES && $token==$csrf && (int)$this->input->post('salt') > 0) {
				$config=[
							['field'=>'party_type', 'label'=>'Please select valid document filed by','rules'=>'trim|max_length[100]|regex_match[/^[A-Za-z_]+$/]'],
							['field'=>'req_dtype', 'label'=>'Please select valid document type','rules'=>'trim|max_length[100]|regex_match[/^[A-Za-z_\/)(\ ]+$/]'],
							['field'=>'userfile', 'label'=>'','rules'=>'trim|max_length[200]|callback_mimeType']
						];
	
				$this->form_validation->set_rules($config);		
				if($this->form_validation->run()==FALSE) {			
					  $returnData=['data'=>'','error' => strip_tags(validation_errors())];
					  echo json_encode($returnData); exit(); 
	
				 } else {
					
				  $fl_path='./upload_doc/efiling/';
				  $schemas='delhi/';
				  $step1=$fl_path.$schemas;

				  $salt=(int)$this->input->post('salt');
				  $step2=$step1.$salt.'/';
				  $typeval=$this->input->post('type');
				  $submittype=$this->input->post('submittype');
				  $docvalid=$this->input->post('docvalid');
				  $pty=$this->input->post('party_type');
				  $step3=$step2.$pty.'/';

				  $rqd_flnm=strtr($this->input->post('req_dtype'),['/'=>'_',' '=>'_']);

				  if(!is_dir($step1)) {
					  mkdir($step1, 0777, true);
				  }

				  if(!is_dir($step2)) {
					  mkdir($step2, 0777, true);
				  }

				  if(!is_dir($step3)) {
					  mkdir($step3, 0777, true);
				  }
				  
				  $valume='';
				  if($docvalid==2){
				      $valume='1';
				  }
				  $array=array('docid'=>$docvalid,'salt'=>$salt);
				  $valexit=$this->efiling_model->data_list_mulwhere('temp_documents_upload',$array);
				  if(!empty($valexit)){
				      foreach($valexit as $fv){
				          if($docvalid==2){
				              $valume='1';
    				          if($fv->valumeno!=''){
    				              $valume = (int)$fv->valumeno+1;
    				          }
				          }
				      }
				  }
				  
				  $docname=$_FILES['userfile']['name'];
				  
				  $array=explode('.',$_FILES['userfile']['name']);
				  if(substr_count($_FILES['userfile']['name'],'.')>1){
				      echo json_encode(['data'=>'','error' =>'File should have only single dot (.) extenction.']);die;
				  }
				  
				  
				  $docname=$_FILES['userfile']['name'];
				  $array=explode('.',$_FILES['userfile']['name']);
				  $config['upload_path']   		= $step3;
				  $config['allowed_types'] 		= 'jpg|jpeg|gif|png|pdf';
				  $config['max_size']      		= '199999';
				  $config['overwrite']	   		= TRUE;
				  $config['file_ext_tolower']	= TRUE;
				  $config['file_name']=$rqd_flnm.'_'.date('dmYHis').'.'.strtolower(end($array));
	
				  $this->load->library('upload', $config);
				  if(!$this->upload->do_upload('userfile') && $this->upload->data('file_name'))
						  echo json_encode(['data'=>'','error' => strip_tags($this->upload->display_errors()),'file_name'=>'']);
				  else 	{
					$final_doc_url=$step3.$config['file_name'];
					$pages=$this->countPages($final_doc_url);
						$data=array(
							'salt' 					=>$salt, 
							'user_id' 				=>$user_id, 
							'document_filed_by' 	=>$pty,
						    'matter' 	            =>$matter,
						    'no_of_pages'           =>$pages,
							'document_type' 		=>$rqd_flnm,
							'file_url' 				=>$final_doc_url,
						    'doc_type' 				=>$typeval,
						    'submit_type'           =>$submittype,
						    'docid'                 =>$docvalid,
						    'doc_name'              =>$docname,
						    'valumeno'              =>$valume,
						);

						$st=$this->efiling_model->insert_query('temp_documents_upload',$data);
					  	echo json_encode(['data'=>'success','error' => '0','file_name'=>$final_doc_url]);
				  }
				}
			}
			else 
				echo json_encode(['data'=>'Invalid request foud, try with valid details','error' => '1','file_name'=>'']);
		}
	    
		
		function countPages($path) {
		    $pdftext = file_get_contents($path);
		    $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
		    return $num;
		}
		
		
	    function addMoredd(){ 
	        $payid=$_REQUEST['payid'];
	        if($payid!=''){  
	            $st=$this->efiling_model->delete_event('sat_temp_payment', 'id', $payid);
	        }else{
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
    	        $salt=htmlspecialchars($_REQUEST['salt']);
    	        $dbankname=htmlspecialchars($_REQUEST['dbankname']);
    	        $amountRs=htmlspecialchars($_REQUEST['amountRs']);
    	        $totalam=htmlspecialchars($_REQUEST['totalam']);
    	        $ddno=htmlspecialchars($_REQUEST['ddno']);
    	        $ddate=htmlspecialchars($_REQUEST['dddate']);
    	        $bd=htmlspecialchars($_REQUEST['bd']);
    	        if($payid =='') {
        	        $query_params=array(
        	            'salt'=>$salt,
        	            'payment_mode'=>$bd,
        	            'branch_name'=>$dbankname,
        	            'dd_no'=>$ddno,
        	            'dd_date'=>$ddate,
        	            'amount'=>$amountRs,
        	            'total_fee'=>$totalam,
        	            
        	        );
    	          $st=$this->efiling_model->insert_query('sat_temp_payment',$query_params);
    	        }
	        }
	        $bd=$_REQUEST['bd'];
	     if($bd==3){
	            $bankname="Name";
	            $dd="challan/Ref. No";
	            $date="Date of Transction";
	            $amount="Aomunt in Rs.";
	        }
	        $html='';
	        if($row->dd_no != 'undefined') { $vals= htmlspecialchars($row->dd_no); }
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
                    $html.='<tr id="id'.$id.'">
                        <td>'.$row->branch_name.'</td>
                        <td>'.$row->dd_no.'</td>
                        <td>'.$row->dd_date.'</td>   
                        <td>'.$row->amount.'</td>
                        <td><input type="button" value="Delete"  class="btn1" onclick="deletePay('.$id.')"/></td>
                     </tr>';
            		}
                    $html.='</table>  
            		<div class="Cell">
                        <p><font color="#510812" size="3">Total Rs</font></p>
                        <p class="custom"><font color="#510812" size="3">'.htmlspecialchars($sum).'</font></p>
                       <input type="hidden" name="collectamount" id="collectamount" value="'.htmlspecialchars($sum).'">
                    </div>';
                  
            	
            		echo json_encode(['data'=>'success','value'=>'','display'=>$html,'error'=>'0']);
	    }
	    
	    function payfeedetailsave(){
	        $salt=  htmlspecialchars($_REQUEST['salt']);
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $tabno= $this->input->post('tabno'); 
    	        $this->form_validation->set_rules('salt','Please enter salt ','trim|required|min_length[1]|max_length[9]|numeric');
    	        if($this->form_validation->run() == FALSE){
    	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
    	        }
    	        $this->form_validation->set_rules('tabno','Please enter tab number','trim|required|min_length[1]|max_length[2]|htmlspecialchars|numeric');
    	        if($this->form_validation->run() == FALSE){
    	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
    	        }
    	        $datatab=array(
    	            'tab_no'=>$tabno,
    	        );
    	        $stss=$this->efiling_model->update_data('sat_temp_appellant', $datatab,'salt', $salt);
    	        if($salt!=''){
    	           echo json_encode(['data'=>'success','value'=>'','display'=>'','error'=>'0']);
    	        }else{
    	            echo json_encode(['data'=>'','value'=>'','display'=>'','error'=>'Some thing error ']);
    	        }
	        }
	    }
	    
	    function feeCalculation(){
	        $noOfRes=htmlspecialchars($_REQUEST['resexp']);
	        $salt=htmlspecialchars($_REQUEST['salt']);
	        $total=htmlspecialchars($_REQUEST['total']);
	        
	        $this->form_validation->set_rules('resexp', 'Choose value numeric', 'trim|required|numeric|max_length[200]');
	        $this->form_validation->set_rules('total', 'total value is numeric', 'trim|required|numeric|max_length[200]');
	        if($this->form_validation->run() == TRUE) { 
	           $total1=0;
	           if($noOfRes!=""){
    	            $feesd=$this->efiling_model->data_list_where('master_fee_detail','doc_code',$feecode[$i]);
    	            $data=array(
    	                'amount_collective'=>$noOfRes,
    	            ); 
    	            $st=$this->efiling_model->update_data('sat_temp_appellant', $data,'salt', $salt);
    	            $feesd=$this->efiling_model->data_list_where('sat_temp_payment','salt',$salt);
    	            if(!empty($feesd)){
        	            foreach($feesd as $row){
        	                $amount=$row->amount;
        	                $sum=$sum+$amount;
        	            } 
    	            }
    	            if($sum!=""){
    	                $tot=$total-$noOfRes;
    	                $total1=$tot-$sum;
    	            }else{
    	                $total1=$total-$noOfRes;
    	            }
    	        }
    	        echo json_encode(['data'=>'success','value'=>$total1,'display'=>'','error'=>'0']);
	        }else{
	            echo json_encode(['data'=>'','error'=>strip_tags(validation_errors())]);
	        }
	    }
	    
	    
	    function efilingfinalsubmit(){
	        $bench='100';
	        $subBench='1';
	        $tokenval=$this->session->userdata('submittoken');
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $salt=$this->session->userdata('salt'); 
	        if($user_id==''){
	            if($this->form_validation->run() == FALSE){
	                echo json_encode(['data'=>'error','value'=>'','display'=>'Request not valid','error'=>'1']);die;
	            }
	        }
	        $this->form_validation->set_rules('ddno','Please enter NTRP number ','trim|required|min_length[1]|max_length[13]|is_unique[sat_temp_payment.dd_no]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        
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
	        
	        $token=$this->input->post('token');
	        if($tokenval==$token){
	            $benchCode= htmlspecialchars(str_pad($bench,3,'0',STR_PAD_LEFT));
	            $subBenchCode= htmlspecialchars(str_pad($subBench,2,'0',STR_PAD_LEFT));
	            $curYear = date('Y');
	            $curMonth = date('m');
	            $curDay = date('d');
	            $curdate="$curYear-$curMonth-$curDay"; 
	            $st=$this->efiling_model->data_list_where('year_initialization','year',$curYear); 
	            $filing_no1=$st[0]->filing_no;
	            if($filing_no1 ==0){
	                $filing_no ='000001';
	                $fil_no =1;
	            }
	            if($filing_no1 !=0){
	                $fil_no =(int)$filing_no1+1;
	                $filing_no = (int)$filing_no1+1;
	                $len = strlen($filing_no);
	                $length =6-$len;
	                for($i=0;$i<$length;$i++){
	                    $filing_no = "0".$filing_no;
	                }
	            }
	            $filing_no=$benchCode.$subBenchCode.$filing_no.$curYear;
	            $caseType='';
	            $res_type=1;
	            $status='P';
	            $pet_type=1;
	            $advCode=0;  
	            $idorg='';
	            $typefiledres='';
	            $sql_temp=$this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt); 
	            $pet_name='';
	            $resname='';
	            if(!empty($sql_temp)){
    	            foreach($sql_temp as $row){
    	                $idorg=$row->pet_type;
    	                $caseType1=$row->apeal_type;
    	                if($caseType1=='SEBI'){
    	                    $caseType='1';
    	                }
    	                if($caseType1=='IRDA'){
    	                    $caseType='2';
    	                }
    	                if($caseType1=='PFRDA'){
    	                    $caseType='3';
    	                }
    	                
    	                $typefiledres=$row->res_type;
        	           if(!empty($sql_temp) && is_array($sql_temp)){
        	                if($idorg==1){
        	                    $orgid=$row->pet_name;   
        	                    $pet_name=$row->pet_name;
        	                    if (is_numeric($row->pet_name)) {
            	                    $storg =$this->efiling_model->data_list_where('master_org','org_id',$orgid);  
            	                    $pet_name=$storg[0]->org_name;
        	                    }
        	                }
    
        	                if($row->pet_council_adv==""){
        	                    $advCode=$advCode;
        	                }else {
        	                    $advCode=$row->pet_council_adv;
        	                }
        	                
    	                    $orgid1=$row->resname;
    	                    $resname=$row->resname;
    	                    if (is_numeric($row->resname)) {
        	                    $storg1 =$this->efiling_model->data_list_where('master_org','org_id',$orgid1);  
        	                    $resname=$storg1[0]->org_name;
    	                    }
    
        	                if($row->res_council_adv==""){
        	                    $resadv=$advCode;
        	                }else{
        	                    $resadv=$row->res_council_adv;
        	                }
        	                
        	                if($row->res_dis=="") {
        	                    $dis='999';
        	                }else{
        	                    $dis=$row->res_dis;
        	                }
    
        	                $iaNature=$row->ia_nature;
        	                $filed_by=$row->filed_by;
            	            $sql_detail=array(
            	                'filing_no'=>$filing_no,
            	                'case_type'=>$caseType,
            	                'dt_of_filing'=>$curdate,
            	                'pet_type'=>$pet_type,
            	                'pet_name'=>$pet_name,
            	                'pet_adv'=>$advCode,
            	                'pet_address'=>$row->pet_address,
            	                'pet_state'=>$row->pet_state,
            	                'pet_district'=>$row->pet_dist,
            	                'pet_pin'=>$row->pincode,
            	                'pet_mobile'=>$row->petmobile,
            	                'pet_phone'=>$row->petphone,
            	                'pet_email'=>$row->pet_email,
            	                'pet_fax'=>$row->pet_fax,
            	                'pet_counsel_address'=>$row->counsel_add,
            	                'pet_counsel_pin'=>$row->counsel_pin,
            	                'pet_counsel_mobile'=>$row->counsel_mobile,
            	                'pet_counsel_phone'=>$row->counsel_phone,
            	                'pet_counsel_email'=>$row->counsel_email,
            	                'pet_counsel_fax'=>$row->counsel_fax,
            	                'matters'=>isset($row->matters)?$row->matters:'',
            	                'res_type'=>$res_type,
            	                'res_name'=>$resname,
            	                'res_adv'=>'1',
            	                'act'=>$row->act,
            	                'res_address'=>$row->res_address,
            	                'res_state'=>$row->res_state,
            	                'res_district'=>$dis,
            	                'res_pin'=>$row->res_pin,
            	                'res_email'=>$row->res_email,
            	                'res_mobile'=>$row->res_mobile,
            	                'res_phone'=>$row->res_phone,
            	                'res_fax'=>$row->res_fax,
            	                'res_counsel_address'=>$row->res_counsel_address,
            	                'res_counsel_pin'=>$row->res_counsel_pin,
            	                'res_counsel_mobile'=>$row->res_counsel_mob,
            	                'res_counsel_phone'=>$row->res_counsel_phone,
            	                'res_counsel_email'=>$row->res_counsel_email,
            	                'res_counsel_fax'=>$row->res_counsel_fax,
            	                'status'=>$status,
            	                'no_of_app'=>$row->no_of_app,
            	                'no_of_res'=>$row->no_of_res,
            	                'user_id'=>$row->user_id,
            	                'filed_user_id'=>$row->user_id,
            	                'no_of_impugned'=>$row->no_of_impugned,
            	                'pet_id'=>$row->pet_id,
            	                'res_id'=>$row->res_id,
            	                'opauthority'=>$row->opauthority,
            	                'iorderdate'=>$row->iorderdate,
            	                'rimpugnedorder'=>$row->rimpugnedorder,
            	                'iordernumber'=>$row->iordernumber,
            	                'delayinfiling'=>$row->delayinfiling,
            	                'apeal_type'=>$row->apeal_type,
            	                'ipenalty'=>$row->ipenalty,
            	                'appage'=>$row->appage,
            	                'appfather'=>$row->appfather,
            	                'apppan'=>$row->apppan,
            	                'resage'=>$row->age,
            	                'idtype'=>$row->idtype,
                           );
            	            $st=$this->efiling_model->insert_query('sat_case_detail',$sql_detail);
            	            $data=array(
            	                'filing_no'=>$fil_no,
            	            );
        	              $st=$this->efiling_model->update_data('year_initialization', $data,'year', $curYear);     
        	            }
    	            }
	            }
	            //additional applant   insert   
	            $additionalpet =$this->efiling_model->data_list_where('sat_temp_additional_party','salt',$salt); 
	            if(!empty($additionalpet)){
    	            foreach($additionalpet as $row){
                      $tempValueadd=array(
                        'filing_no'=>$filing_no,
             		     'party_flag'=>'1',
             		     'pet_name'=>$row->pet_name,
                         'pet_degingnation'=>$row->pet_degingnation,
             		     'pet_address'=>$row->pet_address,
                         'pin_code'=>$row->pin_code,
             		     'pet_state'=>$row->pet_state,
                         'pet_dis'=>$row->pet_dis,
             		     'pet_mobile'=>$row->pet_mobile,
                         'pet_phone'=>$row->pet_phone,
             		     'pet_email'=>$row->pet_email,
                         'pet_fax'=>$row->pet_fax,
		                 'partysrno'=>$row->partysrno,
                         'partyType'=>$row->partyType,
                         'partyreff'=>$row->party_id,
                         'user_id'=>$user_id,
                         'entry_date'=>date('Y-m-d'),
                 		);                      
                      $st=$this->efiling_model->insert_query('additional_party',$tempValueadd); 
    	            }
	            }
	            
	          //additional advocate insert   
	            $stadv=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt); 
	            if(!empty($stadv)){
	                foreach($stadv as $stadv){
    	                $sqlAdditionalAdv=array(
    	                    'filing_no'=>$filing_no,
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

	            //additional respondent  insert   
	            $res =$this->efiling_model->data_list_where('sat_temp_additional_res','salt',$salt); 
	            if(!empty($res)){
    	            foreach ($res as  $row){
                     $resdata=array(
                        'filing_no'=>$filing_no,
                    	'party_flag'=>'2',
                    	'pet_name'=>$row->res_name,
                    	'pet_degingnation'=>$row->res_degingnation,
                        'pet_address'=>$row->res_address,
                    	'pin_code'=>$row->res_code,
                        'pet_state'=>$row->res_state,
                    	'pet_dis'=>$row->res_dis,
                        'pet_mobile'=>$row->res_mobile,
                    	'pet_phone'=>$row->res_phone,
                        'pet_email'=>$row->res_email,
                    	'pet_fax'=>$row->res_fax,
			            'partysrno'=>$row->partysrno,
                        'partyType'=>$row->partyType,
                        'partyreff'=>$row->party_id,
                        'user_id'=>$user_id,
                        'entry_date'=>date('Y-m-d'),
	                 );
                     $st=$this->efiling_model->insert_query('additional_party',$resdata); 
    	            }   
	            }
	            //Fee Calculation 
                $account=array(
                    'salt'=>$salt,
                    'createdate'=>$curdate,
                    'total_fee'=>$_REQUEST['total_amount_amount'],
                    'payment_mode'=>$_REQUEST['bd'],
                    'branch_name'=>$_REQUEST['dbankname'],
                    'dd_no'=>$_REQUEST['ddno'],
                    'dd_date'=>$_REQUEST['dddate'],
                    'amount'=>$_REQUEST['amountRs'],
                );
                $st=$this->efiling_model->insert_query('sat_temp_payment',$account); //New Offline payment code
                $norespondent='';
                $iaFee1='';
                $otherFee2='';
                $total='';
	            $st=$this->efiling_model->data_list_where('sat_temp_appellant','salt', $salt); 
	            $sql_tempss=$this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt); 
	            $act = $sql_tempss[0]->act;
	            $hscqueryact11 =$this->efiling_model->data_list_where('master_act','act_code',$act);
	            $fee = isset($hscqueryact11[0]->fee)?$hscqueryact11[0]->fee:'';
	            if(!empty($st)){
    	            $noofimpugned=$st[0]->no_of_impugned;
    	            $ia=$st[0]->no_of_ia;
    	            $norespondent=$st[0]->no_of_res;
    	            $fee=$this->session->userdata('efilingFeeData');
    	            $iaFee1= @$fee['iaFee1'];
    	            $otherFee2=@$fee['otherFee2'];
    	            $st=$this->efiling_model->data_list_where('sat_temp_additional_res','salt', $salt);
    	            $rescount=count($st)+1;
    	            $resamoubnt=0;
    	            if($rescount>4){
    	                $resamoubnt=($rescount-4)*$fee;
    	            }
    	            $appealFee= (int)$fee*$noofimpugned+$resamoubnt;
    	            $total=@$appealFee+$iaFee1+$otherFee2;
	            }
	    
	            $recamount=0;
	            $pay=$this->efiling_model->data_list_where('sat_temp_payment','salt', $salt);
	            foreach($pay as $payval){
                    $account=array(
                        'total_fee'=>$total,
                        'payment_mode'=>$payval->payment_mode,
                        'branch_name'=>$payval->branch_name,
                        'dd_no'=>$payval->dd_no,
                        'dd_date'=>$payval->dd_date,
                        'amount'=>$payval->amount,
                        'salt'=>$payval->salt,
                        'ia_fee'=>$iaFee1,
                        'other_fee'=>$otherFee2,
                        'other_document'=>'',
                        'fee_type'=>'OF',
                        'user_id'=>$user_id,
                        'entry_date'=>date('Y-m-d'),
                    );
                    $recamount +=(int)$payval->amount;
                    $st=$this->efiling_model->insert_query('sat_temp_payment',$account); //New Offline payment code
	            }

	           //Document Filing  
	            $st=$this->efiling_model->data_list_where('temp_documents_upload','salt',$salt); 
	            if(!empty($st)){
					foreach($st as $vals){
						$data12=array(
							'filing_no'=>$filing_no,
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
	       
	            $iafee='500';
	            $html='';
                $printIAno='';
	            if($iafee > 0 && $iafee!=0) {
 	                $feecode=explode(",",$iaNature);
	                if($feecode[0]!=""){
	                    $len=sizeof($feecode)-1;
	                    for($k=0;$k<$len;$k++){
	                        $ia_nature=$feecode[$k];
	                        if($ia_nature==12){
	                            $matter=htmlspecialchars($_REQUEST['matt']);
	                        }else{
	                            $matter="";
	                        }
							$curYear=date('Y');
	                        $year_ins=$this->efiling_model->data_list_where('ia_initialization','year',$curYear);
	                        $ia_filing_no=$year_ins[0]->ia_filing;
							
	                        if($ia_filing_no ==0){
	                            $iaFilingNo=1;
	                            $filno ='000001';
	                        }
							
	                        if($ia_filing_no!='' || $ia_filing_no!=0){
	                            $iaFilingNo =(int)$ia_filing_no+1;
	                            $ia_filing_no=(int)$ia_filing_no+1;
	                            $length =6-strlen($ia_filing_no);
	                            for($i=0;$i<$length;$i++){
	                                $ia_filing_no= "0".$ia_filing_no;
	                            }
	                        }
	                        $iaFiling_no1=$benchCode.$subBenchCode.$ia_filing_no.$curYear;
	                      //  $printIAno=0;
	                        if (is_numeric($ia_nature)) {
	                           $datatta =$this->efiling_model->data_list_where('moster_ma_nature','nature_code',$ia_nature);
	                        }
	                        $datatta_name = $datatta[0]->nature_name;
	                        $printIAno=$printIAno."MA/".$iaFilingNo."/".$curYear." ( " .$datatta_name .")<br>";
	                        $ia=array(
	                            'ia_no'=>$iaFilingNo,
	                            'filing_no'=>$filing_no,
	                            'filed_by'=>$filed_by,
	                            'entry_date'=>$curdate,
	                            'display'=>'Y',
	                            'ia_filing_no'=>$iaFiling_no1,
	                            'ia_nature'=>$ia_nature,
	                            'status'=>$status,
	                            'matter'=>$matter,
	                        );
	                        $st=$this->efiling_model->insert_query('satma_detail',$ia); 
	                        $data=array(
	                            'ia_filing'=>$iaFilingNo,
	                        );
	                        $st=$this->efiling_model->update_data('ia_initialization', $data,'year', $curYear);
	                    }
	                } 
	            }
				if($filing_no!=''){
					$val= substr($filing_no,-8);
					$a=substr_replace($val ,"",-4);
					$b= substr($val, -4);
					$valfilingno= $a.'/'.$b;
				}    
				
	            $dat=array('filing_no'=>$filing_no);
	            $st=$this->efiling_model->insert_query('scrutiny',$dat); 
	            $data['filingformate']=$valfilingno;
	            $data['filingno']=$filing_no;
	            $data['iafiling']=$printIAno;
	            $data['htmlia']=$html;
	            
	            $this->session->set_userdata('apealdata',$data);
    	         if($filing_no!=""){
    	           $delete= $this->efiling_model-> delete_event('temp_documents_upload', 'salt', $salt);
    	           $delete= $this->efiling_model-> delete_event('sat_temp_appellant', 'salt', $salt);
    	           $pay= $this->efiling_model-> delete_event('sat_temp_payment', 'salt', $salt);
    	           $pet= $this->efiling_model-> delete_event('sat_temp_additional_party', 'salt', $salt);
    	           $res= $this->efiling_model-> delete_event('sat_temp_additional_res', 'salt', $salt);
    	           $res= $this->efiling_model-> delete_event('sat_temp_add_advocate', 'salt', $salt);
                   $this->session->unset_userdata('salt');
                   $this->session->unset_userdata('saltNo');  
    	        } 
                echo json_encode(['data'=>'success','display'=>'success','error'=>'0']);
	        }else{
	            echo json_encode(['data'=>'error','display'=>$html,'error'=>'Request not valid']);
	        }
	    }
	    
	    
	    function fhressuccess(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $this->load->view("admin/fhresssuccess");
	        }
	    }
	   
	    function draft_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $this->session->unset_userdata('filingnosession');
	            $data['draft']= $this->efiling_model->data_list_where('sat_temp_appellant','user_id',$user_id);
	            $this->load->view("admin/draft_list",$data);
	        }
	    }
	    
	    
	    function filedcase_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['filedcase']= $this->efiling_model->data_list('sat_case_detail');
	            $this->load->view("admin/filedcase_list",$data);
	        }
	    }
	    

	    
	    function ia_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){ 
	            $this->load->view("admin/ia_list");
	        }
	    }
	    
	    function rpepcp_filed_list(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	        $data['rpepcp']= $this->efiling_model->data_list_rpepcp('sat_case_detail','user_id',$user_id);
	        if($user_id){
	            $this->load->view("admin/rpepcp_filed_list",$data);
	        }
	    }
	    
	    function rpepcp_draftcase_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $data['filedcase']= $this->efiling_model->data_list_where('rpepcp_reffrence_table','user_id',$user_id);
	        if($user_id){
	            $this->load->view("admin/rpepcp_draftcase_list",$data);
	        }
	    }
	    
	    function rpepcppage(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id!=''){
	            $salt=$_REQUEST['reffrenceno'];
	            $type=$_REQUEST['type'];
	            $this->session->set_userdata('reffrenceno',$salt);
	            $this->session->set_userdata('type',$type);
	            echo json_encode(['data'=>'success','display'=>$html,'error'=>'0']);
	        }else{
	            echo json_encode(['data'=>'success','display'=>$html,'error'=>'User Not Valid']);
	        }
	    }
	    
	    
	    function doc_case_filed_case(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	        $array=array('user_id'=>$user_id);
	        $data['mf']= $this->efiling_model->select_in('document_filing',$array);
			//$array=array('user_id'=>$user_id);
	       // $data['ma']= $this->efiling_model->select_in('ma_detail',$array);
			//$array=array('user_id'=>$user_id);
	       // $data['va']= $this->efiling_model->select_in('vakalatnama_detail',$array);
	        if($user_id){
	            $this->load->view("admin/doc_case_filed_case",$data);
	        }
	    }
	    
	    function doc_draftcase_list(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	        $array=array('case_type'=>'DOC','user_id'=>$user_id);
	        $data['filedcase']= $this->efiling_model->select_in('rpepcp_reffrence_table',$array);
	        if($user_id){
	            $this->load->view("admin/doc_draftcase_list",$data);
	        }
	    }
	    
	    
	    
	    function ia_filed_case(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	       // $array=array('case_type'=>'IA','user_id'=>$user_id);
	       // $data['filedcase']= $this->efiling_model->select_in('rpepcp_reffrence_table',$array);
	        if($user_id){
	            $this->load->view("admin/ia_filed_case");
	        }
	    }
	    
	    function ia_draftcase_list(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=$_REQUEST['filingno'];
	        $user_id=$userdata[0]->id;
	        $array=array('user_id'=>$user_id);
	        $data['iadraftcase']= $this->efiling_model->select_in('temp_iadetail',$array);
	        if($user_id){
	            $this->load->view("admin/ia_draftcase_list",$data);
	        }	
	    }
	    
	    
	  

	  function deleteParty(){
	        $userdata=$this->session->userdata('login_success');
	        $data=$_REQUEST;
	        $id = $this->input->post('id');
	        $this->form_validation->set_rules('id','Please select id','trim|required|min_length[1]|max_length[4]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        

	        $this->form_validation->set_rules('party','Please select id','trim|required|min_length[1]|max_length[10]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        $this->form_validation->set_rules('salt','Please select id','trim|required|min_length[1]|max_length[8]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $this->load->view("admin/deleteParty",$data);
	        }
	    }

          function org(){
    	    $q= $this->input->post('q');
    	    $this->form_validation->set_rules('q','Please select id','trim|required|min_length[1]|max_length[4]|numeric');
    	    if($this->form_validation->run() == FALSE){
    	        echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
    	    }
    	    //$q=1;
    	    if ($q != 0) {
    	        $output = array();
    	        $sql1 = $this->efiling_model->data_list_where('master_org','org_id',$q);
    	        foreach ($sql1 as $row) {
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
    	            $st = $this->efiling_model->data_list_where('master_psstatus','state_code',$stateCode);
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
    	            $users_arr[] = array("org_name"=>$org_name,"address" => $add, "mob" => $mob, "mail" => $mail, "ph" => $ph, "pin" => $pin, "fax" => $fax, "stcode" => $stateCode, "stname" => $statename, "dcode" => $distcode, "dname" => $distname, "desg" => $orgdesg);   
    	        }
    	        echo json_encode($users_arr);
    	    }
	    }
	    


 	 function filing_ajax(){
	        if ($_REQUEST['action'] == 'check_caveat_data') {
	            $val = explode("-", $_REQUEST['decision_date']);
	            $dataa = $val[2] . '-' . $val[1] . '-' . $val[0];
	            $case_no = $_REQUEST['case_no'];
	            $case_year = $_REQUEST['case_year'];
	            $commission = $_REQUEST['commission'];
	            $qu_caveat_detail = "select caveat_name,caveat_filing_no,  commission, nature_of_order, case_no, case_year, decision_date, council_name,caveat_filing_date from caveat_detail  where case_no = '$case_no' and case_year = '$case_year' and decision_date = '$dataa' and commission = '$commission' ";
    
  $query=$this->db->query($qu_caveat_detail);
	            $data = $query->result();
	            if (!empty($data) && is_array($data)) {
	                foreach ($data as $val_dataa) {
	                    $caveat_no = ltrim(substr($val_dataa->caveat_filing_no, 5, 6), 0);
	                    $caveat_date = $val_dataa->caveat_filing_date;
	                    $caveat_counsil = $val_dataa->council_name;
	                    $caveat_name = $val_dataa->caveat_name;
	                    $qu_master_advocate = "SELECT adv_name FROM public.master_advocate where adv_code ='$caveat_counsil'";
	                    $query=$this->db->query($qu_master_advocate);
	                    $data = $query->result();
	                    $adv_name = $data[0]->adv_name;
	                    echo " <b>Caveat No : </b> $caveat_no <br> <b> Date of Caveat Filing : </b> $caveat_date <br> <b> Filed By : </b> $adv_name <br> <b> Caveator Name : </b> $caveat_name<br><br>";
	                }
	            } else {
	                echo 'Not Data Found';
	            }
	        }
	    }


         function addMoreddrpepcp(){
	        $payid=$_REQUEST['payid'];
	        if($payid!=''){
	            $st=$this->efiling_model->delete_event('sat_temp_payment', 'id', $payid);
	        }else{
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
	            
	            $this->form_validation->set_rules('dbankname','Please Enter bank name','trim|required');
	            if($this->form_validation->run() == FALSE){
	                echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	            }  
	            
    	        $salt=htmlspecialchars($_REQUEST['salt']);
    	        $dbankname=htmlspecialchars($_REQUEST['dbankname']);
    	        $amountRs=htmlspecialchars($_REQUEST['amountRs']);
    	        $totalamount=htmlspecialchars($_REQUEST['totalamount']);
    	        $remainamount=htmlspecialchars($_REQUEST['remainamount']);
    	        $filing_no=htmlspecialchars($_REQUEST['filing_no']);
    	        $type=htmlspecialchars($_REQUEST['type']);
    	        $bd=$_REQUEST['bd'];
    	        $ddno=htmlspecialchars($_REQUEST['ddno']);
    	        $ddate=htmlspecialchars($_REQUEST['dddate']);
    	        $dateOfFiling=explode("/",$ddate);
    	        $ddate=@$dateOfFiling[2].'-'.@$dateOfFiling[1].'-'.@$dateOfFiling[0];
    	        $bd=htmlspecialchars($_REQUEST['bd']);
    	        if($bd==3){
    	            $ddno=htmlspecialchars($_REQUEST['ddno']);
    	        }
    	        $cdate=date('Y-m-d');
    	        if($payid =='') {
    	            $query_params=array(
    	                'salt'=>$salt,
    	                'payment_mode'=>$bd,
    	                'branch_name'=>$dbankname,
    	                'dd_no'=>$ddno,
    	                'dd_date'=>$ddate,
    	                'amount'=>$amountRs,
    	                'ia_fee'=>$amountRs,
    	                'total_fee'=>$totalamount,
    	                'fee_type'=>$type,
    	                'total_fee'=>$totalamount,
    	                'createdate'=>$cdate,
    	            );
    	            $st=$this->efiling_model->insert_query('sat_temp_payment',$query_params);
    	        }
    	        $bd=$_REQUEST['bd'];
    	        if($bd==3){
    	            $bankname="Name";
    	            $dd="challan/Ref. No";
    	            $date="Date of Transction";
    	            $amount="Aomunt in Rs.";
    	        }
    	        $html='';
    	        if($row->dd_no != 'undefined') { $vals= htmlspecialchars($row->dd_no); }
    	        $html.='<p> <font color="#510812" size="3">Bank Branch / Post Office / Online Name</font></p>
                <table  class="table">
                      <tr>
                          <th>'.$bankname.'</th>
                          <th>'.htmlspecialchars($dd).'</th>
                          <th>'.htmlspecialchars($date).'</th>
                          <th>'.htmlspecialchars($amount).'</th>
                          <th>Delete</th>
                      </tr> ';
	        }
    	        $sum=0;
    	        $feesd=$this->efiling_model->data_list_where('sat_temp_payment','salt',$salt);
    	        foreach($feesd as $row){
    	            $id=$row->id;
    	            $sum=$sum+$row->amount;
    	            $html.='<tr>
                            <td>'.$row->branch_name.'</td>
                            <td>'.$row->dd_no.'</td>
                            <td>'.date('d/m/Y',strtotime($row->dd_date)).'</td>
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
                        </div>';
    	        echo json_encode(['data'=>'success','value'=>'','paid'=>$sum,'remain'=>$remain,'display'=>$html,'error'=>'0']);
	        
	    }


       function myprofile(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=(int)$userdata[0]->id;
	        if($user_id){
	            $feesd['userDetail']=$this->efiling_model->data_list_where('efiling_users','id',$user_id);
	            $this->load->view('admin/myprofile',$feesd);
	        }
	    }
	    
	    
	    function edit_cetified(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=(int)$userdata[0]->id;
	        if($user_id){
	            $this->load->view("admin/edit_certifiedcopy",$data);
	        }
	    }
	    
	    
	    function cetified_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=(int)$userdata[0]->id;
	        if($user_id){
	            $this->load->view("admin/cetifiedCopyList",$data);
	        }
	    }
		
		
	   function pay_page(){
	       $userdata=$this->session->userdata('login_success');
	       $user_id=(int)$userdata[0]->id;
	       if($user_id){
    	       $this->form_validation->set_rules('salt','Please enter salt ','trim|required|min_length[1]|max_length[9]|numeric');
    	       if($this->form_validation->run() == FALSE){
    	           echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
    	       }
    	       $this->form_validation->set_rules('tabno','Please enter tab number','trim|required|min_length[1]|max_length[2]|htmlspecialchars|numeric');
    	       if($this->form_validation->run() == FALSE){
    	           echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
    	       }
               $this->load->view("admin/pay_page");
	       }
	    }
	    
	    function paysuccess_page(){
	        $salt='96635'; 
	        $aaaa=htmlspecialchars($_POST['BharatkoshResponse']);
	        $url = 'localhost:8086/verifyXml';
	        //url-ify the data for the POST
	        //echo $strbharatxml=$response;
	        $strbharatxml=$aaaa;
	        $ch = curl_init();
	        $fields_string = http_build_query($strbharatxml);
	        $fields_string =http_build_query($post_array);
	        //set the url, number of POST vars, POST data
	        curl_setopt($ch,CURLOPT_URL, $url);
	        //curl_setopt($ch, CURLOPT_HEADER, false);
	        
	        //curl_setopt($ch,CURLOPT_POST, count($fields_string));
	        curl_setopt($ch,CURLOPT_POST, 1);
	        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	        //curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	        curl_setopt($ch,CURLOPT_POSTFIELDS, $strbharatxml);
	        
	        //curl_setopt($ch, CURLOPT_HTTPGET, true);
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	            'Content-Type: application/json',
	            'Accept: application/json'
	        ));
	        $character = json_decode(curl_exec($ch));

	        $ordercodefinalvalue= $character->orderCode;
	        $signaturefinalvalue= $character->signatureValidation;
	        $statusfinalvalue= $character->status;
	        $transactionfinalvalue= $character->refId;
	        $trandatefinalvalue= $character->bankTransacstionDate;
	        $totalamountinalvalue= $character->totalAmount;
	        curl_close($ch);
	        
	        if($statusfinalvalue=='SUCCESS'){ 
	            $data['filing_no']=$filing_no;
	            $data['status']=$statusfinalvalue;
	            $data['refId']=$transactionfinalvalue;
	            $data['bankTransacstionDate']=$trandatefinalvalue;
	            $data['totalAmount']=$totalamountinalvalue;
	            $this->load->view("admin/paysuccess_page_pending",$data);
	        }
	        
	        if($statusfinalvalue=='PENDING'){
	            $st=$this->efiling_model->data_list_where('sat_temp_appellant','salt', $salt);
	            $bench=$st[0]->bench;
	            $subBench=$st[0]->sub_bench;
	            $caseType=$st[0]->l_case_type;

	            $userdata=$this->session->userdata('login_success');
	            $user_id=$userdata[0]->id;
	            $salt=htmlspecialchars($salt);
	            
	            $idorg=$_REQUEST['typefiled'];
	            $typefiledres=$_REQUEST['typefiledres'];
	            
	            if($bench!="" and $subBench!="") {
	                $benchCode= htmlspecialchars(str_pad($bench,3,'0',STR_PAD_LEFT));
	                $subBenchCode= htmlspecialchars(str_pad($subBench,2,'0',STR_PAD_LEFT));
	                $curYear = date('Y');
	                $curMonth = date('m');
	                $curDay = date('d');
	                $curdate="$curYear-$curMonth-$curDay";
	                $st=$this->efiling_model->data_list_where('year_initialization','year',$curYear);
	                $filing_no1=$st[0]->filing_no;
	                if($filing_no1 ==0){
	                    $filing_no ='000001';
	                    $fil_no =1;
	                }
	                if($filing_no1 !='0'){
	                    $fil_no =(int)$filing_no1+1;
	                    $filing_no = (int)$filing_no1+1;
	                    $len = strlen($filing_no);
	                    $length =6-$len;
	                    for($i=0;$i<$length;$i++){
	                        $filing_no = "0".$filing_no;
	                    }
	                }
	                $filing_no=$benchCode.$subBenchCode.$filing_no.$curYear;
	                
	                $sql_temp1=$this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
	                foreach ($sql_temp1 as $row){
	                    $l_case_no=$row->l_case_no;
	                    $l_case_year=$row->l_case_year;
	                    $lower_case_type=$row->lower_case_type;
	                    $commission=$row->commission;
	                    $nature_of_order=$row->nature_of_order;
	                    $decision_date=isset($row->l_date)?$row->l_date:'';
	                    $comm_date=$row->comm_date;
	                    $bench=$row->bench;
	                    $sub_bench=$row->sub_bench;
	                }
	                error_reporting(0);
	                $l_case_no1=explode('|',$l_case_no);
	                $len=sizeof($l_case_no1)-1;
	                for($iii=0;$iii<$len;$iii++){
	                    $l_case_year1=explode('|',$l_case_year);
	                    $lower_case_type1=explode('|',$lower_case_type);
	                    $commission1=explode('|',$commission);
	                    $nature_of_order1=explode('|',$nature_of_order);
	                    $decision_date1=explode('|',$decision_date);
	                    $decdate=$decision_date1[$iii];
	                    $decdate1=explode('-',$decdate);
	                    $decdate2=$decdate1[2].'-'.$decdate1[1].'-'.$decdate1[0];
	                    $comm_date1=explode('|',$comm_date);
	                    $comdate=$comm_date1[$iii];
	                    $comdate1=explode('-',$comdate);
	                    $comdate2=$comdate1[2].'-'.$comdate1[1].'-'.$comdate1[0];
	                    $data=array(
	                        'filing_no'=>$filing_no,
	                        'dt_of_filing'=>$curdate,
	                        'bench'=>$bench,
	                        'sub_bench'=>$sub_bench,
	                        'case_type'=>$lower_case_type1[$iii],
	                        'case_no'=>$l_case_no1[$iii],
	                        'case_year'=>$l_case_year1[$iii],
	                        'decision_date'=>$decdate2,
	                        'commission'=>$commission1[$iii],
	                        'nature_of_order'=>$nature_of_order1[$iii],
	                    );
	                    $st=$this->efiling_model->insert_query('lower_court_detail',$data);
	                }
	                
	                $caseType=$caseType;
	                $res_type=1;
	                $status='P';
	                $pet_type=1;
	                $advCode=0;
	                $sql_temp=$this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
	                $pet_name='';
	                foreach($sql_temp as $row){
	                    if($idorg==1){
	                        $orgid=$row->pet_name;
	                        $pet_name=$row->pet_name;
	                        if (is_numeric($row->pet_name)) {
	                            $storg =$this->efiling_model->data_list_where('master_org','org_id',$orgid);
	                            $pet_name=$storg[0]->org_name;
	                        }
	                    }
	                    if($idorg==2){
	                        $pet_name=$row->pet_name;
	                    }
	                    if($row->pet_council_adv==""){
	                        $advCode=$advCode;
	                    }else {
	                        $advCode=$row->pet_council_adv;
	                    }
	                    if($typefiledres==1){
	                        $orgid1=$row->resname;
	                        $resname=$row->resname;
	                        if (is_numeric($row->resname)) {
	                            $storg1 =$this->efiling_model->data_list_where('master_org','org_id',$orgid1);
	                            $resname=$storg1[0]->org_name;
	                        }
	                    }
	                    if( $typefiledres==2){
	                        $resname=$row->resname;
	                    }
	                    if($row->res_council_adv==""){
	                        $resadv=$advCode;
	                    }else{
	                        $resadv=$row->res_council_adv;
	                    }
	                    if($row->res_dis=="") {
	                        $dis='999';
	                    }else{
	                        $dis=$row->res_dis;
	                    }
	                    $iaNature=$row->ia_nature;
	                    $filed_by=$row->filed_by;
	                }
	                $sql_detail=array(
	                    'filing_no'=>$filing_no,
	                    'case_type'=>$caseType,
	                    'dt_of_filing'=>$curdate,
	                    'pet_type'=>$pet_type,
	                    'pet_name'=>$pet_name,
	                    'pet_adv'=>$advCode,
	                    'pet_address'=>$row->pet_address,
	                    'pet_state'=>$row->pet_state,
	                    'pet_district'=>$row->pet_dist,
	                    'pet_pin'=>$row->pincode,
	                    'pet_mobile'=>$row->petmobile,
	                    'pet_phone'=>$row->petphone,
	                    'pet_email'=>$row->pet_email,
	                    'pet_section'=>$row->petsection,
	                    'pet_sub_section'=>$row->petsubsection,
	                    'pet_fax'=>$row->pet_fax,
	                    'pet_counsel_address'=>$row->counsel_add,
	                    'pet_counsel_pin'=>$row->counsel_pin,
	                    'pet_counsel_mobile'=>$row->counsel_mobile,
	                    'pet_counsel_phone'=>$row->counsel_phone,
	                    'pet_counsel_email'=>$row->counsel_email,
	                    'pet_counsel_fax'=>$row->counsel_fax,
	                    'limitation'=>isset($row->limit_app)?$row->limit_app:'',
	                    'facts'=>isset($row->facts)?$row->facts:'',
	                    'facts_issue'=>isset($row->facts_issue)?$row->facts_issue:'',
	                    'question_low'=>isset($row->question_low)?$row->question_low:'',
	                    'ground_raised'=>isset($row->grounds_raised)?$row->grounds_raised:'',
	                    'matters'=>isset($row->matters)?$row->matters:'',
	                    'relief'=>isset($row->relief)?$row->relief:'',
	                    'interim_application'=>isset($row->interim_application)?$row->interim_application:'',
	                    'appeal'=>isset($row->appeal)?$row->appeal:'',
	                    'res_type'=>$res_type,
	                    'res_name'=>$resname,
	                    'res_adv'=>$resadv,
	                    'res_address'=>$row->res_address,
	                    'res_state'=>$row->res_state,
	                    'res_district'=>$dis,
	                    'res_pin'=>$row->res_pin,
	                    'res_email'=>$row->res_email,
	                    'res_mobile'=>$row->res_mobile,
	                    'res_phone'=>$row->res_phone,
	                    'res_fax'=>$row->res_fax,
	                    'res_counsel_address'=>$row->res_counsel_address,
	                    'res_counsel_pin'=>$row->res_counsel_pin,
	                    'res_counsel_mobile'=>$row->res_counsel_mob,
	                    'res_counsel_phone'=>$row->res_counsel_phone,
	                    'res_counsel_email'=>$row->res_counsel_email,
	                    'res_counsel_fax'=>$row->res_counsel_fax,
	                    'status'=>$status,
	                    'salt'=>$row->salt,
	                    'bench'=>$row->bench,
	                    'sub_bench'=>$row->sub_bench,
	                    'no_of_pet'=>$row->no_of_pet,
	                    'no_of_res'=>$row->no_of_res,
	                    'pet_degingnation'=>$row->pet_degingnation,
	                    'res_degingnation'=>$row->res_degingnation,
	                    'user_id'=>$row->user_id,
	                    'filed_user_id'=>$row->user_id,
	                    'no_of_impugned'=>$row->no_of_impugned,
	                    'act'=>$row->act,
	                ) ;
	                
	                
	                $st=$this->efiling_model->insert_query('sat_case_detail',$sql_detail);
	                $data=array(
	                    'filing_no'=>$fil_no,
	                );
	                $st=$this->efiling_model->update_data('year_initialization', $data,'year', $curYear);
	                //echo "<pre>";print_r($sql_detail);die;
	                $additionalpet =$this->efiling_model->data_list_where('sat_temp_additional_party','salt',$salt);
	                
	                foreach($additionalpet as $row){
	                    $tempValueadd=array(
	                        'filing_no'=>$filing_no,
	                        'party_flag'=>'1',
	                        'pet_name'=>$row->pet_name,
	                        'pet_degingnation'=>$row->pet_degingnation,
	                        'pet_address'=>$row->pet_address,
	                        'pin_code'=>$row->pin_code,
	                        'pet_state'=>$row->pet_state,
	                        'pet_dis'=>$row->pet_dis,
	                        'pet_mobile'=>$row->pet_mobile,
	                        'pet_phone'=>$row->pet_phone,
	                        'pet_email'=>$row->pet_email,
	                        'pet_fax'=>$row->pet_fax,
	                        'partysrno'=>$row->partysrno,
	                    );
	                    $st=$this->efiling_model->insert_query('additional_party',$tempValueadd);
	                    $conusel_code=$row->council_code;
	                    if($conusel_code!='Select Council Name' and $conusel_code!=""){
	                        $sqlAdditionalAdv=array(
	                            'filing_no'=>$filing_no,
	                            'party_flag'=>'P',
	                            'adv_code'=>$conusel_code,
	                            'adv_mob_no'=>$row->counsel_mobile,
	                            'adv_phone_no'=>$row->counsel_phone,
	                            'adv_fax_no'=>$row->counsel_fax,
	                            'adv_email'=>$row->counsel_email,
	                            'adv_address'=>$row->counsel_add,
	                            'user_id'=>$user_id,
	                            'pin_code'=>$row->counsel_pin,
	                        );
	                        $st=$this->efiling_model->insert_query('additional_advocate',$sqlAdditionalAdv);
	                    }
	                }
	                $res =$this->efiling_model->data_list_where('sat_temp_additional_res','salt',$salt);
	                foreach ($res as  $row){
	                    $resdata=array(
	                        'filing_no'=>$filing_no,
	                        'party_flag'=>'2',
	                        'pet_name'=>$row->res_name,
	                        'pet_degingnation'=>$row->res_degingnation,
	                        'pet_address'=>$row->res_address,
	                        'pin_code'=>$row->res_code,
	                        'pet_state'=>$row->res_state,
	                        'pet_dis'=>$row->res_dis,
	                        'pet_mobile'=>$row->res_mobile,
	                        'pet_phone'=>$row->res_phone,
	                        'pet_email'=>$row->res_email,
	                        'pet_fax'=>$row->res_fax,
	                        'partysrno'=>$row->partysrno,
	                    );
	                    $st=$this->efiling_model->insert_query('additional_party',$resdata);
	                    $conusel_code=$row->counsel_code;
	                    if($conusel_code!='Select Council Name' and $conusel_code!=""){
	                        $sqlAdditionalAdv1=array(
	                            'filing_no'=>$filing_no,
	                            'party_flag'=>'R',
	                            'adv_code'=>$conusel_code,
	                            'adv_mob_no'=>$row->counsel_mobile,
	                            'adv_phone_no'=>$row->counsel_phone,
	                            'adv_fax_no'=>$row->counsel_fax,
	                            'adv_email'=>$row->counsel_email,
	                            'adv_address'=>$row->counsel_add,
	                            'user_id'=>$user_id,
	                            'pin_code'=>$row->counsel_pin);
	                        $st=$this->efiling_model->insert_query('additional_advocate',$sqlAdditionalAdv1);
	                    }
	                }
	                
	                $hscquery=$this->efiling_model->data_list_where('sat_temp_payment','salt',$salt);
	                $iafee=0;
	                foreach($hscquery as $row){
	                    $iafee=$row->ia_fee;
	                    $account=array(
	                        'filing_no'=>$filing_no,
	                        'dt_of_filing'=>$curdate,
	                        'fee_amount'=>$row->total_fee,
	                        'payment_mode'=>$row->payment_mode,
	                        'branch_name'=>$row->branch_name,
	                        'dd_no'=>$row->dd_no,
	                        'dd_date'=>$row->dd_date,
	                        'amount'=>$row->amount,
	                        'salt'=>$row->salt,
	                        'ia_fee'=>$row->ia_fee,
	                        'other_fee'=>$row->other_fee,
	                        'other_document'=>$row->other_document,
	                    );
	                    $st=$this->efiling_model->insert_query('aptel_account_details',$account);
	                }
	                
	                $st=$this->efiling_model->data_list_where('temp_document','salt',$salt);
	                if(!empty($st)){
	                    $data12=array(
	                        'filing_no'=>$filing_no,
	                        'user_id'=>$user_id,
	                        'type'=>'efile',
	                        'file_type'=>'efiling',
	                        'fileName'=>$st[0]->fileName,
	                        'doc_url'=>$st[0]->doc_url,
	                    );
	                    $st=$this->efiling_model->insert_query('document_filing',$data12);
	                }
	                
	                if($iafee > 0 && $iafee!=0) {
	                    $feecode=explode(",",$iaNature);
	                    if($feecode[0]!=""){
	                        $len=sizeof($feecode)-1;
	                        $printIAno='';
	                        for($k=0;$k<$len;$k++){
	                            $ia_nature=$feecode[$k];
	                            if($ia_nature==12){
	                                $matter=htmlspecialchars($_REQUEST['matt']);
	                            }else{
	                                $matter="";
	                            }
	                            $year_ins=$this->efiling_model->data_list_where('ia_initialization','year',$curYear);
	                            $ia_filing_no=$year_ins[0]->ia_filing;
	                            if($ia_filing_no =='0'){
	                                $iaFilingNo=1;
	                                $filno ='000001';
	                            }
	                            if($ia_filing_no!='0'){
	                                $iaFilingNo =(int)$ia_filing_no+1;
	                                $ia_filing_no=(int)$ia_filing_no+1;
	                                $length =6-strlen($ia_filing_no);
	                                for($i=0;$i<$length;$i++){
	                                    $ia_filing_no= "0".$ia_filing_no;
	                                }
	                            }
	                            $iaFiling_no1=$benchCode.$subBenchCode.$ia_filing_no.$curYear;
	                            //  $printIAno=0;
	                            if (is_numeric($ia_nature)) {
	                                $datatta =$this->efiling_model->data_list_where('moster_ia_nature','nature_code',$ia_nature);
	                            }
	                            $datatta_name = $datatta[0]->nature_name;
	                            $printIAno=$printIAno."IA/".$iaFilingNo."/".$curYear." ( " .$datatta_name .")<br>";
	                            $ia=array(
	                                'ia_no'=>$iaFilingNo,
	                                'filing_no'=>$filing_no,
	                                'filed_by'=>$filed_by,
	                                'entry_date'=>$curdate,
	                                'display'=>'Y',
	                                'ia_filing_no'=>$iaFiling_no1,
	                                'ia_nature'=>$ia_nature,
	                                'status'=>$status,
	                                'matter'=>$matter,
	                            );
	                            $st=$this->efiling_model->insert_query('satma_detail',$ia);
	                            $data=array(
	                                'ia_filing'=>$iaFilingNo,
	                            );
	                            $st=$this->efiling_model->update_data('ia_initialization', $data,'year', $curYear);
	                        }
	                    } 
	                }
	                $dat=array('filing_no'=>$filing_no);
	                $st=$this->efiling_model->insert_query('scrutiny',$dat);
	                $this->session->unset_userdata('salt');
	                if($filing_no!=""){
	                  //  $delete= $this->efiling_model-> delete_event('sat_temp_appellant', 'salt', $salt);
	                   // $pay= $this->efiling_model-> delete_event('sat_temp_payment', 'salt', $salt);
	                   // $pet= $this->efiling_model-> delete_event('sat_temp_additional_party', 'salt', $salt);
	                   // $res= $this->efiling_model-> delete_event('sat_temp_additional_res', 'salt', $salt);
	                }
	     //           echo json_encode(['data'=>'success','display'=>$html,'error'=>'0']);
	            }
	        }
	        $data['filing_no']=isset($filing_no)?$filing_no:$salt;
	        $data['status']=$statusfinalvalue;
	        $data['refId']=$transactionfinalvalue;
	        $data['bankTransacstionDate']=$trandatefinalvalue;
	        $data['totalAmount']=$totalamountinalvalue;
if($statusfinalvalue!='CANCELED'){
	   
	      /*  $curl_handle=curl_init();
	        curl_setopt($curl_handle,CURLOPT_URL,'https://164.100.129.32/Bharatkosh/getstatus?OrderId=21313&PurposeId=2313213');
	        curl_setopt($curl_handle,CURLOPT_CONNECTTIMEOUT,2);
	        curl_setopt($curl_handle,CURLOPT_RETURNTRANSFER,1);
	        $buffer = curl_exec($curl_handle);
	        curl_close($curl_handle);
	        if (empty($buffer)){
	            print "Nothing returned from url.<p>";
	        }else{
	            print $buffer;
	        }  */

	        $this->load->view("admin/paysuccess_page",$data);
            }else{
                $this->load->view("admin/paysuccess_page_pending",$data);
            }
	    }
	    

	    function master_dash(){
	        $data['adv_varified']= $this->efiling_model->data_list_where('master_advocate','status','1');  
	        $data['org_varified']= $this->efiling_model->data_list_where('master_org','status','1');
	        $data['arg_nonvarified']= $this->efiling_model->data_list_where('master_org','status','0');
	        $data['euser_varified']= $this->efiling_model->data_list_where('efiling_users','verified','1');
	        $data['euser_nonvarified']= $this->efiling_model->data_list_where('efiling_users','verified','0');
	        $this->load->view("admin/master_dash",$data);
	    }
	    
	    function advocate_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
    	        $data['adv']= $this->efiling_model->data_list('master_advocate');
    	        $this->load->view("admin/advocate_list",$data);
	        }
	    }
	    
	    function adv_varified(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $this->form_validation->set_rules('status','status not valid','trim|required|min_length[1]|max_length[5]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('adv_id','advocate id not valid','trim|required|min_length[1]|max_length[5]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('remark','please enter remark','trim|required|htmlspecialchars');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        } 
	        $status=$this->input->post('status');
	        $remark=$this->input->post('remark'); 
	        $adv_id=$this->input->post('adv_id');;
	        if($user_id){         
	            $data=array(
	                'remark'=>$remark,
	                'status'=>$status,
	            );
	            $massage="Successfully Verified.";
	            $st=$this->efiling_model->update_data('master_advocate', $data,'id', $adv_id);
	            echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
	        }else{
	            $massage="User not valid  .";
	            echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'1']);
	        }  
	    }
	    
	    
	    
	    
	    function user_varified(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $this->form_validation->set_rules('status','status not valid','trim|required|numeric|min_length[1]|max_length[5]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('adv_id','advocate id not valid','trim|required|min_length[1]|max_length[5]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('remark','please enter remark','trim|required|htmlspecialchars');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }   
	        $status=htmlspecialchars($_REQUEST['status']);
	        $remark=htmlspecialchars($_REQUEST['remark']);
	        $adv_id=htmlspecialchars($_REQUEST['adv_id']);
	        $val= $this->efiling_model->data_list_where('efiling_users','id', $adv_id);
			//print_r($val);die;
	        $advcode=0;
	        if($user_id){	            
	            if($val[0]->enrolment_number!='' && $val[0]->user_type=='advocate'){ 
	                $mobile=$val[0]->mobilenumber;
	                $val=$this->efiling_model->data_list_where('master_advocate','adv_mobile', $mobile);
	                if(!empty($val)){
	                    $advcode=$val[0]->adv_code;
	                }else{
	                    $massage="User mobile number not registerd  .";
	                    echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);
	                }
	            }
	            $data=array(
	                'remark'=>$remark,
	                'verified'=>$status,
	                'adv_code'=>$advcode,
	            );
	            $massage="Successfully Verified.";
	            $st=$this->efiling_model->update_data('efiling_users', $data,'id', $adv_id);
	            echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);die;
	        }else{
	            $massage="User not valid  .";
	            echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);die;
	        }
	    }
	    
	    
	    function euser_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['users']= $this->efiling_model->data_list('efiling_users');
	            $this->load->view("admin/euser_list",$data);
	        }
	    }
	   
	    function user_view(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $adv_id=$_REQUEST['adv_id'];
	        if($user_id){
	            $st=$this->efiling_model->data_list_where('efiling_users','id', $adv_id);
	            $status='';
	            if($val->verified=='1'){ $status= "Active"; $color='btn-success'; $action= "Varified"; }else{ $status=  "Non Active";$color='btn-primary';$action= "Not Varified";  }    
	            $html='<div class="col-md-8">
                        <div class="tab-content profile-tab" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>User Id</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->loginid.'</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Name</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->fname.''.$st[0]->lname.'</p>
                                            </div>
                                        </div>
                                       <div class="row">
                                            <div class="col-md-6">
                                                <label>User Type</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->user_type.'</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Email</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->email.'</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Phone</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->mobilenumber.'</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Status</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$status.'</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Address</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->address.'</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Enrolment</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->enrolment_number.'</p>
                                            </div>
                                        </div>   
                            </div>';
	            echo json_encode(['data'=>'success','value'=>$html,'massage'=>$massage,'error'=>'1']);
	        }else{
	            $massage="User not valid  .";
	            echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'1']);
	        }  
	    }
	    
	    function org_list(){
	         $data['org']= $this->efiling_model->data_list('master_org');
	         $this->load->view("admin/org_list",$data);
	    }
	    
	    
	    function org_view(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $adv_id=$_REQUEST['org_id'];
	        if($user_id){
	            $st=$this->efiling_model->data_list_where('master_org','org_id', $adv_id);
	            $status='';
	            if($val->status=='1'){ $status= "Active"; $color='btn-success'; $action= "Varified"; }else{ $status=  "Non Active";$color='btn-primary';$action= "Not Varified";  }
	            $html='<div class="col-md-8">
                        <div class="tab-content profile-tab" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Organization Name</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->org_name.'</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Organization Address</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->org_address.'</p>
                                            </div>
                                        </div>          
                                       <div class="row">
                                            <div class="col-md-6">
                                                <label> Short Name</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->short_org_name.'</p>
                                            </div>
                                        </div>              
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label> Mobile</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->mobile_no.'</p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Email</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->email.'</p>
                                            </div>
                                        </div>           
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Status</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$status.'</p>
                                            </div>
                                        </div>                                               
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Full Name</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->orgdisp_name.'</p>
                                            </div>
                                        </div>          
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label>Organization desg</label>
                                            </div>
                                            <div class="col-md-6">
                                                <p>'.$st[0]->org_desg.'</p>
                                            </div>
                                        </div>           
                            </div>';
	            echo json_encode(['data'=>'success','value'=>$html,'massage'=>$massage,'error'=>'1']);
	        }else{
	            $massage="User not valid  .";
	            echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'1']);
	        } 
	    }
	    
	    function org_varified(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $this->form_validation->set_rules('status','status not valid','trim|required|min_length[1]|max_length[5]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        }
	        $this->form_validation->set_rules('adv_id','advocate id not valid','trim|required|min_length[1]|max_length[5]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('remark','please enter remark','trim|required|htmlspecialchars');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	        } 
	        $status=htmlentities($_REQUEST['status']);
	        $remark=htmlentities($_REQUEST['remark']);
	        $adv_id=htmlentities($_REQUEST['adv_id']);
	        if($user_id){
	            $data=array(
	                'remark'=>$remark,
	                'status'=>$status,
	            );
	            $massage="Successfully Verified.";
	            $st=$this->efiling_model->update_data('master_org', $data,'org_id', $adv_id);
	            echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
	        }else{
	            $massage="User not valid.";
	            echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);
	        }
	    }

       function change_password(){
	       $userdata=$this->session->userdata('login_success');
	       $user_id=$userdata[0]->id;
	       $data=array();
	       if($user_id!=''){
	           $data['user_detail']=$this->efiling_model->data_list_where('users','id', $user_id);
	           $this->load->view("admin/changepass",$data);
	       }
	    }
	    
	    function  changepassword(){
	        $massage='';
	        $data='';
	        
	        $oldpassword =htmlentities($_REQUEST['oldpass']);
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id; 
	        $dataval=$this->efiling_model->data_list_where('efiling_users','id', $user_id);
	        if($dataval[0]->password!=$oldpassword){
	            echo json_encode(['data'=>'error','value'=>'','massage'=>'old password not match','error'=>'1']);die;
	        }	       
	        $token =htmlentities($_REQUEST['passval']);
	        $sestoken=$this->session->userdata('passval');
	        
	        $pass= $_REQUEST['password'];
	        $errors = array();
	        if (strlen($pass) < 8 || strlen($pass) > 16) {
	            $massage = "Password should be min 8 characters and max 16 characters";
	        }
	        if (!preg_match("/\d/", $pass)) {
	            $massage = "Password should contain at least one digit";
	        }
	        if (!preg_match("/[A-Z]/", $pass)) {
	            $massage= "Password should contain at least one Capital Letter";
	        }
	        if (!preg_match("/[a-z]/", $pass)) {
	            $massage = "Password should contain at least one small Letter";
	        }
	        if (!preg_match("/\W/", $pass)) {
	            $massage= "Password should contain at least one special character";
	        }
	        if (preg_match("/\s/", $pass)) {
	            $massage = "Password should not contain any white space";
	        }
	        if($massage!=''){
	            echo json_encode(['data'=>$data,'value'=>'','massage'=>$massage,'error'=>$error]); die;
	        }
	        if($token == $sestoken){
	            $new_pass =  hash('sha512', htmlentities($_REQUEST['password']));
	            $conpass =  hash('sha512', htmlentities($_REQUEST['conpass']));
	            if ($new_pass != $conpass) {
	               $massage="Password not match please try again";
	               $error='1';
	               $data='error';
	            }else{
	                $data=array(
	                    'password'=>$conpass,
	                    'is_password'=>1
	                );
	                $st=$this->efiling_model->update_data('efiling_users', $data,'id', $user_id);
	                $massage="Successfully Password Changed.";
	                $error='0';
	                $data='success';
	                $this->session->unset_userdata('passval');
	                $this->session->unset_userdata('login_success');
	            }
	            echo json_encode(['data'=>$data,'value'=>'','massage'=>$massage,'error'=>$error]);
	        }else{
	           echo json_encode(['data'=>$data,'value'=>'','massage'=>$massage,'error'=>$error]);
	        }
	    }


	    function postalorderfinal(){
	        $app['app']=htmlentities($_REQUEST['app']);
	            $this->load->view('admin/postalorderfinal',$app);
	    }
	    
           function folder($location,$filing_no){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id; 
	        if($user_id!=''){
	            $dataval=$this->efiling_model->data_list_where('document_filing','filing_no', $filing_no);
	            if(!empty($dataval)){
	                $file=$dataval[0]->doc_url;
	                $hash_file=hash('sha256',$file);
	            }else{
	                exit("Request not valid");
	            }
    	        if (file_exists($file) && $hash_file==$location){
    	            header('Content-Type: '.get_mime_by_extension($file));
    	            readfile($file);
    	        }
	        }else{
	            echo "Request not valid";
	        }
	    }
	    
	    
	    
	    function folderuser($location,$id){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $file='';
	        if($user_id!=''){
	            $dataval=$this->efiling_model->data_list_where('efiling_users','id', $id);
	            if(!empty($dataval)){
	                $file='.'.$dataval[0]->idproof_upd;
	                $hash_file=hash('sha256',$file);
	            }else{
	                exit("Request not valid");
	            }
	            if (file_exists($file) && $hash_file==$location){
	                header('Content-Type: '.get_mime_by_extension($file));
	                readfile($file);
	            }
	        }else{
	            echo "Request not valid";
	        }
	    }
	    
	    
	    function refile_case(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id!=''){
	            $data['refile']= $this->efiling_model->scrutiny_list();
	            $this->load->view("admin/refile_case",$data);
	        }
	    }
	    
	    
	    function defectlatter($filing_no){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        $data['filing_no']=$filing_no;
	        if($user_id!=''){
	            $this->load->view("admin/defectletter_action",$data);
	        }else{
	            echo json_encode(['data'=>'success','display'=>'','error'=>'User Not Valid']);
	        }
	    }
		
		
		function defective_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['defect']= $this->efiling_model->defective_list();
	            $this->load->view("admin/defective_list",$data);
	        }
	    }
	    
	    
	    
	    function scrutiny_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['scrutiny']= $this->efiling_model->scrutiny_list();
	            $this->load->view("admin/scrutiny_list",$data);
	        }
	    }
	    function registerd_cases(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['regcase']= $this->efiling_model->registerdcases_list();
	            $this->load->view("admin/registerd_cases",$data);
	        }
	    }
	    
	    function dfrdetail($filing_no){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filing_no',$filing_no);
	            $this->load->view("admin/dfrdetail",$data);
	        }
	    }
	    
	public function comman_count(){
		if($this->input->post()){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
			$rs=$this->efiling_model->getData($this->input->post(),'user_id',$user_id);
			if($rs) {
				echo json_encode(['data'=>$rs,'error'=>'0']);
			}
			else {			
				echo json_encode(['data'=>'','error'=>'Data not found!']);
			}
		}
		else {			
			echo json_encode(['data'=>'','error'=>'Invalid request found!']);
		}
	}
	

	//********* Check file mime type ***********//
    public function mimeType($str) { 
        $allowed_mime_type_arr = array('image/jpeg','image/gif','image/png','application/pdf');
        $mime = get_mime_by_extension($_FILES['userfile']['name']);
        if(isset($_FILES['userfile']['name']) && $_FILES['userfile']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('mimeType', 'Accept only pdf,jpg,gif and png file are allowed.');
                return false;
            }
        }else{
            return true;
        }
	}
	
	
	function myCrypt($value, $key, $iv){
	    $encrypted_data = openssl_encrypt($value, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
	    return base64_encode($encrypted_data);
	}
	
	function myDecrypt($value, $key, $iv){
	    $value = base64_decode($value);
	    $data = openssl_decrypt($value, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);
	    return $data;
	}

	
	
	public function uploaded_docs_display(){
	    $userdata=$this->session->userdata('login_success');
	    $user_id=$userdata[0]->id;
	    if($this->session->userdata('login_success') && (int)$this->input->post('docId') > 0){
	        $id=(int)$this->input->post('docId');
	        $url=$this->db->select('*')
	        ->where('id',$id)
	        ->get('temp_documents_upload')
	        ->row();   
	        if($user_id==$url->user_id){
    	        $valTxt=$url->file_url;
    	        $key="01234567890123456789012345678901"; // 32 bytes
    	        $vector="1234567890123412"; // 16 bytes
    	        $encrypted = $this->myCrypt($valTxt, $key, $vector);
    	        echo json_encode(['data'=>@$encrypted,'error'=>'0']);die;
	        }else{
	             echo json_encode(['data'=>'Permission deny!','error'=>'1']);die;
	        }
	    }
	    else echo json_encode(['data'=>'Permission deny!','error'=>'1']);die;
	}

	
	
	
	
	
	
	
	

	public function updDoc_list(){
		if($this->session->userdata('login_success') && (int)$this->input->post('saltId') > 0){
			$salt=$this->input->post('saltId');
			$user_id=(int)$this->session->userdata('login_success')[0]->id;
			$type=$this->input->post('type');
			$warr=array('salt'=>$salt,'display'=>'Y','submit_type'=>$type);
            $docData =$this->efiling_model->list_uploaded_docs('temp_documents_upload',$warr);
			if($docData)
				echo json_encode(['data'=>@$docData,'error'=>'0']);
			else echo json_encode(['data'=>'Data not found.','error'=>'1']);
		}
		else echo json_encode(['data'=>'Permission deny!','error'=>'1']);
	}


	
	
	
	
	
	public function checkList_validate(){	
		if($this->session->userdata('login_success') && $this->input->post()) {
			$saltval=$this->session->userdata('salt');
			$userdata=$this->session->userdata('login_success');  
			$user_id=$userdata[0]->id; 

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
				$this->session->set_userdata('salt',$salt);
			}
			else $salt=$saltval;

			// $impugned_date=$_REQUEST['impugned_date'];
			// $data=[
			// 	'salt'=>$salt,
			// 	'impugned_date'=>$impugned_date,
			// 	'created_date'=>$user_id,
			// ];
			// $db=$this->db->insert('sat_temp_appellant',$data);


	
			$impugned_date=$_REQUEST['impugned_date'];
			$data=[
				'salt'=>$salt,
				'impugned_date'=>$impugned_date,
				'created_date'=>$user_id,
			];
			$db=$this->db->insert('additional_commision',$data);
			
			
			if($db) echo json_encode(['data'=>'success','error'=>'0']);
			else 	echo json_encode(['data'=>'Qyery error, try again','error'=>'1']);
		}
		else echo json_encode(['data'=>'Permission deny!','error'=>'1']);
	}
	
	
	public function uploaded_docs_delete(){
	    if($this->session->userdata('login_success') && (int)$this->input->post('docId') > 0){
	        $id=(int)$this->input->post('docId');
	        $url=$this->db->select('file_url')
	        ->where('id',$id)
	        ->get('temp_documents_upload')
	        ->row()
	        ->file_url;
	        if(unlink($url)) {
	            $this -> db -> where('id', $id);
	            $this -> db -> delete('temp_documents_upload');
	            $msg ='Record Deleted successfully';
	        }
	        echo json_encode(['data'=>'Success','error'=>'0','msg'=>$msg]);
	    }
	    else echo json_encode(['data'=>'Error!','error'=>'1','msg'=>'error']);
	}
	
	
	
	
	public function upd_required_cav($csrf=NULL) {
	    $token=hash('sha512',trim($_REQUEST['token']).'upddoc');
	    $userdata=$this->session->userdata('login_success');
	    $user_id=$userdata[0]->id;
	    $matter=$_REQUEST['matter'];
	    if($_FILES && $token==$csrf && (int)$this->input->post('salt') > 0) {
	        $config=[
	            ['field'=>'party_type', 'label'=>'Please select valid document filed by','rules'=>'trim|max_length[100]|regex_match[/^[A-Za-z_]+$/]'],
	            ['field'=>'req_dtype', 'label'=>'Please select valid document type','rules'=>'trim|max_length[100]|regex_match[/^[A-Za-z_\/)(\ ]+$/]'],
	            ['field'=>'userfile', 'label'=>'','rules'=>'trim|max_length[200]|callback_mimeType']
	        ];
	        
	        $this->form_validation->set_rules($config);
	        if($this->form_validation->run()==FALSE) {
	            $returnData=['data'=>'','error' => strip_tags(validation_errors())];
	            echo json_encode($returnData); exit();
	            
	        } else {
	            
	            $fl_path='./upload_doc/efiling/';
	            $schemas='delhi/';
	            $step1=$fl_path.$schemas;
	            
	            $salt=(int)$this->input->post('salt');
	            $step2=$step1.$salt.'/';
	            $typeval=$this->input->post('type');
	            $submittype=$this->input->post('submittype');
	            $docvalid=$this->input->post('docvalid');
	            $pty=$this->input->post('party_type');
	            $step3=$step2.$pty.'/';
	            
	            $rqd_flnm=strtr($this->input->post('req_dtype'),['/'=>'_',' '=>'_']);
	            
	            if(!is_dir($step1)) {
	                mkdir($step1, 0777, true);
	            }
	            
	            if(!is_dir($step2)) {
	                mkdir($step2, 0777, true);
	            }
	            
	            if(!is_dir($step3)) {
	                mkdir($step3, 0777, true);
	            }
	            
	            $valume='1';
	            $valexit=$this->efiling_model->data_list_where('temp_documents_upload','docid',$docvalid);
	            if(!empty($valexit)){
	                foreach($valexit as $fv){
	                    if($fv->valumeno!=''){
	                        $valume = (int)$fv->valumeno+1;
	                    }
	                }
	            }
	            $docname=$_FILES['userfile']['name'];
	            
	            $array=explode('.',$_FILES['userfile']['name']);
	            if(substr_count($_FILES['userfile']['name'],'.')>1){
	                echo json_encode(['data'=>'','error' =>'File should have only single dot (.) extenction.']);die;
	            }
	            
	            
	            $array=explode('.',$_FILES['userfile']['name']);
	            $config['upload_path']   		= $step3;
	            $config['allowed_types'] 		= 'jpg|jpeg|gif|png|pdf';
	            $config['max_size']      		= '199999';
	            $config['overwrite']	   		= TRUE;
	            $config['file_ext_tolower']	= TRUE;
	            $config['file_name']=$rqd_flnm.'_'.date('dmYHis').'.'.strtolower(end($array));
	            
	            $this->load->library('upload', $config);
	            if(!$this->upload->do_upload('userfile') && $this->upload->data('file_name'))
	                echo json_encode(['data'=>'','error' => strip_tags($this->upload->display_errors()),'file_name'=>'']);
	                else 	{
	                    $final_doc_url=$step3.$config['file_name'];
	                    $pages=$this->countPages($final_doc_url);
	                    $data=array(
	                        'salt' 					=>$salt,
	                        'user_id' 				=>$user_id,
	                        'document_filed_by' 	=>$pty,
	                        'matter' 	            =>$matter,
	                        'no_of_pages'           =>$pages,
	                        'document_type' 		=>$rqd_flnm,
	                        'file_url' 				=>$final_doc_url,
	                        'doc_type' 				=>$typeval,
	                        'submit_type'           =>$submittype,
	                        'docid'                 =>$docvalid,
	                        'doc_name'              =>$docname,
	                        'valumeno'              =>$valume,
	                    );
	                    
	                    $valTxt=$final_doc_url;
	                    $key="01234567890123456789012345678901"; // 32 bytes
	                    $vector="1234567890123412"; // 16 bytes
	                    $encrypted = $this->myCrypt($valTxt, $key, $vector);  
	                    
	                    $st=$this->efiling_model->insert_query('temp_documents_upload',$data);
	                    echo json_encode(['data'=>'success','error' => '0','file_name'=>$encrypted]);
	                }
	        }
	    }
	    else
	        echo json_encode(['data'=>'Invalid request foud, try with valid details','error' => '1','file_name'=>'']);
	}
	
	
	
	
	
	public function upd_required_cert($csrf=NULL) {
	    $token=hash('sha512',trim($_REQUEST['token']).'upddoc');
	    $userdata=$this->session->userdata('login_success');
	    $user_id=$userdata[0]->id;
	    $matter=$_REQUEST['matter'];
	    if($_FILES && $token==$csrf && (int)$this->input->post('salt') > 0) {
	        $config=[
	            ['field'=>'party_type', 'label'=>'Please select valid document filed by','rules'=>'trim|max_length[100]|regex_match[/^[A-Za-z_]+$/]'],
	            ['field'=>'req_dtype', 'label'=>'Please select valid document type','rules'=>'trim|max_length[100]|regex_match[/^[A-Za-z_\/)(\ ]+$/]'],
	            ['field'=>'userfile', 'label'=>'','rules'=>'trim|max_length[200]|callback_mimeType']
	        ];
	        
	        $this->form_validation->set_rules($config);
	        if($this->form_validation->run()==FALSE) {
	            $returnData=['data'=>'','error' => strip_tags(validation_errors())];
	            echo json_encode($returnData); exit();
	            
	        } else {
	            
	            $fl_path='./upload_doc/efiling/';
	            $schemas='delhi/';
	            $step1=$fl_path.$schemas;
	            
	            $salt=(int)$this->input->post('salt');
	            $step2=$step1.$salt.'/';
	            $typeval=$this->input->post('type');
	            $submittype=$this->input->post('submittype');
	            $docvalid=$this->input->post('docvalid');
	            $pty=$this->input->post('party_type');
	            $step3=$step2.$pty.'/';
	            
	            $rqd_flnm=strtr($this->input->post('req_dtype'),['/'=>'_',' '=>'_']);
	            
	            if(!is_dir($step1)) {
	                mkdir($step1, 0777, true);
	            }
	            
	            if(!is_dir($step2)) {
	                mkdir($step2, 0777, true);
	            }
	            
	            if(!is_dir($step3)) {
	                mkdir($step3, 0777, true);
	            }
	            
	            $valume='1';
	            $valexit=$this->efiling_model->data_list_where('temp_documents_upload','docid',$docvalid);
	            if(!empty($valexit)){
	                foreach($valexit as $fv){
	                    if($fv->valumeno!=''){
	                        $valume = (int)$fv->valumeno+1;
	                    }
	                }
	            }
	            $docname=$_FILES['userfile']['name'];
	            
	            
	            $array=explode('.',$_FILES['userfile']['name']);
	            if(substr_count($_FILES['userfile']['name'],'.')>1){
	                echo json_encode(['data'=>'','error' =>'File should have only single dot (.) extenction.']);die;
	            }
	            
	            
	            
	            $array=explode('.',$_FILES['userfile']['name']);
	            $config['upload_path']   		= $step3;
	            $config['allowed_types'] 		= 'jpg|jpeg|gif|png|pdf';
	            $config['max_size']      		= '199999';
	            $config['overwrite']	   		= TRUE;
	            $config['file_ext_tolower']	= TRUE;
	            $config['file_name']=$rqd_flnm.'_'.date('dmYHis').'.'.strtolower(end($array));
	            
	            $this->load->library('upload', $config);
	            if(!$this->upload->do_upload('userfile') && $this->upload->data('file_name'))
	                echo json_encode(['data'=>'','error' => strip_tags($this->upload->display_errors()),'file_name'=>'']);
	                else 	{
	                    $final_doc_url=$step3.$config['file_name'];
	                    $pages=$this->countPages($final_doc_url);
	                    $data=array(
	                        'salt' 					=>$salt,
	                        'user_id' 				=>$user_id,
	                        'document_filed_by' 	=>$pty,
	                        'matter' 	            =>$matter,
	                        'no_of_pages'           =>$pages,
	                        'document_type' 		=>$rqd_flnm,
	                        'file_url' 				=>$final_doc_url,
	                        'doc_type' 				=>$typeval,
	                        'submit_type'           =>$submittype,
	                        'docid'                 =>$docvalid,
	                        'doc_name'              =>$docname,
	                        'valumeno'              =>$valume,
	                    );
	                    $valTxt=$final_doc_url;
	                    $key="01234567890123456789012345678901"; // 32 bytes
	                    $vector="1234567890123412"; // 16 bytes
	                    $encrypted = $this->myCrypt($valTxt, $key, $vector);  
	                    $st=$this->efiling_model->insert_query('temp_documents_upload',$data);
	                    echo json_encode(['data'=>'success','error' => '0','file_name'=>$encrypted]);
	                }
	        }
	    }
	    else
	        echo json_encode(['data'=>'Invalid request foud, try with valid details','error' => '1','file_name'=>'']);
	}
	
	
	
	
	public function upd_required_rpepcp($csrf=NULL) {
	    $token=hash('sha512',trim($_REQUEST['token']).'upddoc');
	    $userdata=$this->session->userdata('login_success');
	    $user_id=$userdata[0]->id;
	    $matter=$_REQUEST['matter'];
	    if($_FILES && $token==$csrf && (int)$this->input->post('salt') > 0) {
	        $config=[
	            ['field'=>'party_type', 'label'=>'Please select valid document filed by','rules'=>'trim|max_length[100]|regex_match[/^[A-Za-z_]+$/]'],
	            ['field'=>'req_dtype', 'label'=>'Please select valid document type','rules'=>'trim|max_length[100]|regex_match[/^[A-Za-z_\/)(\ ]+$/]'],
	            ['field'=>'userfile', 'label'=>'','rules'=>'trim|max_length[200]|callback_mimeType']
	        ];
	        
	        $this->form_validation->set_rules($config);
	        if($this->form_validation->run()==FALSE) {
	            $returnData=['data'=>'','error' => strip_tags(validation_errors())];
	            echo json_encode($returnData); exit();
	            
	        } else {
	            
	            $fl_path='./upload_doc/efiling/';
	            $schemas='delhi/';
	            $step1=$fl_path.$schemas;
	            
	            $salt=(int)$this->input->post('salt');
	            $step2=$step1.$salt.'/';
	            $typeval=$this->input->post('type');
	            $submittype=$this->input->post('submittype');
	            $docvalid=$this->input->post('docvalid');
	            $pty=$this->input->post('party_type');
	            $step3=$step2.$pty.'/';
	            $rqd_flnm=strtr($this->input->post('req_dtype'),['/'=>'_',' '=>'_']);
	            if(!is_dir($step1)) {
	                mkdir($step1, 0777, true);
	            }
	            if(!is_dir($step2)) {
	                mkdir($step2, 0777, true);
	            }
	            if(!is_dir($step3)) {
	                mkdir($step3, 0777, true);
	            }
	            $valume='';
	            if($docvalid==10){
	                $valume='1';
	            }
	            $array=array('docid'=>$docvalid,'salt'=>$salt);
	            $valexit=$this->efiling_model->data_list_mulwhere('temp_documents_upload',$array);
	            if(!empty($valexit)){
	                foreach($valexit as $fv){
	                    if($docvalid==10){
	                        $valume='1';
	                        if($fv->valumeno!=''){
	                            $valume = (int)$fv->valumeno+1;
	                        }
	                    }
	                }
	            }
	            $array=explode('.',$_FILES['userfile']['name']);
	            if(substr_count($_FILES['userfile']['name'],'.')>1){
	                echo json_encode(['data'=>'','error' =>'File should have only single dot (.) extenction.']);die;
	            }
	            
	            $docname=$_FILES['userfile']['name'];
	            $array=explode('.',$_FILES['userfile']['name']);
	            $config['upload_path']   		= $step3;
	            $config['allowed_types'] 		= 'jpg|jpeg|gif|png|pdf';
	            $config['max_size']      		= '199999';
	            $config['overwrite']	   		= TRUE;
	            $config['file_ext_tolower']	= TRUE;
	            $config['file_name']=$rqd_flnm.'_'.date('dmYHis').'.'.strtolower(end($array));
	            $this->load->library('upload', $config);
	            if(!$this->upload->do_upload('userfile') && $this->upload->data('file_name'))
	                echo json_encode(['data'=>'','error' => strip_tags($this->upload->display_errors()),'file_name'=>'']);
	                else 	{
	                    $final_doc_url=$step3.$config['file_name'];
	                    $pages=$this->countPages($final_doc_url);
	                    $data=array(
	                        'salt' 					=>$salt,
	                        'user_id' 				=>$user_id,
	                        'document_filed_by' 	=>$pty,
	                        'matter' 	            =>$matter,
	                        'no_of_pages'           =>$pages,
	                        'document_type' 		=>$rqd_flnm,
	                        'file_url' 				=>$final_doc_url,
	                        'doc_type' 				=>$typeval,
	                        'submit_type'           =>$submittype,
	                        'docid'                 =>$docvalid,
	                        'doc_name'              =>$docname,
	                        'valumeno'              =>$valume,
	                    );
	                    $valTxt=$final_doc_url;
	                    $key="01234567890123456789012345678901"; // 32 bytes
	                    $vector="1234567890123412"; // 16 bytes
	                    $encrypted = $this->myCrypt($valTxt, $key, $vector);  
	                    $st=$this->efiling_model->insert_query('temp_documents_upload',$data);
	                    echo json_encode(['data'=>'success','error' => '0','file_name'=>$encrypted]);
	                }
	        }
	    }
	    else
	        echo json_encode(['data'=>'Invalid request foud, try with valid details','error' => '1','file_name'=>'']);
	}
	

	public function upd_required_ia($csrf=NULL) {
	    $token=hash('sha512',trim($_REQUEST['token']).'upddoc');
	    $userdata=$this->session->userdata('login_success');
	    $user_id=$userdata[0]->id;
	    $matter=$_REQUEST['matter'];
	    if($_FILES && $token==$csrf && (int)$this->input->post('salt') > 0) {
	        $config=[
	            ['field'=>'party_type', 'label'=>'Please select valid document filed by','rules'=>'trim|max_length[100]|regex_match[/^[A-Za-z_]+$/]'],
	            ['field'=>'req_dtype', 'label'=>'Please select valid document type','rules'=>'trim|max_length[100]|regex_match[/^[A-Za-z_\/)(\ ]+$/]'],
	            ['field'=>'userfile', 'label'=>'','rules'=>'trim|max_length[200]|callback_mimeType']
	        ];
	        
	        $this->form_validation->set_rules($config);
	        if($this->form_validation->run()==FALSE) {
	            $returnData=['data'=>'','error' => strip_tags(validation_errors())];
	            echo json_encode($returnData); exit();
	            
	        } else {
	            
	            $fl_path='./upload_doc/efiling/';
	            $schemas='delhi/';
	            $step1=$fl_path.$schemas;
	            
	            $salt=(int)$this->input->post('salt');
	            $step2=$step1.$salt.'/';
	            $typeval=$this->input->post('type');
	            $submittype=$this->input->post('submittype');
	            $docvalid=$this->input->post('docvalid');
	            $pty=$this->input->post('party_type');
	            $step3=$step2.$pty.'/';
	            $rqd_flnm=strtr($this->input->post('req_dtype'),['/'=>'_',' '=>'_']);
	            if(!is_dir($step1)) {
	                mkdir($step1, 0777, true);
	            }
	            if(!is_dir($step2)) {
	                mkdir($step2, 0777, true);
	            }
	            if(!is_dir($step3)) {
	                mkdir($step3, 0777, true);
	            }
	            $valume='';
	            /* if($docvalid==10){
	                $valume='1';
	            }
	            $array=array('docid'=>$docvalid,'salt'=>$salt);
	            $valexit=$this->efiling_model->data_list_mulwhere('temp_documents_upload',$array);
	            if(!empty($valexit)){
	                foreach($valexit as $fv){
	                    if($docvalid==10){
	                        $valume='1';
	                        if($fv->valumeno!=''){
	                            $valume = (int)$fv->valumeno+1;
	                        }
	                    }
	                }
	            } */
	            $array=explode('.',$_FILES['userfile']['name']);
	            if(substr_count($_FILES['userfile']['name'],'.')>1){
	                echo json_encode(['data'=>'','error' =>'File should have only single dot (.) extenction.']);die;
	            }
	            
	            $docname=$_FILES['userfile']['name'];
	            $array=explode('.',$_FILES['userfile']['name']);
	            $config['upload_path']   		= $step3;
	            $config['allowed_types'] 		= 'jpg|jpeg|gif|png|pdf';
	            $config['max_size']      		= '199999';
	            $config['overwrite']	   		= TRUE;
	            $config['file_ext_tolower']	= TRUE;
	            $config['file_name']=$rqd_flnm.'_'.date('dmYHis').'.'.strtolower(end($array));
	            $this->load->library('upload', $config);
	            if(!$this->upload->do_upload('userfile') && $this->upload->data('file_name'))
	                echo json_encode(['data'=>'','error' => strip_tags($this->upload->display_errors()),'file_name'=>'']);
	                else 	{
	                    $final_doc_url=$step3.$config['file_name'];
	                    $pages=$this->countPages($final_doc_url);
	                    $data=array(
	                        'salt' 					=>$salt,
	                        'user_id' 				=>$user_id,
	                        'document_filed_by' 	=>$pty,
	                        'matter' 	            =>$matter,
	                        'no_of_pages'           =>$pages,
	                        'document_type' 		=>$rqd_flnm,
	                        'file_url' 				=>$final_doc_url,
	                        'doc_type' 				=>$typeval,
	                        'submit_type'           =>$submittype,
	                        'docid'                 =>$docvalid,
	                        'doc_name'              =>$docname,
	                        'valumeno'              =>$valume,
	                    );
	                    $valTxt=$final_doc_url;
	                    $key="01234567890123456789012345678901"; // 32 bytes
	                    $vector="1234567890123412"; // 16 bytes
	                    $encrypted = $this->myCrypt($valTxt, $key, $vector); 
	                    $st=$this->efiling_model->insert_query('temp_documents_upload',$data);
	                    echo json_encode(['data'=>'success','error' => '0','file_name'=>$encrypted]);
	                }
	        }
	    }
	    else
	        echo json_encode(['data'=>'Invalid request foud, try with valid details','error' => '1','file_name'=>'']);
	}

	
	function docfiling_required_docs($csrf=NULL){
	    $token=hash('sha512',trim($_REQUEST['token']).'upddoc');
	    $userdata=$this->session->userdata('login_success');
	    $user_id=$userdata[0]->id;
	    $matter=$this->input->post('matter',true);
	    if($_FILES && $token==$csrf && (int)$this->input->post('salt') > 0) {
	        $config=[
	            ['field'=>'party_type', 'label'=>'Please select valid document filed by','rules'=>'trim|max_length[100]|regex_match[/^[A-Za-z_]+$/]'],
	            ['field'=>'req_dtype', 'label'=>'Please select valid document type','rules'=>'trim|max_length[100]|regex_match[/^[A-Za-z_\/)(\ ]+$/]'],
	            ['field'=>'userfile', 'label'=>'','rules'=>'trim|max_length[200]|callback_mimeType']
	        ];
	        
	        $this->form_validation->set_rules($config);
	        if($this->form_validation->run()==FALSE) {
	            $returnData=['data'=>'','error' => strip_tags(validation_errors())];
	            echo json_encode($returnData); exit();
	            
	        } else {
	            
	            $fl_path='./upload_doc/efiling/';
	            $schemas='delhi/';
	            $step1=$fl_path.$schemas;
	            
	            $salt=(int)$this->input->post('salt',true);
	            $step2=$step1.$salt.'/';
	            $typeval=$this->input->post('type',true);
	            $submittype=$this->input->post('submittype',true);
	            $docvalid=$this->input->post('docvalid',true);
	            
	            $this->form_validation->set_rules('docvalid','doc id not valid','trim|required|numeric|min_length[1]|max_length[5]|numeric|regex_match[/^[0-9,]+$/]');
	            if($this->form_validation->run() == FALSE){
	                echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
	            }
	            
	            $pty=$this->input->post('party_type',true);
	            $step3=$step2.$pty.'/';
	            $rqd_flnm=strtr($this->input->post('req_dtype'),['/'=>'_',' '=>'_']);
	            if(!is_dir($step1)) {
	                mkdir($step1, 0777, true);
	            }
	            if(!is_dir($step2)) {
	                mkdir($step2, 0777, true);
	            }
	            if(!is_dir($step3)) {
	                mkdir($step3, 0777, true);
	            }
	            $valume='';
	            
	            
	            $docname=$_FILES['userfile']['name'];
	            $array=explode('.',$_FILES['userfile']['name']);
	            if(substr_count($_FILES['userfile']['name'],'.')>1){
	                echo json_encode(['data'=>'','error' =>'File should have only single dot (.) extenction.']);die;
	            }
	            
	            $array=explode('.',$_FILES['userfile']['name']);
	            $config['upload_path']   		= $step3;
	            $config['allowed_types'] 		= 'jpg|jpeg|gif|png|pdf';
	            $config['max_size']      		= '199999';
	            $config['overwrite']	   		= TRUE;
	            $config['file_ext_tolower']	= TRUE;
	            $config['file_name']=$rqd_flnm.'_'.date('dmYHis').'.'.strtolower(end($array));
	            $this->load->library('upload', $config);
	            if(!$this->upload->do_upload('userfile') && $this->upload->data('file_name'))
	                echo json_encode(['data'=>'','error' => strip_tags($this->upload->display_errors()),'file_name'=>'']);
	                else 	{
	                    $final_doc_url=$step3.$config['file_name'];
	                    $pages=$this->countPages($final_doc_url);
	                    $data=array(
	                        'salt' 					=>$salt,
	                        'user_id' 				=>$user_id,
	                        'document_filed_by' 	=>$pty,
	                        'matter' 	            =>$matter,
	                        'no_of_pages'           =>$pages,
	                        'document_type' 		=>$rqd_flnm,
	                        'file_url' 				=>$final_doc_url,
	                        'doc_type' 				=>$typeval,
	                        'submit_type'           =>$submittype,
	                        'docid'                 =>$docvalid,
	                        'doc_name'              =>$docname,
	                        'valumeno'              =>$valume,
	                    );
	                    $valTxt=$final_doc_url;
	                    $key="01234567890123456789012345678901"; // 32 bytes
	                    $vector="1234567890123412"; // 16 bytes
	                    $encrypted = $this->myCrypt($valTxt, $key, $vector); 
	                    $st=$this->efiling_model->insert_query('temp_documents_upload',$data);
	                    echo json_encode(['data'=>'success','error' => '0','file_name'=>$encrypted]);
	                }
	        }
	    }
	    else
	        echo json_encode(['data'=>'Invalid request foud, try with valid details','error' => '1','file_name'=>'']);
	}
	

	
	function view_doc($path){
	    //echo "sdfsdf";die;
	    $userdata=$this->session->userdata('login_success');
	    $user_id=$userdata[0]->id;
	    if (!empty($path) && $user_id!='') {
	        $key="01234567890123456789012345678901"; // 32 bytes
	        $vector="1234567890123412"; // 16 bytes
	        $path=base64_decode($path);
	        $path =$this->myDecrypt($path, $key, $vector);
	        if(strpos($path, 'upload_doc/efiling')){
	            if(file_exists($path)){
	                if(!strpos($path,"/../")){
	                    $file = time();
	                    header('Content-Type:application/pdf');
	                    header('Content-Disposition: inline; filename="'.basename($file).'"');
	                    readfile($path);
	                }
	            }else{
	                echo 'Permission denied';die;
	            }
	        }else{
	            echo 'Permission denied';die;
	        }
	    } else {
	        echo 'Permission denied';die;
	    }
	}
	
	
	function defect_view($path){
	    $userdata=$this->session->userdata('login_success');
	    $user_id=$userdata[0]->id;
	    if (!empty($path) && $user_id!='') {
	        $key="01234567890123456789012345678901"; // 32 bytes
	        $vector="1234567890123412"; // 16 bytes
	        $path=base64_decode($path);       
	        $path =$this->myDecrypt($path, $key, $vector);
	        if(strpos($path, 'defectlatter/')){
	            if(file_exists($path)){
	                if(!strpos($path,"/../")){
	                    $file = time();
	                    header('Content-Type:application/pdf');
	                    header('Content-Disposition: inline; filename="'.basename($file).'"');
	                    readfile($path);
	                }
	            }else{
	                echo 'Permission denied';die;
	            }
	        }else{
	            echo 'Permission denied';die;
	        }
	    } else {
	        echo 'Permission denied';die;
	    }
	}


	function causelist_view($path){
	    $userdata=$this->session->userdata('login_success');
	    $user_id=$userdata[0]->id;
	    if (!empty($path) && $user_id!='') {
	        $key="01234567890123456789012345678901"; // 32 bytes
	        $vector="1234567890123412"; // 16 bytes
	        $path=base64_decode($path);
	        $path =$this->myDecrypt($path, $key, $vector);
	        if(strpos($path, 'causelist/')){
	            if(file_exists($path)){
	                if(!strpos($path,"/../")){
	                    $file = time();
	                    header('Content-Type:application/pdf');
	                    header('Content-Disposition: inline; filename="'.basename($file).'"');
	                    readfile($path);
	                }
	            }else{
	                echo 'Permission denied';die;
	            }
	        }else{
	            echo 'Permission denied';die;
	        }
	    } else {
	        echo 'Permission denied';die;
	    }
	}

	

	
	
	

}//**********END Main function ************/