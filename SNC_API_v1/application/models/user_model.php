<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

     function checkUser($ref){
        $this->db->where('user_Id', $ref);
        $result = $this->db->get('social_feeds');
        return $result->result();
    }
    
     function insertFeed($data){
        return $this->db->insert('social_feeds', $data);
    }
}