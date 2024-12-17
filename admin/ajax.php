<?php
ob_start();
session_start();

// Include the class that handles the actions
include 'admin_class.php';
$crud = new Action();

// Define valid actions to prevent invalid input
$valid_actions = [
    'login', 'login2', 'logout', 'logout2', 'save_user', 'delete_user', 'signup', 
    'save_settings', 'save_category', 'delete_category', 'save_menu', 
    'delete_menu', 'add_to_cart', 'get_cart_count', 'update_cart_qty', 
    'save_order', 'confirm_order', 'count_today_orders'
];

// Sanitize the action parameter to ensure it's a valid action
$action = isset($_GET['action']) && in_array($_GET['action'], $valid_actions) ? $_GET['action'] : null;

// If the action is invalid, return an error and exit
if ($action === null) {
    echo json_encode(['error' => 'Invalid action']);
    ob_end_flush();
    exit;
}

// Handle each action
switch ($action) {
    case 'login':
        $response = $crud->login();
        echo $response; // Return JSON response directly
        break;
    

    case 'login2':
        $login = $crud->login2();
        if ($login) {
            echo json_encode(['success' => $login]);
        } else {
            echo json_encode(['error' => 'Login2 failed']);
        }
        break;

    case 'logout':
        $logout = $crud->logout();
        if ($logout) {
            echo json_encode(['success' => 'Logged out successfully']);
        } else {
            echo json_encode(['error' => 'Logout failed']);
        }
        break;

    case 'logout2':
        $logout = $crud->logout2();
        if ($logout) {
            echo json_encode(['success' => 'Logged out successfully']);
        } else {
            echo json_encode(['error' => 'Logout2 failed']);
        }
        break;

    case 'save_user':
        $save = $crud->save_user();
        if ($save) {
            echo json_encode(['success' => 'User saved successfully']);
        } else {
            echo json_encode(['error' => 'Failed to save user']);
        }
        break;

        case 'get_user':
            if (isset($_GET['id'])) {
                $user = $action->get_user($_GET['id']);
                echo json_encode($user);
            }
            break;

            case 'delete_user':
                if (isset($_POST['id'])) {
                    $response = $crud->delete_user($_POST['id']); // Correct function call
                    echo $response; // Should already be JSON-encoded in the function
                } else {
                    echo json_encode(['error' => 'User ID not provided']);
                }
                break;

    case 'signup':
        $save = $crud->signup();
        if ($save) {
			// $_SESSION['login_id'] = $user_id; // Replace with the actual user ID
            echo json_encode(['success' => 'Signup successful']);
        } else {
            echo json_encode(['error' => 'Signup failed']);
        }
        break;

        case 'save_settings':
            $save = $crud->save_settings();
            if ($save) {
                echo json_encode(['success' => 'Settings saved successfully']);
            } else {
                echo json_encode(['error' => 'Failed to save settings']);
            }
            break;
        

        case 'save_category':
            echo $crud->save_category(); // This will echo the JSON response from the save_category function
            break;
        
        case 'delete_category':
            echo $crud->delete_category(); // This will echo the JSON response from the delete_category function
            break;

    case 'save_menu':
        $save = $crud->save_menu();
        if ($save) {
            echo json_encode(['success' => 'Menu saved successfully']);
        } else {
            echo json_encode(['error' => 'Failed to save menu']);
        }
        break;

    case 'delete_menu':
        $save = $crud->delete_menu();
        if ($save) {
            echo json_encode(['success' => 'Menu deleted successfully']);
        } else {
            echo json_encode(['error' => 'Failed to delete menu']);
        }
        break;

    case 'add_to_cart':
        $save = $crud->add_to_cart();
        if ($save) {
            echo json_encode(['success' => 'Item added to cart']);
        } else {
            echo json_encode(['error' => 'Failed to add item to cart']);
        }
        break;

    case 'get_cart_count':
        $save = $crud->get_cart_count();
        if ($save) {
            echo json_encode(['success' => $save]);
        } else {
            echo json_encode(['error' => 'Failed to get cart count']);
        }
        break;

    case 'update_cart_qty':
        $save = $crud->update_cart_qty();
        if ($save) {
            echo json_encode(['success' => 'Cart quantity updated']);
        } else {
            echo json_encode(['error' => 'Failed to update cart quantity']);
        }
        break;

    case 'save_order':
        $save = $crud->save_order();
        if ($save) {
            echo json_encode(['success' => 'Order saved successfully']);
        } else {
            echo json_encode(['error' => 'Failed to save order']);
        }
        break;

    case 'confirm_order':
        $save = $crud->confirm_order();
        if ($save) {
            echo json_encode(['success' => 'Order confirmed']);
        } else {
            echo json_encode(['error' => 'Failed to confirm order']);
        }
        break;

        case 'count_today_orders':
            $counts = $crud->count_today_orders();
            echo json_encode($counts); // Return counts as JSON
            break;

    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}

// End output buffering
ob_end_flush();
?>
