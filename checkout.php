<header class="masthead">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-10 align-self-end mb-4 page-title">
                <h3 class="text-white">Checkout</h3>
                <hr class="divider my-4" />
            </div>
        </div>
    </div>
</header>
<div class="container">
    <div class="card">
        <div class="card-body">
            <form action="" id="checkout-frm">
                <h4>Confirm Delivery Information</h4>
                <div class="form-group">
                    <label for="" class="control-label">Firstname</label>
                    <input type="text" name="first_name" required class="form-control" 
                           value="<?php echo isset($_SESSION['login_first_name']) ? $_SESSION['login_first_name'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Last Name</label>
                    <input type="text" name="last_name" required class="form-control" 
                           value="<?php echo isset($_SESSION['login_last_name']) ? $_SESSION['login_last_name'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Contact</label>
                    <input type="text" name="mobile" required class="form-control" 
                           value="<?php echo isset($_SESSION['login_mobile']) ? $_SESSION['login_mobile'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Address</label>
                    <input type="text" name="address" required class="form-control" 
                           value="<?php echo isset($_SESSION['login_address']) ? $_SESSION['login_address'] : ''; ?>">
                </div>
                <div class="form-group">
                    <label for="" class="control-label">Email</label>
                    <input type="email" name="email" required class="form-control" 
                           value="<?php echo isset($_SESSION['login_email']) ? $_SESSION['login_email'] : ''; ?>">
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-block btn-outline-primary">Place Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#checkout-frm').submit(function(e) {
        e.preventDefault(); // Prevent default form submission
        start_load(); // Show loading indicator (assumes this function exists)
        
    // Disable button to indicate loading
    $('#login-form button[type="submit"]').attr('disabled', true).html('Saving your order...');

$.ajax({
    url: 'admin/ajax.php?action=save_order', // Your endpoint
    type: 'POST',
    data: {
        address: $('#address').val(),
        mobile: $('#mobile').val(),
    },
    success: function(response) {
        // Parse response if it's not already an object
        if (typeof response === "string") {
            response = JSON.parse(response);
        }

        if (response.success) {
            // alert(response.success); // Optional: show success message
            // Redirect to the provided URL
            window.location.href = 'index.php?page=home'; // Redirect to the home page
        } else if (response.error) {
            alert(response.error); // Show the error message
        }
    },
    error: function(xhr, status, error) {
        // Handle unexpected errors
        alert("An unexpected error occurred: " + error);
    }
});
    });
});

</script>