<?php

session_start();

require("connect.php");

if (isset($_POST['email']))
{
    $wszystko_OK = true;
    
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
    
    if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
    {
        $wszystko_OK = false;
        $_SESSION['e_email'] = "Podaj poprawny adres e-mail!";
    }
    
    $user = $_POST['user'];
    
    if((strlen($user)<3) || (strlen($user)>20))
    {
        $wszystko_OK = false;
        $_SESSION['e_user'] = "Nazwa użytkownika musi posiadać od 3 do 20 znaków!";
    }
    $haslo1 = $_POST['haslo1'];
    $haslo2 = $_POST['haslo2'];
    
    if(strlen($haslo1)<8 || (strlen($haslo1)>20))
    {
        $wszystko_OK = false;
        $_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków!";
    }
    
    if($haslo1!=$haslo2)
    {
        $wszystko_OK = false;
        $_SESSION['e_haslo2'] = "Podane hasła nie są identyczne!";
    }
    
    // $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
    
//    echo $haslo_hash; exit();     NAPRAWIENIE HASEŁ!
    
    if(!isset($_POST['accept']))
    {
        $wszystko_OK = false;
        $_SESSION['e_regulamin'] = "Potwierdź akceptację regulaminu!";
    }
    
    // Zapamiętywanie wprowadzonych danych
    $_SESSION['fr_user'] = $user;
    $_SESSION['fr_email'] = $email;
    $_SESSION['fr_haslo1'] = $haslo1;
    if(isset($_POST['myCheckboxName'])) $_SESSION['fr_regulamin'] = true;
    
    // Łączenie z bazą danych w cellu sprawdzenia unikatowości danych
    
    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
    
    try
    {
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if ($polaczenie->connect_errno!=0)
	   {
		  throw new Exception(mysqli_connect_errno());
	   }
        else
        {
            // Sprawdzanie emaila w bazie
            $rezultat = $polaczenie->query("SELECT id FROM users WHERE email='$email'");
            if(!$rezultat) throw new Exception($polaczenie->error);
            
            $ile_takich_maili = $rezultat->num_rows;
            if($ile_takich_maili > 0)
            {
                $wszystko_OK = false;
                $_SESSION['e_email'] = "Istnieje już konto przypisane do takiego adresu e-mail";
            }
            
            // Poprawna rejestracja
            if($wszystko_OK == true)
            {
                if($polaczenie->query("INSERT INTO users VALUES (NULL, '$user', '$haslo1', '$email')"))
                {
                    $_SESSION['udanarejestracja'] = true;
                    $_SESSION['alert'] = true;  
                    header('Location: login.php');  // Miejsce do ktorego pojdziesz po zarejestrowaniu się!
                }
                else
                {
                    throw new Exception($polaczenie->error);
                }
                
                
                // Do zrobienia - przekierowanie do login.php oraz ALERT zielony o logowaniu.
            }
            
            
            $polaczenie->close();
        }
        
    }
    catch(Exception $e)
    {
        echo '<span class="error">Błąd serwera! Przeprasamy za niedogodnośći i prosimy o rejestracje w innym terminie!</span>';
//        echo '<br>Informcja developerska: '.$e;   // Informacja dla prgramisty!
    }
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
    
    
    <form method="post">

        <div>
            <h1>Rejestracja</h1>
            <input type="email" name="email" placeholder="E-mail" required value="<?php 
            if(isset($_SESSION['fr_email'])) 
            {
                echo $_SESSION['fr_email'];
                unset($_SESSION['fr_email']);
            }
            ?>">
            <?php
            if(isset($_SESSION['e_email']))
            {   
                echo '<div class="error">'.$_SESSION['e_email'].'</div>';
                unset($_SESSION['e_email']);
            }
            ?>
            <input type="text" name="user" placeholder="Nazwa użytkownika" required value="<?php 
            if(isset($_SESSION['fr_user'])) 
            {
                echo $_SESSION['fr_user'];
                unset($_SESSION['fr_user']);
            }
            ?>">
            <?php
            if(isset($_SESSION['e_user']))
            {   
                echo '<div class="error">'.$_SESSION['e_user'].'</div>';
                unset($_SESSION['e_user']);
            }
            ?>
            <input type="password" name="haslo1" placeholder="Hasło" required value="<?php 
            if(isset($_SESSION['fr_haslo1'])) 
            {
                echo $_SESSION['fr_haslo1'];
                unset($_SESSION['fr_haslo1']);
            }
            ?>">
            <?php
            if(isset($_SESSION['e_haslo']))
            {   
                echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
                unset($_SESSION['e_haslo']);
            }
            ?>
            <input type="password" name="haslo2" placeholder="Powtórz hasło" required>
            <?php
            if(isset($_SESSION['e_haslo2']))
            {   
                echo '<div class="error">'.$_SESSION['e_haslo2'].'</div>';
                unset($_SESSION['e_haslo2']);
            }
            ?>
            <input type="checkbox" name="accept" required <?php 
               if(isset($_SESSION['fr_regulamin']))
               {
                   echo "checked";
                   unset($_SESSION['fr_regulamin']);
               }
               ?>><label>Akceptuje <a href="statute.html">Regulamin</a> strony</label>
               <?php
            if(isset($_SESSION['e_regulamin']))
            {   
                echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
                unset($_SESSION['e_regulamin']);
            }
            ?>
            <input type="submit" name="" value="Zarejestruj się">
        </div>

    </form>

    <footer><a href="info.html"><img src="../img/info.png" alt="Info" title="Info"></a> Projects Class F &copy; </footer>

</body>

</html>
