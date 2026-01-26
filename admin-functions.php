
<?php
function EVTable($toDisplay) {

    if (empty($toDisplay) or $toDisplay == null) {
        echo '<p class="text-warning">No stations available.</p>';
        return;
    }

    ?>
    <div class="table-responsive-lg">
      <table class="table table-striped-columns table-dark table-hover">
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

function EVTableToEdit($toDisplay) {

    if (empty($toDisplay) or $toDisplay == null) {
        echo '<p class="text-warning">No stations available.</p>';
        return;
    }

    ?>
    <div class="table-responsive-lg">
      <table class="table table-dark table-striped-columns table-hover">
        <thead>
          <tr>
            <?php
            foreach (array_keys($toDisplay[0]) as $col) {
                echo '<th scope="col">' . htmlspecialchars($col) . '</th>';
            }
            echo '<th scope="col">Edit info</th>';
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
              echo '<td><a class="btn-btn-info" role="button" href=' ."editEV.php?select={$row['station_id']}" .'> Edit </a></td>';
              echo '</tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
    <?php
}

function EVSearch($toDisplay) {

    if (empty($toDisplay) or $toDisplay == null) {
        echo '<p class="text-warning">No stations available.</p>';
        return;
    }

	?>
	<div class="table-responsive-lg" style="width:100%; overflow-x: auto;">
	<table class="table table-hover table-striped-columns table-secondary">
	<thead>
	<tr>
	<?php
	foreach (array_keys($toDisplay[0]) as $col) {
		echo '<th scope="col">' . htmlspecialchars($col) . '</th>';
	}
	echo '<th scope="col">Status</th>';
	?>
	</tr>
	</thead>
	<tbody>
	<?php

	foreach ($toDisplay as $row) {
	// Output the row
	echo '<tr>';
		foreach ($row as $col=>$cell) {
      echo '<td>' . $cell . '</td>';
    }
		if ($row['available'] == 0) {echo '<td><span class="text-danger">Full<span></td>';} else {echo '<td><span class="text-success">available<span></td>';}
  echo '</tr>';
	}


	?>
	</tbody>
	</table>
	</div>
	<?php
	}


function userTable($toDisplay) {

    if (empty($toDisplay) or $toDisplay == null) {
        echo '<p class="text-warning">No user info available.</p>';
        return;
    }

    ?>
    <div class="table-responsive-lg">
      <table class="table table-info table-striped-columns table-hover">
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

function activeUsers($toDisplay) {

  if (empty($toDisplay) or $toDisplay == null) {
        echo '<p class="text-warning">No customers active at the moment.</p>';
        return;
    }
    foreach ($toDisplay as $row) {
     
      ?>
      <div class="card mb-3" style="max-width: 540px;"> 
      <div class="row g-0">
      <div class="col-md-6">
      <img src="./images/user.jpg" class="img-fluid rounded-start w-auto h-100" alt="...">
      </div>
      <div class="col-md-6">
      <div class="card-body">
      
      <h5 class="card-title">Active Customers</h5>
      <?php
      foreach ($row as $col=>$value) {
          echo '<p class="card-text">' . htmlspecialchars($col) .": " .htmlspecialchars($value) . '</p>';
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
      
      
      

	