<?php
$this->load->view("template/head.php");
?>



<style>
	.b-red {
		border: 2px solid red;
	}
</style>

<!-- navbar -->
<?php $this->load->view("components/navbar"); ?>
<!-- endnavbar -->

<div style="background-color: #ececec;">
	<div class="container">
		<div class="d-flex align-items-center" style="height: 100vh;">
			<div class="text-center shadow col-12 col-sm-8 offset-sm-1 col-md-6 offset-md-3 bg-white d-flex align-items-center justify-content-center flex-column p-4" style="border-radius: 20px; min-height: 400px;">

				<h1 class="mb-5"><?php echo $title_page ?></h1>
				
				<?php if (isset($_SESSION["error_message"])) : ?>
					<div class="alert alert-danger text-danger" role="alert">
						<?php if ($_SESSION["error_message"]) : ?>
							<?php 
								echo $_SESSION["error_message"];
								unset($_SESSION["error_message"]);
							 ?>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if( isset( $_SESSION["success_message"] ) ):?>
					<div class="alert alert-success" role="alert">
						<?php 
							echo $_SESSION["success_message"];
							unset($_SESSION["success_message"]);

						?>
					</div>
				<?php endif?>

				<?php if (isset($centro_votacion)) : ?>

					<!-- <form action="<?php  site_url("votacion/selectCentroVotacion") ?>" method="POST" class="text-left"> -->

					<div class="form-group search_methods">
						<form id="form_search" class="form-inline" method="GET" action="<?php echo site_url("votacion/searchUbch")?>">
							<div class="form-group">
								<input id="search" class="form-control" type="search" autofocus name="search" placeholder="centro de votación">
								<input type="hidden" name="url" value="votacion/selectCentroVotacion/">
								<button class="btn btn-primary" type="submit">buscar</button>
							</div>
						</form>
					</div>
					
					<div class="form-group search_methods" style="display: none;">
						<label for="cedula">Nombre de Centro de Votación</label>
						<select onchange="document.getElementById('link_next').href = '<?php echo site_url('votacion/selectCentroVotacion/') ?>' + this.value" class="form-control" name="centro_votacion" id="centro_votacion">

							<?php foreach ($centro_votacion as $value) : ?>
								<option value="<?php echo $value->id ?>" <?php echo set_value("centro_votacion") == $value->id ? "selected" : "" ?>><?php echo $value->NOMBRE_INSTITUCIONES_CON_CODIGO ?></option>

							<?php endforeach ?>

						</select>

						<br>
						

						<a href="votacion/selectCentroVotacion/1" id="link_next" class="btn btn-primary">Siguiente</a>
					</div>



									
						<div class="">
							<button onclick="mostrarMetodoBusqueda(this.dataset.index)" data-index="0" class="btn btn-outline-primary btn-sm" type="button"><i class="fas fa-search"></i></button>
									
							<button onclick="mostrarMetodoBusqueda(this.dataset.index)" data-index="1" class="btn btn-outline-primary btn-sm " type="button"><i class="fas fa-list-ul"></i></button>
									
						</div>
						
						
						
						<br>
						<!-- <button class="btn btn-primary" type="submit">Siguiente</button> -->


					<!-- </form> -->

				<?php else : ?>
					<form style="max-width: 400px;" class="w-100 text-left" action="<?php echo site_url() ?>/votacion/createVotante" method="post" id="form_hoja_calculo" enctype="multipart/form-data">



						<div class="form-group">
							<label for="cedula">Cédula</label>
							<input require placeholder="Cedula De Identidad" id="cedula" class="form-control" min="1" type="number" name="cedula" >
						</div>

						<input type="hidden" name="centro_votacion" value="<?php echo $idCentro?>">


						<button class="btn btn-primary btn-block">Registrar</button>

					</form>


				<?php endif ?>




			</div>
		</div>
	</div>
</div>

<script>
	function mostrarMetodoBusqueda(index) {
		const methods = document.querySelectorAll(".search_methods");

		for (let i = 0; i < methods.length; i++) {
			methods[i].style.display = "none";
		}

		methods[index].style.display = "block";
		console.log(methods[index])
	}
</script>

<script>
	
	const form_search = document.getElementById("form_search");
	const search = document.getElementById("search");
	form_search.addEventListener("submit", e => {
		if( search.value.replace(" ", "").length == 0 ) e.preventDefault()
	});
</script>
<?php
$this->load->view("template/footer.php");
?>