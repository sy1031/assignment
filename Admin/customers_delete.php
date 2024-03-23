<?php

require '../config/function.php';

$paraResultId = checkParamId('id');
if(is_numeric($paraResultId)){

    $customerId = validate($paraResultId);
    
    $customer = getById('customers', $customerId);

    if($customer['status'] == 200)
    {
        $response = delete('customers', $customerId);
        if($response){
            redirect('customers.php', 'Customer Profile Deleted Successfully.');
        }
        else
        {
            redirect('customers.php', 'Something Went Wrong!');
        }
    }
    else{
        redirect('customers.php', $category['message']);
    } 
}
else{
    redirect('customers.php', 'Something Went Wrong!');
}

?>