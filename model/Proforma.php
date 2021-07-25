<?php 
require_once __DIR__."/ConexionSingleton.php";
class Proforma {
    private $bd = null;

    public function listarProformas(){
        try {
            $this->bd = ConexionSingleton::getInstanceDB()->getConnection();
            $query = "SELECT p.id_proforma, p.codigo_proforma, p.fecha_emision, c.nombres, c.apellido_paterno, c.apellido_materno  FROM proformas p 
             INNER JOIN clientes c
             ON c.id_cliente = p.id_cliente
            WHERE  TIMESTAMPDIFF(HOUR,P.fechaYHora,CURRENT_TIMESTAMP) <= 12 and p.id_estadoProforma = 1";
            $consulta = $this->bd->prepare($query);
            $consulta->execute();

            return $consulta->fetchAll();

        }catch(Exception $ex){
            return $ex->getMessage();
        }
    }

    public function listarProformasFecha($fecha_seleccionada){
        try {
            $this->bd = ConexionSingleton::getInstanceDB()->getConnection();
            $query = "SELECT p.id_proforma, p.codigo_proforma, p.fecha_emision,c.id_cliente, c.nombres, c.apellido_paterno, c.apellido_materno FROM proformas p 
            INNER JOIN clientes c
             ON c.id_cliente = p.id_cliente
            WHERE  p.fecha_emision = :fecha_seleccionada and p.id_EstadoProforma = 1
            ";
            $consulta = $this->bd->prepare($query);
            $consulta->execute([
                'fecha_seleccionada' => $fecha_seleccionada
            ]);
            if($consulta->rowCount()){ 
                return ["existe"=>true, "data"=> $consulta->fetchAll()];
            }else{
                return ["existe"=>false,"mensaje"=>"No se encontrado ninguna proforma habilitada" ];
            }

            

        }catch(Exception $ex){
            return $ex->getMessage();
        }
    }

    public function obtenerProductosDeproformaSeleccionada($id_proforma){
        try {
            $this->bd = ConexionSingleton::getInstanceDB()->getConnection();
            $query = "SELECT count(pr.id_producto) as cantidad,p.id_proforma,p.precioTotal,p.subtotal,p.igv, c.nombres as nom_client,pr.stock, c.apellido_paterno, c.apellido_materno, c.dni, c.celular,dp.id_producto, dp.id_detalleProformaProducto
            ,pr.nombre as nom_product,pr.codigo_producto, pr.precioUnitario as precioProduct FROM proformas p 
                INNER JOIN clientes c
                ON c.id_cliente = p.id_cliente
                INNER JOIN detalleproformaproducto dp
                    ON dp.id_proforma = p.id_proforma
                INNER JOIN productos pr
                    ON pr.id_producto = dp.id_producto
                WHERE  p.id_proforma = :id_proforma and p.id_EstadoProforma = 1
                GROUP BY pr.id_producto;
            ";
            $consulta = $this->bd->prepare($query);
            $consulta->execute([
                'id_proforma' => (int)$id_proforma
            ]);
            return $consulta->fetchAll();          

        }catch(Exception $ex){
            return $ex->getMessage();
        }
    }

    public function obtenerServiciosDeproformaSeleccionada($id_proforma){
        try {
            $this->bd = ConexionSingleton::getInstanceDB()->getConnection();
            $query = "SELECT dps.id_tiposervicio,ts.nombre, ts.precioDeServicio FROM proformas as p
            INNER JOIN detalleproformaservicio as dps
                on dps.id_proforma = p.id_proforma
            INNER JOIN tipodeservicios as ts
            ON ts.id_tipo = dps.id_tiposervicio
            WHERE p.id_proforma = :id_proforma";

            $consulta = $this->bd->prepare($query);

            $consulta->execute([
                'id_proforma' => (int)$id_proforma
            ]);
            if($consulta->rowCount()){ 
                return ["existe"=>true, "data"=> $consulta->fetchAll()];
            }else{
                return ["existe"=>false];
            }        
        }catch(Exception $ex){
            return $ex->getMessage();
        }
    }

    public function cambiarEstadoProforma($id_proforma){
        try{
            $this->bd = ConexionSingleton::getInstanceDB()->getConnection();
            $query = "UPDATE proformas SET id_estadoProforma = 0 where id_proforma = :id_proforma;";
            $consulta = $this->bd->prepare($query);

            $consulta->execute([
                'id_proforma' => (int)$id_proforma
            ]);
            return ["success"=>true];
        }catch(Exception $ex){
            return ["success"=>false,"message"=>$ex->getMessage()];
        }
    }
    public function insertarProforma($id_cliente,$precioTotal,$id_usuario,$subtotal,$igv){
        try{
            date_default_timezone_set('America/Lima');
            $fechaYhora = date('Y-m-d H:i:s', time());
            $fechaemision = explode(" ", $fechaYhora)[0];
            $hora_emision = explode(" ", $fechaYhora)[1];
            $sql = "INSERT INTO proformas(fecha_emision,precioTotal,hora_emision,id_estadoProforma,id_cliente,id_estadoEntidad,id_usuario,fechaYhora,subtotal,igv)
            VALUES (:fecha_emision,:precioTotal,:hora_emision,1,:id_cliente,0,:id_usuario,:fechaYhora,:subtotal,:igv)";
            
            $this->bd = ConexionSingleton::getInstanceDB()->getConnection();
            $consulta = $this->bd->prepare($sql);
            $consulta->execute([
                "fecha_emision"=>$fechaemision,
                "hora_emision"=>$hora_emision,
                "precioTotal" => (double)$precioTotal,
                "id_cliente" =>$id_cliente,
                "id_usuario" => $id_usuario,
                "fechaYhora"=>$fechaYhora,
                "subtotal"=>(double)$subtotal,
                "igv"=>(double)$igv,
            ]);
            $id = $this->bd->lastInsertId();

            $codigo_proforma = substr('00000000' . $id, -8);
            $query = "UPDATE proformas SET codigo_proforma = :codigo_proforma where id_proforma = :id_proforma;";
            $consulta = $this->bd->prepare($query);
            $consulta->execute([
                "id_proforma"=>$id,
                "codigo_proforma"=>$codigo_proforma,
            ]);

            return ["success"=>true,"id"=>$id];

        }catch(Exception $ex){
            return ["success"=>false,"message"=>$ex->getMessage()];
        }
    }

    public function insetarDetalleProformaProducto($id_proforma,$productos = []){
        try{
            $this->bd = ConexionSingleton::getInstanceDB()->getConnection();
            $query = "INSERT INTO detalleproformaproducto (id_producto,id_proforma) VALUES ";
            foreach ($productos as $idp => $cantidad) {
                for ($i=0; $i < $cantidad ; $i++) { 
                    $query.="($idp,$id_proforma),";
                }
            }
            $query = substr_replace($query ,"",-1);
            $consulta = $this->bd->prepare($query);
            $consulta->execute();
            return ["success"=>true]; 
        }catch(Exception $ex){
            return ["success"=>false,"message"=>$ex->getMessage()];
        }
    }
    public function insetarDetalleProformaServicio($id_proforma,$servicios = []){
        try{
            $this->bd = ConexionSingleton::getInstanceDB()->getConnection();
            $query = "INSERT INTO detalleproformaservicio (id_tiposervicio,id_proforma) VALUES ";
            for ($i=0; $i < count($servicios) ; $i++) { 
                $query= $query . "($servicios[$i],$id_proforma),";
            }
            $query = substr_replace($query ,"",-1);
            $consulta = $this->bd->prepare($query);
            $consulta->execute();
            return ["success"=>true]; 
        }catch(Exception $ex){
            return ["success"=>false,"message"=>$ex->getMessage()];
        }
    }
}

?>

