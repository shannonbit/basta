<?php
class Usuario extends CI_Model {
    
    public function __construct () {
        
         parent::__construct();
         
         $this->load->database();
    
    }
    
    function get_users(){
        
        $consulta = $this->db->query('SELECT usuario_id, nombre_usuario FROM usuarios');
        
        foreach ($consulta->result_array() as $row) {

            $results[$row['usuario_id']] = $row;
        }
        
        return $results;
    
    }
    
    function get_users_by_id($ids){
        
        $this->db->select('usuario_id, nombre_usuario');
        
        $this->db->from('usuarios');
        
        foreach($ids as $id){
            
            if(!isset($id['usr_id_2'])){
                
                $this->db->or_where('usuario_id', $id['remitente_usr_id']);
                
            } else {
             
                $this->db->or_where('usuario_id', $id['usr_id_2']);
                
            }
            
        }
        
        $consulta = $this->db->get();
        
        if($consulta->num_rows() > 0){
            
            foreach ($consulta->result_array() as $row) {

                $results[$row['usuario_id']] = $row;
            
            }
            
            $results['mensaje'] = 'ok';
        
        } else {
            
            $results['mensaje'] = 'vacio';
            
        }
        
            return $results;
        
    }
    
    function get_user($nombre_usuario){
        
        $this->db->like('nombre_usuario', $nombre_usuario);
        
        $this->db->select('usuario_id, nombre_usuario');
        
        $consulta = $this->db->get('usuarios');
        
        if($consulta->num_rows() > 0){
            
            foreach ($consulta->result_array() as $row) {

               $results[$row['usuario_id']] = $row;
            
            }
            
            $results['mensaje'] = 'ok';
        
        } else {
            
            $results['mensaje'] = 'vacio';
            
        }
        
            return $results;
            
    }
    
    public function auntenticar($usr, $passwd){
        
        $this->db->select('usuario_id');
        
        $this->db->from('usuarios');
        
        $this->db->where('nombre_usuario', $usr);
        
        $this->db->where('passwd', md5($passwd));
        
        $consulta = $this->db->get();
        
        if ($consulta->num_rows() > 0) {
            
            $logged['mensaje'] = 'ok';
            
            foreach ($consulta->result_array() as $row){
                
                $logged['id_usuario'] = $row['usuario_id'];
                
            }
            
            //echo 'You\'r not logged';
                
        } else {
            
            $logged['mensaje'] = 'vacio';
            
            //echo 'You\'r logged';
            
        }
        
        //$logged = $this->db->last_query();
        
        return $logged;
        
    }
    
    public function registrar_usuario_model($usr, $passwd){
        
        $this->db->select('nombre_usuario'); 
        
        $this->db->where('nombre_usuario', $usr);
        
        $consulta = $this->db->get('usuarios');
        
        if($consulta->num_rows() < 1){
            
            $sql = 'INSERT INTO usuarios (nombre_usuario, passwd) VALUES (?, ?)';
            
            $consulta = $this->db->query($sql, array($usr, md5($passwd)));
            
            $registrado['mensaje'] = 'ok';
            
            $this->db->select_max('usuario_id');
            
            $consulta = $this->db->get('usuarios');
            
            foreach($consulta->result_array() as $row){
                
                $registrado['id_usuario'] = $row['usuario_id'];
                
            }
            
        } else {
            
            $registrado['mensaje'] = 'usuario_existente';
            
        }
        
        return $registrado;
        
    }

}
?>
