<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
class Backlogold extends CI_Controller {
    function __construct() {
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
    
    function backlog(){
		
		//print_r($_POST); //die;
		$data='';
		
		if($this->input->post('submit')):
		 //echo "reached to search "; //die;
		$data=$this->input->Post();
		$case_type= $data['case_type'];
		$case_no= $data['case_no'];
		$case_year= $data['case_year'];
		
		$case_no="400".$case_type.str_pad($case_no,7,'0',STR_PAD_LEFT).$case_year ;
	    $data['caseDetails']= $this->db->get_where('sat_case_detail',['case_no'=>$case_no])->result_array();
		//print_r($this->db->last_query());  die;
        //print_r($data); die;
        
		endif;
		/*if($this->input->post('Submit')):
		 echo "reached to save"; die;
		 endif;*/
		 $this->load->view("backlog/back_log",$data);
    }
	
	function backlogSave(){
		 //echo "reached to save"; //die;
		//print_r($_POST); die;
		$data='';
		
		 
		// echo "reached hgfhgfhgfhgf"; die;
		$data=$this->input->Post();
		$case_type= $data['case_type'];
		$case_no= $data['case_no'];
		$case_year= $data['case_year'];
		
		$case_no="400".$case_type.str_pad($case_no,7,'0',STR_PAD_LEFT).$case_year ;
	    //$data['caseDetails']= $this->db->get_where('sat_case_detail',['case_no'=>$case_no])->result_array();
		$bench='100';
	    $subBench='1';
		$benchCode= htmlspecialchars(str_pad($bench,3,'0',STR_PAD_LEFT));
	    $subBenchCode= htmlspecialchars(str_pad($subBench,2,'0',STR_PAD_LEFT));
		$st=$this->efiling_model->data_list_where('year_initialization','year',$case_year);
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
						//echo $filing_no; die;
	                    $filing_no = "0".$filing_no;
	                }
	            }
				//echo $fil_no;
				//echo $filing_no; die;
				$this->db->update('year_initialization', ['filing_no'=>$fil_no], ['year'=>$case_year]);
				//$this->db->last_query();  die;
	            $filing_no=$benchCode.$subBenchCode.$filing_no.$case_year;
				//echo  $filing_no; die;
		
		if ($this->input->Post('status')=='D')
		{
			$disposel_date = date('Y-m-d',strtotime($this->input->Post('disposel_date')));
		}
		
		
		$obarray1 =[
				'filing_no'=>$filing_no,
				'case_no'=>$case_no,
				'case_type'=>$case_type,
				'iordernumber'=>$this->input->Post('iordernumber'),
				'iorderdate'=>$this->input->Post('iorderdate'),
				'rimpugnedorder'=>$this->input->Post('rimpugnedorder'),
				'dt_of_filing'=>$this->input->Post('dt_of_filing'),
				'regis_date'=>$this->input->Post('regis_date'),
				'ipenalty'=>$this->input->Post('ipenalty'),
				'status'=>$this->input->Post('status'),
				'disposel_date'=>$disposel_date,
				//'hearing_date'=>date('Y-m-d',strtotime($hearing_date)),
				'bench'=>$this->input->Post('bench_nature'),
				'user_id'=>$this->input->Post('user_id'),
				'pet_type'=>1,
				'pet_name'=>$this->input->Post('petName'),
				'pet_adv'=>$this->input->Post('pet_adv'),
				'pet_state'=>$this->input->Post('pet_state'),
				'pet_district'=>$this->input->Post('ddistrict'),
				'pet_email'=>$this->input->Post('petEmail'),
				'pet_mobile'=>$this->input->Post('petmobile'),
				'pet_phone'=>$this->input->Post('petPhone'),
				'pet_pin'=>$this->input->Post('pincode'),
				'pet_fax'=>$this->input->Post('petFax'),
				'pet_address'=>$this->input->Post('petAddress'),
				
				'res_type'=>1,
				'res_name'=>$this->input->Post('resName'),
				'res_adv'=>$this->input->Post('res_adv'),
				'res_state'=>$this->input->Post('stateRes'),
				'res_district'=>$this->input->Post('ddistrictname'),
				'res_email'=>$this->input->Post('resEmail'),
				'res_mobile'=>$this->input->Post('resmobile'),
				'res_phone'=>$this->input->Post('resPhone'),
				'res_pin'=>$this->input->Post('respincode'),
				'res_fax'=>$this->input->Post('resFax'),
				'res_address'=>$this->input->Post('resAddress'),
			
			];
			$obarray2 =[
				'filing_no'=>$filing_no,
				//'case_no'=>$case_no,
				//'case_type'=>$case_type,
				
				//'notification_date'=>$this->input->Post('notification_date'),
				//'compliance_date'=>$this->input->Post('compliance_date'),
				'objection_status'=>'N', //$this->input->Post('objection_status'),
				'defects'=>'N', //$this->input->Post('defects'),
				//'objexpdate'=>$this->input->Post('objexpdate'),
				//'defects'=>$this->input->Post('defects'),
			];
		if ($this->input->Post('status')=='P')
		{
			$hearing_date = date('Y-m-d',strtotime($this->input->Post('disposel_date')));
			
			
			$obarray3 =[
				'filing_no'=>$filing_no,
				'listing_date'=>$hearing_date,
				'purpose'=>$this->input->Post('purpose'),
				'court_no'=>$this->input->Post('court_no'),
				'bench_nature'=>$this->input->Post('bench_nature'),
		];
		$this->db->insert('case_allocation',$obarray3);
		}
			
			$rs=$this->db->insert('sat_case_detail',$obarray1);
			$this->db->insert('scrutiny',$obarray2);
			
		$this->session->set_userdata('Success','Case Updated Successfully');
		redirect('backlog');
    }
	
	    function  district(){
	        $state=$_POST['state_id'];
	        $st=$this->efiling_model->data_list_where('master_psdist','state_code',$state);
	        $val='';
	        if(!empty($st)){
                foreach($st as $row){
                    $val.='<option value="'.$row->district_code.'">'.$row->district_name.'</option>';
                }
            }
            echo  $val;
	    }
	    
}



