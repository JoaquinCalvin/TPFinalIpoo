<?php
    class Viaje{
        private $idViaje;
        private $destino;
        private $cantMaxViajes;
        private $objResponsable;
        private $importe;
        private $objEmpresa ;
        private $mensajeOperacion;
        
        //Mensaje para devolver error de la BD
        public function getMensajeOperacion(){
            return $this->mensajeOperacion ;
        }
        public function setMensajeOperacion($mensajeOperacion){
            $this->mensajeOperacion=$mensajeOperacion;
        }
        //GETS Y SETS
        public function getImporte(){
            return $this->importe;    
        }
        public function setImporte($imp){
            $this->importe=$imp;    
        }
        public function getIdViaje(){
            return $this->idViaje;    
        }
        public function getDestino(){
            return $this->destino;    
        }
        public function getCantMaxPasajeros(){
            return $this->cantMaxViajes;    
        }
        public function setIdViaje($codVia){
            $this->idViaje = $codVia;
        }
        public function setDestino($dest){
            $this->destino = $dest;
        }
        public function setCantMaxPasajeros($cantM){
            $this->cantMaxViajes = $cantM;
        }
        public function setObjResponsable($valor){
            $this->objResponsable = $valor;
        }
        public function getObjResponsable(){
            return $this->objResponsable;
        }
        public function setObjEmpresa($valor){
            $this->objEmpresa = $valor;
        }
        public function getObjEmpresa(){
            return $this->objEmpresa;
        }
        public function __toString(){
            $mensaje="  Codigo Viaje: ".$this->getIdViaje()."\n".
            "  Destino Viaje: ".$this->getDestino()."\n".
            "  Cantidad de maxima de pasajeros: ".$this->getCantMaxPasajeros()."\n".
            "  Importe: ".$this->getImporte()."\n".
            "  Id Empresa: ".$this->getObjEmpresa()->getIdEmpresa()."\n".
            "  Numero Responsable: ".$this->getObjResponsable()->getNumeroEmpleado()."\n";
            return $mensaje;
        }
        public function __construct(){
            $this->idViaje = "";
            $this->destino = "";
            $this->cantMaxViajes = "";
            $this->objResponsable= new Responsable();
            $this->importe= "";
            $this->objEmpresa= new Empresa();
        }
        //Cargar valores de un objeto
        public function cargar($idV,$dest,$cantPas,$objResp,$objEmp,$imp){
            $this->setIdViaje($idV);
            $this->setDestino($dest);
            $this->setCantMaxPasajeros($cantPas);
            $this->setObjResponsable($objResp);
            $this->setObjEmpresa($objEmp);
            $this->setImporte($imp);
        }
        //Insertar Viaje en la BD
        public function insertar(){
            $base=new BaseDatos();
            $resp= false;
            $consultaInsertar="INSERT INTO viaje( vdestino, vcantmaxpasajeros,idempresa,rnumeroempleado,vimporte) 
                    VALUES ('".$this->getDestino()."','".$this->getCantMaxPasajeros()."','".
                    $this->getObjEmpresa()->getIdempresa()."','".$this->getObjResponsable()->getNumeroEmpleado()."','".$this->getImporte()."')";
            if($base->Iniciar()){
                if($id = $base -> devuelveIDInsercion ($consultaInsertar)){
                    $this->setIdViaje($id);
                    $resp =  true;
                }else{
                    $this->setMensajeOperacion($base->getError());
                }
            }else{
                $this->setMensajeOperacion($base->getError());  
            }
            return $resp;
        }
        //Eliminar viaje de la BD
        public function eliminar(){
            $base=new BaseDatos();
            $resp=false;
            if($base->Iniciar()){
                $consultaBorra="DELETE FROM viaje WHERE idviaje=".$this->getIdViaje();
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
         //Modificar viaje en la BD
         public function modificar(){
            $resp =false; 
            $base=new BaseDatos();
            $consultaModifica="UPDATE viaje SET idviaje='".$this->getIdViaje()."',vdestino='".$this->getDestino().
            "',vcantmaxpasajeros='".$this->getCantMaxPasajeros()."',idempresa='".$this->getObjEmpresa()->getIdEmpresa().
            "',rnumeroempleado='".$this->getObjResponsable()->getNumeroEmpleado(). "',vimporte='".$this->getImporte().
            "' WHERE idviaje=". $this->getIdViaje();
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
        //Listar registros de tabla viajes
        public function listar($condicion=""){
            $arregloViaje = null;
            $base=new BaseDatos();
            $consultaViaje="Select * from viaje ";
            if ($condicion!=""){
                $consultaViaje=$consultaViaje.' where '.$condicion;
            }
            $consultaViaje.=" order by idviaje";
            if($base->Iniciar()){
                if($base->Ejecutar($consultaViaje)){				
                    $arregloViaje= array();
                    while($row2=$base->Registro()){
                        $IdVi=$row2['idviaje'];
                        $DesVi=$row2['vdestino'];
                        $CantPasVi=$row2['vcantmaxpasajeros'];
                        $ImpVi=$row2['vimporte'];
                        $ObjResponsable=new Responsable();
                        $ObjResponsable->buscar($row2['rnumeroempleado']);
                        $ObjEmpresa=new Empresa();
                        $ObjEmpresa->buscar($row2['idempresa']);
                        $via=new Viaje();
                        $via->cargar($IdVi,$DesVi,$CantPasVi,$ObjResponsable,$ObjEmpresa,$ImpVi);
                        array_push($arregloViaje,$via);
                    }
                }else{
                    $this->setMensajeOperacion($base->getError());
                }
            }else {
                $this->setMensajeOperacion($base->getError());
            }	
            return $arregloViaje;
        }
        //Buscar viaje en la BD
        public function buscar ($idViaje){
            $base = new BaseDatos();
            $consultaEmpresa = "Select * from viaje where idviaje=".$idViaje;
            $resp = false;
            if ($base -> Iniciar()){
                if ($base -> Ejecutar ($consultaEmpresa)){
                    if ($row2 = $base -> Registro()){					
                        $DesVi=$row2['vdestino'];
                        $CantPasVi=$row2['vcantmaxpasajeros'];
                        $ImpVi=$row2['vimporte'];
                        $ObjetoEmpresa= new Empresa();
                        $ObjetoEmpresa->buscar ($row2['idempresa']);
                        $ObjResponsable = new Responsable();
					    $ObjResponsable -> buscar ($row2['rnumeroempleado']);
                        $this->cargar($idViaje,$DesVi,$CantPasVi,$ObjResponsable,$ObjetoEmpresa,$ImpVi);
                        $resp = true;
                    }
                }else{
                $this -> setMensajeOperacion ($base->getError());
                }
            }else{
            $this -> setMensajeOperacion ($base->getError());	
            }		
            return $resp;
            }
    }

?>