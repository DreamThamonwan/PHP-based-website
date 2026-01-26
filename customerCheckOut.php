<?php
session_start();
//Cache the current user
if (isset($_SESSION['current_user']['name']) and isset($_SESSION['current_user']['role'])) {
	if ($_SESSION['current_user']['role'] === "Administrator") {
		header('Location: adminPanel.php');
		exit;
	} else if($_SESSION['current_user']['role'] === "Customer") {
		//continue staying here
		require "customer-functions.php";
	}
}

require_once 'classes.php'; // loads trait & classes
?>

<?php 
    //Sign out 
	if(isset($_GET['action'])) {
		switch ($_GET['action']) {
				case "logout":
					session_unset(); 
					session_destroy();
					break;
				}
		}
?>

<?php
    if(isset($_GET['selectEV']) && isset($_GET['selectSession'])) {
        $_SESSION['selectEV'] = $_GET['selectEV'];
        $_SESSION['selectSession'] = $_GET['selectSession'];
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
	<a class="nav-link" href="customerPanel.php?action=listIn">Customer Dashboard</a>
	</li>
	<li class="nav-item">
	<a class="nav-link" href="customerPanel.php?action=listIn">Checked-in Stations</a>
	</li>
	<li class="nav-item">
	<a class="nav-link" href="customerPanel.php?action=listOut#checkedOutStations">Checked-out Stations</a>
	</li>
	<li class="nav-item">
	<a class="nav-link" href="customerPanel.php?action=listAll">Available Stations</a>
	</li>
	<li class="nav-item">
	<a class="nav-link" href='index.php?action=logout'>Log out</a>
	</li>
	</ul>
	<form class="d-flex col-lg-6" role="search" action="customerCheckIn.php" method="get">
	<input class="form-control me-2 row-1" name="searchFieldId" type="text" placeholder="Enter station ID" aria-label="Search"/>
	<input class="form-control me-2 row-3" name="searchFieldDes" type="text" placeholder="Enter staion description" aria-label="Search"/>
	<button class="btn btn-success" type="submit" name="search">Search</button>
	</form>
	</div>
	</div>
	</nav>
</head>
<body>
	<!-- Main content display here -->
	<main id="customer-main">
		<div class="hero-section container-fluid"  style="background-image: linear-gradient(to bottom right, rgb(4 16 89 / 65%), rgb(245 191 143 / 36%)), url(./images/customer.jpg); filter: blur(3px); z-index: 1;">
			
		</div>

		<div class="hero-content container-fluid justify-content-center" style="position: absolute; z-index: 3; top: 5vmax;">
			<?php
				if(isset($_GET['selectSession'])) {
				?>
				<div class="d-flex flex-column rounded-4 justify-content-between w-30 shadow-lg bg-body-tertiary rounded">
					<div class="p-3 bg-info rounded-4">
						<p class="text-light" style="text-align: center;">Check-out Confirmation</p>
					</div>
					<form class="row g-3 p-3 d-flex align-items-center" action="customerCheckOut.php" method="post">
						<p class="text-dark">Confirm the checkout at location <?php echo $_SESSION['selectEV']; ?>?</p>
						<input type="hidden" name="session_id" value="<?php echo $_SESSION['selectSession']; ?>" />
						<input type="hidden" name="station_id" value="<?php echo $_SESSION['selectEV']; ?>" />
						<input type="hidden" name="customer_id"  value="<?php if (isset($_SESSION['current_user'])) {echo $_SESSION['current_user']['userId'];} ?>" />
						<button type="submit" name="submitCheckOut" class="btn btn-primary" style="width: 150px; margin: 20px auto;">confirm</button>
                	</form>
				</div>
				<?php
				}
					//Validation check-in
                        if(isset($_POST['submitCheckOut'])) {
                            try {
                                $dt = new DateTime('now', new DateTimeZone('Australia/Sydney'));
                                $end_time = $dt->format('Y-m-d H:i:s');
                                $check_out = new User();
								$payment = $check_out->checkOut((int) $_POST['session_id'], $end_time, $_POST['station_id']);
								if ($payment !== null) {
									$html = '<div class="alert alert-success alert-dismissible fade show" role="alert">
									<strong>Successfully checked out!</strong> here is the total cost you need to pay: $';
									$html .= $payment;
									$html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
									$html .= '</div>';
									echo $html;
								}
                                                               
                            } catch (Exception $e) {
								$html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
									<strong>Successfully checked out!</strong> Error'.$e .'spotted!';
									$html .= '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
									$html .= '</div>';
									echo $html;
                            }
                        }
                            
                    ?>
					
				</div>
				</div>
				
			</div>

		<section>
			<div class="d-flex flex-column w-100">
					<h3><?php if (isset($_GET['search'])) {echo "Searching result below";} ?></h3>
					<div class="d-flex row flex-row flex-nowrap overflow-x-auto" style="gap: 1.2rem;">
						<!--Search for locations-->
						<?php
							if (isset($_GET['search'])) {
								
								$input1 = trim($_GET['searchFieldId']);
								$input1 = stripslashes($_GET['searchFieldId']);
								$input2 = trim($_GET['searchFieldDes']);
								$input2 = stripslashes($_GET['searchFieldDes']);
								$user = new User();
								$searchedEV = $user->searchEV((int) $input1, (string) $input2);
								if ($searchedEV !== null) {
									//var_dump($searchedEV);
									EVSearchCus($searchedEV);
								} else {
									echo "<p class='text-danger'>Invalid details. Please try again</p>";
								}
							}
						?>
						
					</div>
				<div>
		</section>

		<section id="pastStations" class="container-fluid flex-column p-5 .bg-success">
			<h3>All stations you currently checked in!</h3>
			<div class="d-flex row flex-row flex-nowrap overflow-x-auto">
			<?php 
				if(isset($_SESSION['current_user'])) {
					$checkedIn = new User();
					$checkedInEV = $checkedIn->listCheckIn($_SESSION['current_user']['userId']);
					if ($checkedInEV !== null) {
						EVTable($checkedInEV);
					} else {
						echo "<p class='text-danger'>No current checked in session!</p>";
					}
					
				} 	

			?>
			</div>
		</section>
	</main>
	
</body>
</html>