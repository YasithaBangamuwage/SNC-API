<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {

	public function __construct(){
		parent::__construct();
        // To use base_url and redirect on this controller.
        $this->load->helper('url');
	}

	function index(){
		$this->load->view('home');
	}
}