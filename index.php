<?php
include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/cabecera.php';
?>

    <br>


    <?php if($mensaje!="") {?>
       <div class="alert alert-success">
          <?php echo $mensaje;?>
          <a href="mostrarCarrito.php" class="badge badge-success">Ver carrito</a>
       </div>
    <?php }?>

    <div class="row">
         <?php
           $sentencia=$pdo->prepare("SELECT * FROM tblproductos");
           $sentencia->execute();
           $listaProductos=$sentencia->fetchAll(PDO::FETCH_ASSOC);
           // print_r($listaProductos);
    
         ?>

        <?php foreach($listaProductos as $productos){?>

            <div class="col-3">
                    <div class="card">
                              <img 
                                 height="350px"
                                 title="<?php echo $productos['Nombre'];?>"
                                 alt="<?php echo $productos['Nombre'];?>"
                                 class="card-img-top" 
                                 src="<?php echo $productos['Imagen'];?>" 
                               >
                       <div class="card-body">
                             <span><?php echo $productos['Nombre'];?></span>
                             <h5 class="card-title">$<?php echo $productos['Precio'];?></h5>
                            

                              <form action="" method="post">
                                
                              <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt($productos['ID'],COD,KEY);?>">
                              <input type="hidden" name="nombre" id="nombre" value="<?php echo openssl_encrypt($productos['Nombre'],COD,KEY);?>">
                              <input type="hidden" name="precio" id="precio" value="<?php echo openssl_encrypt($productos['Precio'],COD,KEY);?>">
                              <input type="hidden" name="cantidad" id="cantidad" value="<?php echo openssl_encrypt(1,COD,KEY);?>">
                              
         

                              <button 
                              class="btn btn-primary" 
                              name="btnAccion" 
                              value="Agregar" 
                              type="submit">
                              Agregar al carrito
                              </button>
                              
                              </form>

                           
                      </div>
                  </div>
            </div>
         <?php } ?>

      
    </div>

    
</div>


<?php include 'templates/pie.php';?>

