<?php defined('BASEPATH') OR exit('No direct script access allowed');

	class User extends CI_Controller {
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
	    
	    function editprofile(){
	        $userdata=$this->session->userdata('login_success');
	        $filingno=htmlentities($_REQUEST['filingno']);
	        $user_id=isset($userdata[0]->id)?$userdata[0]->id:'';
	        $data=array();
	        if($user_id){
	            $data['users']= $this->efiling_model->data_list_where('efiling_users','id',$user_id);
	            $data['states']=$this->admin_model->getStates();
	            if($_REQUEST['editprofile']=='editprofile'){    
	                $this->form_validation->set_rules('fname', 'Enter first Name', 'trim|required|min_length[1]|max_length[100]');
	                $this->form_validation->set_rules('lname', 'Enter last Name', 'trim|required|min_length[1]|max_length[100]');
	                $this->form_validation->set_rules('mobilenumber', 'Enter mobile number.', 'trim|required|numeric|min_length[1]|max_length[10]');
	                $this->form_validation->set_rules('address', 'Enter valid address', 'trim|required|min_length[1]|max_length[200]');
	                $this->form_validation->set_rules('email', 'Enter Email.', 'trim|required|max_length[150]');
	                $this->form_validation->set_rules('pincode', 'Enter valid amount.', 'trim|required|numeric|min_length[1]|max_length[6]');
	                $this->form_validation->set_rules('country', 'Enter valid country.', 'trim|required|min_length[1]|max_length[5]');
	                $this->form_validation->set_rules('statename', 'Enter valid state', 'trim|required|numeric|min_length[1]|max_length[3]');
	                $this->form_validation->set_rules('district', 'Enter valid district.', 'trim|required|numeric|min_length[1]|max_length[3]');
	                if($this->form_validation->run() == false){
	                    $data['data']='error';
	                    $data['errors']=validation_errors();
	                    $this->load->view("admin/editprofile",$data);
	                }else{
	                    $array=array(
	                        'fname'=>$this->input->post('fname', TRUE), 
	                        'lname'=>$this->input->post('lname', TRUE), 
	                        'mobilenumber'=>$this->input->post('mobilenumber', TRUE),  
	                        'address'=>$this->input->post('address', TRUE), 
	                        'email'=>$this->input->post('email', TRUE),  
	                        'pincode'=>$this->input->post('pincode', TRUE), 
	                        'country'=>$this->input->post('country', TRUE), 
	                        'state'=>$this->input->post('statename', TRUE),  
	                        'district'=>$this->input->post('district', TRUE),  
	                    );
	                    $where=array('id'=>$user_id);
	                    $this->efiling_model->update_data_where('efiling_users',$where,$array);
	                    $data['users']= $this->efiling_model->data_list_where('efiling_users','id',$user_id);
	                    $data['msg']='Record successfully updated';
	                    $this->load->view("admin/editprofile",$data);
	                }
	            }else{
    	            $data['msg']='';
    	            $this->load->view("admin/editprofile",$data);
	            }
	        }
	    }
	    
	    
	    
	    function user_menu_data($user_id,$username) {
	            $query=$this->db->query("select menu_access from users where id='$user_id' AND username='$username'");
	            $partno= $query->result();
	            $menu_access = json_decode($partno,true);
	            return $menu_access;
	    }
	    
	    
	    
	    function menu_list($user_id,$username) {
	        $menu_access =   user_menu_data($user_id,$username);
	        $arr_data = array();
	        $status = '1';
	        if(!empty($menu_access) && is_array($menu_access)) {
	            foreach($menu_access as $key=>$value) {
	                if(!empty($key)) {
	                    $query=$this->db->query("select name from menu where status='$status' AND id='$key' order by priority ASC");
	                    $menu= $query->result();
	                    if(!empty($menu) && is_array($menu))  {
	                        foreach($menu as $val) {
	                            $query=$this->db->query("select name,folder_name,page_name from menu_sub from menu menu_sub status = '$status' and id = '$val' and menu_id = '$key'  order by priority ASC");
	                            $menu_data= $query->result();
	                            $temp_arra = array();
	                            $temp_arra['name'] = $menu_data[0]->name;
	                            $temp_arra['folder_name'] = $menu_data[0]->folder_name;
	                            $temp_arra['page_name'] = $menu_data[0]->page_name;
	                            $arr_data[$menu][] = $temp_arra;
	                        }
	                    }
	                }
	            }
	        }
	        return $arr_data;
	    }
	    
	    
	    function user_restiction($user_id,$username) {
	        $status = '1';
	        $http = $_SERVER['PHP_SELF'];
	        $pageName = explode("/", $http);
	        $lenPage = sizeOf($pageName) - 1;
	        $folder_name = sizeOf($pageName) - 2;
	        $folder =  $pageName[$folder_name];
	        $page =  explode('.',$pageName[$lenPage]);
	        $page = $page['0'];
	        $flags = 0;
            $query=$this->db->query("select id,menu_id  from menu_sub  where status = '$status' and page_name = '$page' and folder_name = '$folder'");
            $check_page= $query->result();
            $menu_access =   user_menu_data($db,$user_id,$username);
            if(!empty($check_page) && is_array($check_page)) {
                $pag_id =  $check_page[0]->id;
                $menu_sub =  $menu_access[$check_page[0]->menu_id];
                if(in_array($pag_id,$menu_sub)) {
                    $flags = true;
                }
            }
	        if($flags=='0')  {
	            unset($_SESSION['user']);
	            session_unset();
	            session_destroy();
	            header("Location:../index.php");
	            die();
	        }
	    } 
	    
	    function user_list(){
	        $userdata=$this->session->userdata('login_success');
	        $user_id=isset($userdata[0]->id)?$userdata[0]->id:'';
	        if($user_id){
	           $this->load->view("users/user_list");
	        }
	    }
	    
	    
	    function organization_list(){
	        $users_list = array();
	        $users_query = $this->db->query("select * from users  order by username asc ");
	        $row = $users_query->result();
	        foreach ($row as $vals) {
	            $users_list[] = $vals;
	        }
	        echo json_encode($users_list);
	    }
	    
	    function menu_access($userid){
	        $user_id = $_REQUEST['user_id'];
	        $users_query =$this->db->query("select menu_access,sub_menu from users where id = '$userid' ");
	        $menu_access = $users_query->result();
	        $menu_arra = json_decode($menu_access[0]->menu_access,true);
	        $sub_sub_menu_data11 = json_decode($menu_access[0]->sub_menu,true);
	        $status = '1';
	        $stng1 =$this->db->query("select name,id from menu where status= '$status'  order by priority ASC");
	        $menu_data = $stng1->result();
	        ?>
                <table class="table"> 
                <tr>
                <th>Sr. No</th>
                <th>Menu</th>
                <th>Action</th>
                </tr>
                <?php if(!empty($menu_data) && is_array($menu_data)) { 
                    $i= 1;
                    foreach($menu_data as $value) { 
                      $checked = '';
                      if(is_array($menu_arra)){
                        if(array_key_exists($value->id,$menu_arra)) { 
                            $checked = "checked='checked'";
                        }
                      }
                    ?>
                <tr>
                <td><?php echo $i;?>.</td>
                <td><?php echo $value->name;?></td>
                <td><input type="checkbox" name="menu_name[]" <?php echo $checked; ?> id="menu_name<?php echo $value->id;?>" value="<?php echo $value->id;?>" > </td>
                </tr>
                <tr>
                <td colspan="3">
                <table class="table">
                <?php 
                $menu_id = $value->id;
                $stng1 =$this->db->query("select id,name,folder_name,page_name from menu_sub where status = '$status' and menu_id = '$menu_id'  order by priority ASC");
                $sub_menu_data = $stng1->result();
                if(!empty($sub_menu_data) && is_array($sub_menu_data)) { 
                    $ii= 1;
                    foreach($sub_menu_data as $value_sub) { 
                        $sub_checked = '';
                        if(!empty($menu_arra[$value->id]) && in_array($value_sub->id,$menu_arra[$value->id])) { 
                                $sub_checked = "checked='checked'";
                            }
                 
                ?>
                <tr style='background-color: darkgrey;'>
                <td><?php echo $ii;?>. </td>
                <td><?php echo $value_sub->name;?></td>
                <td><input type="checkbox" <?php echo $sub_checked; ?> name="sub_menu_name_<?php echo $value->id;?>[]" id="sub_menu_name<?php echo $value_sub->id;?>" value="<?php echo $value_sub->id;?>" > </td>
                </tr>
                <?php  if($value->id =='110' && $value_sub->id =='2') { 
                    $menu_id= $value->id;
                    $sub_menu_id= $value_sub->id;
                    $stng1_sub =$this->db->query("SELECT id,name FROM menu_sub_sub where status = '$status' and menu_id = '$menu_id' and sub_menu_id = '$sub_menu_id'");
                    $sub_sub_menu_data = $stng1->result();
                    ?>
                <tr>
                <td colspan="3">
                <table class="table">
                <?php if(!empty($sub_sub_menu_data) && is_array($sub_sub_menu_data)) { 
                        $iii= 1;
                        foreach($sub_sub_menu_data as $value_sub_sub) { 
                            $su_su_checked = '';
                            if(!empty($sub_sub_menu_data11['2']) && in_array($value_sub_sub->id,$sub_sub_menu_data11['2'])) { 
                                $su_su_checked = "checked='checked'";
                            }
                            ?>
                <tr style='background-color: #e29999;'>
                <td><?php echo $iii; ?>.</td>
                <td> <?php echo $value_sub_sub->name; ?> </td>
                <td><input type="checkbox" <?php echo $su_su_checked; ?>  name="sub_sub_menu_name_<?php echo $sub_menu_id; ?>[]" id="sub_sub_menu_name<?php echo $value_sub_sub->id; ?>" value="<?php echo $value_sub_sub->id; ?>" > </td>
                </tr>
                        <?php 
                    $iii++;
                    } } ?>
                </table>
                </td>
                </tr>
                <?php } ?>
                <?php 
                $ii++;
                    } } ?>
                </table>
                </td>
                </tr>
                    <?php 
                
                $i++; } } ?>
                </table>
                
                <?php 
	        
	    }
	    
	    
	    function json_validator($data) {
	        if (!empty($data)) {
	            return is_string($data) &&
	            is_array(json_decode($data, true)) ? true : false;
	        }
	        return false;
	    }
	    
	    function update_menu($userid){
	        $menu_data = $_REQUEST['menu'];
	        $user_id = $userid;
	        $sub_menu = json_encode($_REQUEST['sub_menu']);
	        $menu_json_data =  json_encode($menu_data);
	        $validatejson=$this->json_validator($menu_json_data);
	        if($validatejson==true){
                $query = $this->db->query("update users set menu_access = '$menu_json_data',sub_menu = '$sub_menu'  where id ='$user_id'");
                if($query){
                    echo 'Update Menu Sucessfully.';die;
                }
	        }else{
	            echo 'Request is not valid formate .';die;
	        }
	    }
	    
	    function adduser($insert){
	        $data = json_decode(file_get_contents("php://input"));
	        $spcl_char=['<'=>'','>'=>'','/\/'=>'','\\'=>'','('=>'',')'=>'','!'=>'','^'=>'',"'"=>''];
	        $short_name= pg_escape_string($data->short_name);
	        $mobile_no= pg_escape_string($data->mobile_no);
	        $fname= htmlspecialchars(strtr($data->fname, $spcl_char));
	        if($fname==''){
	            echo $msg = 'Please Enter first name .';die;
	        }
	        $username=ucfirst($fname);
	        $lname= htmlspecialchars(strtr($data->lname, $spcl_char));
	        if($lname==''){
	            echo $msg = 'Please Enter last name .';die;
	        }
	        $email=htmlspecialchars(strtr($data->email, $spcl_char));
	        if($email==''){
	            echo $msg = 'Please Enter email .';die;
	        }
	        
	        $mobile_no= htmlspecialchars(strtr($data->mobile_no, $spcl_char));
	        if($mobile_no==''){
	            echo $msg = 'Please Enter mobile .';die;
	        }
	        
	        if(strlen($mobile_no)>10){
	            echo  $msg = 'Please Enter 10 digit mobile number .';die;
	        }
	        $gender=  htmlspecialchars(strtr($data->gender, $spcl_char));
	        if($gender==''){
	            echo  $msg = 'Please gender.';die;
	        }
	        
	        if(strlen($gender)>10){
	            echo $msg = 'Please Enter valid gender .';die;
	        }
	        
	        
	        $address=htmlspecialchars(strtr($data->address, $spcl_char));
	        if($address==''){
	            echo  $msg = 'Please gender.';die;
	        }
	        $country= htmlspecialchars(strtr($data->country, $spcl_char));   
	        
	        
	        $default='Test@123';
	        $password=hash('sha512',$default);
	        $msg = 'Something Error. Please try again.';
	        if ($mobile_no) {
	            $array=array(
	                'username'=>$username,
	                'password'=>$password,
	                'email'=>$email,
	                'location'=>'100',
	                'fname'=>$fname,
	                'lname'=>$lname,
	                'country'=>$country,
	                'address'=>$address,
	                'gender'=>$gender,
	                'short_name'=>$short_name,
	                'mobile'=>$mobile_no,
	                'menuaccess_codeall'=>6,
	            );
	            $st=$this->efiling_model->insert_query('users', $array);
	           echo  $msg = "Successfully Inserted Your Record"; die;
	        }
	        echo $msg; die;
	    }
	
	
	
	function deleteUser(){
	    $id = json_decode(file_get_contents("php://input"));
	    $id = pg_escape_string($id);
	    $msg = '';
	    if(is_numeric($id) && $id!=''){
    	    if ($id != "") {
    	        $query = $this->db->query("DELETE FROM  users  WHERE id ='$id'");
    	        if($query){
    	           $msg = "Successfully Deleted Your Record";die;
    	        }
    	    }
	    }else{
	       echo "Id should be only numeric and not blank";die;
	    }
	}
	
	
	
	function userupdate(){     
	    $data = json_decode(file_get_contents("php://input")); // form fields ge
	    $spcl_char=['<'=>'','>'=>'','/\/'=>'','\\'=>'','('=>'',')'=>'','!'=>'','^'=>'',"'"=>''];
	    $org_id =  htmlspecialchars(strtr($data->id, $spcl_char)); 
	    if($org_id==''){echo  $msg = 'Please valid  id'; die;}
	    if ($org_id != "") {  
	        $fname= htmlspecialchars(strtr($data->fname, $spcl_char));
	        if($fname==''){
	           echo $msg = 'Please Enter first name .';die;
	        }
	        if(strlen($fname) < 2 && strlen($fname) > 50){
	            echo $msg = 'Please Enter first name .';die;
	        }
	        
	        $lname= htmlspecialchars(strtr($data->lname, $spcl_char));
	        if($lname==''){
	            echo $msg = 'Please Enter last name .';die;
	        }
	        
	        if(strlen($lname) < 2 && strlen($lname) > 50){
	            echo $msg = 'Please Enter first name .';die;
	        }
	        
	        
	        $email=htmlspecialchars(strtr($data->email, $spcl_char));  
	        if($email==''){
	            echo $msg = 'Please Enter email .';die;
	        }
	        
	        if(strlen($email) < 5 && strlen($email) > 50){
	            echo $msg = 'Please Enter email .';die;
	        }

	        $mobile_no= htmlspecialchars(strtr($data->mobile_no, $spcl_char)); 
	        if($mobile_no==''){
	            echo $msg = 'Please Enter mobile .';die;
	        }
	        
	        if(strlen($mobile_no)>10 && !is_numeric($mobile_no)){
	            echo  $msg = 'Please Enter 10 digit mobile number .';die;
	        }
	        $gender=  htmlspecialchars(strtr($data->gender, $spcl_char));
	        if($gender=='' || strlen($fname) < 5){
	            echo  $msg = 'Please gender.';die;
	        }
	        
	        if(strlen($gender)>10){
	            echo $msg = 'Please Enter valid gender .';die;
	        }
	        
	        
	        $address=htmlspecialchars(strtr($data->address, $spcl_char)); 
	        if($address==''){
	            echo  $msg = 'Please gender.';die;
	        }
	        
	        
	        $country= htmlspecialchars(strtr($data->country, $spcl_char));   
	        if($country=='' || !is_numeric($country)){
	            echo  $msg = 'Enter valid country code.';die;
	        }
	        
	        if(strlen($country) < 5 && strlen($country) > 4){
	            echo $msg = 'Please Enter country code .';die;
	        }
	        
	        
	        $org_id= htmlspecialchars(strtr($org_id, $spcl_char));
	        if(!is_numeric($org_id)){
	            echo  $msg = 'Enter valid id.';die;
	        }
	        
	        if(strlen($org_id) < 1 && strlen($org_id) > 4){
	            echo $msg = 'Enter valid id .';die;
	        }
	        
	        
	        if ($mobile_no) {
	            $array=array(
	                'email'=>$email,
	                'location'=>'100',
	                'fname'=>$fname,
	                'lname'=>$lname,
	                'country'=>$country,
	                'address'=>$address,
	                'gender'=>$gender,
	                'short_name'=>$short_name,
	                'mobile'=>$mobile_no,
	                'menuaccess_codeall'=>6,
	            );
	            $st1=$this->efiling_model->update_data('users', $array,'id', $org_id);
	            echo $msg = "Successfully updated Your Record";die;
	        }
	        echo $msg;
	    }
	    echo $msg;
	}
	
	
	function unlockuser($user_id){
	    if(is_numeric($user_id)){
    	    $users_query =$this->db->query("select username from users where id='$user_id'");
    	    $val = $users_query->result();
    	    if(!empty($val)){
    	        $usersid = $val[0]->username;
    	        $query = $this->db->query("UPDATE log_attempt SET lock='N',failed='1' WHERE user_id ='$usersid'");
    	        if($query){
    	                echo "Successfully enable user";
                }
    	    }else{
    	        echo "User not found";
    	     }
	    }else{
	        echo "Not valid formate .";die;
	    }
    	}
    	
    	
    	function chnagepassword(){
    	    $data = json_decode(file_get_contents("php://input")); // form fields get
    	    $org_id = pg_escape_string($data->id);
    	    $newPassword = pg_escape_string($data->new_password);
    	    $confirm_pass = pg_escape_string($data->con_password);
    	    $msg = 'Confirm Password Not Match.';
    	    if($newPassword == $confirm_pass){
    	        $password = hash('sha512',$newPassword);
    	        if ($org_id != "") {
    	            $query =$this->db->query("UPDATE users SET password= '$password' WHERE id ='$org_id'");
    	            if($query){
                        $msg = "Successfully Updated Your Record";
    	            }
                }
    	    }
    	    echo $msg;
    	}
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
    	
	
	
	}

	
	
	
	
	