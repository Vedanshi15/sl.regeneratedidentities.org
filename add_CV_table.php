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

    $SectionDisplay = $_POST['SectionDisplay'];
    $section = $_POST['section'];
    $ColumnName = $_POST['ColumnName'];
    $FieldType = $_POST['FieldType'];
    $Options = $_POST['Options'];
    $display = $_POST['display'];
    //$status = $_POST['status'];
    $indexpage = $_POST['indexpage'];
    $sql = "INSERT INTO SL_Objects (SectionDisplay, section, ColumnName, FieldType, Options, display, status, indexpage) 
              VALUES ( :SectionDisplay, :section, :ColumnName, :FieldType, :Options, :display, 0, :indexpage) ";
    $pst = $conn->prepare($sql);
    $pst->bindParam(':SectionDisplay', $SectionDisplay);
    $pst->bindParam(':section', $section);
    $pst->bindParam(':ColumnName', $ColumnName);
    $pst->bindParam(':FieldType', $FieldType);
    $pst->bindParam(':Options', $Options);
    $pst->bindParam(':display', $display);
    $pst->bindParam(':indexpage', $indexpage);
    $count = $pst->execute();
    if ($_POST['FieldType'] == "dropdown-CV") {
      $sql1 = "CREATE TABLE " . $_POST['Options'] . " (
        ID INT(100) AUTO_INCREMENT PRIMARY KEY,
        listorder int(255) NOT NULL, 
        Status varchar(1000) NOT NULL, 
        Message varchar(1000) NOT NULL, 
        Submitby varchar(1000) NOT NULL,
        Name varchar(1000) NOT NULL, 
        Name_-_In_French varchar(1000) NOT NULL, 
        Name_-_In_Spanish varchar(1000) NOT NULL, 
        Name_-_In_Portuguese varchar(1000) NOT NULL)";
      var_dump($sql1);
      $count1 = $conn->exec($sql1);
      var_dump($count1);
      if ($count == true && $count1 == true) {
        $success = true;

        //header("Refresh:1;url=index.php");
        //header('Location: index.php');
        $message = 'CV table has been added to the system. ';
      } else {
        $failure = true;
        $message = 'Error occured while adding the CV table.';
      }
    } else {
      if ($count == true) {
        $success = true;

        //header("Refresh:1;url=index.php");
        //header('Location: index.php');
        $message = 'CV table has been added to the system. ';
      } else {
        $failure = true;
        $message = 'Error occured while adding the CV table.';
      }
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
      <?php if ($_SESSION['role'] == "Super Admin") { ?>
      <div class="card">
        <div class="card-header">
          <strong>Add CV Table</strong>
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
                  const url = "add_CV_table.php";
                  window.location.href = url;
              </script>
            </div>
          <?php } ?>
          <form action="" method="post" name="form_CV_table" enctype="multipart/form-data" class="form-horizontal">

            <div class="row form-group">
              <div class="col col-md-3">
                <label for="SectionDisplay" class=" form-control-label">Section Display</label>
              </div>
              <div class="col-12 col-md-9">
                <input type="text" id="SectionDisplay" name="SectionDisplay" placeholder="Section Display"
                       value="<?= isset($SectionDisplay) ? $SectionDisplay : ''; ?>" class="form-control">
              </div>
            </div>


            <div class="row form-group">
              <div class="col col-md-3">
                <label for="section" class=" form-control-label">Section</label>
              </div>
              <div class="col-12 col-md-9">
                <input type="text" id="section" name="section" placeholder="Section"
                       value="<?= isset($section) ? $section : ''; ?>" class="form-control">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="ColumnName" class=" form-control-label">Column Name</label>
              </div>
              <div class="col-12 col-md-9">
                <input type="text" id="ColumnName" name="ColumnName" placeholder="Column Name"
                       value="<?= isset($ColumnName) ? $ColumnName : ''; ?>" class="form-control">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="FieldType" class=" form-control-label">FieldT ype</label>
              </div>
              <div class="col-12 col-md-9">
                <select id="FieldType" name="FieldType" class="form-control">
                  <option value="text">Text</option>
                  <option value="textarea">Textarea</option>
                  <option value="dropdown-CV">Dropdown CV</option>
                  <option value="radio">Radio</option>
                  <option value="dropdown">Dropdown</option>
                </select>
              </div>
            </div>

            <div class="row form-group">
              <div class="col col-md-3">
                <label for="display" class=" form-control-label">Display</label>
              </div>
              <div class="col-12 col-md-9">
                <input name="display" id="display" placeholder="Display"
                       class="form-control" value="<?= isset($display) ? $display : ''; ?>">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="Options" class=" form-control-label">Table Name</label>
              </div>
              <div class="col-12 col-md-9">
                <input name="Options" id="Options" placeholder="Table Name"
                       class="form-control" value="<?= isset($Options) ? $Options : ''; ?>">
              </div>
            </div>
            <div class="row form-group">
              <div class="col col-md-3">
                <label for="indexpage" class=" form-control-label">Index of page</label>
              </div>
              <div class="col-12 col-md-9">
                <input name="indexpage" id="indexpage" placeholder="Index of page"
                       class="form-control" value="<?= isset($indexpage) ? $indexpage : ''; ?>">
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
                    <h5 class="modal-title" id="exampleModalLongTitle">CV Table Added</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <p> CV Table has been added to the system.</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" onclick="location.href = 'add_CV_table.php';" class="btn btn-secondary"
                            data-dismiss="modal">Close
                    </button>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <?php } else { ?>
          <div class="alert alert-danger" role="alert">
            <p><i class="fas fa-ban text-left"></i> Sorry, you don't have access to this page!!</p>
          </div>
        <?php } ?>

      </div>
    </div>

  </div>
  <script>
      $(document).ready(function () {
          var formHandle = document.forms.form_CV_table;
          formHandle.onsubmit = processForm;

          function processForm() {
              var SectionDisplay = formHandle.SectionDisplay;
              var section = formHandle.section;
              var ColumnName = formHandle.ColumnName;
              var FieldType = formHandle.FieldType;
              var Options = formHandle.Options;
              var display = formHandle.display;
              var indexpage = formHandle.indexpage;
              //console.log(SectionDisplay.value + lname.value + email.value + org.value + role.value);

              //Validation
              if (SectionDisplay.value === "") {
                  $('#SectionDisplay').addClass('is-invalid');
                  SectionDisplay.focus();
                  return false;
              }
              if (section.value === "") {
                  $('#section').addClass('is-invalid');
                  section.focus();
                  return false;
              }
              if (ColumnName.value === "") {
                  $('#ColumnName').addClass('is-invalid');
                  ColumnName.focus();
                  return false;
              }

              if (FieldType.value === "") {
                  $('#FieldType').addClass('is-invalid');
                  FieldType.focus();
                  return false;
              }
              if (Options.value === "") {
                  $('#Options').addClass('is-invalid');
                  Options.focus();
                  return false;
              }
              if (display.value === "") {
                  $('#display').addClass('is-invalid');
                  display.focus();
                  return false;
              }
              if (indexpage.value === "") {
                  $('#indexpage').addClass('is-invalid');
                  indexpage.focus();
                  return false;
              }
              var numbers = /^[0-9]+$/;
              if (indexpage.value.match(numbers)) {
              } else {
                  alert('Please input numeric characters only');
                  indexpage.focus();
                  return false;
              }

          }

      });
  </script>
<?php require_once 'footer.php' ?>