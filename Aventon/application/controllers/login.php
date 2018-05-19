<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //load model
        $this->load->model('model_user');
        //load code igniter library to validate form
        $this->load->library('form_validation');
    }

    public function index() {
        $data['title'] = 'Login Aventon';
        //load view and pass the data
        $this->load->view('templates/header', $data);
        $this->load->view('view_login');
        $this->load->view('templates/footer');
    }

    function existEmail() {
        $email = $this->input->post('email');
        //verifies email exists in DB
        return ($this->model_user->is_registered($email));
    }

    function validPass() {
        //verifies password matches with user password
        $email = $this->input->post('email');
        $pass = $this->input->post('password');
        return ($this->model_user->validate_credentials($email, $pass));
    }

    public function validar() {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_existEmail'); //nombre campo, label men error, validación
        $this->form_validation->set_rules('password', 'Password', 'required|callback_validPass');

        if ($this->form_validation->run() == FALSE) {
            // no pasa validacion, vuelvo a mostrar el formulario con los datos precargados
            $this->load->view('fail');
        } else {
            // validacion exitosa
            $this->load->view('success');
        }
    }

}
