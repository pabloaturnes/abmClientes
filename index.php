<?php


// abrir el Archivo.txt si existe
if(file_exists("archivo.txt")){
        //leer archivo
        $jsonClientes = file_get_contents("archivo.txt");
        // descodificar el json en array
        $aClientes = json_decode($jsonClientes, true);

}

$id = isset($_GET["id"])? $_GET["id"] : "";

if(isset($_GET["accion"]) && $_GET["accion"]=="eliminar"){
    //Elimina la imagen fisicamente

    $imgBorrar = "archivos/" . $aClientes[$id]["imagen"];
    if(file_exists($imgBorrar)){
    unlink($imgBorrar);
    }
    
    //Eliminamos el cliente del array
    unset($aClientes[$id]);
    
    //Actualizo el archivo con el nuevo array de clientes modificado
    file_put_contents("archivo.txt", json_encode($aClientes));
    
     header("location: index.php");
    }


    // BOTON DE GUARDAR

if(isset($_POST["btnGuardar"])){
    $dni = $_POST["txtDni"];
    $nombre = $_POST["txtNombre"];
    $telefono = $_POST["txtTelefono"];
    $correo = $_POST["txtCorreo"];
    $nuevoNombre = "";

    if($_FILES["archivo"]["error"] === UPLOAD_ERR_OK){
        $nombrealt = date("Ymdhmsi"); 
        $archivo_tmp = $_FILES["archivo"]["tmp_name"];
        $nombreArchivo = $_FILES["archivo"]["name"];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $nuevoNombre = "$nombrealt.$extension";
        move_uploaded_file($archivo_tmp, "archivos/" . $nuevoNombre);
    };
    
    //Arma un array con los datos y los agrega al array clientes
            $aClientes[] = array( "dni" => $dni,
                            "nombre" => $nombre,
                            "telefono" => $telefono,
                            "correo" => $correo,
                            "imagen" => $nuevoNombre);

                               
    // codifica el array en json

    $jsonClientes=json_encode($aClientes);

    //Guardar el jason en un archivo.txt

    file_put_contents("archivo.txt", $jsonClientes);
}


    // BOTON DE ACTUALIZAR

if( isset ($_POST["btnActualizar"])){
    $dni = $_POST["txtDni"];
    $nombre = $_POST["txtNombre"];
    $telefono = $_POST["txtTelefono"];
    $correo = $_POST["txtCorreo"];
    $nuevoNombre = "";

    if($_FILES["archivo"]["error"] === UPLOAD_ERR_OK){
        $nombrealt = date("Ymdhmsi"); 
        $archivo_tmp = $_FILES["archivo"]["tmp_name"];
        $nombreArchivo = $_FILES["archivo"]["name"];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $nuevoNombre = "$nombrealt.$extension";
        move_uploaded_file($archivo_tmp, "archivos/" . $nuevoNombre);
    }
    
    if ($id >= 0){
        if ($_FILES["archivo"]["error"] !== UPLOAD_ERR_OK){
        $nuevoNombre = $aClientes[$id]["imagen"];
        } else {
            //elimna la imagen anterior
            if(file_exists("archivos/".$aClientes[$id]["imagen"])){
                unlink("archivos/".$aClientes[$id]["imagen"]);
                }

        }

            // sustituye los datos antiguos con los nuevos
        $aClientes[$id] = array( "dni" => $dni,
                                "nombre" => $nombre,
                                "telefono" => $telefono,
                                "correo" => $correo,
                                "imagen" => $nuevoNombre);

    }
                               
    // codificar el array en json

    $jsonClientes=json_encode($aClientes);

    //Guardar el jason en un archivo.txt

    file_put_contents("archivo.txt", $jsonClientes);
}

    // BOTON BORRAR
if(isset($_POST["btnBorrar"])){

    $imgBorrar = "archivos/" . $aClientes[$id]["imagen"];
    if(file_exists($imgBorrar)){
    unlink($imgBorrar);
    }
    
    //Eliminamos el cliente del array
    unset($aClientes[$id]);
    
    //Actualizo el archivo con el nuevo array de clientes modificado
    file_put_contents("archivo.txt", json_encode($aClientes));
    
     header("location: index.php");
       
};

    // BOTON LIMPIAR
if(isset($_POST["btnLimpiar"])){
    header("Location: /abmclientes/index.php");
};

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Empleados</title>
    <link rel="stylesheet" href="css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/fontawesome/css/fontawesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="css/Estilos.css">
</head>
<body>
<div class="container">
    <div class ="row">
        <div class="col-12 my-5 text-center">
            <h1>Registro de Clientes</h1>
        </div>
    </div>    
    <div class="row">
        <div class="col-md-6 col-12">
            <form action="" method="POST" enctype="multipart/form-data">
                            <div class="col-12 form-group">
                                <label for="textDni">DNI: *</label>
                                <input type="txt" id="txtDni" name="txtDni" class="form-control"  value="<?php echo isset($aClientes[$id])? $aClientes[$id]["dni"] : ""?>">
                            </div>
                            <div class="col-12 form-group">
                                <label for="textNombre">Nombre: *</label>
                                <input type="text" id="txtNombre" name="txtNombre" class="form-control"  value="<?php echo isset($aClientes[$id])? $aClientes[$id]["nombre"] : ""?>">
                            </div>                            
                            <div class="col-12 form-group">
                                <label for="textTelefono">Telefono: </label>
                                <input type="text" id="txtTelefono" name="txtTelefono" class="form-control" value="<?php echo isset($aClientes[$id])? $aClientes[$id]["telefono"] : ""?>">
                            </div>                            
                            <div class="col-12 form-group">
                                <label for="textCorreo">Correo: *</label>
                                <input type="text" id="txtCorreo" name="txtCorreo" class="form-control"  value="<?php echo isset($aClientes[$id])? $aClientes[$id]["correo"] : ""?>">
                            </div>                            
                            <div class="col-12 form-group mt-3">
                                 <label for="txtCorreo"> Archivo adjunto:</label>
                                 <input type="file" id="archivo" name="archivo" class=form-control-file accept=".jpg, .jpeg,.png">
                                 <small class="d-block">Archivos admitidos_.jpg, .jpeg, .png</small>
                            </div>
                            <div class="row">
                                <div class="col-12 mt-3">
                                    <button type="submit" id="btnGuardar" name="btnGuardar" class="btn btn-primary"> Guardar </button>
                                    <button type="submit" id="btnLimpiar" name="btnLimpiar" class="btn btn-secondary"> Limpiar </button>
                                    <button type="submit" id="btnBorrar" name="btnBorrar" class="btn btn-danger"> Borrar </button>
                                    <button type="submit" id="btnActualizar" name="btnActualizar" class="btn btn-success"> Actualizar </button>
                                </div>

                                <?php if(isset($_POST["btnGuardar"])){?>
                                <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                                    <strong>Tu cliente se ha guardado exitosamente</strong> 
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php }; ?>


                                <?php if(isset($_POST["btnActualizar"])){?>
                                <div class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
                                    <strong>Tu cliente se ha actualizado exitosamente</strong> 
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php }; ?>

                            </div>                          
            </form>
        </div>    
        <div class="col-12 col-md-6 mt-md-0 mt-4 mb-md-0 mb-2 tabla">
            <table class="table table-hover border">
                <tr>
                    <th>Imagen</th>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
                <?php foreach($aClientes as $key => $cliente): ?>
                 <tr>
                    <td><img src="archivos/<?php echo $cliente['imagen'];?>" class="img-thumbnail"></td>
                    <td><?php echo $cliente["dni"]?></td>
                    <td><?php echo $cliente["nombre"]?></td>
                    <td><?php echo $cliente["correo"]?></td>
                    <td style="width: 110px;">
                        <a href="?id=<?php echo $key; ?>"><i class="fas fa-edit"></i></a>
                        <a href="?id=<?php echo $key; ?>&accion=eliminar"><i class="fas fa-trash-alt"></i></a>
                    </td>
                 </tr>   
                <?php endforeach; ?>
            </table>
            <a href="index.php"><i class="fas fa-plus"></i></a>
        </div>
    </div>
</div> 


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</body>

</html>