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
            redirect('staff.php', 'Staff Deleted Successfully.');
        }else{
            redirect('staff.php', 'Something Went Wrong');
        }
    }
    else{
        redirect('staff.php', $staff['message']);
    } 
}
else{
    redirect('staff.php', 'Something Went Wrong.');
}

?>