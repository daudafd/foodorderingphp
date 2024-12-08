	<!-- !PAGE CONTENT! -->
	<div class="w3-main" style="margin-left:300px;margin-top:43px;">

<!-- Header -->
<header class="w3-container" style="padding-top:22px">
  <h5><b><i class="fa fa-dashboard"></i> Orders</b></h5>
</header>



<div class="container-fluid">
<div class="card">
		<div class="card-body">
  <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
        <tr>
        <th>#</th>
			<th>Name</th>
			<th>Address</th>
			<th>Email</th>
			<th>Mobile</th>
			<th>Status</th>
			<th></th>
        </tr>
      </thead>
      <tbody>
			<?php 
			$i = 1;
			include 'db_connect.php';
			$qry = $conn->query("SELECT * FROM orders ");
			while($row=$qry->fetch_assoc()):
			 ?>
			 <tr>
			 		<td><?php echo $i++ ?></td>
			 		<td><?php echo $row['name'] ?></td>
			 		<td><?php echo $row['address'] ?></td>
			 		<td><?php echo $row['email'] ?></td>
			 		<td><?php echo $row['mobile'] ?></td>
			 		<?php if($row['status'] == 1): ?>
			 			<td class="text-center"><span class="badge bg-success">Confirmed</span></td>
			 		<?php else: ?>
			 			<td class="text-center"><span class="badge bg-secondary">For Verification</span></td>
			 		<?php endif; ?>
			 		<td>
           <!-- <button id="confirm_order_button" class="btn btn-success">Confirm Order</button> -->

			 			<button id="confirm_order_button" class="btn btn-sm btn-primary view_order" data-id="<?php echo $row['id'] ?>" >View Order</button>
			 		</td>
			 </tr>
			<?php endwhile; ?>
		</tbody>
    </table>
  </div>
</div>



<script>
    $(document).ready(function() {
        $('.view_order').click(function() {
            console.log("Button clicked");
            uni_modal('Order','view_order.php?id=' + $(this).attr('data-id'));
        });
    });
</script>