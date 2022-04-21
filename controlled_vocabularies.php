
<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once('header.php');
  require_once("db_files/db_config.php");

?>
<?php //phpinfo(); ?>
<?php
  $sql = "SELECT SL_Objects.* FROM `SL_Objects`";
  $query = $conn->query($sql);
  $resultsH = array();
  $rowsCV = array();
  $colNames = array();
  $CVColNames = array();
  $CVDisplay = array();
  $CVColTypes = array();
  $STerms = array();
  $c1 = 0;
  while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $resultsH[] = $row;
    $colNames[] = $row['ColumnName'];
    if ($row['FieldType'] == "dropdown-CV") {
      $rowsCV[] = $row;
      $CVColNames[$c1] = $row['ColumnName'];
      $CVDisplay[$c1] = $row['display'];
      $CVColTypes[$c1] = $row['FieldType'];
      $STerms[$c1] = "#sTerm" . $row['ColumnName'];
      $c1 = $c1 + 1;
    }
  }?>
<!-- Find the headers -->
<?php
  $lnH = count($resultsH);
  $rheaders = array();
  for($i=0; $i<$lnH; $i++):
    $temp = $resultsH[$i];
    $colName = $temp['ColumnName'];
    $rheaders[$colName] = $temp['display'];
  endfor;
?>
<!-- Find the controlled vocabularies -->
<?php
  $controlledVocab = array();
  $lnCV = count($rowsCV);
  for ($i = 0; $i < $lnCV; $i++) {
    $temp = $rowsCV[$i];
    $colName = $temp['ColumnName']; // Get the field name for our array key
    if ($temp['FieldType'] != "radio") {
      $cvTable = $temp['Options']; // Get the table name for the new query
      // Fetch the CV from each table
      $sql = "SELECT * FROM " . $cvTable;
      $query = $conn->query($sql);
      $tempCV = array();
      while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $tempCV[] = $row;
      }
      $controlledVocab[] = array($colName => $tempCV); // combine CV data with field as array key
    } else {
      $controlledVocab[] = array($colName => $temp);
    }
  }
  // Prep controlled vocabularies
  $cvCount = count($controlledVocab);
  $FieldNumCVs = array();
  for ($i = 0; $i < count($CVColNames); $i++):
    $FieldNumCVs[$i] = '$' . $CVColNames[$i] . 'CVs = array();';
  endfor;
  for ($i = 0; $i < $cvCount; $i++) {
    $cv = $controlledVocab[$i];
    for ($j = 0; $j < count($CVColNames); $j++):
      if (isset($cv[$CVColNames[$i]])) {
        $FieldNumCVs[$i] = $cv[$CVColNames[$i]];
      }
    endfor;
  }
  ?>
<!-- MAIN CONTENT-->
<div class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <h3 class="title-5 m-b-35">Controlled Vocabularies </h3>
          <div class="table-data__tool">
            <div class="table-data__tool-left">

            </div>
            <div class="table-data__tool-right">
              <a href="add_CV_table.php" class="au-btn au-btn-icon au-btn--green au-btn--small">
                <i class="zmdi zmdi-plus"></i>add New</a>
            </div>
          </div>
        </div>
      </div>

      <div class="row">

          <!-- DATA TABLE -->
          <?php
            $lnH = count($rowsCV);
            for ($i = 0; $i < $lnH; $i++):
            $tempH = $rowsCV[$i]; ?>

          <div class="col-md-4">
            <aside class="profile-nav alt">
              <section class="card">
                <div class="card-header user-header alt bg-dark">
                  <div class="media">
                    <div class="media-body">
                      <h2 class="text-light display-6"><?php echo htmlspecialchars_decode($tempH['display']); ?></h2>
                    </div>
                    <form class="float-right m-r-10" action="delete_CV_table.php" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="id" value="<?php echo $tempH['id'] ?>"/>
                      <input type="hidden" name="tname" value="<?php echo $tempH['Options']; ?>"/>
                      <button class="item mr-2" name="delete_CV_table" data-toggle="tooltip" data-placement="top"
                              title="Delete CV Table">
                        <i class="zmdi zmdi-delete"></i>
                      </button>
                    </form>
                  </div>
                </div>


                <ul class="list-group list-group-flush">
                  <?php
                    $arrln = count($FieldNumCVs[$i]);
                    for ($j = 0; $j < $arrln; $j++):
                      $temp1 = $FieldNumCVs[$i][$j];
                      $id = $temp1['ID'];
                      $name = $temp1['Name'];
                      //echo $id;
                    ?>
                      <li class="list-group-item">
                        <a href="#">
                          <?php echo htmlspecialchars_decode($name); ?>

                        </a>
                        <form class="float-right" action="edit_CV.php" method="post" enctype="multipart/form-data">
                          <input type="hidden" name="id" value="<?php echo $temp1['ID']; ?>"/>
                          <input type="hidden" name="tname" value="<?php echo $tempH['Options']; ?>"/>
                          <button class="item mr-2" name="edit_CV" data-toggle="tooltip" data-placement="top"
                                  title="Edit CV">
                            <i class="zmdi zmdi-edit"></i>
                          </button>
                        </form>
                        <form class="float-right m-r-10" action="delete_CV.php" method="post" enctype="multipart/form-data">
                          <input type="hidden" name="id" value="<?php echo $temp1['ID'] ?>"/>
                          <input type="hidden" name="tname" value="<?php echo $tempH['Options']; ?>"/>
                          <button class="item mr-2" name="delete_CV" data-toggle="tooltip" data-placement="top"
                                  title="Delete CV">
                            <i class="zmdi zmdi-delete"></i>
                          </button>
                        </form>
                      </li>
                  <?php endfor; ?>
                  <li class="list-group-item au-btn--green">
                    <form class="text-center" action="add_CV.php" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="tname" value="<?php echo $tempH['Options']; ?>"/>
                      <button class="item mr-2" name="add_CV" data-toggle="tooltip" data-placement="top"
                              title="Add CV">
                        <i class="zmdi zmdi-plus-circle m-r-10"></i>ADD
                      </button>
                    </form>
                  </li>
                </ul>

              </section>
            </aside>
          </div>
            <?php endfor; ?>

          <!-- END DATA TABLE -->

      </div>


      <?php require_once 'footer.php' ?>
