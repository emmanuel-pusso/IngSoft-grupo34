<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class model_user extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function is_registered($email) {
        $this->db->where('email', $email);
        $amount_results = $this->db->count_all_results('user');
        return ($amount_results == 1);
    }

    public function register_user($user) {
        $this->db->insert('user', $user);
    }
    public function compare_email($id_user){
        $this->db->select('email');
        $this->db->from('user');
        $this->db->where('id_user',$id_user);
        $consulta = $this->db->get();
        $resultado = $consulta->row();
        return $resultado;
    }

    public function user_by_name_pass($email, $password) {
        $this->db->select('password, email, nombre, apellido, id_user');
        $this->db->from('user');
        $this->db->where('email', $email);
        $this->db->where('password', $password);
        $consulta = $this->db->get();
        $resultado = $consulta->row();
        return $resultado;
    }

    public function update_user($user, $id) {
        $this->db->where('id_user', $id);
        $this->db->update('user', $user);
        $this->db->trans_complete();
        return ($this->db->trans_status() === TRUE);
    }
    

    public function user_by_id($id) {
        //retorno todos los datos de un user, por "id"   
        $this->db->where('id_user', $id);
        $consulta = $this->db->get('user');
        $resultado = $consulta->row_array();
        return $resultado;
    }
    
    public function eliminar_usuario(){
        $usuario = array(
                        'email' => '',
                        'password' => '',
                        'nombre' => $this->session->userdata('nombre'),
                        'apellido' => $this->session->userdata('apellido'),
                        'id_user'=>$this->session->userdata('id_user'),
                       
                    );
         $id = $this->session->userdata('id_user');
        $this->db->where('id_user', $id);
         $this->db->update('user', $usuario);
        
    }

}
