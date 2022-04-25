<?php

  session_start();

  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once("db_files/db_config.php");
  $success = false;
  $success1 = false;
  $failure1 = false;
  $failure = false;
  $result = null;
  $objects = null;
  $grouped_object = null;
  $answers = null;
  $id = null;
  $class = 0;
  if (isset($_POST['edit_metatag'])) {
    $id = $_POST['id'];
    $query = $conn->query("SELECT * FROM `object` where objectID = " . $id);
    $temp = $query->fetch(PDO::FETCH_ASSOC);

  }

  if (isset($_POST['update_file'])) {
    var_dump($_POST);
      $oid = $_POST['oid'];
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

      $ext = pathinfo($_FILES['meta_file']['name'], PATHINFO_EXTENSION);
      $allowed = array('jpg', 'jpeg', 'png', 'tiff', 'gif', 'pdf');
      if (in_array($ext, $allowed)) {
        $target_file = $target_dir . basename($ui_id . '.' . $ext);
        $file = $ui_id . '.' . $ext;
        //$filename = $_FILES['file']['name'][$key];
        //echo $_FILES["file"]["size"].'f sz';
        $file_link = "http://sl.regeneratedidentities.org/Datafiles/" . $file;
        var_dump('filedata ' . $file_link . 'filnam' . $file);
        $sql = "UPDATE `object` set UI = $ui_id, File = $file_link where objectID = $oid";
        var_dump($sql);
        $conn->query($sql);
        move_uploaded_file($_FILES['meta_file']['tmp_name'], 'DataFiles/' . $file);
        //move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)
        $success1 = true;
        $message1 = 'Successfully Updated..... ';
      } else {
        $failure1 = true;
        $message1 = 'Error in updating! Please try again! ';
      }


  }


  if (isset($_POST['Field1'])) {
    $id = $_POST['objectID'];
    $set = '';
    $x = 1;
//var_dump($_POST);
    foreach ($_POST as $name => $value) {
      $set .= "{$name} = ?";
      if ($x < count($_POST)) {
        $set .= ', ';
      }
      $x++;
    }
    $qry = "UPDATE `object` SET {$set} WHERE objectID = " . $id;

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
      $success = true;
      $message = 'Successfully updated the Metadata!';
    } else {
      $failure = true;
      $message = 'Error! Something went wrong, Please try again . ';
    }

  }
  $q = $conn->query("SELECT * FROM SL_Objects WHERE status = 1 ORDER BY length(`ColumnName`) , `ColumnName`");
  $objects = $q->fetchAll(PDO::FETCH_OBJ);
  $sections = array_map(function ($x) {
    return $x->section;
  }, $objects);
  $sections = array_unique($sections);
  sort($sections);

  foreach ($sections as $index => $section) {
    foreach ($objects as $object) {
      if ($object->section == $section) {
        $grouped_object[$index][] = $object;
      }
    }
  }
  if ($id) {
    $query = $conn->query("SELECT * FROM `object` WHERE objectID = " . $id);
    if ($query) {
      $r = $query->fetchAll(PDO::FETCH_OBJ);
      $result = $r[0];
      //var_dump($result->File);
    }
  } else {

  }
  require_once 'header.php'
?>
  <!-- MAIN CONTENT-->
  <div class="main-content">
  <div class="section__content section__content--p30">
  <div class="container-fluid">
  <div class="card">
    <div class="card-header">
      <strong>Edit Meta Tags</strong>
    </div>
    <div class="row">

      <div class="col-lg-12 p-t-30">
        <div class="card-body card-block p-t-0 p-b-0">
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
        </div>
      </div>

      <div class="col-lg-6 col-md-12">
        <div class="card-body card-block m-t-30" style="height: 95% !important;">
          <div class="col-lg-12">
            <div class="card-body card-block p-t-0 p-b-0">
              <?php if ($failure1) { ?>
                <div class="alert alert-danger" role="alert">
                  <?php echo $message1; ?>
                </div>
              <?php } ?>
              <?php if ($success1) { ?>
                <div class="alert alert-success" role="alert">
                  <?php echo $message1; ?>

                </div>
              <?php } ?>
            </div>
          </div>
          <form action="" method="post" enctype="multipart/form-data">
          <div class="row mb-3 form-group">
            <div class="col-12 text-secondary">
              <div class="">
                <input type="hidden" name="oid" value="<?php echo $temp['objectID'] ?>"/>
                <input type="file" name="meta_file" id="meta_file" class="form-control" value="<?= $temp['UI']; ?>" hidden/>
              </div>
              <div class="overview-item overview-item--c1 mb-0">
                <div class="overview__inner text-center">
                  <div class="overview-box clearfix p-b-15">


                    <div class="dashboard-card-titles">
                      <label style="color: white; font-size: 10px" for="meta_file">
                        <div class="dashboard-card-titles">
                          <p style="color: white; font-size: 15px">Replace File</p>
                        </div>
                        <div class="icon">
                          <i class="zmdi zmdi-search-replace"></i>
                        </div>
                      </label>
                    </div>
                    <span id="file-chosen" class="text-white"></span>
                  </div>
                </div>
              </div>

            </div>
          </div>
            <div class="row mb-3 text-center">
              <div class="col-12 p-1 m-l-15">
                <input type="submit" name="update_file" id="update_file" class="btn btn-primary px-4"
                       value="Save">
                <button class="btn btn-danger px-3" id="resetFileForm">
                  <i class="zmdi zmdi-format-clear-all"></i> Clear
                </button>
              </div>
            </div>
          </form>
          <iframe class="border p-2" style="box-shadow: #b9bec1 0px 0px 5px 0px; width: 100% !important; height: 80% !important; margin-bottom: 50px !important;" src="<?= $temp['File'] ?>"></iframe>
        </div>
      </div>
      <div class="col-lg-6 col-md-12">

        <div class="card-body card-block">
          <form class="form" action="" method="POST">
            <?php
              foreach ($grouped_object as $key => $sl_objects) :
              $section_name = $sl_objects[0]->SectionDisplay;
              $section = $sl_objects[0]->section;
              ?>
              <div class="card-header border bg-gray-300 m-t-30 <?= $class; ?>"  style="box-shadow: #b9bec1 0px 0px 5px 0px">
                <strong><?php echo $section_name; ?></strong>
                <!--<div class="text-right">
                  <i class="fas fa-arrow-circle-down"></i>
                </div>-->
              </div>
              <fieldset class="border p-2 <?php echo 'Fieldset' . $class; ?>" style="box-shadow: #b9bec1 0px 0px 5px 0px">
                <legend class="w-auto text-hide"><?php echo $section_name; ?></legend>
                <?php foreach ($sl_objects as $sl_object) : ?>
                  <?php
                  switch ($sl_object->FieldType):
                    case 'dropdown-CV':
                      if ($sl_object->Options == "CV_Source_Type") :
                        $q = $conn->query("SELECT * FROM CV_Source_Type WHERE status = 1 ORDER BY listorder");
                        if ($q) :
                          $source_types = $q->fetchAll(PDO::FETCH_OBJ);
                          ?>
                          <div class="form-group">
                            <label><?php echo $sl_object->display; ?></label>
                            <select name="<?php echo $sl_object->ColumnName; ?>" class="form-control">
                              <?php foreach ($source_types as $source_type) : ?>
                                <option <?php echo $result->{$sl_object->ColumnName} == $source_type->ID ? "selected" : "" ?>
                                  value="<?= $source_type->ID; ?>">
                                  <?php echo $source_type->Name; ?>
                                </option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                        <?php
                        endif;
                      endif;
                      break;
                    case 'text':
                      ?>
                      <div class="form-group">
                        <label><?php echo $sl_object->display; ?></label>
                        <input value="<?= $result->{$sl_object->ColumnName}; ?>"
                               name="<?php echo $sl_object->ColumnName; ?>" type="text" class="form-control"
                               placeholder="Enter value">
                      </div>
                      <?php
                      break;
                    case 'textarea':
                      ?>
                      <div class="form-group">
                        <label><?php echo $sl_object->display; ?></label>
                        <textarea name="<?php echo $sl_object->ColumnName; ?>" class="form-control"
                                  placeholder="textarea"
                                  rows="5"><?php echo $result->{$sl_object->ColumnName}; ?></textarea>
                      </div>
                      <?php
                      break;
                    case 'radio':
                      ?>
                      <div class="form-group">
                        <label class=" form-control-label"><?php echo $sl_object->display; ?></label>
                        <div>
                          <?php
                            $options = explode(";", $sl_object->Options);
                            foreach ($options as $option) :
                              ?>
                              <div class="form-check form-check-inline">
                                <input <?php echo $result->{$sl_object->ColumnName} == $option ? "checked" : "" ?>
                                  name="<?php echo $sl_object->ColumnName; ?>"
                                  class="form-check-input" type="radio"
                                  value="<?= $option; ?>">
                                <label class="form-check-label"> <?php echo $option; ?></label>
                              </div>
                            <?php
                            endforeach;
                          ?>
                        </div>
                      </div>
                      <?php
                      break;
                    case 'dropdown':
                      ?>
                      <div class="form-group">
                        <label><?php echo $sl_object->display; ?></label>
                        <select name="<?php echo $sl_object->ColumnName; ?>" class="form-control">
                          <?php
                            $options = explode(";", $sl_object->Options);
                            foreach ($options as $option) :
                              ?>
                              <option <?php echo $result->{$sl_object->ColumnName} == $option ? "selected" : "" ?>
                                value="<?= $option; ?>"><?php echo $option; ?></option>
                            <?php
                            endforeach;
                          ?>
                        </select>
                      </div>
                      <?php
                      break;
                    default:
                      break;
                  endswitch;
                  ?>
                <?php endforeach; ?>

              </fieldset>

            <?php $class = $class +1; endforeach; ?>
            <input type="hidden" name="objectID" value="<?= $id; ?>"/>
            <button class="btn btn-success mt-4" type="submit">Submit</button>

          </form>
        </div>

      </div>
    </div>
  </div>
  <script>
      $(document).ready(function () {
          var ln = <?php echo json_encode($class);?>;
          const actualBtn = document.getElementById('meta_file');

          const fileChosen = document.getElementById('file-chosen');
          actualBtn.addEventListener('change', function () {
              for (i = 0; i < this.files.length; i++) {
                  fileChosen.innerHTML += this.files[i].name + '<br/>';

              }

          })

          for (let i = 0; i < ln; i++) {
              let str = 'Fieldset' + i;
              $('.'+ str).hide();
              $('.'+i).click(function() {
                  $( '.'+ str ).toggle( "slow", function() {
                  });
              });

          }
          for (let i = 0; i < ln; i++) {
              let str = 'Fieldset' + i;
              $('.'+ str).hide();
              if(i == 0){
                  $('.'+ str).show();
              }

          }

      });

  </script>
<?php require_once 'footer.php' ?>