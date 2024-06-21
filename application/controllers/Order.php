<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Order extends CI_Controller
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
    {error_reporting(0);

        parent::__construct();
        $this->load->model('Admin_model','admin_model');
        $this->load->model('Efiling_model','efiling_model');
		$this->userData = $this->session->userdata('login_success');
		$userLoginid = $this->userData[0]->username;

		
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

    public function orderUpload()
    {
		//$schemas=session()->get('schemas');
		$data='';
       if($this->input->Post()):
	  
				$case_type= $this->input->Post('case_type');
				$case_no= $this->input->Post('case_no');
				$case_year= $this->input->Post('case_year');
				
				$case_no="400".$case_type.str_pad($case_no,7,'0',STR_PAD_LEFT).$case_year ;
				//echo $case_no; die;
				$caseDetails= $this->db->get_where('sat_case_detail',['case_no'=>$case_no])->row_array();
				//print_r($caseDetails); die;
				//$caseDetails= $this->db->get_where('sat_case_detail',['case_no'=>$case_no])->result_array();
				if(!empty($caseDetails)):
				$filing_no = $caseDetails['filing_no'];  				//die;
				$caseAllocations= $this->db->get_where('case_allocation',['filing_no'=>$filing_no])->row_array();
				
				endif;
				if(empty($caseDetails)):
			$this->session->set_userdata('Error','Case Not Found');
			endif;
				endif;	
            $data=[
				'caseDetails'=>(isset($caseDetails))?$caseDetails:'',
				'caseAllocations'=>(isset($caseAllocations))?$caseAllocations:'',
				//'filing_no'=>$filing_no,
				'cat_code'=>1,'label1'=>'label1','cat_code'=>'label1','case_type'=>10,
				'pet_name'=>(isset($caseDetails['pet_name']))?$caseDetails['pet_name']:'',
				'res_name'=>(isset($caseDetails['res_name']))?$caseDetails['res_name']:'',
				'status'=>(isset($caseDetails['status']))?$caseDetails['status']:'',
				'next_list_date'=>(isset($caseAllocations['listing_date']))?date('d-m-Y',strtotime($caseAllocations['listing_date'])):'',
				'purposeArray'=>$this->db->get_where('master_purpose',['display'=>true])->result_array(),
				'judgeArray'=>$this->db->get_where('master_judge',['display'=>true])->result_array(),
				'actionArray'=>$this->db->get_where('master_action',['display'=>true])->result_array(),
				'getBenchNatureListArray'=>[''=>'SELECT'],
				'getRemarksArray'=>$this->db->get_where('remarks',['display'=>true])->result_array(),
            ];	
		//	print_r($data); die;
			$this->load->view("court/order_upload",$data);
    }
    
    
    
    
    
    public function orderAction(){
		$data1=$this->input->Post();
		//print_r($data1); die;
		
		$this->form_validation->set_rules('case_type','Please case type','trim|required|min_length[1]|max_length[1]|numeric');
		if($this->form_validation->run() == FALSE){
		    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
		}
		
		$this->form_validation->set_rules('case_no','Please case no','trim|required|min_length[1]|max_length[4]|numeric');
		if($this->form_validation->run() == FALSE){
		    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
		}
		
		$this->form_validation->set_rules('case_year','Please case year','trim|required|min_length[1]|max_length[4]|numeric');
		if($this->form_validation->run() == FALSE){
		    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
		}
		
		$this->form_validation->set_rules('filing_no','Please enter filing no','trim|min_length[15]|max_length[15]|numeric');
		if($this->form_validation->run() == FALSE){
		    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
		}
		
		$this->form_validation->set_rules('res_name','Please respondent name','trim|required|min_length[1]|max_length[250]');
		if($this->form_validation->run() == FALSE){
		    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
		}
		
		$this->form_validation->set_rules('pet_name','Please applicant name','trim|required|min_length[1]|max_length[250]');
		if($this->form_validation->run() == FALSE){
		    echo json_encode(['data'=>'error','value'=>validation_errors(),'massage'=>validation_errors(),'error'=>'1']);die;
		}
		
		
				$benchcode='2';
				if($benchcode ==2)
				{
					$bench ='Delhi';
				}
				$date= $data1['order_date'];
				$month = date('M', strtotime($date)); 
				$year = date('Y', strtotime($date)); 
				if($data1['order_type']=='F'){
					$path	= './upload_doc/'.$bench.'/order/final/';
				}else{				
					$path	= './upload_doc/'.$bench.'/order/daily/';
					//echo $path; die;
				}
				$config['upload_path']          = $path.$year.'/'.$month.'/';
				//echo $config['upload_path']; die;
				if(!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0777, TRUE);
				$config['allowed_types']        = 'pdf';
                $config['max_size']             = 1000;
                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload('userfile')){
                        $error = array('error' => $this->upload->display_errors());
						$this->session->set_userdata('Error','kindly upload pdf file only');
						$this->load->view('court/order_upload');
                }else {
                        $data = array('upload_data' => $this->upload->data());
						//$file_name= $data['upload_data']['full_path'];
						$file_name= $config['upload_path'].$data['upload_data']['file_name'];
						$orderDetails=[
						'filing_no'=> $data1['filing_no'],
						'case_type'=> $data1['case_type'],
						'case_no'=> $data1['case_no'],
						'case_year'=> $data1['case_year'],
						'pet_name'=> $data1['pet_name'],
						'res_name'=> $data1['res_name'],
						'judge_code'=> $data1['judge_code'],
						'date_of_order'=> date('Y-m-d',strtotime($data1['order_date'])),
						'status'=> $data1['status'],
						'order_id'=> '1',
						'bench'=> $benchcode,
						'order_type'=> $data1['order_type'],
						'remarks'=> $data1['remarks'],
						'display'=> 'Y',
						'path'=>$file_name,
						'entry_date'=>date('Y-m-d'),
						];
						$ordtl= $this->db->get_where('order_details',['filing_no'=>$orderDetails['filing_no'],'date_of_order'=>$orderDetails['date_of_order']])->row_array();
						if(empty($ordtl)) {
						$this->db->insert('order_details', $orderDetails);
						$this->session->set_userdata('Success','Order Uploaded Successfully');
						}
						else{
							$this->session->set_userdata('Error','Order Alredy Uploaded');
						}
						redirect('order');
                        //$this->load->view('court/order_upload');
                }
		
			
    }

}
