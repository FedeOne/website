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

// get frequencies

$idStudy = $_GET['idStudy'];
$idPopulation = $_GET['idPopulation'];
$idProject = $_GET['idProject'];
$user_id= $_SESSION["user_id"];
$stmt = $db->prepare("SELECT * FROM frequencies WHERE `user_id` = :user_id  AND `idStudy` = :idStudy AND `idPopulation` = :idPopulation " );
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':idStudy', $idStudy, PDO::PARAM_INT);
$stmt->bindValue(':idPopulation', $idPopulation, PDO::PARAM_INT);


$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Frequencies</title>
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
<p>Your user ID is: <?= htmlspecialchars($user_id) ?></p>
  <main class="container">
    <div class="row">
      <section class="col-12">
        <h1>Frequencies</h1>
        <table class="table">
          <thead>

            <!-- <th>Population size</th> -->
            <th>Exposure name</th>
            <th>Frequency type</th>
            <th>Frequency measure</th>
            <th class="col-md-1">Unit</th>
            <th>Subgroup 1</th>

            <th>Actions</th>
          </thead>
          <tbody>
            <?php
            // on boucle sur la variable result
            foreach($result as $freq){
              ?>
              <tr>
                <!-- <td><?// =$freq['subpopSize'] ?></td> -->
                <td><?=$freq['expFrequencyName'] ?></td>
                <td><?=$freq['freqType'] ?></td> 
                <td><?=$freq['freqMeasure'] ?></td>
                <td><?=$freq['freqUnit'] ?></td>
                <td><?=$freq['subset1'] ?></td>
     
                <td>
                <a href="details-freq.php?idStudy=<?= $freq['idStudy'] ?>&idProject=<?= $freq['idProject'] ?> &idPopulation=<?= $freq['idPopulation'] ?>&idFreq=<?= $freq['idFreq'] ?>">Details</a>
                <a href="edit-freq.php?idStudy=<?= $freq['idStudy'] ?>&idProject=<?= $freq['idProject'] ?> &idPopulation=<?= $freq['idPopulation'] ?>&idFreq=<?= $freq['idFreq'] ?>">Edit</a>
                <a style="color: red;" onclick="return confirm('Are you sure?')" href="delete-freq.php?idStudy=<?= $freq['idStudy'] ?>&idProject=<?= $freq['idProject'] ?>&idPopulation=<?= $freq['idPopulation'] ?>&idFreq=<?= $freq['idFreq'] ?>">Delete</a>
              </td>
              </tr>
            <?php
            }
            ?>
          </tbody>
        </table>
        <a href="add-freq.php?idStudy=<?= $idStudy ?>&idPopulation=<?= $idPopulation ?>&idProject=<?= $idProject ?>" class="btn btn-primary">Add a Frequency</a>

      </section>
    </div>
  </main>
<?php 

?>

</body>
</html>