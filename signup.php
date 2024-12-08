<?php session_start() ?>
<div class="container-fluid">
	<!-- <form action="" id="signup-form"> -->
	<form id="signup-form" method="POST" action="admin/ajax.php?action=signup">

		<div class="form-group">
			<label for="" class="control-label">Firstname</label>
			<input type="text" name="first_name" placeholder="Input your First name" required="" class="form-control">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Lastname</label>
			<input type="text" name="last_name" placeholder="Input your Last name" required="" class="form-control">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Phone</label>
			<input type="text" name="mobile" placeholder="Input your Phone Number" required="" class="form-control">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Address</label>
			<textarea cols="30" rows="3" name="address" placeholder="Input your address here" required="" class="form-control"></textarea>
		</div>
		<div class="form-group">
			<label for="" class="control-label">Email</label>
			<input type="email" name="email" placeholder="Input your email" required="" class="form-control">
		</div>
		<div class="form-group">
			<label for="" class="control-label">Password</label>
			<input type="password" name="password" placeholder="input your password" required="" class="form-control">
		</div>
		<button class="button btn btn-info btn-sm">Create</button>
	</form>
</div>

<style>
	#uni_modal .modal-footer{
		display:none;
	}
</style>
<script>
$('#signup-form').submit(function(e) {
    e.preventDefault();

    // Disable button and indicate progress
    $('#signup-form button[type="submit"]').attr('disabled', true).html('Saving...');

    if ($(this).find('.alert-danger').length > 0) {
        $(this).find('.alert-danger').remove();
    }

    $.ajax({
        url: 'admin/ajax.php?action=signup',
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.success) {
					setTimeout(function() {
        				window.location.href = 'index.php?page=home';
    					}, 500); // 500ms delay
                } else {
                    alert(data.error || 'Signup failed');
                }
            } catch (error) {
                console.error('Invalid response:', response);
                alert('An error occurred during signup.');
            }
        },
        error: function() {
            alert('An error occurred during signup.');
        }
    });
});

</script>