<?php

	error_reporting( ~E_NOTICE ); // avoid notice
	
	require_once 'dbconfig.php';
	
	if(isset($_POST['btnsave']))
	{
		$nombre = $_POST['nombre'];// nombre estudiante
		$profesion = $_POST['profesion'];// profesion
		$descripcion = $_POST['descripcion'];// descripcion
		$red_uno = $_POST['red_uno'];// instagram
		$red_dos = $_POST['red_dos'];// facebook
		$red_tres = $_POST['red_tres'];// red tres
		
		$imgFile = $_FILES['user_image']['name'];
		$tmp_dir = $_FILES['user_image']['tmp_name'];
		$imgSize = $_FILES['user_image']['size'];

		$imgFileU = $_FILES['img_uno']['name'];
		$tmp_dirU = $_FILES['img_uno']['tmp_name'];
		$imgSizeU = $_FILES['img_uno']['size'];

		$imgFileD = $_FILES['img_dos']['name'];
		$tmp_dirD = $_FILES['img_dos']['tmp_name'];
		$imgSizeD = $_FILES['img_dos']['size'];

		$imgFileT = $_FILES['img_tres']['name'];
		$tmp_dirT = $_FILES['img_tres']['tmp_name'];
		$imgSizeT = $_FILES['img_tres']['size'];
		
		
		if(empty($nombre)){
			$errMSG = "Por favor ingrese nombre.";
		}
		else if(empty($profesion)){
			$errMSG = "Por favor ingrese profesión.";
		}
		else if(empty($descripcion)){
			$errMSG = "Por favor ingrese descripcion.";
		}
		else if(empty($red_uno)){
			$errMSG = "Por favor ingrese red uno.";
		}
		else if(empty($red_dos)){
			$errMSG = "Por favor ingrese red dos.";
		}
		else if(empty($red_tres)){
			$errMSG = "Por favor ingrese red tres";
		}
		else if(empty($imgFile)){
			$errMSG = "Por favor ingrese imagen de perfil.";
		}
		else if(empty($imgFileU)){
			$errMSG = "Por favor ingrese imagen de perfil.";
		}
		else if(empty($imgFileD)){
			$errMSG = "Por favor ingrese imagen de perfil.";
		}
		elseif (!empty($imgFile))
		{
			$upload_dir = 'user_images/'; // upload directory
	
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
		
			// valid image extensions
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
		
			// rename uploading image
			$userpic = rand(1000,1000000).".".$imgExt;
				
			// allow valid image file formats
			if(in_array($imgExt, $valid_extensions)){			
				// Check file size '5MB'
				if($imgSize < 5000000)				{
					move_uploaded_file($tmp_dir,$upload_dir.$userpic);
				}
				else{
					$errMSG = "Sorry, your file is too large.";
				}
			}
			else{
				$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
			}
		}else if (!empty($imgFileU)){
			$upload_dirU = 'trabajos/';
			$imgExtU = strtolower(pathinfo($imgFileU,PATHINFO_EXTENSION));

			$valid_extensionsU = array('jpeg', 'jpg', 'png', 'gif');
			$imguno = rand(1000,1000000).".".$imgExtU;
			if (in_array($imgExtU, $valid_extensionsU)) {
				if ($imgSize < 5000000) {
					move_uploaded_file($tmp_dirU,$upload_dirU.$imguno);
				}else{
					$errMSG = "Imagen demasiado grande";
				}
			}else{
				$errMSG ="Solo imagenes JPG, JPEG y PNG por favor";
			}
		}
		
		
		// if no error occured, continue ....
		if(!isset($errMSG))
		{
			$stmt = $DB_con->prepare('INSERT INTO alumnos(nombre,profesion,descripcion,red_uno, red_dos,
				red_tres, img_perfil, img_uno, img_dos, img_tres) VALUES(:nombre, :profesion, :descripcion, :red_uno, :red_dos, :red_tres, :img_perfil, :img_uno, :img_dos, :img_tres)');
			$stmt->bindParam(':nombre',$nombre);
			$stmt->bindParam(':profesion',$profesion);
			$stmt->bindParam(':descripcion',$descripcion);
			$stmt->bindParam(':red_uno',$red_uno);
			$stmt->bindParam(':red_dos',$red_dos);
			$stmt->bindParam(':red_tres',$red_tres);
			$stmt->bindParam(':img_perfil',$userpic);
			$stmt->bindParam(':img_uno',$imguno);
			$stmt->bindParam(':img_dos',$userpic);
			$stmt->bindParam(':img_tres',$userpic);
			
			if($stmt->execute())
			{
				$successMSG = "new record succesfully inserted ...";
				header("refresh:5;index.php"); // redirects image view page after 5 seconds.
			}
			else
			{
				$errMSG = "error while inserting....";
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Upload, Insert, Update, Delete an Image using PHP MySQL - Coding Cage</title>

<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">

</head>
<body>



<div class="container">


	<div class="page-header">
    	<h1 class="h2">Nuevo estudiante. <a class="btn btn-default" href="index.php"> <span class="glyphicon glyphicon-eye-open"></span> &nbsp; view all </a></h1>
    </div>
	<?php
	if(isset($errMSG)){
			?>
            <div class="alert alert-danger">
            	<span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?></strong>
            </div>
            <?php
	}
	else if(isset($successMSG)){
		?>
        <div class="alert alert-success">
              <strong><span class="glyphicon glyphicon-info-sign"></span> <?php echo $successMSG; ?></strong>
        </div>
        <?php
	}
	?>   

<form method="post" enctype="multipart/form-data" class="form-horizontal">
	    
	<table class="table table-bordered table-responsive">
	
    <tr>
    	<td><label class="control-label">Nombre.</label></td>
        <td><input class="form-control" type="text" name="nombre" placeholder="Ingrese nombre" value="<?php echo $nombre; ?>" /></td>
    </tr>
    
    <tr>
    	<td><label class="control-label">Profesión.</label></td>
        <td><input class="form-control" type="text" name="profesion" placeholder="Ingrese profesión" value="<?php echo $profesion; ?>" /></td>
    </tr>
    <tr>
    	<td><label class="control-label">Descripción.</label></td>
        <td><textarea class="form-control" name="descripcion"><?php echo $descripcion; ?></textarea></td>
    </tr>
    <tr>
    	<td><label class="control-label">Facebook.</label></td>
        <td><input class="form-control" type="text" name="red_uno" placeholder="Ingrese Facebook" value="<?php echo $red_uno; ?>" /></td>
    </tr>
    <tr>
    	<td><label class="control-label">Instagram.</label></td>
        <td><input class="form-control" type="text" name="red_dos" placeholder="Ingrese Instagram" value="<?php echo $red_dos; ?>" /></td>
    </tr>
    <tr>
    	<td><label class="control-label">Red tres.</label></td>
        <td><input class="form-control" type="text" name="red_tres" placeholder="Ingrese red tres" value="<?php echo $red_tres; ?>" /></td>
    </tr>
    <tr>
    	<td><label class="control-label">Foto de perfil.</label></td>
        <td><input class="input-group" type="file" name="user_image" accept="image/*" /></td>
    </tr>
    <tr>
    	<td><label class="control-label">Foto portada uno.</label></td>
        <td><input class="input-group" type="file" name="img_uno" accept="image/*" /></td>
    </tr>
    <tr>
    	<td><label class="control-label">Foto portada dos.</label></td>
        <td><input class="input-group" type="file" name="img_dos" accept="image/*" /></td>
    </tr>
    <tr>
    	<td><label class="control-label">Foto portada tres.</label></td>
        <td><input class="input-group" type="file" name="img_tres" accept="image/*" /></td>
    </tr>
    
    <tr>
        <td colspan="2"><button type="submit" name="btnsave" class="btn btn-default">
        <span class="glyphicon glyphicon-save"></span> &nbsp; save
        </button>
        </td>
    </tr>
    
    </table>
    
</form>    

</div>



	


<!-- Latest compiled and minified JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>


</body>
</html>