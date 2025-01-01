<div class="container-fluid">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Qty</th>
                <th>Order</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total = 0;
            $delivery_charge = 0;
            include 'admin/db_connect.php';

            // Fetch delivery charge from the orders table
            $order_id = $_GET['id'];
            $order_details = $conn->query("SELECT delivery_charge FROM orders WHERE id = $order_id");
            if ($order_details->num_rows > 0) {
                $delivery_charge = $order_details->fetch_assoc()['delivery_charge'];
            }

            // Fetch order list and calculate the total
            $qry = $conn->query("SELECT * FROM order_list o INNER JOIN product_list p ON o.product_id = p.id WHERE order_id = $order_id");
            while ($row = $qry->fetch_assoc()):
                $total += $row['qty'] * $row['price'];
            ?>
            <tr>
                <td><?php echo $row['qty'] ?></td>
                <td><?php echo $row['name'] ?></td>
                <td><?php echo number_format($row['qty'] * $row['price'], 2) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2" class="text-right">Subtotal</th>
                <th><?php echo number_format($total, 2) ?></th>
            </tr>
            <tr>
                <th colspan="2" class="text-right">Delivery Charge</th>
                <th><?php echo number_format($delivery_charge, 2) ?></th>
            </tr>
            <tr>
                <th colspan="2" class="text-right">Grand Total</th>
                <th><?php echo number_format($total + $delivery_charge, 2) ?></th>
            </tr>
        </tfoot>
    </table>
    <div class="text-center">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
    </div>
</div>


<style>
    #uni_modal .modal-footer {
        display: none;
    }
</style>

<script>
function confirm_order() {
    start_load(); // Show loading animation (if necessary)

    $.ajax({
        url: 'ajax.php?action=confirm_order',
        method: 'POST',
        data: {id: '<?php echo $_GET['id']; ?>'}, // Send the order ID via POST
        dataType: 'json', // Ensure we expect a JSON response
        success: function(resp) {
            if (resp.success) {
                // Store the success message in localStorage
                localStorage.setItem('toast_message', resp.success);
                localStorage.setItem('toast_type', 'success');

                // Reload the page after 1.5 seconds to reflect the change
                setTimeout(function(){
                    location.reload();
                }, 1500);
            } else {
                // Store the error message in localStorage
                localStorage.setItem('toast_message', resp.error);
                localStorage.setItem('toast_type', 'danger');

                // Reload the page after 1.5 seconds
                setTimeout(function(){
                    location.reload();
                }, 1500);
            }
        },
        error: function() {
            // Store the error message if AJAX fails
            localStorage.setItem('toast_message', "An error occurred. Please try again.");
            localStorage.setItem('toast_type', 'danger');
            
            // Reload the page after 1.5 seconds
            setTimeout(function(){
                location.reload();
            }, 0);
        }
    });
}
</script>

<script>
// When the page loads, display the toast message if it exists in localStorage
$(document).ready(function(){
    // Check if a toast message exists in localStorage
    var toastMessage = localStorage.getItem('toast_message');
    var toastType = localStorage.getItem('toast_type');

    if (toastMessage) {
        // Display the toast with the message
        alert_toast(toastMessage, toastType);
        
        // Remove the message from localStorage after showing it
        localStorage.removeItem('toast_message');
        localStorage.removeItem('toast_type');
    }

    // Hide preloader if it's visible
    $('#preloader').fadeOut('fast', function() {
        $(this).remove();
    });
});
</script>
