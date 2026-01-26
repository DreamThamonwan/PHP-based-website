<?php
session_start();

if (isset($_GET['action']) && $_GET['action']==='logout') {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

//Cache the current user
if (isset($_SESSION['current_user']['name']) && isset($_SESSION['current_user']['role'])) {
	if ($_SESSION['current_user']['role'] === "Customer") {
		header('Location: customerPanel.php');
		exit;
	} else if($_SESSION['current_user']['role'] === "Administrator") {
		
	}
}

	require_once "admin-functions.php";
	require_once 'classes.php';// loads trait & classes

	class validationEdit {
		private $submit;
		private array $regex = [
			"address"=>array(
				"regex"=>"/^.+$/",
				"emptyError"=>"Please fill in the station's address"
			), 
			"city"=>array(
				"regex"=>"/^.+$/",
				"emptyError"=>"Please fill in the station's city"
			), 
			"state"=>array(
				"regex"=>"/^.+$/",
				"emptyError"=>"Please fill in the station's state"
			),
			"description"=>array(
				"regex"=>"/^.+$/",
				"emptyError"=>"Please fill in the station's description"
			),
			"cost_per_hr"=>array(
				"regex"=>"/^[1-9][0-9]*(\.[0-9][0-9])$/",
				"emptyError"=>"Please fill in the cost per hour",
				"formatError"=>"Please fill in the cost per hour with 2 decimal"
			),
			"capacity"=>array(
				"regex"=>"/^[1-9][0-9]*$/",
				"emptyError"=>"Please fill in the station's capacity",
				"formatError"=>"Please fill in the capacity with integer"
			),
			"availability"=>array(
				"regex"=>"/^[1-9][0-9]*$/",
				"emptyError"=>"Please fill in the station's availability",
				"formatError"=>"Please fill in the availability with integer",
				"identicalError"=>"Please fill in the availability as the same number as capacity"
			)];
					
		private array $errorFlag = [
			"address"=>array(
			"emptyError"=>1
			), 
			"city"=>array(
				"emptyError"=>1
			), 
			"state"=>array(
				"emptyError"=>1
			),
			"description"=>array(
				"emptyError"=>1
			),
			"cost_per_hr"=>array(
				"emptyError"=>1,
				"formatError"=>1
			),
			"capacity"=>array(
				"emptyError"=>1,
				"formatError"=>1
			),
			"availability"=>array(
				"emptyError"=>1,
				"formatError"=>1,
				"identicalError"=>1
			)];
			
					
			public function __construct($submit) {
				$this->submit = $submit;
			}
					
			public function cleanData(array $data) {
				foreach($data as $dataField => $dataValue) {
					$data[$dataField] = trim($dataValue);
					$data[$dataField] = stripslashes($dataValue);
				}
				return $data;
						
			}

			public function regexValidation(array $data) {
				if(isset($_POST['submitEdit'])) {
					$this->submit = $_POST['submitEdit'];
					foreach ($this->regex as $errorField => $regexArray) {
						 $this->errorFlag[$errorField]['emptyError'] = empty($data[$errorField]) ? 0 : 1;
						if (! empty($data[$errorField])) {
							foreach ($regexArray as $errorName => $errorValue) {
								
								if ($errorName == "regex") {
									$dummy = array ($errorField=>array("regex"=>$errorValue));
								}
							
								if ($errorName !== "identicalError" && $errorName !== "emptyError") {
									$this->errorFlag[$errorField]["formatError"] = preg_match($dummy[$errorField]["regex"], $data[$errorField]);
								} 
							}
						} else {
							
						}
					}
						$this->errorFlag["availability"]["identicalError"] = (int) ($data['availability'] === $data['capacity']);
					
				}
				return $this->errorFlag;
			}
			
			public function verification() {
				foreach ($this->errorFlag as $errorField => $valueArray) {
					foreach ($valueArray as $errorName => $errorValue) {
						//echo "$errorValue";
						if ($errorValue == 0) {
							return false;
						} 
					}
				}
				return true;	
			}	
	}
	
	
	$regex = [
			"address"=>array(
				"regex"=>"/^.+$/",
				"emptyError"=>"Please fill in the station's address"
			), 
			"city"=>array(
				"regex"=>"/^.+$/",
				"emptyError"=>"Please fill in the station's city"
			), 
			"state"=>array(
				"regex"=>"/^.+$/",
				"emptyError"=>"Please fill in the station's state"
			),
			"description"=>array(
				"regex"=>"/^.+$/",
				"emptyError"=>"Please fill in the station's description"
			),
			"cost_per_hr"=>array(
				"regex"=>"/^[1-9][0-9]*(\.[0-9][0-9])$/",
				"emptyError"=>"Please fill in the cost per hour",
				"formatError"=>"Please fill in the cost per hour with 2 decimal"
			),
			"capacity"=>array(
				"regex"=>"/^[1-9][0-9]*$/",
				"emptyError"=>"Please fill in the station's capacity",
				"formatError"=>"Please fill in the capacity with integer"
			),
			"availability"=>array(
				"regex"=>"/^[1-9][0-9]*$/",
				"emptyError"=>"Please fill in the station's availability",
				"formatError"=>"Please fill in the availability with integer",
				"identicalError"=>"Please fill in the availability as the same number as capacity"
				
	)];

	$errorFlag = [
			"address"=>array(
			"emptyError"=>1
			), 
			"city"=>array(
				"emptyError"=>1
			), 
			"state"=>array(
				"emptyError"=>1
			),
			"description"=>array(
				"emptyError"=>1
			),
			"cost_per_hr"=>array(
				"emptyError"=>1,
				"formatError"=>1
			),
			"capacity"=>array(
				"emptyError"=>1,
				"formatError"=>1
			),
			"availability"=>array(
				"emptyError"=>1,
				"formatError"=>1,
				"identicalError"=>1
	)];
	
?>

<!DOCTYPE HTML>
<html>
<head>
<title>EasyEV-Charging</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins&display=swap">  <!-- Google Fonts CSS -->
	<link rel="stylesheet" href="style_sheet.css"><!-- Main CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">  <!-- Bootstrap CSS -->
	<!-- Mainbar -->
	<nav class="navbar fixed-top navbar-expand-lg shadow-lg bg-dark" data-bs-theme="dark">
		<!-- Main Navbar content -->
		<div class="container-fluid">
			<a class="navbar-brand text-warning"><b>EasyEV-Charging</b></a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNav">
				 <ul class="nav nav-underline me-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link" id="backHome" href="index.php">Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link"  href="adminPanel.php">Admin Dashboard</a>
				</li>
				<li class="nav-item should-hide">
				  <!-- Collapse 1 -->
					<a class="nav-link" role="button" data-bs-toggle="collapse" href="#collapse1" aria-current="true" aria-expanded="true" aria-controls="collapse1">
					  <span>EV stations</span>
					</a>
					<!-- Collapse 1 -->
				</li>
				<li class="nav-item should-hide">
					<!-- Collapse 2 -->
					<a class="nav-link" role="button" data-bs-toggle="collapse" href="#collapse2" aria-current="true" aria-expanded="true" aria-controls="collapse2">
					  <span>Customers</span>
					</a>
					<!-- Collapsed content 2 -->
					<!-- Collapse 2 -->
				</li>
				<li class="nav-item">
					<a class="nav-link" href="index.php?action=logout">Log out</a>
				</li>
				</ul>
			</div>
		</div>
		<!-- Collapsed content 1 -->
		<div id="collapse1" class="collapse should-hide" style="background-color: white; width: 100%;">
			<ul>
				<li class="list-group-item">
					<a href="editEV.php" class="text-reset">List charging locations</a>
				</li>
				<li class="list-group-item">
					<a href="adminPanel.php?action=listAvailable" class="text-reset">List available charging locations</a>
				</li>
				<li class="list-group-item">
					<a href="adminPanel.php?action=listFull" class="text-reset">List full charging locations</a>
				</li>
				<li class="list-group-item">
					<a href="adminPanel.php?action=add" class="text-reset">Add charging locations</a>
				</li>
				<li class="list-group-item">
					<a href="adminPanel.php?action=edit" class="text-reset">Edit charging locations</a>
				</li>
			</ul>
		</div>
		<div id="collapse2" class="collapse should-hide" style="background-color: white; width: 100%;">
			<ul >
				<li class="list-group-item py-1">
					<a href="adminPanel.php?action=listAll" class="text-reset"><span>List all users</span></a>
				</li>
				<li class="list-group-item py-1">
					<a href="adminPanel.php?action=listActive" class="text-reset"><span>List active users</span></a>
				</li>
			</ul>
		</div>		
	</nav>
	<!-- Mainbar -->
	
	<!-- Sidebar -->
		<nav id="sidebarMenu" class="collapse d-lg-block sidebar collapse bg-dark shadow-lg" data-bs-theme="dark"> 
			<div class="position-sticky sidebar-content">
				<div class="list-group list-group-flush mx-3 mt-4">
				  <!-- Collapse 1 -->
					<a class="list-group-item list-group-item-action py-2 ripple text-white" role="button" data-bs-toggle="collapse" href="#collapse1" aria-current="true" aria-expanded="true" aria-controls="collapseExample1">
					  <span>EV stations</span>
					</a>
					<!-- Collapsed content 1 -->
					<ul id="collapse1" class="collapse list-group list-group-flush">
					  <li class="list-group-item py-1">
						<a href="#allStations" class="text-reset">List charging locations</a>
					  </li>
					  <li class="list-group-item py-1">
						<a href="adminPanel.php?action=listAvailable" class="text-reset">List available charging locations</a>
					  </li>
					  <li class="list-group-item py-1">
						<a href="adminPanel.php?action=listFull" class="text-reset">List full charging locations</a>
					  </li>
					  <li class="list-group-item py-1">
						<a href="adminPanel.php?action=add" class="text-reset">Add charging locations</a>
					  </li>
					</ul>
					<!-- Collapse 1 -->
					
					<!-- Collapse 2 -->
					<a class="list-group-item bg-dark list-group-item-action py-2 ripple text-white" role="button" data-bs-toggle="collapse" href="#collapse2" aria-current="true" aria-expanded="true" aria-controls="collapseExample1">
					  <span>Customers</span>
					</a>
					<!-- Collapsed content 2 -->
					<ul id="collapse2" class="collapse list-group list-group-flush">
					  <li class="list-group-item py-1">
						<a href="adminPanel.php?action=listAllU" class="text-reset">List all users</a>
					  </li>
					  <li class="list-group-item py-1">
						<a href="adminPanel.php?action=listActiveU" class="text-reset">List active users</a>
					  </li>
					</ul>
					<!-- Collapse 2 -->
				</div>
			</div>
		</nav>	  
	<!-- Sidebar -->

</head>
<body>
	<main id="admin-main">

		<section id="allStations" class="container-fluid flex-column p-3">
			
			<div class="d-flex m-1 row flex-column align-items-center flex-nowrap overflow-auto shadow-lg bg-body-tertiary rounded">
				<h4>Current EV Locations</h4>
				<p>Click edit at the row you want in the table below</p>
                <!--Check admin action-->
			<?php 
				try {
                    if (! isset($_POST['submitEdit'])) {
                        $admin = new Admin();
                        $allEVs = $admin->listAllEVs();
                        //Display table
                        EVTableToEdit($allEVs);

                    }
    
                    if (isset($_GET['select'])) {
                        $selectEVs = $admin->searchEV($_GET['select'], "");
						$selectEV = $selectEVs[0];
						//var_dump($selectEV[0]);
                    }
                    
                    if (isset($_POST['submitEdit'])) {
                        
                        $fields = array("name", "address", "city", "state", "description", "cost_per_hr", "capacity", "availability");
                        $data = array();
                        foreach ($fields as $f) {
                            $data[$f] = $_POST[$f];
                        } 
                        $st_id = $_POST['station_id'];
                        
                        $check = new validationEdit($_POST['submitEdit']);
                        $cleanData = $check->cleanData($data);
                        $errorFlag = $check->regexValidation($cleanData);
                        $verify = $check->verification();
                        if ($verify == true) {
                            try {
                                $ev = new Admin();
                                $result = $ev->editEV(
                                    $st_id,
                                    $cleanData["name"],
                                    $cleanData["address"],
                                    $cleanData["city"],
                                    $cleanData["state"],
                                    $cleanData["description"],
                                    $cleanData["cost_per_hr"],
                                    $cleanData["capacity"],
                                    $cleanData["availability"],
                                    
                                );
								$html = '<div class="alert w-50 alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 10vh; z-index: 3;">
								<p>Successfully editing the selected location!</p>';
								$html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
								$html .= '</div>';
								echo $html;
                                $allEVs = $ev->listAllEVs();
                                //Display table
                                EVTableToEdit($allEVs);
                            } catch (Exception $e) {
                                echo "<p class='text-danger'>Error $e spotted!</p>";
                            }
                        } else {
                            echo "<p class='text-danger'>Invalid inputs!</p>";
							//Redisplay the table again
							$ev = new Admin();
							$allEVs = $ev->listAllEVs();
							EVTableToEdit($allEVs);
                        }
                    }

				} catch (Exception $e) {
					echo "<p class='text-danger'>Error {$e} found! no data available to display</p>";
				}

			?>
		</section>

        <section>
			<h4>Select one above and Edit their information here!</h4>
            <form class="row g-3" action="editEV.php" method="post">
                <input type="hidden" name="station_id" value="<?php if (isset($selectEV)) {echo $selectEV["station_id"];} ?>">
                <div class="col-sm-3 col-md-6">
                <label for="InputEV" class="form-label">name</label>
                <input type="text" name="name" class="form-control" id="InputEV" value="<?php if (isset($selectEV)) {echo $selectEV["name"];} ?>">
                </div>
                <div class="col-sm-3 col-md-6">
                <label for="InputAddress" class="form-label">Address</label>
                <input type="text" name="address" class="form-control <?php if ($errorFlag["address"]["emptyError"] !== 1) {echo "is-invalid";}?>" id="InputAddress" value="<?php if (isset($selectEV)) {echo $selectEV["address"];} ?>">
                <div class="invalid-feedback">
                <?php if ($errorFlag["address"]["emptyError"] !== 1) {echo "".$regex["address"]["emptyError"]."";} ?>
                </div>
                </div>
                <div class="col-sm-3 col-md-6">
                <label for="InputCity" class="form-label">City</label>
                <input type="text" name="city" class="form-control <?php if ($errorFlag["city"]["emptyError"] !== 1) {echo "is-invalid";}?>" id="InputCity"  value="<?php if (isset($selectEV)) {echo $selectEV["city"];} ?>">
                <div class="invalid-feedback">
                <?php if ($errorFlag["city"]["emptyError"] !== 1) {echo "".$regex["city"]["emptyError"]."";} ?>
                </div>
                </div>
                <div class="col-sm-3 col-md-6">
                <label for="InputState" class="form-label">State</label>
                <input type="text" name="state" class="form-control <?php if ($errorFlag["state"]["emptyError"] !== 1) {echo "is-invalid";}?>" id="InputState" value="<?php if (isset($selectEV)) {echo $selectEV["state"];} ?>">
                <div class="invalid-feedback">
                <?php if ($errorFlag["state"]["emptyError"] !== 1) {echo "".$regex["state"]["emptyError"]."";} ?>
                </div>
                </div>
                <div class="col-sm-3 col-md-6">
                <label for="InputDescription" class="form-label">Description</label>
                <input type="text" name="description" class="form-control <?php if ($errorFlag["description"]["emptyError"] !== 1) {echo "is-invalid";}?>" id="InputDescription" value="<?php if (isset($selectEV)) {echo $selectEV["description"];} ?>">
                <div class="invalid-feedback">
                <?php if ($errorFlag["description"]["emptyError"] !== 1) {echo "".$regex["description"]["emptyError"]."";} ?>
                </div>
                </div>
                <div class="col-sm-3 col-md-6">
                <label for="InputCost" class="form-label">Cost per hour</label>
                <input type="text" name="cost_per_hr" class="form-control <?php if ($errorFlag["cost_per_hr"]["emptyError"] !== 1 or $errorFlag["cost_per_hr"]["formatError"] !== 1) {echo "is-invalid";}?>" value="<?php if (isset($selectEV)) {echo $selectEV["cost_per_hr"];} ?>">
                <div class="invalid-feedback">
                <?php if ($errorFlag["cost_per_hr"]["emptyError"] !== 1) {echo "".$regex["cost_per_hr"]["emptyError"]."";}
                else if ($errorFlag["cost_per_hr"]["formatError"] !== 1) {echo "".$regex["cost_per_hr"]["formatError"]."";} ?>
                </div>
                </div>
                <div class="col-sm-3 col-md-6">
                <label for="InputCapacity" class="form-label">Capacity</label>
                <input type="text" name="capacity" class="form-control <?php if ($errorFlag["capacity"]["emptyError"] !== 1 or $errorFlag["capacity"]["formatError"] !== 1) {echo "is-invalid";}?>" value="<?php if (isset($selectEV)) {echo $selectEV["capacity"];} ?>" />
                <div class="invalid-feedback">
                <?php if ($errorFlag["capacity"]["emptyError"] !== 1) {echo "".$regex["capacity"]["emptyError"]."";}
                else if ($errorFlag["capacity"]["formatError"] !== 1) {echo "".$regex["capacity"]["formatError"]."";} ?>
                </div>
                </div>
                <div class="col-sm-3 col-md-6">
                <label for="InputAvailable" class="form-label">Available</label>
                <input type="text" name="availability" class="form-control <?php if ($errorFlag['availability']['emptyError'] !== 1 or $errorFlag['availability']['formatError'] !== 1 or $errorFlag['availability']['identicalError'] !== 1) {echo 'is-invalid';}?>" value="<?php if (isset($selectEV)) {echo $selectEV["available"];} ?>" />
                <div class="invalid-feedback">
                <?php if ($errorFlag["availability"]["emptyError"] !== 1) {echo "".$regex["availability"]["emptyError"]."";}
                else if ($errorFlag["availability"]["formatError"] !== 1) {echo "".$regex["availability"]["formatError"]."";}
                else if ($errorFlag["availability"]["identicalError"] !== 1) {echo "".$regex["availability"]["identicalError"]."";} ?>
                </div>
                </div>
                <button type="submit" name="submitEdit" class="btn btn-primary" style="width: 150px; margin: 20px auto;">Edit</button>
                </form>
        </section>

	</main>
</body>
<footer style="font-size: 0.5rem;">
	<ul>
		<li>
			Photo by <a href="https://unsplash.com/@nathanareboucas?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">Nathana Rebouças</a> on <a href="https://unsplash.com/photos/woman-in-white-grey-and-black-plaid-shirt-enaNfAjiDGg?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">Unsplash</a>
        </li>
		<li><a href="https://www.flaticon.com/free-icons/high-price" title="high-price icons">High-price icons created by Vectorslab - Flaticon</a></li>
		<li><a href="https://www.flaticon.com/free-icons/booking" title="Booking icons">Booking icons created by Peerapak Takpho - Flaticon</a></li>
	</ul>
</footer>
</html>