<form class="row g-3 p-2" action="adminPanel.php" method="post">
<div class="col-md-5 col-sm-2">
<label for="InputEV" class="form-label">name</label>
<input type="text" name="name" class="form-control" id="InputEV">
</div>
<div class="col-md-5 col-sm-2">
<label for="InputAddress" class="form-label">Address</label>
<input type="text" name="address" class="form-control <?php if ($errorFlag["address"]["emptyError"] !== 1) {echo "is-invalid";}?>" id="InputAddress">
<div class="invalid-feedback">
<?php if ($errorFlag["address"]["emptyError"] !== 1) {echo "".$regex["address"]["emptyError"]."";} ?>
</div>
</div>
<div class="col-md-5 col-sm-2">
<label for="InputCity" class="form-label">City</label>
<input type="text" name="city" class="form-control <?php if ($errorFlag["city"]["emptyError"] !== 1) {echo "is-invalid";}?>" id="InputCity">
<div class="invalid-feedback">
<?php if ($errorFlag["city"]["emptyError"] !== 1) {echo "".$regex["city"]["emptyError"]."";} ?>
</div>
</div>
<div class="col-md-5 col-sm-2">
<label for="InputState" class="form-label">State</label>
<input type="text" name="state" class="form-control <?php if ($errorFlag["state"]["emptyError"] !== 1) {echo "is-invalid";}?>" id="InputState">
<div class="invalid-feedback">
<?php if ($errorFlag["state"]["emptyError"] !== 1) {echo "".$regex["state"]["emptyError"]."";} ?>
</div>
</div>
<div class="col-md-5 col-sm-2">
<label for="InputDescription" class="form-label">Description</label>
<input type="text" name="description" class="form-control <?php if ($errorFlag["description"]["emptyError"] !== 1) {echo "is-invalid";}?>" id="InputDescription">
<div class="invalid-feedback">
<?php if ($errorFlag["description"]["emptyError"] !== 1) {echo "".$regex["description"]["emptyError"]."";} ?>
</div>
</div>
<div class="col-sm-2 col-md-5">
<label for="InputCost" class="form-label">Cost per hour</label>
<input type="text" name="cost_per_hr" class="form-control <?php if ($errorFlag["cost_per_hr"]["emptyError"] !== 1 or $errorFlag["cost_per_hr"]["formatError"] !== 1) {echo "is-invalid";}?>">
<p class="text-primary">two-decimal number</p>
<div class="invalid-feedback">
<?php if ($errorFlag["cost_per_hr"]["emptyError"] !== 1) {echo "".$regex["cost_per_hr"]["emptyError"]."";}
else if ($errorFlag["cost_per_hr"]["formatError"] !== 1) {echo "".$regex["cost_per_hr"]["formatError"]."";} ?>
</div>
</div>
<div class="col-sm-2 col-md-5">
<label for="InputCapacity" class="form-label">Capacity</label>
<input type="text" name="capacity" class="form-control <?php if ($errorFlag["capacity"]["emptyError"] !== 1 or $errorFlag["capacity"]["formatError"] !== 1) {echo "is-invalid";}?>">
<div class="invalid-feedback">
<?php if ($errorFlag["capacity"]["emptyError"] !== 1) {echo "".$regex["capacity"]["emptyError"]."";}
else if ($errorFlag["capacity"]["formatError"] !== 1) {echo "".$regex["capacity"]["formatError"]."";} ?>
</div>
</div>
<div class="col-sm-2 col-md-5">
<label for="InputAvailable" class="form-label">Available</label>
<input type="text" name="availability" class="form-control <?php if ($errorFlag['availability']['emptyError'] !== 1 or $errorFlag['availability']['formatError'] !== 1 or $errorFlag['availability']['identicalError'] !== 1) {echo 'is-invalid';}?>" />
<div class="invalid-feedback">
<?php if ($errorFlag["availability"]["emptyError"] !== 1) {echo "".$regex["availability"]["emptyError"]."";}
else if ($errorFlag["availability"]["formatError"] !== 1) {echo "".$regex["availability"]["formatError"]."";}
else if ($errorFlag["availability"]["identicalError"] !== 1) {echo "".$regex["availability"]["identicalError"]."";} ?>
</div>
</div>
<div class="col-12">
<button type="submit" name="submitAdd" class="btn btn-primary" style="width: 150px; margin: 20px auto;">Submit</button>
</div>
</form>

