<?php

require '../config/function.php';

$paraResultId = checkParamId('id');
if(is_numeric($paraResultId)){

    $staffId = validate($paraResultId);
    
    $staff = getById('staff', $staffId);
    if($staff['status'] == 200)
    {
        $staffDeleteRes = delete('staff', $staffId);
        if($staffDeleteRes){
            redirect('admins.php', 'Admin Deleted Successfully.');
        }else{
            redirect('admins.php', 'Something Went Wrong');
        }
    }
    else{
        redirect('admins.php', $staff['message']);
    } 
}
else{
    redirect('admins.php', 'Something Went Wrong.');
}

?>