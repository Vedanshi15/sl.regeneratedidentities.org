<?php
  session_start();
  if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
  }
  require_once 'header.php';
  require_once("db_files/db_config.php");
  if (isset($_SESSION['user_id']) && $_SESSION['role'] = "Super Admin") {
    $query = $conn->query("SELECT * FROM `users`");
    $resultsH = array();
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
      $resultsH[] = $row;
    }
  } else {
    echo "You don't have permission";
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
      <h3 class="title-5 m-b-35">User List</h3>
      <div class="table-data__tool">
        <div class="table-data__tool-left">
        </div>
        <div class="table-data__tool-right">
          <a href="add_user.php" class="au-btn au-btn-icon au-btn--green au-btn--small">
            <i class="zmdi zmdi-plus"></i>add New</a>
        </div>
      </div>
      <div class="table-responsive table-responsive-data2">
        <table class="table table-data2">
          <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Organization</th>
            <th>Role</th>
          </tr>
          </thead>
          <tbody>
          <?php $ln = count($resultsH);
            for ($i = 0; $i < $ln; $i++):
              $temp = $resultsH[$i]; ?>
              <tr class="tr-shadow">
                <td><?php echo $temp['fname'] . " " . $temp['lname'] ?></td>
                <td><?php echo strlen(htmlspecialchars_decode($temp['email'])) > 1 ? htmlspecialchars_decode($temp['email']) : "Unknown"; ?></td>
                <td><?php echo strlen(htmlspecialchars_decode($temp['Organization'])) > 1 ? htmlspecialchars_decode($temp['Organization']) : "Unknown"; ?></td>
                <td><?php $role = null;
                    if ($temp['security'] == 0) {
                      $role = "Super Admin";
                    } else if ($temp['security'] == 1) {
                      $role = "Project Director";
                    } else if ($temp['security'] == 2) {
                      $role = "Archivists/Researcher";
                    } else {
                      $role = "Unknown";
                    }
                    echo $role;
                  ?>
                </td>

                <td>
                  <div class="table-data-feature">
                    <form action="update_user.php" method="post">
                      <input type="hidden" name="id" value="<?= $temp['id']; ?>"/>
                      <button class="item mr-2" name="update_user" data-toggle="tooltip" data-placement="top"
                              title="Edit">
                        <i class="zmdi zmdi-edit"></i>
                      </button>
                    </form>
                    <form action="delete_user.php" method="post">
                      <input type="hidden" name="id" value="<?= $temp['id']; ?>"/>
                      <button class="item" name="delete_user" data-toggle="tooltip" data-placement="top"
                              title="Delete">
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