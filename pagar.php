<?php
include 'global/config.php';
include 'global/conexion.php';
include 'carrito.php';
include 'templates/cabecera.php';
?>

<?php 
if ($_POST) {
    $total=0;
    $SID=session_id();
    $Correo=$_POST['email'];
    foreach($_SESSION['CARRITO'] as $indice=>$producto){
    
        $total=$total+($producto['PRECIO']*$producto['CANTIDAD']);

    }

    $sentencia=$pdo->prepare("INSERT INTO `tblventas` 
                            (`ID`, `ClaveTransaccion`, `PaypalDatos`, `Fecha`, `Correo`, `Total`, `status`) 
                            VALUES (NULL,:ClaveTransaccion,'',NOW(),:Correo,:Total,'pendiente');");
                            
                            $sentencia->bindParam(":ClaveTransaccion",$SID);
                            $sentencia->bindParam(":Correo",$Correo);
                            $sentencia->bindParam(":Total",$total);
                            $sentencia->execute();
                            $idVenta=$pdo->lastInsertId();



                            foreach($_SESSION['CARRITO'] as $indice=>$producto){

                                $sentencia=$pdo->prepare("INSERT INTO 
                                `tbldetalleventa` (`ID`, `IDVENTA`, `IDPRODUCTO`, `PRECIOUNITARIO`, `CANTIDAD`, `DESCARGADO`) 
                                VALUES (NULL,:IDVENTA,:IDPRODUCTO,:PRECIOUNITARIO,:CANTIDAD, '0');");

                                $sentencia->bindParam(":IDVENTA", $idVenta);
                                $sentencia->bindParam(":IDPRODUCTO",$producto['ID']);
                                $sentencia->bindParam(":PRECIOUNITARIO",$producto['PRECIO']);
                                $sentencia->bindParam(":CANTIDAD",$producto['CANTIDAD']);
                                $sentencia->execute();
                         
                            }

    //echo "<h3>".$total."<h3>";
}
?>

<script src="https://www.paypalobjects.com/api/checkout.js"></script>


<style>
    
    /* Media query for mobile viewport */
    @media screen and (max-width: 400px) {
        #paypal-button-container {
            width: 100%;
        }
    }
    
    /* Media query for desktop viewport */
    @media screen and (min-width: 400px) {
        #paypal-button-container {
            width: 250px;
            display: inline-block;
        }
    }
    
</style>

<div class="jumbotron text-center">
    <h1 class="display-4">¡Paso Final !</h1>
    <hr class="my-4">
    <p class="lead">Estas a punto de pagar con paypal la cantidad de: 
    <h4>$<?php echo number_format($total,2);?></h4>
    <div id="paypal-button-container"></div>
    </p>

    <p>Factura estara disponible una vez se procese el pago<br/>
    <strong>(Para aclaraciones : contacto@gmail.com)</strong>
    </p>
</div>



 

<script>
    paypal.Button.render({
        env: 'sandbox', // sandbox | production
        style: {
            label: 'checkout',  // checkout | credit | pay | buynow | generic
            size:  'responsive', // small | medium | large | responsive
            shape: 'pill',   // pill | rect
            color: 'gold'   // gold | blue | silver | black
        },

        // PayPal Client IDs - replace with your own
        // Create a PayPal app: https://developer.paypal.com/developer/applications/create

        client: {
            sandbox:   'AZDxjDScFpQtjWTOUtWKbyN_bDt4OgqaF4eYXlewfBP4-8aqX3PiV8e1GWU6liB2CUXlkA59kJXE7M6R',
            production: 'insert production client id'
        },

        // Wait for the PayPal button to be clicked

        payment: function(data, actions) {
            return actions.payment.create({
                payment: {
                    transactions: [
                        {
                            amount: { total: '<?php echo $total;?>', currency: 'USD' }, 
                            description:"Compra de productos a Develoteca:$0.01",
                            custom:"Codigo"
                        }
                    ]
                }
            });
        },

        // Wait for the payment to be authorized by the customer

        onAuthorize: function(data, actions) {
            return actions.payment.execute().then(function() {
                window.aler("Pyment complete");
                console.log(data);
                window.location="verificador.php?paymentToken="+data.paymentToken+"&paymentID="+data.paymentID;
            });
        }
    
    }, '#paypal-button-container');

</script>





<?php include 'templates/pie.php';?>