<!DOCTYPE html>
<?php
session_start();
session_destroy();
include("connessione.php");
if (isset($_POST['AccediUtente'])) {

    $email = $_POST['emailUtente'];
    $password = $_POST['passwordUtente'];

    $sqlControllo = "Select Email, Password from user WHERE ";
    $sqlControllo .= "Email='" . $email . "' and Password='" . $password . "';";
    $risControllo = mysqli_query($link, $sqlControllo);

    if (mysqli_num_rows($risControllo) == 0) {
        echo "<script>alert('Email or Password not correct!');document.location.href='index.php'</script>";
    } else {
        session_start();
        $_SESSION['Email'] = $email;
        $_SESSION['Password'] = $password;
        include("functionCheckUserType.php");
        $user_type = checkUserType($email);
        // creo tipologia e la impostato come valore di sessione in base alla funzione checkUserType 
        if ($user_type == 1) {
            $_SESSION['tipologia'] = 'amministratore';
        } else if ($user_type == 2) {
            $_SESSION['tipologia'] = 'premium';
        } else {
            $_SESSION['tipologia'] = 'generico';
        }
        header('location:UserArea.php');
    }
}

if (isset($_POST['AccediAzienda'])) {

    $email = $_POST['emailAzienda'];
    $password = $_POST['passwordAzienda'];

    $sqlControllo = "Select Email, Password from company WHERE ";
    $sqlControllo .= "Email='" . $email . "' and Password='" . $password . "';";
    $risControllo = mysqli_query($link, $sqlControllo);

    if (mysqli_num_rows($risControllo) == 0) {
        echo "<script>alert('Email o Password non corrette!');document.location.href='index.php'</script>";
    } else {
        session_start();
        $_SESSION['Email'] = $email;
        $_SESSION['Password'] = $password;
        $_SESSION['tipologia'] = 'azienda';
        header('location:CompanyArea.php');
    }
}
?>
<html>

<head>
    <meta charset="utf-8">
    <title>SURVEYHUB</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        .small-button {
            padding: 10px 5px;
            font-size: 12px;
            color: #007bff;
            width: 100px;
        }

        body {
            background-color: lightskyblue;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            max-width: 400px;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #fff;
            border-radius: 5px;
            background-color: lightskyblue;
        }
    </style>
</head>

<body>
    <h1><i>Welcome to SurveyHub!</i></h1>
    <p><b>Here you can manage your surveys</b></p><br>
    <div class="container">
        <form method="post">
            <div>
                <p>If you are an USER, please enter your credentials here: </p>
                <input type="email" name="emailUtente" placeholder="Email" />
                <input type="password" name="passwordUtente" placeholder="Password" />
                <input type="submit" class="small-button" name="AccediUtente" value="LOGIN" />
                <p>Click here to <a href="RegistrationUser.php">Signup as a USER</a></p>
            </div>
            <br>
            <div>
                <p>If you are a COMPANY, please enter your credentials here: </p>
                <input type="email" name="emailAzienda" placeholder="Email" />
                <input type="password" name="passwordAzienda" placeholder="Password" />
                <input type="submit" class="small-button" name="AccediAzienda" value="LOGIN" />
                <p>Click here to <a href="RegistrationCompany.php">Signup as a COMPANY</a></p>
            </div>
        </form>
    </div>
</body>

</html>