<!-- Authors -->
<!-- Szymon Skrzpek -->
<!-- Jan Walicki -->

<?php
    session_start();

    if(isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']==true))
    {
        header('Location: pages/main.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="pl">

<head>

    <meta charset="UTF-8">
    <title>Notatnik</title>
    <meta name="viewport" content="width=device-width; initial-scale=1.0;">
    <link rel="icon" type="image/x-icon" href="img/logo.png">

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <meta name="description"
        content="Internetowy notatnik w bardzo prostu sposób może pomóc w organizacji twojego czasu. 
        Jest on dostępny na wielu platformach. Prosta w użyciu, użyteczna oraz z nowoczesnym designem witryna rozwiąże 
        twoje problemy z brakiem wolengo czasu. Ciekawym atutem jest to, że powiadomienia związane 
        z ukończeniem zadania na czas, są wysyłane mailowo lub na twój numer telefonu.">
    <meta name="keywords" content="notatnik, zadania, organizacja czasu, brak wolengo czasu">



</head>

<body>

    <div class="alert hide" id="alert">
        NAJPIERW SIĘ ZALOGUJ
    </div>


    <header>
        <div>
            <a href="https://notatnik.projectsclassf.pl">
                <img src="img/logo.png" alt="Logo">
                <h1>Notatnik</h1>
            </a>
        </div>
        <a href="pages/login.php"><img src="img/login.png" alt="Login" title="Logowanie/Rejestracja"></a>
    </header>

    <section>


        <!--   COLUMN 1     -->


        <div class="column">

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




        <div class="column">


            <div class="list">

                <div>
                    <i class="fas fa-pencil-alt fa-xl" onclick="ShowAlert()"></i>
                    <h2>Do szkoły</h2>
                    <i class="fa-solid fa-plus fa-xl" onclick="ShowAlert()"></i>
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




        <div class="column">

            <div class="list">

                <div>
                    <i class="fas fa-pencil-alt fa-xl" onclick="ShowAlert()"></i>
                    <h2>Nauka programowania</h2>
                    <i class="fa-solid fa-plus fa-xl" onclick="ShowAlert()"></i>
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




    <footer><a href="pages/info.html"><img src="img/info.png" alt="Info" title="Info"></a> 
        <a href="https://projectsclassf.pl/" title="Autorzy" target="_blank">&copy; Szymon Skrzypek, Jan Walicki</a> </footer>



    <script>
        function ShowAlert() {
            var element = document.getElementById("alert");
            if (!element.classList.contains("active")) {
                element.classList.add("active");
                HideAlert();
            }
        }

        function HideAlert() {
            setTimeout(function () {
                var element = document.getElementById("alert");
                element.classList.remove("active");

            }, 2800);
        }

    </script>


</body>

</html>