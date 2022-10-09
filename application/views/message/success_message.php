<?php 
	$this->load->view("template/head.php");
?>

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
				
      <div class="">
        <div class="d-flex justify-content-center mb-4">
          <i class="far fa-check-circle display-2 text-success"></i>
        </div>

        <h1 class="text-success">
          <?php echo $title?>
        </h1>
        
        <p>
          <?php echo $description?>
          
        </p>

        <a href="<?php echo site_url()?>" class="btn btn-success btn-lg">Entendido</a>

      </div>

			</div>
		</div>
	</div>
</div>


<?php 
	$this->load->view("template/footer.php");
?>

<?php 
	$this->load->view("template/footer.php");
?>