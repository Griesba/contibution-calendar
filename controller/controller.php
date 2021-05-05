<?php 

include_once('functions.php');

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

$action = $_POST['action'];

header('Content-Type: application/json');

if(isset($action)){
    switch($action) {
        case 'getContributions':
               
                echo getContributions();
               
               
            break;
        case 'validation':
            if(isset($_POST['imageId']) && isset($_POST['cellId']) && isset($_POST['questionId'])) {
                echo validation($_POST['imageId'], $_POST['cellId'], $_POST['questionId']);
            } else {
                echo json_encode( array('error' => 'invalid action: validation'));
            }

            break;
        default: 
         echo json_encode( array('error' => 'unknown action'));
    }
}


function getContributions () {
    
        $contributions = get_contribution();

        return json_encode($contributions);
}


function validation($old_img_id, $id_cell, $id_question) {

    save_selection ($old_img_id, $id_question, $id_cell);

    $count = 0;
    $total = 0;

    if( isset( $_SESSION['total'] ) ) {
        $_SESSION['total'] += 1;
     }else {
        $_SESSION['total'] = 1;
     }
     $total = $_SESSION['total'];
     
     if(!isset( $_SESSION['count'] ) ) {
        $_SESSION['count'] = 0;
     }

    if(is_valid($id_cell, $id_question)) {        

        $_SESSION['count'] += 1;
         
        $count = $_SESSION['count'];
        
        echo json_encode(array(
            'valid' => true,
            'count' => $_SESSION['count'],
            'total' => $total,
            'newImage' => get_new_image($old_img_id),
            'questions' => get_random_question()
        ));

    } else {
        
        if($_SESSION['count'] > 0) {
            $_SESSION['total'] = 1;
        }
        
        $_SESSION['count'] = 0;
        
        echo json_encode(array(
            'valid' => false,
            'count' => 0,
            'total' => $_SESSION['total'],     
            'newImage' => get_new_image ($old_img_id),
            'questions' => get_random_question()
        ));
    }

    if($_SESSION['count'] > 1 || $_SESSION['total'] > 1) {
        if($count > 1) {            
            $_SESSION['count'] = 0;
        }
        unset($_SESSION['count']);
        unset($_SESSION['total']);
    }
}
