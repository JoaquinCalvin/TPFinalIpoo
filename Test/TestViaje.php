<?php
  include_once ("../Clases/BaseDatos.php");
  include_once '../Clases/Pasajero.php';
  include_once '../Clases/Viaje.php';
  include_once '../Clases/Empresa.php';
  include_once '../Clases/Responsable.php';

  //Funciones de opciones 
  function opcionesEmpresa(){
    echo"---------------------------------------------------------------\n";
    echo "   ### Ingrese 1 si quiere INGRESAR una empresa\n";
    echo "   ### Ingrese 2 si quiere MODIFICAR una empresa\n";
    echo "   ### Ingrese 3 si quiere ELIMINAR una empresa\n";
    echo "   ### Ingrese 4 si quiere LISTAR empresas\n";
    echo "   ### Ingrese 5 si quiere TRABAJAR SOBRE una empresa\n";
    echo "   ### Ingrese cualquier numero distinto para salir \n";
    $opcEmp = trim(fgets(STDIN));
    echo"---------------------------------------------------------------\n";
    return $opcEmp;
  }
  function opcionesGenerales(){
    echo"---------------------------------------------------------------\n";
    echo "   ### Ingrese 1 si quiere TRABAJAR SOBRE LOS VIAJES\n";
    echo "   ### Ingrese 2 si quiere TRABAJAR SOBRE LOS PASAJEROS\n";
    echo "   ### Ingrese 3 si quiere TRABAJAR SOBRE LOS RESPONSABLES\n";
    echo "   ### Ingrese 4 para volver al menu de empresas\n";
    echo "   ### Ingrese cualquier numero distinto para salir de la APP \n";
    echo"---------------------------------------------------------------\n";
    $menu = trim(fgets(STDIN));
    return $menu;
  }
  function opcionesViaje(){
    echo"---------------------------------------------------------------\n";
    echo "   ### Ingrese 1 si quiere INGRESAR un viaje\n";
    echo "   ### Ingrese 2 si quiere MODIFICAR un viaje\n";
    echo "   ### Ingrese 3 si quiere ELIMINAR un viaje\n";
    echo "   ### Ingrese 4 si quiere LISTAR viajes\n";
    echo "   ### Ingrese cualquier numero distinto para volver atras\n";
    echo"---------------------------------------------------------------\n";
    $opcVia = trim(fgets(STDIN));
    
    return $opcVia;
  }
  function opcionesResponsable(){
    echo"---------------------------------------------------------------\n";
    echo "   ### Ingrese 1 si quiere INGRESAR un Responsable\n";
    echo "   ### Ingrese 2 si quiere MODIFICAR un Responsable\n";
    echo "   ### Ingrese 3 si quiere ELIMINAR un Responsable\n";
    echo "   ### Ingrese 4 si quiere LISTAR Responsables\n";
    echo "   ### Ingrese cualquier numero distinto para volver atras\n";
    echo"---------------------------------------------------------------\n";
    $opcResp = trim(fgets(STDIN));
    return $opcResp;
  }
  function opcionesPasajeros(){
    echo"---------------------------------------------------------------\n";
    echo "   ### Ingrese 1 si quiere INGRESAR un Pasajeros\n";
    echo "   ### Ingrese 2 si quiere MODIFICAR un Pasajeros\n";
    echo "   ### Ingrese 3 si quiere ELIMINAR un Pasajeros\n";
    echo "   ### Ingrese 4 si quiere LISTAR Pasajeros\n";
    echo "   ### Ingrese cualquier numero distinto para volver atras\n";
    echo"---------------------------------------------------------------\n";
    $opcPas = trim(fgets(STDIN));
    return $opcPas;
  } 
  
  //Funciones para Empresa !.. Revisar que ande todo bien(Chekeado)
  function ingresarEmpresa(){
    $empresa=new Empresa();
    echo "Ingrese el Nombre de la empresa ";
    $nombreE=trim(fgets(STDIN));
    echo "Ingrese la direccion de la empresa ";
    $direccionE=trim(fgets(STDIN));
    $empresa->setNombre($nombreE);
    $empresa->setDireccion($direccionE);
    $empresa->insertar();
  }
  function eliminarEmpresa(){
    $emp=new Empresa();
    echo "Ingrese el ID de la empresa que desea eliminar\n";
    $idEli = trim(fgets(STDIN));
    $emp->setIdEmpresa($idEli); 
    $existeEmp=existeEmpresa($idEli);
    if($existeEmp){
      $viaje=new Viaje;
      $condicion= "idempresa =".$idEli;
      $arregloViajes= $viaje->listar($condicion);
      if($arregloViajes!=[]){
        foreach($arregloViajes as $v){
          $pas=new Pasajero();
          $arregloPasajero=$pas->listar();
          if($arregloPasajero!=[]){
            foreach ($arregloPasajero as $p){
              $p->buscar($p->getDni());
              if($p->getObjetoViaje()->getIdViaje()==$v->getIdViaje()){
                $p->eliminar();
              }
            } 
          }
          $v->eliminar();
        }
        echo "Elimino los Viajes de la empresa juntos a los pasajeros de cada viaje";
      }
      $emp->eliminar();
      echo "Empresa eliminada correctamente\n";
    }else{
      echo "ID ingresado no existente \n";
    }
  }
  function modificarEmpresa(){
    $emp= new Empresa();
    echo "Ingrese el ID de la empresa que desea modificar\n";
    $idMod = trim(fgets(STDIN));
    $emp->setIdEmpresa($idMod);
    $existeEmp=existeEmpresa($idMod);
    if($existeEmp){
      echo "Ingrese el nombre\n";
      $nombreE=trim(fgets(STDIN));
      echo "Ingrese la direccion de la empresa\n";
      $direccionE=trim(fgets(STDIN));
      $emp->setNombre($nombreE);
      $emp->setDireccion($direccionE);
      $emp->modificar();
      echo "Empresa Modificada correctamente\n";
    }else{
      echo "ID ingresado no existente\n";
    }
  }
  function listarEmpresas(){
    $emp=new Empresa();
    $arregloEmp=$emp->listar();
    $cantEmp=1;
    foreach ($arregloEmp as $e){
      echo "Empresa ".$cantEmp."\n";
      echo $e."\n";
      $cantEmp++;
    }
  }
  function devolverEmpresa($idEmp){
    $emp= new Empresa();
    $emp->setIdEmpresa($idEmp);
    $arregloEmp=$emp->listar();
    foreach($arregloEmp as $e){
      if($e->getIdEmpresa()==$idEmp){
        $emp=$e;
      }
    }
    return $emp;
  }
  function existeEmpresa($idEmp){
    $emp= new Empresa();
    $emp->setIdEmpresa($idEmp);
    $arregloEmp=$emp->listar();
    $existencia=false;
    foreach($arregloEmp as $e){
      if($e->getIdEmpresa()==$idEmp){
        $existencia=true;
      }
    }
    return $existencia;
  }

  // Funciones para Viaje. Revisar que ande todo bien(Chekeado)
  function existeViaje($idVia,$idEmp){
    $via= new Viaje();
    $via->setIdViaje($idVia);
    $arregloVia=$via->listar();
    $existencia=false;
    foreach($arregloVia as $v){
      $v->buscar($v->getIdViaje());
      if($v->getObjEmpresa()->getIdEmpresa()==$idEmp && $v->getIdViaje()==$idVia){
        $existencia=true;
      }
    }
    return $existencia;
  }
  function devolverViaje($id){
    $via= new Viaje();
    $via->setIdViaje($id);
    $arregloVia=$via->listar();
    foreach($arregloVia as $v){
      if($v->getIdViaje()==$id){
        $via=$v;
      }
    }
    return $via;
  }
  function ingresarViaje($idEmp){
    echo "Ingrese el Numero del responsable ";
    $numeroRes=trim(FGETS(STDIN));
    if(existeResponsable($numeroRes)){
      $viaje=new Viaje();
      echo "Ingrese el Destino del viaje ";
      $destinoV=trim(fgets(STDIN));
      echo "Ingrese la Cantidad Maxima de pasajeros del viaje ";
      $cantMaxPasajerosV=trim(fgets(STDIN));
      $empresaV=devolverEmpresa($idEmp);
      echo "Ingrese el Importe del viaje ";
      $importeV=trim(fgets(STDIN));
      $responsableV=devolverResponsable($numeroRes);

      $viaje->setDestino($destinoV);
      $viaje->setCantMaxPasajeros($cantMaxPasajerosV);
      $viaje->setObjResponsable($responsableV);
      $viaje->setObjEmpresa($empresaV);
      $viaje->setImporte($importeV);
      $viaje->insertar();
    }else{
      echo "El Responsable ingresado no existe\n";
      echo "Cargue un responsable o igrese el codigo correcto\n";
    }
  }
  function modificarViaje($numEmpresa){
    $via= new Viaje();
    echo "Ingrese el ID del viaje que desea modificar\n";
    $idMod = trim(fgets(STDIN));
    $via->setIdViaje($idMod);
    $existeVia=existeViaje($idMod,$numEmpresa);
    if($existeVia){
      echo "Ingrese el destino\n";
      $destinoV=trim(fgets(STDIN));
      echo "Ingrese la cantidad de pasajeros maxima\n";
      $cantPasV=trim(fgets(STDIN));
      echo "Ingrese el importe del viaje\n";
      $importeV=trim(fgets(STDIN));
      do{
        echo "Ingrese el numero del representante ";
        $numeroRepreV=trim(fgets(STDIN));
        $esta=true;
        if(existeResponsable($numeroRepreV)){
          $esta=false;
          $responsableV=devolverResponsable($numeroRepreV);
        }else{
          echo "No existe el representante, ingrese un numero correcto";
        }
      }while($esta);
      $empresaV=devolverEmpresa($numEmpresa);
      $via->setDestino($destinoV);
      $via->setCantMaxPasajeros($cantPasV);
      $via->setObjEmpresa($empresaV);
      $via->setObjResponsable($responsableV);
      $via->setImporte($importeV);
      $via->modificar();
      echo "Viaje Modificado correctamente\n";
    }else{
      echo "ID ingresado no existente\n";
    }
  }
  function listarViajes($idEmp){
    $via=new Viaje();
    $arregloVia=$via->listar();
    $cantVia=1;
    foreach ($arregloVia as $v){
      $v->buscar($v->getIdViaje());
      if($v->getObjEmpresa()->getIdEmpresa()==$idEmp){
        echo "Viaje ".$cantVia."\n";
        echo $v."\n";
        $cantVia++;
      }
    }
  }
  function eliminarViaje($numEmp){
    $via=new Viaje();
    echo "Ingrese el numero de viaje que desea eliminar ";
    $idViaje=trim(fgets(STDIN));
    if(existeViaje($idViaje,$numEmp)){
      $viajeEliminar=devolverViaje($idViaje);
      $pas=new Pasajero();
      $arregloPasajero=$pas->listar();
      if($arregloPasajero!=[]){
        foreach ($arregloPasajero as $p){
          $p->buscar($p->getDni());
          if($p->getObjetoViaje()->getIdViaje()==$idViaje){
            $p->eliminar();
          }
        }
      }
      $viajeEliminar->eliminar();
      echo "Viaje eliminado correctamente\n";
    }else{
      echo "\nViaje no existente\n";
    }

  }
 
  //Funciones para Responsable. Revisar que ande todo bien(Chekeado)
  function existeResponsable($numR){
    $res= new Responsable();
    $res->setNumeroEmpleado($numR);
    $arregloRes=$res->listar();
    $existencia=false;
    foreach($arregloRes as $r){
      if($r->getNumeroEmpleado()==$numR){
        $existencia=true;
      }
    }
    return $existencia;
  }
  function devolverResponsable($numR){
    $res= new Responsable();
    $res->setNumeroEmpleado($numR);
    $arregloRes=$res->listar();
    foreach($arregloRes as $r){
      if($r->getNumeroEmpleado()==$numR){
        $res=$r;
      }
    }
    return $res;
  }
  function ingresarResponsable(){
    $respon= new Responsable;
    echo "Ingresar numero Licencia ";
    $numeroLic=trim(fgets(STDIN));
    echo "Ingresar nombre ";
    $nombreR=trim(fgets(STDIN));
    echo "Ingresar apellido ";
    $apellidoR=trim(fgets(STDIN));
    $respon->setNumeroLicencia($numeroLic);
    $respon->setNombre($nombreR);
    $respon->setApellido($apellidoR);
    $respon->insertar();
  }
  function modificarResponsable(){
    $res= new Responsable();
    echo "Ingrese el Numero del responsable que  desea modificar\n";
    $idMod = trim(fgets(STDIN));
    $res->setNumeroEmpleado($idMod);
    $existeRes=existeResponsable($idMod);
    if($existeRes){
      echo "Ingrese el numero licencia\n";
      $numeroLicR=trim(fgets(STDIN));
      echo "Ingrese el nombre\n";
      $nombreR=trim(fgets(STDIN));
      echo "Ingrese el apellido\n";
      $apellidoR=trim(fgets(STDIN));
      $res->setNumeroLicencia($numeroLicR);
      $res->setNombre($nombreR);
      $res->setApellido($apellidoR);
      $res->modificar();
      echo "Empresa Modificada correctamente\n";
    }else{
      echo "ID ingresado no existente\n";
    }
  }
  function eliminarResponsable(){
    $res=new Responsable();
    echo "Ingrese el NUMERO de responsable que desea eliminar\n";
    $idEli = trim(fgets(STDIN));
    $res->setNumeroEmpleado($idEli); 
    $existeRes=existeResponsable($idEli);
    if($existeRes){
      $res->eliminar();
      echo "Responsable eliminado correctamente\n";
    }else{
      echo "Numero ingresado no existente\n";
    }
  }
  function listarResponsables(){
    $res=new Responsable();
    $arregloRes=$res->listar();
    $cantRes=1;
    foreach ($arregloRes as $r){
      echo "Resposanble ".$cantRes."\n";
      echo $r."\n";
      $cantRes++;
    }
  }

  //Funciones para Pasajeros. Revisar que ande todo bien(Chekeado)
  function ingresarPasajero($numEmp){
    $pasa= new Pasajero;
    $maximaOcupacion=false;
    do{
      echo "Ingresar numero documento ";
      $documento=trim(fgets(STDIN));
      $Esta=existePasajero($documento);
      if($Esta){
        echo"Pasajero ya ingresado, Ingrese otra vez el documento\n";
      }
    }while($Esta);
    echo "Ingresar nombre ";
    $nombreP=trim(fgets(STDIN));
    echo "Ingresar apellido ";
    $apellidoP=trim(fgets(STDIN));
    echo "Ingresar telefono ";
    $telefonoP=trim(fgets(STDIN));
    do{
      echo "Ingresar ID Viaje ";
      $idViaje=trim(fgets(STDIN));
      $esta=true;
      if(existeViaje($idViaje,$numEmp)){
        $esta=false;
        $viaje=devolverViaje($idViaje);
        $cantMaxPasajeros=$viaje->getCantMaxPasajeros();
        $idVia=$viaje->getIdViaje();
        $condicion="idviaje=".$idVia;
        $coleccionPasajeros=$pasa->listar($condicion);
        $pasajesOcupados=count($coleccionPasajeros);
        if($pasajesOcupados>=$cantMaxPasajeros){
          $maximaOcupacion=true;
        }
      }else{
        echo "No existe el viaje, ingrese un numero correcto\n";
      }
    }while($esta);
    if($maximaOcupacion){
      echo "Lo sentimos el pasajero no puede ser ingresado, VIAJE LLENO!\n";
    }else{
      $pasa->setDni($documento);
      $pasa->setNombre($nombreP);
      $pasa->setApellido($apellidoP);
      $pasa->setNumeroTelefono($telefonoP);
      $pasa->setObjetoViaje($viaje);
      echo "Viaje pasajero correctamente\n";
      $pasa->insertar();
    }
  }
  function modificarPasajero($numEmp){
    $pas= new Pasajero();
    echo "Ingrese el Numero de documento que  desea modificar\n";
    $doc = trim(fgets(STDIN));
    $pas->setDni($doc);
    $existePas=existePasajero($doc);
    if($existePas){
      echo "Ingresar numero documento ";
      $documento=trim(fgets(STDIN));
      echo "Ingresar nombre ";
      $nombreP=trim(fgets(STDIN));
      echo "Ingresar apellido ";
      $apellidoP=trim(fgets(STDIN));
      echo "Ingresar telefono ";
      $telefonoP=trim(fgets(STDIN));
      $maximaOcupacion=false;
      do{
        echo "Ingresar ID Viaje ";
        $idViaje=trim(fgets(STDIN));
        $esta=true;
        if(existeViaje($idViaje,$numEmp)){
          $esta=false;
          $viaje=devolverViaje($idViaje);
          $cantMaxPasajeros=$viaje->getCantMaxPasajeros();
          $idVia=$viaje->getIdViaje();
          $condicion="idviaje=".$idVia;
          $coleccionPasajeros=$pas->listar($condicion);
          $pasajesOcupados=count($coleccionPasajeros);
          if($pasajesOcupados>=$cantMaxPasajeros){
            $maximaOcupacion=true;
          }
        }else{
          echo "No existe el viaje, ingrese un numero correcto";
        }
      }while($esta);
      if($maximaOcupacion){
        echo "Lo sentimos el pasajero no puede ser ingresado, EL VIAJE ESTA LLENO\n";
      }else{
        $pas->setDni($documento);
        $pas->setNombre($nombreP);
        $pas->setApellido($apellidoP);
        $pas->setNumeroTelefono($telefonoP);
        $pas->setObjetoViaje($viaje);
        $pas->modificar();
        echo "Pasajero Modificado correctamente\n";
      }
     
    }else{
      echo "Documento ingresado no existente\n";
    }
  }
  function eliminarPasajero(){
    $pas=new Pasajero();
    echo "Ingrese el N° de DOCUMENTO que desea eliminar\n";
    $doc = trim(fgets(STDIN));
    $pas->setDni($doc); 
    $existePas=existePasajero($doc);
    if($existePas){
      $pas->eliminar();
      echo "Pasajero eliminado correctamente\n";
    }else{
      echo "Numero ingresado no existente\n";
    }
  }
  function listarPasajeros($numEmp){
    $pas=new Pasajero();
    $arregloPas=$pas->listar();
    $cantPas=1;
    foreach ($arregloPas as $p){
      $p->buscar($p->getDni());
      $viaje=new Viaje();
      $viaje->buscar($p->getObjetoViaje()->getIdViaje());//Seteo de emp como obj
      if($viaje->getObjEmpresa()->getIdEmpresa()==$numEmp){
        echo "Pasajero ".$cantPas."\n";
        echo $p."\n";
        $cantPas++;
      }
    }
  }
  function existePasajero($doc){
    $pas= new Pasajero();
    $pas->setDni($doc);
    $arregloPas=$pas->listar();
    $existencia=false;
    foreach($arregloPas as $p){
      if($p->getDni()==$doc){
        $existencia=true;
      }
    }
    return $existencia;
  }
  function devolverPasajero($doc){
    $pas= new Pasajero();
    $pas->setDni($doc);
    $arregloPas=$pas->listar();
    foreach($arregloPas as $p){
      if($p->getDni()==$doc){
        $pas=$p;
      }
    }
    return $pas;
  }
// Programa principal
  do{
    $opcion=opcionesEmpresa();
    switch($opcion){
      case 1:
        ingresarEmpresa();
        break;
      case 2:
        modificarEmpresa();
        break;
      case 3:
        eliminarEmpresa();
        break;
      case 4:
        listarEmpresas();
        break;
      case 5:
        $emp= new Empresa();
        echo "Ingrese el numero de empresa con la cual quiere trabajar\n";
        $numEmp=trim(fgets(STDIN));
        $existeEmp=existeEmpresa($numEmp);
        if($existeEmp){
          do{
            $opcionGen=opcionesGenerales();
            switch($opcionGen){
              case 1://Case Viajes
                  do{
                    $opcionV=opcionesViaje();
                    switch($opcionV){
                      case 1:
                        ingresarViaje($numEmp);
                        break;
                      case 2: 
                        modificarViaje($numEmp);
                        break;
                      case 3:
                        eliminarViaje($numEmp);
                        break;
                      case 4:
                        listarViajes($numEmp);
                        break;
                      default:
                        $opcionV=-3;
                        break;
                    }
                  }while($opcionV!=-3);
                break;
              case 2://Case Pasajeros
                do{
                  $opcionP=opcionesPasajeros();
                  switch($opcionP){
                    case 1:
                      ingresarPasajero($numEmp);
                      break;
                    case 2: 
                      modificarPasajero($numEmp);
                      break;
                    case 3:
                      eliminarPasajero();
                      break;
                    case 4:
                      listarPasajeros($numEmp);
                      break;
                    default:
                      $opcionP=-3;
                      break;
                  }
                }while($opcionP!=-3);
                break;
              case 3://Case Responsable
                do{
                  $opcionR=opcionesResponsable();
                  switch($opcionR){
                    case 1:
                      ingresarResponsable($numEmp);
                      break;
                    case 2: 
                      modificarResponsable();
                      break;
                    case 3:
                      eliminarResponsable();
                      break;
                    case 4:
                      listarResponsables();
                      break;
                    default:
                      $opcionR=-3;
                      break;
                  }
                }while($opcionR!=-3);
                break;
              case 4:
                $opcionGen=-2;
                break;
              default:
                $opcion=-1;
                $opcionGen=-2;
                break;
              }         
            }while($opcionGen!=-2); //sale del while y explota. Revisar
          }else{
            echo "ID ingresado no existente\n";
          }
        break;
      default: $opcion=-1;
        break;
    }
  }while($opcion!=-1);
?>