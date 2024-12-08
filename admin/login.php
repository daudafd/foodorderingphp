<!DOCTYPE html>
<html lang="en">
<head>
	<title>Admin | FIFI's Cuisine</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="assets/images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="assets/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="assets/vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="assets/css/util.css">
	<link rel="stylesheet" type="text/css" href="assets/css/main.css">
<!--===============================================================================================-->

<?php include('./db_connect.php'); ?>
<?php 
session_start();
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

$query = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['setting_'.$key] = $value;
		}
?>
</head>
<style>
	/* Spinner style */
.spinner {
  display: inline-block;
  width: 16px;
  height: 16px;
  margin-right: 8px; /* Space between spinner and text */
  border: 2px solid transparent;
  border-top: 2px solid #ffffff; /* Adjust color to match design */
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

/* Spinner animation */
@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

/* Disable button styling for better visual feedback */
button:disabled {
  opacity: 0.8;
  cursor: not-allowed;
}
</style>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-form-title" style="background-image: url(assets/images/foodbanner.jpg);">
				</div>

				
				<form class="login100-form validate-form" form id="login-form" method="POST">
				<span class="login100-form-title-1">
						Sign In
					</span>
					<div class="wrap-input100 validate-input m-b-26" data-validate="Username is required">
						<span class="label-input100">Username</span>
						<input class="input100" type="text" name="username" placeholder="Enter username" required class="form-control">
						<span class="focus-input100"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-18" data-validate = "Password is required">
						<span class="label-input100">Password</span>
						<input class="input100" type="password" name="password" placeholder="Enter password" required class="form-control">
						<span class="focus-input100"></span>
					</div>
					<div class="container-login100-form-btn">
						<button type="submit" class="login100-form-btn">
							<span class="button-text">Login</span>
							<span class="spinner" style="display: none;"></span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	
<!--===============================================================================================-->
	<script src="assets/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/vendor/bootstrap/js/popper.js"></script>
	<script src="assets/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="assets/vendor/daterangepicker/moment.min.js"></script>
	<script src="assets/vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="assets/vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="assets/js/main.js"></script>

</body>
<script>
$('#login-form').submit(function (e) {
    e.preventDefault();

    var $submitBtn = $('#login-form button[type="submit"]');
    var $spinner = $submitBtn.find('.spinner');
    var $buttonText = $submitBtn.find('.button-text');

    // Disable button and show the spinner
    $submitBtn.attr('disabled', true);
    $spinner.show(); // Show the spinner
    $buttonText.text('Logging in...'); // Update the button text

    setTimeout(function () {
        $.ajax({
            url: 'ajax.php?action=login', // Ensure this matches your endpoint
            method: 'POST',
            data: $('#login-form').serialize(), // Serialize form data
            success: function (response) {
                var data = JSON.parse(response);
                if (data.success) {
                    // Successful login: Redirect to home page
                    window.location.href = 'index.php?page=home';
                } else {
                    alert('Login failed: ' + data.error);
                    $submitBtn.attr('disabled', false);
                    $spinner.hide(); // Hide the spinner
                    $buttonText.text('Login'); // Reset the button text
                }
            },
            error: function () {
                alert('An error occurred, please try again.');
                $submitBtn.attr('disabled', false);
                $spinner.hide(); // Hide the spinner
                $buttonText.text('Login'); // Reset the button text
            }
        });
    }, 1200); // Optional delay for spinner visibility before AJAX call
});
</script>	
</html>