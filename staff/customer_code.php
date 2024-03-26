<?php
include('../config/function.php');

if (isset($_POST['updateCustomer'])) {

    $customerId = validate($_POST['customer_ID']);

    // Retrieve existing customer data
    $customerData = getById('customer', $customerId);
    if ($customerData['status'] != 200) {
        redirect('customer_edit.php?id=' . $customerId, 'Customer not found.');
    }

    // Retrieve user ID associated with the customer ID
    $userId = $customerData['data']['user_ID'];

    $first_name = validate($_POST['first_name']);
    $last_name = validate($_POST['last_name']);
    $email = validate($_POST['email']);
    $phone = validate($_POST['phone']);
    $username = validate($_POST['username']);
    $address = validate($_POST['address']);

    if ($username != '' && $email != '') {
        // Prepare data for updating customer
        $customer_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'username' => $username,
            'address' => $address
        ];

        // Update customer record
        $result_customer = update('customer', $customerId, $customer_data);

        // Prepare data for updating user
        $user_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'username' => $username,
        ];

        // Update user record using user_id
        $result_user = update('user', $userId, $user_data);

        if ($result_customer && $result_user) {
            redirect('customer_edit.php?id=' . $customerId, 'Customer Updated Successfully!');
        } else {
            redirect('customer_edit.php?id=' . $customerId, 'Something Went Wrong');
        }
    } else {
        redirect('customer_edit.php?id=' . $customerId, 'Please fill required fields.');
    }
}
?>