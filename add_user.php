<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once 'header.php';
  require_once("db_files/db_config.php");
  $count = null;
  $success = false;
  $failure = false;
  $message = '';
    if (isset($_POST['submit']) and $_SESSION['role'] == "Super Admin") {
    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $organization = $_POST['organization'];
    $role = $_POST['role'];
    $sql = "INSERT INTO users (email, fname, lname, security, Organization) 
              VALUES ( :email, :fname, :lname, :security, :Organization) ";
    $pst = $conn->prepare($sql);
    $pst->bindParam(':email', $email);
    $pst->bindParam(':fname', $fname);
    $pst->bindParam(':lname', $lname);
    $pst->bindParam(':security', $role);
    $pst->bindParam(':Organization', $organization);
    $count = $pst->execute();
    if ($count) {
      $success = true;

      //header("Refresh:1;url=index.php");
      //header('Location: index.php');
      $message = 'User has been added to the system. ';
    } else {
      $failure = true;
      $message = 'Error occured while adding the user.';
    }
  }
  require_once 'header.php';
?>


  <!-- MAIN CONTENT-->
  <div class="main-content">
  <div class="section__content section__content--p30">
  <div class="container-fluid">

  <div class="row">
    <div class="col-lg-12">
      <?php if ($_SESSION['role'] == "Super Admin") {?>
      <div class="card">
        <div class="card-header">
          <strong>Add User</strong>
        </div>

        <div class="card-body card-block">
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
          <form action="" method="post" name="form_user" enctype="multipart/form-data" class="form-horizontal">

            <div class="row form-group">
              <div class="col col-md-3">
                <label for="email" class=" form-control-label">Email</label>
              </div>
              <div class="col-12 col-md-9">
                <input type="text" id="email" name="email" placeholder="Email"
                       value="<?= isset($email) ? $email : ''; ?>" class="form-control">
              </div>
            </div>


            <div class="row form-group">
              <div class="col col-md-3">
                <label for="fname" class=" form-control-label">First Name</label>
              </div>
              <div class="col-12 col-md-9">
                <input type="text" id="fname" name="fname" placeholder="First Name"
                       value="<?= isset($fname) ? $fname : ''; ?>" class="form-control">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="lname" class=" form-control-label">Last Name</label>
              </div>
              <div class="col-12 col-md-9">
                <input type="text" id="lname" name="lname" placeholder="Last Name"
                       value="<?= isset($lname) ? $lname : ''; ?>" class="form-control">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="organization" class=" form-control-label">Organization</label>
              </div>
              <div class="col-12 col-md-9">
                <input name="organization" id="organization" placeholder="Organization"
                       class="form-control" value="<?= isset($organization) ? $organization : ''; ?>">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="role" class=" form-control-label">Role</label>
              </div>
              <div class="col-12 col-md-9">
                <select id="role" name="role" class="form-control">
                  <option value="0">Super Admin</option>
                  <option value="1">Project Director</option>
                  <option value="2">Archivists/Researcher</option>
                </select>
              </div>
            </div>

            <div class="card-footer">
              <button type="submit" name="submit" id="submit"
                      class="btn btn-primary btn-sm">
                <i class="fa fa-dot-circle-o"></i> Submit
              </button>
              <button type="reset" class="btn btn-danger btn-sm">
                <i class="fa fa-ban"></i> Reset
              </button>
            </div>
        </div>
        <?php } else {?>
          <div class="alert alert-danger" role="alert">
            <p><i class="fas fa-ban text-left"></i> Sorry, you don't have access to this page!!</p>
          </div>
        <?php } ?>

      </div>
    </div>

  </div>
  <script>
      $(document).ready(function () {
          var formHandle = document.forms.form_user;
          formHandle.onsubmit = processForm;
          function processForm(){
              var fname = formHandle.fname;
              var lname = formHandle.lname;
              var email = formHandle.email;
              var org = formHandle.organization;
              var role = formHandle.role;
              console.log(fname.value + lname.value + email.value + org.value + role.value);

              //Validation
              if(email.value==="")
              {
                  $('#email').addClass('is-invalid');
                  email.focus();
                  return false;
              }
              if(fname.value==="")
              {
                  $('#fname').addClass('is-invalid');
                  fname.focus();
                  return false;
              }
              if(lname.value==="")
              {
                  $('#lname').addClass('is-invalid');
                  lname.focus();
                  return false;
              }

              if(org.value==="")
              {
                  $('#org').addClass('is-invalid');
                  org.focus();
                  return false;
              }
              if(role.value==="")
              {
                  $('#role').addClass('is-invalid');
                  role.focus();
                  return false;
              }

          }

      });
  </script>
<?php require_once 'footer.php' ?>