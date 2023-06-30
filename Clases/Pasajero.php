<?php
    class Pasajero{
        private $dni;
        private $nombre;
        private $apellido;
        private $numeroTel;
        private $objetoViaje;
        private $mensajeOperacion;
        //GETS Y SETS 
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
        public function setDni($valor){
            $this->dni=$valor;
        }
        public function getDni(){
            return $this->dni;
        }
        public function setNumeroTelefono($valor){
            $this->numeroTel=$valor;
        }
        public function getNumeroTelefono(){
            return $this->numeroTel;
        }
        public function setObjetoViaje($valor){
            $this->objetoViaje=$valor;
        }
        public function getObjetoViaje(){
            return $this->objetoViaje;
        }
        //Mensaje para devolver error de la BD
        public function getMensajeOperacion(){
            return $this->mensajeOperacion ;
        }
        public function setMensajeOperacion($mensajeOperacion){
            $this->mensajeOperacion=$mensajeOperacion;
        }
        public function __construct(){
            $this->nombre= "";
            $this->apellido="";
            $this->dni= "";
            $this->numeroTel= "";
            $this->objetoViaje = new Viaje();
        }
        //Cargar valores de un objeto
        public function Cargar($dni,$nom,$ape,$tel,$objV){
            $this->setNombre($nom);
            $this->setApellido($ape);
            $this->setDni($dni);
            $this->setNumeroTelefono($tel);
            $this->setObjetoViaje($objV);
        } 
        public function __toString(){
            return "     Nombre: ".$this->getNombre().
            "\n     Apellido: ".$this->getApellido().
            "\n     Dni: ".$this->getDni().
            "\n     Numero Telefono: ".$this->getNumeroTelefono().
            "\n     ID Viaje: ".$this->getObjetoViaje()->getIdViaje();
        }
        //Insertar pasajero en la BD
        public function insertar(){
            $base=new BaseDatos();
            $resp= false;
            $consultaInsertar="INSERT INTO pasajero(pdocumento, pnombre, papellido,ptelefono,idviaje) 
                    VALUES (".$this->getDni().",'".$this->getNombre()."','".$this->getApellido()."','".$this->getNumeroTelefono()."','".$this->getObjetoViaje()->getIdViaje()."')";
            
            if($base->Iniciar()){
                if($base->Ejecutar($consultaInsertar)){
                    $resp=  true;
                }else{
                    $this->setMensajeOperacion($base->getError());
                }
            }else{
                $this->setMensajeOperacion($base->getError());  
            }
            return $resp;
        }
        //Eliminar pasajero en la BD
        public function eliminar(){
            $base=new BaseDatos();
            $resp=false;
            if($base->Iniciar()){
                $consultaBorra="DELETE FROM pasajero WHERE pdocumento=".$this->getDni();
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
         //Modificar pasajero en la BD
        public function modificar(){
            $resp =false; 
            $base=new BaseDatos();
            $consultaModifica="UPDATE pasajero SET pdocumento='".$this->getDni()."',pnombre='".$this->getNombre().
            "',papellido='".$this->getApellido()."',ptelefono='".$this->getNumeroTelefono()."',idviaje='".$this->getObjetoViaje()->getIdViaje().
            "' WHERE pdocumento=". $this->getDni();
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
        //Listar registros de tabla pasajeros
        public function listar($condicion=""){
            $arregloPasajero = null;
            $base=new BaseDatos();
            $consultaPasajero="Select * from pasajero ";
            if ($condicion!=""){
                $consultaPasajero=$consultaPasajero.' where '.$condicion;
            }
            $consultaPasajero.=" order by pdocumento ";
            if($base->Iniciar()){
                if($base->Ejecutar($consultaPasajero)){				
                    $arregloPasajero= array();
                    while($row2=$base->Registro()){
                        $DocPa=$row2['pdocumento'];
                        $NomPa=$row2['pnombre'];
                        $ApePa=$row2['papellido'];
                        $TelPa=$row2['ptelefono'];
                        $objViaje  = new Viaje();
					    $objViaje -> buscar($row2['idviaje']);
                        $pasa=new Pasajero();
                        $pasa->cargar($DocPa,$NomPa,$ApePa,$TelPa,$objViaje);
                        array_push($arregloPasajero,$pasa);
                    }
                }else{
                $this->setMensajeOperacion($base->getError());
                }
            }else {
            $this->setMensajeOperacion($base->getError());
            }	
        return $arregloPasajero;
        }
        //Buscar pasajero por ID
        public function buscar($dni){
            $base=new BaseDatos();
            $consultaPasajero ="Select * from pasajero where pdocumento=".$dni;
            $resp= false;
            if($base->Iniciar()){
                if($base->Ejecutar($consultaPasajero)){
                    if($row2=$base->Registro()){					
                        $DocPa=$dni;
                        $NomPa=$row2['pnombre'];
                        $ApePa=$row2['papellido'];
                        $TelPa=$row2['ptelefono'];
                        $objViaje = new Viaje();
                        $objViaje -> buscar ($row2['idviaje']);
                        $this->cargar($DocPa,$NomPa,$ApePa,$TelPa,$objViaje);
                        $resp = true;
                    }				
                }else{
                $this->setMensajeOperacion($base->getError());
                }
            }else{
            $this->setMensajeOperacion($base->getError());
            }		
        return $resp;
        }
    }
?>