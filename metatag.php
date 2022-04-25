<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once 'header.php';
  require_once("db_files/db_config.php");
  $query = $conn->query("SELECT * FROM `object`");
  $resultsH = array();
  while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $resultsH[] = $row;
  }
  if (isset($_POST['replace_file'])) {
    $id = $_POST['id'];
    $result = null;
    $query = $conn->query("SELECT * FROM `object` WHERE objectID = " . $id);
    if ($query) {
      $r = $query->fetchAll(PDO::FETCH_OBJ);
      $result = $r[0];
      //var_dump($result);
      $tempPath = $_FILES['file']['tmp_name'];
      $size = filesize($tempPath);
      $info = finfo_open(FILEINFO_MIME_TYPE);
      $type = finfo_file($info, $tempPath);
      var_dump($info);
      $temp = null;
      $rules = [
        "type" => ["image/jpeg", "image/png", "image/tiff", "application/pdf"]
      ];
      if (empty($rules)) {
        $temp = true;
      }
      if (isset($rules['size'])) {
        if ($rules['size'] > (1024 * 1024 * $this->size)) {
          $temp = true;
        }
      }
      if (isset($rules['mime']) && !empty($rules['mime'])) {
        if (!in_array($this->type, $rules['mime'])) {
          $temp = false;
        }
      }
      $t = null;
      if ($temp) {
        try {
          $filename = $result->UI ? $result->UI : randomString(5);

          $extension = $mimeTypes[$type];
          $newFilepath = $filename . "." . $extension;

          if (!cp($tempPath, ROOT . "/Datafiles/" . $newFilepath)) {
            $t = false;
          }
          unlink($tempPath);

          $link = "http://sl.regeneratedidentities.org/Datafiles/" . $newFilepath;
          $t = true;
        } catch (\Throwable $th) {
          $t = false;
        }
        $qry = "UPDATE `object` SET File = " . $link . "WHERE objectID = " . $id;

        $q1 = $conn->prepare($qry);
        $x = 1;
        if (count($_POST)) {
          foreach ($_POST as $param) {
            $q1->bindValue($x, $param);
            $x++;
          }
        }
        $q1->execute();
        if ($q1) {
          header("Location: metatag.php");
        } else {
          echo "Error in Updating...";
        }
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
    <div class="col-md-12">
      <!-- DATA TABLE -->
      <h3 class="title-5 m-b-35">Meta Tag Documents </h3>
      <div class="table-data__tool">
        <div class="table-data__tool-left">

        </div>
        <div class="table-data__tool-right">
          <a href="upload.php" class="au-btn au-btn-icon au-btn--green au-btn--small">
            <i class="zmdi zmdi-plus"></i>add New</a>
        </div>
      </div>
      <div class="table-responsive table-responsive-data2">
        <table class="table table-data2">
          <thead>
          <tr>
            <th data-field="objectID">File Name</th>
            <th data-field="File" data-formatter="fileFormatter">Title</th>
          </tr>
          </thead>
          <tbody>
          <?php $ln = count($resultsH);
            for ($i = 0; $i < $ln; $i++):
              $temp = $resultsH[$i]; ?>
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
      <!-- END DATA TABLE -->
    </div>
  </div>


<?php require_once 'footer.php' ?>