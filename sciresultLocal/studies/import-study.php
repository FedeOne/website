<?php
session_start();

try{
    // Connexion à la base
    $db = new PDO('mysql:host=localhost; dbname=sciresults;', 'root', '');
    $db->exec('SET NAMES "UTF8"');
} catch (PDOException $e){
    echo 'Erreur : '. $e->getMessage();
    die();
}

$idProject = $_GET['idProject'];
$user_id= $_SESSION["user_id"];
$PMID = isset($_POST['PMID']) ? $_POST['PMID'] : null;

 // get exisitng PMId and user_id to be sure that the typed PMId is not already in the table for the current user_id and current idProject

 $stmt0 = $db->prepare("SELECT PMID, user_id FROM study WHERE user_id = :user_id AND PMID = :PMID AND idProject =:idProject"  );
 $stmt0->bindValue(':PMID', $PMID, PDO::PARAM_INT);
 $stmt0->bindValue(':user_id', $user_id, PDO::PARAM_INT);
 $stmt0->bindValue(':idProject', $idProject, PDO::PARAM_INT);
 $stmt0->execute();

 $userPMID = $stmt0->fetchAll(PDO::FETCH_ASSOC);
 


if ($PMID !== null && empty($userPMID)) {
 // Fetch data from studiesfromcode table in phpmyadmin using $PMID 

  $stmt = $db->prepare("SELECT * FROM studiesfromcode WHERE `PMID` = :PMID" );
  $stmt->bindValue(':PMID', $PMID, PDO::PARAM_INT);
  $stmt->execute();

  $studies0 = $stmt->fetchAll(PDO::FETCH_ASSOC);


  if (!empty($studies0) && $idProject !== null) {
    // Insert data into the study table
      foreach ($studies0 as $row) {
        $stmt = $db->prepare("INSERT INTO study (idstudy0, idProject, user_id, PMID, title, author, ABSTRACT, yearPublication, journal, objective, studyPlan, uploadDate) 
            VALUES (:idstudy0, :idProject, :user_id, :PMID, :title, :author, :ABSTRACT, :yearPublication, :journal, :objective, :studyPlan, :uploadDate)");
        $stmt->bindValue(':idstudy0', $idProject, PDO::PARAM_INT);
        $stmt->bindValue(':idProject', $idProject, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':PMID', $row['PMID'], PDO::PARAM_INT); 
        $stmt->bindValue(':title', $row['title'], PDO::PARAM_STR); 
        $stmt->bindValue(':author', $row['author'], PDO::PARAM_STR); 
        $stmt->bindValue(':ABSTRACT', $row['ABSTRACT'], PDO::PARAM_STR); 
        $stmt->bindValue(':yearPublication', $row['yearPublication'], PDO::PARAM_INT); 
        $stmt->bindValue(':journal', $row['journal'], PDO::PARAM_STR); 
        $stmt->bindValue(':objective', $row['objective'], PDO::PARAM_STR); 
        $stmt->bindValue(':studyPlan', $row['studyPlan'], PDO::PARAM_STR); 
        $stmt->bindValue(':uploadDate', $row['uploadDate'], PDO::PARAM_STR); 
    
        $stmt->execute();
    }
  
  

    // get the idStudy from the study table cause it has been generated automatically and it will be necessary
    // to put it into the insert popumation stmt

    $stmt2 = $db->prepare("SELECT idStudy FROM study WHERE `PMID` = :PMID AND `user_id` = :user_id AND idProject =:idProject" );
    $stmt2->bindValue(':PMID', $PMID, PDO::PARAM_INT);
    $stmt2->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt2->bindValue(':idProject', $idProject, PDO::PARAM_INT);
    $stmt2->execute();
  
    $idStudy = $stmt2->fetchColumn(); // Fetch the value of idStudy directly

    ## getting data PopulationsfromCode

    $stmtPop = $db->prepare("SELECT * FROM populationsfromcode WHERE `PMID` = :PMID" );
    $stmtPop->bindValue(':PMID', $PMID, PDO::PARAM_INT);
    $stmtPop->execute();
  
    $populations0 = $stmtPop->fetchAll(PDO::FETCH_ASSOC);
    
    

    ## Insert PopulationsfromCode into Populations

    foreach ($populations0 as $row) {
      $stmtInsPop = $db->prepare("INSERT INTO populations (idPopulations0, idProject,idStudy, user_id, PMID, pNam , popSize, popDisease, ageUni, ageMean, ageSD, ageLow, ageUp, femalefreq, uploadDate) 
      VALUES (:idPopulations0, :idProject, :idStudy, :user_id, :PMID, :pNam, :popSize, :popDisease, :ageUni, :ageMean, :ageSD, :ageLow, :ageUp, :femaleFreq, :uploadDate)");
      $stmtInsPop->bindValue(':idProject', $idProject, PDO::PARAM_INT);
      $stmtInsPop->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
      $stmtInsPop->bindValue(':user_id', $user_id, PDO::PARAM_INT);
      $stmtInsPop->bindValue(':idPopulations0', $row['idPopulations0'], PDO::PARAM_INT);
      $stmtInsPop->bindValue(':PMID', $row['PMID'], PDO::PARAM_STR); 
      $stmtInsPop->bindValue(':pNam', $row['pNam'], PDO::PARAM_STR); 
      $stmtInsPop->bindValue(':popSize', $row['popSize'], PDO::PARAM_STR); 
      $stmtInsPop->bindValue(':popDisease', $row['popDisease'], PDO::PARAM_STR); 
      $stmtInsPop->bindValue(':ageUni', $row['ageUni'], PDO::PARAM_STR); 
      $stmtInsPop->bindValue(':ageMean', $row['ageMean'], PDO::PARAM_STR); 
      $stmtInsPop->bindValue(':ageSD', $row['ageSD'], PDO::PARAM_STR); 
      $stmtInsPop->bindValue(':ageLow', $row['ageLow'], PDO::PARAM_STR); 
      $stmtInsPop->bindValue(':ageUp', $row['ageUp'], PDO::PARAM_STR); 
      $stmtInsPop->bindValue(':femaleFreq', $row['femaleFreq'], PDO::PARAM_STR); 
      $stmtInsPop->bindValue(':uploadDate', $row['uploadDate'], PDO::PARAM_STR); 

      $stmtInsPop->execute();
  }

  // get population id to insert results
  
    $stmt3 = $db->prepare("SELECT idPopulation FROM populations WHERE `PMID` = :PMID AND `user_id` = :user_id AND idProject =:idProject AND idStudy =:idStudy"  );
    $stmt3->bindValue(':PMID', $PMID, PDO::PARAM_INT);
    $stmt3->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt3->bindValue(':idProject', $idProject, PDO::PARAM_INT);
    $stmt3->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
    $stmt3->execute();
  
    $idPopulation = $stmt3->fetchColumn(); // Fetch the value of idPopulation

      ## getting data resultsfromCode

      $stmtRes = $db->prepare("SELECT * FROM resultsfromcode WHERE `PMID` = :PMID" );
      $stmtRes->bindValue(':PMID', $PMID, PDO::PARAM_INT);
      $stmtRes->execute();
    
      $results0 = $stmtRes->fetchAll(PDO::FETCH_ASSOC);
      
      echo "id study";
      var_dump($idStudy);
      echo "rest";

      ## Insert resultsfromCode into results

      foreach ($results0 as $row) {
        $stmtInsRes = $db->prepare("INSERT INTO results (idResult0, idPopulation, idProject, idStudy, user_id, PMID, exp1, expType, expLow1, expHigh1, expUnit1, refExp1, outcome, result, icLow, icUpper, measureType, exp2, adjustment, uploadDate) 
        VALUES (:idResult0, :idPopulation, :idProject, :idStudy, :user_id, :PMID, :exp1, :expType, :expLow1, :expHigh1, :expUnit1, :refExp1, :outcome, :result, :icLow, :icUpper, :measureType, :exp2, :adjustment, :uploadDate)");
        
        $stmtInsRes->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT); 
        $stmtInsRes->bindValue(':idProject', $idProject, PDO::PARAM_INT);
        $stmtInsRes->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
        $stmtInsRes->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmtInsRes->bindValue(':idResult0', $row['idResult0'], PDO::PARAM_INT);
        $stmtInsRes->bindValue(':PMID', $row['PMID'], PDO::PARAM_STR);
        $stmtInsRes->bindValue(':exp1', $row['exp1'], PDO::PARAM_STR); 
        $stmtInsRes->bindValue(':expType', $row['expType'], PDO::PARAM_STR); 
        $stmtInsRes->bindValue(':expLow1', $row['expLow1'], PDO::PARAM_STR); 
        $stmtInsRes->bindValue(':expHigh1', $row['expHigh1'], PDO::PARAM_STR); 
        $stmtInsRes->bindValue(':expUnit1', $row['expUnit1'], PDO::PARAM_STR); 
        $stmtInsRes->bindValue(':refExp1', $row['refExp1'], PDO::PARAM_STR); 
        $stmtInsRes->bindValue(':outcome', $row['outcome'], PDO::PARAM_STR); 
        $stmtInsRes->bindValue(':result', $row['result'], PDO::PARAM_STR); 
        $stmtInsRes->bindValue(':icLow', $row['icLow'], PDO::PARAM_STR); 
        $stmtInsRes->bindValue(':icUpper', $row['icUpper'], PDO::PARAM_STR); 
        $stmtInsRes->bindValue(':measureType', $row['measureType'], PDO::PARAM_STR); 
        $stmtInsRes->bindValue(':exp2', $row['exp2'], PDO::PARAM_STR); 
        $stmtInsRes->bindValue(':adjustment', $row['adjustment'], PDO::PARAM_STR); 
        $stmtInsRes->bindValue(':uploadDate', $row['uploadDate'], PDO::PARAM_STR); 
    
        $stmtInsRes->execute();
    }
    
    ## getting data frequenciesfromCode

    $stmtFreq = $db->prepare("SELECT * FROM frequenciesfromcode WHERE `PMID` = :PMID" );
    $stmtFreq->bindValue(':PMID', $PMID, PDO::PARAM_INT);
    $stmtFreq->execute();
  
    $freq0 = $stmtFreq->fetchAll(PDO::FETCH_ASSOC);
    
    ## Insert frequenciesfromCode into frequencies
    
    foreach ($freq0 as $row) {
      $stmtInsFreq = $db->prepare("INSERT INTO frequencies (idFreq0, idProject, idStudy, idPopulation, user_id, PMID, subpopSize, subset1, subset2, subset3, expFrequencyName, freqType, freqMeasure, freqUnit, uploadDate) 
      VALUES (:idFreq0, :idProject, :idStudy, :idPopulation, :user_id, :PMID, :subpopSize, :subset1, :subset2, :subset3, :expFrequencyName, :freqType, :freqMeasure, :freqUnit, :uploadDate)");
      
      
      $stmtInsFreq->bindValue(':idProject', $idProject, PDO::PARAM_INT);
      $stmtInsFreq->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
      $stmtInsFreq->bindValue(':idPopulation',  $idPopulation, PDO::PARAM_INT);
      $stmtInsFreq->bindValue(':user_id', $user_id, PDO::PARAM_INT);
      $stmtInsFreq->bindValue(':idFreq0', $row['idFreq0'], PDO::PARAM_INT); 
      $stmtInsFreq->bindValue(':PMID', $row['PMID'], PDO::PARAM_INT); 
      $stmtInsFreq->bindValue(':subpopSize', $row['subpopSize'], PDO::PARAM_INT); 
      $stmtInsFreq->bindValue(':subset1', $row['subset1'], PDO::PARAM_STR); 
      $stmtInsFreq->bindValue(':subset2', $row['subset2'], PDO::PARAM_STR); 
      $stmtInsFreq->bindValue(':subset3', $row['subset3'], PDO::PARAM_STR); 
      $stmtInsFreq->bindValue(':expFrequencyName', $row['expFrequencyName'], PDO::PARAM_STR); 
      $stmtInsFreq->bindValue(':freqType', $row['freqType'], PDO::PARAM_STR); 
      $stmtInsFreq->bindValue(':freqMeasure', $row['freqMeasure'], PDO::PARAM_INT); 
      $stmtInsFreq->bindValue(':freqUnit', $row['freqUnit'], PDO::PARAM_STR);
      $stmtInsFreq->bindValue(':uploadDate', $row['uploadDate'], PDO::PARAM_STR); 
  
      $stmtInsFreq->execute();
  }
  


    
  echo '<p style="color: green; font-weight: bold;">Data inserted into study table successfully.</p>';

} else {
    echo '<p style="color: red; font-weight: bold;">No data found for PMID: " . htmlspecialchars($PMID)</p>';
}

} 
  else {
    echo '<p style="color: red; font-weight: bold;">Error: PMID is either null or already exists for this user."</p>';
  } 


?>

<!-- Your HTML content here -->


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studies</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet"   href="../css/style.css">
  </head>
  <body>
    <div class="container timeline">
          <a href="index-study-test.php?idProject=<?= $idProject ?>">
              <button class="btn btt-timeline">Back to studies</button>
          </a>
      </div>
    <br>  
    <form method="post" action="import-study.php?idProject=<?= htmlspecialchars($idProject) ?>">
    <input type="hidden" name="idProject" value="<?= htmlspecialchars($idProject) ?>">
    <label for="PMID">Enter PMID:</label>
    <input type="text" name="PMID" id="PMID" required>
    <button type="submit">Import</button>
    </form>


  </body>
</html>