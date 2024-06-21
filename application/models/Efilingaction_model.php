<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Efilingaction_model extends CI_Model {
    
    function user() {
        parent::Model();
    }
    
    
    function getPartydetail($filing_no,$party_flag){
        $this->db->from('additional_party');
        $this->db->where(array('filing_no'=>$filing_no,'party_flag'=>$party_flag));
        $this->db->order_by('partysrno','ASC');
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }
    
    

    
}