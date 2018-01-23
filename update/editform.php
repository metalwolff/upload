<?php

	error_reporting( ~E_NOTICE );
	
	require_once 'dbconfig.php';
	
	if(isset($_GET['edit_id']) && !empty($_GET['edit_id']))
	{
		$id = $_GET['edit_id'];
		$stmt_edit = $DB_con->prepare('SELECT nombre, profesion, descripcion, red_uno, red_dos, red_tres, img_perfil FROM alumnos WHERE id =:uid');
		$stmt_edit->execute(array(':uid'=>$id));
		$edit_row = $stmt_edit->fetch(PDO::FETCH_ASSOC);
		extract($edit_row);
	}
	else
	{
		header("Location: index.php");
	}
	
	
	
	if(isset($_POST['btn_save_updates']))
	{
		$nombre = $_POST['nombre'];// user name
		$profesion = $_POST['profesion'];// user email
		$descripcion = $_POST['descripcion'];// user name
		$red_uno = $_POST['red_uno'];// user email
		$red_dos = $_POST['red_dos'];// user name
		$red_tres = $_POST['red_tres'];// user email
			
		$imgFile = $_FILES['user_image']['name'];
		$tmp_dir = $_FILES['user_image']['tmp_name'];
		$imgSize = $_FILES['user_image']['size'];
					
		if($imgFile)
		{
			$upload_dir = 'user_images/'; // upload directory	
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
			$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // valid extensions
			$userpic = rand(1000,1000000).".".$imgExt;
			if(in_array($imgExt, $valid_extensions))
			{			
				if($imgSize < 5000000)
				{
					unlink($upload_dir.$edit_row['img_perfil']);
					move_uploaded_file($tmp_dir,$upload_dir.$userpic);
				}
				else
				{
					$errMSG = "Sorry, your file is too large it should be less then 5MB";
				}
			}
			else
			{
				$errMSG = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";		
			}	
		}
		else
		{
			// if no image selected the old image remain as it is.
			$userpic = $edit_row['img_perfil']; // old image from database
		}	
						
		
		// if no error occured, continue ....
		if(!isset($errMSG))
		{
			$stmt = $DB_con->prepare('UPDATE alumnos 
									     SET nombre=:uname, 
										     profesion=:ujob,
										     descripcion=:udesc,
										     red_uno=:uredu,
										     red_dos=:uredd,
										     red_tres=:uredt, 
										     img_perfil=:upic 
								       WHERE id=:uid');
			$stmt->bindParam(':uname',$nombre);
			$stmt->bindParam(':ujob',$profesion);
			$stmt->bindParam(':udesc',$descripcion);
			$stmt->bindParam(':uredu',$red_uno);
			$stmt->bindParam(':uredd',$red_dos);
			$stmt->bindParam(':uredt',$red_tres);
			$stmt->bindParam(':upic',$userpic);
			$stmt->bindParam(':uid',$id);
				
			if($stmt->execute()){
				?>
                <script>
				alert('Successfully Updated ...');
				window.location.href='index.php';
				</script>
                <?php
			}
			else{
				$errMSG = "Sorry Data Could Not Updated !";
			}
		
		}
		
						
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Editar alumno</title>

<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="bootstrap/css/bootstrap-theme.min.css">

<!-- custom stylesheet -->
<link rel="stylesheet" href="style.css">

<!-- Latest compiled and minified JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>

<script src="jquery-1.11.3-jquery.min.js"></script>
</head>
<body>



<div class="container">


	<div class="page-header">
    	<h1 class="h2">update profile. <a class="btn btn-default" href="index.php"> all members </a></h1>
    </div>

<div class="clearfix"></div>

<form method="post" enctype="multipart/form-data" class="form-horizontal">
	
    
    <?php
	if(isset($errMSG)){
		?>
        <div class="alert alert-danger">
          <span class="glyphicon glyphicon-info-sign"></span> &nbsp; <?php echo $errMSG; ?>
        </div>
        <?php
	}
	?>
   
    
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
        <td colspan="2"><button type="submit" name="btn_save_updates" class="btn btn-default">
        <span class="glyphicon glyphicon-save"></span> Update
        </button>
        
        <a class="btn btn-default" href="index.php"> <span class="glyphicon glyphicon-backward"></span> cancel </a>
        
        </td>
    </tr>
    
    </table>
    
</form>


<div class="alert alert-info">
    <strong>tutorial link !</strong> <a href="http://www.codingcage.com/2016/02/upload-insert-update-delete-image-using.html">Coding Cage</a>!
</div>

</div>
</body>
</html>