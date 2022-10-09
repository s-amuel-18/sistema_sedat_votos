<?php 
	$this->load->view("template/head.php");
?>



<style>
	.b-red {
		border: 2px solid red;
	}
</style>
<div style="background-color: #ececec;">
	<div class="container">
		<div class="d-flex align-items-center" style="height: 100vh;">
			<div class="text-center shadow col-12 col-sm-8 offset-sm-1 col-md-6 offset-md-3 bg-white d-flex align-items-center justify-content-center flex-column p-4" style="border-radius: 20px; min-height: 400px;">
				
				<h1 class="mb-5">Iniciar Sesión</h1>

				<form style="max-width: 400px;" class="w-100 text-left" action="<?php echo site_url()?>/auth/login" method="post" id="form_hoja_calculo" enctype="multipart/form-data">

				<?php if(validation_errors() or isset($error_message) ):?>
					<div class="alert alert-danger text-danger" role="alert">
            <?php echo validation_errors("<div>", "*</div>")?>
            
            <?php if(isset($error_message) ):?>
						    <?php echo $error_message?>
            <?php endif;?>
					</div>
          <?php endif;?>

				<div class="form-group">
					<label for="username">Usuario</label>
					<input autofocus require placeholder="Nombre De Usuario" id="username" class="form-control" type="text" name="username" value="<?php echo set_value("username")?>">
				</div>

				<div class="form-group">
					<label for="password">Contraseña</label>
					<input require class="form-control" id="password" type="password" name="password" placeholder="********">
				</div>

				<button class="btn btn-primary btn-block">Ingresar</button>
				
				</form>
			</div>
		</div>
	</div>
</div>


<?php 
	$this->load->view("template/footer.php");
?>