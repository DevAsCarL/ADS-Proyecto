<?php 
  include_once("../shared/formulario.php");
    class formAgregarProducto extends formulario{
      public function __construct($informacion){
          $this->path = "..";
          $this->encabezadoShow("Formulario Agregar Producto",$informacion);
      }

      public function formAgregarProductoShow($datosProducto = [], $datosProductos = [],$id_proforma,$id_cliente,$nomProd = ''){
        echo "<main class='wrapper-actions'>";
        ?>
        <div>
            <form action="getComprobantePago.php" method="post">
                <h3>Producto</h3>
                <input type="text" name="producto" value="<?php echo $nomProd?>">
                <input type="hidden" name="idProforma" value="<?php echo $id_proforma;?>">
                <input type="hidden" name="idCliente" value="<?php echo $id_cliente;?>">
                <button type="submit" class="" name="btnBuscarProducto">Buscar</button>
            </form>
            
        </div>
        <div class="wrapper-actions">
        <?php
        
        if(empty($datosProducto)){
           
        }else{?>
        
            <form class="lista-form" method="post" action="getComprobantePago.php">
                    <tr>
                        <th>Nombre Producto:</th>
                        <td><?php echo $datosProducto[0]["nombre"]?></td>            
                    </tr>
                    <tr>
                        <th>stock:</th>
                        <td><?php echo $datosProducto[0]["stock"]?></td>                
                    </tr>
                    <tr>
                        <th>Precio Unitario:</th>
                        <td><?php echo $datosProducto[0]["precioUnitario"]?></td>                
                    </tr>
                    <tr>
                        <th>Cantidad:</th>
                        
                        <input type="hidden" name="producto" value="<?php echo $nomProd?>">
                        <input type="hidden" name="idProducto" value="<?php echo $datosProducto[0]["id_producto"]?>">
                        <input type="hidden" name="idProforma" value="<?php echo $id_proforma?>">
                        <input type="hidden" name="idCliente" value="<?php echo $id_cliente?>">
                        <td><input type="number" name="cantidad" min="1" max="<?php echo $datosProducto[0]["stock"]?>" value="1"></td>                
                    </tr>
                    <div>
                        <button type="submit" name="btnAgregar">Agregar</button>
                    </div>  
        </form>
            
        <?php }
        ?>
        
        </div>
        <div>
        <table class="lista-form">
        
        <?php
        
        if(empty($datosProductos)){
           
        }else{
            ?>
            <tr>
            <th>Código del Producto</th>
            <th>Nombre del Producto</th>
            <th>Acción</th>
        </tr>
            <?php
             
            foreach($datosProductos as $dato) {
                ?>
                <tr>
                <form action="getComprobantePago.php" method= "post">
                    <input type="hidden" name="producto" value="<?php echo $nomProd?>">
                    <input type="hidden" name="idProducto" value="<?php echo $dato["id_producto"]?>">
                    <input type="hidden" name="idProforma" value="<?php echo $id_proforma?>">
                    <input type="hidden" name="idCliente" value="<?php echo $id_cliente?>">
                    <td align="center" ><?php echo $dato["codigo_producto"]?></td>
                    <td align="left" ><?php echo $dato["nombre"]?></td>
                    <td><button  type="submit" class="" name="btnSeleccionarProducto">Seleccionar</button></td>
                </form>
                
                </tr>
                <?php 
            }
        }?>
        </table>
        </div>
        <div class="lista-form">
            <form action="getComprobantePago.php" method= "post">
                <input type="hidden" value="<?php echo $id_proforma;?>" name="idProforma">
                <input type="hidden" value="<?php echo $id_cliente;?>" name="idCliente">
                <input class="volver-form__button" name="btnRegresarFactura" type="submit" value="Volver" >
                
            <form>
        </div>


        <?php
        echo "</main>";
        ?>
              
        <?php
        $this->piePaginaShow(); 
      }
    }
?>