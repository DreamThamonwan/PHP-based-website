<?php
session_start();
//Cache the current user
if (isset($_SESSION['current_user']['name']) and isset($_SESSION['current_user']['role'])) {
	if ($_SESSION['current_user']['role'] === "Administrator") {
		header('Location: adminPanel.php');
		exit;
	}
} else {
	//Directing to home page if no information found 
	header('Location: index.php');
	exit;
}
//continue staying here
require_once "customer-functions.php"; // loads customer functions
require_once 'classes.php';// loads trait & classes
$user = new User(); //Create user object once to reuse in the entire page
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
</head>
<body>
	<!-- Main content display here -->
	<main id="customer-main">
		<div class="hero-section container-fluid shadow-lg"  
			style="background-image:linear-gradient(to bottom right, rgb(4 16 89 / 65%), rgb(245 191 143 / 36%)), 
			url(./images/customer.jpg);">
			<nav id = "Nav" class="navbar fixed-top navbar-expand-lg" data-bs-theme="dark">
			  <!-- Navbar content -->
				<div class="container-fluid">
				<a class="navbar-brand text-warning"><b>EasyEV-Charging</b></a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				  <span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNav">
				  <ul class="nav nav-underline me-auto mb-2 mb-lg-0">
					<li class="nav-item">
					  <a class="nav-link" href="index.php">Home</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="customerPanel.php">Your Dashboard</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="customerPanel.php?action=listIn#checkedInStations">Checked-in Stations</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="customerPanel.php?action=listOut#checkedOutStations">Checked-out Stations</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href="customerPanel.php?action=listAll#activeStations">Available Stations</a>
					</li>
					<li class="nav-item">
					  <a class="nav-link" href='index.php?action=logout'>Log out</a>
					</li>
				  </ul>
				</div>
			  </div>
			</nav>
			<div class="hero-content w-100">
				<div class="hero-text flex-fill">
					<h2 class='text-white'>Start seamless service today.</h2>
					<p class='text-white'>Reserve your spot, arrange it, or Check your history.</p>
					<p class='text-white'>Find charging stations. Enter station details below if known.</p>
						<form class="d-flex col-lg-6" role="search" action="customerPanel.php" method="get">
							<input class="form-control me-2 row-1" name="searchFieldId" type="text" placeholder="Enter station ID" aria-label="Search"/>
							<input class="form-control me-2 row-3" name="searchFieldDes" type="text" placeholder="Enter staion description" aria-label="Search"/>
							<button class="btn btn-success" type="submit" name="search">Search</button>
						</form>
				</div>
				<div class="d-flex flex-column flex-fill rounded-4 align-items-center" style="background-color: rgb(255, 255, 255, 0.2); backdrop-filter: blur(8px);">
					<!--Search for locations-->
					<?php
						if (isset($_GET['search'])) {
							echo "<h3 class='text-white'>Searching result below</h3>";
							$input1 = trim($_GET['searchFieldId']);
							$input1 = stripslashes($_GET['searchFieldId']);
							$input2 = trim($_GET['searchFieldDes']);
							$input2 = stripslashes($_GET['searchFieldDes']);
							
							$searchedEV = $user->searchEV((string) $input1, (string) $input2);
							if ($searchedEV !== null) {
							?>
							<div class="d-flex flex-row over-flow-auto p-3" style="gap: 2rem;">
							<?php
								EVSearchCus($searchedEV);
							?>
							</div>
							<?php
							} else {
								echo "<p class='text-danger' style='font-weight: 600;'>Invalid details. Please try again</p>";
							}
						}
					?>
				</div>
			</div>
		</div>
	
		<section id="checkedInStations" class="container-fluid flex-column p-5 bg-success bg-gradient">
			<h3 class="text-light">All locations you currently checked in</h3>
			<div class="d-flex row flex-row flex-nowrap overflow-x-auto">
			<?php 
				if(isset($_SESSION['current_user'])) {
					try {
						$checkedIn = $user;
						$checkedInEV = $checkedIn->listCheckIn($_SESSION['current_user']['userId']);
						if ($checkedInEV !== null) {
							EVTable($checkedInEV);
						} else {
							echo "<p class='text-danger' style='font-weight: 600;'>No current checked in session!</p>";
						}
					} catch (Exception $e) {
						echo "<p class='text-danger' style='font-weight: 600;'>No current checked in session!</p>";
					}
					
				} 

			?>
			</div>
		</section>

		
		<section id="activeStations" class="container-fluid flex-column p-5 shadow-lg">
			<h3>Current active EV Stations 
				<a class="btn btn-info" role="button" href="customerPanel.php?action=view#activeStations">View as table</a>
				<a class="btn btn-info" role="button" href="customerPanel.php?#activeStations">View as cards</a>
			</h3>
			<div class="d-flex row flex-row flex-nowrap overflow-x-auto" style="gap: 1.2rem;">
			<?php
				try {
					$avail = $user;
					$availEV = $avail->listAvailableEVs();
					if (isset($_GET['action']) && $_GET['action'] =="view") {
						checkinTable($availEV);
					} else {
						availableEV($availEV);
					}
				} catch (Exception $e) {
					echo "<p class='text-danger' style='font-weight: 600;'>No current checked in session!</p>";
				}
				
			?>

			</div>
		</section>

		<section id="checkedOutStations" class="container-fluid flex-column bg-secondary bg-gradient p-5">
			
			<div class="d-flex row flex-column flex-nowrap overflow-auto">
				<h3 class="text-light">All past sessions you checked out</h3>
			<?php 
				if(isset($_SESSION['current_user'])) {
					try {
						$checkedOut = $user;
						$checkedOutEV = $checkedIn->listCheckOut($_SESSION['current_user']['userId']);
						if ($checkedOutEV !== null) {
							checkoutTable($checkedOutEV);
						} else {
							echo "<p class='text-danger'>No current checked out session!</p>";
						}
					} catch (Exception $e) {
						echo "<p class='text-danger' style='font-weight: 600;'>No current checked out session!</p>";
					}
					
				} 

			?>
			</div>
		</section>
	</main>
	
</body>

</html>
