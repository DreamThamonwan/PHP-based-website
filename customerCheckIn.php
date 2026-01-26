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
if(isset($_GET['selectEV'])) {
	$_SESSION['selectEV'] = $_GET['selectEV'] ?: "";
}
?>

<?php

    $regex = [
        "date"=>array(
            "regex"=>"/^.+$/",
            "emptyError"=>"Please fill in the station's address",
            "formatError"=>"Invalid date"
        ), 
        "time"=>array(
            "regex"=>"/^.+$/",
            "emptyError"=>"Please fill in the station's city",
            "formatError"=>"Invalid time"
        )];
            
        $errorFlag = [
            "date"=>array(
                "emptyError"=>1,
                "formatError"=>1
            ), 
            "time"=>array(
                "emptyError"=>1,
                "formatError"=>1
            )];

    class validationCheckIn {
		private $submit;
		private array $regex = [
        "date"=>array(
            "emptyError"=>"Please fill in the date",
            "formatError"=>"Invalid date"
        ), 
        "time"=>array(
            "emptyError"=>"Please fill in the time",
            "formatError"=>"Invalid time"
        )];
            
        private array $errorFlag = [
            "date"=>array(
                "emptyError"=>1,
                "formatError"=>1
            ), 
            "time"=>array(
                "emptyError"=>1,
                "formatError"=>1
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

            public function validatedate($date, $format = 'Y-m-d')
            {
                $d = DateTime::createFromFormat($format, $date);
                return $d && $d->format($format) == $date;
            }

            public function validateTime($time, $format = 'H:i:s')
            {
                $d = DateTime::createFromFormat($format, $time);
                return $d && $d->format($format) == $time;
            }

			public function validation(array $data) {
				if(isset($_POST['submitCheckIn'])) {
					$this->submit = $_POST['submitCheckIn'];
					//Check empty
					$this->errorFlag["date"]["emptyError"] = empty($data["date"]) ? 0 : 1;
					$this->errorFlag["time"]["emptyError"] = empty($data["time"]) ? 0 : 1;
					//Check format
					$this->errorFlag["date"]["formatError"] = $this->validatedate($data["date"], $format = 'Y-m-d') ? 1 : 0;
					$this->errorFlag["time"]["formatError"] = $this->validateTime($data["time"], $format = 'H:i:s') ? 1 : 0;
					
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
</head>
<body>
	<!-- Main content display here -->
	<main id="customer-main">
		<div class="hero-section container-fluid"  style="background-image:linear-gradient(to bottom, rgb(2 7 35 / 86%), rgb(245 191 143 / 36%)), 
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
					  <a class="nav-link" href="customerPanel.php">Customer Dashboard</a>
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
                  <form class="d-flex col-lg-6" role="search" action="customerCheckIn.php" method="get">
                    <input class="form-control me-2 row-1" name="searchFieldId" type="text" placeholder="Enter station ID" aria-label="Search"/>
                    <input class="form-control me-2 row-3" name="searchFieldDes" type="text" placeholder="Enter staion description" aria-label="Search"/>
                    <button class="btn btn-success" type="submit" name="search">Search</button>
                  </form>
				</div>
			  </div>
			</nav>
			<div class="hero-content w-100" style="color: white;">
                <form class="row g-3 d-flex align-items-baseline" action="customerCheckIn.php" method="post">
					<h4>Please fill in the form to check in at EV station <?php echo $_SESSION['selectEV']?></h4>
                <input type="hidden" name="station_id" value="<?php echo $_SESSION['selectEV']?>" />
                <input type="hidden" name="customer_name"  value="<?php if (isset($_SESSION['current_user'])) {echo $_SESSION['current_user']['name'];} ?>" />
                <div class="col-sm-3 col-md-6">
                    <label for="InputDate" class="form-label">Charging up date</label>
                    <input type="date" id="InputDate" name="date" class="form-control <?php if ($errorFlag["date"]["emptyError"] !== 1) {echo "is-invalid";}?>" value="<?php if (isset($selectEV)) {echo $selectEV["date"];} ?>">
                    <div class="invalid-feedback">
                        <?php if ($errorFlag["date"]["emptyError"] !== 1) {echo "".$regex["date"]["emptyError"]."";} ?>
                    </div>
                </div>
                <div class="col-sm-3 col-md-6">
                    <label for="InputTime" class="form-label">Time</label>
                    <input  type="time" id="InputTime" name="time" class="form-control <?php if ($errorFlag["time"]["emptyError"] !== 1 or $errorFlag["time"]["formatError"] !== 1) {echo "is-invalid";}?>" value="<?php if (isset($selectEV)) {echo $selectEV["time"];} ?>">
                    <div class="invalid-feedback">
                        <?php if ($errorFlag["time"]["emptyError"] !== 1) {echo "".$regex["time"]["emptyError"]."";}
                            else if ($errorFlag["time"]["formatError"] !== 1) {echo "".$regex["time"]["formatError"]."";} ?>
                    </div>
                </div>
                <input type="hidden" name="second"  value="00" />
                <button type="submit" name="submitCheckIn" class="btn btn-primary" style="width: 150px; margin: 20px auto;">Submit request</button>
                </form>
				<div class="d-flex flex-column w-100 align-items-center">
					<h4><?php if (isset($_GET['search'])) {echo "Searching result below";} ?></h4>
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
									$html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
									<p>Invalid details. Please try again</p>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>';
									echo $html;
								}
							}
						?>
						
					</div>
				<div>
					<!--Validation check-in-->
                    <?php
                        if(isset($_POST['submitCheckIn'])) {
                            try {
								$time =  $_POST["time"] . ":" .$_POST["second"];
                                $data = array("date"=>$_POST["date"], "time"=>$time);
                                $check = new validationCheckIn($_POST['submitCheckIn']);
                                $clean_data = $check->cleanData($data);
                                $errorFlag = $check->validation($clean_data);
                                $verify = $check->verification();
                                if ($verify == true) {
									if($_SESSION['selectEV'] !== "") {
										 $issue_time = $clean_data['date'] . " " .$clean_data['time'];
										$session = new User();
										$session->setSession($_SESSION['current_user']['userId'],  $issue_time);
										$verified_session = $session->checkIn($_POST['station_id']);
									} else {
										 $html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
										<p>Please select EV location!</p>
										<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
										</div>';
										echo $html;
									}
									$_SESSION['selectEV'] = "";
									
                                } else {
                                    $html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
									<p>Invalid input. Please try again</p>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>';
									echo $html;
                                }
                                
                            } catch (Exception $e) {
								$html = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
									<p>Error: ' . $e . 'spotted!</p>
									<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>';
								echo $html;
                            }
                            
                        }
                    ?>
				</div>
				</div>
				
			</div>
		</div>

		<section id="checkedInStations" class="container-fluid flex-column p-5 .bg-success">
			<h3>All stations you currently checked in</h3>
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

		<section id="activeStations" class="container-fluid flex-column p-5">
			<h3>Current active EV Stations  
				<a class="btn btn-info" role="button" href="customerCheckIn.php?action=view#activeStations">View as table</a>
				<a class="btn btn-info" role="button" href="customerCheckIn.php?#activeStations">View as cards</a>
			</h3>
			<div class="d-flex row flex-row flex-nowrap overflow-x-auto"  style="gap: 1.2rem;">
			<?php
			try {
				$avail = new User();
				$availEV = $avail->listAvailableEVs();
				if (isset($_GET['action']) && $_GET['action'] =="view") {
					checkinTable($availEV);
				} else {
					availableEV($availEV);
				}
			} catch (Exception $e) {
				echo "<p class='text-danger' style='font-weight: 600;'>No current available stations!</p>";
			}
			?>
			</div>
		</section>
	</main>
</body>

</html>
