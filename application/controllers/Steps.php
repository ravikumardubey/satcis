<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class Steps extends CI_Controller {
	    function __construct() { error_reporting(0);   
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
			else $_REQUEST[$key]=htmlspecialchars(strtr($val, $spcl_char));
			endforeach;
	    }
	    
	    
	    function draftrefiling($salt,$tab){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id!=''){
	            $this->session->set_userdata('salt',$salt);
	            if($tab=='0'){
	                $this->checklist();
	            }
	            if($tab=='1'){
	                $this->basic_detailsdraft();
	            }
	            if($tab=='2'){
	                $this->applicant();
	            }
	            if($tab=='3'){
	                $this->respondent();
	            }
	            if($tab=='4'){
	                $this->counsel();
	            }
	            if($tab=='5'){
	                $this->ia_detail();
	            }
	            if($tab=='6'){
	                $this->other_fee();
	            }
	            if($tab=='7'){
	                $this->document_upload();
	            }
	            if($tab=='8'){
	                $this->payment_mode();
	            }
	            if($tab=='9'){
	                $this->final_preview();
	            }
	            
	            
	        }else{
	            echo json_encode(['data'=>'failed','display'=>'','error'=>'User Not Valid']);
	        }
	    }

	    function basic_details(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['datacomm']== '';
    	        $this->load->view('admin/basic_details',$data);
	        }
	    }
	    
	    
	    
	    function basic_detailsdraft(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['datacomm']== '';
	            $this->load->view('admin/basic_details',$data);
	        }
	    }
	    
	    
	    
	    function checklist(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
			$saltval=$this->session->userdata('salt');
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
	        if($user_id){
	            $this->load->view("admin/checkList_first",$data);
	        }
	    }
	    
	    function applicant(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['regcase']= $this->efiling_model->registerdcases_list();
	            $this->load->view("admin/applicant",$data);
	        }
	    }
	    
	    function respondent(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['regcase']= $this->efiling_model->registerdcases_list();
	            $this->load->view("admin/respondent",$data);
	        }
	    }
	    
	    function counsel(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['regcase']= $this->efiling_model->registerdcases_list();
	            $this->load->view("admin/counsel",$data);
	        }
	    }
	    
	    function ia_detail(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['regcase']= $this->efiling_model->registerdcases_list();
	            $this->load->view("admin/ia_detail",$data);
	        }
	    }

	    
	    function other_fee(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['regcase']= $this->efiling_model->registerdcases_list();
	            $this->load->view("admin/other_fee",$data);
	        }
	    }
	    function document_upload(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['regcase']= $this->efiling_model->registerdcases_list();
	            $this->load->view("admin/document_upload",$data);
	        }
	    }
	    function payment_mode(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['regcase']= $this->efiling_model->registerdcases_list();
	            $this->load->view("admin/payment_mode",$data);
	        }
	    }
	    function final_preview(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=$userdata[0]->id;
	        if($user_id){
	            $data['regcase']= $this->efiling_model->registerdcases_list();
	            $this->load->view("admin/final_preview",$data);
	        }
	    }
	    
	    

	    
	    function getApplant(){
	        $userdata=$this->session->userdata('login_success');
	        $salt=$this->session->userdata('salt');
	        $subtoken=$this->session->userdata('submittoken');
	        $token=$_REQUEST['token'];
	        $user_id=$userdata[0]->id;
	        $id=$_REQUEST['id'];
	        $type=$_REQUEST['type'];
	        $stateid='';
	        $disid='';
	        if($subtoken==$token){
	            if($type=='add'){
    	            $data= $this->efiling_model->edit_data('sat_temp_additional_party','id',$id);
    	            $st = $this->efiling_model->data_list_where('master_psstatus','state_code',$data->pet_state);
    	            $stateid=$data->pet_state;
    	            $statename = $st[0]->state_name;
    	            $distname = '';
    	            if ($data->pet_state!= "") {
    	                $stdit = $this->efiling_model->data_list_where('master_psdist','district_code',$data->pet_dis);
    	                $distname = $stdit[0]->district_name;
    	                $disid= $data->pet_dis;
    	            }
    	            $date=array(
    	                'pet_name' =>$data->pet_name,
    	                'pet_degingnation' => $data->pet_degingnation,
    	                'pet_address' =>$data->pet_address,
    	                'pin_code' =>$data->pin_code,
    	                'pet_state' =>$statename,
    	                'pet_dis' =>$distname,
    	                'pet_mobile' =>$data->pet_mobile,
    	                'pet_phone' =>$data->pet_phone,
    	                'pet_email' =>$data->pet_email,
    	                'pet_fax' =>$data->pet_fax,
    	                'party_id' =>$data->party_id,
    	                'id'=> $data->id,
    	                'action'=> 'edit',
    	                'type'=> 'addparty',
    	                'stateid' =>$stateid,
    	                'disid' =>$disid,
    	            );
    	            echo json_encode($date);
	            }
	            if($type=='main'){
	                $data= $this->efiling_model->edit_data('sat_temp_appellant','salt',$salt);
	                $st = $this->efiling_model->data_list_where('master_psstatus','state_code',$data->pet_state);
	                $statename = $st[0]->state_name;
	                $distname = '';
	                if ($data->pet_state!= "") {
	                    $stdit = $this->efiling_model->data_list_where('master_psdist','district_code',$data->pet_dist);
	                    $distname = $stdit[0]->district_name;
	                }	 
	                
	                $stateid=$data->pet_state;
	                $disid= $data->pet_dist;
	                
	                // pet_name,pet_fax,pet_state,pet_address,pet_degingnation,pet_dist,pet_email,pet_id,pet_type      
	                $date=array(
	                    'pet_name' =>$data->pet_name,
	                    'pet_degingnation' => $data->pet_degingnation,
	                    'pet_address' =>$data->pet_address,
	                    'pin_code' =>$data->pincode,
	                    'pet_state' =>$statename,
	                    'pet_dis' =>$distname,
	                    'pet_mobile' =>$data->petmobile,
	                    'pet_phone' =>$data->petphone,
	                    'pet_email' =>$data->pet_email,
	                    'pet_fax' =>$data->pet_fax,
	                    'party_id' =>$data->pet_id,
	                    'id'=> $data->salt,
	                    'action'=> 'edit',
	                    'state'=> $data->pet_state,
	                    'dist'=> $data->pet_dist,	 
	                    'dist'=> $data->pet_type,	 
	                    'type'=> 'mainparty',
	                    'stateid' =>$stateid,
	                    'disid' =>$disid,
	                );
	                echo json_encode($date);
	            }
	        }else{
	            echo "Request not valid!";die;
	        }
	    }
	    
	
	    function editSubmitApplent(){
	        $userdata=$this->session->userdata('login_success');
	        $salt=$this->session->userdata('salt');
	        $subtoken=$this->session->userdata('submittoken');
	        $token=$_REQUEST['token'];
	        $user_id=$userdata[0]->id;
	        $id=$this->input->post('id',true);
	        
	        $petname = htmlspecialchars($this->input->post('petName',true));
	        if(is_numeric($petname)){
	            $hscquery = $this->efiling_model->data_list_where('master_org','org_id',$petname);
	            $petname = $hscquery[0]->orgdisp_name;
	        }
	        $this->form_validation->set_rules('petmobile','Please enter mobile number','trim|required|min_length[1]|max_length[10]|numeric');
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
	        
	        
	        $this->form_validation->set_rules('petEmail','Please enter email address','trim|required|valid_email|min_length[1]|max_length[50]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter email address','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        $this->form_validation->set_rules('id','Please enter valid id','trim|required|min_length[1]|max_length[6]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter valid id','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        $edittype=$this->input->post('edittype');
	        if($token==$subtoken){
	            if($edittype=='addparty'){
    	            $array = array(
    	                'pet_name' =>$petname,
    	                'pet_address' => $this->input->post('petAddress',true),
    	                'pin_code' =>$this->input->post('pincode',true),
    	                'pet_state' =>$this->input->post('dstate',true),
    	                'pet_dis' =>$this->input->post('ddistrict',true),
    	                'pet_mobile' =>$this->input->post('petmobile',true),
    	                'pet_phone' => $this->input->post('petPhone',true),
    	                'pet_email' =>$this->input->post('petEmail',true),
    	                'pet_fax' => $this->input->post('petFax',true),
    	                'party_id'=>$this->input->post('petName',true),
    	                'user_id'=>$user_id,
    	            );
    	            $where=array('id'=>$id);
    	            $st = $this->efiling_model->update_data_where('sat_temp_additional_party',$where,$array);
    	            $additionalparty=$this->efiling_model->data_list_where('sat_temp_additional_party','salt',$salt);
    	            $htmladditonalparty=$this->htmaladditionalparty($additionalparty,$salt);
    	            if($st){
    	                echo json_encode(['data'=>'success','display'=>$htmladditonalparty,'error'=>'0','4 last_query'=>$this->db->last_query()]);
    	            }
	            }
	            if($edittype=='mainparty'){
	                $array = array(
	                    'pet_name' =>$petname,
	                    'pet_address' =>$this->input->post('petAddress',true),
	                    'pincode' =>$this->input->post('pincode',true),
	                    'pet_state' =>$this->input->post('dstate',true),
	                    'pet_dist' =>$this->input->post('ddistrict',true),
	                    'petmobile' =>$this->input->post('petmobile',true),
	                    'petphone' =>$this->input->post('petPhone',true),
	                    'pet_email' =>$this->input->post('petEmail',true),
	                    'pet_fax' =>$this->input->post('petFax',true),
	                    'pet_id'=>$this->input->post('petName',true),
	                );
	                $where=array('salt'=>$salt);
	                $st = $this->efiling_model->update_data_where('sat_temp_appellant',$where,$array);
	                $additionalparty=$this->efiling_model->data_list_where('sat_temp_additional_party','salt',$salt);
	                $htmladditonalparty=$this->htmaladditionalparty($additionalparty,$salt);
	                if($st){
	                    echo json_encode(['data'=>'success','display'=>$htmladditonalparty,'error'=>'0','4 last_query'=>$this->db->last_query()]);
	                }
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
    	            $html.='
                    <tr style="color:green">
                      <td> 1</td>
            	        <td>'.$petName.'(A-1)</td>
            	        <td>'. $vals[0]->petmobile.'</td>
            	        <td>'.$vals[0]->pet_email.'</td>
            	        <td><input type="button" name="nextsubmit" id="nextsubmit" value="Edit" class="btn1"  data-toggle="modal" data-target="#exampleModal" onclick="editParty('.$vals[0]->salt.','.$appleant.','.$type.')"></td>
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
    	                $html.=
    	                '<tr>
                	        <td>'.$i.'</td>
                	        <td>'.$petName.'(A-'.$i.')</td>
                	        <td>'.$val->pet_mobile.'</td>
                	        <td>'.$val->pet_email.'</td>
                	        <td><input type="button" name="nextsubmit" id="nextsubmit" value="Edit" class="btn1" data-toggle="modal" data-target="#exampleModal"  onclick="editParty('.$val->id.','.$appleant.','.$type.')"></td>
                            <td><input type="button" name="nextsubmit" id="nextsubmit" value="Delete" class="btn1" onclick="deleteParty('.$val->id.','.$appleant.')"></td>
                	        </td>
                          </tr>';
    	                $i++;
    	            }
    	        }
    	        return $html;
	    }	 
	    
	    
	    
	    
	    
	    function getRespondent(){
	        $userdata=$this->session->userdata('login_success');
	        $salt=$this->session->userdata('salt');
	        $subtoken=$this->session->userdata('submittoken');
	        $token=$_REQUEST['token'];
	        $user_id=$userdata[0]->id;
	        $id=$_REQUEST['id'];
	        $type=$_REQUEST['type'];
	        if($subtoken==$token){
	            if($type=='add'){
	                $data= $this->efiling_model->edit_data('sat_temp_additional_res','id',$id);
	                $st = $this->efiling_model->data_list_where('master_psstatus','state_code',$data->res_state);
	                $statename = $st[0]->state_name;
	                $distname = '';
	                if ($data->res_state!= "") {
	                    $stdit = $this->efiling_model->data_list_where('master_psdist','district_code',$data->res_dis);
	                    $distname = $stdit[0]->district_name;
	                }
	                $date=array(
	                    'pet_name' =>$data->res_name,
	                    'pet_degingnation' => $data->res_degingnation,
	                    'pet_address' =>$data->res_address,
	                    'pin_code' =>$data->res_code,
	                    'pet_state' =>$statename,
	                    'pet_dis' =>$distname,
	                    'state'=> $data->res_state,
	                    'dist'=> $data->res_dis,
	                    'pet_mobile' =>$data->res_mobile,
	                    'pet_phone' =>$data->res_phone,
	                    'pet_email' =>$data->res_email,
	                    'pet_fax' =>$data->res_fax,
	                    'party_id' =>$data->party_id,
	                    'id'=> $data->id,
	                    'action'=> 'edit',
	                    'type'=> 'addparty',
	                );
	                echo json_encode($date);
	            }
	            if($type=='main'){
	                $data= $this->efiling_model->edit_data('sat_temp_appellant','salt',$salt);
	                $st = $this->efiling_model->data_list_where('master_psstatus','state_code',$data->res_state);
	                $statename = $st[0]->state_name;
	                $distname = '';
	                if ($data->res_state!= "") {
	                    $stdit = $this->efiling_model->data_list_where('master_psdist','district_code',$data->res_dis);
	                    $distname = $stdit[0]->district_name;
	                }
	                $date=array(
	                    'pet_name' =>$data->resname,
	                    'pet_degingnation' => $data->res_degingnation,
	                    'pet_address' =>$data->res_address,
	                    'pin_code' =>$data->res_pin,
	                    'pet_state' =>$statename,
	                    'pet_dis' =>$distname,
	                    'pet_mobile' =>$data->res_mobile,
	                    'pet_phone' =>$data->res_phone,
	                    'pet_email' =>$data->res_email,
	                    'pet_fax' =>$data->res_fax,
	                    'party_id' =>$data->party_id,
	                    'id'=> $data->salt,
	                    'action'=> 'edit',
	                    'state'=> $data->res_state,
	                    'dist'=> $data->res_dis,
	                    'res_type'=> $data->res_type,
	                    'type'=> 'mainparty',
	                );
	                echo json_encode($date);
	            }
	        }else{
	            echo "Request not valid!";die;
	        }
	    }
	    
	    

	    function editSubmitRespondent(){
	        $userdata=$this->session->userdata('login_success');
	        $salt=$this->session->userdata('salt');
	        $subtoken=$this->session->userdata('submittoken');
	        $token=$_REQUEST['token'];
	        $user_id=$userdata[0]->id;
	        $id=$this->input->post('id');
	        $resname = htmlspecialchars($this->input->post('resName',true));
	        if(is_numeric($resname)){
	            $hscquery = $this->efiling_model->data_list_where('master_org','org_id',$resname);
	            $resname = $hscquery[0]->orgdisp_name;
	        }
	        
	        $this->form_validation->set_rules('resName','Please enter respondent','trim|required|min_length[1]|max_length[250]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter respondent','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        $this->form_validation->set_rules('resMobile','Please enter mobile number','trim|required|min_length[1]|max_length[10]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter mobile number','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        $this->form_validation->set_rules('stateRes','Please enter state','trim|required|min_length[1]|max_length[4]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter state','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        $this->form_validation->set_rules('ddistrictres','Please enter district','trim|required|min_length[1]|max_length[4]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter district','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        
	        
	        $this->form_validation->set_rules('resEmail','Please enter email','trim|valid_email|required|min_length[1]|max_length[50]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter email','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        $this->form_validation->set_rules('id','Please enter name','trim|required|min_length[1]|max_length[8]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter name','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        
	        $edittype=$_REQUEST['edittype'];
	        if($token==$subtoken){
	            if($edittype=='addparty'){
	                $array = array(
	                    'res_name' =>$resname,
	                    'res_address' => $this->input->post('resAddress',true),
                        'res_code' =>$this->input->post('respincode',true),
                        'res_state' =>$this->input->post('stateRes',true),
                        'res_dis' =>$this->input->post('ddistrictres',true),
                        'res_mobile' =>$this->input->post('resMobile',true),
                        'res_phone' =>$this->input->post('resPhone',true),
                        'res_email' =>$this->input->post('resEmail',true),
                        'res_fax' =>$this->input->post('resFax',true),
	                    'party_id'=>$id,
	                    'user_id'=>$user_id,
	                );
	                $where=array('id'=>$id);
	                $st = $this->efiling_model->update_data_where('sat_temp_additional_res',$where,$array);
	                $additionalparty=$this->efiling_model->data_list_where('sat_temp_additional_res','salt',$salt);
	                $htmladditonalparty=$this->htmaladditionalrespondentparty($additionalparty,$salt);
	                if($st){
	                    echo json_encode(['data'=>'success','display'=>$htmladditonalparty,'error'=>'0','4 last_query'=>$this->db->last_query()]);
	                }
	            }
	            
	            if($edittype=='mainparty'){
	                $array = array(
	                    'resname' =>$resname,
	                    'res_address' =>$this->input->post('resAddress',true),
	                    'res_pin' =>$this->input->post('respincode',true),
	                    'res_state' =>$this->input->post('stateRes',true),
	                    'res_dis' =>$this->input->post('ddistrictres',true),
	                    'res_mobile' =>$this->input->post('resMobile',true),
	                    'res_phone' =>$this->input->post('resPhone',true),
	                    'res_email' =>$this->input->post('resEmail',true),
	                    'res_fax' =>$this->input->post('resFax',true),
	                    'res_id'=>$this->input->post('resName',true),
	                    'user_id'=>$user_id,
	                );
	                $where=array('salt'=>$salt);
	                $st = $this->efiling_model->update_data_where('sat_temp_appellant',$where,$array);
	                $additionalparty=$this->efiling_model->data_list_where('sat_temp_additional_res','salt',$salt);
	                $htmladditonalparty=$this->htmaladditionalrespondentparty($additionalparty,$salt);
	                if($st){
	                    echo json_encode(['data'=>'success','display'=>$htmladditonalparty,'error'=>'0','4 last_query'=>$this->db->last_query()]);
	                }
	            }
	        }
	    }
	    
	    
	    function htmaladditionalrespondentparty($additionalresparty,$salt){
	        $html='';
	        $appleant="'res'";
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
	    
	    function addCouncel(){
	        $userdata=$this->session->userdata('login_success');
	        $salt=$this->session->userdata('salt');
	        $user_id=$userdata[0]->id;
	        $id =$this->input->post('id',true);
	        $partyType=$this->input->post('partyType',true);
	        $advType=$this->input->post('advType',true);
	        $petadvName='';
	        if($salt!=''){
	            $hscquery = $this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
	            $petadvName = $hscquery[0]->pet_council_adv;
	        }
	        if($advType=='1'){
	            $councilCode= htmlspecialchars($this->input->post('councilCode'));
	            $this->form_validation->set_rules('councilCode','Please add councel','trim|required|min_length[1]|max_length[10]|numeric');
	            if($this->form_validation->run() == FALSE){
	                echo json_encode(['data'=>'error','value'=>'Please add councel','display'=>validation_errors(),'error'=>'1']);die;
	            }
	            if(is_numeric($councilCode)){
	                $hscquery = $this->efiling_model->data_list_where('master_advocate','adv_code',$councilCode);
	                $advName = $hscquery[0]->adv_name;
	            }
	        }
	        if($advType=='2'){
	            $councilCode= htmlspecialchars($this->input->post('councilCode'));
	            $this->form_validation->set_rules('councilCode','Please add councel','trim|required|min_length[1]|max_length[10]|numeric');
	            if($this->form_validation->run() == FALSE){
	                echo json_encode(['data'=>'error','value'=>'Please add councel','display'=>validation_errors(),'error'=>'1']);die;
	            }
	            if(is_numeric($councilCode)){
	                $hscquery = $this->efiling_model->data_list_where('efiling_users','id',$councilCode);
	                $advName = $hscquery[0]->fname.' '.$hscquery[0]->lname;
	            }
	        }
	        $this->form_validation->set_rules('salt','Please enter salt','trim|required|min_length[1]|max_length[10]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter salt','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('counselMobile','Please enter mobile number','trim|required|min_length[1]|max_length[10]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter mobile number','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('cdstate','Please enter state','trim|required|min_length[1]|max_length[4]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter state','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('cddistrict','Please enter district','trim|required|min_length[1]|max_length[4]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter district','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('counselEmail','Please enter email','trim|required|min_length[1]|max_length[50]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter email','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('counselAdd','Please enter address','trim|required|min_length[1]|max_length[250]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter address','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('counselPhone','Please enter numeric  phone number','trim|max_length[11]|regex_match[/^[0-9]+$/]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter numeric phone number','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('counselPin','Please enter numeric  pin code','trim|min_length[6]|max_length[6]|numeric');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter numeric  pin code','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        $this->form_validation->set_rules('counselFax','Please enter numeric Fax','trim|max_length[11]|regex_match[/^[0-9]+$/]');
	        if($this->form_validation->run() == FALSE){
	            echo json_encode(['data'=>'error','value'=>'Please enter numeric Fax','display'=>validation_errors(),'error'=>'1']);die;
	        }
	        $edittype=$this->input->post('action',true);
	        $advType=$this->input->post('advType',true);
	         //Add Advocate Main   
	            if($edittype=='add' && $petadvName==''){
    	            $this->form_validation->set_rules('counselMobile', 'Choose council mobile', 'trim|required|numeric|max_length[200]');
    	            if($this->form_validation->run() == TRUE) {
    	                $query_params=array(
    	                    'counsel_add'=>$this->input->post('counselAdd',true),
    	                    'counsel_pin'=>$this->input->post('counselPin',true),
    	                    'counsel_mobile'=>$this->input->post('counselMobile',true),
    	                    'counsel_email'=>$this->input->post('counselEmail',true),
    	                    'pet_council_adv'=>$councilCode,
    	                    'counsel_fax'=>$this->input->post('counselFax',true),
    	                    'counsel_phone'=>$this->input->post('counselPhone',true),
    	                    'advType'=>$advType,
    	                );
    	                $data_app=$this->efiling_model->update_data('sat_temp_appellant', $query_params,'salt', $salt);
    	                $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
    	                $getAdvocatelist=$this->getAdvocatelist($advocatelist,$salt);
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
	                    'counsel_add'=>$this->input->post('counselAdd',true),
	                    'counsel_pin'=>$this->input->post('counselPin',true),
	                    'counsel_mobile'=>$this->input->post('counselMobile',true),
	                    'counsel_email'=>$this->input->post('counselEmail',true),
	                    'council_code'=>$councilCode,
	                    'counsel_fax'=>$this->input->post('counselFax',true),
	                    'counsel_phone'=>$this->input->post('counselPhone',true),
	                    'user_id'=>$user_id,
	                    'adv_district'=>$this->input->post('cddistrict',true),
	                    'adv_state'=>$this->input->post('cdstate',true),
	                    'entry_time'=>date('Y-m-d'),
	                    'advType'=>$advType,
	                    'patitiontype'=>'app',
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
	                    $id=$this->input->post('id',true);
	                    $advid=$this->input->post('councilCode',true);
                        if(is_numeric($councilCode)){
                            $hscquery = $this->efiling_model->data_list_where('master_advocate','adv_code',$advid);
                            $advName = $hscquery[0]->adv_name;
                        }
                        $query_params=array(
                            'counsel_add'=>$this->input->post('counselAdd',true),
                            'counsel_pin'=>$this->input->post('counselPin',true),
                            'counsel_mobile'=>$this->input->post('counselMobile',true),
                            'counsel_email'=>$this->input->post('counselEmail',true),
                            'pet_council_adv'=>$councilCode,
                            'counsel_fax'=>$this->input->post('counselFax',true),
                            'counsel_phone'=>$this->input->post('counselPhone',true),
                            'advType'=>$advType,
                        );
                        $data_app=$this->efiling_model->update_data('sat_temp_appellant', $query_params,'salt', $id);
    	                $advocatelist=$this->efiling_model->data_list_where('sat_temp_add_advocate','salt',$salt);
    	               // $this->db->last_query();
    	                $getAdvocatelist=$this->getAdvocatelist($advocatelist,$salt);
    	                if($data_app){
    	                    echo json_encode(['data'=>'success','display'=>$getAdvocatelist,'error'=>'0']);die;
    	                }
	                }
	                
	              
	                if($partyType=='add'){
	                    $id=$this->input->post('id',true);
	                    $advid=$this->input->post('councilCode',true);
	                    
	                    if($salt!=''){
	                        $hscquery = $this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
	                        $petadvName = $hscquery[0]->pet_council_adv;
	                    }
	                    if($advType=='1'){
	                        $councilCode= htmlspecialchars($this->input->post('councilCode',true));
	                        if(is_numeric($councilCode)){
	                            $hscquery = $this->efiling_model->data_list_where('master_advocate','adv_code',$councilCode);
	                            $advName = $hscquery[0]->adv_name;
	                        }
	                    }
	                    if($advType=='2'){
	                        $councilCode= htmlspecialchars($this->input->post('councilCode',true));
	                        if(is_numeric($councilCode)){
	                            $hscquery = $this->efiling_model->data_list_where('efiling_users','id',$councilCode);
	                            $advName = $hscquery[0]->fname.' '.$hscquery[0]->lname;
	                        }
	                    }
	                    $array = array(
	                        'adv_name'=>$advName,
	                        'counsel_add'=>$this->input->post('counselAdd',true),
	                        'counsel_pin'=>$this->input->post('counselPin',true),
	                        'counsel_mobile'=>$this->input->post('counselMobile',true),
	                        'counsel_email'=>$this->input->post('counselEmail',true),
	                        'council_code'=>$this->input->post('councilCode',true),
	                        'counsel_fax'=>$this->input->post('counselFax',true),
	                        'counsel_phone'=>$this->input->post('counselPhone',true),
	                        'user_id'=>$user_id,
	                        'adv_district'=>$this->input->post('cddistrict',true),
	                        'adv_state'=>$this->input->post('cdstate',true),
	                        'entry_time'=>date('Y-m-d'),
	                        'advType'=>$advType,
	                        'patitiontype'=>'app',
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
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    function deleteAdvocate(){
	        $msg='';
	        $userdata=$this->session->userdata('login_success');
	        $salt=$this->session->userdata('salt');
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
	    
	    
	    function getAdvDetailEdit(){
	        $array=array();
	        $userdata=$this->session->userdata('login_success');
	        $salt=$this->session->userdata('salt');
	        $subtoken=$this->session->userdata('submittoken');
	        $token=$_REQUEST['token'];
	        $user_id=$userdata[0]->id;
	        $id=$_REQUEST['id'];
	        if($token==$subtoken){
    	        $type=$_REQUEST['type'];
    	        $advType=$_REQUEST['advType'];
    	           if($type=='main'){
    	            $st=$this->efiling_model->data_list_where('sat_temp_appellant','salt',$id);
    	            $advc=$st[0]->pet_council_adv;
    	            if (is_numeric($advc)) {
    	                $salt=$st[0]->salt;
    	                if($advType=='1'){
        	                $orgname=$this->efiling_model->data_list_where('master_advocate','adv_code',$advc);
        	                $adv_name=$orgname[0]->adv_name;
        	                $adv_fax=$orgname[0]->adv_reg;
        	                $adv_mobile=$orgname[0]->adv_mobile;
        	                $email=$orgname[0]->email;
        	                $adv_phone=$orgname[0]->adv_phone;
        	                $state_code=$orgname[0]->state_code;
        	                $adv_pin=$orgname[0]->adv_pin;
        	                $adv_dist=$orgname[0]->adv_dist;
        	                $address=$orgname[0]->address;
        	                $adv_fax=$orgname[0]->adv_fax;
        	                $adv_code=$orgname[0]->adv_code;
        	                if($orgname[0]->state_code!=''){
        	                    $st3 = $this->efiling_model->data_list_where('master_psstatus','state_code',$orgname[0]->state_code);
        	                    $statename= $st3[0]->state_name;
        	                }
        	                if($orgname[0]->adv_dist!=''){
        	                    $st2 = $this->efiling_model->data_list_where('master_psdist','district_code',$orgname[0]->adv_dist);
        	                    $ddistrictname= $st2[0]->district_name;
        	                }
    	                }
    	                if($advType=='2'){
    	                    $orgname=$this->efiling_model->data_list_where('efiling_users','id',$advc);
    	                    $adv_name=$orgname[0]->fname.''.$orgname[0]->lname;
    	                    $adv_fax=$orgname[0]->adv_reg;
    	                    $adv_mobile=$orgname[0]->mobilenumber;
    	                    $email=$orgname[0]->email;
    	                    $adv_phone='';
    	                    $state_code=$orgname[0]->state;
    	                    $adv_pin=$orgname[0]->pincode;
    	                    $adv_dist=$orgname[0]->district;
    	                    $address=$orgname[0]->address;
    	                    $adv_fax='';
    	                    $adv_code=$orgname[0]->id;
    	                    if($state_code!=''){
    	                        $st3 = $this->efiling_model->data_list_where('master_psstatus','state_code',$state_code);
    	                        $statename= $st3[0]->state_name;
    	                    }
    	                    if($adv_dist!=''){
    	                        $st2 = $this->efiling_model->data_list_where('master_psdist','district_code',$adv_dist);
    	                        $ddistrictname= $st2[0]->district_name;
    	                    }
    	                }
    	            }
    	            $array = array(
    	                'salt'=>$salt,
    	                'id'=> $salt,
    	                'adv_name'=>$adv_name,
    	                'counsel_add'=>$address,
    	                'counsel_pin'=>$adv_pin,
    	                'counsel_mobile'=>$adv_mobile,
    	                'counsel_email'=>$email,
    	                'council_code'=>$adv_code,
    	                'counsel_fax'=>$adv_fax,
    	                'counsel_phone'=>$adv_phone,
    	                'adv_district'=>$adv_dist,
    	                'adv_state'=>$state_code,
    	                'ddistrictname'=>$ddistrictname,
    	                'statename'=>$statename, 
    	                'action'=>'edit',
    	                'advType'=>$advType,
    	            );
    	            $data=json_encode($array);
    	            $msg="Something went wrong";
    	            echo json_encode(['data'=>'','display'=>$data,'error'=>'1','massage'=>$msg]);die;
    	        }
    	        
    	        
    	        

    	        if($type=='add'){
    	            $st = $this->efiling_model->data_list_where('sat_temp_add_advocate','id',$id);
    	            if($st[0]->adv_state!=''){
    	                $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$st[0]->adv_state);
    	                $statename= $st2[0]->state_name;
    	            }
    	            if($st[0]->adv_district!=''){
    	                $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$st[0]->adv_district);
    	                $ddistrictname= $st1[0]->district_name;
    	            }

    	            $array = array(
    	                'salt'=>$salt,
    	                'id'=>$st[0]->id,
    	                'adv_name'=>$st[0]->adv_name,
    	                'counsel_add'=>$st[0]->counsel_add,
    	                'counsel_pin'=>$st[0]->counsel_pin,
    	                'counsel_mobile'=>$st[0]->counsel_mobile,
    	                'counsel_email'=>$st[0]->counsel_email,
    	                'council_code'=>$st[0]->council_code,
    	                'counsel_fax'=>$st[0]->counsel_fax,
    	                'counsel_phone'=>$st[0]->counsel_phone,
    	                'adv_district'=>$st[0]->adv_district,
    	                'adv_state'=>$st[0]->adv_state,
    	                'ddistrictname'=>$ddistrictname,
    	                'statename'=>$statename, 
    	                'action'=>'edit',
    	                'advType'=>$advType,
    	            );
    	            $data=json_encode($array);
    	            $msg="Something went wrong";
    	            echo json_encode(['data'=>'','display'=>$data,'error'=>'1','massage'=>$msg]);die;
    	        }
    	        
	        }else{
	            $msg="Something went wrong";
	            echo json_encode(['data'=>'','display'=>'','error'=>'1','massage'=>$msg]);die;
	        }
	    }
	   
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    
	    function getAdvocatelist($advocatelist,$salt){
	        ini_set('display_errors', 1);
	        ini_set('display_startup_errors', 1);
	        error_reporting(E_ALL);
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
	        $vals=$this->efiling_model->data_list_where('sat_temp_appellant','salt',$salt);
	        if(!empty($vals)){
	        $advType=$vals[0]->advType;
	        if($vals[0]->pet_council_adv){
	            $counseladd=$vals[0]->pet_council_adv;
	            if($vals[0]->advType=='1'){
	                if (is_numeric($vals[0]->pet_council_adv)) {
	                    $orgname=$this->efiling_model->data_list_where('master_advocate','adv_code',$counseladd);
	                    $adv_name=$orgname[0]->adv_name;
	                    $adv_reg=$orgname[0]->adv_reg;
	                    $adv_mobile=$orgname[0]->adv_mobile;
	                    $email=$orgname[0]->email;
	                    $address=$orgname[0]->address;
	                    $pin_code=$orgname[0]->adv_pin;
	                    
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
        	        <td><center><input type="button" name="nextsubmit" id="nextsubmit" value="Edit" class="btn1" data-toggle="modal" data-target="#exampleModal"  onclick="editPartyAdv('.$vals[0]->salt.','.$type.','.$advType.')"></center></td>
                    <td></td>
                </tr>';
	        }
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
	    
	    
	    
	   
	    
	    function getAdvDetailinperson(){
	        $userdata=$this->session->userdata('login_success');
	        $salt=$this->session->userdata('salt');
	        $subtoken=$this->session->userdata('submittoken');
	        $token=$_REQUEST['token'];
	        $user_id=$userdata[0]->id;
	        $type=$_REQUEST['type'];
	        if($token==$subtoken){
	            $st = $this->efiling_model->data_list_where('efiling_users','id',$user_id);
	            if($st[0]->state!=''){
	                $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$st[0]->state);
	                $statename= $st2[0]->state_name;
	            }
	            if($st[0]->district!=''){
	                $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$st[0]->district);
	                $ddistrictname= $st1[0]->district_name;
	            }
    	        $array = array(
    	            'salt'=>$salt,
    	            'id'=>$st[0]->id,
    	            'adv_name'=>$st[0]->id,
    	            'counsel_add'=>$st[0]->address,
    	            'counsel_pin'=>$st[0]->pincode,
    	            'counsel_mobile'=>$st[0]->mobilenumber,
    	            'counsel_email'=>$st[0]->email,
    	            'council_code'=>'',
    	            'counsel_fax'=>'',
    	            'counsel_phone'=>'',
    	            'adv_district'=>$st[0]->district,
    	            'adv_state'=>$st[0]->state,
    	            'ddistrictname'=>$ddistrictname,
    	            'statename'=>$statename,
    	        );
    	        $data=json_encode($array);
    	        $msg="success";
    	        echo json_encode(['data'=>'success','display'=>$data,'error'=>'1','massage'=>$msg]);die;
	        }
	    }
	    
	    
	    function getAdvinpers(){
	        $userdata=$this->session->userdata('login_success');
	        $salt=$this->session->userdata('salt');
	        $subtoken=$this->session->userdata('submittoken');
	        $token=$_REQUEST['token'];
	        $user_id=$userdata[0]->id;
	        $id=$_REQUEST['id'];
	        if($token==$subtoken){
	            $st = $this->efiling_model->data_list_where('efiling_users','id',$id);
	            if($st[0]->state!=''){
	                $st2 = $this->efiling_model->data_list_where('master_psstatus','state_code',$st[0]->state);
	                $statename= $st2[0]->state_name;
	            }
	            if($st[0]->district!=''){
	                $st1 = $this->efiling_model->data_list_where('master_psdist','district_code',$st[0]->district);
	                $ddistrictname= $st1[0]->district_name;
	            }
	            $array = array(
	                'salt'=>$salt,
	                'id'=>$st[0]->id,
	                'adv_name'=>$st[0]->id,
	                'counsel_add'=>$st[0]->address,
	                'counsel_pin'=>$st[0]->pincode,
	                'counsel_mobile'=>$st[0]->mobilenumber,
	                'counsel_email'=>$st[0]->email,
	                'council_code'=>'',
	                'counsel_fax'=>'',
	                'counsel_phone'=>'',
	                'adv_district'=>$st[0]->district,
	                'adv_state'=>$st[0]->state,
	                'ddistrictname'=>$ddistrictname,
	                'statename'=>$statename,
	            );
	            $data=json_encode($array);
	            $msg="success";
	            echo json_encode(['data'=>'success','display'=>$data,'error'=>'1','massage'=>$msg]);die;
	        }
	    }
	    
	    
	    
	    
	    
	    function getApplantName(){
	        $key=$this->input->post();
	        $rs=$this->admin_model->getAppRecord($this->input->post());
	        $html='';
	        foreach($rs as $vals){
	            $html.='<li value="'.$vals->adv_code.'" onclick="showUserApp('.$vals->org_id.')">'.$vals->org_name.'</li>';
	        }
	        echo $html;die;
	    }
	    
	    
	    function getApplantNameEdit(){
	        $key=$this->input->post();
	        $rs=$this->admin_model->getAppRecord($this->input->post());
	        $html='';
	        foreach($rs as $vals){
	            $html.='<li value="'.$vals->adv_code.'" onclick="showUserAppedit('.$vals->org_id.')">'.$vals->org_name.'</li>';
	        }
	        echo $html;die;
	    }
	    
	    
	    
	    function getRespondentName(){
	        $key=$this->input->post();
	        $rs=$this->admin_model->getAppRecord($this->input->post());
	        $html='';
	        foreach($rs as $vals){
	            $html.='<li value="'.$vals->adv_code.'" onclick="showUserAppRes('.$vals->org_id.')">'.$vals->org_name.'</li>';
	        }
	        echo $html;die;
	    }
	    
	    
	    function getRespondentNameEdit(){
	        $key=$this->input->post();
	        $rs=$this->admin_model->getAppRecord($this->input->post());
	        $html='';
	        foreach($rs as $vals){
	            $html.='<li value="'.$vals->adv_code.'" onclick="showUserAppResEdit('.$vals->org_id.')">'.$vals->org_name.'</li>';
	        }
	        echo $html;die;
	    }
	    
	    
	    
	    function getAdv(){
	        $key=$this->input->post();
	        $rs=$this->admin_model->getAdv($this->input->post());
	        $html='';
	        foreach($rs as $vals){
	            $html.='<li value="'.$vals->adv_code.'" onclick="showUserOrg('.$vals->adv_code.')">'.$vals->adv_name.'</li>';
	        }
	        echo $html;die;
	    }
	    
	    
	    function getAdv1(){
	        $key=$this->input->post();
	        $rs=$this->admin_model->getAdv($this->input->post());
	        $html='';
	        foreach($rs as $vals){
	            $html.='<li value="'.$vals->adv_code.'" onclick="showUserOrg111('.$vals->adv_code.')">'.$vals->adv_name.'</li>';
	        }
	        echo $html;die;
	    }
	    
	    
	    
	    
	    
	    
	    



}//**********END Main function ************/