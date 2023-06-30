<?php 
    class Responsable{
        private $numeroEmpleado;
        private $numeroLicencia;
        private $nombre;
        private $apellido;
        private $mensajeOperacion;
        
        //Mensaje para devolver error de la BD
        public function getMensajeOperacion(){
            return $this->mensajeOperacion ;
        }
        public function setMensajeOperacion($mensajeOperacion){
            $this->mensajeOperacion=$mensajeOperacion;
        }
        //  GETS Y SETS 
        public function setNumeroEmpleado($valor){
            $this->numeroEmpleado=$valor;
        }
        public function getNumeroEmpleado(){
            return $this->numeroEmpleado;
        }
        public function setNumeroLicencia($valor){
            $this->numeroLicencia=$valor;
        }
        public function getNumeroLicencia(){
            return $this->numeroLicencia;
        }
        public function setNombre($valor){
            $this->nombre=$valor;
        }
        public function getNombre(){
            return $this->nombre;
        }
        public function setApellido($valor){
            $this->apellido=$valor;
        }
        public function getApellido(){
            return $this->apellido;
        }

        public function __construct(){
            $this->numeroEmpleado= "";
            $this->numeroLicencia= "";
            $this->nombre= "";
            $this->apellido= "";
        }
        //Cargar valores de un objeto
        public function cargar($emp,$lic,$nomb,$ape){
            $this->setNumeroEmpleado($emp);
            $this->setNumeroLicencia($lic);
            $this->setNombre($nomb);
            $this->setApellido($ape);
        }
        public function __toString(){
            return "     Numero Empleado: ".$this->getNumeroEmpleado().
            "\n     Numero Licencia: ".$this->getNumeroLicencia().
            "\n     Nombre: ".$this->getNombre().
            "\n     Apellido: ".$this->getApellido()."\n";
        }
        //Insertar responsable en la BD
        public function insertar(){
            $base=new BaseDatos();
            $resp= false;
            $consultaInsertar="INSERT INTO responsable (rnumerolicencia,rnombre,rapellido) 
                    VALUES ('".$this->getNumeroLicencia()."','".$this->getNombre()."','".$this->getApellido()."')";
            if($base->Iniciar()){
                if($id = $base -> devuelveIDInsercion($consultaInsertar)){
                    $this->setNumeroEmpleado($id);
                    $resp=  true;
                }else{
                    $this->setMensajeOperacion($base->getError());
                }
            }else{
                $this->setMensajeOperacion($base->getError());  
            }
            return $resp;
        }
        //Eliminar un responsable en la BD
        public function eliminar(){
            $base=new BaseDatos();
            $resp=false;
            if($base->Iniciar()){
                $consultaBorra="DELETE FROM responsable WHERE rnumeroempleado=".$this->getNumeroEmpleado();
                if($base->Ejecutar($consultaBorra)){
                    $resp=  true;
                }else{
                    $this->setMensajeOperacion($base->getError());
                }
            }else{
                $this->setMensajeOperacion($base->getError());
            }
            return $resp; 
        }
         //Modificar responsable en la BD
         public function modificar(){
            $resp =false; 
            $base=new BaseDatos();
            $consultaModifica="UPDATE responsable SET rnumeroempleado='".$this->getNumeroEmpleado()."',rnumerolicencia='".$this->getNumeroLicencia().
            "',rnombre='".$this->getNombre()."',rapellido='".$this->getApellido().
            "' WHERE rnumeroempleado=". $this->getNumeroEmpleado();
            if($base->Iniciar()){
                if($base->Ejecutar($consultaModifica)){
                    $resp=  true;
                }else{
                    $this->setMensajeOperacion($base->getError());
                }
            }else{
                $this->setMensajeOperacion($base->getError());
            }
            return $resp;
        }
        //Listar registros de tabla Responsable 
        public function listar($restriccion=""){
            $arregloResponsable = null;
            $base=new BaseDatos();
            $consultaResponsable="Select * from responsable ";
            if ($restriccion!=""){
                $consultaResponsable=$consultaResponsable.' where '.$restriccion;
            }
            $consultaResponsable.=" order by rnombre ";
            if($base->Iniciar()){
                if($base->Ejecutar($consultaResponsable)){				
                    $arregloResponsable= array();
                    while($row2=$base->Registro()){
                        $NumRes=$row2['rnumeroempleado'];
                        $LicRes=$row2['rnumerolicencia'];
                        $NomRes=$row2['rnombre'];
                        $ApelRes=$row2['rapellido'];
                        $resp=new Responsable();
                        $resp->cargar($NumRes,$LicRes,$NomRes,$ApelRes);
                        array_push($arregloResponsable,$resp);
                    }
                }else{
                    $this->setMensajeOperacion($base->getError());
                }
            }else {
                $this->setMensajeOperacion($base->getError());
            }	
            return $arregloResponsable;
        }
        //Buscar Responsable por ID
        public function buscar($nroEmpleado){
		    $base = new BaseDatos();
		    $consultaResponsable = "Select * from responsable where rnumeroempleado=".$nroEmpleado;
		    $resp = false;
		    if ($base->Iniciar()){
		    	if ($base->Ejecutar($consultaResponsable)){
		    		if ($row2=$base->Registro()){					
		    			$LicRes=$row2['rnumerolicencia'];
		    			$NomRes=$row2['rnombre'];
                        $ApelRes=$row2['rapellido'];
                        $this->cargar($nroEmpleado,$LicRes,$NomRes,$ApelRes);
		    			$resp = true;
		    		}				
		     	}else{
		     	$this->setMensajeOperacion($base->getError());
		    	}
		    }else {
		    $this->setMensajeOperacion($base->getError());
		    }		
		    return $resp;
        }
    }
    
?>