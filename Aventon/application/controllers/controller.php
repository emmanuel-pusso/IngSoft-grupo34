<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index_page($view, $data) {
        //load view and pass the data
        if ($this->session->userdata('logueado')) {
            $this->load->view('templates/header');
            $this->load->view('templates/navbar');
            $this->load->view('templates/sidebar');
            $this->load->view($view, $data);
            $this->load->view('templates/footer');
        } else {
            $this->load->view('templates/header-logout');
            $this->load->view($view, $data);
        }
    }

}
