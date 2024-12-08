<?php
// session_start();
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login() {
		extract($_POST);
	
		// Check if username and password are provided
		if (empty($username) || empty($password)) {
			return "Username or Password is empty.";
		}
	
		// Fetch user with the provided username
		$qry = $this->db->query("SELECT * FROM users WHERE username = '$username'");
		
	
		if ($qry->num_rows > 0) {
			$user = $qry->fetch_assoc();
	
			// Verify the provided password against the stored hash
			if (password_verify($password, $user['password'])) {
				foreach ($user as $key => $value) {
					if ($key != 'password' && !is_numeric($key)) {
						$_SESSION['login_' . $key] = $value;
					}
				}
				return 1; // Login successful
			} else {
				return 3; // Incorrect password
			}
		} else {
			return 3; // User not found
		}
	}


	function login2() {
		extract($_POST);
	
		// Check if username and password are provided
		if (empty($email) || empty($password)) {
			return "Email or Password is empty.";
		}
	
		// Fetch user with the provided username
		$qry = $this->db->query("SELECT * FROM user_info WHERE email = '$email'");
		if ($qry->num_rows > 0) {
			$user = $qry->fetch_assoc();
	
			// Verify the provided password against the stored hash
			if (password_verify($password, $user['password'])) {
				foreach ($user as $key => $value) {
					if ($key != 'password' && !is_numeric($key)) {
						$_SESSION['login_' . $key] = $value;
					}
				}
				// Fetch additional fields if needed (e.g., address, mobile)
				$_SESSION['address'] = $user['address'] ?? '';
				$_SESSION['mobile'] = $user['mobile'] ?? '';

				return 1; // Login successful
			} else {
				return 3; // Incorrect password
			}
		} else {
			return 3; // User not found
		}
	}	
	
	
	// Helper function to get the client's IP address
	private function getClientIP() {
		if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			return $_SERVER['HTTP_CLIENT_IP'];
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}
	
	
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../index.php");
	}

	function save_user() {
		extract($_POST);
	
		// Prepare the data
		$data = " name = '$name', ";
		$data .= " username = '$username', ";
		
		// Only hash the password if it's provided
		if (!empty($password)) {
			$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
			$data .= " password = '$hashedPassword', ";
		}
	
		$data .= " type = '$type' ";
	
		// Insert or update logic
		if (empty($id)) {
			// New user creation
			$save = $this->db->query("INSERT INTO users SET $data");
		} else {
			// Update existing user
			$save = $this->db->query("UPDATE users SET $data WHERE id = $id");
		}
	
		if ($save) {
			return 1;
		}
	}	
	
	function signup() {
		// Debugging: Output only in non-production mode
		if (defined('DEBUG') && DEBUG) {
			echo "<pre>";
			print_r($_POST);
			echo "</pre>";
		}
	
		// Check if any field is empty
		$fields = ['first_name', 'last_name', 'mobile', 'address', 'email', 'password'];
		foreach ($fields as $field) {
			if (empty($_POST[$field])) {
				error_log("Field '$field' is empty.");
			}
		}
	
		// Validation checks
		if (
			empty($_POST['email']) || 
			empty($_POST['password']) || 
			empty($_POST['first_name']) || 
			empty($_POST['last_name']) || 
			empty($_POST['mobile']) || 
			empty($_POST['address'])
		) {
			return json_encode(["error" => "All fields are required."]);
		}
	
		// Extract and sanitize POST values
		extract($_POST);
		$email = $this->db->real_escape_string(strtolower($email));
		$password_hashed = password_hash($password, PASSWORD_DEFAULT);
	
		$data = "
			first_name = '" . $this->db->real_escape_string($first_name) . "',
			last_name = '" . $this->db->real_escape_string($last_name) . "',
			mobile = '" . $this->db->real_escape_string($mobile) . "',
			address = '" . $this->db->real_escape_string($address) . "',
			email = '$email',
			password = '$password_hashed'
		";
	
		// Check if email already exists
		$chk_query = "SELECT COUNT(*) as count FROM user_info WHERE LOWER(email) = '$email'";
		$chk = $this->db->query($chk_query)->fetch_assoc()['count'];
	
		if ($chk > 0) {
			return json_encode(['error' => 'Email already exists.']);
		}
	
		// Insert user into the database
		$save = $this->db->query("INSERT INTO user_info SET " . $data);
		if ($save) {
			$login = $this->login2(); // Log the user in after successful signup
			return json_encode(['success' => 'Signup successful']);
		} else {
			// Log the database error in case of failure
			error_log("Signup Error: " . $this->db->error);
			return json_encode(['error' => 'Signup failed. Database error: ' . $this->db->error]);
		}
	}
	

	function save_settings(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'../assets/img/'. $fname);
					$data .= ", cover_img = '$fname' ";

		}
		
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data." where id =".$chk->fetch_array()['id']);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['setting_'.$key] = $value;
		}

			return 1;
				}
	}

	
	function save_category(){
		extract($_POST);
		$data = " name = '$name' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO category_list set ".$data);
		}else{
			$save = $this->db->query("UPDATE category_list set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_category(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM category_list where id = ".$id);
		if($delete)
			return 1;
	}
	
	function save_menu(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", price = '$price' ";
		$data .= ", category_id = '$category_id' ";
		$data .= ", description = '$description' ";
		if(isset($status) && $status  == 'on')
		$data .= ", status = 1 ";
		else
		$data .= ", status = 0 ";

		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'../assets/img/'. $fname);
					$data .= ", img_path = '$fname' ";

		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO product_list set ".$data);
		}else{
			$save = $this->db->query("UPDATE product_list set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}

	function delete_menu(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM product_list where id = ".$id);
		if($delete)
			return 1;
	}

	function add_to_cart(){
		extract($_POST);
		$data = " product_id = $pid ";	
		$qty = isset($qty) ? $qty : 1 ;
		$data .= ", qty = $qty ";	
		if(isset($_SESSION['login_user_id'])){
			$data .= ", user_id = '".$_SESSION['login_user_id']."' ";	
		}else{
			$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
			$data .= ", client_ip = '".$ip."' ";	

		}
		$save = $this->db->query("INSERT INTO cart set ".$data);
		if($save)
			return 1;
	}
	
	function get_cart_count(){
		extract($_POST);
		if(isset($_SESSION['login_user_id'])){
			$where =" where user_id = '".$_SESSION['login_user_id']."'  ";
		}
		else{
			// $ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
			$ip = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : (isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
			$where =" where client_ip = '$ip'  ";
		}
		$get = $this->db->query("SELECT sum(qty) as cart FROM cart ".$where);
		if($get->num_rows > 0){
			return $get->fetch_array()['cart'];
		}else{
			return '0';
		}
	}

	function update_cart_qty(){
		extract($_POST);
		$data = " qty = $qty ";
		$save = $this->db->query("UPDATE cart set ".$data." where id = ".$id);
		if($save)
		return 1;	
	}

	function save_order() {
		// Start session (if not already started)
		if (session_status() == PHP_SESSION_NONE) {
			session_start();
		}
	
		// Get user data from session and POST
		$first_name = trim($_SESSION['login_first_name'] ?? '');
		$last_name = trim($_SESSION['login_last_name'] ?? '');
		$email = trim($_SESSION['login_email'] ?? '');
		$address = trim($_SESSION['address'] ?? ''); // Address from session
		$mobile = trim($_SESSION['mobile'] ?? '');  // Mobile from session
		
		// $address = trim($_POST['address'] ?? '');
		// $mobile = trim($_POST['mobile'] ?? '');
	
		// Debug input data
		error_log("First Name: $first_name");
		error_log("Last Name: $last_name");
		error_log("Email: $email");
		error_log("Address: $address");
		error_log("Mobile: $mobile");
	
		// Validate data
		if (empty($first_name) || empty($last_name) || empty($address) || empty($mobile) || empty($email)) {
			echo json_encode(["error" => "Incomplete order details"]);
			exit; // Stop script execution
		}
	
		if (empty($_SESSION['login_user_id'])) {
			echo json_encode(["error" => "Session expired. Please log in again."]);
			exit;
		}
	
		// Escape data to prevent SQL injection
		$address = $this->db->real_escape_string($address);
		$mobile = $this->db->real_escape_string($mobile);
		$email = $this->db->real_escape_string($email);
	
		// Start transaction
		$this->db->begin_transaction();
		try {
			// Insert order into database
			$data = " name = '" . $first_name . " " . $last_name . "' ";
			$data .= ", address = '$address' ";
			$data .= ", mobile = '$mobile' ";
			$data .= ", email = '$email' ";
			$data .= ", user_id = '" . $_SESSION['login_user_id'] . "' "; // Optional: link the order to the user
	
			$save = $this->db->query("INSERT INTO orders SET " . $data);
			if (!$save) {
				throw new Exception("Failed to insert order: " . $this->db->error);
			}
	
			$order_id = $this->db->insert_id; // Get the inserted order ID
	
			// Fetch cart items for the user
			$qry = $this->db->query("SELECT * FROM cart WHERE user_id = " . $_SESSION['login_user_id']);
			if (!$qry) {
				throw new Exception("Failed to fetch cart: " . $this->db->error);
			}
	
			// Process each cart item
			while ($row = $qry->fetch_assoc()) {
				$data = " order_id = '$order_id' ";
				$data .= ", product_id = '" . $row['product_id'] . "' ";
				$data .= ", qty = '" . $row['qty'] . "' ";
	
				$save_item = $this->db->query("INSERT INTO order_list SET " . $data);
				if (!$save_item) {
					throw new Exception("Failed to insert order item: " . $this->db->error);
				}
	
				// Remove the item from the cart
				$delete_cart = $this->db->query("DELETE FROM cart WHERE id = " . $row['id']);
				if (!$delete_cart) {
					throw new Exception("Failed to delete cart item: " . $this->db->error);
				}
			}
	
			// Commit transaction
			$this->db->commit();
	
			// Return success response
			echo json_encode(["success" => "Order saved successfully", "redirect_url" => "/home"]);
		} catch (Exception $e) {
			// Rollback transaction on error
			$this->db->rollback();
			
			// Log the error
			error_log("Order processing failed: " . $e->getMessage());
			
			// Return error response
			echo json_encode(["error" => "Order processing failed. Please try again."]);
		}
	
		exit; // Stop script execution
	}
	
	

function confirm_order(){
	extract($_POST);
		$save = $this->db->query("UPDATE orders set status = 1 where id= ".$id);
		if($save)
			return 1;
}

}