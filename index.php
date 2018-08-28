<html>
<head>
<title>Cipher Challenge</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<!-- <script src="assets/js/scripts.js" ></script> -->
</head>

<body>
<?php
require_once('class/CipherChallenge.php');
if (isset($_POST['submit'])) { 
	 $obj = new CipherChallenge();
	if($obj->uploadFile() === true){
		echo '<div class="alert alert-success" role="alert">
				  Please view the file '.getcwd() .DS.'decryptFile.txt
				</div>'; 
	 }else{
	 	foreach ($obj->_errors as $key => $value) {
	 		 echo  '<div class="alert alert-danger" role="alert">
					  '.$value.'
					</div>'; 
	 	}
	}
}
?>
	 <form id="fileForm" class="form-horizontal" method="post" enctype="multipart/form-data" >
		<fieldset> 
			<legend>Decrypt the given encrypt file</legend> 
			    <div class="form-group">
			        <label class="col-sm-3 control-label">Upload the encrypt file</label>
			        <div class="col-sm-4">
			            <input type="file" class="form-control" name="encryptFile" />
			        </div>
			    </div> 
			<input type="submit" name="submit" class="btn btn-primary" value="Decrypt It"> 
		</fieldset>
	</form>
</body>
</html> 
