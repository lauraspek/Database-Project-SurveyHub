<!DOCTYPE html>
<?php
session_start();
include("connessione.php");
if (isset($_SESSION['Email']) && isset($_SESSION['Password'])) {
    //verifico se esistono nel database
    $email = $_SESSION['Email'];
    $password = $_SESSION['Password'];
    $sqlControllo = "Select Email, Password from company WHERE ";
    $sqlControllo .= "Email='" . $email . "' and Password='" . $password . "';";
    $risControllo = mysqli_query($link, $sqlControllo);

    if (mysqli_num_rows($risControllo) == 0) {
        $_SESSION = array();
        session_destroy();
        header("Location:index.php");
    } else {
        $user_type = $_SESSION['tipologia'];
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
                    width: 100px;
                }
            </style>
        </head>

        <body class="page-container">
            <form method="post">
                <nav>
                    <ul class="navbar">
                        <li><a href="SurveyManage.php">Create a new survey</a></li>
                        <li><a href="UsersRanking.php">See users ranking</a></li>
                        <li><a href="Prizes.php">Prizes</a></li>
                        <li><a href="index.php">Logout</a></li>
                    </ul>
                </nav>
                <h2>Here are the surveys created by your company account!</h2>
                <table border="1" class="survey-table">
                    <tr>
                        <th>DOMAIN</th>
                        <th>TITLE</th>
                        <th>STATE</th>
                        <th>CREATION DATE</th>
                        <th>CLOSURE DATE</th>
                    </tr>
                    <?php
                    $sqlSondaggiCreati = "CALL ViewCompanyCreatedSurveys('$email')";
                    $sqlSondaggiCreati = "Select * from survey where Email_Company='$email'";
                    $risSondaggiCreati = mysqli_query($link, $sqlSondaggiCreati);

                    while ($rigaSondaggiCreati = mysqli_fetch_array($risSondaggiCreati)) {
                        echo "<tr>";
                        echo "<td>" . $rigaSondaggiCreati['Keyword_Domain'] . "</td>";
                        echo "<td>" . $rigaSondaggiCreati['Title'] . "</td>";
                        echo "<td>" . $rigaSondaggiCreati['State'] . "</td>";
                        echo "<td>" . $rigaSondaggiCreati['CreationDate'] . "</td>";
                        echo "<td>" . $rigaSondaggiCreati['ClosingDate'] . "</td>";
                        $idSondaggioCreato = $rigaSondaggiCreati['Id'];
                    ?>

                        <td>
                            <div class="button-container">
                                <a href="<?php echo 'Surveys.php?idSondaggio=' . $idSondaggioCreato; ?>">
                                    <input type="button" class="small-button" value="VIEW" />
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="button-container">
                                <a href="<?php echo 'InviteUser.php?idSondaggio=' . $idSondaggioCreato; ?>">
                                    <input type="button" class="small-button" value="INVITE" />
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="button-container">
                                <a href="<?php echo 'StatsSurvey.php?idSondaggio=' . $idSondaggioCreato; ?>">
                                    <input type="button" class="small-button" value="STATS" />
                                </a>
                            </div>
                        </td>
                        <td>
                            <div class="button-container">
                                <a href="<?php echo 'DeleteSurvey.php?idSondaggio=' . $idSondaggioCreato; ?>">
                                    <input type="button" class="small-button" value="DELETE" />
                                </a>
                            </div>
                        </td>

                    <?php
                        echo "</tr>";
                    }
                    ?>
                </table>
            </form>
        </body>

        </html>

<?php
    }
} else {
    header("Location:index.php");
}
?>