<?php
session_start();
try {
    // Connexion à la base
    $db = new PDO('mysql:host=localhost; dbname=sciresults;', 'root', '');
    $db->exec('SET NAMES "UTF8"');
} catch (PDOException $e) {
    echo 'Erreur : ' . $e->getMessage();
    die();
}

// get studies
$idProject = $_GET['idProject'];
$user_id = $_SESSION["user_id"];
$stmt = $db->prepare("SELECT * FROM study WHERE `user_id` = :user_id  AND `idProject` = :idProject ");
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':idProject', $idProject, PDO::PARAM_INT);

$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Studies</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <!-- Remove the Spectrum CSS and JS links -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container timeline">
        <a href="../index-proj.php">
            <button class="btn btt-timeline">Back to projects</button>
        </a>
    </div>
    <p>Your user ID is: <?= htmlspecialchars($user_id) ?></p>
    <main class="container">
        <div class="row">
            <section class="col-12">
                <h1>List of studies</h1>

                <table class="table">
                    <thead>
                        <th>Action</th>
                        <th>PMID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Year of publication</th>
                        <th>Actions</th>
                        <th>Color Picker</th>
                    </thead>
                    <tbody>
                        <?php
                        $rowIndex = 0;
                        foreach ($result as $article) {
                            ?>
                            <tr id="row<?=$rowIndex?>">
                                <td><a href="population/index-pop.php?idStudy=<?= $article['idStudy'] ?>&idProject=<?= $article['idProject'] ?>"> Add populations  </a></td>
                                <td><?=$article['PMID'] ?></td>
                                <td><?=$article['title'] ?></td>
                                <td><?=$article['author'] ?></td>
                                <td><?=$article['yearPublication'] ?></td>
                                <td>
                                    <a href="edit-study.php?idStudy=<?= $article['idStudy'] ?>&idProject=<?= $article['idProject'] ?>">Edit</a>
                                    <a href="details-study.php?idStudy=<?= $article['idStudy'] ?>&idProject=<?= $article['idProject'] ?>">Details</a>
                                    <a style="color: red;" href="delete-study.php?idStudy=<?= $article['idStudy'] ?>&idProject=<?= $article['idProject'] ?>">Delete</a>
                                </td>
                                
                               <!-- ... (Your existing PHP code) ... -->
                                <td>
                                    <form class="color-form" action="update-color.php" method="POST">
                                        <input type="hidden" name="idStudy" value="<?= $article['idStudy'] ?>">
                                        <select name="color">
                                            <option value="#FF0000"<?= ($article['color'] == "#FF0000") ? ' selected' : '' ?>>Red</option>
                                            <option value="#00FF00"<?= ($article['color'] == "#00FF00") ? ' selected' : '' ?>>Green</option>
                                            <option value="#0000FF"<?= ($article['color'] == "#0000FF") ? ' selected' : '' ?>>Blue</option>
                                            <!-- Add more color options as needed -->
                                        </select>
                                        <button type="submit" class="btn btn-primary">Color Row</button>
                                    </form>
                                </td>
<!-- ... (Your existing PHP code) ... -->



                            </tr>
                            <?php
                            $rowIndex++;
                        }
                        ?>
                    </tbody>
                </table>
                <a href="add-study.php?idProject=<?= $idProject ?>" class="btn btn-primary">Add a study</a>
                <a href="import-study.php?idProject=<?= $idProject ?>" class="btn btn-primary">Import a study with PMID</a>
            </section>
        </div>
    </main>

    <script>
    $(document).ready(function() {
        $(".color-form").on("submit", function(e) {
            e.preventDefault(); // Prevent the default form submission

            var form = $(this);
            var idStudy = form.find("input[name='idStudy']").val();
            var color = form.find("select[name='color']").val();

            // Update the color in the database
            $.ajax({
                type: 'POST',
                url: 'update-color.php',
                data: { 
                    idStudy: idStudy,
                    color: color
                },
                success: function(response) {
                    console.log(response);
                    // Optionally, you can perform additional actions after a successful update
                    // For example, update the color in the row without reloading the page
                    var rowIndex = parseInt(form.closest("tr").attr("id").match(/\d+/)[0]);
                    $("#row" + rowIndex).css("background-color", color);
                },
                error: function(error) {
                    console.error('Error updating color:', error);
                }
            });
        });
    });
</script>



</body>
</html>
