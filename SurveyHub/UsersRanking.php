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
            </style>
        </head>

        <body>
            <form method="post">
                <nav>
                    <ul class="navbar">

                        <li><a href="SurveyManage.php">Surveys / Domains</a></li>
                        <li><a href="UsersRanking.php"> See users ranking</a></li> 
                        <li><a href="Prizes.php">Prizes</a></li>
                        <?php
                        if ($user_type == 'azienda') {
                        ?> <li><a href="CompanyArea.php">Go back to your personal company page</a></li>
                        <?php
                        } else {
                        ?> <li><a href="UserArea.php">Go back to your personal user page</a></li>
                            <li><a href="InvitationManage.php">Invitations</a></li> 
                            <?php   
                        }
                        ?>
                        <li><a href="index.php">Logout</a></li>
                    </ul>
                </nav>
                
                <h2>Users ranking:</h2>
                <?php
                $sqlClassificaUtenti = "CALL OrderBonusTot()";
                $risClassificaUtenti = mysqli_query($link, $sqlClassificaUtenti) or die("Query fallita");
                if (mysqli_num_rows($risClassificaUtenti) > 0) {
                ?>
                    <table border="1" class="survey-table">
                        <tr> 
                            <th>EMAIL</th>
                            <th>SCORE</th>
                            <th>RANKING</th>
                        </tr>
                        <?php
                        $i = 0;
                        while ($rigaClassificaUtenti = mysqli_fetch_array($risClassificaUtenti)) {
                            $i++;
                            echo "<tr>";
                            echo "<td>" . $rigaClassificaUtenti['Email'] . "</td>";
                            echo "<td>" . $rigaClassificaUtenti['BonusTot'] . "</td>";
                            echo "<td>" . $i . "°</td>";
                            echo "</tr>";
                        }
                        ?>
                    </table>
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
?>