<!-- Masthead -->
<header class="masthead">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-10 align-self-end mb-4 page-title">
                <h3 class="text-white">My Orders</h3>
                <hr class="divider my-4" />
            </div>
        </div>
    </div>
</header>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="order-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Reference No.</th>
                            <th>Total Amount</th>
                            <th>Order Date</th>
                            <th>Order Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    include('admin/db_connect.php');

                    $data = ""; // Initialize query filter

                    // Check if the user is logged in
                    if (isset($_SESSION['login_user_id'])) {
                        // Filter by user_id and include all relevant payment statuses (0, 1, 2)
                        $data = "WHERE user_id = '" . $_SESSION['login_user_id'] . "' AND payment_status IN (0, 1, 2)";
                    } else {
                        // Fallback to IP address check
                        $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : 
                            (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
                        $data = "WHERE client_ip = '$ip' AND payment_status IN (0, 1, 2)";
                    }

                    // Fetch orders with latest order first
                    $qry = $conn->query("SELECT * FROM orders $data ORDER BY created_at DESC");
                    $i = 1;

                    if ($qry->num_rows > 0):
                        while ($row = $qry->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $row['transaction_reference']; ?></td>
                            <td><?php echo $row['total_amount']; ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                            <td class="text-center">
                                <?php 
                                // Display payment status badges
                                if ($row['payment_status'] == 0): ?>
                                    <span class="badge bg-info">Awaiting confirmation</span>
                                <?php elseif ($row['payment_status'] == 1): ?>
                                    <span class="badge bg-success">Ready For Pickup</span>
                                <?php elseif ($row['payment_status'] == 2): ?>
                                    <span class="badge bg-success">Dispatched</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary view_order" data-id="<?php echo $row['id']; ?>">View Order</button>
                            </td>
                        </tr>
                    <?php endwhile; else: ?>
                        <!-- <tr>
                            <td colspan="6" class="text-center">No orders found.</td>
                        </tr> -->
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.8/af-2.7.0/b-3.2.0/b-colvis-3.2.0/date-1.5.4/r-3.0.3/sc-2.4.3/sb-1.8.1/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.1.8/af-2.7.0/b-3.2.0/b-colvis-3.2.0/date-1.5.4/r-3.0.3/sc-2.4.3/sb-1.8.1/datatables.min.js"></script>
<script>
    $(document).ready(function() {
        // Only initialize DataTable if there are rows in the table
        if ($('#order-table tbody tr').length > 0) {
            $('#order-table').DataTable({
                "order": [[4, "desc"]], // Default sorting by order status (latest first)
                "columnDefs": [
                    { "targets": [5], "orderable": false } // Disable sorting on the last column
                ],
                "language": {
                    "emptyTable": "No orders available",  // This message will show if the table has no rows
                    "zeroRecords": "No matching records found" // This message is shown if no records match the filter/search
                }
            });
        } else {
            // If there are no orders, hide the table and show the message
            $('#order-table').closest('.table-responsive').html('<div class="alert alert-info">No orders found.</div>');
        }

        $('.view_order').click(function() {
            uni_modal('Order', 'view_order2.php?id=' + $(this).attr('data-id'));
        });
    });
</script>
