<?php
  session_start();
  $role = $_SESSION['role'];
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once 'header.php';
  require_once("db_files/db_config.php");
  $tname = null ;
  var_dump($_POST);
  if (isset($_POST['edit_CV'])) {
    $id = $_POST['id'];
    $tname = $_POST['tname'];
    $query = $conn->query("SELECT * FROM `" .$tname."` where ID = " . $id);
    //var_dump($query);
    $temp = $query->fetch(PDO::FETCH_ASSOC);

  }
  if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $cvname = $_POST['cvname'];
    $fname = $_POST['fname'];
    $sname = $_POST['sname'];
    $pname = $_POST['pname'];
    $tname = $_POST['tname'];
    if (isset($_POST['status'])) {
      $sql = "Update" . $tname ."set
      			Name = :Name,
                Name_-_In_French = :Name_-_In_French,
                Name_-_In_Spanish = :Name_-_In_Spanish,
                Name_-_In_Portuguese = :Name_-_In_Portuguese,
                Status = :Status          
                WHERE ID = :ID ";

    }

    $sql = "UPDATE $tname set
      			Name = :Name,
                Name_-_In_French = :Name_-_In_French,
                Name_-_In_Spanish = :Name_-_In_Spanish,
                Name_-_In_Portuguese = :Name_-_In_Portuguese           
                WHERE ID = :ID ";
    var_dump($sql);

    $pst = $conn->prepare($sql);
    var_dump(':Name', $cvname);
    $pst->bindParam(':Name', $cvname);
    var_dump(':Name_-_In_French', $fname);
    $pst->bindParam(':Name_-_In_French', $fname);
    var_dump(':Name_-_In_Spanish', $sname);
    $pst->bindParam(':Name_-_In_Spanish', $sname);
    var_dump(':Name_-_In_Portuguese', $pname);
    $pst->bindParam(':Name_-_In_Portuguese', $pname);
    var_dump(':ID', $id);
    $pst->bindParam(':ID', $id, PDO::PARAM_INT);

    if (isset($_POST['status'])) {
      $pst->bindParam(':Status', $_POST['status']);
    }
    $count = $pst->execute();


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
//var_dump($temp);

  require_once 'header.php';
?>
<div class="main-content">
  <div class="container">
    <div class="main-body">
      <div class="row">
        <div class="col-lg-12">
          <h3 class="title-5 m-b-35">Edit Controlled Vocabularies Data</h3>
          <div class="card">
            <div class="card-body">
              <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                <div class="row form-group">
                  <div class="col col-md-3">
                    <input type="hidden" name="id" value="<?= $temp['ID']; ?>"/>
                    <input type="hidden" name="tname" value="<?= $tname; ?>"/>
                  </div>
                </div>

                <div class="row form-group">
                  <div class="col col-md-3">
                    <label for="cvname" class=" form-control-label">Name</label>
                  </div>
                  <div class="col-12 col-md-9">
                    <input name="cvname" id="cvname" placeholder="Name"
                           class="form-control" value="<?= $temp['Name']; ?>">
                  </div>
                </div>
                <div class="row form-group">
                  <div class="col col-md-3">
                    <label for="fname" class=" form-control-label">French Name</label>
                  </div>
                  <div class="col-12 col-md-9">
                    <input name="fname" id="fname" placeholder="French Name"
                           class="form-control" value="<?= $temp['Name_-_In_French']; ?>">
                  </div>
                </div>
                <div class="row form-group">
                  <div class="col col-md-3">
                    <label for="sname" class=" form-control-label">Spanish Name</label>
                  </div>
                  <div class="col-12 col-md-9">
                    <input name="sname" id="sname" placeholder="Spanish Name"
                           class="form-control" value="<?= $temp['Name_-_In_Spanish']; ?>">
                  </div>
                </div>
                <div class="row form-group">
                  <div class="col col-md-3">
                    <label for="pname" class=" form-control-label">Portuguese Name</label>
                  </div>
                  <div class="col-12 col-md-9">
                    <input name="pname" id="pname" placeholder="Portuguese Name"
                           class="form-control" value="<?= $temp['Name_-_In_Portuguese']; ?>">
                  </div>
                </div>
                <?php if($role == 'Project Director'){?>
                  <div class="row form-group">
                    <div class="col col-md-3">
                      <label for="status" class=" form-control-label">Status</label>
                    </div>
                    <div class="col-12 col-md-9">
                      <select id="status" name="status" class="form-control">
                        <?php
                          if ($temp['Status'] == 0) {
                            $st = "Pending";
                            $stn = 0;
                          } else if ($temp['Status'] == 1) {
                            $st = "Approved";
                            $stn = 1;
                          } else {
                            $st = "Unknown";
                            $stn = " ";
                          }
                        ?>
                        <option value="<?= $stn; ?>" selected><?= $st; ?></option>
                        <option value="0">Pending</option>
                        <option value="1">Approved</option>
                      </select>
                    </div>
                  </div>
                <?php } ?>


                <div class="card-footer">
                  <button type="submit" name="submit" id="submit" data-toggle="modal" class="btn btn-primary btn-sm">
                    <i class="fa fa-dot-circle-o"></i> Submit
                  </button>
                  <button type="reset" class="btn btn-danger btn-sm">
                    <i class="fa fa-ban"></i> Reset
                  </button>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="success-dialog" tabindex="-1" role="dialog" data-backdrop="false"
                     aria-labelledby="success-dialog" aria-hidden="true">
                  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                    <div class="modal-content  bg-success">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">CV Updated</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body text-dark">
                        <p>CV data has been updated!</p>
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
                        <p>Sorry, there is an error in updating!</p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" onclick="location.href = 'controlled_vocabularies.php';" class="btn bg-dark text-white"
                                data-dismiss="modal">Close
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

            </div>
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
