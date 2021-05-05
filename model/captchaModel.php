<?php

if(session_status() == PHP_SESSION_NONE){
    session_start();
}

include_once('dbconnect.php');
include_once('../controller/functions.php');

if (!isset($_POST['question'])){
	//Requête permettant de récupérer les images
	$get_image = $db->prepare('SELECT IdImage, LienImage FROM image ORDER BY rand() LIMIT 2');
    
	//Requête permettant de récupérer les questions
	$get_question = $db->prepare('SELECT LibelleQuestion FROM question ORDER BY rand() LIMIT 5');
    
	//Requête permettant de récupérer un couple
	$get_couple = $db->prepare('SELECT IdCouple FROM couple WHERE IdImage = :IdImage AND IdQuestion = :IdQuestion AND PositionCouple = :PositionCouple');

	//Requête permettant de créer un couple
	$insert_couple = $db->prepare('INSERT INTO couple(IdImage, IdQuestion, PositionCouple, CompteurCouple) VALUES(:IdImage, :IdQuestion, :PositionCouple, :CompteurCouple)');

	//Requête permettant de mettre à jour le compteur de clics d'un couple
	$update_couple = $db->prepare('UPDATE couple SET CompteurCouple = :CompteurCouple WHERE IdCouple = :IdCouple');


	//Récupération des images
	try{
		$get_image->execute();
		$image = $get_image->fetchall();
        
        
	} catch (Exception $e){
	    // En cas d'erreur, on affiche un message et on arrête tout
	    $error = "Erreur lors de la récupération des images";
	  	w_log("ERROR/" + $error);
	  	die ('Erreur : '.$e->getMessage());
	}

	//Récupération des questions
	try{
		$get_question->execute();
		$question = $get_question->fetchall();

	} catch (Exception $e){
	    // En cas d'erreur, on affiche un message et on arrête tout
	  	$error = "Erreur lors de la récupération des questions";
	  	w_log("ERROR/" + $error);
	  	die ('Erreur : '.$e->getMessage());
	}
    


    
}

/*if(isset($_POST['image'])){
    $i = $_POST['image'];
    $insertion = "INSERT INTO test (compteur) VALUES ('$i')";
        
    $execute = $bdd-> query($insertion);
    echo "succes 2";
    $a = $i;
    

echo $a;
}*/
/* if(isset($_POST['case'])){
    $i = $_POST['case'];
    $insertion = "INSERT INTO test (compteur) VALUES ('$i')";
        
    $execute = $bdd-> query($insertion);

}
if(isset($_POST['submit'])){
    if(isset($_POST['case'])){
        $i = $_POST['case'];
        if(isset($_POST['question']) ){
            $IdImage = 1;
            $IdQuestion = $_POST['question'];
            //$PositionCouple = "$IdImage".","."$IdQuestion";
            $PositionCouple = "$i".","."$IdQuestion";
            $CompteurCouple = 0;
            
            $query = 'SELECT PositionCouple FROM couple WHERE $PositionCouple';
            if($PositionCouple == $query){
                $CompteurCouple = $CompteurCouple + 1;
                $q = "UPDATE captcha SET CompteurCouple = $CompteurCouple ";
            }else{
                $CompteurCouple = 0;
            }
        
        
           $insertion = "INSERT INTO couple(IdImage, IdQuestion, PositionCouple, CompteurCouple) VALUES ('$IdImage','$IdQuestion', '$PositionCouple', '$CompteurCouple')";
            //$insertion = "INSERT INTO test (compteur) VALUES ('$radio')";
        
            $execute = $bdd-> query($insertion);
        
        
         
                  
            if($execute == true){
                echo "succes";
            }else{
                echo "faille";
            }
        }

}*/

if(isset($_POST['questionId']) && isset($_POST['imageId']) && isset($_POST['cellId'])) {
    $questionId = $_POST['questionId'];
    $imageId = $_POST['imageId'];
    $cellId = $_POST['cellId'];


    //$insertion = "INSERT INTO couple(IdImage, IdQuestion, PositionCouple, CompteurCouple) VALUES ('$IdImage','$IdQuestion', '$PositionCouple', '$CompteurCouple')";

    $execute = save_selection($imageId, $questionId, $cellId);
    if($execute !== true) {
        var_dump('execute failed');
    }
    
    $isValid = is_valid($questionId, $cellId);
            
    if($execute === true && $isValid === true){
        if(!isset($_SESSION['count'])) {
            $_SESSION['count'] = 1;
            header('Location:../controller/captcha.php');
        } else {
            echo 'vous ête un humain';
        }        
    }else{
        $_SESSION['count'] = 0;
        echo "recommencer";
    }
}
