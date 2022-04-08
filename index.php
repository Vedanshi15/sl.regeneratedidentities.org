<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags-->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Sierra Leone Public Archives">
  <meta name="author" content="Sierra Leone Public Archives">
  <meta name="keywords" content="Sierra Leone Public Archives">

  <!-- Title Page-->
  <title>Login</title>

  <!-- Fontfaces CSS-->
  <link href="assets/css/font-face.css" rel="stylesheet" media="all">
  <link href="assets/vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
  <link href="assets/vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
  <link href="assets/vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

  <!-- Bootstrap CSS-->
  <link href="assets/vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

  <!-- Vendor CSS-->
  <link href="assets/vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
  <link href="assets/vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
  <link href="assets/vendor/wow/animate.css" rel="stylesheet" media="all">
  <link href="assets/vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
  <link href="assets/vendor/slick/slick.css" rel="stylesheet" media="all">
  <link href="assets/vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

  <!-- Main CSS-->
  <link href="assets/css/theme.css" rel="stylesheet" media="all">
  <link href="assets/css/login.css" rel="stylesheet" media="all">

</head>
<?php
require 'db_files/db_config.php';
date_default_timezone_set('America/Toronto');
$date = new DateTime();
$TimeDate = $date->format('Y-m-d H:i:s');
$success = false;
$failure = false;
if (isset($_GET['message'])) {
	$message = "Invalid Credentials! Please try again.";
}
if (!empty($_POST['email']) && !empty($_POST['password'])):

	$records = $conn->prepare('SELECT * FROM users WHERE email = :email');
	$records->bindParam(':email', $_POST['email']);
	$records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);

	$message = '';

	if (count($results) > 0 && password_verify($_POST['password'], $results['password'])) {
		session_start();
		$_SESSION['user_id'] = $results['id'];
		$_SESSION['email'] = $results['email'];
		$_SESSION['user_name'] = $results['fname'] . " " . $results['lname'];
		if($results['security'] == 0){
		  $_SESSION['role'] = "Super Admin";
    }
	  if($results['security'] == 1){
		  $_SESSION['role'] = "Project Director";
	  }
	  if($results['security'] == 2){
		  $_SESSION['role'] = "Archivists/Researcher";
	  }

		$sql = "INSERT INTO `logs` (`UserName`, `TimeDate`) VALUES ('" . $_POST['email'] . "', '" . $TimeDate . "')";
		$stmt = $conn->prepare($sql);

		if ($stmt->execute()) {
		$success = true;

		//header("Refresh:1;url=index.php");
		//header('Location: index.php');
		$message = 'Log in Success ! Redirecting ... ';
		} else {
		$failure = true;
		$message = 'Error! Please contact Admin at admin@regid.ca.';
		}

	} else {
		$failure = true;
		$message = 'Incorrect Credentials! Please try again.';
	}

endif;

?>
<body>
<section id="login-form">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="form">
        <div class="container">
          <div class="login-form">
            <?php if ($failure) { ?>
              <div class="alert alert-danger" role="alert">
                <?php echo $message; ?>
              </div>
            <?php } ?>
            <?php if ($success) { ?>
              <div class="alert alert-success" role="alert">
                <?php echo $message; ?>
                <script type="text/javascript">
                    const url = "dashboard.php";
                    window.location.href=url;
                </script>
              </div>
            <?php } ?>
            <form action="index.php" method="POST" class="p-3 col-lg-10 col-md-12 col-sm-12 mx-auto">
              <center><img src="#" class="logo img-fluid pb-2" alt="Sierra Leone Public Archives Logo"></center>
              <div class="form-group mt-5">
                <label>Email Address</label>
                <input type="email" class="form-control" name="email" placeholder="User Name">
              </div>
              <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" placeholder="Password">
              </div>
              <button type="submit" class="btn btn-success col-4">Login</button>

            </form>

          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" id="main-text">
        <div class="p-5">
          <h1 class="text-white">Sierra Leone Public Archives</h1>
          <h2 class="text-white pb-2"> Login </h2>
          <p><i>- Powered by Regenerated Identities secured Gateway</i></p>
        </div>
      </div>
    </div>
</section>

<!-- end document-->


<?php
require_once("footer.php")
?>