<form class="row g-3" action='index.php?action=signin' method='post'>
  <div class="col-md-4">
    <label for="validationServerName" class="form-label">Name</label>
    <input type="text" name='name' class="form-control <?php if ($errorFlag['name']['emptyError'] !== 1) {echo 'is-invalid';}?>" >
	<div id="validationServerName" class="invalid-feedback">
        <?php if ($errorFlag['name']['emptyError'] !== 1) {echo "".$regex['name']['emptyError']."";} ?>
     </div>
  </div>
  <div class="col-md-4">
    <label for="validationServerPhone" class="form-label">Phone</label>
    <input type="text" name='phone' class="form-control <?php if ($errorFlag['phone']['emptyError'] !== 1 or $errorFlag['phone']['formatError'] !== 1) {echo 'is-invalid';}?>" >
	<div id="validationServerPhone" class="invalid-feedback">
        <?php if ($errorFlag['phone']['emptyError'] !== 1) {echo "".$regex['phone']['emptyError']."";}
			  else if ($errorFlag['phone']['formatError'] !== 1) {echo "".$regex['phone']['formatError']."";} ?>
     </div>
  </div>
  <div class="col-md-6">
    <label for="validationDefault03" class="form-label">Email</label>
    <input type="text" name='email' class="form-control <?php if ($errorFlag['email']['emptyError'] !== 1 or $errorFlag['email']['formatError'] !== 1) {echo 'is-invalid';}?>" >
	<div id="validationServerPassword" class="invalid-feedback">
        <?php if ($errorFlag['email']['emptyError'] !== 1) {echo "".$regex['email']['emptyError']."";}
			  else if ($errorFlag['email']['formatError'] !== 1) {echo "".$regex['email']['formatError']."";} ?>
     </div>
  </div>
  <div class="col-md-3">
    <label for="validationType" class="form-label">User's type:</label>
    <select id="validationType" name='role' class="form-select" >
      <option selected value='Customer'>Customer</option>
      <option selected value='Administrator'>Administrator</option>
    </select>
  </div>
  <div class="col-md-4">
    <label for="validationServerPassword" class="form-label">Password</label>
    <input type="password" name='password' class="form-control <?php if ($errorFlag['password']['emptyError'] !== 1 or $errorFlag['password']['formatError'] !== 1) {echo 'is-invalid';}?>" >
    <p class="text-warning">The password should be at least 8 but no more than 16 characters</p>
	<div id="validationServerPassword" class="invalid-feedback">
        <?php if ($errorFlag['password']['emptyError'] !== 1) {echo "".$regex['password']['emptyError']."";}
			  else if ($errorFlag['password']['formatError'] !== 1) {echo "".$regex['password']['formatError']."";} ?>
     </div>
  </div>
  <div class="col-md-4">
    <label for="validationServerPassword2" class="form-label">Confirm your Password</label>
    <input type="password" name='password2' class="form-control <?php if ($errorFlag['password2']['emptyError'] !== 1 or $errorFlag['password2']['formatError'] !== 1 or $errorFlag['password2']['identicalError'] !== 1) {echo 'is-invalid';}?>" >
	<div id="validationServerPassword2" class="invalid-feedback">
        <?php if ($errorFlag['password2']['emptyError'] !== 1) {echo "".$regex['password']['emptyError']."";}
			  else if ($errorFlag['password2']['formatError'] !== 1) {echo "".$regex['password']['formatError']."";}
			  else if ($errorFlag['password2']['identicalError'] !== 1) {echo "".$regex['password']['identicalError']."";}
		?>
     </div>
  </div>
  <div class="col-12">
    <button class="btn btn-primary" type="submit" name='submitUp' value='submitUp'>Submit</button>
	<button class="btn btn-info" type="reset">Reset</button>
  </div>
</form>
<p>Already have an account?</p>
<a class= "link-light" href="index.php?action=signin">Sign in instead</a>
