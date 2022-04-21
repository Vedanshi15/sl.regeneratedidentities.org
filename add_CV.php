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
  if (isset($_GET['message'])) {
    $message = "Please try again.";
  }
  if (isset($_POST['submit']) and $_SESSION['role'] == "Super Admin") {
    $cvname = $_POST['cvname'];
    $fname = $_POST['fname'];
    $msg = $_POST['msg'];
    $sname = $_POST['sname'];
    $pname = $_POST['pname'];
    $tname = $_POST['tname'];
    $sql = "INSERT INTO ".$tname." (Status, Message, Submitby, Name, Name_-_In_French, Name_-_In_Spanish, Name_-_In_Portuguese) 
              VALUES (0, :Message, :Submitby, :Name, :Name_-_In_French, :Name_-_In_Spanish, :Name_-_In_Portuguese) ";
    $pst = $conn->prepare($sql);
    $pst->bindParam(':Message', $msg);
    $pst->bindParam(':Submitby', $_SESSION['user_name']);
    $pst->bindParam(':Name', $cvname);
    $pst->bindParam(':Name_-_In_French', $fname);
    $pst->bindParam(':Name_-_In_Spanish', $sname);
    $pst->bindParam(':Name_-_In_Portuguese', $pname);
    $count = $pst->execute();
    if ($count) {
      $success = true;

      //header("Refresh:1;url=index.php");
      //header('Location: index.php');
      $message = 'CV data has been added to the system. ';
    } else {
      $failure = true;
      $message = 'Error occured while adding the CV data.';
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
          <strong>Add CV Data</strong>
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
              <script type="text/javascript">
                  const url = "add_user.php";
                  window.location.href=url;
              </script>
            </div>
          <?php } ?>
          <form action="" method="post" name="form_user" enctype="multipart/form-data" class="form-horizontal">
            <div class="row form-group">
              <div class="col col-md-3">
                <input type="hidden" name="id" value="<?= isset($temp['ID']) ? $temp['ID'] : ''; ?>"/>
                <input type="hidden" name="tname" value="<?= isset($tname) ? $tname : ''; ?>"/>
              </div>
            </div>

            <div class="row form-group">
              <div class="col col-md-3">
                <label for="cvname" class=" form-control-label">Name</label>
              </div>
              <div class="col-12 col-md-9">
                <input name="cvname" id="cvname" placeholder="Name"
                       class="form-control" value="<?= isset($temp['Name']) ? $temp['Name'] : ''; ?>">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="msg" class=" form-control-label">Message</label>
              </div>
              <div class="col-12 col-md-9">
                <input name="msg" id="msg" placeholder="Message"
                       class="form-control" value="<?= isset($temp['Message']) ? $temp['Message'] : ''; ?>">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="fname" class=" form-control-label">French Name</label>
              </div>
              <div class="col-12 col-md-9">
                <input name="fname" id="fname" placeholder="French Name"
                       class="form-control" value="<?= isset($temp['Name_-_In_French']) ? $temp['Name_-_In_French'] : ''; ?>">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="sname" class=" form-control-label">Spanish Name</label>
              </div>
              <div class="col-12 col-md-9">
                <input name="sname" id="sname" placeholder="Spanish Name"
                       class="form-control" value="<?= isset($temp['Name_-_In_Spanish']) ? $temp['Name_-_In_Spanish'] : ''; ?>">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="pname" class=" form-control-label">Portuguese Name</label>
              </div>
              <div class="col-12 col-md-9">
                <input name="pname" id="pname" placeholder="Portuguese Name"
                       class="form-control" value="<?= isset($temp['Name_-_In_Portuguese']) ? $temp['Name_-_In_Portuguese'] : ''; ?>">
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

            <!-- Modal -->
            <div class="modal fade" id="success-dialog" tabindex="-1" role="dialog" aria-labelledby="success-dialog"
                 aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">User Added</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p> User has been added to the system.</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" onclick="location.href = 'users.php';" class="btn btn-secondary"
                            data-dismiss="modal">Close
                    </button>
                  </div>
                </div>
              </div>
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