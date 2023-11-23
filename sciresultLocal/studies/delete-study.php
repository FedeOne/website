<?php
// On démarre une session
session_start();

require_once('../connect_sql2.php');
    $idStudy = strip_tags($_GET['idStudy']);
    $idProject = strip_tags($_GET['idProject']);
    
    $sql = 'SELECT * FROM `populations` WHERE `idProject` = :idProject AND `idStudy` = :idStudy;';
    $query = $db->prepare($sql);
    $query->bindValue(':idProject', $idProject, PDO::PARAM_INT);
    $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
    $query->execute();
    $populations = $query->fetch();

    if(isset($populations['idPopulation']) ){
        echo "You have active populations inside this study, first delete them";
        //header('Location: ../index.php');
        die();
    } else if(isset($_GET['idStudy']) && !empty($_GET['idStudy'])){
    require_once('../connect_sql2.php');

    // On nettoie l'id envoyé
    $user_id= strip_tags($_SESSION["user_id"]);
    $idStudy = strip_tags($_GET['idStudy']);
    $idProject = strip_tags($_GET['idProject']);

    $sql = 'SELECT * FROM `study` WHERE `idStudy` = :idStudy AND `idProject` = :idProject ';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    
    $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
    $query->bindValue(':idProject', $idProject, PDO::PARAM_INT);
    // On exécute la requête
    $query->execute();

    // On récupère le produit
    $article = $query->fetch();

    // On vérifie si le produit existe
    if(!$article){
        $_SESSION['erreur'] = "Cet id n'existe pas";
        header("Location: index-study.php?idProject=".$idProject );
        die();
    }

    $sql = 'DELETE FROM `study` WHERE `idStudy` = :idStudy AND `idProject` = :idProject AND `user_id`=:user_id; ;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
    $query->bindValue(':idProject', $idProject, PDO::PARAM_INT);    
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    
    // On exécute la requête
    $query->execute();
    $_SESSION['message'] = "Study deleted";
    header("Location: index-study-test.php?idProject=".$idProject );


}else{
    $_SESSION['erreur'] = "Invalid URL";
    header('Location: index-proj.php');
}