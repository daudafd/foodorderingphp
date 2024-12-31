<!DOCTYPE html>
<html lang="en">
<?php
  include('header.php');

// Get banner images
$banner_qry = $conn->query("SELECT image_path FROM banner_images");
$banner_images = [];
while ($banner_row = $banner_qry->fetch_assoc()) {
    $banner_images[] = $banner_row['image_path'];
}

// Handle the case where there are no banner images
if (empty($banner_images)) {
    $banner_images = ['default-banner.jpg']; // Use a default image
}

// Option 1: Display a single random image (PHP)
$random_banner = $banner_images[array_rand($banner_images)];

// Option 2: Prepare image paths for JavaScript slideshow (PHP)
$banner_images_json = json_encode($banner_images);
  ?>

    <style>
    	header.masthead {
      background: linear-gradient(to bottom, rgb(0 0 0 / 40%) 0%, rgb(245 242 240 / 45%) 100%), url(assets/img/<?php echo $random_banner; ?>);
      background-repeat: no-repeat;
		  background-size: cover;
      transition: background-image 1s ease-in-out;
		}

    #mainNav .navbar-brand,
    #mainNav .nav-link {
      color: #ffffff !important; /* Ensure white text */
    }

    #mainNav .navbar-brand:hover,
    #mainNav .nav-link:hover {
      color: #000 !important; /* Hover color for navbar brand and nav links */
    }

    .navbar-toggler-icon {
      filter: invert(1); /* Make the toggle icon white */
    }

    </style>
    <body id="page-top">
        <!-- Navigation-->
        <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-white">
        </div>
      </div>
        <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" style="background-color: #ea3b16;" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="./"><?php echo $setting_name; ?></a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto my-2 my-lg-0">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="index.php?page=home">Home</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="index.php?page=cart_list"><span> <span class="badge badge-danger item_count">0</span> <i class="fa fa-shopping-cart"></i>  </span>Cart</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="index.php?page=about">About</a></li>
                        <?php if(isset($_SESSION['login_user_id'])): ?>
                          <li class="nav-item"><a class="nav-link js-scroll-trigger" href="index.php?page=order">Orders</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="admin/ajax.php?action=logout2"><?php echo "Welcome ". $_SESSION['login_first_name'] ?> <i class="fa fa-power-off"></i></a></li>
                      <?php else: ?>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="javascript:void(0)" id="login_now">Login</a></li>
                      <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
       
        <?php
          $allowed_pages = ['home', 'about', 'cart_list', 'checkout', 'order'];
          $page = isset($_GET['page']) && in_array($_GET['page'], $allowed_pages) ? basename($_GET['page']) : 'home';
          echo "<!-- Including page: $page.php -->"; // Debugging line
          include $page . '.php';
        ?>

       

<div class="modal fade" id="confirm_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body">
        <div id="delete_content"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal" role='dialog'>
    <div class="modal-dialog modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
      </div>
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="uni_modal_right" role='dialog'>
    <div class="modal-dialog modal-full-height  modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span class="fa fa-arrow-righ t"></span>
        </button>
      </div>
      <div class="modal-body">
      </div>
      </div>
    </div>
  </div>
        <footer class="bg-light py-5">
            <div class="container"><div class="small text-center text-muted">Copyright © 2024 - Designed by <a href="https://www.kosibound.com.ng" target="_blank">Kosibound</a></div></div>
        </footer>
        
       <?php include('footer.php') ?>
    </body>

    <?php $conn->close() ?>

</html>
