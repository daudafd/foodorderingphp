<?php session_start() ?>
<div class="container-fluid">
<form id="login-form" method="POST" action="ajax.php?action=login">
		<div class="form-group">
			<label for="" class="control-label">Email</label>
			<input type="email" name="email" required="" class="form-control">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Password</label>
			<input type="password" name="password" required="" class="form-control">
			<small><a href="javascript:void(0)" id="new_account">Create New Account</a></small>
		</div>
		<button type="submit" class="button btn btn-info btn-sm">Login</button>
	</form>
</div>

<style>
	#uni_modal .modal-footer{
		display:none;
	}
</style>

<script>
$('#login-form').submit(function(e) {
    e.preventDefault();

    // Disable button and add the rolling animation immediately
    var $submitBtn = $('#login-form button[type="submit"]');
    $submitBtn.attr('disabled', true).html('Logging in...');
    $submitBtn.addClass('rolling'); // Add the rolling animation class

    $.ajax({
        url: 'admin/ajax.php?action=login2', // Ensure this matches your endpoint
        method: 'POST',
        data: $('#login-form').serialize(), // Serialize form data
        success: function(response) {
            var data = JSON.parse(response);
            setTimeout(function() { // Add a delay here after the AJAX request
                if (data.success) {
                    // Close the modal
                    $('#loginModal').modal('hide');
                    // Redirect to the home page
                    window.location.href = 'index.php?page=home'; // Redirect to the home page
                } else {
                    alert('Login failed: ' + data.error);
                    $submitBtn.attr('disabled', false).html('Login').removeClass('rolling'); // Reset button
                }
            }, 1000); // Delay in milliseconds (2 seconds)
        },
        error: function() {
            alert('An error occurred, please try again.');
            $submitBtn.attr('disabled', false).html('Login').removeClass('rolling'); // Reset button
        }
    });
});

</script>