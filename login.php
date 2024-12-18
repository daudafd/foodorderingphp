<?php session_start() ?>
<div class="container-fluid">
  <form id="login-form" method="POST" action="ajax.php?action=login2">
    <div class="form-group">
    <div id="login-error" class="text-danger mt-2" style="display:none;"></div> <!-- Error message area -->
      <label for="" class="control-label">Email</label>
      <input type="email" name="email" required="" class="form-control">
    </div>
    <div class="form-group">
      <label for="" class="control-label">Password</label>
      <input type="password" name="password" required="" class="form-control">
      <small><a href="javascript:void(0)" id="new_account">Create New Account</a></small>
    </div>
    <button type="submit" class="button btn btn-info btn-sm">Login</button>
    <!-- <button type="button" class="btn btn-secondary" id="closeModal">Close</button> -->
  </form>
</div>

<style>
  #uni_modal .modal-footer {
    display: none;
  }

  .rolling {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
</style>

<script>
    $('#new_account').click(function(){
		uni_modal("Create an Account",'signup.php?redirect=index.php?page=checkout')
	})

$('#login-form').submit(function(e) {
  e.preventDefault();

  // Disable button and add the rolling animation immediately
  var $submitBtn = $('#login-form button[type="submit"]');
  $submitBtn.attr('disabled', true).html(''); // Remove text (clear the button text)
  $submitBtn.append('<div class="rolling"></div>'); // Add spinner inside the button

  // Hide any previous error messages
  $('#login-error').hide();

  $.ajax({
    url: 'admin/ajax.php?action=login2', // Ensure this matches your endpoint
    method: 'POST',
    data: $('#login-form').serialize(), // Serialize form data
    success: function(response) {
      var data = JSON.parse(response);
      setTimeout(function() { // Add a delay here after the AJAX request
        if (data.success) {
          window.location.href = 'index.php?page=home'; // Redirect to the home page
        } else {
          $('#login-error').text('Login failed: ' + data.error).show(); // Show error message
          $submitBtn.attr('disabled', false).html('Login'); // Reset button text
          var errorMessage = data.error || 'An unknown error occurred.';
            alert(errorMessage);
        }
      }, 1000); // Delay in milliseconds (2 seconds)
    },
    error: function() {
      $('#login-error').text('An error occurred, please try again.').show(); // Show error message
      $submitBtn.attr('disabled', false).html('Login'); // Reset button text
    }
  });
});

// Ensure that the modal stays open when there's an error
$('#loginModal').on('hidden.bs.modal', function () {
  // This will keep the modal open in case of error and prevent automatic closure
  // You can also trigger the error message again if needed
});

$('#closeModal').click(function() {
  // Clear error messages or reset form fields if needed
  $('#login-error').text('').hide(); 
  $('#login-form')[0].reset(); 
  $('#loginModal').modal('hide'); 
});
</script>
