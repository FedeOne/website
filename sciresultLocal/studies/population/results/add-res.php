<?php 
session_start();

// on prend userID de la session en theorie pas d'inquietude
$user_id = $_SESSION['user_id'];
$idPopulation = strip_tags($_GET['idPopulation']);
$idStudy = strip_tags($_GET['idStudy']);
$idProject = strip_tags($_GET['idProject']);

/*
// on prends idProject de l'URL et on nettoie avec strip tags
if(isset($_GET['idProject']) && !empty($_GET['idProject'])){
  require_once('../../connect_sql2.php');

  // On nettoie l'id envoyé
  $idProject = strip_tags($_GET['idProject']);
}*/
?>

<?php

if($_POST){
    if(isset($_POST['exp1']) && !empty($_POST['exp1'])
    && isset($_POST['result']) && !empty($_POST['result'])
    ){
        // On inclut la connexion à la base
        require_once('../../../connect_sql2.php');

        // On nettoie les données envoyées par le formulaire de ce script
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
     


             $sql = 'INSERT INTO `results` (`idPopulation`, `user_id`, `idProject`,`idStudy`,`exp1`,`expType`,`expLow1`,`expHigh1`,`expUnit1`,`refExp1`,
             `outcome`,`result`,`icLow`,`icUpper`, `measureType`,`exp2`,`adjustment`) VALUES 
         (:idPopulation, :user_id, :idProject, :idStudy, :exp1, :expType, :expLow1,  :expHigh1, :expUnit1 , :refExp1, :outcome, :result,  :icLow, :icUpper,
         :measureType, :exp2, :adjustment );';

        $query = $db->prepare($sql);

        $query->bindValue(':user_id', $user_id, PDO::PARAM_STR);
        $query->bindValue(':idProject', $idProject, PDO::PARAM_INT);
        $query->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
        $query->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);

        $query->bindValue(':exp1', $exp1, PDO::PARAM_STR);
        $query->bindValue(':expType', $expType, PDO::PARAM_STR);
        $query->bindValue(':expLow1', $expLow1, PDO::PARAM_STR);
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
    
        $_SESSION['message'] = "A result has been added";
        require_once('close.php');

        // array to explain results

        
        // meilleur methode pour passer arguments par l URL
        $quer = array(
          'idStudy' => $idStudy, 
          'idProject' => $idProject,
          'idPopulation' => $idPopulation,
          );
      
      $quer = http_build_query($quer);
       header("Location: index-results.php?$quer");
    }else{
        $_SESSION['erreur'] = "Fill at least Exposure, result";
    }
      // var_dump($query);
       // var_dump($query);
       // var_dump($popName);
        //header("Location: index-pop.php?idStudy=".$idStudy);
   
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add result</title>

    <link rel="stylesheet" href="../../../css/bootstrap.min.css">
    <link rel="stylesheet"   href="../../../css/style.css">
    
      <script type = "text/javascript" src="jsRes/functionRes.js"> </script>
</head>
<body>
    <p>This is population number: <?= htmlspecialchars($idPopulation) ?></p>
    
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
                <h1>Add a result</h1>
                <form method="post" onsubmit="return checkConfint();">
                      <div class="row">
                        <div class="form-group col"><label for="exp1"></label> </div>
                        <div class="form-group col"><label for="expType1"></label> </div>
                        <div class="form-group col"><label for="expLow1" style="color: green;" id="text1">Type same number</label> </div>
                        <div class="form-group col"><label for="exp1High" style="color: green;" id="text2">in both fields</label> </div>
                        <div class="form-group col"><label for="expUit1"></label> </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="exp1">Exposure</label>
                            <input required type="text" id="exp1" name="exp1" class="form-control" placeholder="ex: Wine">
                        </div>
                         <div class="form-group col">
                            <label for="expType">Exposure type</label>
                            <select id="expType" name="expType" class="form-select" onclick="showHideExp()">
                            <option value="no numeric treshold" selected>no numeric treshold</option>
                            <option value="lower than">lower than</option>
                            <option value="greater than">greater than</option>
                            <option value="between">between</option>
                            <option value="equal to">equal to</option>
                            </select>
                        </div>
                        <div class="form-group col">
                            <label for="expLow1">Lower treshold (Exposure)</label>
                            <input type="number" step="0.01" id="expLow1" name="expLow1" class="form-control" placeholder="ex: 2" >
                        </div>
                        <div class="form-group col">
                            <label for="expHigh1">Upper treshold (Exposure)</label>
                            <input type="number" step="0.01" id="expHigh1" name="expHigh1" class="form-control" placeholder="ex: 5" >
                        </div>
                        <div class="form-group col">
                            <label for="expUnit1">Exposure unit</label>
                            <input type="text" id="expUnit1" name="expUnit1" class="form-control" placeholder="ex: Glasses/day">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="refExp1">Reference class</label>
                            <input type="text" id="refExp1" name="refExp1" class="form-control" placeholder="ex: 0 glasses/day">
                        </div>
                        <div class="form-group col">
                            <label for="outcome">Outcome</label>
                            <input type="text" id="outcome" name="outcome" class="form-control" placeholder="ex: Cognitive functions">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col">
                            <label for="result">Result</label>
                            <input required type="number" step ="0.01" id="result" name="result" class="form-control" placeholder="ex:2" onchange="document.getElementById('icLow').max=this.value;" >
                        </div>
                        <!--- onclick="document.getElementById('icUpper').min=this.value;" --->
                        <div class="form-group col">
                            <label for="icLow">Confint lower</label>
                            <input type="number" step ="0.01" id="icLow" name="icLow" class="form-control" placeholder="ex: 1.5" required max="document.getElementById('result').value">
                        </div>

                        <div class="form-group col">
                            <label for="icUpper">Confint Upper</label>
                            <input type="number" step ="0.01" id="icUpper" name="icUpper" class="form-control"  placeholder="ex: 2.5" required onchange="handleOnchange()">
                        </div>
                    
                        <div class="form-group col">
                            <label for="measureType">Measure type</label>
                            <select id="measureType" name="measureType" class="form-select"> 
                            <option value="choose" selected>choose measure type</option>
                            <option value="hazard ratio">hazard ratio</option>
                            <option value="odd ratio">odd ratio</option>
                            <option value="risk ratio">risk ratio</option>
                            <option value="mean">mean</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="exp2">Subgroup</label>
                        <input type="text" id="exp2" name="exp2" class="form-control" placeholder="students">
                    </div>

                    <div class="form-group">
                        <label for="adjustment">Adjustment variables</label>
                        <input type="text" id="adjustment" name="adjustment" class="form-control" placeholder="Age, sex">
                    </div>
                  

                    <button class="btn btn-primary">Add</button>
                </form>
            </section>
        </div>
        <br>
        <br>
        <br>
        <h5>Here is an example on how to code the exposure</h5>
        <img height="200 px;" src="explain_expo.png" alt="Explanation coding exposure">
    </main>
</body>


</html>