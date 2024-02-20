<!DOCTYPE html>
<?php
session_start();
include("connessione.php");
if (isset($_SESSION['Email']) && isset($_SESSION['Password'])) {
    //verifico se esistono nel database
    $email = $_SESSION['Email'];
    $password = $_SESSION['Password'];
    $user_type = $_SESSION['tipologia'];
    $idSondaggio = $_REQUEST['idSondaggio'];

    $sqlControllo1 = "Select Email, Password from user WHERE ";
    $sqlControllo1.= "Email='" . $email . "' and Password='" . $password . "';";
    $risControllo1 = mysqli_query($link, $sqlControllo1);

    if ((mysqli_num_rows($risControllo1) == 0 && $user_type != 'premium') && $user_type != 'azienda') {
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
                .switch {
                    position: relative;
                    display: inline-block;
                    width: 40px;
                    height: 18px;
                }

                .switch input {
                    opacity: 0;
                    width: 0;
                    height: 0;
                }

                .slider {
                    position: absolute;
                    cursor: pointer;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: #ccc;
                    -webkit-transition: .4s;
                    transition: .4s;
                }

                .slider:before {
                    position: absolute;
                    content: "";
                    height: 12px;
                    width: 12px;
                    left: 1px;
                    bottom: 3px;
                    background-color: white;
                    -webkit-transition: .4s;
                    transition: .4s;
                }

                input:checked+.slider {
                    background-color: #2196F3;
                }

                input:checked+.slider:before {
                    -webkit-transform: translateX(25px);
                    -ms-transform: translateX(25px);
                    transform: translateX(25px);
                }

                .slider.round {
                    border-radius: 34px;
                }

                .slider.round:before {
                    border-radius: 50%;
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
                    width: 130px;
                }

                .button-container {
                    text-align: center;
                }

                .button-container a {
                    display: inline-block;
                    margin: 5px;
                }

                .button-container input[type="button"] {
                    width: auto;
                    padding: 10px 5px;
                    max-width: 100%;
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
                    ?><li><a href="CompanyArea.php">Go back to your personal company page</a></li><?php
                    } else {
                    ?><li><a href="UserArea.php">Go back to your personal users page</a></li>
                        <li><a href="InvitationManage.php">Invitations</a></li><?php
                    }
                    ?>
                    <li><a href="index.php">Logout</a></li>
                </ul>
            </nav>

            <h2>List of all answers:</h2>
            <table border="1" class="survey-table">
                <tr>
                    <?php if ($user_type != 'azienda') {
                        echo "<th>USER</th>";
                    } ?>
                    <th>ANSWER</th>
                </tr>
                <?php
                $idDomanda = $_REQUEST['idDomanda'];
                $sqlTipologiaDomanda = "SELECT * FROM open_answer  WHERE Id_OpenQuestion='$idDomanda'";
                $risTipologiaDomanda = mysqli_query($link, $sqlTipologiaDomanda) or die(mysqli_error($link));
                if (mysqli_num_rows($risTipologiaDomanda) > 0) {

                    //DOMANDA APERTA
                    while ($risposta = mysqli_fetch_array($risTipologiaDomanda)) {
                ?>
                        <tr>
                            <?php if ($user_type != 'azienda') {
                                echo "<td>" . $risposta['Email_User'] . "</td>";
                            } ?>
                            <td><?php echo $risposta['Text'] ?></td>
                        </tr>
                    <?php
                    }
                } else {
                    //DOMANDA CHIUSA
                    $sqlRispostaChiusa = "SELECT * FROM closed_answer AS c, options AS o WHERE o.Id_ClosedQuestion='$idDomanda' AND c.Id_Options=o.Id";
                    $risRispostaChiusa = mysqli_query($link, $sqlRispostaChiusa) or die(mysqli_error($link));
                    while ($risposta = mysqli_fetch_array($risRispostaChiusa)) {
                    ?>
                        <tr>
                            <?php if ($user_type != 'azienda') {
                                echo "<td>" . $risposta['Email_User'] . "</td>";
                            } ?>
                            <td><?php echo $risposta['Text'] ?></td>
                        </tr>
                <?php
                    }
                }
                ?>
            </table>
        </body>

        </html>
<?php
    }
} else {
    header("Location:index.php");
}
?>