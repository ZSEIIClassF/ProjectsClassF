<?php
    session_start();

    if(isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']==true))
    {
        header('Location: main.php');
        exit();
    }
    
    // Usuwanie zmiennych pamiętająych wartości wpisane do formularza
    if(isset($_SESSION['fr_user'])) unset($_SESSION['fr_user']);
    if(isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
    if(isset($_SESSION['fr_haslo1'])) unset($_SESSION['fr_haslo1']);
    if(isset($_SESSION['fr_regulamin'])) unset($_SESSION['fr_regulamin']);

    // Usuwanie błedów rejestracji
    if(isset($_SESSION['e_user'])) unset($_SESSION['e_user']);
    if(isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
    if(isset($_SESSION['e_haslo1'])) unset($_SESSION['e_haslo1']);
    if(isset($_SESSION['e_haslo2'])) unset($_SESSION['e_haslo1']);
    if(isset($_SESSION['e_regulamin'])) unset($_SESSION['e_regulamin']);

    if(isset($_SESSION['alert']) && $_SESSION['alert']==true)
    {
        $alert = true;
        unset($_SESSION['alert']);
    }
  

?>

<!DOCTYPE html>
<html lang='pl'>

<head>
    <meta charset="UTF-8">
    <title>Notatnik logowanie</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <header>
        <div>
            <a href="https://notatnik.projectsclassf.pl">
                <img src="../img/logo.png" alt="Logo">
                <h1>Notatnik</h1>
            </a>
        </div>
    </header>
    
    
    <form action="zaloguj.php" method="post">

        <div>
            <h1>Logowanie</h1>
            <input type="text" name="email" placeholder="E-mail" required>
            <input type="password" name="haslo" placeholder="Hasło" required>
            <input type="submit" name="" value="Zaloguj się">
            <?php
            if(isset($_SESSION['blad']))
            {   
                echo '<div class="error">'.$_SESSION['blad'].'</div>';
                unset($_SESSION['blad']);
            }
            ?>

            <p>Możesz się zarejestrować klikając w link - <a href="register.php">Rejestracja</a></p>
        </div>

    </form>

    <footer><a href="info.html"><img src="../img/info.png" alt="Info" title="Info"></a> Projects Class F &copy; </footer>

</body>

</html>
