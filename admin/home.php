<style>
	/* .custom-menu {
        z-index: 1000;
	    position: absolute;
	    background-color: #ffffff;
	    border: 1px solid #0000001c;
	    border-radius: 5px;
	    padding: 8px;
	    min-width: 13vw;
}
a.custom-menu-list {
    width: 100%;
    display: flex;
    color: #4c4b4b;
    font-weight: 600;
    font-size: 1em;
    padding: 1px 11px;
}
	span.card-icon {
    position: absolute;
    font-size: 3em;
    bottom: .2em;
    color: #ffffff80;
}
.file-item{
	cursor: pointer;
}
a.custom-menu-list:hover,.file-item:hover,.file-item.active {
    background: #80808024;
}
table th,td{
	/*border-left:1px solid gray;*/
/* }
a.custom-menu-list span.icon{
		width:1em;
		margin-right: 5px
}
.candidate {
    margin: auto;
    width: 23vw;
    padding: 0 10px;
    border-radius: 20px;
    margin-bottom: 1em;
    display: flex;
    border: 3px solid #00000008;
    background: #8080801a;

}
.candidate_name {
    margin: 8px;
    margin-left: 3.4em;
    margin-right: 3em;
    width: 100%;
}
	.img-field {
	    display: flex;
	    height: 8vh;
	    width: 4.3vw;
	    padding: .3em;
	    background: #80808047;
	    border-radius: 50%;
	    position: absolute;
	    left: -.7em;
	    top: -.7em;
	}
	
	.candidate img {
    height: 100%;
    width: 100%;
    margin: auto;
    border-radius: 50%;
}
.vote-field {
    position: absolute;
    right: 0;
    bottom: -.4em;
} */ */
</style>

<div class="container-fluid">

	<div class="row">
		<div class="col-lg-12">
			
		</div>
	</div>


	<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-left:300px;margin-top:43px;">

<!-- Header -->
<header class="w3-container" style="padding-top:22px">
  <h5><b><i class="fa fa-dashboard"></i> Orders Dashboard</b></h5>
</header>

<div class="w3-row-padding w3-margin-bottom">
  <div class="w3-quarter">
	<div class="w3-container w3-red w3-padding-16">
	  <div class="w3-left"><i class="fa fa-comment w3-xxxlarge"></i></div>
	  <div class="w3-right">
		<h3><span id="pending_count">0</span></h3>
	  </div>
	  <div class="w3-clear"></div>
	  <h4>Pending</h4>
	</div>
  </div>
  <div>
</div>
  <div class="w3-quarter">
	<div class="w3-container w3-blue w3-padding-16">
	  <div class="w3-left"><i class="fa fa-eye w3-xxxlarge"></i></div>
	  <div class="w3-right">
		<h3><span id="confirmed_count">0</span></h3>
	  </div>
	  <div class="w3-clear"></div>
	  <h4>Confirmed</h4>
	</div>
  </div>
  <div class="w3-quarter">
	<div class="w3-container w3-teal w3-padding-16">
	  <div class="w3-left"><i class="fa fa-share-alt w3-xxxlarge"></i></div>
	  <div class="w3-right">
		<h3><span id="total_orders">0</span></h3>
	  </div>
	  <div class="w3-clear"></div>
	  <h4>Total Orders</h4>
	</div>
  </div>
  <!-- <div class="w3-quarter">
	<div class="w3-container w3-orange w3-text-white w3-padding-16">
	  <div class="w3-left"><i class="fa fa-users w3-xxxlarge"></i></div>
	  <div class="w3-right">
		<h3>50</h3>
	  </div>
	  <div class="w3-clear"></div>
	  <h4>Users</h4>
	</div> -->
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    fetch('ajax.php?action=count_today_orders', {
        method: 'GET',
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        // Update the HTML with the counts
        document.getElementById('pending_count').innerText = data.pending || 0;
        document.getElementById('confirmed_count').innerText = data.confirmed || 0;
        document.getElementById('total_orders').innerText = data.total || 0;
    })
    .catch(error => {
        console.error('Error fetching order counts:', error);
    });
});


</script>