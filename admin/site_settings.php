<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM system_settings LIMIT 1");
$meta = [];
if ($qry->num_rows > 0) {
    foreach ($qry->fetch_array() as $k => $val) {
        $meta[$k] = $val;
    }
}
?>

<!-- Place the first <script> tag in your HTML's <head> -->
<script src="https://cdn.tiny.cloud/1/ecyyq21wg06c2ehp31r1s9f8v321oud9uvirqx8enqpffv7o/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<div class="container-fluid">
    <!-- !PAGE CONTENT! -->
    <div class="w3-main" style="margin-left:300px;margin-top:43px;">
        <header class="w3-container" style="padding-top:22px">
            <h5><b><i class="fa fa-dashboard"></i> USERS</b></h5>
        </header>

        <div class="card col-lg-12">
            <div class="card-body">
                <form action="" id="manage-settings" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name" class="control-label">System Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="control-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo isset($meta['email']) ? $meta['email'] : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="contact" class="control-label">Contact</label>
                        <input type="text" class="form-control" id="contact" name="contact" value="<?php echo isset($meta['contact']) ? $meta['contact'] : '' ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="about" class="control-label">About Content</label>
                        <textarea name="about" class="text-jqte"><?php echo isset($meta['about_content']) ? $meta['about_content'] : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="" class="control-label">Image</label>
                        <input type="file" class="form-control" name="images[]" multiple onchange="displayImg(this, $(this))">
                    </div>
                    <div class="form-group">
                        <img src="<?php echo isset($meta['cover_img']) ? '../assets/img/'.$meta['cover_img'] :'' ?>" alt="" id="cimg">
                    </div>
                    <center>
                        <button class="btn btn-info btn-primary btn-block col-md-2">Save</button>
                    </center>
                </form>
            </div>
        </div>
        <style>
            img#cimg{
                max-height: 10vh;
                max-width: 6vw;
            }
        </style>

    <script>
    function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cimg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
            }    
        }        

	// $(document).ready(function() {
    //     $('.text-jqte').jqte();
    // });

    tinymce.init({
    selector: 'textarea.text-jqte', // Select the textarea by class
    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
    });

    $('#manage-settings').submit(function(e){
                e.preventDefault();
                start_load();
                $.ajax({
                    url: 'ajax.php?action=save_settings',
                    data: new FormData($(this)[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    method: 'POST',
                    type: 'POST',
                    error: function(err){
                        console.log(err);
                    },
                    success: function(resp) {
                    resp = JSON.parse(resp) // Parse the JSON response
                    if (resp.success) {
                        alert_toast(resp.success, 'success'); // Access resp.success
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else if (resp.error) {
                        alert_toast(resp.error, 'error'); // Access resp.error
                    }
                }
                });
            });        

        </script>
    </div>
</div>
