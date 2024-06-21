<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Iafiling extends CI_Controller {
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
		else $_POST[$key]=htmlspecialchars(strtr($val, $spcl_char));
		endforeach;
		
		$logvvalidate=$this->admin_model->logvalidate();
		if($logvvalidate==false){
		    $this->session->unset_userdata('login_success');
		    redirect(base_url());
		}
    }
    
    function  iabasic_detail(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('iasalt');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['datacomm']= '';
            $this->load->view('ia/iabasic_detail',$data);
        }
    }
    function ia_partydetail(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('iasalt');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['datacomm']=  '';
            $this->load->view('ia/ia_partydetail',$data);
        }
    }
    function ia_detail_ia(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('iasalt');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['datacomm']= '';
            $this->load->view('ia/ia_detail_ia',$data);
        }
    }
    
    function ia_upload_doc(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('iasalt');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['datacomm']=  '';
            $this->load->view('ia/ia_upload_doc',$data);
        }
    }
    function ia_checklist(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('iasalt');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['checklist']= $this->efiling_model->data_list_where('master_checklist','status','1');
            $this->load->view('ia/ia_checklist',$data);
        }
    }
    function ia_finalprivew(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('iasalt');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['datacomm']=  '';
            $this->load->view('ia/ia_finalprivew',$data);
        }
    }
    function ia_payment(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('iasalt');
        $user_id=$userdata[0]->id;
        if($user_id){
            $data['datacomm']=  '';
            $this->load->view('ia/ia_payment',$data);
        }
    }
    
    function ia_finalreceipt(){
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('iasalt');
        $user_id=$userdata[0]->id;
        if($user_id){
            $this->load->view('ia/ia_finalreceipt');
        }
    }

    
    function iasave(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        if($_REQUEST['submittype']=='finalsave'){
            $userdata=$this->session->userdata('login_success');
            $user_id=$userdata[0]->id;
            $salt=$this->session->userdata('iasalt');
            $curdate=date('Y-m-d');
            $iarow=$this->efiling_model->data_list_where('temp_iadetail','salt',$salt);
            $filed_by=isset($iarow[0]->partyType)?$iarow[0]->partyType:'';
            $filingNo=isset($iarow[0]->filing_no)?$iarow[0]->filing_no:'';
            $iaNature=isset($iarow[0]->ianature)?$iarow[0]->ianature:''; // $_REQUEST['ia_nature'];
            $party=isset($iarow[0]->iapartys)?$iarow[0]->iapartys:'';
            $feecode=explode(",",$iaNature);
            
            $curYear=date('Y');
            if($feecode[0]!="") {
                $printIAno='';
                $iaNo='';
                $len=sizeof($feecode)-1;
                for($k=0;$k<$len;$k++){
                    $ia_nature=$feecode[$k];
                    $year_ins=$this->efiling_model->data_list_where('ia_initialization','year',$curYear);
                    $ia_filing_no=$year_ins[0]->ia_filing;
                    if($ia_filing_no ==0){
                        $iaFilingNo=1;
                        $filno = $ia_filing_no ='000001';
                    }
                    if($ia_filing_no!=0){
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
                    $iaFiling_no1='1000'.'1'.$ia_filing_no.$curYear;
                    $printIAno=$printIAno."MA/".$iaFilingNo."/".$curYear."<br>";
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
                
                //Document Filing
                $st=$this->efiling_model->data_list_where('temp_documents_upload','salt',$salt);
                if(!empty($st)){
                    foreach($st as $vals){
                        $data12=array(
                            'filing_no'=>$filingNo,
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
                    $strsrno="Select max(partysrno :: INTEGER) as partysrno from additional_party where filing_no='$filingNo' and party_flag='$flag'";
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
                /*    $this->efiling_model->delete_event('temp_iadetail','salt',$salt);
                 $this->efiling_model->delete_event('temp_documents_upload','salt',$salt);
                 $this->efiling_model->delete_event('sat_temp_payment','salt',$salt); */
                //  $this->session->unset_userdata('iasalt');
                $vals=base_url().'iaprint/'.$filingNo.'/'.base64_encode($iaNo).'/'.$curYear;
                $data['url']=$vals;
                $data['iaregisterd']=$vasl;
                $this ->session->set_userdata('iadetail',$data);
                echo json_encode(['data'=>'success','display'=>'','error'=>1]);die;
            }
        }else{
            $massage='Request not Valid';
            echo json_encode(['data'=>'error','display'=>$massage,'error'=>1]);die;
        }
    }
    
    
    
    function saveIabasic(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $token=htmlentities($_REQUEST['token']);
        $tabno=htmlentities($_REQUEST['tab_no']);
        $filing_no=htmlentities($_REQUEST['filing_no']);
        $saltval='';
        $toalannexure=htmlentities($_REQUEST['toalannexure']);
        $type=htmlentities($_REQUEST['type']);
        $cudate=date('Y-m-d');
        $subtoken=$this->session->userdata('submittoken');
        $this->form_validation->set_rules('type','Please select type','trim|required|min_length[1]|max_length[2]|htmlspecialchars|regex_match[/^[a-z,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('toalannexure','Please toal annexure','trim|required|min_length[1]|max_length[3]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('filing_no','enter valid filing no','trim|required|min_length[15]|max_length[15]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('tab_no','Please enter tab number','trim|required|min_length[1]|max_length[2]|htmlspecialchars|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }

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
            $this->session->set_userdata('iasalt',$salt);
        }
        if($subtoken==$token){
            $postdata=array(
                'salt'=>$salt,
                'totalanx'=>$toalannexure,
                'filing_no'=>$filing_no,
                'tab_no'=>$tabno,
                'entry_date'=>$cudate,
                'user_id'=>$user_id,
                'type'=>$type,
            );
            $st=$this->efiling_model->insert_query('temp_iadetail', $postdata);
            if($st){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);
        }
    }
    
    
    function iapartysave(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $token=$this->input->post('token',true);
        $tabno=$this->input->post('tab_no',true);
        $salt=$this->session->userdata('iasalt');
        $type=htmlentities($this->input->post('type',true));
        $partyType=htmlentities($this->input->post('partyType',true));
        $iapartys=htmlentities($this->input->post('partyids'));
        $cudate=date('Y-m-d');
        $subtoken=$this->session->userdata('submittoken'); 
        $this->form_validation->set_rules('partyids','Please select type','trim|required|min_length[1]|max_length[25]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('type','Please select type','trim|required|min_length[1]|max_length[4]|htmlspecialchars');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('partyType','Please select type','trim|required|min_length[1]|max_length[2]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('tab_no','Please enter tab number','trim|required|min_length[1]|max_length[3]|htmlspecialchars|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('filing_no','enter valid filing no','trim|required|min_length[15]|max_length[15]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        if($subtoken==$token){
            $array=array(
                'tab_no'=>$tabno,
                'entry_date'=>$cudate,
                'user_id'=>$user_id,
                'type'=>$type,
                'partyType'=>$partyType,
                'iapartys'=>$iapartys,
            );           
            $where=array('salt'=>$salt);
            $st = $this->efiling_model->update_data_where('temp_iadetail',$where,$array);
            if($st){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);
        }
    }
    
    
    function iadetailsave(){
        $cudate=date('Y-m-d');
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $salt=$this->session->userdata('iasalt');
        $subtoken=$this->session->userdata('submittoken');
        $token=$this->input->post('token');
        $tabno=$this->input->post('tabno',true);
        $type=$this->input->post('type',true);
        $iaNature=$this->input->post('iaNature',true);
        $totalia=$this->input->post('totalia',true);
        $this->form_validation->set_rules('totalia','Please enter total number of IA','trim|required|min_length[1]|max_length[3]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('iaNature','Please enter ia nature','trim|required|min_length[1]|max_length[25]|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('type','Please enter type','trim|required|min_length[1]|max_length[4]|htmlspecialchars');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('tabno','Please enter tab number','trim|required|min_length[1]|max_length[3]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']);die;
        }
        $this->form_validation->set_rules('filing_no','enter valid filing no','trim|required|min_length[15]|max_length[15]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        $this->form_validation->set_rules('totalia','enter valid total number of ia','trim|required|min_length[0]|max_length[15]|htmlspecialchars|regex_match[/^[0-9,]+$/]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>'','massage'=>validation_errors(),'error'=>'1']); die;
        }
        if($subtoken==$token){
            $array=array(
                'tab_no'=>$tabno,
                'entry_date'=>$cudate,
                'user_id'=>$user_id,
                'type'=>$type,
                'totalia'=>$totalia,
                'ianature'=>$iaNature,
            );
            $where=array('salt'=>$salt);
            $st = $this->efiling_model->update_data_where('temp_iadetail',$where,$array);
            if($st){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);
        }
    }
    
    
    
    
    function doc_save_nextIA(){
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $token=htmlentities($_REQUEST['token']);
        $tabno=htmlentities($_REQUEST['tabno']);
        $untak=htmlentities($_REQUEST['untak']);
        $salt=$this->session->userdata('iasalt');
        $cudate=date('Y-m-d');
        $subtoken=$this->session->userdata('submittoken');
        if($subtoken==$token){
            $st=$this->efiling_model->data_list_where('rpepcp_reffrence_table','salt', $salt);
            $partyType=isset($st[0]->partyType)?$st[0]->partyType:'';
            $subdoc=$this->efiling_model->data_list_where('temp_documents_upload','salt',$salt);
            foreach($subdoc as $subdocval){
                if($subdocval->docid!=0){
                    $subdocvalue[]=$subdocval->docid;
                }
            }
            if($partyType==1 && $partyType==2){
                $subdocvalue[]=18;
            }
            $doctype=$this->efiling_model->data_list_where('master_document_efile','doctype','ia');
            foreach($doctype as $doc){
                $doctypearr[]=$doc->id;
            }
            $result=array_diff($doctypearr,$subdocvalue);
            if($partyType=='1' || $partyType=='2'){
                if(!empty($result)){
                    echo json_encode(['data'=>'Please upload all mandatory document','error'=>'1']);die;
                }
            }
            if($partyType=='3'){
                if(!empty($result)){
                    echo json_encode(['data'=>'Please upload all mandatory document','error'=>'1']);die;
                }
            }
            
            
            $array=array(
                'tab_no'=>$tabno,
                'undertaking'=>$untak,
            );
            $where=array('salt'=>$salt);
            $st = $this->efiling_model->update_data_where('temp_iadetail',$where,$array);
            if($st){
                $massage="Successfully Submit.";
                echo json_encode(['data'=>'success','value'=>'','massage'=>$massage,'error'=>'0']);
            }
        }else{
            $massage="User not valid.";
            echo json_encode(['data'=>'error','value'=>'','massage'=>$massage,'error'=>'1']);
        }
    }
    
    
    
    function chk_listdataIA(){
        if($this->session->userdata('login_success') && $this->input->post()) {
            $salt=$this->session->userdata('iasalt');
            $userdata=$this->session->userdata('login_success');
            $user_id=$userdata[0]->id;
            $token=htmlentities($this->input->post('token'));
            $type=htmlentities($this->input->post('type'));
            $subtoken=$this->session->userdata('submittoken');
            $tabno=htmlentities($this->input->post('tabno'));
            if($subtoken==$token){
                $tabno=htmlentities($this->input->post('tabno'));
                $this->form_validation->set_rules('tabno','Enter tab number.','trim|required|min_length[1]|max_length[2]|numeric');
                if($this->form_validation->run() == FALSE){
                    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
                }
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
                $st = $this->efiling_model->update_data_where('temp_iadetail',$where,$array);
                if($db) echo json_encode(['data'=>'success','error'=>'0']);
                else 	echo json_encode(['data'=>'Qyery error, try again','error'=>'1']);
            }
        }
        else echo json_encode(['data'=>'Permission deny!','error'=>'1']);
    }
    
    
    
    
    
    function iafpsave(){
        $salt=$this->session->userdata('iasalt');
        $userdata=$this->session->userdata('login_success');
        $user_id=$userdata[0]->id;
        $token=htmlentities($this->input->post('token'));
        $type=htmlentities($this->input->post('type'));
        $iatotalfee=htmlentities($this->input->post('totalfee'));
        $subtoken=$this->session->userdata('submittoken');
        
        $this->form_validation->set_rules('tabno','Enter tab number.','trim|required|min_length[1]|max_length[2]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        
        $this->form_validation->set_rules('totalfee','Enter total fee.','trim|required|min_length[1]|max_length[7]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']); die;
        }
        if($subtoken==$token){
            $tabno=$_REQUEST['tabno'];
            $datatab=array(
                'tab_no'    =>$tabno,
                'iatotal_fee'=>$iatotalfee,
            );
            $st1=$this->efiling_model->update_data('temp_iadetail', $datatab,'salt', $salt);
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
            if($valcheck=='yes'){
                echo json_encode(['data'=>'success','display'=>$valcheck,'error'=>'0']);die;
            }else{
                echo json_encode(['data'=>'error','display'=>'no','error'=>'1']);die;
            }
        }else {
            echo json_encode(['data'=>'error','display'=>'Vakalatnama Not Filed','error'=>'1']);die;
        }
      }
 
    
}