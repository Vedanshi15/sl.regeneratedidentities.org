<?php
session_start();
if (!isset($_SESSION['user_id'])) {
	header("Location: index.php");
}
require_once('header.php');

?>
<!-- MAIN CONTENT-->
<div class="main-content">
	<div class="section__content section__content--p30">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<h3 class="title-5 m-b-35">Preview Document </h3>

				</div>
			</div>

		</div>
	</div>
</div>

<?php require_once 'footer.php' ?>
