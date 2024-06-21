<?php
class home_model extends CI_Model {
    function user() {
        parent::Model();
    }
    
    function get_single_table($table){
        $this->db->where('status', '1');
        $query = $this->db->get($table);
        $data = $query->result();
        return $data;
    }
    
    function data_list($table,$col1,$col2){
        $this->db->select($col2,$col1);
        $this->db->from($table);
        //$this->db->order_by('state_name','ASC');
        //$query = $this->db->get_where($table, array('state_name'=>$id));
        $query = $this->db->get();
        $data = $query->result_array();
        //echo $str = $this->db->last_query();
        return $data;
    }
    
    
    function getdistric($table,$col){
        $this->db->from($table);
        $this->db->where('state_id', $col);
        $query = $this->db->get();
        $data = $query->result();
        return $data;
    }
    
    
    function insert_query($table, $data){
        $query = $this->db->insert($table, $data);
        return $query;
    }
    
    function edit_data($table,$col, $id){
        $query = $this->db->get_where($table, array($col=>$id));
        $data = $query->row();
        return $data;
    }
    
    function update_data($table, $data, $idname, $id){
        $this->db->where($idname, $id);
        $query = $this->db->update($table, $data);
        return $query;
    }
    
    function delete_event($table, $col, $id){
        $this->db->where($col, $id);
        $delqu = $this->db->delete($table);
        return $delqu;
        
    }
    
    function createSlug($slug) {
        $lettersNumbersSpacesHyphens = '/[^\-\s\pN\pL]+/u';
        $spacesDuplicateHypens = '/[\-\s]+/';
        $slug = preg_replace($lettersNumbersSpacesHyphens, '', $slug);
        $slug = preg_replace($spacesDuplicateHypens, '-', $slug);
        $slug = trim($slug, '-');
        return mb_strtolower($slug, 'UTF-8');
    }
    
}
?>