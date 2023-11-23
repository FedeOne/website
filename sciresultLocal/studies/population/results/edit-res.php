<?php
// On démarre une session
session_start();

// ce premier bloc sert à recuperer les données mises à jour dans le formulaire plus bas
if($_POST){
  if(isset($_GET['idStudy']) && !empty($_GET['idStudy'])
  && isset($_GET['idPopulation']) && !empty($_GET['idPopulation'])
  && isset($_GET['idProject']) && !empty($_GET['idProject'])
  && isset($_GET['idResult']) && !empty($_GET['idResult'])){
        // On inclut la connexion à la base
        require_once('../../../connect_sql2.php');

        $editDate = date("Y-m-d"); 
        // On nettoie les données envoyées
        $user_id= strip_tags($_SESSION["user_id"]);
        $idStudy = strip_tags($_POST['idStudy']);
        $idProject = strip_tags($_POST['idProject']);
        $idPopulation = strip_tags($_POST['idPopulation']);
        $idResult = strip_tags($_POST['idResult']);
        
        $exp1 = strip_tags($_POST['exp1']);
        $expType = strip_tags($_POST['expType']);
        $expLow1 = strip_tags($_POST['expLow1']);
        $expHigh1 = strip_tags($_POST['expHigh1']);
        $expUnit1 = strip_tags($_POST['expUnit1']);
        
        $refExp1 = strip_tags($_POST['refExp1']);
        $outcome = strip_tags($_POST['outcome']);
        $result = strip_tags($_POST['result']);
        $icLow = strip_tags($_POST['icLow']);
        $icUpper = strip_tags($_POST['icUpper']);

        $measureType = strip_tags($_POST['measureType']);
        $exp2 = strip_tags($_POST['exp2']);
        $adjustment = strip_tags($_POST['adjustment']);
      
        $sql = 'UPDATE `results` SET  `editDate`=:editDate, `exp1`=:exp1, `expType`=:expType, `expLow1`=:expLow1, `expHigh1`=:expHigh1, `expUnit1`=:expUnit1, `refExp1`=:refExp1, `outcome`=:outcome, `result`=:result,
         `icLow`=:icLow, `icUpper`=:icUpper, `measureType`=:measureType, `exp2`=:exp2 , `adjustment`=:adjustment WHERE 
        `idStudy`=:idStudy AND `idPopulation`=:idPopulation AND `idResult`=:idResult AND `user_id`=:user_id ;';

        $query = $db->prepare($sql);

        $query->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $query->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);
        //$query->bindValue(':idProject', $idProject, PDO::PARAM_INT);
        $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
        $query->bindValue(':idResult', $idResult, PDO::PARAM_INT);

        $query->bindValue(':editDate', $editDate, PDO::PARAM_STR);
        $query->bindValue(':exp1', $exp1, PDO::PARAM_STR);
        $query->bindValue(':expType', $expType, PDO::PARAM_STR);
        $query->bindValue(':expLow1', $expLow1, PDO::PARAM_INT);
        $query->bindValue(':expHigh1', $expHigh1, PDO::PARAM_STR);
        $query->bindValue(':expUnit1', $expUnit1, PDO::PARAM_STR);

        $query->bindValue(':refExp1', $refExp1, PDO::PARAM_STR);
        $query->bindValue(':outcome', $outcome, PDO::PARAM_STR);
        $query->bindValue(':result', $result, PDO::PARAM_STR);
        $query->bindValue(':icLow', $icLow, PDO::PARAM_STR);
        $query->bindValue(':icUpper', $icUpper, PDO::PARAM_STR);

        $query->bindValue(':measureType', $measureType, PDO::PARAM_STR);
        $query->bindValue(':exp2', $exp2, PDO::PARAM_STR);
        $query->bindValue(':adjustment', $adjustment, PDO::PARAM_STR);

        $query->execute();

        $_SESSION['message'] = "Result details have been modified";
        require_once('close.php');

        $quer = array(
          'idProject' => $idProject,
          'idStudy' => $idStudy, 
          'idPopulation' => $idPopulation,
          'idResult' => $idResult,
          );
      
      $quer = http_build_query($quer);
      header("Location: index-results.php?$quer");
    }else{
        $_SESSION['erreur'] = "Form is incomplete";
    }
}

// ce deuxieme bloc sert a donner des valeurs pour pre-remplir le formulaire en faisant une query
// Est-ce que l'id existe et n'est pas vide dans l'URL
if(isset($_GET['idStudy']) && !empty($_GET['idStudy'])
  && isset($_GET['idPopulation']) && !empty($_GET['idPopulation'])
  // && isset($_GET['idProject']) && !empty($_GET['idProject'])
  && isset($_GET['idResult']) && !empty($_GET['idResult'])){
    require_once('../../../connect_sql2.php');

    // On nettoie l'id envoyé
    $idStudy = strip_tags($_GET['idStudy']);
   // $idProject = strip_tags($_GET['idProject']);
    $idPopulation = strip_tags($_GET['idPopulation']);
    $idResult = strip_tags($_GET['idResult']);

    $sql = 'SELECT * FROM `results` WHERE `idResult` = :idResult;';

    // On prépare la requête
    $query = $db->prepare($sql);

    // On "accroche" les paramètre (id)
    $query->bindValue(':idResult', $idResult, PDO::PARAM_INT);

    // On exécute la requête
    $query->execute();

   
    // On récupère le produit
    $results = $query->fetch();

  }
/*
    // On vérifie si le produit existe
    if(!$population){
        $_SESSION['erreur'] = "Cet id n'existe pas";
        $quer = array(
          'idProject' => $idProject,
          'idStudy' => $idStudy, 
          'idPopulation' => $idPopulation,
          );
      
      $quer = http_build_query($quer);
      header("Location: index-pop.php?$quer");
    
}else{
    $_SESSION['erreur'] = "URL invalide";
    $quer = array(
      'idProject' => $idProject,
      'idStudy' => $idStudy, 
      'idPopulation' => $idPopulation,
      );
  
  $quer = http_build_query($quer);
  header("Location: index-pop.php?$quer");
}
*/
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update result</title>

    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
    <link rel="stylesheet"   href="../../../css/style.css">
    
    <script type = "text/javascript" src="jsRes/functionRes.js"> </script>
</head>
<body>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <?php
                    if(!empty($_SESSION['erreur'])){
                        echo '<div class="alert alert-danger" role="alert">
                                '. $_SESSION['erreur'].'
                            </div>';
                        $_SESSION['erreur'] = "";
                    }
                ?>
                <h1>Update result</h1>
                <form method="post" >
                    <div class="row">
                        <div class="form-group col"><label for="exp1"></label> </div>
                        <div class="form-group col"><label for="expType1"></label> </div>
                        <div class="form-group col"><label for="expLow1" style="color: green;" id="text1">Type same number</label> </div>
                        <div class="form-group col"><label for="exp1High" style="color: green;" id="text2">in both fields</label> </div>
                        <div class="form-group col"><label for="expUit1"></label> </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="exp1">Exposure </label>
                            <input required type="text" id="exp1" name="exp1" class="form-control"  value="<?= $results['exp1']?>">
                        </div>
                         <div class="form-group col">
                            <label for="expType">Exposure type</label>
                            <select id="expType" name="expType" class="form-select" onclick="showHideExp()">
                            <option value="no numeric treshold" selected>no numeric treshold</option>
                            <option value="lower than">lower than</option>
                            <option value="greater than">greater than</option>
                            <option value="between">between</option>
                            <option value="equal to">equal to</option>
                            <option selected value="<?= $results['expType']?>"><?= $results['expType']?></option>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="expLow1">Lower treshold (Exposure)</label>
                            <input type="number" step="0.01" id="expLow1" name="expLow1" class="form-control" value="<?= $results['expLow1']?>">
                        </div>
                        <div class="form-group col">
                            <label for="expHigh1">Upper treshold (Exposure)</label>
                            <input type="number" step="0.01" id="expHigh1" name="expHigh1" class="form-control" value="<?= $results['expHigh1']?>">
                        </div>
                        <div class="form-group col">
                            <label for="expUnit1">Exposure unit</label>
                            <input type="text" id="expUnit1" name="expUnit1" class="form-control" value="<?= $results['expUnit1']?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="refExp1">Reference class</label>
                        <input type="text" id="refExp1" name="refExp1" class="form-control" value="<?= $results['refExp1']?>">
                    </div>
                    <div class="form-group">
                        <label for="outcome">Outcome</label>
                        <input type="text" id="outcome" name="outcome" class="form-control" value="<?= $results['outcome']?>">
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="result">Result</label>
                            <input required type="number" step ="0.01" id="result" name="result" class="form-control" value="<?= $results['result']?>">
                        </div>
                        <div class="form-group col">
                            <label for="icLow">Confint lower</label>
                            <input type="number" step ="0.01" id="icLow" name="icLow" class="form-control" value="<?= $results['icLow']?>">
                        </div>
                        <div class="form-group col">
                            <label for="icUpper">Confint Upper</label>
                            <input type="number" step ="0.01" id="icUpper" name="icUpper" class="form-control" value="<?= $results['icUpper']?>" >
                        </div>
                        <div class="form-group col">
                            <label for="measureType">Measure type</label>
                            <select id="measureType" name="measureType" class="form-select"> 
                            <option value="choose">choose measure type</option>
                            <option value="hazard ratio">hazard ratio</option>
                            <option value="odd ratio">odd ratio</option>
                            <option value="risk ratio">risk ratio</option>
                            <option value="mean">mean</option>
                            <option selected value="<?= $results['measureType']?>"><?= $results['measureType']?></option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exp2">Subgroup</label>
                        <input type="text" id="exp2" name="exp2" class="form-control" value="<?= $results['exp2']?>">
                    </div>

                    <div class="form-group">
                        <label for="adjustment">Adjustment variables</label>
                        <input type="text" id="adjustment" name="adjustment" class="form-control" value="<?= $results['adjustment']?>">
                    </div>


                    <input type="hidden" value="<?= $results['idStudy']?>" name="idStudy">
                    <input type="hidden" value="<?= $results['idProject']?>" name="idProject">
                    <input type="hidden" value="<?= $results['idPopulation']?>" name="idPopulation">
                    <input type="hidden" value="<?= $results['idResult']?>" name="idResult">
                    <button class="btn btn-primary">Send</button>
                </form>
            </section>
        </div>
    </main>
   
</body>
</html> 

