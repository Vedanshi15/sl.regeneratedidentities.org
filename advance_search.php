<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once 'header.php';
  require_once("db_files/db_config.php");

?>
<section id="advancedsearch"  class="main-content">
  <div class="section__content section__content--p30">
	<?php $addRowsCount = 1; ?>
  <!--Main Content-->
  <div class="container-fluid content">
    <div class="row">
      <div class="margin-handler" style="width:100%;">
        <div class="row justify-content-center">
          <div class="col-11">
            <!--Start of Content-->
            <div class="row">
              <div class="col-12 text-left">
                <h3 class="title-5 m-b-35">ADVANCED SEARCH</h3>
				  <?php
				  if (empty($_GET['page_n'])) {
					  $page_n = 1;
				  } else {
					  $page_n = $_GET['page_n'];
				  }
				  $rowLimit =16;
				  $offset = $rowLimit * ($page_n - 1);
				  $fromRow = (($rowLimit) * ($page_n - 1)) + 1; // Calculate the starting number
				  $toRow = ($page_n * $rowLimit);
				  ?>
                <p id="pShowing">Showing <span id="recordsFrom"><?php echo $fromRow; ?></span> - <span id="recordsTo" data-val="<?php echo $toRow; ?>"><?php echo $toRow; ?></span>
                  of <span id="recordsTotal"></span></p>
              </div>
            </div>
            <div class="row">
              <div class="col-12 text-left">
                <a href="search.php" class="btn btn-light mt-2 mb-2"><i class="fas fa-arrow-left"></i> Back</a>
                <a id="restartSearch" href="advance_search.php" class="btn btn-dark mt-2 mb-2"><i class="fas fa-sync-alt"></i>Refresh Page</a>
              </div>
            </div>
            <div id="pageEnd">
              <!--Fetch from the SR_Person_V1 Table-->
				<?php
				$sql = "SELECT SL_Objects.* FROM `SL_Objects`";
				$query = $conn->query($sql);
				$resultsH = array();
				$rowsCV = array();
				while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
					$resultsH[] = $row;
					if ($row['FieldType'] == "dropdown-CV"  || ($row['FieldType'] == "radio")) {
						$tID = $row['ColumnName'];
						$rowsCV[$tID] = $row;
					}
				}
				?>
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
				foreach ($rowsCV as $trow) {
					$colName = $trow['ColumnName'];
					$fieldType = $trow['FieldType'];
					if ($fieldType != "radio") {
						// For dropdown-CV and dropdown-CV-multi types
						$cvTable = $trow['Options']; // Get the table name for the new query
						// Fetch the CV from each CV table...
						$sql = "SELECT * FROM " . $cvTable;
						$query = $conn->query($sql);
						$tempCV = array();
						while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
							$tID = $row['ID'];
							$tempCV[$tID] = $row;
						}
						$controlledVocab[$colName] = $tempCV; // combine CV data with field as array key
					} else {
						// TO-DO : Explode the yes;no here and set it to be the temp in place of trow
						$controlledVocab[$colName] = $trow; // handle radio - no CV Table
					}
				} ?>
              <!--Check whether the advanced search param is set or not-->
				<?php
				$lnCNO = 0; // initialize
				$originalQuery = "";
				$returnedTotal = 0;
				$row_op = array();
				$row_si = array();
				$row_st = array();
				$cnamesOnly = array();
				if (empty($_GET['ad_search'])) {
					$isAdvancedLSesults = false; ?>
                  <form action="advance_search.php" method="GET" class="mb-5">
                    <div id="ad-form">
                      <div class="row">
                        <div class="col-md-2">
                          <div class="form-group">
                            <input id="row_op" name="row_op" type="hidden" value="">
                          </div>
                        </div>
                        <div class="col-lg-4">
                          <div class="form-group">
                            <label for="row_si">Search Index</label>
                            <div>
                              <select id="row_si_1" name="row_si" class="row_si form-select form-select-lg mb-3 mt-2 select2">
								  <?php
								  $lnH = count($resultsH);
								  for ($i = 0; $i < $lnH; $i++):
									  $tempH = $resultsH[$i]; ?>
                                    <option id="<?php echo htmlspecialchars_decode($tempH['ColumnName']); ?>" value="<?php echo htmlspecialchars_decode($tempH['ColumnName']); ?>" class="selected-item"><?php echo htmlspecialchars_decode($tempH['display']); ?></option>
									  <?php if ($i == 0): ?>
                                    <option id="UI" value="UI" class="selected-item">UI</option>
								  <?php endif; ?>
								  <?php endfor; ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-lg-6">
                          <div id="fg_st_1" class="form-group">
                            <label for="row_st">Search Term</label>
                            <input type="text" class="form-control mt-2 mb-3" id="row_st_1" name="row_st" placeholder="Enter text here">
                          </div>
                        </div>
                        <hr>
                      </div>
                      <!--More Rows Go Here-->
                    </div>
                    <input id="ad_search" name="ad_search" type="hidden" value="Y">
                    <div class="row">
                      <div class="col-12 text-right">
                        <button id="removeARowBtn" type="button" class="btn btn-light mt-2 mb-2"><i class="fas fa-minus"></i> Remove a Row
                        </button>
                        <button id="addARowBtn" type="button" class="btn btn-light mt-2 mb-2"><i class="fas fa-plus"></i> Add a Row
                        </button>
                        <button type="reset" class="btn btn-light mt-2 mb-2" style="margin-left:15px;"><i class="fas fa-sync-alt"></i> Clear
                        </button>
                      </div>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-12 text-right">
                        <a href="advance_search.php" class="btn btn-light mt-2 mb-2"><i class="fas fa-sync-alt"></i>Refresh Page</a>
                        <button type="submit" class="filter-btn btn btn-secondary mt-2 mb-2" style="margin-left:15px;padding-left:30px; padding-right:30px;">Search</button>
                      </div>
                    </div>
                  </form>
				<?php } else {
					$isAdvancedLSesults = true; ?>
                  <!--The Table Buttons-->
                  <div class="row pb-3">
                    <div class="col-12 col-md-6 col-lg-3"></div>
                    <div class="col-12 col-md-6 col-lg-3"></div>
                    <div class="col-12 col-lg-3"></div>
                    <div class="col-12 col-lg-3 text-right">
                      <!--Hide/Show Button-->
                      <div class="filter-btn-container hide-for-mobile">
                        <a href="advance_search.php" class="btn btn-light mt-2 mb-2"><i class="fas fa-sync-alt"></i>Refresh Page</a>
                      </div>
                      <!--Hide/show Button Removed for Mobile-->
                    </div>
                  </div>
					<?php
					$originalQuery = $_SERVER['QUERY_STRING'];
					$ad_query = explode('&', $_SERVER['QUERY_STRING']);
					$params = array();
					foreach ($ad_query as $param) {
						// prevent notice on explode() if $param has no '='
						if (strpos($param, '=') === false) $param += '=';
						list($name, $value) = explode('=', $param, 2);
						$params[urldecode($name)][] = urldecode($value);
					}
					//Get the params for the query
					$row_op = $params['row_op'];
					$row_si = $params['row_si'];
					$row_st = $params['row_st'];
					//Put together the query
					$rcnt = count($row_op);
					$advanced_query = "";
					for ($i = 0; $i < $rcnt; $i++):
						$op = $row_op[$i];
						$si = $row_si[$i];
						$st = $row_st[$i];
						$isStNull = false;
						if ($st == "") {
							$isStNull = true;
						}
						$prefix = "";
						if ($i > 0) {
							if ($op == "AND") {
								if ($isStNull) {
									$prefix = "AND object." . $si . " IS NULL ";
								} else {
									$prefix = "AND object." . $si . " LIKE '%" . $st . "%' ";
								}
							} else if ($op == "NOT") {
								if ($isStNull) {
									$prefix = "AND object." . $si . " IS NOT NULL ";
								} else {
									$prefix = "AND object." . $si . " NOT LIKE '%" . $st . "%' ";
								}
							} else if ($op == "OR") {
								if ($isStNull) {
									$prefix = "OR object." . $si . " IS NULL ";
								} else {
									$prefix = "OR object." . $si . " LIKE '%" . $st . "%' ";
								}
							}
						} else {
							if ($isStNull) {
								$prefix = "object." . $si . " IS NULL";
							} else {
								$prefix = "object." . $si . " LIKE '%" . $st . "%' ";
							}
						}
						$advanced_query = $advanced_query . $prefix;
					endfor;
					?>
                  <!--Fetch the Results-->
					<?php
					$sql = "SELECT * FROM `object` WHERE " . $advanced_query . " LIMIT " . $offset . "," . $rowLimit;
					$query = $conn->query($sql);
					$results = array();
					while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
						$results[] = $row;
					}
					// Get the row counts per the query
					$msql = "SELECT COUNT(objectID) AS `Numofobjects` FROM `object` WHERE " . $advanced_query;

					$query = $conn->query($msql);
					$records = $query->fetch(PDO::FETCH_ASSOC);
					$returnedTotal = $records['Numofobjects'];
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

                        <form action="" method="post" enctype="multipart/form-data">
                          <input type="hidden" name="id" value="<?php echo $temp['objectID'] ?>"/>
                          <button class="item mr-2" name="replace_file" data-toggle="tooltip" data-placement="top"
                                  title="Replace File">
                            <i class="zmdi zmdi-search-replace"></i>
                          </button>
                        </form>
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

        <?php } ?>
              <!-- The Table Pagination-->
              <div id="pagination" class="row justify-content-end mb-5 pb-5">
				  <?php
				  $pgSearch = $originalQuery;
				  $max_page = ceil($returnedTotal/$rowLimit);
				  ?>
                <div class="table-pagination col-12 text-center">
                  <p><b><?php echo "Page ".$page_n." of ".$max_page; ?></b></p>
                </div>
                <div class="table-pagination col-12 show-on-mobile">
                  <!--Pagination-->
                  <ul class="paginationLists input-group-append justify-content-center">
                    <li>
                      <!-- Previous Page -->
						<?php if($page_n!=1){?>
                          <a class="page-link previous-page" href="advance_search.php?<?php echo $pgSearch; ?>&page_n=<?php echo $page_n-1;?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo; Previous</span>
                            <span class="sr-only">Previous</span>
                          </a>
						<?php }?>
						<?php if($page_n==1){?>
                          <p class="page-link previous-page">
                            <span aria-hidden="true">&laquo; Previous</span>
                            <span class="sr-only">Previous</span>
                          </p>
						<?php }?>
                    </li>
                    <li>
                      <!-- Next Page -->
						<?php if($page_n!=$max_page){?>
                          <a class="page-link next-page" href="advance_search.php?<?php echo $pgSearch; ?>&page_n=<?php echo $page_n+1;?>" aria-label="Next">
                            <span aria-hidden="true">Next &raquo;</span>
                            <span class="sr-only">Next</span>
                          </a>
						<?php }?>
						<?php if($page_n==$max_page){?>
                          <p class="page-link next-page">
                            <span aria-hidden="true">Next &raquo;</span>
                            <span class="sr-only">Next</span>
                          </p>
						<?php }?>
                    </li>
                  </ul>
                </div>
              </div>
              <!--End of Content-->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--Footer-->

	  <?php require_once("footer.php"); ?>

</section>
<!--End of Section-->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js" defer></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();// search for Field names
		<?php if($isAdvancedLSesults){?>
        $("#pShowing").show();
        $("#pagination").show();
        $("#recordsTotal").html(<?php echo $returnedTotal;?>);
        var recordsTo = $("#recordsTo").attr("data-val");
        var returnedTotal = <?php echo $returnedTotal;?>;

        if (recordsTo > returnedTotal) {
            $("#recordsTo").html(returnedTotal);
        }
        if (returnedTotal == 0) {
            $("#pShowing").html("No records matching your search were found.");
            $("#restartSearch").css('display', 'inline');
            $("#pageEnd").css('display', 'none');
        }
		<?php } else {?>
        $("#pShowing").hide();
        $("#pagination").hide();
		<?php } ?>

        /*
          Handle adding rows to the advanced search - see template at the end of file...
         */
        var cnt = <?php echo $addRowsCount;?>;
        // Load first 3 rows ...

        $("#addARowBtn").on("click", function () {

            if (cnt < 10) {
                cnt += 1;
                var template = $("#advancedSearchRowTemplate").html();
                $("#ad-form").append(template);
                $("select#row_si").attr("id", "row_si_" + cnt); // Update the counter and id
                $("input#row_st").attr("id", "row_st_" + cnt); // Update the counter and id
                $("div#fg_st").attr("id", "fg_st_" + cnt); // Update the counter and id

                $("#addARowBtn").prop('disabled', false);
                if (cnt > 1) {
                    $("#removeARowBtn").prop('disabled', false);
                }
            } else if (cnt == 10) {
                $("#addARowBtn").prop('disabled', true);
            }
            //console.log("count is now " + cnt);
            $('.select2').select2();
        });

        $("#removeARowBtn").on("click", function () {
            if (cnt > 1 && cnt <= 10) {
                cnt -= 1;
                var template = $("#advancedSearchRowTemplate").html();
                $("div#ad-form").children().last().remove();
                $("#removeARowBtn").prop('disabled', false);
                $("#addARowBtn").prop('disabled', false);
            } else if (cnt == 1) {
                $("#removeARowBtn").prop('disabled', true);
            }
            //console.log("count is now "+cnt);
        });

        /*
		 Handle updating dropdowns for single selects - first get the ID...
		*/
		<?php
		$jsCVs = json_encode($controlledVocab);
		$jsrowsCVs = json_encode($rowsCV);
		echo "var jsControlledVocab = " . $jsCVs . ";\n"; // All the CVs
		echo "var jsMap = " . $jsrowsCVs . ";\n"; // The map table
		?>
        // When clicked update the select if it is a CV...
        $(document).on('change', '.row_si', function () {
            //$('.row_si').on("change", function(){
            var theID = $(this).attr('id');
            var theInd = theID.slice(7); // row_si_1 ... row_si_10
            var selectedID = $(this).val(); // the colName ... check if in $controlledVocab
            if (selectedID in jsControlledVocab) {
                $('input#row_st_' + theInd).remove();
                var cvArr = jsControlledVocab[selectedID]; // Get the CVs for e.g for the table related to 'Field3'
                var cvMap = jsMap[selectedID]; // Get the row from map table e.g where ColumnName is 'Field3'
                var fType = cvMap['FieldType'];
                if (fType == "dropdown-CV") {
                    // Check the select isn't there before adding
                    if ($("#sSinSelect_" + theInd).length == 0) {
                        // the select doesn't exit...add it...
                        // Attach the select
                        var template = $("#sinSelectTemplate").html();
                        $("div#fg_st_" + theInd).append(template); // Try with just the select div#fg_st_
                        $("#sSinSelect_").attr("id", "sSinSelect_" + theInd); // Update the counter and id
                        $("select#row_st").attr("id", "row_st_" + theInd); // Update the counter and id
                    } else {
                        // the select exists ... clear it and update
                        $("select#row_st_" + theInd).children().remove();
                    }
                    // Single Select
                    for (var key in cvArr) {
                        var temp = cvArr[key];
                        var id = temp['ID'];
                        var name = temp['Name'];
                        var tOption = '<option id="' + id + '" class="selected-item" value="' + name + '">' + name + '</option>';
                        $("select#row_st_" + theInd).append(tOption);
                    }
                    showInputs("sin", theInd);
                } else if (fType == "radio") {
                    // Check the select isn't there before adding
                    if ($("#sSinSelect_" + theInd).length == 0) {
                        // the select doesn't exit...add it...
                        // Attach the select
                        var template = $("#sinSelectTemplate").html();
                        $("div#fg_st_" + theInd).append(template); // Try with just the select div#fg_st_
                        $("#sSinSelect_").attr("id", "sSinSelect_" + theInd); // Update the counter and id
                        $("select#row_st").attr("id", "row_st_" + theInd); // Update the counter and id
                    } else {
                        // the select exists ... clear it and update
                        $("select#row_st_" + theInd).children().remove();
                    }
                    var ROP = "";
                    // Single Select
                    for (var key in cvArr) {
                        var temp = cvArr[key];
                        ROP = cvMap['Options'].split(';');
                    }
                    for (var YN in ROP) {
                        var tOption = '<option id="' + ROP[YN] + '" class="selected-item" value="' + ROP[YN] + '">' + ROP[YN] + '</option>';
                        $("select#row_st_" + theInd).append(tOption);
                    }
                    // Radio - TO-DO : Explode array earlier then move this to the dropdown-CV statement
                    showInputs("rad", theInd);
                }
            } else {
                initiate_search_box(theInd);
                showInputs("def", theInd); // TO-DO also pass the index.
            }
        });


        // Prevent dropdown menu from closing when list label is clicked
        $(".dropdown-menu.keep-open").on("click", function (e) {
            e.stopPropagation();
        });

    });

    /*
	 Set the default value for dropdowns
	*/
    function initiate_dropdown_value(theInd) {
        $('input#row_st_' + theInd).attr('value', '0');
    }

    /*
	 Reset the default value for search box
	*/
    function initiate_search_box(theInd) {
        // Check the select isn't there before adding
        if ($("input#row_st_" + theInd).length == 0) {
            // the input doesn't exit...add it...
            // Remove the select
            $("#sSinSelect_" + theInd).remove();
            // Re-attach the input
            var template = $("#inputTemplate").html();
            $("div#fg_st_" + theInd).append(template); // Try with just the select div#fg_st_
            $("input#row_st").attr("id", "row_st_" + theInd); // Update the counter and id
        }
    }

    /*
	 Show Inputs
	*/
    function showInputs(stat, theInd) {
        // Hide everything
        $("input#row_st_" + theInd).css('display', 'none');
        $("select#row_st_" + theInd).css('display', 'none');
        $("#sSinSelect_" + theInd).css('display', 'none');
        $("select#row_st_" + theInd).css('display', 'none');
        // Get what needs to be shown
        switch (stat) {
            case "sin":
                $("select#row_st_" + theInd).css('display', 'flex');
                $("#sSinSelect_" + theInd).css('display', 'flex');
                break;
            case "rad":
                $("select#row_st_" + theInd).css('display', 'flex');
                $("#sSinSelect_" + theInd).css('display', 'flex');
                break;
            default:
                $("input#row_st_" + theInd).css('display', 'flex');
        }
    }

</script>
<!--Input Template-->
<script type="text/html" id="inputTemplate">
  <input type="text" class="form-control mb-3 mt-2" id="row_st" name="row_st" placeholder="Enter text here"
         style="padding:1.45rem .75rem;">
</script>
<!--Single Dropdown Select Template-->
<script type="text/html" id="sinSelectTemplate">
  <div id="sSinSelect_" class="select-container">
    <select id="row_st" name="row_st" class="row_st form-control form-select form-select-lg mb-3 mt-2" aria-label=".form-select-lg">
      <!--<option></option> updated by javascript-->
    </select>
  </div>
</script>
<!--Advanced Search Default Row Template-->
<script type="text/html" id="advancedSearchRowTemplate">
  <div class="row">
    <div class="col-md-2 col-lg-2">
      <div class="form-group">
        <label for="row_op">Operator</label>
        <div class="select-container">
          <select id="row_op" name="row_op" class="form-select form-select-lg mb-3 mt-2">
            <option>AND</option>
            <option>OR</option>
            <option>NOT</option>
          </select>
        </div>
      </div>
    </div>
    <div class="col-md-10 col-lg-4">
      <div class="form-group">
        <label for="row_si">Search Index</label>
        <div>
          <select id="row_si" name="row_si" class="row_si form-control form-select form-select-lg mb-3 mt-2 select2">
			  <?php
			  $lnH = count($resultsH);
			  for ($i = 0; $i < $lnH; $i++):
				  $tempH = $resultsH[$i]; ?>
                <option id="<?php echo htmlspecialchars_decode($tempH['ColumnName']); ?>"
                        value="<?php echo htmlspecialchars_decode($tempH['ColumnName']); ?>"
                        class="selected-item"><?php echo htmlspecialchars_decode($tempH['display']); ?></option>
				  <?php if ($i == 0): ?>
                <option id="UI" value="UI" class="selected-item">UI</option>
			  <?php endif; ?>
			  <?php endfor; ?>
          </select>
        </div>
      </div>
    </div>
    <div class="col-lg-6">
      <div id="fg_st" class="form-group">
        <label for="row_st">Search Term</label>
        <input type="text" class="form-control mb-3 mt-2" id="row_st" name="row_st" placeholder="Enter text here">
      </div>
    </div>
    <hr>
  </div>
</script>
</body>
</html>
