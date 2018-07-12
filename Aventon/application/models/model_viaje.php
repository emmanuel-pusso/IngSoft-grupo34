<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class model_viaje extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function getMisAutos() { //para listar mis autos en el formulario de Crear Viaje
        $id = $this->session->userdata('id_user');

        $this->db->from('auto');
        $this->db->where('id_user', $id);
        $this->db->where('estado', 0);

        $this->db->select('marca, modelo, num_patente, id_auto');

        $consulta = $this->db->get();
        $resultado = $consulta->result();
        return $resultado;
    }

    private function valida_antes($id_chofer, $fecha_inicio, $hora_inicio) {
        //Viaje que quiero crear, inicio está contenido en otro viaje
        $superpone_inicio = $this->db->query("SELECT id_viaje FROM viaje WHERE id_chofer=$id_chofer AND estado=0 AND cast(concat('$fecha_inicio',' ','$hora_inicio') as datetime) BETWEEN cast(concat(fecha,' ',hora_inicio) as datetime) AND DATE_ADD(cast(concat(fecha,' ',hora_inicio) as datetime), INTERVAL duracion_horas hour)");
        return $superpone_inicio->num_rows();
        /*
          $resultado = $superpone_inicio->row_array();
          return $resultado;
         */
    }

    private function valida_despues($id_chofer, $fecha_inicio, $hora_inicio, $duracion) {
        //Viaje que quiero crear, inicio está contenido en otro viaje
        $superpone_fin = $this->db->query("SELECT id_viaje FROM viaje WHERE id_chofer=$id_chofer AND estado=0 AND DATE_ADD(cast(concat('$fecha_inicio',' ','$hora_inicio') as datetime),INTERVAL cast('$duracion' as int) hour) AND cast(concat('$fecha_inicio',' ','$hora_inicio') as datetime) BETWEEN cast(concat(fecha,' ',hora_inicio) as datetime) AND DATE_ADD(cast(concat(fecha,' ',hora_inicio) as datetime), INTERVAL duracion_horas hour)");
        return $superpone_fin->num_rows();
        /*
          $resultado = $superpone_fin->row_array();
          return $resultado;
         */
    }

    private function valida_entre($id_chofer, $fecha_inicio, $hora_inicio, $duracion) {
        // Viaje que quiero crear contiene a otro viaje
        $superpone_entre = $this->db->query("SELECT id_viaje FROM viaje WHERE id_chofer=$id_chofer AND estado=0 AND cast(concat('$fecha_inicio',' ','$hora_inicio') as datetime) <= cast(concat(fecha,' ',hora_inicio) as datetime) AND DATE_ADD(cast(concat('$fecha_inicio',' ','$hora_inicio') as datetime),INTERVAL cast('$duracion' as int) hour) >= DATE_ADD(cast(concat(fecha,' ',hora_inicio) as datetime), INTERVAL duracion_horas hour)");
        return $superpone_entre->num_rows();
        /*
          $resultado = $superpone_entre->row_array();
          return $resultado;
         */
    }

    public function is_registered($viaje) {
        // Obtengo los datos del viaje pasado por parámetro en variables
        $hora_inicio = $viaje['hora'];
        $duracion = $viaje['duracion'];
        $id_chofer = $viaje['id_chofer'];
        $todas_fechas = $viaje['array_fechas'];
        $resultado = 0;
        foreach ($todas_fechas as $fecha_inicio) {
            $resultado = $this->valida_antes($id_chofer, $fecha_inicio, $hora_inicio) + $this->valida_despues($id_chofer, $fecha_inicio, $hora_inicio, $duracion) + $this->valida_entre($id_chofer, $fecha_inicio, $hora_inicio, $duracion);
            if ($resultado >= 1)
                return $resultado;
        }
        return $resultado;
    }

    //registra uno o más viajes, de acuerdo al array de viajes pasado por parámetro
    public function registrar_viaje($array_viajes) {
        foreach ($array_viajes as $viaje) {
            $this->db->insert('viaje', $viaje);
        }
        $this->db->trans_complete();
        return ($this->db->trans_status() === TRUE);
    }

    public function consulta_id_viaje($viaje) {
        $this->db->select('id_viaje');
        $this->db->from('viaje');
        $this->db->where('fecha', $viaje['fecha']);
        $this->db->where('hora_inicio', $viaje['hora_inicio']);
        $this->db->where('id_chofer', $viaje['id_chofer']);
        $consulta = $this->db->get();
        $resultado = $consulta->row();
        return $resultado;
    }

    // cuando se implemente las búsquedas $search debería ser un array, 
    // ya que hay más de un criterio (orígen, destino, fecha, etc) 
    public function getViajes($rowno, $rowperpage, $search) {

        if (!empty($search)) {

            $this->db->like('origen', $search['origen']);
            $this->db->like('destino', $search['destino']);
            if ($search['fecha']) { //búsqueda por fecha es opcional
                $this->db->where('fecha', $search['fecha']);
            }
        }
        // Si selecciono "solo mis viajes"
        $solo_mis_viajes =$this->session->userdata('solo_mis_viajes');
        if ($solo_mis_viajes)
            {
             $id_chofer=$this->session->userdata('id_user');
             $this->db->where('id_chofer', $id_chofer);
            }
        
        //ordered desc to display the new element at the top
        $current_date = date("Y-m-d");
        $this->db->order_by('fecha', 'asc');
        $this->db->where('fecha >= ', $current_date);
        $this->db->where('estado', 0);
        $query = $this->db->get('viaje', $rowperpage, $rowno);

        return $query->result_array();
        //result_array() returns the query result as a pure array, 
        //or an empty array when no result is produced.
    }

    public function viaje_por_id($id) {

        $this->db->where('id_viaje', $id);
        $consulta = $this->db->get('viaje');
        $resultado = $consulta->row(); //This function returns a single result row
        return $resultado;
    }

    // Select total records according with search parameters
        public function getrecordCount($search) {
        if (!empty($search)) {

            $this->db->like('origen', $search['origen']);
            $this->db->like('destino', $search['destino']);
            if ($search['fecha']) { //búsqueda por fecha es opcional
                $this->db->where('fecha', $search['fecha']);
            }
        }
        
          // Si selecciono "solo mis viajes"
        $solo_mis_viajes =$this->session->userdata('solo_mis_viajes');
        if ($solo_mis_viajes)
            {
             $id_chofer=$this->session->userdata('id_user');
             $this->db->where('id_chofer', $id_chofer);
            }
        
        $current_date = date("Y-m-d");

        $this->db->from('viaje');
        $this->db->where('fecha >= ', $current_date);
        $this->db->where('estado', 0);

        return $this->db->count_all_results();
    }
    
    //Un usuario tiene un viaje activo, cuando la fecha del viaje es >= fecha actual, y el estado del viaje no es "rechazado"
    public function tiene_viaje_activo($id_user){ 
        //estados de viajes distinto de rechazado(3): 1 (pendiente), 2(aprobado), 4 (inactivo)
        $estado = array(1,2,4);
        $current_date = date("Y-m-d");
        $this->db->from('postulacion_viaje');
        $this->db->join('viaje', 'viaje.id_viaje = postulacion_viaje.id_viaje');
        $this->db->where('postulacion_viaje.id_user', $id_user);
        $this->db->where('fecha >= ', $current_date);
        $this->db->where_in('id_estado', $estado);
        $amount = $this->db->count_all_results();
        return ($amount >= 1);   
    }
/*
    public function getrecordCount($search) {
        if (!empty($search)) {

            $this->db->like('origen', $search['origen']);
            $this->db->like('destino', $search['destino']);
            if ($search['fecha']) { //búsqueda por fecha es opcional
                $this->db->where('fecha', $search['fecha']);
            }
        }
        $current_date = date("Y-m-d");

        $this->db->from('viaje');
        $this->db->where('fecha >= ', $current_date);
        $this->db->where('estado', 0);

        return $this->db->count_all_results();
    }
*/
    public function viaje_pertenece_user($id_viaje, $id_user) {
        $this->db->where('id_viaje', $id_viaje);
        $this->db->where('id_chofer', $id_user);
        $amount_results = $this->db->count_all_results('viaje');
        return ($amount_results == 1);
    }

    //Elimina el viaje, seteando el estado del viaje de 0 a 1
    function eliminar_viaje($id) {
        $this->db->where('id_viaje', $id);
        $this->db->set('estado', 1);
        $this->db->update('viaje');
    }

    //Se crea un array con los datos del viaje y se inserta dicha estructura en la BD
    function restar_reputacion($id_user, $id_pasajero, $id_viaje) {
        $data = array(
            'id_chofer' => $id_user,
            'id_viaje' => $id_viaje,
            'id_pasajero' => $id_pasajero,
            'calificacion' => -1,
            
        );
        $this->db->insert('calificacion_chofer', $data);
    }

    function reanudar_solicitudes_inactivas($id_pasajero, $id_viaje) {
        $this->db->select('fecha,hora_inicio,duracion_horas');
        $this->db->where('id_viaje', $id_viaje);
        $viaje = $this->db->get('viaje');
        $resultado = $viaje->result_array();
        foreach ($resultado as $viaje) {
            $this->get_postulaciones($id_pasajero, $viaje['fecha'], $viaje['duracion_horas'], $viaje['hora_inicio'], 1, 4);
        }
    }

    public function get_postulaciones($id_postulante, $fecha, $hora, $dura, $valor, $valorActual) {
        $this->db->select('fecha,hora_inicio,viaje.id_viaje, user.id_user');
        $this->db->join('viaje', 'viaje.id_viaje = postulacion_viaje.id_viaje', 'inner');
        $this->db->join('user', 'user.id_user = postulacion_viaje.id_user', 'inner');
        $this->db->where('postulacion_viaje.id_user', $id_postulante);
        $this->db->where('id_estado', $valorActual);
        $this->db->where('fecha', $fecha);

        //$this->db->order_by('fecha', 'asc');
        $query = $this->db->get('postulacion_viaje');

        $resultado = $query->result_array();
        $fecha_inicio = $fecha;
        $hora_inicio = $hora;
        $duracion = $dura;

        foreach ($resultado as $id) {
            if ($this->postulacion_valida_antes($id['id_viaje'], $fecha_inicio, $hora_inicio) or $this->postulacion_valida_despues($id['id_viaje'], $fecha_inicio, $hora_inicio, $duracion) or $this->postulacion_valida_entre($id['id_viaje'], $fecha_inicio, $hora_inicio, $duracion)) {
                //$valor= 4;
                $this->setear_postulacion($id['id_viaje'], $id_postulante, $valor);
            }
        }
    }

    public function setear_postulacion($id_viaje, $id_postulante, $valor) {
        $this->db->where('id_viaje', $id_viaje);
        $this->db->where('id_user', $id_postulante);
        $data['id_viaje'] = $id_viaje;
        $data['id_user'] = $id_postulante;
        $data['id_estado'] = $valor;
        $this->db->update('postulacion_viaje', $data);
    }

    function consulta_estado_postulacion($id) {
        $this->db->where('id_viaje', $id);
        $this->db->where('id_estado', 2);
        $this->db->select('id_user');
        $consulta = $this->db->get('postulacion_viaje');
        $resultado = $consulta->result_array();
        foreach ($resultado as $user) {
            $resultado = $this->restar_reputacion($this->session->userdata('id_user'), $user['id_user'], $id);
            $this->reanudar_solicitudes_inactivas($user['id_user'], $id);
        }
        $this->eliminar_postulacion($id, $user['id_user']);
    }

    function eliminar_postulacion($id_viaje) {
        $this->db->where('id_viaje', $id_viaje);
        $this->db->delete('postulacion_viaje');
    }

    //Este método retorno "true" si el User tiene al menos un viaje creado (es al menos chofer en algún viaje)
    function tiene_un_viaje() {
        $id = $this->session->userdata('id_user');
        $this->db->where('id_chofer', $id);
        $amount_results = $this->db->count_all_results('viaje');
        return ($amount_results >= 1);
    }

//ACÁ COMIENZAN FUNCIONES CORRESPONDIENTES A LA HU POSTULARSE COMO ACOMPAÑANTE    
    private function postulacion_valida_antes($id, $fecha_inicio, $hora_inicio) {
        //VALIDO SI EL VIAJE QUE QUIERO POSTULARME SE SUPERPONE CON OTRO VIAJE AL CUAL ME POSTULÉ Y FUE APROBADO.
        $superpone_inicio = $this->db->query("SELECT id_viaje FROM viaje WHERE id_viaje=$id AND estado=0 AND cast(concat('$fecha_inicio',' ','$hora_inicio') as datetime) BETWEEN cast(concat(fecha,' ',hora_inicio) as datetime) AND DATE_ADD(cast(concat(fecha,' ',hora_inicio) as datetime), INTERVAL duracion_horas hour)");
        return $superpone_inicio->num_rows();
    }

    private function postulacion_valida_despues($id, $fecha_inicio, $hora_inicio, $duracion) {
        //VALIDO SI EL VIAJE QUE QUIERO POSTULARME SE SUPERPONE CON OTRO VIAJE AL CUAL ME POSTULÉ Y FUE APROBADO.
        $superpone_fin = $this->db->query("SELECT id_viaje FROM viaje WHERE id_viaje=$id AND estado=0 AND DATE_ADD(cast(concat('$fecha_inicio',' ','$hora_inicio') as datetime),INTERVAL cast('$duracion' as int) hour) AND cast(concat('$fecha_inicio',' ','$hora_inicio') as datetime) BETWEEN cast(concat(fecha,' ',hora_inicio) as datetime) AND DATE_ADD(cast(concat(fecha,' ',hora_inicio) as datetime), INTERVAL duracion_horas hour)");
        return $superpone_fin->num_rows();
    }

    private function postulacion_valida_entre($id, $fecha_inicio, $hora_inicio, $duracion) {
        ///VALIDO SI EL VIAJE QUE QUIERO POSTULARME SE SUPERPONE CON OTRO VIAJE AL CUAL ME POSTULÉ Y FUE APROBADO.
        $superpone_entre = $this->db->query("SELECT id_viaje FROM viaje WHERE id_viaje=$id AND estado=0 AND cast(concat('$fecha_inicio',' ','$hora_inicio') as datetime) <= cast(concat(fecha,' ',hora_inicio) as datetime) AND DATE_ADD(cast(concat('$fecha_inicio',' ','$hora_inicio') as datetime),INTERVAL cast('$duracion' as int) hour) >= DATE_ADD(cast(concat(fecha,' ',hora_inicio) as datetime), INTERVAL duracion_horas hour)");
        return $superpone_entre->num_rows();
    }

    public function superposicion_postulacion($miviaje) {

        $this->db->where('id_user', $miviaje['id_user']);
        $this->db->where('id_estado', 2);
        $this->db->select('id_viaje');
        $this->db->from('postulacion_viaje');
        $consulta = $this->db->get();
        $resultado = $consulta->result_array();
        $fecha_inicio = $miviaje['fecha'];
        $hora_inicio = $miviaje['hora'];
        $duracion = $miviaje['duracion'];
        $cant = 0;
        foreach ($resultado as $id) {
            $resultado = $this->postulacion_valida_antes($id['id_viaje'], $fecha_inicio, $hora_inicio) + $this->postulacion_valida_despues($id['id_viaje'], $fecha_inicio, $hora_inicio, $duracion) + $this->postulacion_valida_entre($id['id_viaje'], $fecha_inicio, $hora_inicio, $duracion);
            $cant = $cant + $resultado;
        }
        return $cant;
    }

    public function superposicion_postulacion_con_mi_viaje($miviaje) {

        $this->db->where('id_chofer', $miviaje['id_user']);
        //$this->db->where('id_estado', 2);
        $this->db->select('id_viaje');
        $this->db->from('viaje');
        $consulta = $this->db->get();
        $resultado = $consulta->result_array();
        $fecha_inicio = $miviaje['fecha'];
        $hora_inicio = $miviaje['hora'];
        $duracion = $miviaje['duracion'];
        $cant = 0;
        foreach ($resultado as $id) {
            $resultado = $this->postulacion_valida_antes($id['id_viaje'], $fecha_inicio, $hora_inicio) + $this->postulacion_valida_despues($id['id_viaje'], $fecha_inicio, $hora_inicio, $duracion) + $this->postulacion_valida_entre($id['id_viaje'], $fecha_inicio, $hora_inicio, $duracion);
            $cant = $cant + $resultado;
        }
        return $cant;
    }

    public function postular($postulacion) {
        $this->db->insert('postulacion_viaje', $postulacion);
    }

    public function ya_postulado($ids) {
        $this->db->where('id_viaje', $ids['id_viaje']);
        $this->db->where('id_user', $ids['id_user']);
        $amount_results = $this->db->count_all_results('postulacion_viaje');
        return ($amount_results == 0);
    }

//ACÁ FINALIZAN LAS FUNCIONES CORRESPONDIENTES A LA HU POSTULARME COMO ACOMPAÑANTE
    
    public function chofer_por_id($viaje_id){
        $this->db->from('viaje');
    $this->db->where('id_viaje', $viaje_id);
    $this->db->select('id_chofer');
    $consulta = $this->db->get();
    $resultado = $consulta->row();
    $this->db->from('user');
    $this->db->where('id_user', $resultado->id_chofer);
    $this->db->select('nombre, apellido');
    $consulta2 = $this->db->get();
    $resultado2 = $consulta2->row();
    return $resultado2;
    
    }
    
    public function viaje_pendiente_chofer() {
        $id = $this->session->userdata('id_user');
        $this->db->where('id_chofer', $id);
        $this->db->where('estado', 0);
        $amount_results = $this->db->count_all_results('viaje');
        if ($amount_results >= 1){
        $this->db->from('viaje');
        $this->db->where('id_chofer', $id);
        $this->db->where('estado', 0);
        $consulta2 = $this->db->get();
        $resultado2 = $consulta2->result_array();
        return $resultado2;
            }
        else
            { return FALSE;
        }
    }
    
    public function viaje_pendiente_pasajero() {
        $id = $this->session->userdata('id_user');
        $this->db->where('id_user', $id);
        $this->db->where('id_estado', 1);
        $amount_results = $this->db->count_all_results('postulacion_viaje');
        return ($amount_results >= 1);
    }
    
    public function viaje_aprobado_pasajero() {
        $id = $this->session->userdata('id_user');
        $this->db->where('id_user', $id);
        $this->db->where('id_estado', 2);
        $amount_results = $this->db->count_all_results('postulacion_viaje');
        return ($amount_results >= 1);
    }
    public function consultar_viaje($id_viaje) { 
        $this->db->where('id_viaje', $id_viaje);
        $consulta = $this->db->get('viaje');
        $resultado = $consulta->row();
        return $resultado;
    }
    public function estado_viaje($id_viaje){
        $this->db->where('id_viaje',$id_viaje);
        $this->db->where('id_estado',1);
        $this->db->or_where('id_estado',2);
        $amount_results = $this->db->count_all_results('postulacion_viaje');
        return ($amount_results >= 1);
    }
    public function actualizar_viaje($viaje,$id_viaje){
        $this->db->where('id_viaje',$id_viaje);
        $this->db->update('viaje',$viaje);
        $this->db->trans_complete();
        return ($this->db->trans_status() === TRUE);
    }
}
