<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'controllers/controller.php';

class eliminar_perfil extends controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->model('model_user');
        $this->load->model('model_tarjeta');
        $this->load->model('model_auto');
        $this->load->model('model_viaje');
        $this->load->model('model_solicitud');
        $this->load->model('model_calificacion');
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('upload');
    }

    public function index() {
        if ($this->session->userdata('logueado')) { //si está logueado
           
            $chofer = $this->model_viaje->viaje_pendiente_chofer();
            $pendiente =$this->model_viaje->viaje_pendiente_pasajero();
            $aprobado =$this->model_viaje->viaje_aprobado_pasajero();
            
            if($chofer<> FALSE){
            $fecha_actual = date("d-m-Y");
            $bool=FALSE;
            foreach($chofer as $each){
            $newDate = date("d-m-Y", strtotime($each['fecha']));
            if((strtotime($fecha_actual) < strtotime($newDate))){
                $bool=TRUE;
            }
            }
                
            }
            if(($bool==TRUE)or($pendiente ==TRUE) or($aprobado==TRUE )){
             
                $this->session->set_flashdata('error', 'POSEE VIAJES PENDIENTES. NO PODRÁ ELIMINAR SU PERFIL.');
               redirect('ver_perfil/mi_perfil'); 
            }else{
              $this->model_solicitud->eliminar_postulaciones_inactivas($this->session->userdata('id_user')) ; 
              $this->model_auto->eliminar_all_autos($this->session->userdata('id_user'));   
              $tarjeta = $this->model_tarjeta->consultar_tarjeta($this->session->userdata('id_user'));
              $this->model_tarjeta->eliminar_tarjeta($tarjeta->id_tarjeta);  
              $this->model_user->eliminar_usuario();
              
               $usuario_data = array(
                        'logueado' => FALSE
                    );
                    $this->session->set_userdata($usuario_data);
               $this->session->set_flashdata('notifico', 'USUARIO ELIMINADO EXITOSAMENTE.');
               redirect('login');
              
            }
            //parent::index_page('view_editar_perfil', $data);
        } else {
            redirect('login');
        }
    }
    
    
    public function ver_eliminar_perfil(){
        $this->session->set_flashdata('nom',$this->session->userdata('nombre'));
        $this->session->set_flashdata('ap',$this->session->userdata('apellido'));
        $calif_chofer = $this->model_calificacion->mostrar_calificacion_as_chofer($this->session->userdata('id_user'));
        $calif_pasajero = $this->model_calificacion->mostrar_calificacion_as_pasajero($this->session->userdata('id_user'));
        $this->session->set_flashdata('calif_chofer',$calif_chofer);
        $this->session->set_flashdata('calif_pasajero',$calif_pasajero);
        $data=0;
        parent::index_page('view_eliminar_perfil',$data);  
        
    }
}