<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//namespace Modules\Court\Controllers;
//use  Application\Models\Admin_model;
//use Modules\Court\Config\Validation;
class Court extends CI_Controller
{
	private $benchmodel;
	private $benchjudge;
	private $app_proceeding;
	private $case_detail;
	private $app_detail;
	private $app_proceeding_his;
	private $app_allocation;
	private $app_allocation_his;
	private $case_proceeding_rpt;
    public function __construct()
    {   error_reporting(0);

         parent::__construct();
        $this->load->model('Admin_model','admin_model');
        $this->load->model('Efiling_model','efiling_model');
       // $this->load->model('Caseallocation','Caseallocation');
		$this->userData = $this->session->userdata('login_success');
		$userLoginid = $this->userData[0]->username;
		/*if(empty($userLoginid)){
			//redirect(base_url(),'refresh');
		}*/
		
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

    public function caseProceedings($filing_no){
        
       		$caseDetails= $this->db->get_where('sat_case_detail',['filing_no'=>$filing_no])->row_array();
				$caseAllocations= $this->db->get_where('case_allocation',['filing_no'=>$filing_no])->row_array();
			$data=[
				'caseDetails'=>(isset($caseDetails))?$caseDetails:'',
				'caseAllocations'=>(isset($caseAllocations))?$caseAllocations:'',
				'filing_no'=>(isset($caseDetails['filing_no']))?$caseDetails['filing_no']:'',
				'cat_code'=>1,'label1'=>'label1','cat_code'=>'label1','case_type'=>10,
				'pet_name'=>(isset($caseDetails['pet_name']))?$caseDetails['pet_name']:'',
				'res_name'=>(isset($caseDetails['res_name']))?$caseDetails['res_name']:'',
			//	'next_list_date'=>(isset($caseAllocations['listing_date']))?date('d-m-Y',strtotime($caseAllocations['listing_date'])):'',
				//'label'=>(isset($caseAllocations['listing_date'])!=null)?'Next Hearing Date':'First Hearing Date',
				'caseTypesArray'=>$this->db->get_where('master_case_type',['display'=>true])->result_array(),
				'purposeArray'=>$this->db->get_where('master_purpose',['display'=>true])->result_array(),
				'actionArray'=>$this->db->get_where('master_action',['display'=>true])->result_array(),
				'getBenchNatureListArray'=>[''=>'SELECT'],
				'getRemarksArray'=>$this->db->get_where('remarks',['display'=>true])->result_array(),
            ];
				 
			$this->load->view("court/caseproceeding",$data);
       // return view('court/caseproceeding',$data);

    }
    public function proceedingAction()
    {
        
        $this->form_validation->set_rules('purpose','Please purpose','trim|required|min_length[1]|max_length[4]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        
        $this->form_validation->set_rules('filing_no','Please enter filing_no','trim|required|min_length[15]|max_length[15]|numeric');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        
        
        $this->form_validation->set_rules('remark','Please enter remark','trim|required|min_length[1]|max_length[500]');
        if($this->form_validation->run() == FALSE){
            echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
        }
        
        
        
        $data=$this->input->Post();
		$caseAllocations= $this->db->get_where('case_allocation',['filing_no'=>$data['filing_no']])->row_array();
		$caseproceeding= $this->db->get_where('case_proceeding',['filing_no'=>$data['filing_no']])->row_array();
        $sql="insert into case_proceeding_his select * from case_proceeding where filing_no ='".$data['filing_no']."'";
        $this->db->query($sql);
        $proceedingdata=[
		'filing_no'=>$data['filing_no'],
		'listing_date'=>date('Y-m-d',strtotime($data['listing_date'])),
		'next_list_date'=>date('Y-m-d',strtotime($data['next_list_date'])),
		'todays_action'=>$data['todays_action'],
		'bench_nature'=>$caseAllocations['bench_nature'],
        //'bench_nature'=>'5',
		'purpose'=>$data['purpose'],
		'remarks'=>$data['remark'],
		'entry_date'=>date('Y-m-d')
		];
		if(!empty($caseproceeding)){
				$this->db->where(['filing_no'=>$data['filing_no']]);
				$this->db->update('case_proceeding', $proceedingdata);
			} else {
				$this->db->insert('case_proceeding', $proceedingdata);
			}
		
        $case_allochis=[
            'filing_no'=>$caseAllocations['filing_no'],
            'bench_nature'=>$caseAllocations['bench_nature'],
            'listing_date'=>date('Y-m-d',strtotime($caseAllocations['listing_date'])),
            'purpose'=>$caseAllocations['purpose'],
            'deal_cd'=>$caseAllocations['deal_cd'],
            'entry_date'=>date('Y-m-d',strtotime($caseAllocations['entry_date'])),
            'id'=>$caseAllocations['id'],
            'remarks'=>$caseAllocations['remarks']
            ];
       
        $this->db->insert('case_allocation_his', $case_allochis);
        $case_alloc=[
            'filing_no'=>$caseAllocations['filing_no'],
			//'bench_nature'=>'5',
            'listing_date'=>(date('Y-m-d',strtotime($this->input->Post('next_list_date'))!='')?date('Y-m-d',strtotime($this->input->Post('next_list_date'))):null),
            'purpose'=>$this->input->Post('purpose'),
            'remarks'=>$this->input->Post('remarks'),
            'entry_date'=>date('Y-m-d',strtotime($caseAllocations['entry_date'])),
            'id'=>$caseAllocations['id'],
            'remarks'=>$caseAllocations['remarks']
            ];
			
				$this->db->where(['filing_no'=>$data['filing_no']]);
				$this->db->update('case_allocation', $case_alloc);
		
	   $this->session->set_userdata('Success','Case Proceeded Successfully');
		redirect('proceeding');
    }
	
	function proceeding(){
		//print_r($_POST); die;
        $userdata=$this->session->userdata('login_success');
        $salt=$this->session->userdata('salt');
        $user_id=$userdata[0]->id;
		$listing_date= $this->input->Post('listing_date');
		if ($listing_date=='')  {  
		$listing_date=date('Y-m-d');	}	//die;
        if($user_id){
			//$data['filedcase']= $this->efiling_model->data_list_where('sat_case_detail','filed_user_id',$user_id);
			$listing_date=date('Y-m-d',strtotime($listing_date));
            $data['filedcase']= $this->db->get_where('case_allocation',array('listing_date' =>$listing_date))->result_array();
			//print_r( $data['filedcase']); //die;
			 $data['listing_date']=$listing_date;
            $this->load->view("court/proceedinglist",$data);
        }
    }

}
