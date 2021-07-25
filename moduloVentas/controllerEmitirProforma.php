<?php 
require_once __DIR__ ."/formEmitirProforma.php";
class controllerEmitirProforma {
    public function mostrarFormularioAddProductoYServicioAProforma(){
        include_once("../model/FactoryModels.php");
        $objTipoDeServicios = FactoryModels::getModel("tipodeservicio");
        $tiposServicio =  $objTipoDeServicios->listarServicios();
        $formulario = new formEmitirProforma();
        if(count($_SESSION["lista_proforma"]["servicios"])){
            $formulario->formEmitirProformaShow($_SESSION["informacion"],$tiposServicio,[],[],'',$_SESSION["lista_proforma"]["servicios"]);
        }else{
            $formulario->formEmitirProformaShow($_SESSION["informacion"],$tiposServicio);
        }
    }

    public function buscarProducto($nombreProd){
        include_once("../model/FactoryModels.php");
        $objProducto = FactoryModels::getModel("producto");
        $objTipoDeServicios = FactoryModels::getModel("tipodeservicio");
        $tiposServicio =  $objTipoDeServicios->listarServicios();
        $datosProductos = $objProducto -> obtenerProductos($nombreProd);
        $formulario = new formEmitirProforma();
        if(count($_SESSION["lista_proforma"]["servicios"])){
            $formulario->formEmitirProformaShow($_SESSION["informacion"],$tiposServicio,[],$datosProductos,$nombreProd,$_SESSION["lista_proforma"]["servicios"]);
        }else{
            $formulario->formEmitirProformaShow($_SESSION["informacion"],$tiposServicio,[],$datosProductos,$nombreProd);
        }
    }
    public function seleccionarProducto($id_producto,$productos){
        include_once("../model/FactoryModels.php");
        $objProducto = FactoryModels::getModel("producto");
        $objTipoDeServicios = FactoryModels::getModel("tipodeservicio");
        $tiposServicio =  $objTipoDeServicios->listarServicios();
        $datosProducto = $objProducto -> obtenerProducto($id_producto);
        $datosProductos = $objProducto -> obtenerProductos($productos);
        if(!isset($_SESSION)) 
        { 
            session_start(); 
        }
		$formulario = new formEmitirProforma();

        if(count($_SESSION["lista_proforma"]["servicios"])){
            $formulario->formEmitirProformaShow($_SESSION["informacion"],$tiposServicio,$datosProducto,$datosProductos,$productos,$_SESSION["lista_proforma"]["servicios"]);
        }else{
            $formulario->formEmitirProformaShow($_SESSION["informacion"],$tiposServicio,$datosProducto,$datosProductos,$productos);
        }
    }

    public function agregarProducto($idProducto,$productos,$cantidad){
        include_once("../model/FactoryModels.php");
        $objProducto = FactoryModels::getModel("producto");
        $objTipoDeServicios = FactoryModels::getModel("tipodeservicio");
        $tiposServicio =  $objTipoDeServicios->listarServicios();
        $datosProductos = $objProducto -> obtenerProductos($productos);
        $formulario = new formEmitirProforma();

        if(isset($_SESSION["lista_proforma"]["productos"][$idProducto])){
            $_SESSION["lista_proforma"]["productos"][$idProducto]+= $cantidad;
        }else{
            $_SESSION["lista_proforma"]["productos"][$idProducto]= $cantidad;
        }

        if(count($_SESSION["lista_proforma"]["servicios"])){
            $formulario->formEmitirProformaShow($_SESSION["informacion"],$tiposServicio,[],$datosProductos,$productos,$_SESSION["lista_proforma"]["servicios"]);
        }else{
            $formulario->formEmitirProformaShow($_SESSION["informacion"],$tiposServicio,[],$datosProductos,$productos);
        }

    }

    public function verLista(){
        include_once("../model/FactoryModels.php");
        $objProducto = FactoryModels::getModel("producto");
        $objTipoDeServicios = FactoryModels::getModel("tipodeservicio");
        $tiposServicio =  $objTipoDeServicios->listarServicios();
        include_once("formVerLista.php");
        $formulario = new formVerLista();
        $datosLista = [];
        $dinero = [];
        $total = 0;
        if(count($_SESSION["lista_proforma"]["productos"])){
            $productos = $objProducto->listarInformacionProductos($_SESSION["lista_proforma"]["productos"]);
            for ($i=0; $i < count($productos); $i++) {
                $productos[$i]["cantidad"] = $_SESSION["lista_proforma"]["productos"][$productos[$i]["id_producto"]];
                $total+=((double)$productos[$i]["precioProduct"])*$productos[$i]["cantidad"];
            }
            $datosLista = $productos;
        }
        if(count($_SESSION["lista_proforma"]["servicios"])){
            foreach ($tiposServicio as $tipo){
                if(count($_SESSION["lista_proforma"]["servicios"])==2){
                    if($_SESSION["lista_proforma"]["servicios"][0]==$tipo["id_tipo"] or $_SESSION["lista_proforma"]["servicios"][1]==$tipo["id_tipo"]){
                        $total+=(double)$tipo["precioDeServicio"];
                    }
                }
                if(count($_SESSION["lista_proforma"]["servicios"])==1){
                    if($_SESSION["lista_proforma"]["servicios"][0]==$tipo["id_tipo"]){
                        $total+=(double)$tipo["precioDeServicio"];
                    }
                }
            }
            
        }
        $dinero["precioTotal"] = $total;
        $dinero["igv"] = (double)$total * 0.18;
        $dinero["subtotal"] = $total - $dinero["igv"];
        $dinero["precioTotal"] =number_format( floatval($dinero["precioTotal"]), 2, '.', '');
        $dinero["igv"] = number_format( floatval($dinero["igv"]), 2, '.', '');
        $dinero["subtotal"] = number_format( floatval($dinero["subtotal"]), 2, '.', '');
        $_SESSION["lista_proforma"]["precioTotal"] = $dinero["precioTotal"];
        $formulario->formVerListaShow($_SESSION["informacion"],$tiposServicio,
        count($_SESSION["lista_proforma"]["servicios"]) ? $_SESSION["lista_proforma"]["servicios"] : [],
        $datosLista,$dinero);
    }
    public function obtenerPrecioUnitaciosProductos($idDeProductos){
        include_once("../model/FactoryModels.php");
        $objProducto = FactoryModels::getModel("producto");
        $datosPreciosUnitarios = $objProducto ->obtenerPrecioUnitaciosProductos($idDeProductos);
        return $datosPreciosUnitarios;
    }
    public function obtenerPrecioUnitaciosServicios($idDeServicios){
        include_once("../model/FactoryModels.php");
        $objTipoDeServicio = FactoryModels::getModel("tipodeservicio");
        $datosPreciosUnitarios = $objTipoDeServicio ->obtenerPrecioUnitaciosServicios($idDeServicios);
        return $datosPreciosUnitarios;
    }
    public function obtenerTotal($objPreciosUnitariosProductos = [], $objPreciosUnitariosServicios = []){
        $total = (float) 0;
        if(count($objPreciosUnitariosProductos)){
            foreach ($objPreciosUnitariosProductos as $objProducto){
                $total+= (double)$objProducto["precioUnitario"]*$_SESSION["lista_proforma"]["productos"][$objProducto["id_producto"]];
            }
        }

        if(count($objPreciosUnitariosServicios)){
            for ($i=0; $i < count($objPreciosUnitariosServicios); $i++) { 
                $total+= (double)$objPreciosUnitariosServicios[$i]["precioDeServicio"];
            }
        }
        $_SESSION["lista_proforma"]["precioTotal"]=number_format( floatval($total), 2, '.', '');
        return $_SESSION["lista_proforma"];
    }

    public function buscarClientePorDNI($dni){
        include_once("../model/FactoryModels.php");
        $objCliente = FactoryModels::getModel("cliente");
        $cliente = $objCliente->buscarClientePorDNI($dni);
        if($cliente["existe"]){
            if(!isset($_SESSION)) 
            { 
                session_start(); 
            }
            include_once("formAgregarCliente.php");
            $form = new formAgregarCliente;
            $form->formAgregarClienteShow($_SESSION["informacion"],$cliente["data"]);
        }else{
            include_once("../shared/formMensajeSistema.php");
            $nuevoMensaje = new formMensajeSistema;
            $nuevoMensaje -> formMensajeSistemaShow($cliente["mensaje"],"<form action='getEmitirProforma.php' class='form-message__link' method='post' style='padding:0;'>
            <input name='btnAgregarCliente'  class='form-message__link' style='width:100%;font-size:1.5em;padding:.5em;' value='Volver' type='submit'>
        </form>");
        }
    }

    public function insertarProforma($dni){
        include_once("../model/FactoryModels.php");
        $objCliente = FactoryModels::getModel("cliente");
        $cliente = $objCliente->buscarClientePorDNI($dni);
    }
}

?>