<?php include('db_connect.php');?>

<div class="container-fluid">

	<!-- !PAGE CONTENT! -->
	<div class="w3-main" style="margin-left:300px;margin-top:43px;">

<!-- Header -->
<header class="w3-container" style="padding-top:22px">
  <h5><b><i class="fa fa-dashboard"></i> Menu List</b></h5>
</header>
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-menu">
				<div class="card">
					<div class="card-header">
						    Menu Form
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div class="form-group">
								<label class="control-label">Menu Name</label>
								<input type="text" class="form-control" name="name">
							</div>
							<div class="form-group">
								<label class="control-label">Menu Description</label>
								<textarea cols="30" rows="3" class="form-control" name="description"></textarea>
							</div>
							<div class="form-group">
								<div class="custom-control custom-switch">
								  <input type="checkbox" name="status" class="custom-control-input" id="availability" checked>
								  <label class="custom-control-label" for="availability">Available</label>
								</div>
							</div>	
							<div class="form-group">
								<label class="control-label">Category </label>
								<select name="category_id" id="" class="custom-select browser-default">
									<?php
									$cat = $conn->query("SELECT * FROM category_list order by name asc ");
									while($row=$cat->fetch_assoc()):
									?>
									<option value="<?php echo $row['id'] ?>"><?php echo $row['name'] ?></option>
									<?php endwhile; ?>
								</select>
								
							</div>
							<div class="form-group">
								<label class="control-label">Price</label>
								<input type="number" class="form-control text-right" name="price" step="any">
							</div>
							<div class="form-group">
								<label for="" class="control-label">Image</label>
								<input type="file" class="form-control" name="img" onchange="displayImg(this,$(this))">
							</div>
							<div class="form-group">
								<img src="<?php echo isset($image_path) ? '../assets/img/'.$cover_img :'' ?>" alt="" id="cimg">
							</div>
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Save</button>
								<button class="btn btn-sm btn-default col-sm-3" type="button" onclick="$('#manage-menu').get(0).reset()"> Cancel</button>
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
									<th class="text-center">Img</th>
									<th class="text-center">Room</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$cats = $conn->query("SELECT * FROM product_list order by id asc");
								while($row=$cats->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>

								
									<td class="text-center">
										<img src="<?php echo isset($row['img_path']) ? '../assets/img/'.$row['img_path'] :'' ?>" alt="" id="cimg">
									</td>
									<td class="">
										<p>Name : <b><?php echo $row['name'] ?></b></p>
										<p>Description : <b class="truncate"><?php echo $row['description'] ?></b></p>
										<p>Price : <b><?php echo "$".number_format($row['price'],2) ?></b></p>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_menu" type="button" data-id="<?php echo $row['id'] ?>" data-name="<?php echo $row['name'] ?>" data-status="<?php echo $row['status'] ?>" data-description="<?php echo $row['description'] ?>" data-price="<?php echo $row['price'] ?>" data-img_path="<?php echo $row['img_path'] ?>">Edit</button>
										<button class="btn btn-sm btn-danger delete_menu" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
	img#cimg,.cimg{
		max-height: 10vh;
		max-width: 6vw;
	}
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset !important;
	}
	.custom-switch,.custom-control-input,.custom-control-label{
		cursor:pointer;
	}
	b.truncate {
		 overflow: hidden;
   text-overflow: ellipsis;
   display: -webkit-box;
   -webkit-line-clamp: 3; 
   -webkit-box-orient: vertical;	
    font-size: small;
    color: #000000cf;
    font-style: italic;
}
</style>
<script>
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }

	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$('#manage-menu').submit(function(e) {
    e.preventDefault(); // Prevent the form from submitting normally
    start_load(); // Show loading spinner or indicator if you have one
    $.ajax({
        url: 'ajax.php?action=save_menu', // Endpoint to handle form submission
        data: new FormData($(this)[0]), // Serialize form data
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST', // HTTP method
        type: 'POST', // Fallback method
        success: function(resp) {
            try {
                let response = JSON.parse(resp); // Parse JSON response
                if (response.success) {
                    alert_toast("Data successfully added", 'success'); // Show success toast
                    setTimeout(function() {
                        location.reload(); // Reload the page after 1.5 seconds
                    }, 500);
                } else if (response.error) {
                    alert_toast(response.error, 'danger'); // Show error toast if provided
                }
            } catch (e) {
                alert_toast("Unexpected error occurred. Please try again.", 'danger'); // Catch unexpected errors
            }
            end_load(); // Hide loading spinner or indicator
        },
        error: function() {
            alert_toast("An error occurred while saving the menu. Please try again.", 'danger'); // Handle AJAX errors
            end_load(); // Hide loading spinner
        }
    });
});

	$('.edit_menu').click(function(){
		start_load()
		var cat = $('#manage-menu')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='description']").val($(this).attr('data-description'))
		cat.find("[name='name']").val($(this).attr('data-name'))
		cat.find("[name='price']").val($(this).attr('data-price'))
		if($(this).attr('data-status') == 1)
			$('#availability').prop('checked',true)
		else
			$('#availability').prop('checked',false)

		cat.find("#cimg").attr('src','../assets/img/'+$(this).attr('data-img_path'))
		end_load()
	})

	$('.delete_menu').click(function(){
		_conf("Are you sure to delete this menu?","delete_menu",[$(this).attr('data-id')])
	})

	function delete_menu($id){
    start_load(); // Start loading animation
    $.ajax({
        url: 'ajax.php?action=delete_menu',
        method: 'POST',
        data: {id: $id},
        dataType: 'json', // Ensure the response is treated as JSON
        success: function(resp){
            console.log(resp); // Log the response for debugging
            if(resp.success == "Menu deleted successfully") {
                alert_toast("Data successfully deleted", 'success'); // Show success toast
                setTimeout(function(){
                    location.reload(); // Force reload after a delay
                }, 100); // Adjust the delay if needed
            } else {
                alert_toast("Failed to delete data", 'error'); // In case of failure
            }
        },
        error: function() {
            alert_toast("An error occurred. Please try again.", 'error');
        }
    });
}

</script>