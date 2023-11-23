<?php
// On démarre une session
session_start();

// Est-ce que l'id existe et n'est pas vide dans l'URL
if(isset($_GET['idProject']) && !empty($_GET['idProject'])){
    require_once('../connect_sql2.php');

    $idProject = strip_tags($_GET['idProject']);
    $sql = 'SELECT * FROM `study` WHERE `idProject` = :idProject;';
    $query = $db->prepare($sql);
    $query->bindValue(':idProject', $idProject, PDO::PARAM_INT);
    $query->execute();
    $study = $query->fetch();

    if(isset($study['idStudy']) ){
        echo "You have active studies inside this project, first delete them";
        //header('Location: ../index-proj.php');
        die();
    } else{

    require_once('../connect_sql2.php');
    // On nettoie l'id envoyé
    $user_id= strip_tags($_SESSION["user_id"]);
    $idProject = strip_tags($_GET['idProject']);
    $sql = 'SELECT * FROM `project` WHERE `idProject` = :idProject;';
    // On prépare la requête
    $query = $db->prepare($sql);
    // On "accroche" les paramètre (id)
    $query->bindValue(':idProject', $idProject, PDO::PARAM_INT);
    // On exécute la requête
    $query->execute();
    // On récupère le produit
    $project = $query->fetch();
    // On vérifie si le produit existe
    if(!$project){
        $_SESSION['erreur'] = "Cet id n'existe pas";
        header('Location: ../index-proj.php');
        die();
    }

    $sql = 'DELETE FROM `project` WHERE `idProject` = :idProject AND `user_id`=:user_id;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':idProject', $idProject, PDO::PARAM_INT);
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();
    $_SESSION['message'] = "Project deleted";
    header('Location: ../index-proj.php');

}
}else{
    $_SESSION['erreur'] = "Invalid URL";
    header('Location: ../index-proj.php');
}