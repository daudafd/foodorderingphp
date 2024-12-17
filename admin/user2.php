<?php include('db_connect.php');?>

<div class="container-fluid">

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

<!-- Header -->
<header class="w3-container" style="padding-top:22px">
  <h5><b><i class="fa fa-dashboard"></i> Users</b></h5>
</header>
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
            <form action="" id="manage-user">
                <div class="card">
                    <div class="card-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label class="control-label">Name</label>
                            <input type="text" class="form-control" placeholder="input your name" required="" name="name">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Username</label>
                            <input type="text" class="form-control" placeholder="input your username" required="" name="username">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Password</label>
                            <input type="password" name="password" placeholder="Leave blank to keep current password" class="form-control">
                        </div>
                        <div class="form-group">
                            <select name="type" required>
                            <!-- <option value="admin">Admin</option> -->
                            <option value="user">User</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-user').get(0).reset()"> Cancel</button>
							</div>
						</div>
					</div>
                </div>
    </form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-body">
						<table class="table table-bordered table-hover">
							<thead>
                            <tr>
					<th class="text-center">#</th>
					<th class="text-center">Name</th>
					<th class="text-center">Username</th>
					<th class="text-center">Action</th>
				</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$cats = $conn->query("SELECT * FROM users WHERE type = 2 order by name asc");
								while($row=$cats->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<?php echo $row['name'] ?>
									</td>
                                    <td class="">
										<?php echo $row['username'] ?>
									</td>
									<td class="text-center">
                                    <button class="btn btn-sm btn-primary edit_user" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-username="<?php echo $row['username'] ?>">Edit</button>
                                    <button class="btn btn-sm btn-danger del_user" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
</style>
<script>
	
	$('#manage-user').submit(function(e){
    e.preventDefault();
    start_load();
    $.ajax({
        url:'ajax.php?action=save_user',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        success:function(resp){
            // Parse the JSON response
            var response = JSON.parse(resp);

            // Check if the response indicates success
            if(response.success){
                alert_toast(response.success, 'success');
                setTimeout(function(){
                    location.reload(); // Reload the page
                }, 500);
            }
            else if(response.error){
                alert_toast(response.error, 'error');
            }
        },
        error:function(){
            alert_toast("An error occurred, please try again", 'error');
        }
    });
});

$('.edit_user').click(function(){
    start_load();
    var cat = $('#manage-user');
    cat.get(0).reset(); // Reset form
    cat.find("[name='id']").val($(this).attr('data-id'));
    cat.find("[name='name']").val($(this).attr('data-name'));
    cat.find("[name='username']").val($(this).attr('data-username'));
    end_load();
});

$('.del_user').click(function(){
    _conf("Are you sure you want to delete this user?", "del_user", [$(this).attr('data-id')]);
});

function del_user(id) {
    start_load();
    $.ajax({
        url: 'ajax.php?action=delete_user',
        method: 'POST',
        data: {id: id},
        success: function(resp) {
            console.log(resp); // Debug response
            var response = JSON.parse(resp);
            if (response.status === 'success') {
                alert_toast(response.message, 'success');
                setTimeout(function(){
                    location.reload();
                }, 500);
            } else {
                alert_toast(response.message, 'error');
            }
        },
        error: function() {
            alert_toast("An error occurred, please try again", 'error');
        }
    });
}


</script>