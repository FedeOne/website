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

// get user infos

$idStudy = $_GET['idStudy'];
$idProject = $_GET['idProject'];
$user_id= $_SESSION["user_id"];
$stmt = $db->prepare("SELECT * FROM user WHERE `id` = :user_id" );
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);



$stmt->execute();

$userInfos = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
  <title>Comments about: </title>
  <p class="container">Title:  <?= htmlspecialchars($study[0]["title"]) ?></p>
  <link rel="stylesheet" href="../../css/bootstrap.min.css">
  <link rel="stylesheet"   href="../../css/style.css">
</head>
<body>
    <div class="container timeline">
        <a href="../index-proj.php">
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
        <h1>Comments: </h1>
        <p class="container"> Some other infos</p>
        <table class="table">
          <thead>
            <th>user ID</th>
            <th>user name</th>
           <!--- <th>idPopulation</th> --->
            <th>Comment</th>
            <th>commentDate</th>
            
            <th>Actions</th>
          </thead>
          <tbody>
            <?php
            // on boucle sur la variable result
            foreach($result as $population){
              ?>
              <tr>
                <td><a href="results/index-results.php?idPopulation=<?= $population['idPopulation'] ?>&idStudy=<?= $population['idStudy'] ?>&idProject=<?= $population['idProject'] ?>"> Add a comment  </a></td>
                
               <!--- <td><?=$population['idPopulation'] ?></td> --->
                <td><?=$userInfos['id'] ?></td>
                <td><?=$userInfos['name'] ?></td>
                <!--- <td><?=$comments['comment'] ?></td>
                <td><?=$comments['commentDate'] ?></td>
                 --->
                
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