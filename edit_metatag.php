<?php

  session_start();

  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once("db_files/db_config.php");
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
    }
  } else {
    Redirect::to(404);
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
      <div class="col-lg-6 col-md-12">
        <div class="card-body card-block m-t-30" style="height: 95% !important;">
          <iframe class="border p-2" style="box-shadow: #b9bec1 0px 0px 5px 0px; width: 100% !important; height: 100% !important;" src="<?php echo $temp['File'] ?>"></iframe>
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
              <div class="card-header border bg-gray-300 m-t-30 <?php echo $class; ?>"  style="box-shadow: #b9bec1 0px 0px 5px 0px">
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
                                  value="<?php echo $source_type->ID; ?>">
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
                        <input value="<?php echo $result->{$sl_object->ColumnName}; ?>"
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
                                  value="<?php echo $option; ?>">
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
                                value="<?php echo $option; ?>"><?php echo $option; ?></option>
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
            <input type="hidden" name="objectID" value="<?php echo $id; ?>"/>
            <button class="btn btn-outline-success mt-4" type="submit">Submit</button>
            <!-- Modal -->
            <div class="modal fade" id="success-dialog" tabindex="-1" role="dialog" data-backdrop="false"
                 aria-labelledby="success-dialog" aria-hidden="true">
              <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                <div class="modal-content  bg-success">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Metatags Data Updated</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body text-dark">
                    <p>Metatags data has been updated!</p>
                  </div>
                  <div class="modal-footer">
                    <button type="button" onclick="location.href = 'metatag.php';" class="btn bg-dark text-white"
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
                    <button type="button" onclick="location.href = 'metatag.php';" class="btn bg-dark text-white"
                            data-dismiss="modal">Close
                    </button>
                  </div>
                </div>
              </div>
            </div>

          </form>
        </div>

      </div>
    </div>
  </div>
  <script>
      $(document).ready(function () {
          var ln = <?php echo json_encode($class);?>;


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