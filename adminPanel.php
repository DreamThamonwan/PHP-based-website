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
	} 
} else {
	//Directing to home page if no information found 
	header('Location: index.php');
	exit;
}
	require_once "admin-functions.php";
	require_once 'classes.php';// loads trait & classes
	$admin = new Admin(); //Create admin object once to reuse in the entire page
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

	class validationEV {
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
				"formatError"=>"Please fill in the cost per hour with 2-decimal number"
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
				if(isset($_POST['submitAdd'])) {
					$this->submit = $_POST['submitAdd'];
					//Check empty
					$this->errorFlag["address"]["emptyError"] = empty($data["address"]) ? 0 : 1;
					$this->errorFlag["city"]["emptyError"] = empty($data["city"]) ? 0 : 1;
					$this->errorFlag["state"]["emptyError"] = empty($data["state"]) ? 0 : 1;
					$this->errorFlag["description"]["emptyError"] = empty($data["description"]) ? 0 : 1;
					$this->errorFlag["cost_per_hr"]["emptyError"] = empty($data["cost_per_hr"]) ? 0 : 1;
					$this->errorFlag["capacity"]["emptyError"] = empty($data["capacity"]) ? 0 : 1;
					$this->errorFlag["availability"]["emptyError"] = empty($data["capacity"]) ? 0 : 1;
					//Check format
					$this->errorFlag["cost_per_hr"]["formatError"] = preg_match($this->regex["cost_per_hr"]["regex"], $data["cost_per_hr"]);
					$this->errorFlag["capacity"]["formatError"] = preg_match($this->regex["capacity"]["regex"], $data["capacity"]);
					$this->errorFlag["availability"]["formatError"] = preg_match($this->regex["availability"]["regex"], $data["availability"]);
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
					  <span>Users</span>
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
					<a href="adminPanel.php?action=listAllEV#allStations" class="text-reset">List charging locations</a>
				</li>
				<li class="list-group-item">
					<a href="adminPanel.php?action=listAvailable#availableStations" class="text-reset">List available charging locations</a>
				</li>
				<li class="list-group-item">
					<a href="adminPanel.php?action=listFull#fullStations" class="text-reset">List full charging locations</a>
				</li>
				<li class="list-group-item">
					<a href="adminPanel.php?action=add" class="text-reset">Add charging locations</a>
				</li>
				<li class="list-group-item">
					<a href="editEV.php" class="text-reset">Edit charging locations</a>
				</li>
			</ul>
		</div>
		<div id="collapse2" class="collapse should-hide" style="background-color: white; width: 100%;">
			<ul >
				<li class="list-group-item py-1">
					<a href="adminPanel.php?action=listAllU#allUsers" class="text-reset"><span>List all users</span></a>
				</li>
				<li class="list-group-item py-1">
					<a href="adminPanel.php?action=listActiveU#activeUsers" class="text-reset"><span>List active users</span></a>
				</li>
			</ul>
		</div>		
	</nav>
	<!-- Mainbar -->
	
	<!-- Sidebar -->
		<nav id="sidebarMenu" class="navbar collapse d-lg-block sidebar collapse bg-dark shadow-lg" data-bs-theme="dark">
			<div class="position-sticky sidebar-content">
				<div class="list-group list-group-flush mx-3 mt-4">
				  <!-- Collapse 1 -->
					<a class="list-group-item list-group-item-action py-2 ripple" role="button" data-bs-toggle="collapse" href="#collapse1" aria-current="true" aria-expanded="true" aria-controls="collapse1">
					  <span>EV stations</span>
					</a>
					<!-- Collapsed content 1 -->
					<ul id="collapse1" class="collapse list-group list-group-flush" style="text-decoration: none;">
					  <li class="list-group-item py-1">
						<a href="adminPanel.php?action=listAllEV#allStations" class="text-reset">List charging locations</a>
					  </li>
					  <li class="list-group-item py-1">
						<a href="adminPanel.php?action=listAvailable#availableStations" class="text-reset">List available charging locations</a>
					  </li>
					  <li class="list-group-item py-1">
						<a href="adminPanel.php?action=listFull#fullStations" class="text-reset">List full charging locations</a>
					  </li>
					  <li class="list-group-item py-1">
						<a href="adminPanel.php?action=add" class="text-reset">Add charging locations</a>
					  </li>
					  <li class="list-group-item py-1">
						<a href="editEV.php" class="text-reset">Edit charging locations</a>
					  </li>
					</ul>
					<!-- Collapse 1 -->
					
					<!-- Collapse 2 -->
					<a class="list-group-item list-group-item-action py-2 ripple" role="button" data-bs-toggle="collapse" href="#collapse2" aria-current="true" aria-expanded="true" aria-controls="collapse2">
					  <span>Users</span>
					</a>
					<!-- Collapsed content 2 -->
					<ul id="collapse2" class="collapse list-group list-group-flush" style="text-decoration: none;">
					  <li class="list-group-item py-1">
						<a href="adminPanel.php?action=listAllU#allUsers" class="text-reset">List all users</a>
					  </li>
					  <li class="list-group-item py-1">
						<a href="adminPanel.php?action=listActiveU#activeUsers" class="text-reset">List active users</a>
					  </li>
					</ul>
					<!-- Collapse 2 -->
				</div>
			</div>
		</nav>	  
	<!-- Sidebar -->

</head>
<body>
	<main id="admin-main" class="container-fluid">
		<section id="form-section" class="container-fluid shadow-lg rounded" style="--bs-gutter-x: 0;">
			<div class="hero-section justify-content-between " style="background-image: linear-gradient(to bottom, rgb(14 14 14 / 81%), rgb(82 82 85 / 32%)), url(./images/admin.jpg);">
				<div class="hero-content w-100">
					<div class="hero-text" style="width: 100%; padding: 2vh 0;">
						<h2 style="color: RGB(225, 225, 225);"> <?php if (isset($_SESSION['current_user'])) {
							echo $_SESSION['current_user']['name'];} ?>.</h2>
						<p style="color: RGB(225, 225, 225);">Take control as an admin.</p>
						<p style="color: RGB(225, 225, 225);">Full robust functions for decent admin like you!</p>
						<p>Find charging stations</p>
						<form class="d-flex col" role="search" action="adminPanel.php" method="get">
							<input class="form-control me-2 row-1" name="searchFieldId" type="text" placeholder="Enter station ID" aria-label="Search"/>
							<input class="form-control me-2 row-3" name="searchFieldDes" type="text" placeholder="Enter staion description" aria-label="Search"/>
							<button class="btn btn-success" type="submit" name="search">Search</button>
						</form>
					</div>
				</div>
			<div class="add-section w-100 over-flow-auto m-3">
				<!--Search for locations-->
				<?php
					if (isset($_GET['search'])) {
						echo "<h3>Searching result below</h3>";
						$input1 = trim($_GET['searchFieldId']);
						$input1 = stripslashes($_GET['searchFieldId']);
						$input2 = trim($_GET['searchFieldDes']);
						$input2 = stripslashes($_GET['searchFieldDes']);
						
						$searchedEV = $admin->searchEV((string) $input1, (string) $input2);
						if ($searchedEV !== null) {
							EVSearch($searchedEV);
						} else {
							echo "<p class='text-danger'>Invalid details. Please try again</p>";
						}
					}
					
				?>
				<!--Check admin action-->
				<?php 

				if(isset($_GET['action']) && $_GET['action'] == 'add') {

						echo "<h4>Add newer stations here!</h4>";
						include_once("addEV.php");

				}
				 
				if(isset($_POST['submitAdd'])) {
						$fields = array("name", "address", "city", "state", "description", "cost_per_hr", "capacity", "availability");
						$data = array();
						foreach ($fields as $f) {
							$data[$f] = $_POST[$f];
						} 
						
						$check = new validationEV($_POST['submitAdd']);
						$cleanData = $check->cleanData($data);
						$errorFlag = $check->regexValidation($cleanData);
						$verify = $check->verification();
						if ($verify == true) {
							try {
								$ev = new Admin();
								$ev->setEV(
									$data["name"],
									$cleanData["address"],
									$cleanData["city"],
									$cleanData["state"],
									$cleanData["description"],
									$cleanData["cost_per_hr"],
									$cleanData["capacity"],
									$cleanData["availability"]
								);
								$check_row = $ev->checkEV($cleanData["address"],
								$cleanData["city"],
								$cleanData["state"]);
							if ($check_row == null) {
								$row = $ev->addEV();
								if ($row !== null) {
									echo "<p class='text-primary' style='font-size: 1.5rem;'>Successfully Added New Charging Location!</p>";
								} else {
									echo "<p class='text-danger'>Error occurs, please try again.</p>";
									include("addEV.php"); 
								}
								
							} else {
								echo "<p class='text-danger'>This location already existed.</p>";
								include("addEV.php"); 
							}					
							
						} catch (Exception $e) {
							echo "<p class='text-danger'>Error $e spotted!</p>";
							include("addEV.php");
						}
					} else {
						echo "<p>Error found!</p>";
						include("addEV.php");
					}
				} else if (! isset($_GET['search'])) {
					echo "<h1>What would you do today?</h1>";
				}
				?>
				</div>
			</div>
		</section>

		<section id="availableStations" class="container-fluid row d-flex flex-column p-3 over-flow-auto">
				<div class="d-flex m-3 col rounded-4 flex-column flex-nowrap overflow-x-auto shadow-lg bg-success bg-gradient text-white rounded flex-fill" style="height: min-content; width: fit-content;">
					<h3>Current available stations</h3>
				<?php 
				
				try {
					if (isset($_GET['action'])) {
						if ($_GET['action'] == "listAvailable") {
							
							
							$availableEVs = $admin->listAvailableEVs();
							EVTable($availableEVs);
						}
						
					} else {
						
						$availableEVs = $admin->listAvailableEVs();
						EVTable($availableEVs);
					}

				} catch (Exception $e) {
					echo "<p class='text-danger'>Error {$e} found! no data available to display</p>";
				}
				?>

				</div>
				<div class="d-flex m-3 col rounded-4 flex-column flex-nowrap overflow-x-auto shadow-lg bg-danger bg-gradient text-white rounded flex-fill" style="height: min-content; width: fit-content;">
					<h3>Current fully booked stations!</h3>
				<?php 
				
				try {
					if (isset($_GET['action'])) {
						if ($_GET['action'] == "listFull") {
							
							
							$fullEVs = $admin->listFullEVs();
							EVTable($fullEVs);
						}
						
					} else {
						
						$fullEVs = $admin->listFullEVs();
						EVTable($fullEVs);
					}

				} catch (Exception $e) {
					echo "<p class='text-danger'>Error {$e} found! no data available to display</p>";
				}
				?>

				</div>
		</section>

		<section id="allStations" class="container-fluid d-flex flex-column p-3 over-flow-auto">
			
			<div class="d-flex m-1 row rounded-4 flex-column flex-nowrap overflow-auto shadow-lg bg-warning bg-gradient text-white rounded">
				<h3>All stations in the system</h3>
			<?php 
			
			if (isset($_GET['action'])) {
				try {
					if ($_GET['action'] == "listAllEV") {
						
						
						$allEVs = $admin->listAllEVs();
						//Display table
						EVTable($allEVs);
					} else if($_GET['action'] == "edit") {
						
						$allEVs = $admin->listAllEVs();
						//Display table
						EVTableToEdit($allEVs);
						
				}
					
				} catch (Exception $e) {
					echo "<p class='text-danger'>Error {$e} found! no data available to display</p>";
				}

			} else {
				
				$allEVs = $admin->listAllEVs();
				EVTable($allEVs);
			}

			?>
			</div>
		</section>

		<section id="allUsers" class="container-fluid d-flex flex-column p-3 over-flow-auto">
			<div class="d-flex m-1 row rounded-4 flex-column flex-nowrap overflow-auto shadow-lg bg-info bg-gradient text-white rounded">
			<h3>All user in the system</h3>
			<?php 
			try {
				if (isset($_GET['action'])) {
					
					if ($_GET['action'] == "listAllU") {

						$allU = $admin->listAllUser();
						//Display table
						userTable($allU);
					} 
					
				} else {
					
					$allU = $admin->listAllUser();
					userTable($allU);
				}
			} catch (Exception $e) {
				echo "<p class='text-danger'>Error {$e} found! no data available to display</p>";
			}
			

			?>
			</div>
		</section>

		<section id="activeUsers" class="container-fluid flex-column p-3">
			<div class="d-flex m-1 flex-column bg-primary bg-gradient text-white rounded-4 shadow-lg">
				<h3 class="d-flex m-3">Active customers</h3>
				<div class="row m-3 flex-row rounded flex-nowrap overflow-x-auto" style="gap: 1.2rem;">
						
					<?php 
						try {
							if (isset($_GET['action'])) {
								
								
								if ($_GET['action'] == "listActiveU") {
									
									$allU = $admin->listActiveUsers();
									activeUsers($allU);
									
								}
								
								
							} else {
								$allU = $admin->listActiveUsers();
								activeUsers($allU);
							} 
							
						} catch (Exception $e) {
							echo "<p class='text-danger'>Error {$e} found! no data available to display</p>";
						}
					?>
				</div>
			</div>
				
		</section>
	</main>
</body>

</html>
