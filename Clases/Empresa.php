<?php 
    class Empresa{
        private $idEmpresa;
        private $nombre;
        private $direccion;
        private $mensajeOperacion;
        //GETS Y SETS
        public function setIdEmpresa($valor){
            $this->idEmpresa=$valor;
        }
        public function getIdEmpresa(){
            return $this->idEmpresa;
        } 
        public function setNombre($valor){
            $this->nombre=$valor;
        }
        public function getNombre(){
            return $this->nombre;
        } 
        public function setDireccion($valor){
            $this->direccion=$valor;
        }
        public function getDireccion(){
            return $this->direccion;
        } 
        //Mensaje para devolver error de la BD
        public function getMensajeoperacion(){
            return $this->mensajeOperacion ;
        }
        public function setMensajeoperacion($mensajeOperacion){
            $this->mensajeOperacion=$mensajeOperacion;
        }
        //Inicio de constructor vacio
        public function __construct(){
            $this->idEmpresa= null;
            $this->nombre= null;
            $this->direccion= null;
        }
        //Cargar valores de un objeto
        public function cargar($id,$nom,$dir){		
            $this->setIdEmpresa($id);
            $this->setNombre($nom);
            $this->setDireccion($dir);
        }
        public function __toString(){
            return "    Id Empresa: ".$this->getIdEmpresa()."\n".
                   "    Nombre: ".$this->getNombre()."\n".
                   "    Direccion: ".$this->getDireccion();
        }
        //Insertar empresa en la BD
        public function insertar(){
            $base=new BaseDatos();
            $resp= false;
            $consultaInsertar = "INSERT INTO empresa(enombre, edireccion) 
                VALUES ('".$this->getNombre()."','".$this->getDireccion()."')";
            if($base->Iniciar()){//
                if ($id = $base -> devuelveIDInsercion ($consultaInsertar)){
                    $this -> setIdEmpresa($id);
                    $resp = true;
                }else{
                    $this -> setMensajeOperacion($base->getError());
                }
            }else{
                $this->setMensajeOperacion($base->getError());  
            }
            return $resp;
        }
        //Eliminar empresa en la BD
        public function eliminar(){
            $base=new BaseDatos();
            $resp=false;
            if($base->Iniciar()){
                $consultaBorra="DELETE FROM empresa WHERE idempresa=".$this->getIdEmpresa();
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
        //Modificar empresa en la BD
        public function modificar(){
            $resp =false; 
            $base=new BaseDatos();
            $consultaModifica="UPDATE empresa SET enombre='".$this->getNombre()."',edireccion='".$this->getDireccion().
                    "' WHERE idempresa=". $this->getIdEmpresa();
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
        //Listar registros de tabla empresas 
        public function listar($restriccion=""){
            $arregloEmpresa = null;
            $base=new BaseDatos();
            $consultaEmpresa="Select * from empresa ";
            if($restriccion!=""){
                $consultaEmpresa=$consultaEmpresa.' where '.$restriccion;
            }
            $consultaEmpresa.=" order by idempresa ";
            if($base->Iniciar()){
                if($base->Ejecutar($consultaEmpresa)){			
                    $arregloEmpresa= array();
                    while($row2=$base->Registro()){
                        $IdEmp=$row2['idempresa'];
                        $NomEmp=$row2['enombre'];
                        $DirEmp=$row2['edireccion'];
                        $empre=new Empresa();
                        $empre->cargar($IdEmp,$NomEmp,$DirEmp);
                        array_push($arregloEmpresa,$empre);
                    }
                }else{
                    $this->setMensajeOperacion($base->getError());
                }
             }	else {
                     $this->setMensajeOperacion($base->getError());
                 
             }	
             return $arregloEmpresa;
        }
        //Buscar empresa por ID
        public function buscar($idEmpresa){
		    $base = new BaseDatos();
		    $consultaEmpresa = "Select * from empresa where idempresa=".$idEmpresa;
		    $resp = false;
		    if ($base -> Iniciar()){
		        if ($base->Ejecutar($consultaEmpresa)){
		        	if ($row2 = $base -> Registro()){	
		        		$NomEmp=$row2['enombre'];
		        		$DirEmp=$row2['edireccion'];
                        $this->cargar($idEmpresa,$NomEmp,$DirEmp);
		        		$resp = true;
		        	}	
		        }else{
		        $this -> setMensajeOperacion($base->getError());
		        }
		    }else{
	         	$this -> setMensajeOperacion($base->getError());	
	        }	
		return $resp;
	    }
    }
?>