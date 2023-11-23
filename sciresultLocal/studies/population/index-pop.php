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
$idProject = $_GET['idProject'];
$user_id= $_SESSION["user_id"];
$stmt = $db->prepare("SELECT * FROM populations WHERE `user_id` = :user_id  AND `idStudy` = :idStudy " );
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);


$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get info about which study

$stmt2 = $db->prepare("SELECT * FROM study WHERE `user_id` = :user_id  AND `idStudy` = :idStudy " );
$stmt2->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt2->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);

$stmt2->execute();

$study = $stmt2->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Populations</title>
  <link rel="stylesheet" href="../../css/bootstrap.min.css">
  <link rel="stylesheet"   href="../../css/style.css">
</head>
<body>
    <div class="container timeline">
        <a href="../../index-proj.php">
            <button class="btn btt-timeline">Back to projects</button>
        </a>
        <a href="../index-study-test.php?idProject=<?= $idProject ?>">
            <button class="btn btt-timeline">Back to studies</button>
        </a>
    </div>
    <br>
<p class="container"><strong>Your user ID is: </strong> <?= htmlspecialchars($user_id) ?></p>
<p class="container"><strong>Study title:</strong> <?= htmlspecialchars($study[0]['title']) ?></p>
<p class="container"><strong>PMID:</strong> <?= htmlspecialchars($study[0]['PMID']) ?></p>
  <main class="container">
    <div class="row">
      <section class="col-12">
        <h1>Populations</h1>
        <p class="container"> Each study can have more than one population, for example a global population and a subset on which are performed more specific analysis</p>
        <table class="table">
          <thead>
            <th>Results</th>
            <th>Frequencies</th>
           <!--- <th>idPopulation</th> --->
            <th>Population name</th>
            <th>Population size</th>
            <th>Population country</th>
            <th>Pop. main pathology</th>
            <!---
            <th>Pop. secondary characteristic</th>
            <th>Inclusion criteria</th>
            <th>Age unit</th>
            <th>Age mean</th>
            <th>Age Standard dev.</th>
            <th>% of females</th> --->
            <th>Actions</th>
          </thead>
          <tbody>
            <?php
            // on boucle sur la variable result
            foreach($result as $population){
              ?>
              <tr>
                <td><a href="results/index-results.php?idPopulation=<?= $population['idPopulation'] ?>&idStudy=<?= $population['idStudy'] ?>&idProject=<?= $population['idProject'] ?>"> Add results  </a></td>
                <td><a href="frequency/index-freq.php?idPopulation=<?= $population['idPopulation'] ?>&idStudy=<?= $population['idStudy'] ?>&idProject=<?= $population['idProject'] ?>"> Add frequencies  </a></td>
               <!--- <td><?=$population['idPopulation'] ?></td> --->
                <td><?=$population['pNam'] ?></td>
                <td><?=$population['popSize'] ?></td>
                <td><?=$population['popCountry'] ?></td>
                <td><?=$population['popDisease'] ?></td>
                <!--- <td><?=$population['pSecChar'] ?></td>
                <td><?=$population['inclCrit'] ?></td>
                <td><?=$population['ageUni'] ?></td>
                <td><?=$population['ageMean'] ?></td>
                <td><?=$population['ageSD'] ?></td>
                <td><?=$population['femaleFreq'] ?></td> --->
                <td>
                <a href="edit-pop.php?idStudy=<?= $population['idStudy'] ?>&idPopulation=<?= $population['idPopulation'] ?>&idProject=<?= $population['idProject'] ?>">Edit</a>
                <a href="details-pop.php?idStudy=<?= $population['idStudy'] ?>&idPopulation=<?= $population['idPopulation'] ?>&idProject=<?= $population['idProject'] ?>">Details</a>
                <a style="color: red;" href="delete-pop.php?idStudy=<?= $population['idStudy'] ?>&idPopulation=<?= $population['idPopulation'] ?>&idProject=<?= $population['idProject'] ?>">Delete</a>
                
              </td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
        <a href="add-pop2.php?idStudy=<?= $idStudy ?>&idProject=<?= $idProject ?>" class="btn btn-primary">Add a population</a>

      </section>
    </div>
  </main>
<?php 

?>

</body>
</html>