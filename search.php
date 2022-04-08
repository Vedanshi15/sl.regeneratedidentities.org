<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once 'header.php';
  require_once("db_files/db_config.php");

  $rows = [];
  $objects = [];
  $sl_objects = array();
  $db = $conn;
  $query = $db->query("SELECT * FROM SL_Objects WHERE status = 1 ORDER BY length(`ColumnName`) , `ColumnName`");
  while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $sl_objects[] = $row;
  }

  if (isset($_POST) && isset($_POST['field']) && isset($_POST['operater']) && isset($_POST['value'])) {
    $fields = $_POST['field'];
    $operaters = $_POST['operater'];
    $values = $_POST['value'];
    if (isset($_POST['andor'])) {
      $andors = $_POST['andor'];
    } else {
      $andors = null;
    }


    if ((count($fields) == count($operaters)) && (count($operaters) == count($values))) {

      (function () use ($fields, $operaters, $values, $andors, $sl_objects, $db, $objects, $rows) {
        global $fields, $operaters, $values, $andors, $sl_objects, $db, $objects, $rows;
        foreach ($sl_objects as $sl_object) {
          foreach ($fields as $index => $field_id) {
            if ($sl_object['id'] == $field_id) {
              $fields[$index] = $sl_object;
            }
          }
        }

        $search_query = "SELECT * FROM `object` WHERE ";

        foreach ($fields as $index => $field) {
          $search_query .= $field['ColumnName'] . "	" . $operaters[$index] . " ?";
          var_dump($search_query);
          if ($andors) {
            if ((count($fields) - 1) != $index) {
              $search_query .= " " . $andors[$index] . " ";
            }
          }
        }
        // dnl($search_query);
        $searchquery = $db->query($search_query);

        $q1 = $db->prepare($searchquery);
        $x = 1;
        if (count($values)) {
          foreach ($values as $param) {
            var_dump($param);
            $q1->bindValue($x, $param);
            $x++;
          }
        }

        $tt = $q1->execute();
        var_dump($tt);
        while ($row1 = $q1->fetchAll(PDO::FETCH_OBJ)) {
          $objects = $row1;
          $rows = $objects;
        }
        var_dump($objects);
        //var_dump($v);
      })();
    }
  }
  if ($objects) :
    ?>
    <div class="main-content" style="min-height:90vh;">
      <div>
        <div class="container-fluid">

          <table id="table" data-search="true" data-show-columns="true">
            <thead>
            <tr>
              <th data-field="objectID">#</th>
              <th data-field="File" data-formatter="fileFormatter">Filename</th>
              <th data-field="File" data-formatter="previewfileFormatter">Preview file</th>
              <th data-field="objectID" data-formatter="replacefileFormatter">Replace File</th>
              <th data-field="objectID" data-formatter="deletefileFormatter">Delete File</th>
              <!-- <th data-field="Field1" data-formatter="titleFormatter" >Title</th> -->
              <th data-field="objectID" data-formatter="editmetadataFormatter">Edit Metafields</th>
            <tr>
            </thead>
          </table>
        </div>
      </div>
    </div>

  <?php
  else :
    ?>
    <div class="main-content" style="min-height:90vh;">
      <div>
        <div class="container-fluid">
          <form action="" method="POST">
            <div class="search-row-container">
              <div class="form-row search-row">

                <div class="form-group col-md-2 andor-container">

                </div>

                <div class="form-group col-md-4">
                  <label>Select Field</label>
                  <select class="form-control" name="field[]">
                    <option value="" selected disabled>Choose...</option>
                    <?php foreach ($sl_objects as $sl_object) : ?>
                      <option value="<?php echo $sl_object['id']; ?>"><?php echo $sl_object['display']; ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>

                <div class="form-group col-md-2">
                  <label>Select Operater</label>
                  <select name="operater[]" class="form-control">
                    <option value="" selected disabled>Choose...</option>
                    <option value="=">equals</option>
                    <option value="!=">not equals</option>
                    <option value="like">Like</option>
                  </select>
                </div>
                <div class="form-group col-md-2">
                  <label>Value</label>
                  <input name="value[]" type="text" class="form-control" placeholder="value">
                </div>
                <div class="form-group col-md-2 m-t-35">
                  <button type="button" onclick="addRow()" class="btn btn-info form-control">Add Row</button>
                </div>
              </div>
            </div>


            <div class="form-row">
              <div class="form-group col-md-6">
                <button class="btn btn-success btn-block">Find Results</button>
              </div>
              <div class="form-group col-md-6">
                <button type="button" onclick="location.reload()" class="btn btn-warning btn-block">Reset</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script src="assets/js/edit-metatag.js"></script>
  <?php
  endif;

  $rows_encoded = json_encode($rows);
  $customJs = <<<HTML
    <script>
      let rows = $rows_encoded
      
    </script>
    
  HTML;

  require_once('footer.php');

?>