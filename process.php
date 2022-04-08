<?php
  session_start();
  header('Access-Control-Allow-Methods: OPTIONS, GET, DELETE, POST, HEAD, PATCH');
  header('Access-Control-Allow-Headers: content-type, upload-length, upload-offset, upload-name');
  header('Access-Control-Expose-Headers: upload-offset');

  require_once ("db_files/db_config.php");
  require_once ("classes/Storage.php");
  const ENTRY_FIELD = array('filepond');


  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $storage = new Storage();
    /**
     * instance DB;
     */

    $files = $_FILES["filepond"];
    $imageName = null;
    $id = null;
    if(isset($_POST['title'])){
      $title = $_POST['title'];
    }
    if(isset($_GET['title'])){
      $title = $_GET['title'];
    }
    if (!$title) {
      //exit("title not found");
      $title = 't1';
    }
    echo "nefnwk";
    $uploaded = false;
    $uploaded_id = 0;

    $ui_id = 0;
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
echo $ui_id;
    $structuredFile = [];
    if (isset($files)) {
      $structuredFile["name"] =  $files["name"][0];
      $structuredFile["type"] = $files["type"][0];
      $structuredFile["tmp_name"] = $files["tmp_name"][0];
      $structuredFile["error"] = $files["error"][0];
      $structuredFile["size"] = $files["size"][0];
    }

    if ($structuredFile) {
      $storage->prepare($structuredFile);
      if ($storage->validate([
        "type" => ["image/jpeg", "image/png", "image/tiff", "application/pdf"]
      ])) {

        if ($storage->upload($ui_id)) {
          $file_link = $storage->getUploadedLink();
          $s = 0;
          $sql = "INSERT INTO `object` (Status, UI, File, Field1) 
              VALUES ( :Status, :UI, :File, :Field1) ";
          $pst = $conn->prepare($sql);
          $pst->bindParam(':Status', $s);
          $pst->bindParam(':UI', $ui_id);
          $pst->bindParam(':File', $file_link);
          $pst->bindParam(':Field1', $title);
          $count = $pst->execute();
          if($count){
            $uploaded = true;
            $query1 = $conn->query("SELECT LAST_INSERT_ID() as id");
            $p = $conn->prepare($query1);
            $c = $p->execute();
            if ($c) {
              $lid = $p->fetchAll(PDO::FETCH_OBJ);
              $uploaded_id = $lid[0];
              $uploaded = true;
            }
          }
        }
      }
    }



    if ($uploaded) {

      $response["status"] = "success";
      // TODO : here we will send id of "object" row
      $response["key"] = $uploaded_id;
      $response["msg"] = null;

      http_response_code(200);
    } else {

      $response["status"] = "error";
      $response["key"] = $uploaded_id;
      $response["try"] = null;
      $response["msg"] = "An error occured while uploading image";

      http_response_code(400);
    }

    header('Content-Type: application/json');
    echo json_encode($response);

    exit();
  } else {
    exit();
  }
