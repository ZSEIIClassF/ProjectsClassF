<?php
    session_start();

    if(!isset($_SESSION['zalogowany']))
    {
        header('Location: login.php');
        exit();
    }
    
    if(isset($_SESSION['alertZalogowany']) && $_SESSION['alertZalogowany']==true)
    {
        $alertZalogowany = true;
        unset($_SESSION['alertZalogowany']);
    }

?>

<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="UTF-8">
    <title>Notatnik</title>
    <meta name="viewport" content="width=device-width; initial-scale=1.0;">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

	
</head>
<body>
    
    <div class="alert hide" id="alert">
        NAJPIERW SIĘ ZALOGUJ
    </div>
    
    
    
    
    <header>
        <div>
            <a href="https://notatnik.projectsclassf.pl">
                <img src="../img/logo.png" alt="Logo">
                <h1>Notatnik</h1>
            </a>
        </div>
        <a href="login.php"><img src="../img/login.png" alt="Login" title="Logowanie/Rejestracja"></a>
    </header>
    
    <!-- tylko do testow -->
    <a class="loggedLink" href="logout.php" title="Wyloguj się">Wyloguj się!</a>
    <?php
        echo "<span class='logged '>Witaj ".ucfirst($_SESSION['user']).'!</span>'
    ?>
    
    
    <section>
        
        
        <!--   COLUMN 1     -->
        
        
        <div class="column" id="column1">
    
            <div class="list">
                
                <div>
                    <i class="fas fa-pencil-alt fa-xl"></i>
                    <h2>Prace domowe</h2>
                    <i class="fa-solid fa-plus fa-xl"></i>
                </div>
                
                <div class="task">
                    <input type="checkbox">
                    <p>Poodkurzać mieszakanie</p>
                </div>
                
                <div class="task">
                    <input type="checkbox">
                    <p>Posegregować książki w szafkach</p>
                </div>
                
                <div class="task">
                    <input type="checkbox">
                    <p>Wynieść śmieci</p>
                </div>
                
                <div class="task">
                    <input type="checkbox">
                    <p>Dokończyć czytanie książki</p>
                </div>
                
                <div class="task">
                    <input type="checkbox">
                    <p>Zrobić trening</p>
                </div>

            </div>
                
              
            
            
        </div>
    
        
        
        <!--   COLUMN 2     -->
        
        
        
        
        <div class="column" id="column2">
            
            
            <div class="list">
                
                <div>
                    <i class="fas fa-pencil-alt fa-xl"></i>
                    <h2>Do szkoły</h2>
                    <i class="fa-solid fa-plus fa-xl"></i>
                </div>
                
                <div class="task">
                    <input type="checkbox">
                    <p>Odrobić lekcje do godziny 18</p>
                    
                </div>
                
                <div class="task">
                    
                    <input type="checkbox">
                    <p>Wykonać prezentację na geografię z tematu Atmosfera</p>
                    
                </div>
                
                <div class="task">
                    
                    <input type="checkbox">
                    <p>Nauczyć się na sprawdzianu z matematyki</p>
                    
                </div>
                
                <div class="task">
                    
                    <input type="checkbox">
                    <p>Dokończyć referat z historii oraz nauczyć się jego prezentacji</p>
                    
                </div>
                
            </div>
            
        </div>
        
        
        
        
        
        <!--   COLUMN 3     -->
        
        
        
        
        <div class="column" id="column3">
            
            <div class="list">
                
                <div>
                    <i class="fas fa-pencil-alt fa-xl"></i>
                    <h2>Nauka programowania</h2>
                    <i class="fa-solid fa-plus fa-xl"></i>
                </div>
                
                <div class="task">
                    
                    <input type="checkbox">
                    <p>Dokończyć kurs Javy</p>
                    
                </div>
                
                <div class="task">
                        <input type="checkbox">
                        <p>Stowrzyć relatywną aplikację mobilną</p>
                </div>
                
                <div class="task">
                    <input type="checkbox">
                    <p>Opanować metody w JS</p>
                </div>
                
                <div class="task">
                    
                    <input type="checkbox">
                    <p>Doinformować się z zakresu programowalnych mikroprocesorów - Arduino/Raspberry Pi</p>
                    
                </div>
                
                <div class="task">
                    
                    <input type="checkbox">
                    <p>Skończyć tworzenie strony :D</p>
                    
                </div>
                
            </div>
            
        </div>
    
    
    </section>
    
    
    <a class="newList"><i class="fa-solid fa-circle-plus fa-4x"></i></a>
    
    
    
    
    <footer><a href="info.html"><img src="../img/info.png" alt="Info" title="Info"></a> Projects Class F &copy; </footer>
    
    
</body>
</html>