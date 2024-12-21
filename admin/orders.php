<div class="w3-main" style="margin-left:300px;margin-top:43px;">

    <header class="w3-container" style="padding-top:22px">
        <h5><b><i class="fa fa-dashboard"></i> Orders</b></h5>
    </header>

    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="order-table" class="table table-bordered table-striped"> <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Mobile</th>
                                <th>Time</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            include 'db_connect.php';
                            $qry = $conn->query("SELECT * FROM orders ORDER BY created_at DESC"); // Order by time (newest first)
                            while ($row = $qry->fetch_assoc()) :
                            ?>
                                <tr>
                                    <td><?php echo $i++ ?></td>
                                    <td><?php echo $row['name'] ?></td>
                                    <td><?php echo $row['address'] ?></td>
                                    <td><?php echo $row['mobile'] ?></td>
                                    <td><?php echo $row['created_at'] ?></td>
                                    <?php if ($row['status'] == 1) : ?>
                                        <td class="text-center"><span class="badge bg-success">Confirmed</span></td>
                                    <?php else : ?>
                                        <td class="text-center"><span class="badge bg-secondary">For Verification</span></td>
                                    <?php endif; ?>
                                    <td>
                                        <button class="btn btn-sm btn-primary view_order" data-id="<?php echo $row['id'] ?>">View Order</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
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
            $('#order-table').DataTable({ // Initialize DataTable on the table
                "order": [[ 4, "desc" ]] // Default sort by "Time" column (index 4) descending
            });

            $('.view_order').click(function() {
                uni_modal('Order', 'view_order.php?id=' + $(this).attr('data-id'));
            });
        });
    </script>

</div>