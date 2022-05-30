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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        echo "<span class='logged '>Witaj ".ucfirst($_SESSION['user']).'!</span>';
    ?>
    
    
    <section>
          
        <div class="column" id="column1">
    
            
        </div>
    
    </section>
    
    <a class="newList" title="Dodaj zadanie"><i class="fa-solid fa-circle-plus fa-4x"></i></a>
    
    <footer><a href="info.html"><img src="../img/info.png" alt="Info" title="Info"></a> 
    <a href="https://projectsclassf.pl/" title="Autorzy" target="_blank">&copy; Szymon Skrzypek, Jan Walicki</a> </footer>
    
    <script>

function generate_tasks(names)
{
    name = names.charAt(0).toUpperCase() + names.slice(1);
    res = "";

    res +=          '<div class="task">';
    res +=                '<input type="checkbox">';
    res +=                '<p>'+ name +'</p>';
    res +=          '</div>';

    return res;
}

function generate_lists(names, ids)
{
    name = names.charAt(0).toUpperCase() + names.slice(1);
    res = "";

    res +=      '<div class="list">';
    res +=            '<div>';
    res +=                '<i class="fas fa-pencil-alt fa-xl"></i>';
    res +=                '<h2 id="title">'+ name +'</h2>';
    res +=                '<i class="fa-solid fa-plus fa-xl"></i>';
    res +=            '</div>';
    res +=            '<br/>';
    res +=         '<div  id='+ ids +'>'
    res +=         '</div>'
    res +=      '</div>';
        
    return res;
}

nameTasks = [];
idOfLists = [];
function prepare_task_html(todo)
{
    nameTasks.push(todo.name);
    idOfLists.push(todo.listId);
}

function load_only_tasks()
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var objectsTasks = JSON.parse(this.responseText);
            // console.log(this.responseText);
            objectsTasks.forEach(todo => 
            {
                prepare_task_html(todo);
            });
            var namesTask = JSON.stringify(nameTasks);
            var idOfList = JSON.stringify(idOfLists);

            // nameTasks = [];  -> tablica z nazwami taskow
            // idOfLists = [];  -> tablica z id list z taskow
            // idLists = [];    -> tablica z id list z lists
            // nameLists = [];  -> tablica z nazwami list
            var result = "";
            for(i=0; i<idLists.length; i++){
                for(j=0; j<idOfLists.length; j++){
                    if(idLists[i]==idOfLists[j]){
                            result += generate_tasks(nameTasks[j]);
                            document.getElementById(idLists[i]).innerHTML = result;
                        }
                        else{
                            continue;
                        }
                }
                result = "";
            }

            // console.log(nameTasks);
            // console.log(idOfLists);
            // console.log(namesTask);
            // console.log(idOfList);
        }
    };

    var ids = JSON.stringify(idLists);
    xhttp.open("GET", "apiTasks.php", true);
    xhttp.setRequestHeader("auth-key", "ProgramingIsSooGreat");
    xhttp.setRequestHeader("full-list", "true");
    xhttp.setRequestHeader("status", ids);
    xhttp.send();
    result = xhttp.response;
}

idLists = [];  
nameLists = [];
function prepare_html(todo)
{
    idLists.push(todo.id);
    nameLists.push(todo.name)

    return 0;
}

function load_tasks()
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 200)
        {
            var objects = JSON.parse(this.responseText);
            // console.log(this.responseText);
            objects.forEach(todo => 
            {
                prepare_html(todo);
            });
            var names = JSON.stringify(nameLists);

            var result = "";
            for(i=0; i<nameLists.length; i++){
                result += generate_lists(nameLists[i], idLists[i]);
            }
            document.querySelector("#column1").innerHTML = result;
            
            // console.log(idLists);
            // console.log(nameLists);
            // console.log(objects);
            // console.log(names);
            // console.log("-----------tasks----------");

            load_only_tasks();
        }
    };


    xhttp.open("GET", "api.php", true);
    xhttp.setRequestHeader("auth-key", "ProgramingIsGreat");
    xhttp.setRequestHeader("full-list", "true");
    xhttp.send();
    result = xhttp.response;
}

load_tasks();
</script>
</body>
</html>