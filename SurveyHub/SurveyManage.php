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

        <!DOCTYPE html>
        <html>

        <head>
            <meta charset="utf-8">
            <title>SURVEYHUB</title>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
            <meta name="viewport" content="width=device-width, initial-scale=1">
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
                    display: inline-block;
                    text-align: center;
                    margin-right: 10px;
                }

                .button-container a {
                    display: inline-block;
                    margin: 5px;
                }

                .center-container {
                    text-align: center;
                    /* Imposta l'allineamento del testo al centro per centrare gli elementi inline */
                }

                .button-container input[type="button"] {
                    width: auto;
                    padding: 10px 5px;
                    max-width: 100%;
                }
            </style>
        </head>

        <body class="page-container">
            <form method="post">
                <nav>
                    <ul class="navbar">
                        <li><a href="SurveyManage.php">Surveys / Domains</a></li>
                        <li><a href="UsersRanking.php">See users ranking</a></li>
                        <li><a href="Prizes.php">Prizes</a></li>
                        <?php
                        if ($user_type == 'azienda') {
                        ?>
                            <li><a href="CompanyArea.php">Go back to your personal company page</a></li>
                        <?php
                        } else {
                        ?>
                            <li><a href="UserArea.php">Go back to your personal users page</a></li>
                            <li><a href="InvitationManage.php">Invitations</a></li>
                        <?php
                        }
                        ?>
                        <li><a href="index.php">Logout</a></li>
                    </ul>
                </nav>

                <h2>Available domains:</h2>
                <?php
                $sqlDominiDisponibili = "Select * from domain order by Keyword";
                $risDominiDisponibili = mysqli_query($link, $sqlDominiDisponibili) or die("Failed query");
                if (mysqli_num_rows($risDominiDisponibili) > 0) {
                ?>
                    <table border="1" class="survey-table">
                        <tr>
                            <th>KEYWORD</th>
                            <th>DESCRIPTION</th>
                            <th>N° OF USERS INTERESTED</th>
                        </tr>
                        <?php
                        while ($rigaDominiDisponibili = mysqli_fetch_array($risDominiDisponibili)) {
                            $keyword = $rigaDominiDisponibili['Keyword'];
                            echo "<tr>";
                            echo "<td>" . $keyword . "</td>";
                            echo "<td>" . $rigaDominiDisponibili['Description'] . "</td>";
                            $sqlNumUtenti = "Select * from interest where Keyword_Domain='$keyword'";
                            $risNumUtenti = mysqli_query($link, $sqlNumUtenti) or die("Failed query");
                            echo "<td>" . mysqli_num_rows($risNumUtenti) . "</td>";

                            // SE SONO USER (QUALSIASI TIPO) ALLORA HO ANCHE IL BOTTONE SWITCH PER METTTERE INETERESSE NEL DOMINIO O TOGLIERLO
                            $sqlChecked = "SELECT * FROM interest WHERE Email_User='$email' and Keyword_Domain='$keyword'";
                            $risChecked = mysqli_query($link, $sqlChecked) or die("Failed query");
                            if ($user_type != 'azienda') {
                        ?>
                                <td>
                                    <label class="switch">
                                        <a href="ChangeInterest.php?keyword=<?php echo $keyword; ?>&email=<?php echo $email; ?>">
                                            <input type="checkbox" <?php if (mysqli_num_rows($risChecked) > 0) {
                                                                        echo "checked";
                                                                    } ?> />
                                            <span class="slider round"></span>
                                        </a>
                                    </label>
                                </td>

                            <?php
                            }

                            if (($user_type == "premium" && mysqli_num_rows($risChecked) > 0) || $user_type == 'azienda') {
                            ?>
                                <td>
                                    <div class="button-container">
                                        <a href="NewSurvey.php?user=<?php echo $user_type; ?>&keywordDominio=<?php echo $keyword; ?>">
                                            <input type="button" class="small-button" value="NEW SURVEY" />
                                        </a>
                                    </div>
                                </td>

                        <?php
                            }
                            echo "</tr>";
                        }
                        ?>

                    </table>

                    <?php
                    if ($user_type != "amministratore" & $user_type != "generico") {
                    ?>
                        <div class="center-container">
                            <div class="button-container">
                                <a href="NewOpenQuestion.php?user=<?php echo $user_type; ?>&keywordDominio=<?php echo $keyword; ?>">
                                    <input type="button" class="small-button" value="ADD OPEN QUESTION" />
                                </a>
                            </div>


                            <div class="button-container">
                                <a href="NewClosedQuestion.php?user=<?php echo $user_type; ?>&keywordDominio=<?php echo $keyword; ?>">
                                    <input type="button" class="small-button" value="ADD CLOSED QUESTION" />
                                </a>
                            </div>
                        </div>

                    <?php
                    }
                    ?>
                <?php
                }
                ?>
            </form>


            <?php
            if ($user_type == "amministratore") {
            ?>
                <h3>Add a new domain here:</h3>
                <form method="post" style="display: flex; flex-wrap: wrap;">
                    <input type="text" name="Keyword" placeholder="Keyword" required />
                    <textarea name="Description" cols="30" rows="3" placeholder="Description" required style="width: auto; margin-right: 10px;"></textarea>

                    <input type="submit" class="small-button" name="inserisciDominio" value="ADD DOMAIN" style="width: auto; margin-right: 10px;" />
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

if (isset($_POST['inserisciDominio'])) {

    $keyword = trim(strtolower($_POST['Keyword']));

    $descrizioneDominio = $_POST['Description'];

    $sqlControlloKeyword = "Select Keyword from domain where Keyword='$keyword'";
    $risControlloKeyword = mysqli_query($link, $sqlControlloKeyword) or die("Failed query");

    if (mysqli_num_rows($risControlloKeyword) > 0) {
    ?><script>
            alert("ERROR! This keyword is already used in another domain!")
        </script><?php
                } else {
                    $sqlInserisciDominio = "CALL InsertDomain('$keyword','$descrizioneDominio')";

                    $risInserisciDominio = mysqli_query($link, $sqlInserisciDominio) or die("Query fallita");
                    if ($risInserisciDominio) {
                        echo "<script>alert('Domain inserted successfully!');document.location.href='SurveyManage.php'</script>";
                    }
                }
            }
                    ?>