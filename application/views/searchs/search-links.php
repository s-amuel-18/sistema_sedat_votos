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



<div class="container">

<?php if( isset($error_message) ): ?>
    <h1 class="text-danger display-5">
        <?php echo $error_message?>
    </h1>
<?php endif;?>

<div class="py-3">
    <a class="btn btn-outline-primary" href="<?php echo $url_go_back?>"><i class="fas fa-arrow-left"></i> volver</a>
</div>
    

<?php 
    if( isset($data_link) ):
        foreach ($data_link as $item) {
            echo '<a class="d-block mb-3" href="'.$url.$item->id.'">'.$item->NOMBRE_INSTITUCIONES_CON_CODIGO.'</a>';

        }
    endif;
?>
    
</div>

<?php
$this->load->view("template/footer.php");
?>