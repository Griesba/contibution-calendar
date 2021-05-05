<?php
include_once('../model/dbconnect.php');

function get_random_question() {
    $instance = ConnectDb::getInstance();
    $conn = $instance->getConnection();
    $results =  $conn->query('SELECT * FROM question order by rand() Limit 5');
    
    $questionsMap = [];
    while ($result = $results->fetch()) {
        $questionsMap [] = $result;        
    } 
    return $questionsMap;

}

function get_random_image() {
    $instance = ConnectDb::getInstance();
    $conn = $instance->getConnection();
    $statement =  $conn->prepare('SELECT IdImage, LienImage FROM image ORDER BY rand()');
    
    $statement->execute();
	$results = $statement->fetchall();
    $images = [];
    foreach($results as $result) {

        $images [] = $result;        
        
    }
    return $images[0];

}
function get_contribution() {
    $instance = ConnectDb::getInstance();
    $conn = $instance->getConnection();
    $statement =  $conn->prepare('SELECT id, contrib_date, has_contributed, subject FROM contribution');    
    $statement->execute();
	$results = $statement->fetchall();
    $contribution = [];
    foreach($results as $result) {

        $date = new DateTime($result['contrib_date']);
        $foramtedDate = $date->format('Y-m-d');
        $obj = array(
                'subject' => $result['subject'],
                'hasContribution' => $result['has_contributed'] === '1'? true : false,
                'date' => $foramtedDate
            );
        
        $contribution [] = array( $foramtedDate => $obj);
        
    }
    return $contribution;
}

function save_selection ($imageId, $questionId, $cellId) {
    $instance = ConnectDb::getInstance();
    $conn = $instance->getConnection();

    $statement =  $conn->prepare('SELECT count as total FROM counter WHERE  agregat = :agregat LIMIT 1');
    $agregat = '(' . $imageId .','. $questionId . ','. $cellId.')';
    $statement->bindParam(':agregat', $agregat, PDO::PARAM_STR);
    $statement->execute();
    
    $results = $statement->fetch();

    if($results) {
        $value = 1 + $results['total'];
        $stmt = $conn->prepare("UPDATE counter SET count = ? WHERE agregat = ?");
        $stmt->bindParam(1, $value);
        $stmt->bindParam(2, $agregat);
        $stmt->execute();
    } else {
        $value = 1;
        $stmt = $conn->prepare("INSERT INTO counter (agregat, count) VALUES (?, ?)");
        $stmt->bindParam(1, $agregat);
        $stmt->bindParam(2, $value);
        $stmt->execute();
    }


    $insertion = "INSERT INTO couple(IdImage, IdQuestion, PositionCouple, CompteurCouple) VALUES ($imageId, $questionId, $cellId, 34234)";
        
    $execute =  $conn-> query($insertion);
    return $execute == true ? true : false;
    
}

function is_valid($id_cell, $id_question) {
    $instance = ConnectDb::getInstance();
    $conn = $instance->getConnection();
    

    $statement =  $conn->prepare('SELECT questionId, cell_id FROM reponses WHERE questionId = :id_question && cell_id = :id_cell');

    $statement->bindParam(':id_question', $id_question, PDO::PARAM_INT);
    $statement->bindParam(':id_cell', $id_cell, PDO::PARAM_INT);
    $statement->execute();
	$results = $statement->fetchall();   
    

    if(count($results) > 0) {
        return true;
    } 
    return false;
}

?>