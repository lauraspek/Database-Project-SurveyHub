<!DOCTYPE html>
<?php
session_start();
include("connessione.php");
if (isset($_SESSION['Email']) && isset($_SESSION['Password'])) {
    //verifico se esistono nel database
    $email = $_SESSION['Email'];
    $password = $_SESSION['Password'];
    $user = $_REQUEST['user'];
    $keyword = $_REQUEST['keywordDominio'];

    $sqlControllo1 = "Select Email, Password from user WHERE ";
    $sqlControllo1 .= "Email='" . $email . "' and Password='" . $password . "';";
    $risControllo1 = mysqli_query($link, $sqlControllo1);

    $sqlControllo2 = "Select * from interest WHERE ";
    $sqlControllo2 .= "Keyword_Domain='" . $keyword . "' and Email_User='" . $email . "';";
    $risControllo2 = mysqli_query($link, $sqlControllo2);

    if ((mysqli_num_rows($risControllo1) == 0 or mysqli_num_rows($risControllo2) == 0) && $user != 'azienda') {
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

                .small-button {
                    padding: 10px 5px;
                    font-size: 12px;
                    color: #007bff;
                    width: 100px;
                }

                .button-container {
                    text-align: center;
                    margin-top: 20px;
                }

                .button-container input[type="submit"],
                .button-container input[type="reset"] {
                    display: inline-block;
                    margin-right: 10px;
                }
            </style>
        </head>

        <body class="page-container">
            <nav>
                <ul class="navbar">
                    <li><a href="SurveyManage.php">Create a new survey</a></li>
                    <li><a href="UsersRanking.php">See users ranking</a></li>
                    <li><a href="Prizes.php">Prizes</a></li>
                    <?php
                    if ($user == 'azienda') {
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
            
            <h2>Create a new survey about: <i>
                    <?php echo $keyword; ?></i></h2>
            <form method="post">
                <table border="1" class="survey-table">
                    <tr>
                        <td>Title</td>
                        <td><input type="text" name="Title" required /></td>
                    </tr>
                    <tr>
                        <td>N° max of users</td>
                        <td><input type="number" name="MaxUser" min="1" required /></td>
                    </tr>
                    <tr>
                        <td>Closing date</td>
                        <td><input type="date" name="ClosingDate" required /></td>
                    </tr>
                </table>
                <div class="button-container">
                    <input type="submit" class="small-button" name="inserisciSondaggio" value="CREATE" />
                </div>

            </form>
        </body>

        </html>

<?php
    }
} else {
    header("Location:index.php");
}

if (isset($_POST['inserisciSondaggio'])) {

    $titoloSondaggio = trim(strtolower($_POST['Title']));
    $maxUtenti = $_POST['MaxUser'];
    $dataChiusura = $_POST['ClosingDate'];
    $titoloSondaggio = ucwords($titoloSondaggio);
    $dataCreazione = date('Y-m-d');

    if ($user != 'azienda') {
        $sql = "CALL CreatePremiumSurvey('$titoloSondaggio','open','$maxUtenti','$dataCreazione','$dataChiusura','$keyword','$email')";
    } else {
        $sql = "CALL CreateCompanySurvey('$titoloSondaggio','open','$maxUtenti','$dataCreazione','$dataChiusura','$keyword','$email')";
    }
    $ris = mysqli_query($link, $sql) or die("Failed query" . mysqli_error($link));

    if ($ris) {
        echo "<script>alert('Survey inserted successfully!');</script>";
    }
}
?>