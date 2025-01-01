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
            <form id="checkout-frm">
                <h4>Confirm Delivery Information</h4>
                <div class="form-group">
                    <label for="first_name" class="control-label">Firstname</label>
                    <input type="text" id="first_name" name="first_name" required class="form-control" 
                           value="<?php echo $_SESSION['login_first_name'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="last_name" class="control-label">Last Name</label>
                    <input type="text" id="last_name" name="last_name" required class="form-control" 
                           value="<?php echo $_SESSION['login_last_name'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="mobile" class="control-label">Contact</label>
                    <input type="text" id="mobile" name="mobile" required class="form-control" 
                           value="<?php echo $_SESSION['login_mobile'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="address" class="control-label">Address</label>
                    <input type="text" id="address" name="address" required class="form-control" 
                           value="<?php echo $_SESSION['login_address'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="email" class="control-label">Email</label>
                    <input type="email" id="email" name="email" required class="form-control" 
                           value="<?php echo $_SESSION['login_email'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="delivery_option" class="control-label">Delivery Option</label>
                    <select id="delivery_option" name="delivery_option" class="form-control">
                        <option value="0">Self Pickup (Free)</option>
                        <option value="1200">Home Delivery + Include Takeaway Plastic (+1200)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="total_amount" class="control-label">Total Amount</label>
                    <input type="text" id="total_amount" readonly class="form-control" 
                           value="<?php echo number_format($_SESSION['total_amount'] ?? 0, 2); ?>">
                </div>
                <div class="row">
                    <div class="col text-center">
                        <button type="button" class="btn btn-block btn-secondary" id="proceedToPayment">Card Payment</button>
                    </div>
                    <div class="col text-center">
                        <button type="button" class="btn btn-block btn-primary" id="transfer">Make Transfer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal for Bank Transfer -->
<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transferModalLabel">Bank Transfer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Please make your transfer to the following account details:</p>
                <ul>
                    <li><strong>Account Name:</strong> Fisayo Dauda</li>
                    <li><strong>Bank:</strong> Opay</li>
                    <li><strong>Account Number:</strong> 7062319778</li>
                </ul>
                <p><strong>Total Amount:</strong> ₦<span id="modalTotalAmount"></span></p>
                <p>After making the transfer, click the button below to confirm your payment.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmTransfer">I have made payment</button>
            </div>
        </div>
    </div>
</div>

<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const deliveryOption = document.getElementById('delivery_option');
    const totalAmountField = document.getElementById('total_amount');
    const proceedToPaymentButton = document.getElementById('proceedToPayment');
    const transferButton = document.getElementById('transfer');
    const transferModal = new bootstrap.Modal(document.getElementById('transferModal'));
    const modalTotalAmount = document.getElementById('modalTotalAmount');
    const confirmTransferButton = document.getElementById('confirmTransfer');

    let baseTotal = <?php echo $_SESSION['total_amount'] ?? 0; ?>;

    // Update total amount based on delivery option
    function updateTotal() {
        let deliveryCharge = parseFloat(deliveryOption.value) || 0;
        let newTotal = baseTotal + deliveryCharge;
        totalAmountField.value = newTotal.toFixed(2);
        modalTotalAmount.textContent = newTotal.toFixed(2); // Update modal with the total amount
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

        console.log("Generated Payment Reference:", orderDetails.reference);

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
                console.log("Payment Reference from Paystack:", response.reference);
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
       
       const orderData = {
       first_name: $('input[name="first_name"]').val(),
       last_name: $('input[name="last_name"]').val(),
       mobile: $('input[name="mobile"]').val(),
       address: $('input[name="address"]').val(),
       email: $('input[name="email"]').val(),
       delivery_charge: deliveryOption.value,
       payment_reference: paymentReference
   };

   // Log the data to be sent
   console.log("Sending order data:", orderData);
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
               payment_reference: paymentReference || 'transfer' // Set an empty string for non-Paystack orders
           },
           success: function(response) {
               let res = JSON.parse(response);
               if (res.success) {
                   alert(res.success);
                   window.location.href = 'index.php?page=order';
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

    // Handle Bank Transfer
    function handleTransfer() {
        transferModal.show(); // Show the bank transfer modal
    }

    // Confirm Bank Transfer
    function confirmTransfer() {
        const orderData = {
            first_name: document.getElementById('first_name').value,
            last_name: document.getElementById('last_name').value,
            mobile: document.getElementById('mobile').value,
            address: document.getElementById('address').value,
            email: document.getElementById('email').value,
            delivery_charge: deliveryOption.value,
            payment_reference: 'transfer' // Indicate bank transfer
        };

        console.log("Sending bank transfer order data:", orderData);

        // Make AJAX request to save the order
        $.ajax({
            url: 'admin/ajax.php?action=save_order',
            type: 'POST',
            data: orderData,
            success: function(response) {
                let res = JSON.parse(response);
                if (res.success) {
                    alert(res.success);
                    window.location.href = 'index.php?page=order';
                } else if (res.error) {
                    alert(res.error);
                }
            },
            error: function(xhr, status, error) {
                alert("An unexpected error occurred. Please try again.");
                console.error("Error details:", xhr.responseText);
            }
        });

        transferModal.hide(); // Hide modal after confirmation
    }

    // Event listeners
    deliveryOption.addEventListener('change', updateTotal);
    proceedToPaymentButton.addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default button behavior
        handlePayment();
    });
    transferButton.addEventListener('click', handleTransfer);
    confirmTransferButton.addEventListener('click', confirmTransfer);

    // Initial total calculation
    updateTotal();
});
</script>
