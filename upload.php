<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once('header.php');
  require_once("db_files/db_config.php");
  $success = false;
  $failure = false;
  if (isset($_GET['message'])) {
    $message = "Invalid Credentials! Please try again.";
  }
  if (isset($_POST['submit'])) {
    var_dump($_POST);
    foreach ($_FILES["file"]["tmp_name"] as $key => $tmp_name) {
      $ui_id = 0;
      $file = null;
      //$fn = $_FILES['file']['name'][$key];
      //var_dump($_FILES['file']['name'][$key]."file size");
      //var_dump(filesize($fn)."file size");
      $query = $conn->query("SELECT MAX(objectID) as id FROM `object`");
      if ($query->rowCount()) {
        $r = $query->fetchAll(PDO::FETCH_OBJ);
        $result = $r[0];
        $ui_id = $result->id;
        if (is_numeric($ui_id)) {
          $ui_id = sprintf("%06d", $ui_id);
        } else {
          $ui_id = 0;
        }
      }
      $ui_id = "SL" . $ui_id;
      var_dump("uid " . $ui_id);
      $target_dir = 'DataFiles/';

      $ext = pathinfo($_FILES['file']['name'][$key], PATHINFO_EXTENSION);
      $allowed = array('jpg', 'jpeg', 'png', 'tiff', 'gif', 'pdf');
      if (in_array($ext, $allowed)) {
        $target_file = $target_dir . basename($ui_id . '.' . $ext);
        $file = $ui_id . '.' . $ext;
        //$filename = $_FILES['file']['name'][$key];
        //echo $_FILES["file"]["size"].'f sz';
        $file_link = "http://sl.regeneratedidentities.org/Datafiles/" . $file;
        var_dump('filedata ' . $file_link . 'filnam' . $file);
        $sql = "INSERT INTO `object`(Status, UI, File) VALUES (0,'$ui_id','$file_link')";
        var_dump($sql);
        $conn->query($sql);
        move_uploaded_file($_FILES['file']['tmp_name'][$key], 'DataFiles/' . $file);
        //move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)
        $success = true;
        $message = 'Successfully Uploaded..... ';
      } else {
        $failure = true;
        $message = 'Error! You can not upload this file type. ';
      }
    }
  }

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

              <form action="" method="post" id="fileForm" enctype="multipart/form-data">
                <button class="btn btn-danger" id="resetFileForm">
                  <i class="zmdi zmdi-format-clear-all"></i> Clear
                </button>
                <input class="btn btn-success" name='submit' value="Start Uploading" type="submit">
            </div>
          </div>
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
          <div class="form-group">

            <input type="file" name="file[]" id="file" multiple hidden>
          </div>
          <div class="overview-item overview-item--c4">
            <div class="overview__inner text-center">
              <div class="overview-box clearfix p-b-15">


                <div class="dashboard-card-titles">
                  <label style="color: white; font-size: 15px" for="file">
                    <div class="icon">
                      <i class="zmdi zmdi-file-add"></i>
                    </div>
                  </label>
                </div>
                <span id="file-chosen"></span>
              </div>
            </div>
          </div>

          </form>


          <!-- END DATA TABLE -->
        </div>
      </div>
      <script>
          const actualBtn = document.getElementById('file');

          const fileChosen = document.getElementById('file-chosen');
          var temp;
          actualBtn.addEventListener('change', function () {
              for (i = 0; i < this.files.length; i++) {
                  fileChosen.innerHTML += this.files[i].name + '<br/>';

              }

          })
      </script>

      <?php require_once 'footer.php' ?>
