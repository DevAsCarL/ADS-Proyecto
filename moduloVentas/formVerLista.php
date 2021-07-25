<?php 

require_once __DIR__ ."/../shared/footerSingleton.php";
require_once __DIR__ ."/../shared/headSingleton.php";

class formVerLista {
    public function formVerListaShow($informacion,$tiposServicio,$serviciosElegidos = [],$datosLista=[],$dinero = []){
        headSingleton::getHead("Formulario ver Lista",$informacion,"..");
        ?>
        <main class='wrapper-actions'>
        <div style="width:100% ">
            <h2 align="center">Información de Proforma </h2>
            <div style="width:100%;">
                <table style="width:100%;" id="table_productos_lista">
                    <tr>
                        <th>Acción</th>
                        <th>Código</th>
                        <th>Nombre del Producto</th>
                        <th>Unidades</th>
                        <th>Precio Unitario</th>
                        <th>Precio Total</th>
                    </tr>
                    <?php 
                    foreach ($datosLista as $dato){
                        ?> 
                        <tr>
                        <td align="center"><button type="button" data-idproducto="<?php echo $dato['id_producto'] ?>">X</button></td>
                        <td align="center"><p><?php echo $dato['codigo_producto'] ?></p></td>      
                        <td><p><?php echo $dato['nom_product'] ?></p></td>
                        <td><input class="input-counter" data-idproducto="<?php echo $dato['id_producto'] ?>" type="number" value="<?php echo $dato['cantidad'] ?>" min="1" max="<?php echo $dato['stock']?>"></td>
                        <td align="center"><input type="text" value="<?php echo $dato['precioProduct']?>" disabled></td>
                        <td><input class="input-result" type="text" value="<?php echo number_format( floatval($dato['precioProduct']*$dato['cantidad']), 2, '.', '') ?>" disabled></td>

                        
                        </tr>
                        <?php 
                    }
                    
                    ?>
                </table>
            </div>
        </div>
        <div id="container-servicios" style="width:100%;display:flex;justify-content: center;flex-direction: column;align-items: center;margin-bottom: 5rem;">
         <h2>Servicios</h2>
        <?php
                    foreach ($tiposServicio as $tipo) {
                        ?>
                        <div>
                            <label for="<?php echo $tipo['nombre']?>" ><?php echo $tipo['nombre']?></label>
                            <input type="checkbox" id="<?php echo $tipo['nombre']?>"
                            data-precioServicio = "<?php echo $tipo['precioDeServicio'] ?>"
                            data-idServicio = "<?php echo $tipo['id_tipo'] ?>"
                            <?php 
                            if(count($serviciosElegidos)){
                                if(count($serviciosElegidos) == 1){
                                    if($tipo['id_tipo']==$serviciosElegidos[0]){
                                        echo "checked";
                                    }
                                }else{
                                    if($tipo['id_tipo']==$serviciosElegidos[0] or $tipo['id_tipo']==$serviciosElegidos[1]){
                                        echo "checked";
                                    }
                                }
                            }
                            
                            ?>
                            
                            >
                            <label for="<?php echo $tipo['precioDeServicio']?>" >S/. <?php echo $tipo['precioDeServicio']?></label>
                        </div>
                        <?php 
                    }
                ?>
            </div>
            <div style="width:100%;">
                <table class="lista-form">
                    <tr>
                        <th>Subtotal: </th>
                        <td id="subtotal"><?php echo $dinero['subtotal']  ?></td>            
                    </tr>
                    <tr>
                        <th>IGV: </th>
                        <td id="igv"><?php echo $dinero['igv'] ?></td>                
                    </tr>
                    <tr>
                        <th>Precio Total: </th>
                        <td id="precioTotal"><?php echo $dinero['precioTotal'] ?></td>                
                    </tr>
                </table>
            </div>
            <div class="lista-form" style="display:flex;width: 100%;gap:50px">
            <form action='getEmitirProforma.php' class='form-message__link' method='post' style='padding:0;'>
                <input type='hidden' name='regresar' />
                <input name='btnEmitirProforma'  class='form-message__link' style='width:100%;font-size:1.5em;padding:.5em;' value='Volver' type='submit'>
            </form>
        </div>
        </main>
        <?php
        var_dump($_SESSION["lista_proforma"]);
        footerSingleton::getFooter("..");
    }
}

?>