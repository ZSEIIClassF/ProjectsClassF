<?php

session_start();

if(!isset($_POST['email']) || (!isset($_POST['haslo'])))
{
    header('Location: login.php');
    exit();
}

require_once "connect.php";

$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);

if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
    else
    {
        $login = $_POST['email'];
        $haslo = $_POST['haslo'];
        
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
        
        if($rezultat = @$polaczenie->query(sprintf("SELECT * FROM users WHERE email='%s'", 
            mysqli_real_escape_string($polaczenie, $login))))
        {
            $ilu_userow = $rezultat->num_rows;
            if($ilu_userow>0)
            {
                $wiersz = $rezultat->fetch_assoc();
                
                if($haslo == $wiersz['pass'])
                {
                    $_SESSION['zalogowany'] = true;
                    $_SESSION['alertZalogowany'] = true;

                    $_SESSION['id'] = $wiersz['id'];
                    $_SESSION['user'] = $wiersz['user'];
                    $_SESSION['email'] = $wiersz['email'];
                    // Tutaj można wyciągać wartości z bazy danych
                    // na przykład $_SESSION['email'] = $wiersz['email'];

                    unset($_SESSION['blad']);
                    $rezultat->free_result();
                    header('Location: main.php');
                }
                else
                {
                    $_SESSION['blad'] = '<div class="error">Nieprawidłowe hasło!</div>'; 
                    header('Location: login.php');
                }
                
            }
            else
            {
                $_SESSION['blad'] = '<div class="error">Nieprawidłowy login lub hasło!</div>'; 
                header('Location: login.php');
            }
        
        }
        
        if($rezultat2 = @$polaczenie->query(sprintf("SELECT * FROM lists WHERE email='%s'", 
            mysqli_real_escape_string($polaczenie, $login))))
        {
            
        }
        
        $polaczenie->close();
    }

?>
