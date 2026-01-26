<form class="row g-3" action='index.php?action=signin' method='post'>
  <div class="col-md-6">
    <label for="validationServerName" class="form-label">Email</label>
    <input id="validationServerName" type="text" style="width: 200px;" name="email" class="form-control <?php if ($errorFlag['email']['emptyError'] !== 1 or $errorFlag['email']['formatError'] !== 1) {echo 'is-invalid';}?>" />
	<div class="invalid-feedback">
        <?php if ($errorFlag['email']['emptyError'] !== 1) {echo $regex['email']['emptyError'];}
			  else if ($errorFlag['email']['formatError'] !== 1) {echo $regex['email']['formatError'];} ?>
     </div>
  </div>
  <div class="col-md-6">
    <label for="validationServerPassword" class="form-label">Password</label>
    <input id="validationServerPassword" type="password" style="width: 200px;" name="password" class="form-control <?php  if ($errorFlag['password']['emptyError'] !== 1 or $errorFlag['password']['formatError'] !== 1) {echo 'is-invalid';}?>" />
	<div class="invalid-feedback">
        <?php if ($errorFlag['password']['emptyError'] !== 1) {echo $regex['password']['emptyError'];}
			  else if ($errorFlag['password']['formatError'] !== 1) {echo $regex['password']['formatError'];} ?>
     </div>
  </div>
  <div class="col-12">
    <button class="btn btn-primary" type="submit" name="submitIn" value='submitIn'>Submit</button>
	<button class="btn btn-info" type="reset">Reset</button>
  </div>
</form>
<p>Don't have an account?</p>
<a class= "link-light" href="index.php?action=signup">Register with us</a>