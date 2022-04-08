
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
	header("Location: index.php");
}
	require_once('header.php');

?>
<?php //phpinfo(); ?>
  <!-- MAIN CONTENT-->
  <div class="main-content">
  <div class="section__content section__content--p30">
  <div class="container-fluid">

  <div class="row">
    <div class="col-md-12">
      <!-- DATA TABLE -->
      <h3 class="title-5 m-b-35">Upload Document </h3>
      <div class="table-data__tool">
        <div class="table-data__tool-left">

        </div>
        <div class="table-data__tool-right">
          <form action="./" method="post" id="fileForm">
            <button class="btn btn-danger" id="resetFileForm">
              <i class="zmdi zmdi-format-clear-all"></i> Clear
            </button>
            <button class="btn btn-success" type="submit">
              <i class="zmdi zmdi-upload pr-2"></i>Start Uploading
            </button>
        </div>
      </div>
      <div class="form-group">
        <input type="file" class="my-pond" name="filepond[]" />
      </div>
      </form>



      <!-- END DATA TABLE -->
    </div>
  </div>


	<?php require_once 'footer.php' ?>
