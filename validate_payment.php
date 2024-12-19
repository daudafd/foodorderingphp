<?php

require_once 'admin/admin_class.php';
$admin = new AdminClass();

if (isset($_POST['transaction_reference'])) {
    $transaction_reference = $_POST['transaction_reference'];

    // Validate the payment with Paystack
    $url = "https://api.paystack.co/transaction/verify/" . $transaction_reference;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer sk_test_d5032a62c7a44aaefd49d68184a1013a0c9561a6", // Replace with your Paystack secret key
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($result['status'] && $result['data']['status'] === 'success') {
        // Call save_order with the transaction reference
        $order_saved = $admin->save_order($transaction_reference);

        if ($order_saved) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['error' => 'Failed to save order.']);
        }
    } else {
        echo json_encode(['error' => 'Payment validation failed.']);
    }
} else {
    echo json_encode(['error' => 'Transaction reference not provided.']);
}


?>