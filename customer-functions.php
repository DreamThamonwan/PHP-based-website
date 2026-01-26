
<?php
function EVTable($toDisplay) {

    if (empty($toDisplay) or $toDisplay == null) {
        echo '<p class="text-warning">No stations available.</p>';
        return;
    }

    ?>
    <div class="table-responsive-lg">
      <table class="table table-dark table-striped table-hover">
        <thead>
          <tr>
            <?php
            foreach (array_keys($toDisplay[0]) as $col) {
                echo '<th scope="col">' . htmlspecialchars($col) . '</th>';
            }
            echo '<th scope="col">' . 'finish?' . '</th>';
            ?>
          </tr>
        </thead>
        <tbody>
          <?php
		      // Output each row
          foreach ($toDisplay as $row) {
              echo '<tr>';
              foreach ($row as $cell) {
                  echo '<td>' . htmlspecialchars($cell) . '</td>';
              }
              echo '<td>' 
              ?>
              <a role="button" class="btn btn-primary" href="customerCheckOut.php?selectEV=<?php echo $row['station_Id'];?>&selectSession=<?php echo $row['session_Id'];?>">Check out</a>
              <?php
              echo '</td>';
              echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
    <?php
}

function EVSearchCus($toDisplay) {

    if (empty($toDisplay) or $toDisplay == null) {
        echo '<p class="text-warning">No stations available.</p>';
        return;
    }
    foreach ($toDisplay as $row) {
      ?>
      <div class="card mb-3 shadow rounded" style="max-width: 540px;"> 
      <div class="row g-0">
      <div class="col-md-6">
      <img src="./images/ev-card.jpg" class="img-fluid rounded-start w-auto h-100" alt="...">
      </div>
      <div class="col-md-6">
      <div class="card-body">
      
      <h5 class="card-title">EV location</h5>
      <?php
      foreach ($row as $col=>$value) {
        if ($col !== 'station_id' && $col !== 'available') {
          echo '<p class="card-text">' . htmlspecialchars($col) .": " .htmlspecialchars($value) . '</p>';
        } 
      }
      if ($row['available'] == 0) {
        echo 'Status: <p class="card-text text-danger">Full</p>';
      } else {
        echo "<p class='card-text text-success'>available: {$row['available']}</p>";
      }
      if (isset($_SESSION['current_user']) && $_SESSION['current_user']['role'] == "Customer" && $row['available'] !== 0) {
        ?>
        <a href="customerCheckIn.php?selectEV=<?php echo $row['station_id'];?>" role="button" class="btn btn-primary">Check in</a>
        <?php
      }
      ?>
      </div>
        </div>
      </div>
    </div>
    <?php
    }
    ?>
          
          
    <?php
	}
?>
<?php
function availableEV($toDisplay) {

  if (empty($toDisplay) or $toDisplay == null) {
        echo '<p class="text-warning">No stations available.</p>';
        return;
    }
    foreach ($toDisplay as $row) {
      if ($row['available'] == 0) {
        continue;
      }
      ?>
      <div class="card mb-3 shadow rounded" style="max-width: 540px;"> 
      <div class="row g-0">
      <div class="col-md-6">
      <img src="./images/ev-card.jpg" class="img-fluid rounded-start w-auto h-100" alt="...">
      </div>
      <div class="col-md-6">
      <div class="card-body">
      
      <h5 class="card-title">Charging location</h5>
      <?php
      foreach ($row as $col=>$value) {
        if ($col !== 'station_id' && $col !== 'available') {
          echo '<p class="card-text">' . htmlspecialchars($col) .": " .htmlspecialchars($value) . '</p>';
        } 
      }
      if ($row['available'] !== 0) {
        echo "<p class='card-text text-success'>available: {$row['available']}</p>";
      }
       if (isset($_SESSION['current_user']) && $_SESSION['current_user']['role'] == "Customer") {
        ?>
      <a href="customerCheckIn.php?selectEV=<?php echo $row['station_id'];?>" role="button" class="btn btn-primary">Check in</a>
      <?php
       }
      ?>
      </div>
        </div>
      </div>
    </div>
    <?php
    }

    ?>  
          
    <?php
    
}

function checkoutTable($toDisplay) {

    if (empty($toDisplay) or $toDisplay == null) {
        echo '<p class="text-warning">No stations available.</p>';
        return;
    }

    ?>
    <div class="table-responsive-lg">
      <table class="table table-dark table-hover">
        <thead>
          <tr>
            <?php
            foreach (array_keys($toDisplay[0]) as $col) {
                echo '<th scope="col">' . htmlspecialchars($col) . '</th>';
            }
            ?>
          </tr>
        </thead>
        <tbody>
          <?php
		      // Output each row
          foreach ($toDisplay as $row) {
              echo '<tr>';
              foreach ($row as $cell) {
                  echo '<td>' . htmlspecialchars($cell) . '</td>';
              }
              echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
    <?php
}

function checkinTable($toDisplay) {
  
  if (empty($toDisplay) or $toDisplay == null) {
    echo '<p class="text-warning">No stations available.</p>';
    return;
  }

  
  ?>
  <div class="table-responsive-lg">
  <table class="table table-success table-hover">
  <thead>
  <tr>
  <?php
  foreach (array_keys($toDisplay[0]) as $col) {
    echo '<th scope="col">' . htmlspecialchars($col) . '</th>';
  }
  echo '<th>Wanna check in?</th>';

  ?>
  </tr>
  </thead>
  <tbody>
  <?php
  // Output each row
  foreach ($toDisplay as $row) {
    if ($row['available'] == 0) {
      continue;
    }
    echo '<tr>';
    foreach ($row as $cell) {
      echo '<td>' . htmlspecialchars($cell) . '</td>';
    }
    if (isset($_SESSION['current_user']) && $_SESSION['current_user']['role'] == "Customer") {
      echo '<td>' .'<a href="customerCheckIn.php?selectEV='. $row["station_id"] . '" role="button" class="btn btn-primary">Check in</a>';
    }
    echo '</tr>';
  }
  ?>
  </tbody>
  </table>
  </div>
  <?php
}