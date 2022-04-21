<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once 'header.php';
  require_once("db_files/db_config.php");
  $tname = null ;
  if (isset($_POST['delete_CV'])) {
    $id = $_POST['id'];
    $tname = $_POST['tname'];
    $query = $conn->query("SELECT * FROM `".$tname."` where ID = " . $id);
    $temp = $query->fetch(PDO::FETCH_ASSOC);
  }
  if (isset($_POST['deleteconfirm'])) {
    $id = $_POST['id'];
    $tname = $_POST['tname'];
    $sql = "DELETE FROM `".$tname."` where ID = " . $id;
    $count = $conn->exec($sql);
    if ($count) {
      echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#success-dialog").modal("show");
			});
				</script>';
    } else {
      echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#error-dialog").modal("show");
			});
				</script>';
    }
  }
  if (isset($_POST['close'])) {
    header("Location: controlled_vocabularies.php");
  }
  require_once 'header.php';
?>

  <!-- MAIN CONTENT-->
  <div class="main-content">
  <div class="col-md-12">

  <div class="card">
    <div class="card-header">
      <strong class="card-title">Confirm Delete</strong>
    </div>
    <div class="card-body">
      <form method="post">
        <div class="sufee-alert alert with-close alert-danger alert-dismissible fade show">
          Are you sure you want to delete this CV record?</br>
          You can't get this form back.</br>
          <div class="mt-2">
            <button name="deleteconfirm" class="btn btn-danger btn-sm">Delete</button>
            <button name="close" class="btn btn-primary btn-sm">Cancel</button>
          </div>
        </div>
        <input type="hidden" name="id" value="<?= $temp['ID']; ?>"/>
        <input type="hidden" name="tname" value="<?php echo $tname; ?>"/>
        <p><?php echo $temp['Name']; ?></p>
        <!-- Modal -->
        <div class="modal fade" id="success-dialog" tabindex="-1" role="dialog" data-backdrop="false"
             aria-labelledby="success-dialog" aria-hidden="true">
          <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content  bg-success">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Removed CV data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-dark">
                <p>CV data has been removed from system!</p>
              </div>
              <div class="modal-footer">
                <button type="button" onclick="location.href = 'controlled_vocabularies.php';" class="btn bg-dark text-white"
                        data-dismiss="modal">Close
                </button>
              </div>
            </div>
          </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="error-dialog" tabindex="-1" role="dialog" data-backdrop="false"
             aria-labelledby="error-dialog" aria-hidden="true">
          <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
            <div class="modal-content  bg-danger">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body text-dark">
                <p>Sorry, there is an error in removing CV data from system!</p>
              </div>
              <div class="modal-footer">
                <button type="button" onclick="location.href = 'controlled_vocabularies.php';" class="btn bg-dark text-white"
                        data-dismiss="modal">Close
                </button>
              </div>
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
<?php require_once 'footer.php' ?>