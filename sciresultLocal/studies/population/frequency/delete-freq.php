<?php
// On démarre une session
session_start();

// Est-ce que l'id existe et n'est pas vide dans l'URL
if(isset($_GET['idStudy']) && !empty($_GET['idStudy'])
&& isset($_GET['idPopulation']) && !empty($_GET['idPopulation'])
&& isset($_GET['idProject']) && !empty($_GET['idProject'])
&& isset($_GET['idFreq']) && !empty($_GET['idFreq'])){
    require_once('../../../connect_sql2.php');

    // On nettoie l'id envoyé
    $user_id= strip_tags($_SESSION["user_id"]);
    $idProject = strip_tags($_GET['idProject']);
    $idPopulation = strip_tags($_GET['idPopulation']);
    $idStudy = strip_tags($_GET['idStudy']);
    $idFreq = strip_tags($_GET['idFreq']);


    
    $sql = 'SELECT * FROM `frequencies` WHERE `idStudy` = :idStudy AND `idPopulation` = :idPopulation AND `idFreq` = :idFreq ;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
    $query->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);
    $query->bindValue(':idFreq', $idFreq, PDO::PARAM_INT);
    // On exécute la requête
    $query->execute();

    // On récupère le produit
    $result = $query->fetch();

    // On vérifie si le produit existe
    if(!$result){
        $_SESSION['erreur'] = "Ce resultat n'existe pas";

        $quer = array(
          'idProject' => $idProject,
          'idStudy' => $idStudy, 
          'idPopulation' => $idPopulation,
   
          );
      
      $quer = http_build_query($quer);
      header("Location: index-freq.php?$quer");
        
        die();
    }

    $sql = 'DELETE FROM `frequencies` WHERE `idStudy` = :idStudy AND `idPopulation` = :idPopulation AND `idFreq` = :idFreq AND `user_id`=:user_id ;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
    $query->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);
    $query->bindValue(':idFreq', $idFreq, PDO::PARAM_INT);    
    $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();
    $_SESSION['message'] = "Frequency deleted";
    $quer = array(
      'idProject' => $idProject,
      'idStudy' => $idStudy, 
      'idPopulation' => $idPopulation,
      );
  
  $quer = http_build_query($quer);
  header("Location: index-freq.php?$quer");


}else{
    $_SESSION['erreur'] = "Invalid URL";
    $quer = array(
      'idProject' => $idProject,
      'idStudy' => $idStudy, 
      'idPopulation' => $idPopulation,
      );
  
  $quer = http_build_query($quer);
  header("Location: index-freq.php?$quer");
}

