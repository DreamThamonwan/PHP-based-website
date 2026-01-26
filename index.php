<?php

session_start(); //Save details in session
require_once 'classes.php'; // loads trait & classes

if (isset($_GET['action']) && $_GET['action']==='logout') {
    session_unset();
    session_destroy();
}

require_once 'customer-functions.php';
?>
<?php

$regex = ["name"=>array(
	"regex"=>"/^.$/",
	"emptyError"=>"Please fill your name"
), 
"phone"=>array(
	"regex"=>"/^04([\d]{8})$/",
	"emptyError"=>"Please fill your phone number",
	"formatError"=>"The correct phone number format starts with 04, followed by 8 digits"
), 
"email"=>array(
	"regex"=>"/^[\w-]+(\.)*[\w-]*@[\w-]+(\.)*[\w-]*(\.[a-zA-Z]{2,})$/",
	"emptyError"=>"Please fill your email",
	"formatError"=>"Please re-enter your email with standard email format"
), 
"password"=>array(
	"regex"=>"/^(?!.*\s).{8,16}$/",
	"emptyError"=>"Please fill your password",
	"identicalError"=>"the confirmed password does not match",
	"formatError"=>"Please re-enter your password correctly. It should be at least 8 but no more than 16 characters. No white space allowed."
	)];
	
	$errorFlag = [
		"name"=>array(
			"emptyError"=>1
		), 
		"phone"=>array(
			"emptyError"=>1,
			"formatError"=>1
		), 
		"email"=>array(
			"emptyError"=>1,
			"formatError"=>1
		),  
		"password"=>array(
			"emptyError"=>1,
			"identicalError"=>1,
			"formatError"=>1
		),  
		"password2"=>array(
			"emptyError"=>1,
			"identicalError"=>1,
			"formatError"=>1
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
			<link rel="stylesheet" href="style_sheet.css">
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">  <!-- Bootstrap CSS -->
			</head>
			<body>
			<div class="container-content h-100">
			<div class="hero-section shadow-lg">
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
			<a class="nav-link" aria-current="page" href="">Home</a>
			</li>
			<li class="nav-item">
			<a class="nav-link" href='index.php?action=signin'>Sign in</a>
			</li>
			<li class="nav-item">
			<a class="nav-link" href='index.php?action=signup'>Sign up</a>
			</li>
			<li class="nav-item">
			<a class="nav-link" href='index.php?action=logout'>Log out</a>
			</li>
			</ul>
			<form class="d-flex" role="search" action="index.php" method="get">
			<input class="form-control me-2" name="searchFieldId" type="text" placeholder="Enter station ID" aria-label="Search"/>
			<input class="form-control me-2" name="searchFieldDes" type="text" placeholder="Enter staion description" aria-label="Search"/>
			<button class="btn btn-success" type="submit" name="search">Search</button>
			</form>
			</div>
			</div>
			</nav>
			<div class="hero-content mt-5">
			<div class="hero-text">
			<h2 class="text-warning">Plug In. Power Up. Get Going.</h2>
			<p>Reserve your spot, set your rate, and be on your way—happy fully charged.</p>
			<a class="btn btn-primary col-sm-6 col-md-6 col-lg-3" 
			href="<?php if (!isset($_SESSION['current_user']['name'])) {echo 'index.php?action=signup';} 
			else {
				if($_SESSION['current_user']['role'] == "Administrator") {
					echo 'adminPanel.php';
				} else {
					echo 'customerPanel.php';
				}
			} ?>" 
			role="button">
			<?php if (!isset($_SESSION['current_user']['name'])) {echo 'Register today';} else {echo 'Visit your dashboard';} ?>
			</a>
			</div>
			<div id="registration" class="m-3">
			<?php
			
			class validation {
				private $submit;
				private array $regex = ["name"=>array(
					"regex"=>"/^.$/",
					"emptyError"=>"Please fill your name"
				), 
				"phone"=>array(
					"regex"=>"/^04([\d]{8})$/",
					"emptyError"=>"Please fill your phone number",
					"formatError"=>"The correct phone number format starts with 04, followed by 8 digits"
				), 
				"email"=>array(
					"regex"=>"/^[\w-]+(\.)*[\w-]*@[\w-]+(\.)*[\w-]*(\.[a-zA-Z]{2,})$/",
					"emptyError"=>"Please fill your email",
					"formatError"=>"Please re-enter your email with standard email format"
				), 
				"password"=>array(
					"regex"=>"/^(?!.*\s).{8,16}$/",
					"emptyError"=>"Please fill your password",
					"identicalError"=>"the confirmed password does not match",
					"formatError"=>"Please re-enter your password correctly. It should be at least 8 but no more than 16 characters. No white space allowed."
					)];
					
					private array $errorFlag = [
						"name"=>array(
							"emptyError"=>1
						), 
						"phone"=>array(
							"emptyError"=>1,
							"formatError"=>1
						), 
						"email"=>array(
							"emptyError"=>1,
							"formatError"=>1
						),  
						"password"=>array(
							"emptyError"=>1,
							"formatError"=>1
						),  
						"password2"=>array(
							"emptyError"=>1,
							"identicalError"=>1,
							"formatError"=>1
							)]; 
							
							public function __construct($submit) {
								$this->submit = $submit;
							}
							
							public function cleanData(array $data) {
								foreach($data as $dataField => $dataValue) {
									$dataValue = trim($dataValue);
									$dataValue = stripslashes($dataValue);
								}
								return $data;
								
							}
							
							public function regexValidation(array $data) {
								if(isset($_POST['submitIn'])) {
									$this->submit = $_POST['submitIn'];
									$this->errorFlag["email"]["emptyError"] = empty($data["email"]) ? 0 : 1;
									$this->errorFlag["password"]["emptyError"] = empty($data["password"]) ? 0 : 1;
									$this->errorFlag["email"]["formatError"] = preg_match($this->regex["email"]["regex"], $data["email"]);
									$this->errorFlag["password"]["formatError"] = preg_match($this->regex["password"]["regex"], $data["password"]); 
								} else if (isset($_POST['submitUp'])) {
									
									$this->errorFlag["email"]["emptyError"] = empty($data["email"]) ? 0 : 1;
									$this->errorFlag["phone"]["emptyError"] = empty($data["phone"]) ? 0 : 1;
									$this->errorFlag["password"]["emptyError"] = empty($data["password"]) ? 0 : 1;
									$this->errorFlag["password2"]["emptyError"] = empty($data["password2"]) ? 0 : 1;
									$this->errorFlag["email"]["formatError"] = preg_match($this->regex["email"]["regex"], $data["email"]);
									$this->errorFlag["name"]["emptyError"] = empty($data["name"]) ? 0 : 1;
									$this->errorFlag["phone"]["emptyError"] = preg_match($this->regex["phone"]["regex"], $data["phone"]);
									$this->errorFlag["password"]["formatError"] = preg_match($this->regex["password"]["regex"], $data["password"]);
									$this->errorFlag["password2"]["formatError"] = preg_match($this->regex["password"]["regex"], $data["password2"]);
									$this->errorFlag["password2"]["identicalError"] = (int) ($data['password'] === $data['password2']);
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

						$table = new User();
						$table->tableCreation();

						if (isset($_SESSION['current_user']['name'])) {

							echo "<h3 class='text-light'>Welcome back! " .$_SESSION['current_user']['name'] ."</h3>";

							} else if(isset($_POST['submitIn'])) {
								
								$data = array("email"=>trim($_POST['email']), "password"=>trim($_POST['password']));
								$check = new validation($_POST['submitIn']);
								$cleanData = $check->cleanData($data);
								$errorFlag = $check->regexValidation($cleanData);
								$verify = $check->verification();
								if ($verify == true) {
									try {
										$user = new User();
										$user->setUser($cleanData["email"], md5($cleanData["password"]));
										$row = $user->getUser($cleanData["email"], md5($cleanData["password"]));
										if ($row !== null) {
											echo "<h3>Successfully signed in!</h3>";
											//Add user in seesion storage for later use
											$_SESSION["current_user"] = array("userId"=>$row["userId"], "name"=>$row["userName"], "email"=>$row["userEmail"], "role"=>$row["userRole"]);
											echo "<p>Hi! " .$_SESSION["current_user"]['name'] ."</p>";
											if ($_SESSION['current_user']['role']==='Administrator') {
												header('Location: adminPanel.php');
												exit;
											} else if ($_SESSION['current_user']['role']==='Customer') {
												header('Location: customerPanel.php');
												exit;
											}
										} else {
											echo "<h4 class='text-danger'>Invalid email or password, please try again.</h4>";
											echo file_get_contents("signInForm.php"); 
										}
										
									} catch (Exception $e) {
										echo "<h4 class='text-danger'>No information, please try again.</h4>";
										echo file_get_contents("signInForm.php"); 
									}
								} else {
									echo "<h4 class='text-danger'>Invalid email or password, please try again.</h4>";
									echo include("signInForm.php");
								}
								
							} else if (isset($_POST['submitUp'])) {
								$data = array("email"=>$_POST['email'],
								"password"=>$_POST['password'], 
								"password2"=>$_POST['password2'],
								"name"=>$_POST['name'], 
								"phone"=>$_POST['phone'], 
								"role"=>$_POST['role']);
								$check = new validation($_POST['submitUp']);
								$cleanData = $check->cleanData($data);
								$errorFlag = $check->regexValidation($cleanData);
								$verify = $check->verification();
								if ($verify == true) {
									try {
										//Check if the same user exist
										$user = new User();
										$user->setUser($cleanData["email"], md5($cleanData["password"]));
										$row = $user->getUser($cleanData["email"], md5($cleanData["password"]));
										$checkEmail = $user->checkUser($cleanData["email"]);
										if ($row == null && $checkEmail == true) {

											$user->setInfo($cleanData["name"], $cleanData["phone"] ,$cleanData["role"]);
											$new_user = $user->addUser($cleanData["email"], md5($cleanData["password"]), $cleanData["name"], $cleanData["phone"], $cleanData["role"]);
											if ($new_user !== null) {
												echo "<h3>Successfully signed up!</h3>";
												echo "<p>Please click" ."<a class='nav-link' href='index.php?action=signin'>Sign in</a>" ."to verify that this is you!</p>";
											} else {
												echo "<h4 class='text-danger'>Invalid credentials, please try again.</h4>";
												include("signUpForm.php"); 
											}
										} else {
											echo "<h4 class='text-danger'>This email already registered.</h4>";
										}							
										
									} catch (Exception $e) {
										echo "<h4 class='text-danger'>No information, please try again.</h4>";
										include("signInForm.php"); 
									}
								} else {
									echo "<h4 class='text-danger'>Error found!</h4>";
									include("signUpForm.php");
								}
							}  else {
								if (isset($_GET['action'])) {
									switch ($_GET['action']) {
										case "signin":
											echo "<h2>For registered user</h2>";
											echo file_get_contents("signInForm.php");
											break;
										case "signup":
											echo "<h2>For new user</h2>";
											echo file_get_contents("signUpForm.php");
											break;
										case "logout":
											echo file_get_contents("signInForm.php");
											break;

										}
									} else {
										echo "<h2>Join us today!</h2>";
										echo file_get_contents("signUpForm.php"); 
									}
							}
						?>
						</div>
						</div>


					</div>

					<section class="container-fluid flex-column p-5" style="background-color: rgb(2 12 41 / 97%);">
					<h3 class="text-light">Search your favourite locations!</h3>
					<form class="d-flex col-lg-6 mb-3" role="search" action="index.php" method="get">
					<input class="form-control me-2 row-1" name="searchFieldId" type="text" placeholder="Enter station ID" aria-label="Search"/>
					<input class="form-control me-2 row-3" name="searchFieldDes" type="text" placeholder="Enter staion description" aria-label="Search"/>
					<button class="btn btn-success" type="submit" name="search">Search</button>
					</form>
					<?php if (isset($_GET['search'])) {
						echo "<h4 class='text-light'>Searching result below</h4>";
						?>
						<div class="d-flex row flex-row flex-nowrap overflow-x-auto" style="gap: 1.2rem;">
						<!--Search for locations-->
						<?php
						
						$input1 = trim($_GET['searchFieldId']);
						$input1 = stripslashes($_GET['searchFieldId']);
						$input2 = trim($_GET['searchFieldDes']);
						$input2 = stripslashes($_GET['searchFieldDes']);
						$user = new User();
						$searchedEV = $user->searchEV((string) $input1, (string) $input2);
						if ($searchedEV !== null) {
							EVSearchCus($searchedEV);
						} else {
							echo "<p class='text-warning'>Invalid details. Please try again</p>";
						}
					}
					?>
					</div>
					</section>

					<section class="container-fluid flex-column p-5">

					<h3>Find available stations for you now!</h3>
					<div class="d-flex row flex-row flex-nowrap overflow-x-auto" style="gap: 1.2rem;">
					<?php 
					try {
						$avail = new User();
						$availEV = $avail->listAvailableEVs();
						availableEV($availEV);
					} catch (Exception $e) {
						echo "<p class='text-danger'>no data available</p>";
					}

					?>

					</div>
					</section>

					<section class="info">
					<div class="value">
					<img src="./images/time-management.png" />
					<p class="text-warning">Real-Time Availability</p>
					</div>
					<div class="value">
					<img src="./images/high-cost.png" />
					<p class="text-warning">Transparent Pricing</p>
					</div>
					<div class="value">
					<img src="./images/booking.png" />
					<p class="text-warning">One-Tap Booking</p>
					</div>
					</section>
					</div>

					<footer style="font-size: 0.5rem;" style="position: absolute; bottom: 0;">
					<ul  class="list-unstyled list-inline">
					<li><a href="https://www.flaticon.com/free-icons/productivity" title="productivity icons">Productivity icons created by Freepik - Flaticon</a></li>
					<li><a href="https://www.flaticon.com/free-icons/high-price" title="high-price icons">High-price icons created by Vectorslab - Flaticon</a></li>
					<li><a href="https://www.flaticon.com/free-icons/booking" title="Booking icons">Booking icons created by Peerapak Takpho - Flaticon</a></li>
					<li>
					Photo by <a href="https://unsplash.com/@nathanareboucas?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">Nathana Rebouças</a> on <a href="https://unsplash.com/photos/woman-in-white-grey-and-black-plaid-shirt-enaNfAjiDGg?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">Unsplash</a>
					</li>
					<li>
					Photo by <a href="https://unsplash.com/@oksdesign?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">Oxana Melis</a> on <a href="https://unsplash.com/photos/an-electric-car-plugged-into-a-charging-station-zjqe_46ga4k?utm_content=creditCopyText&utm_medium=referral&utm_source=unsplash">Unsplash</a>
					</li>
					</ul>
					</footer>

					</body>
					</html>
