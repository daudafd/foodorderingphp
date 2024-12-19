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
                <div class="form-group">
                    <label for="" class="control-label">Delivery Option</label>
                    <select id="delivery_option" name="delivery_option" class="form-control">
                        <option value="0">Self Pickup (Free)</option>
                        <option value="1200">Home Delivery + Include Takeaway Plastic (+1200)</option>
                    </select>
                </div>
                <!-- <div class="form-group">
                    <label for="" class="control-label">
                        <input type="checkbox" id="plastic_option" name="plastic_option" value="200">
                        Include Takeaway Plastic (+₦200)
                    </label>
                </div> -->
                <div class="form-group">
                    <label for="" class="control-label">Total Amount</label>
                    <input type="text" readonly class="form-control" 
                            value="<?php echo isset($_SESSION['total_amount']) ? number_format($_SESSION['total_amount'], 2) : '0.00'; ?>">
                </div>
                <div class="text-center">
                    <button class="btn btn-block btn-primary" id="proceedToPayment">Proceed to Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
function initializeCheckoutPage() {
    // Get DOM elements
    const deliveryOption = document.getElementById('delivery_option');
    const totalAmountField = document.querySelector('input[readonly]');
    const checkoutForm = document.getElementById('checkout-frm');
    const proceedToPaymentButton = document.getElementById('proceedToPayment');

    // Base total amount from session
    let baseTotal = <?php echo isset($_SESSION['total_amount']) ? $_SESSION['total_amount'] : 0; ?>;

    // Update total amount based on delivery option
    function updateTotal() {
        let deliveryCharge = parseFloat(deliveryOption.value) || 0;
        let newTotal = baseTotal + deliveryCharge;
        totalAmountField.value = newTotal.toFixed(2);
    }

    // Handle Paystack payment
    function handlePayment() {
        const orderDetails = {
            email: "<?php echo $_SESSION['login_email']; ?>",
            amount: (baseTotal + parseFloat(deliveryOption.value || 0)) * 100, // Amount in kobo
            reference: "PS" + Math.floor(Math.random() * 1000000000),
            name: "<?php echo $_SESSION['login_first_name'] . ' ' . $_SESSION['login_last_name']; ?>",
            phone: "<?php echo $_SESSION['login_mobile']; ?>"
        };

        // Validate amount
        if (orderDetails.amount <= 0) {
            alert("Total amount is invalid. Please check your order.");
            return;
        }

        // Initialize Paystack
        const handler = PaystackPop.setup({
            key: 'pk_test_2e511fd2fd5ccbf4f54a1d85b0217526c2ad6eff',
            email: orderDetails.email,
            amount: orderDetails.amount,
            currency: "NGN",
            ref: orderDetails.reference,
            metadata: {
                custom_fields: [
                    { display_name: "Name", variable_name: "name", value: orderDetails.name },
                    { display_name: "Phone Number", variable_name: "phone_number", value: orderDetails.phone }
                ]
            },
            callback: function(response) {
                saveOrder(response.reference);
            },
            onClose: function() {
                alert("Transaction was not completed. Please try again.");
            }
        });

        handler.openIframe();
    }

    // Save order via AJAX
    function saveOrder(paymentReference) {
        $.ajax({
            url: 'admin/ajax.php?action=save_order',
            type: 'POST',
            data: {
                first_name: $('input[name="first_name"]').val(),
                last_name: $('input[name="last_name"]').val(),
                mobile: $('input[name="mobile"]').val(),
                address: $('input[name="address"]').val(),
                email: $('input[name="email"]').val(),
                delivery_charge: deliveryOption.value,
                payment_reference: paymentReference
            },
            success: function(response) {
                let res = JSON.parse(response);
                if (res.success) {
                    alert(res.success);
                    window.location.href = 'index.php?page=home';
                } else if (res.error) {
                    alert(res.error);
                }
            },
            error: function(xhr, status, error) {
                alert("An unexpected error occurred. Please try again.");
                console.error("Error details:", xhr.responseText);
            }
        });
    }

    // Event listeners
    deliveryOption.addEventListener('change', updateTotal);

    proceedToPaymentButton.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default button behavior
        handlePayment();
    });

    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent form submission
        saveOrder(null); // Save order without payment reference for non-Paystack orders
    });

    // Initial total calculation
    updateTotal();
}

// Initialize checkout page scripts
document.addEventListener('DOMContentLoaded', initializeCheckoutPage);
</script>

</script>