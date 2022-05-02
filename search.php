<?php
  error_reporting(0);
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once 'header.php';
  require_once("db_files/db_config.php");

?>
<section class="main-content">
  <div class="section__content section__content--p30">
    <div class="container-fluid">
      <div class="row">
         <div class="col-md-12">
           <h3 class="title-5 m-b-35">Search Metatags</h3>
            <div class="card">

              <div class="card-body" id="body-container">
        <div class="row">
          <div class="col-12 text-left">
            <?php

              if (empty($_GET['page_n'])) {
                $page_n = 1;
              } else {
                $page_n = $_GET['page_n'];
              }
              $rowLimit = 10;
              $offset = $rowLimit * ($page_n - 1);
              $fromRow = (($rowLimit) * ($page_n - 1)) + 1; // Calculate the starting number
              $toRow = ($page_n * $rowLimit);
            ?>
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
                if (($row['FieldType'] == "dropdown-CV") || ($row['FieldType'] == "radio")) {
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
              }?>
            <div class="row m-b-30"  id="restartRegularSearch">
              <div class="col-12 text-left">
                <a href="search.php" class="btn btn-dark mb-2"><i
                    class="fas fa-sync-alt"></i> Refresh Page</a>
              </div>
            </div>
            <div id="pageSearchMenu" class="row">
              <div class="col-12 text-right">
                <a href="advance_search.php " class="btn overview-item--c1 mb-3 text-white" style="padding-left:30px; padding-right:30px;"><i
                    class="fas fa-search-plus"></i> Advanced Search</a>
              </div>
            </div>
          </div>
        </div>
        <div id="pageEnd">
          <!--<button type="submit" formmethod="post">Submit using POST</button>-->
          <form action="search.php" method="GET">
            <div class="row p-t-10">
              <div class="col-12 col-md-6 col-lg-4">
                <!--Column Name Select-->
                <div class="form-group mb-3">
                  <label class="sLabels text-uppercase" for="colName">Select the Column:</label>
                  <div id="search-autocomplete" class="select-container">
                    <select id="colName" name="colName" class="form-select form-select-lg mb-3 mt-2 form-control select2" aria-label=".form-select-lg">
                      <!--<option selected>Select Fields...</option>-->
                      <?php
                        $lnH = count($resultsH);
                        for ($i = 0; $i < $lnH; $i++):
                          $tempH = $resultsH[$i]; ?>
                          <option id="<?php echo htmlspecialchars_decode($tempH['ColumnName']); ?>" class="selected-item" value="<?php echo htmlspecialchars_decode($tempH['ColumnName']); ?>"><?php echo htmlspecialchars_decode($tempH['display']); ?></option>
                          <?php if ($i == 0): ?>
                          <option id="UI" value="UI" class="selected-item">UI</option>
                        <?php endif; ?>
                        <?php endfor; ?>
                      <option id="All" class="selected-item">All Fields</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <!--Query Condition Select-->
                <div class="form-group mb-3">
                  <label class="sLabels text-uppercase" for="cond">Select condition:</label>
                  <div class="select-container">
                    <select id="SelectID" name="cond" class="form-select form-select-lg mb-3 mt-2 form-control"
                            aria-label=".form-select-lg">
                      <!--<option selected>Select Condition...</option>-->
                      <option class="selected-item" id="1" value="1">equals</option>
                      <option class="selected-item" id="2" value="2">not equals</option>
                      <option class="selected-item" id="3" value="3">begins with</option>
                      <option class="selected-item" id="4" value="4">does not begin with</option>
                      <option class="selected-item" id="5" value="5">ends with</option>
                      <option class="selected-item" id="6" value="6">contains</option>
                      <option class="selected-item" id="7" value="7">does not contain</option>
                      <option class="selected-item" id="8" value="8">is blank</option>
                      <option class="selected-item" id="9" value="9">is not blank</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-12 col-lg-5">
                <!--Search Terms Select-->
                <div class="form-group">
                  <label class="sLabels text-uppercase" for="sTerm">Enter term:</label>
                  <div class="input-group mt-2 mb-3">
                    <!--Search Term entry box-->
                    <input id="sTermTxt" name="sTerm" type="text" class="form-control" placeholder="Search term here..." aria-hidden="false">
                    <?php for ($i = 0; $i < count($CVColNames); $i++) { ?>
                      <select id="<?php echo 'sTerm' . $CVColNames[$i]; ?>" style="display: none;" class="form-control form-select form-select-lg" aria-label="<?php echo $CVDisplay[$i] . 'Select'; ?>"><option>Select <?php echo $CVDisplay[$i]; ?></option>
                        <?php
                          // check for radio button fields
                          if ($CVColTypes[$i] == 'radio') {
                            echo $FieldNumCVs[$i]['Options'];
                            $termArr = explode(';', trim($FieldNumCVs[$i]['Options'], ';'));
                            $arrln = count($termArr);
                          } else {
                            $arrln = count($FieldNumCVs[$i]);
                          }
                          // check for radio button fields
                          for ($j = 0; $j < $arrln; $j++):
                            if ($CVColTypes[$i] == 'radio') {
                              $txt = $termArr[$j]; ?>
                              <option id="<?php echo htmlspecialchars_decode($txt); ?>" class="selected-item" value="<?php echo htmlspecialchars_decode($txt); ?>"><?php echo htmlspecialchars_decode($txt); ?></option>
                            <?php } else {
                              $temp = $FieldNumCVs[$i][$j];
                              $id = $temp['ID'];
                              $name = $temp['Name'];
                            } ?>
                            <?php if ($CVColTypes[$i] == 'dropdown-CV') {?>
                            <option id="<?php echo htmlspecialchars_decode($id); ?>" class="selected-item" value="<?php echo htmlspecialchars_decode($name); ?>"><?php echo htmlspecialchars_decode($name); ?></option>
                          <?php } endfor; ?>
                      </select>
                    <?php } ?>

                    <div class="input-group-append">
                      <!--Search Button-->
                      <button class="input-group-text red search-btn"><i class="fas fa-search" id="searchBtn" aria-hidden="false" type="submit"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
          <div class="row">
            <div class="col-12 text-left">
              <a href="search.php" class="btn btn-light mb-2"><i class="fas fa-sync-alt"></i> Refresh Page</a>
            </div>
          </div>
          <hr class="mb-4" style="height:2px;">
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="card py-2 p-3 mb-5 bg-white rounded">
              <h5 class="card-header text-white text-center">Files</h5>
              <div class="row justify-content-center mt-2">
                <p id="pShowing">Showing <span id="recordsFrom"><?php echo $fromRow; ?></span> - <span id="recordsTo" data-val="<?php echo $toRow; ?>"><?php echo $toRow; ?></span>
                  of <span id="recordsTotal"></span></p>
              </div>
              <?php
                $qCols = "`object`.*";
                // To handle when a CV column is being searched
                $isCVFieldNum = array();
                $isBlankqName = false;
                for ($i = 0; $i < count($CVColNames); $i++):
                  $isCVFieldNum[$i] = '$isCV' . $CVColNames[$i] . ' = false;';
                endfor;

                for ($i = 0; $i < count($CVColNames); $i++):
                  $isCVFieldNum[$i] = '$isCV' . $CVColNames[$i] . ' = false;';
                endfor;
                // set where condition of query
                if ((empty($_GET['colName']) && !isset($_GET['colName']))) {
                  $qName = " ";
                  $isBlankqName = true;
                  //break;
                } else {
                  for ($i = 0; $i < count($colNames); $i++):
                    if ($_GET['colName'] == $colNames[$i]) {
                      $qName = "WHERE object.$colNames[$i]";
                      for ($j = 0; $j < count($CVColNames); $j++):
                        if ($_GET['colName'] == $CVColNames[$j]) {
                          $isCVFieldNum[$j] = true;
                        }
                      endfor;
                    }
                    if ($_GET['colName'] == "UI") {
                      $qName = "WHERE object.UI";
                    }
                    if ($_GET['colName'] == "objectID") {
                      $qName = "WHERE object.objectID";
                    }
                  endfor;
                }


                // Check the search term
                if(empty($_GET['sTerm']) && !isset($_GET['sTerm'])){
                  $qTerm = " ";
                } else if((isset($_GET['sTerm']) && !empty($_GET['sTerm'])) || (isset($_GET['sTerm']) && $_GET['sTerm']=='0')) {
                  $qTerm = "'".$_GET['sTerm']."'";
                } else {
                  $qTerm = "null";
                }

                // Check the conditions
                if (empty($_GET['cond']) && !isset($_GET['cond'])) {
                  $qCond = " " . $qTerm;
                } else if ($_GET['cond'] == "1") {
                  // equals
                  $qCond = "=" . $qTerm;
                } else if ($_GET['cond'] == "2") {
                  // not equals
                  $qCond = "<>" . $qTerm;
                } else if ($_GET['cond'] == "3") {
                  // begins with
                  $qCond = " LIKE '" . $_GET['sTerm'] . "%'";
                } else if ($_GET['cond'] == "4") {
                  // does not begins with
                  $qCond = " NOT LIKE '" . $_GET['sTerm'] . "%'";
                } else if ($_GET['cond'] == "5") {
                  // ends with
                  $qCond = " LIKE '%" . $_GET['sTerm'] . "'";
                } else if ($_GET['cond'] == "6") {
                  $qCond = " LIKE '%" . $_GET['sTerm'] . "%'";
                } else if ($_GET['cond'] == "7") {
                  $qCond = " NOT LIKE '%" . $_GET['sTerm'] . "%'";
                } else if ($_GET['cond'] == "8") {
                  // is blank
                  $qCond = " IS NULL or object." . $_GET['colName'] . "=''";
                } else if ($_GET['cond'] == "9") {
                  // is not blank
                  $qCond = " IS NOT NULL and object." . $_GET['colName'] . "<>''";
                } else {
                  $qCond = " ";
                }
                $sql = "SELECT " . $qCols . " FROM `object` " . $qName . $qCond . " LIMIT " . $offset . "," . $rowLimit;
                $query = $conn->query($sql);
                $results = array();
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                  $results[] = $row;
                }
                // Get the row counts per the query
                if ($isBlankqName) {
                  $msql = "SELECT COUNT(objectID) as `NumofIdentities` FROM `object`";
                } else {
                  $msql = "SELECT COUNT(objectID) as `NumofIdentities` FROM `object`" . $qName . $qCond;
                }
                $query = $conn->query($msql);
                $records = $query->fetch(PDO::FETCH_ASSOC);
                $returnedTotal = $records['NumofIdentities'];
              ?>
              <div class="tabledata table-responsive table-responsive-data2">
                <table class="table table-data2">
                  <thead>
                  <tr>
                    <th data-field="objectID">File Name</th>
                    <th data-field="File" data-formatter="fileFormatter">Title</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php $ln = count($results);
                    for ($i = 0; $i < $ln; $i++):
                      $temp = $results[$i]; ?>
                      <tr class="tr-shadow">
                        <td><?php echo strlen(htmlspecialchars_decode($temp['UI'])) > 1 ? htmlspecialchars_decode($temp['UI']) : "Unknown"; ?></td>
                        <td><?php echo strlen(htmlspecialchars_decode($temp['Field1'])) > 1 ? htmlspecialchars_decode($temp['Field1']) : "Unknown"; ?></td>
                        <td>
                          <div class="table-data-feature">


                            <a target="_blank" href="<?php echo $temp['File'] ?>" class="item mr-2" name="preview_metatag"
                               data-toggle="tooltip" data-placement="top"
                               title="Preview File">
                              <i class="zmdi zmdi-more"></i>
                            </a>
                            <form action="edit_metatag.php" method="post" enctype="multipart/form-data">
                              <input type="hidden" name="id" value="<?php echo $temp['objectID']; ?>"/>
                              <button class="item mr-2" name="edit_metatag" data-toggle="tooltip" data-placement="top"
                                      title="Edit Meta Data">
                                <i class="zmdi zmdi-edit"></i>
                              </button>
                            </form>
                            <form action="delete_metatag.php" method="post" enctype="multipart/form-data">
                              <input type="hidden" name="id" value="<?php echo $temp['objectID'] ?>"/>
                              <button class="item mr-2" name="delete_metatag" data-toggle="tooltip" data-placement="top"
                                      title="Delete File">
                                <i class="zmdi zmdi-delete"></i>
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                      <tr class="spacer"></tr>

                    <?php endfor; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- Pagination Section -->
            <nav aria-label="Page navigation example" id="pgNum">
              <?php
                $pgcolName = "";
                $pgCond = "";
                $pgsTerm = "";
                if (!empty($_GET['colName'])) {
                  $pgcolName = $_GET['colName'];
                }
                if (!empty($_GET['cond'])) {
                  $pgCond = $_GET['cond'];
                }
                if(isset($_GET['sTerm'])){
                  if(!empty($_GET['sTerm'])){
                    $pgsTerm = $_GET['sTerm'];
                  } else {
                    $pgsTerm = 0;
                  }
                }
                $pgSearch = "colName=" . $pgcolName . "&cond=" . $pgCond . "&sTerm=" . $pgsTerm;
                $max_page = ceil($returnedTotal / $rowLimit);
              ?>
              <div class="table-pagination col-md-12 text-center">
                <p><b><?php echo "Page " . $page_n . " of " . $max_page; ?></b></p>
              </div>
              <ul class="pagination justify-content-center">
                <li>
                  <!-- Previous Page -->
                  <?php if ($page_n != 1) { ?>
                    <a class="page-link previous-page"
                       href="search.php?<?php echo $pgSearch; ?>&page_n=<?php echo $page_n - 1; ?>"
                       aria-label="Previous">
                      <span aria-hidden="true">&laquo; Previous</span>
                      <span class="sr-only">Previous</span>
                    </a>
                  <?php } ?>
                  <?php if ($page_n == 1) { ?>
                    <p class="page-link previous-page">
                      <span aria-hidden="true">&laquo; Previous</span>
                      <span class="sr-only">Previous</span>
                    </p>
                  <?php } ?>
                </li>
                <li>
                  <!-- Next Page -->
                  <?php if ($page_n != $max_page) { ?>
                    <a class="page-link next-page"
                       href="search.php?<?php echo $pgSearch; ?>&page_n=<?php echo $page_n + 1; ?>"
                       aria-label="Next">
                      <span aria-hidden="true">Next &raquo;</span>
                      <span class="sr-only">Next</span>
                    </a>
                  <?php } ?>
                  <?php if ($page_n == $max_page) { ?>
                    <p class="page-link next-page">
                      <span aria-hidden="true">Next &raquo;</span>
                      <span class="sr-only">Next</span>
                    </p>
                  <?php }?>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
            </div>
         </div>
      </div>


</section>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js" defer></script>

<script>
    $(document).ready(function () {
        $('.select2').select2();// search for Field names
        $("#recordsTotal").html(<?php echo $returnedTotal;?>);

        //$('#sTermTxt').attr('value','0');
        var recordsTo = $("#recordsTo").attr("data-val");
        var returnedTotal = <?php echo $returnedTotal;?>;
        var STerms = <?php echo json_encode($STerms);?>;
        var Fields = <?php echo json_encode($CVColNames);?>;
        var T = 0;
        if (recordsTo > returnedTotal) {
            $("#recordsTo").html(returnedTotal);
        }
        // set message if No records matching the data
        if (returnedTotal == 0) {
            $("#pShowing").html("No records matching your query were found.");
            $("#restartRegularSearch").css('display', 'inline'); // show restart search button
            $("#pageEnd").css('display', 'none');
            $(".tabledata").css('display', 'none');
            $("#pgNum").css('display', 'none');
        }
        // Hide the search box when searching blank / empty fields
        $("#SelectID").on("change", function () {
            var selected = $(this).text();
            var selectedID = $(this).val();
            // Disable or enable the search box when not needed.
            if ((selectedID == '8') || (selectedID == '9')) {
                $("#sTermTxt").prop('disabled', true);
                for (let i = 0; i < STerms.length; i++) {
                    $(STerms[i]).prop('disabled', true);
                }
            } else {
                $("#sTermTxt").prop('disabled', false);
                for (let i = 0; i < STerms.length; i++) {
                    $(STerms[i]).prop('disabled', false);
                }
            }
        });
        $("#colName").on("change", function () {
            var selected = $(this).text();
            var selectedID = $(this).val();
            for (let i = 0; i < STerms.length; i++) {
                $("#sTermTxt").css('display', 'none');
                for (let j = 0; j < STerms.length; j++) {
                    $(STerms[j]).css('display', 'none');
                }
                if (selectedID == Fields[i]) {
                    $(STerms[i]).css('display', 'flex');
                    break;
                } else {
                    $("#sTermTxt").css('display', 'flex');
                    continue;
                }

            }
        });

        for (let i = 0; i < STerms.length; i++) {
            $(STerms[i]).on("change", function () {
                var selectedID = $(this).val();
                $('#sTermTxt').attr('value', selectedID);
            });
        }

        // Prevent dropdown menu from closing when list label is clicked
        $(".dropdown-menu.keep-open").on("click", function (e) {
            e.stopPropagation();
        });

    });

    $(document).on('click', '.allow-focus', function (e) {
        e.stopPropagation();
    });
</script>

<?php
  require_once("footer.php");
?>
