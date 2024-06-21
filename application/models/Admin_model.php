<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model
{
    
    function __construct() {
        parent::__construct();
    }
    
    public function verify_user($password,$user_id,$salt){
        $data=$this->db->select('*')
        ->get_where('users',['username'=>$user_id,'verified'=>'1'])
        ->result();
        $db_sha1_pass=$data[0]->password;
        $db_sha1_salt_pass=hash('sha512', $db_sha1_pass.$salt);
        if($password == $db_sha1_salt_pass) {
            return $data;
        } else  return false;
        
    }
    
    public function verify_filingno($post_array){
        $filing_no=$post_array['filing_no'];
        switch(strlen($filing_no)) {
            case '1' : $filing_no='00000'.$filing_no; break;
            case '2' : $filing_no='0000'.$filing_no; break;
            case '3' : $filing_no='000'.$filing_no; break;
            case '4' : $filing_no='00'.$filing_no; break;
            case '5' : $filing_no='0'.$filing_no; break;
        }
        $data_array['RIGHT(filing_no,10)']=$filing_no.$post_array['year'];
        
        $rs=$this->db->where($data_array)->get('sat_case_detail');
        if($rs->num_rows() == 1) return 'success';
        else return 'not_found';
    }
    
    public function insert_caveat($post_array){
        unset($post_array['organization']); 
	       unset($post_array['salt']);
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if(!$this->session->userdata('caveat_salt')) {
            $rs=$this->db->select('MAX(CAST(salt AS INTEGER))+1 salt')->get('temp_caveat')->result();
            $salt=$rs[0]->salt; $this->session->set_userdata('caveat_salt',$salt);
            $post_array['salt']=$salt;
        }
        else $post_array['salt']=$this->session->userdata('caveat_salt');
        $post_array['filed_user_id']=$user_id;
        $this->db->insert('temp_caveat',$post_array);
        if($this->db->affected_rows() ==1) {
            $this->session->unset_userdata('skey_session');
            return true;
        }
        else {
            $this->session->unset_userdata('caveat_salt');
            return false;
        }
    }
    
    public function insert_caveat_final($post_array){
        $salt=$this->session->userdata('caveat_salt');  $this->session->unset_userdata('caveat_salt');
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $ia_filing_no=$this->db->select('caveat_filing')
        ->get_where('chamber_initialization',['year'=>date('Y')])
        ->row('caveat_filing');
        
        $temp_cvt=$this->db->select('commission,nature_of_order')
        ->get_where('temp_caveat',['salt'=>"$salt"])
        ->result();
        
        $comm = $temp_cvt[0]->commission;
        $order = $temp_cvt[0]->nature_of_order;
        $benchCode = htmlspecialchars(str_pad($comm, 3, '0', STR_PAD_RIGHT));
        $subBenchCode = htmlspecialchars(str_pad($order, 2, '0', STR_PAD_LEFT));
        
        $iaFiling=$ia_filing_no;
        if ($ia_filing_no == '0') { $iaFilingNo = 1; $filno = $ia_filingNo = '000001'; }
        if ($ia_filing_no != '0') {
            $iaFilingNo = $iaFiling + 1; $filno = $ia_filing_no = (int)$ia_filing_no + 1;
            $len = strlen($ia_filing_no); $length = 6 - $len;
            for ($i = 0; $i < $length; $i++) $filno = "0" . $filno;
        }
        $caveat_filing_no = $benchCode.$subBenchCode.$filno.date('Y');
        $sql="INSERT INTO caveat_detail (caveat_filing_no,caveat_name,caveat_address,caveat_state,caveat_district ,caveat_pin,caveat_email,caveat_phone,caveat_mobile,prayer,commission,nature_of_order,case_no, case_year,decision_date,council_name,council_address,council_email,council_phone,council_mobile) SELECT $caveat_filing_no,caveat_name,
	    	caveat_address,caveat_state,caveat_district,caveat_pin,caveat_email,caveat_phone,caveat_mobile,prayer,commission,nature_of_order,case_no,case_year,decision_date,council_name,council_address,council_email,council_phone,council_mobile from temp_caveat WHERE salt='$salt'";
        $this->db->query($sql,false);
        if($this->db->affected_rows() ==1){
            $post_array['filing_no']=$caveat_filing_no;
            $post_array['dt_of_filing']=date('Y-m-d');
            $post_array['user_id']=$user_id;
            $this->db->insert('aptel_account_details',$post_array);
            if($this->db->affected_rows() == 1){
                $this->db->set('caveat_filing',$iaFilingNo)->where('year',date('Y'))->update('chamber_initialization');
                $this->db->set('filing_no',$caveat_filing_no)->where('filing_no',"$salt")->update('additional_commision');
                $this->db->delete('temp_caveat',['CAST(salt AS INTEGER) <'=>$salt],false);
                return $caveat_filing_no;
            }
        }
        else false;
    }
    
    public function insert_ma_ia_vakalatnama_mpapers($post_array){
        $upd_session_array=$this->session->userdata('upd_file_sess');
        $doc_url=''; $doc_id=''; $ia='';
        /*print_r($upd_session_array);
         print_r($post_array);*/
        
        $user_id=$this->session->userdata('login_success')[0]->id;
        
        if(!is_array($upd_session_array['doc_type'])) {
            $document_type=$upd_session_array['doc_type'];
            $doc_url=$upd_session_array['file_name'];
            $doc_full_name=$upd_session_array['doc_type'];
            $doc_id=substr($doc_full_name,strpos($doc_full_name, ' '));
        }
        else{
            $document_type=$upd_session_array['doc_type'][0];
            for($i=0; $i<sizeof($upd_session_array['doc_type']); $i++){
                $doc_url.=$upd_session_array['file_name'][$i].',';
                $doc_full_name=$upd_session_array['doc_type'][$i];
                $doc_id.=substr($doc_full_name,strpos($doc_full_name, ' ')).',';
            }
            $doc_url=rtrim($doc_url,',');
            $doc_id=rtrim($doc_id,',');
        }
        
        
        $filing_no=$post_array['filing_no'];
        $filing_year=$post_array['filing_year'];
        $party_flag=$post_array['userType'];
        $adv_code=@$post_array['council_name'];
        $matter=$post_array['matter'];
        $payment_mode=$post_array['payment_mode'];
        $branch_name=$post_array['branch_name'];
        $dd_no=@$post_array['dd_no'];
        $ia_fee=$post_array['ia_fee'];
        $dd_date=$post_array['dd_date'];
        
        switch(strlen($filing_no)) {
            case '1' : $filing_no='00000'.$filing_no; break;
            case '2' : $filing_no='0000'.$filing_no; break;
            case '3' : $filing_no='000'.$filing_no; break;
            case '4' : $filing_no='00'.$filing_no; break;
            case '5' : $filing_no='0'.$filing_no; break;
        }
        
        $final_filing_no=$filing_no.$filing_year;
        $get_full_filing_no=$this->db->select('filing_no')->where('RIGHT(filing_no,10)',$final_filing_no)->get('sat_case_detail')->row('filing_no');
        
        switch (strtolower(substr($document_type,0,2))) {
            case 'va':
                
                $vkln=$this->db->select('*')
                ->where('filing_no',$get_full_filing_no)
                ->get('vakalatnama_detail');
                
                if($vkln->num_rows() == 0){
                    $max_id=$this->db->select('MAX(id)+1 id')->get('vakalatnama_detail')->row('id');
                    
                    $sql="INSERT INTO vakalatnama_detail (filing_no,party_flag,adv_code,adv_regn_no,adv_mob_no,adv_phone_no,adv_fax_no,adv_email,adv_address,display,user_id,pin_code,add_party_code,state,district,id,vakalatnama_no,entry_date) SELECT '$get_full_filing_no','$party_flag',$adv_code,adv_reg,adv_mobile,adv_phone,adv_fax,email,address,display,$user_id,adv_pin,'$party_flag',state_code,adv_dist,$max_id,'$max_id','".date('Y-m-d')."' from master_advocate WHERE adv_code=$adv_code";
                    
                    $this->db->query($sql,false);
                }
                else return 'Vakalatnama number already exists.';
                break;
                
            case 'ma':
                $data_array=[
                'filing_no'		=>$get_full_filing_no,
                'main_party'	=>$party_flag,
                'doc'			=>$doc_id,
                'total_no_annexure'=>$post_array['annexure'],
                'ma_filing_no'		=>'',
                'dt_of_filing'		=>date('Y-m-d')
                ];
                $this->db->insert('ma_detail',$data_array);
                $sql="update ma_detail set ma_filing_no=ma_id where filing_no='".$get_full_filing_no."'";
                $this->db->query($sql,false);
                break;
                
            case 'ia': $ia='Yes';
            $max_ia_no=$this->db->select('MAX(ia_no)+1 ia_no')
            ->get('satma_detail')
            ->row('ia_no');
            $ia_data_array=[
                'ia_no'		=>$max_ia_no,
                'filing_no'	=>$get_full_filing_no,
                'filed_by'	=>$party_flag,
                'entry_date'=>date('Y-m-d'),
                'display'	=>'Y',
                'ia_filing_no'=>$get_full_filing_no,
                'ia_nature'	=>$post_array['ia_nature_name'],
                'status'	=>'P',
                'matter'	=>$matter
            ];
            $this->db->insert('satma_detail',$ia_data_array);
            break;
        }
        
        
        if($this->db->affected_rows() ==1){
            
            $max_salt=$this->db->select('CAST(MAX(salt) AS INTEGER)+1 salt')->get('sat_temp_payment')->row('salt');
            $payment_array=['salt'=>$max_salt,'payment_mode'=>$payment_mode,'branch_name'=>$branch_name,'dd_no'=>$dd_no,'dd_date'=>$dd_date,'amount'=>$ia_fee,'other_fee'=>$ia_fee,'createdate'=>date('Y-m-d')];
            
            if($ia=='Yes')
                $payment_array=['salt'=>$max_salt,'total_fee'=>$ia_fee,'payment_mode'=>$payment_mode,'branch_name'=>$branch_name,'dd_no'=>$dd_no,'dd_date'=>$dd_date,'amount'=>$ia_fee,'ia_fee'=>'1000','other_fee'=>'25','createdate'=>date('Y-m-d')];
                
                $this->db->insert('sat_temp_payment',$payment_array);
                
                if($this->db->affected_rows() ==1){
                    $upd_salt=['salt'=>$max_salt];
                    $this->db->set($upd_salt)->where('filing_no',$get_full_filing_no)->update('sat_case_detail',$upd_salt);
                    if($this->db->affected_rows()==1){
                        
                        $doc_upload_array=[
                            "filing_no"			=>$get_full_filing_no,
                            "doc_url"			=>$doc_url,
                            "doc_date"			=>date('Y-m-d'),
                            "doc_filing_no"		=>'',
                            "main_party"		=>$party_flag,
                            "add_party_code"	=>$party_flag,
                            "doc_id"			=>$doc_id
                        ];
                        $this->db->insert('document_filing',$doc_upload_array);
                        if($this->db->affected_rows() == 1) return 'success';
                    }
                }
        }
     //   else return $this->db->last_query();
        
    }
    
    public function forgot_pass($post_array){
        $loginid=$post_array['loginid'];
        $email=$post_array['email'];
        $rs=$this->db->select('mobile')
        ->get_where('users',['email'=>$email,'username'=>$loginid])
        ->row('mobile');
        $user_mobile=$rs;
        if(is_numeric($user_mobile)) {
            $randPass=hash('sha512',rand(10000000,99999999));
            $alphabet='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $rndmAlphabet=str_shuffle($alphabet);
            $specialChar='@#*!';
            $rndmSpecial=str_shuffle($specialChar);
            
            $make_rand_pass=substr($rndmAlphabet,17,1).strtolower(substr($randPass,101,2)).substr($rndmSpecial,2,1).strtolower(substr($randPass,30,4));
            $hash_pass=hash('sha512',$make_rand_pass);
            $update_pass=['password'=>$hash_pass,'is_password'=>'1'];            
            //    unset($post_array['skey_pass']); 
            $data['email']=$email;
            $data['username']=$loginid;
            $updRs=$this->db->set($update_pass)->where($data)->update('users',$update_pass);
         //   echo $str = $this->db->last_query();die;
            if($updRs) return array('new_password'=>$make_rand_pass,'user_mobile'=>$user_mobile);
            else return 'db-error';
        }else return "not-found";
    }
    
    public function getStates() {
        return $this->db->select("state_code,state_name")
        ->where('state_code <>','0',FALSE)
        ->where('state_code is not NULL',NULL,FALSE)
        ->order_by('state_name','ASC')
        ->get('master_psstatus')
        ->result();
    }
    
    public function getDistricts($sid) {
        $rs=$this->db->select("district_code,district_name")
        ->order_by('district_name','ASC')
        ->get_where('master_psdist',$sid);
        if($rs->num_rows() > 0) return $rs->result();
        else return false;
    }
    
    public function getDistrictsall() {
        $rs=$this->db->select("district_code,district_name")
        ->order_by('district_name','ASC')
        ->get('master_psdist');
        if($rs->num_rows() > 0) return $rs->result();
        else return false;
    }
    

    public function insert_commission($post_array) {
        unset($post_array['filing_no']);
        $post_array['created_date']=date('Y-m-d');
        
        $rs=$this->db->select('*')->get_where('additional_commision',$post_array);
        $n_rows=(int)$rs->num_rows();
        if(!is_numeric($n_rows) || $n_rows==0) $n_rows=1;
        
        if(!$this->session->userdata('caveat_salt')) {
            $rs=$this->db->select('MAX(CAST(salt AS INTEGER))+1 salt')->get('temp_caveat')->result();
            $salt=$rs[0]->salt;
            if($salt==0 || $salt=='') $salt=1;
            $this->session->set_userdata('caveat_salt',$salt);
            $post_array['filing_no']=$salt;
        }else $post_array['filing_no']=$this->session->userdata('caveat_salt');
        
        if($n_rows <= 10) {
            $this->db->insert('additional_commision',$post_array);
            if($this->db->affected_rows() ==1){
                return 'success';
            }
            else {
                $this->session->unset_userdata('caveat_salt');
                return 'Insert query error!';
            }
        }else {
            $this->session->unset_userdata('caveat_salt');
            return 'Permission denay.';
        }
    }
    
     public function get_advocate_details($post_data){
        $adv_reg=isset($post_data['adv_reg'])?$post_data['adv_reg']:''; 
        if($adv_reg!=''){
            $mobile=isset($post_data['adv_reg'])?$post_data['adv_reg']:''; 
        }
        $rs=$this->db->select('a.adv_reg,a.adv_name,a.address,a.adv_mobile,a.email,a.adv_sex,a.state_code,a.adv_pin,a.adv_dist,b.district_name')
        ->from('master_advocate a')
        ->join('master_psdist b','a.adv_dist=CAST(b.district_code AS varchar)','inner')
        //->where('a.status', '1')
        ->where('a.adv_code', $mobile)     
        ->get();   
       // echo $this->db->last_query();die;
        if($rs->num_rows() ==1) return $rs->result();
        else return false;
    }    
    
    public function get_org_details(){
        $rs=$this->db->distinct()
        ->select('org_id,org_name')
        ->order_by('org_name','ASC')
        ->get('master_org');
        
        if($rs->num_rows() > 0) return $rs->result();
        else return $this->db->last_query();
    }
    
    public function get_org_fdetails($post_data){  
        $rs=$this->db->select('a.org_id,a.org_address,a.state,a.pin,a.mobile_no,a.email,a.orgdisp_name,a.org_desg,a.short_org_name,a.district,b.district_name')
        ->from('master_org a')
        ->join('master_psdist b','a.district=b.district_code')
        ->where($post_data)
        ->get();
        
        if($rs->num_rows() > 0) return $rs->result();
        else return false;
    }
    
    public function get_master_advocate($post_array) {
        unset($post_array['table_name']);
        $where_array=['a.adv_code'=>$post_array['adv_code']];
        return $this->db->select('a.address,a.adv_mobile,a.email,a.adv_phone,a.adv_fax,a.adv_pin,b.state_code,b.state_name,c.district_code,c.district_name')
        ->from('master_advocate a')
        ->join('master_psstatus b','b.state_code=a.state_code','left')
        ->join('master_psdist c','c.district_code=CAST(a.adv_dist AS INTEGER)','left')
        ->where($where_array)
        ->get()
        ->result();
    }
    
    public function verifyUnique($dataArray) {
        foreach ($dataArray as $key => $value) :;
        switch($key) {
            case 'loginid':
                $rs=$this->db->select('*')->get_where('efiling_users',$dataArray);
                if($rs->num_rows() > 0) return 'found';
                else return 'not found';
                break;
                
            default :
                $rs=$this->db->select('*')->get_where('efiling_users',$dataArray);
                if($rs->num_rows() > 0) return 'found';
                else return 'not found';
                break;
        }
        endforeach;
    }
    
   /* public function insert_newuser($post_array) {
        date_default_timezone_set('Asia/Calcutta');
        
        $randPass=hash('sha512',rand(10000000,99999999));
        $alphabet='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rndmAlphabet=str_shuffle($alphabet);
        $specialChar='@#*!';
        $rndmSpecial=str_shuffle($specialChar);
        
        $make_rand_pass=substr($rndmAlphabet,17,1).strtolower(substr($randPass,101,2)).substr($rndmSpecial,3,1).strtolower(substr($randPass,30,4));
        $hash_password=hash('sha512','test123');
        $post_array['password']=$hash_password;
        
        $dinsert='success';
        
        if(trim($post_array['user_type']) == 'advocate') :;
        
        $adv_reg=['adv_reg'=>$post_array["enrolment_number"]];
        $rs=$this->db->get_where('master_advocate',$adv_reg);
        if($rs->num_rows() == 0) {
            $adv_data=[
                'adv_name'=>$post_array['fname'].' '.$post_array['lname'],
                'adv_reg'=>$post_array['enrolment_number'],
                'address'=>$post_array['address'],
                'adv_mobile'=>$post_array['mobilenumber'],
                'email'=>$post_array['email'],
                'adv_sex'=>substr($post_array['gender'],0,1),
                'adv_phone'=>'',
                'adv_fax'=>'',
                'state_code'=>$post_array['state'],
                'high_court_code'=>0,
                'fr_ind'=>0,
                'adv_reg_year'=>'',
                'adv_pin'=>$post_array['pincode'],
                'adv_dist'=>$post_array['district']
            ];
            $this->db->insert('master_advocate',$adv_data);
            if($this->db->affected_rows() < 1) $dinsert='failed';
        }
        endif;
        
        if(trim($post_array['user_type']) == 'company') :;
        
        if(trim($post_array["org_name"]) == '0') {
            $org_data=[
                'org_name'=>$post_array['org_admin'],
                'org_address'=>$post_array['address'],
                'state'=>$post_array['state'],
                'district'=>$post_array['district'],
                'pin'=>$post_array['pincode'],
                'mobile_no'=>$post_array['mobilenumber'],
                'phone_no'=>'',
                'email'=>$post_array['email'],
                'fax'=>'',
                'orgdisp_name'=>$post_array['org_admin'],
                'org_desg'=>$post_array['org_desg'],
                'org_type_code'=>0,
                'short_org_name'=>$post_array['short_org_name']
            ];
            $this->db->insert('master_org',$org_data);
            if($this->db->affected_rows() > 0) {
                $rs=$this->db->select('org_id')->get_where('master_org',['org_name'=>$post_array['org_admin']])->row('org_id');
                unset($post_array['org_name']); unset($post_array['orgdisp_name']);
                $post_array['org_name']=$rs; $post_array['orgdisp_name']=$post_array['org_admin'];
            }
            else $dinsert='failed';
        }
        
        endif;
        
        if($dinsert=='success') {
            $this->db->insert('efiling_users',$post_array);
            if($this->db->affected_rows() > 0) return $make_rand_pass;
            else return false;
        }
        else return false;
        
    }*/
	
	
	public function insert_newuser($post_array) {
        date_default_timezone_set('Asia/Calcutta');
        
        $randPass=hash('sha512',rand(10000000,99999999));
        $alphabet='ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rndmAlphabet=str_shuffle($alphabet);
        $specialChar='@#*!';
        $rndmSpecial=str_shuffle($specialChar);
        
        $make_rand_pass=substr($rndmAlphabet,17,1).strtolower(substr($randPass,101,2)).substr($rndmSpecial,3,1).strtolower(substr($randPass,30,4));
        $hash_password=hash('sha512','test123');
        $post_array['password']=$hash_password;
         $status='0';
        $dinsert='success';
        
        if(trim($post_array['user_type']) == 'advocate') :;
        
        $adv_reg=['adv_reg'=>$post_array["enrolment_number"]];
        $rs=$this->db->get_where('master_advocate',$adv_reg);
        if($rs->num_rows() != 0) {
          $status='1';
        }

        if($rs->num_rows() == 0) {
            $adv_data=[
                'adv_name'=>$post_array['fname'].' '.$post_array['lname'],
                'adv_reg'=>$post_array['enrolment_number'],
                'address'=>$post_array['address'],
                'adv_mobile'=>$post_array['mobilenumber'],
                'email'=>$post_array['email'],
                'adv_sex'=>substr($post_array['gender'],0,1),
                'adv_phone'=>'',
                'adv_fax'=>'',
                'state_code'=>$post_array['state'],
                'high_court_code'=>0,
                'fr_ind'=>0,
                'adv_reg_year'=>'',
                'adv_pin'=>$post_array['pincode'],
                'adv_dist'=>$post_array['district']
            ];
            $this->db->insert('master_advocate',$adv_data);
            if($this->db->affected_rows() < 1) $dinsert='failed';
        }
        endif;
        
        if(trim($post_array['user_type']) == 'company') :;
        
        if(trim($post_array["org_name"]) == '0') {
            $org_data=[
                'org_name'=>$post_array['org_admin'],
                'org_address'=>$post_array['address'],
                'state'=>$post_array['state'],
                'district'=>$post_array['district'],
                'pin'=>$post_array['pincode'],
                'mobile_no'=>$post_array['mobilenumber'],
                'phone_no'=>'',
                'email'=>$post_array['email'],
                'fax'=>'',
                'orgdisp_name'=>$post_array['org_admin'],
                'org_desg'=>$post_array['org_desg'],
                'org_type_code'=>0,
                'short_org_name'=>$post_array['short_org_name']
            ];
            $this->db->insert('master_org',$org_data);
            if($this->db->affected_rows() > 0) {
                $rs=$this->db->select('org_id')->get_where('master_org',['org_name'=>$post_array['org_admin']])->row('org_id');
                unset($post_array['org_name']); unset($post_array['orgdisp_name']);
                $post_array['org_name']=$rs; $post_array['orgdisp_name']=$post_array['org_admin'];
            }
            else $dinsert='failed';
        }
        
        endif;
        unset($post_array['adv_mobile']);
        $post_array['verified']=$status;
        if($dinsert=='success') {
            $this->db->insert('efiling_users',$post_array);
            if($this->db->affected_rows() > 0) return $make_rand_pass;
            else return false;
        }
        else return false;
    }
    
    function update_newuser($post_array){
        $sqlAdditionalAdv=array(
            'id_number'=>$post_array['id_number'],
            'idptype'=>$post_array['idptype'],
            'idproof_upd'=>$post_array['idproof_upd'],
        );
        $id=base64_decode($post_array['id_reff']);
        $st=$this->efiling_model->update_data('efiling_users', $sqlAdditionalAdv,'id', $id);
        if($st){ 
            return true;
        }else{
            return false;
        }
    }
	
	
    
    public function userlist($step) {
        if($step==1) {
            $rs=$this->db->distinct()
            ->select('id,name,enrlno,enrldate,idproof,pic')
            ->from('registerd_users')
            ->where(['display'=>'Y','step'=>$step])
            ->get();
        }else {
            $rs=$this->db->distinct()
            ->select('a.id,a.name,a.enrlno,a.enrldate,a.idproof,a.pic')
            ->from('registerd_users a')
            ->join('application_status b','b.id=a.id','inner')
            ->where(['a.display'=>'Y','b.step'=>$step,'b.remark'=>''])
            ->get();
            
        }
        
        if($rs->num_rows() > 0) return $rs->result();
        else return false;
    }
    
    public function get_appdetails($data){
        $rs=$this->db->select('name, email, mobile, form_type, salutation, gender, user_type, fname, dob, addressl1, addressl2, scountry, sstate, sdistrict, zip_code, std_code, telno, faxno, oaddressl1, oaddressl2, ocountry, ostate, odistrict, ozip_code, ostd_code, otelno, ofaxno, edu, oedu, enrlno, enrldate, wexp, pastate, padistrict, pasdistrict, appoffice, idproof, pic, sign, update_date, jgov_service_date, retirement_date, designation, practice_area')
        ->where($data)
        ->get('registerd_users');
        if($rs->num_rows()==1) return $rs->result();
        else return $this->last_query();
    }
    
    public function add_remark($step=NULL,$data) {
        $id=$data['id']; $remark=$data['remark'];
        $dataArray=$this->session->userdata('login_success');
        
        if($dataArray['admin_level']==1)
            $this->db->where('id',$id)->update('registerd_users',['remark'=>$remark,'step'=>'0']);
            
            else
                $this->db->where('id',$id)->update('application_status',['remark'=>$remark]);
                
                if($this->db->affected_rows() ==1) return true;
                else return false;
    }
    
    public function forward_appl($step=NULL,$data) {
        date_default_timezone_set('Asia/Calcutta'); $now=date('Y-m-d H:i:s');
        $dataArray=$this->session->userdata('login_success');
        
        $id=$data['id'];
        if($step == NULL)
            $step=(int)$dataArray['admin_level'];
            
            $step=($step+1);
            
            $data['step']=$step;
            $data['remark']='';
            $data['remark_officer_name']=$dataArray['name'];
            $data['remark_date_time']=$now;
            $data['display']='Y';
            
            if($dataArray['admin_level']==1)
                $this->db->insert('application_status',$data);
                else {
                    unset($data['id']);
                    $this->db->where('id',$id)->update('application_status',$data);
                }
                if($this->db->affected_rows() ==1) {
                    $this->db->where('id',$id)->update('registerd_users',['step'=>$step]);
                    if($this->db->affected_rows() ==1) return true;
                    else return false;
                }
                else return false;
    }
    
    public function fetch_addvparty($post_array){
        
        $filing_no=$post_array['filing_no'];
        switch(strlen($filing_no)) {
            case '1' : $filing_no='00000'.$filing_no; break;
            case '2' : $filing_no='0000'.$filing_no; break;
            case '3' : $filing_no='000'.$filing_no; break;
            case '4' : $filing_no='00'.$filing_no; break;
            case '5' : $filing_no='0'.$filing_no; break;
        }
        $filing_no.=$post_array['filing_year'];
        $data_array=['right(a.filing_no,10)'=>$filing_no,'a.party_flag'=>$post_array['party_flag']];
        $rs=$this->db->select('a.party_flag,a.pet_name,b.vakalatnama_no')
        ->from('additional_party a')
        ->join('vakalatnama_detail b','a.filing_no=b.filing_no','left')
        ->where($data_array)
        ->get();
        if($rs->num_rows() > 0)
            return $rs->result();
            else 	return false;
            
    }
    
    public function _get_data($table_nm=NULL){
        switch ($table_nm) {
            case 'master_commission':
                return  $rs=$this->db->select('full_name,id')
                ->order_by('full_name','ASC')
                ->get($table_nm)
                ->result();
                
                break;
                
            case 'master_case_type':
                return  $rs=$this->db->select('short_name,case_type_code')
                ->order_by('case_type_code','ASC')
                ->get_where($table_nm,['case_type_code <>'=>'8','display'=>'TRUE','flag'=>'2'])
                ->result();
                
                break;
                
            case 'master_org':
                return  $rs=$this->db->select('org_id,orgdisp_name')
                ->order_by('orgdisp_name','ASC')
                ->get($table_nm)
                ->result();
                
                break;
                
            case 'master_psstatus':
                return  $rs=$this->db->select('state_code,state_name')
                ->order_by('state_name','ASC')
                ->get($table_nm)
                ->result();
                
                break;
                
            case 'master_advocate':
                return  $rs=$this->db->select('adv_code,adv_name')
                ->order_by('adv_name','ASC')
                ->get($table_nm)
                ->result();
                
                break;
                
            case 'master_document':
                return $this->db->select('d_name,pay,did')
                ->get_where($table_nm,['pay <>'=>'IAO'])
                ->result();
                
                break;
                
            case 'master_document_with_IAO':
                $field_array=['pay '=>'IAO'];
                return $this->db->select('d_name,did')
                ->get_where('master_document',$field_array)
                ->result();
                
                break;
                
        }
    }
    
    public function fetch_caveat_details($filing_no){
        $rs=$this->db->select('a.*,b.fee_amount,c.short_name')
        ->from('caveat_detail a')
        ->join('aptel_account_details b','a.caveat_filing_no=b.filing_no','inner')
        ->join('master_commission c','CAST(a.commission AS INTEGER)=c.id')
        ->where('a.caveat_filing_no',$filing_no)
        ->get();
        
        if($rs->num_rows() == 1) {
            $data_array=$rs->result();
            $filingNo=$data_array[0]->caveat_filing_no;
            
            $data='<div style="position: relative;">
		        <p style="text-align:center; font-family: Arial, Helvetica, sans-serif; font-size: 20px; margin: 0; line-height: 2.6;">
		            <u><b>RECEIPT</b></u></p>
		        <p style="text-align:center; font-size: 24px; margin: 0;"><u>APPELLATE TRIBUNAL FOR ELECTRICITY</u></p>
		        <p style="text-align:center; margin: 0;">Core- 4, 7th Floor Scope Complex Lodhi Road New Delhi-110003</p>
                
		        <div style="overflow: hidden; margin-top: 50px;">
		            <div style="float: left; width: 50%;">
		                <p>';
            $filing_No = substr($filingNo, 5, 6);
            $filing_No = ltrim($filing_No, 0);
            $filingYear = substr($filingNo, 11, 4);
            $data.='Caveat No. :- CAVEAT/'.$filing_No.'/'.$filingYear.'</p>
		            </div>
		            <div style="float: right; width: 50%; text-align: right;">
		                <p>DATE OF FILING : '.date("d/m/Y",strtotime($data_array[0]->caveat_filing_date)).'</p>
		            </div>
		        </div>
		                    
		        <p style="margin: 0;">CASE TYPE:- Caveat</p>
		        <p><b>'.$data_array[0]->caveat_name.'</b> <span style="float: right; margin-right: 31%;"> ...Caveator</span>
		        </p>
		        <p><b>'.$data_array[0]->caveatee_name.'</b> <span style="float: right; margin-right: 31%;"> ...Expected Appellant</span>
		        </p>
		            
		        <table border="1" cellpadding="3" style="width:100%;border-collapse:collapse">
		            <tbody>
		            <tr>
		                <td rowspan="100" style="text-align:center"><b>Impugned order Details</b></td>
		                <td>Commission Name</td>
		                <td>Case No.</td>
		                <td>Case Year</td>
		                <td>Date of Order</td>
		            </tr>
		            <tr>
		                <td>'.$data_array[0]->short_name.'</td>
		                <td>'.$data_array[0]->case_no.'</td>
		                <td>'.$data_array[0]->case_year.'</td>
		                <td>'.date("d/m/Y",strtotime($data_array[0]->decision_date)).'</td>
		            </tr>';
            
            $additRs=$this->db->select("a.case_no,a.case_year,a.decision_date,b.short_name")
            ->from('additional_commision a')
            ->join('master_commission b','b.id=CAST(a.commission AS INTEGER)','INNER')
            ->where('a.filing_no',$filing_no)
            ->get();
            
            if($additRs->num_rows() > 0) :;
            
            foreach($additRs->result() as $row444) :;
            $case_no = $row444->case_no;
            $case_year = $row444->case_year;
            $decision_date = $row444->decision_date;
            $commision_anme = $row444->short_name;
            
            $data.='<tr>
		                    		<td>'.$commision_anme.'</td>
		                            <td>'.$case_no.'</td>
		                            <td>'.$case_year.'</td>
		                            <td>'.date("d/m/Y",strtotime($decision_date)).'</td>
		                        </tr>';
            endforeach;
            endif;
            
            $data.='</tbody>
		        </table>
                
		        <table border="0" style="width:100%;">
		            <tr>
		                <td style="width:100%" valign="top">
		                    <p><b>&nbsp;</b></p>
		                    <p><b>Amount Received :- '.$data_array[0]->fee_amount.'</b></p>
		                </td>
		                <td valign="top" style="text-align: center;">
		                    <p>COUNTER&nbsp;ASSISTANT</p>
		                    <img src="'.base_url('asset/images/stamp.jpg').'" style="width:100px;">
		                </td>
		            </tr>
		        </table>
		        <img src="'.base_url('asset/images/bg.jpg').'" style="position:absolute; left:0; right:0; top:0; bottom:0; margin:auto; z-index: -1;">
		    </div>';
            
            return $data;
        }
        else return false;
    }
    
    public function get_add_petitioner(){
        $data='<table class="table table-bordered table-hover" id="add_petitioner_table">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Address</th>
							<th>State</th>
							<th>District</th>
							<th>Pincode</th>
							<th>Mobile</th>
							<th>E-mail</th>
							<th>PAN No</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>';
        return $data;
    }
    
    
    public function get_add_respondent(){
        $data='<table class="table table-bordered table-hover" id="add_respondent_table">
					<thead>
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Address</th>
							<th>State</th>
							<th>District</th>
							<th>Pincode</th>
							<th>Mobile</th>
							<th>E-mail</th>
							<th>PAN No</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>';
        return $data;
    }
    
    public function get_caveat_filing(){
        $data='<table class="table table-bordered table-hover" id="caveat_filing_table">
					<thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Caveat No</th>
                            <th scope="col">Caveat Name</th>
                            <th scope="col">Caveatee Name</th>
                            <th scope="col">Case No</th>
                            <th scope="col">Case Year</th>
                            <th scope="col">Decision Date</th>
                            <th scope="col">Commission Nname</th>
                            <th scope="col">Receipt</th>
                        </tr>
                    </thead>
					<tbody>';
        
        $rs=$this->db->select('a.caveat_filing_no,a.caveat_name,a.caveatee_name,a.case_no,a.case_year,a.decision_date,b.short_name as commission_name')
        ->from('caveat_detail a')
        ->join('master_commission b','b.id=CAST(a.commission AS INTEGER)','inner')
        ->order_by('RIGHT(a.caveat_filing_no,4)','DESC',false)
        ->order_by('SUBSTR(RIGHT(a.caveat_filing_no,10),1,6)','DESC',false)
        ->get()
        ->result();
        
        $count=1;
        foreach($rs as $row):;
        $data.='<tr>
                            <td>'.$count++.'</td>
                            <td>'.ltrim(substr($row->caveat_filing_no,5,6),"0").'</td>
                            <td>'.$row->caveat_name.'</td>
                            <td>'.$row->caveatee_name.'</td>
                            <td>'.$row->case_no.'</td>
                            <td>'.$row->case_year.'</td>
                            <td>'.date("d/m/Y",strtotime($row->decision_date)).'</td>
                            <td>'.$row->commission_name.'</td>
                            <td>'.form_button(["content"=>"&nbsp;","value"=>false,"class"=>"fa fa-print text-danger","onClick"=>"view_caveat_recipt('".$this->encryption->encrypt(trim($row->caveat_filing_no))."',this)"]).'</td>
                        </tr>';
        endforeach;
        
        $data.='</tbody>
				</table>';
        return $data;
    }
    
    public function fetch_iadata($year=NULL){
        return $data_array=$this->db->select('a.*,b.*,c.nature_name')
        ->from('satma_detail a')
        ->join('sat_case_detail b','b.filing_no=a.filing_no')
        ->join('moster_ia_nature c','CAST(a.ia_nature AS INTEGER)=c.nature_code')
        ->where("date_part('year',a.entry_date)",$year,false)
        ->order_by('a.status','DESC')
        ->order_by('a.entry_date','DESC')
        ->get()->result();
    }
    
    function ia_data_list(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $this->db->from('satma_detail');
        $query = $this->db->get();
        return $query->result();
    }
    
    function getadvRecord($key){
        $key=strtolower($this->input->post('key'));
        $key=trim($key);
        $this->db->select('adv_code,adv_reg,adv_name,adv_mobile');
        $this->db->from('master_advocate');
        $this->db->like('LOWER(adv_name)',$key,'after');
        $this->db->or_like('adv_mobile', $key,'after');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
       return $query->result();
    }
    
    
    function getAppRecord($key){
        $key=strtolower($this->input->post('key'));
        $key=trim($key);
        $this->db->select('org_id,short_org_name,org_name,mobile_no');
        $this->db->from('master_org');
        $this->db->like('LOWER(org_name)',$key,'after');
        $this->db->or_like('mobile_no', $key,'before');
        $query = $this->db->get();
       // echo $this->db->last_query();die;
        return $query->result();
    }
    
    
    function getAdv($key){
        $key=strtolower($this->input->post('key'));
        $key=trim($key);
        $this->db->select('adv_code,adv_name');
        $this->db->from('master_advocate');
        $this->db->like('LOWER(adv_name)',$key,'after');
        $query = $this->db->get();
        return $query->result();
    }
    
    
    
    public function get_authority($sid) {
        $this->db->where('regulator_type',$sid);
        $rs=$this->db->get('master_regulator')->result();
        return $rs;
    }
    
    
    function data_list_where($table,$col,$id){
        $data=$this->db->select('*')
        ->get_where($table,[$col=>$id])
        ->result();
        return $data;
    }
    
    function logvalidate(){
        $this->userData = $this->session->userdata('login_success');
        $userLoginid = $this->userData[0]->username;
        $validseesion= $this->session->userdata('log_session_is');
        $datausers= $this->data_list_where('users','username',$userLoginid);
        $seeion=$datausers[0]->session_is;
        if($validseesion==$seeion){
            return true;
        }
        return false;
    }
    
}