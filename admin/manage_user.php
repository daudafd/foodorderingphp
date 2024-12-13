<div class="container-fluid">

    <form action="" id="manage-menu">
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
        </div>
    </form>

        <!-- Toast HTML -->
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 11">
        <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    User saved successfully!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

</div>

<script>
        $(document).ready(function () {
            $('#manage-menu').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: 'ajax.php?action=save_user',
                    method: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (resp) {
                        if (resp.success) {
                            const successToast = new bootstrap.Toast(document.getElementById('successToast'));
                            successToast.show();

                            // Reload the page after 2 seconds
                            setTimeout(() => location.reload(), 2000);
                        } else if (resp.error) {
                            alert(resp.error);
                        }
                    },
                    error: function () {
                        alert('An error occurred.');
                    }
                });
            });
        });
    </script>