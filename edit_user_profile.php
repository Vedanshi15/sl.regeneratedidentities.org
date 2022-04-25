<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once 'header.php';
  require_once("db_files/db_config.php");
  $success = false;
  $failure = false;
  if (isset($_POST['submit'])) {
    $id = $_SESSION['user_id'];
    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    //$role = $_POST['role'];
    $organization = $_POST['organization'];
    $target_dir='assets/img/users/';
    $user_image = null;
    $sql = null;
    $ext = pathinfo($_FILES["user_image"]["name"], PATHINFO_EXTENSION);
    $target_file = $target_dir . basename(time().'.'.$ext);
    if (move_uploaded_file($_FILES["user_image"]["tmp_name"], $target_file))
    {
      //echo "The file ". basename( $_FILES["photo"]["name"]). " has been uploaded.";
    }
    if($id != '')
    {
      if($_FILES["user_image"]["name"]!='')
      {
        $user_image = time().'.'.$ext;
        //$info = mime_content_type($user_image);
        var_dump($user_image);
        //var_dump($info);
        $sql = "Update users set
      			email = :email,
                fname = :fname,
                lname = :lname,
                Organization = :Organization,
                 img = :img,
                security = :security                 
                WHERE id = :id ";
        var_dump($sql);
        $pst = $conn->prepare($sql);
        $pst->bindParam(':email', $email);
        $pst->bindParam(':fname', $fname);
        $pst->bindParam(':lname', $lname);
        $pst->bindParam(':Organization', $organization);
        var_dump($user_image);
        $pst->bindParam(':img', $user_image);
        $pst->bindParam(':security', $role);
        $pst->bindParam(':id', $id);
        $count = $pst->execute();
        var_dump($count);
      }
      else
      {
        $sql = "Update users set
      			email = :email,
                fname = :fname,
                lname = :lname,
                Organization = :Organization,
                security = :security
                WHERE id = :id ";
        $pst = $conn->prepare($sql);
        $pst->bindParam(':email', $email);
        $pst->bindParam(':fname', $fname);
        $pst->bindParam(':lname', $lname);
        $pst->bindParam(':Organization', $organization);
        $pst->bindParam(':security', $role);
        $pst->bindParam(':id', $id);
        $count = $pst->execute();
        var_dump($count);
      }

    }
    else
    {
      if($_FILES["user_image"]["name"]!='')
      {
        $user_image = time().'.'.$ext;
      }
      else
      {
        $user_image='';
      }
    }






    if ($count) {
      $success = true;
      $message = 'Successfully updated the profile!';
    } else {
      $failure = true;
      $message = 'Error! Something went wrong, Please try again . ';
    }
  }
  $records = $conn->prepare('SELECT * FROM users WHERE id = :id');
  $records->bindParam(':id', $_SESSION['user_id']);
  $records->execute();
  $results = $records->fetch(PDO::FETCH_ASSOC);
  $role = null;
  if ($results['security'] == 0) {
    $role = "Super Admin";
  } else if ($results['security'] == 1) {
    $role = "Project Director";
  } else if ($results['security'] == 2) {
    $role = "Archivists/Researcher";
  } else {
    $role = "Unknown";
  }


  require_once 'header.php';
?>
<div class="main-content">
  <div class="container">
    <div class="main-body">
      <div class="row">
        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <div class="d-flex flex-column align-items-center text-center">
                <img src="assets/img/users/<?php echo $results['img'] ? $results['img'] : 'NA.jpg';?>" alt="User Image"
                     class="rounded-circle p-1 bg-primary" width="110">
                <div class="mt-3">
                  <h4><?php echo $results['fname'] . " " . $results['lname']; ?></h4>
                  <p class="text-secondary mb-1"><?php echo $role; ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-8">
          <div class="card">
            <div class="card-body">
              <?php if ($failure) { ?>
                <div class="alert alert-danger" role="alert">
                  <?php echo $message; ?>
                </div>
              <?php } ?>
              <?php if ($success) { ?>
                <div class="alert alert-success" role="alert">
                  <?php echo $message; ?>

                </div>
              <?php } ?>
              <form action="" method="post" enctype="multipart/form-data">
                <div class="row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">First Name</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" name="fname" id="fname" class="form-control"
                           value="<?= $results['fname'] ?>">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Last Name</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" name="lname" id="lname" class="form-control"
                           value="<?= $results['lname'] ?>">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Email</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" id="email" name="email" class="form-control"
                           value="<?= $results['email'] ?>">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Organization</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="text" name="organization" id="organization" class="form-control"
                           value="<?= $results['Organization'] ?>">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-sm-3">
                    <h6 class="mb-0">Image</h6>
                  </div>
                  <div class="col-sm-9 text-secondary">
                    <input type="file" name="user_image" id="user_image" class="form-control" value="<?= $results['img'] ?>"/>

                  </div>
                </div>

                <div class="row">
                  <div class="col-sm-3"></div>
                  <div class="col-sm-9 text-secondary">
                    <input type="submit" name="submit" id="submit" class="btn btn-primary px-4"
                           value="Save Changes">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
  require_once("footer.php")
?>
