<!DOCTYPE html>
<?php
session_start();
include("connessione.php");
if (isset($_SESSION['Email']) && isset($_SESSION['Password'])) {
    //verifico se esistono nel database
    $email = $_SESSION['Email'];
    $password = $_SESSION['Password'];
    $sqlControllo = "Select Email, Password from user WHERE ";
    $sqlControllo .= "Email='" . $email . "' and Password='" . $password . "';";
    $risControllo = mysqli_query($link, $sqlControllo);
    $user_type = $_SESSION['tipologia'];

    if (mysqli_num_rows($risControllo) == 0 && $user_type != 'azienda') {
        $_SESSION = array();
        session_destroy();
        header("Location:index.php");
    } else {

?>

        <html>

        <head>
            <meta charset="utf-8">
            <title>SURVEYHUB</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">

            <style>
                .survey-table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                    background-color: #fff;
                    border: 2px solid #007bff;
                }

                .survey-table th,
                .survey-table td {
                    padding: 10px;
                    text-align: center;
                    border: 1px solid #ddd;
                }

                .survey-table th {
                    background-color: #007bff;
                    color: #fff;
                }

                .survey-table tr:hover {
                    background-color: #f5f5f5;
                }

                nav {
                    background-color: #007bff;
                    padding: 10px;
                }

                nav ul.navbar {
                    list-style: none;
                    padding: 0;
                    margin: 0;
                    display: flex;
                    justify-content: space-around;
                }

                nav .navbar li {
                    margin-right: 10px;
                }

                nav .navbar a {
                    text-decoration: none;
                    color: #fff;
                }

                .page-container {
                    max-width: 1200px;
                    margin: 0 auto;
                    padding: 20px;
                }

                .small-button {
                    padding: 10px 5px;
                    font-size: 12px;
                    color: #007bff;
                    width: 150px;
                }
            </style>
        </head>

        <body class="page-container">
            <nav>
                <ul class="navbar">
                    <li><a href="SurveyManage.php">Surveys / Domains</a></li>
                    <li><a href="UsersRanking.php">See users ranking</a></li>
                    <li><a href="Prizes.php">Prizes</a></li>


                    <?php
                    if ($user_type == 'azienda') {
                    ?><li><a href="CompanyArea.php">Go back to your personal company page</a></li>
                    <?php
                    } else {
                    ?>
                    <li><a href="UserArea.php">Go back to your personal user page</a></li>
                        <li><a href="InvitationManage.php">Invitations</a></li>
                        <?php
                    }
                    ?>
                    <li><a href="index.php">Logout</a></li>
                </ul>
            </nav>
            <h2>Prizes on SurveyHub:</h2>
            <table border="1" class="survey-table">
                <tr>
                    <th>PHOTO</th>
                    <th>NAME</th>
                    <th>DESCRIPTION</th>
                    <th>NEEDED POINTS</th>
                </tr>
                <?php
                $sqlPremiDisponibili = "CALL ViewPrizes()";
                $risPremiDisponibili = mysqli_query($link, $sqlPremiDisponibili);

                while ($rigaPremiDisponibili = mysqli_fetch_array($risPremiDisponibili)) {
                    echo "<tr>";
                    echo "<td>";
                ?>
                    <img width="50" src="data:image/jpeg;base64,<?php echo base64_encode($rigaPremiDisponibili['Photo']); ?>" />
                    <?php
                    echo "</td>";
                    echo "<td>" . $rigaPremiDisponibili['Name'] . "</td>";
                    echo "<td>" . $rigaPremiDisponibili['Description'] . "</td>";
                    echo "<td>" . $rigaPremiDisponibili['Points'] . "</td>";
                    
                    // se sei admin compare anche elimina premio
                    if ($rigaPremiDisponibili['Email_AdminUser'] == $email) {
                    ?>
                        <td><a href="DeletePrize.php?nomePremio=<?php echo $rigaPremiDisponibili['Name']; ?>"><input type="button" class="small-button" value="DELETE" /></a></td>
                <?php
                    }
                    echo "</tr>";
                }
                ?>
            </table>
            
            <!-- parte aggiunta premio by admin -->
            <?php
            if ($user_type == "amministratore") {
            ?>
                <br><h3>Add a new prize:</h3>
                <form method="post" enctype="multipart/form-data" >
                   
                        <input type="text" name="nomePremio" placeholder="Name" required /><br>
                        <textarea name="descrizione" cols="30" rows="5" placeholder="Description" required></textarea>
                        <label for="immagine">Load a photo</label><input type="file" name="immaginePremio" accept=".jpg,.jpeg,.png" /><br>
                        <input type="number" name="numeroPunti" placeholder="Needed points" min="1" required /><br>
                                     
                        <input type="submit" name="inserisciPremio" class="small-button" value="ADD PRIZE" />
                    
                </form>

            <?php
            }
            ?>
        </body>

        </html>

    <?php
    }
} else {
    header("Location:index.php");
}

if (isset($_POST['inserisciPremio'])) {

    $nomePremio = $_POST['nomePremio'];
    $descrizionePremio = $_POST['descrizione'];
    $immaginePremio = file_get_contents($_FILES["immagine"]["tmp_name"]);
    $immaginePremio = addslashes($immaginePremio);
    $numeroPuntiPremio = $_POST['numeroPunti'];

    mysqli_close($connessione);
    require("connessione.php");
    $sqlControlloNome = "Select Name from prize where Name='$nomePremio'";
    $risControlloNome = mysqli_query($link, $sqlControlloNome) or die("Query fallita" . mysqli_error($link));

    if (mysqli_num_rows($risControlloNome) > 0) {
    ?><script>
            alert("ERROR! This prize name is already used!")
        </script>
        <?php
                } else {
                    $sqlInserisciPremio = "CALL InsertPrize('$nomePremio','$descrizionePremio','$immaginePremio','$numeroPuntiPremio','$email')";

                    $risInserisciPremio = mysqli_query($link, $sqlInserisciPremio) or die("Query fallita200" . mysqli_error($link));
                    if ($risInserisciPremio) {
                        echo "<script>alert('Prize inserted successfully!');document.location.href='Prizes.php'</script>";
                    }
                }
            }
                    ?>

            