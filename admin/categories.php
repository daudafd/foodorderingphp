<?php include('db_connect.php');?>

<div class="container-fluid">

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

<!-- Header -->
<header class="w3-container" style="padding-top:22px">
  <h5><b><i class="fa fa-dashboard"></i> Menu Category</b></h5>
</header>
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-category">
				<div class="card">
					<div class="card-header">
						    Category Form
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">Category</label>
								<input type="text" class="form-control" name="name">
							</div>
							
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-category').get(0).reset()"> Cancel</button>
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
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$cats = $conn->query("SELECT * FROM category_list order by id asc");
								while($row=$cats->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<?php echo $row['name'] ?>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_cat" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" >Edit</button>
										<button class="btn btn-sm btn-danger delete_cat" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
	
	$('#manage-category').submit(function(e){
    e.preventDefault();
    start_load();
    $.ajax({
        url:'ajax.php?action=save_category',
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

$('.edit_cat').click(function(){
    start_load();
    var cat = $('#manage-category');
    cat.get(0).reset(); // Reset form
    cat.find("[name='id']").val($(this).attr('data-id'));
    cat.find("[name='name']").val($(this).attr('data-name'));
    end_load();
});

$('.delete_cat').click(function(){
    _conf("Are you sure you want to delete this category?", "delete_cat", [$(this).attr('data-id')]);
});

function delete_cat(id){
    start_load();
    $.ajax({
        url: 'ajax.php?action=delete_category',
        method: 'POST',
        data: {id: id},
        success:function(resp){
            // Parse the JSON response
            var response = JSON.parse(resp);

            if(response.success){
                alert_toast(response.success, 'success');
                setTimeout(function(){
                    location.reload(); // Reload the page after deletion
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
}

</script>