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

// get studies

$idStudy = $_GET['idStudy'];
$idPopulation = $_GET['idPopulation'];
$idProject = $_GET['idProject'];
$user_id= $_SESSION["user_id"];
$stmt = $db->prepare("SELECT * FROM results WHERE `user_id` = :user_id  AND `idStudy` = :idStudy AND `idPopulation` = :idPopulation " );
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
$stmt->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);


$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get info about which study

$stmt2 = $db->prepare("SELECT * FROM study WHERE `user_id` = :user_id  AND `idStudy` = :idStudy " );
$stmt2->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt2->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);

$stmt2->execute();

$study = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// get info about which population

$stmt3 = $db->prepare("SELECT * FROM populations WHERE `user_id` = :user_id  AND `idPopulation` = :idPopulation " );
$stmt3->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt3->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);

$stmt3->execute();

$popul = $stmt3->fetchAll(PDO::FETCH_ASSOC);


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Results</title>
  <link rel="stylesheet" href="../../../css/bootstrap.min.css">
  <link rel="stylesheet"   href="../../../css/style.css">
</head>
<body>
<div class="container timeline">
        <a href="../../../index-proj.php">
            <button class="btn btt-timeline">Back to projects</button>
        </a>
        <a href="../../index-study-test.php?idProject=<?= $idProject ?>">
            <button class="btn btt-timeline">Back to studies</button>
        </a>
        <a href="../index-pop.php?idProject=<?= $idProject ?>&idStudy=<?= $idStudy ?>">
            <button class="btn btt-timeline">Back to populations</button>
        </a>
    </div>
    <br>
<p class="container"><strong>Your user ID is: </strong> <?= htmlspecialchars($user_id) ?></p>
<p class="container"><strong>Study title:</strong> <?= htmlspecialchars($study[0]['title']) ?></p>
<p class="container"><strong>Population name:</strong> <?= htmlspecialchars($popul[0]['pNam']) ?></p>
  <main class="container">
    <div class="row">
      <section class="col-12">
        <h1>Results</h1>
        <table class="table">
          <thead>
            <!---<th>idResult</th>
            <th>idPopulation</th> --->
            <th>Exposure</th>
            <th class="col-md-1">Exposure (lower treshold)</th>
            <th class="col-md-1">Exposure (Upper treshold))</th>
            <th>Exposure unit</th>
            
            <th>Reference class</th>
            <th>Outcome</th>
            <th>Result</th>
            <th class="col-md-1">Confint Low</th>
            <th class="col-md-1">Confint Upper</th>

            <th>Measure type</th>
           <!--- <th>Subgroup</th>
            <th>Adjustment variables</th> --->

            <th>Actions</th>
          </thead>
          <tbody>
            <?php
            // on boucle sur la variable result
            foreach($result as $results){
              ?>
              <tr>
                <!---<td><?=$results['idResult'] ?></td>
                <td><?=$results['idPopulation'] ?></td> --->
                <td><?=$results['exp1'] ?></td>
                <td><?=$results['expLow1'] ?></td>
                <td><?=$results['expHigh1'] ?></td>
                <td><?=$results['expUnit1'] ?></td>

                <td><?=$results['refExp1'] ?></td>
                <td><?=$results['outcome'] ?></td>
                <td><?=$results['result'] ?></td>
                <td><?=$results['icLow'] ?></td>
                <td><?=$results['icUpper'] ?></td>

                <td><?=$results['measureType'] ?></td>
                <!---<td><?=$results['exp2'] ?></td>
                <td><?=$results['adjustment'] ?></td> --->

                <td>
                <a href="details-res.php?idStudy=<?= $results['idStudy'] ?>&idProject=<?= $results['idProject'] ?> &idPopulation=<?= $results['idPopulation'] ?>&idResult=<?= $results['idResult'] ?>">Details</a>
                <a href="edit-res.php?idStudy=<?= $results['idStudy'] ?>&idProject=<?= $results['idProject'] ?> &idPopulation=<?= $results['idPopulation'] ?>&idResult=<?= $results['idResult'] ?>">Edit</a>
                <a style="color: red;" onclick="return confirm('Are you sure?')" href="delete-res.php?idStudy=<?= $results['idStudy'] ?>&idProject=<?= $results['idProject'] ?>&idPopulation=<?= $results['idPopulation'] ?>&idResult=<?= $results['idResult'] ?>">Delete</a>
              </td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
        <a href="add-res.php?idStudy=<?= $idStudy ?>&idPopulation=<?= $idPopulation ?>&idProject=<?= $idProject ?>" class="btn btn-primary">Add a result</a>

      </section>
    </div>
  </main>
<?php 

?>

</body>
</html>