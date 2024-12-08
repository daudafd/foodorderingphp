<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Admin | FIFI's Cuisine</title>
 	

<?php include('./header.php'); ?>
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
	body{
		width: 100%;
	    height: calc(100%);
	    /*background: #007bff;*/
	}
	main#main{
		width:100%;
		height: calc(100%);
		background:white;
	}
	#login-right{
		position: absolute;
		right:0;
		width:40%;
		height: calc(100%);
		background:white;
		display: flex;
		align-items: center;
	}
	#login-left{
		position: absolute;
		left:0;
		width:60%;
		height: calc(100%);
		background:#00000061;
		display: flex;
		align-items: center;
	}
	#login-right .card{
		margin: auto
	}
	.logo {
	    margin: auto;
	    font-size: 8rem;
	    background: white;
	    border-radius: 50% 50%;
	    height: 29vh;
	    width: 13vw;
	    display: flex;
	    align-items: center;
	}
	.logo img{
		height: 80%;
		width: 80%;
		margin: auto
	}


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

  <main id="main" class=" bg-dark">
  		<div id="login-left">
  			<div class="logo">
  				<img src="../assets/img/sample_logo.png" alt="">
  			</div>
  		</div>
  		<div id="login-right">
  			<div class="card col-md-8">
  				<div class="card-body">
				  <form id="login-form" method="POST">
					<div class="form-group">
						<label for="username" class="control-label">Username</label>
						<input type="text" name="username" placeholder="Enter your username" required class="form-control">
					</div>
					<div class="form-group">
						<label for="password" class="control-label">Password</label>
						<input type="password" name="password" placeholder="Enter your password" required class="form-control">
					</div>
					<button type="submit" class="btn btn-info btn-sm"> 
    					<span class="button-text">Login</span>
						<span class="spinner" style="display: none;"></span>
					</button>
					</form>
  				</div>
  			</div>
  		</div>
   
  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


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