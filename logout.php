<?php
session_start();
require 'header.php';
//require 'public/sections/topmenu.php';
?>


<?php
if (isset($_SESSION['user_id'])) {
	unset($_SESSION['user_id']);
	//header("Refresh:0");
	?>
	<div style="margin: 100px 0px;" class=" alert alert-success alert-dismissible fade show" role="alert">
		<strong>Success !!</strong> You have been logged out. Redirecting ...
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<?php
} else {
	?>
	<div style="margin: 100px 0px;" class=" alert alert-danger alert-dismissible fade show" role="alert">
		<strong>Failure !!</strong> You are already logged out. Redirecting ...
		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
			<span aria-hidden="true">&times;</span>
		</button>
	</div>

	<?php
}
?>

<script>
    (function () {
        window.history.back();
    })();
</script>


<?php require 'footer.php'; ?>
