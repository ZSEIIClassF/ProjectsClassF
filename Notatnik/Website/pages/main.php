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
    
    <a class="newList" title="Dodaj zadanie" onclick="add_list();"><i class="fa-solid fa-circle-plus fa-4x"></i></a>
    
    <footer><a href="info.html"><img src="../img/info.png" alt="Info" title="Info"></a> 
    <a href="https://projectsclassf.pl/" title="Autorzy" target="_blank">&copy; Szymon Skrzypek, Jan Walicki</a> </footer>
    
    <script>

function add_task(id)
{
    res = "";
    res +=          '<div class="task">';
    res +=                '<input type="text" placeholder="Nazwa zadania" name="nameTask">';
    res +=           '</div>'
    res +=              '<br/>'
    res +=           '<div class="task">';
    res +=                '<button onclick="add_new_task('+id+');">Dodaj</button>';
    res +=           '</div>';

    document.getElementById(id).innerHTML = res;
}

function add_list()
{
    res = "";

    res +=      '<div class="list">';
    res +=            '<div>';
    res +=                '<i class="fas fa-pencil-alt fa-xl"></i>';
    res +=                '<input type="text" name="nameListNew" placeholder="Nazwa listy">';
    res +=                '<i class="fa-solid fa-plus fa-xl";"></i>';
    res +=            '</div>';
    res +=            '<br/>';
    res +=           '<div class="task">';
    res +=                '<button onclick="submit_form();">Dodaj</button>';   
    res +=         '</div>'
    res +=      '</div>';

    document.getElementById("column1").innerHTML = res;
}

function generate_tasks(names, id)
{
    name = names.charAt(0).toUpperCase() + names.slice(1);
    res = "";

    res +=          '<div class="task">';
    res +=                '<input type="checkbox">';
    res +=                '<p>'+ name +'</p>';
    res +=                '<i class="fa-solid fa-trash" onclick="delete_task('+id+');"></i>';
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
    res +=                '<i class="fa-solid fa-plus fa-xl" onclick="add_task('+ids+');"></i>';
    res +=            '</div>';
    res +=            '<br/>';
    res +=         '<div  id='+ ids +'>'
    res +=         '</div>'
    res +=      '</div>';
        
    return res;
}

nameTasks = [];
idOfLists = [];
idTasks = [];
function prepare_task_html(todo)
{
    nameTasks.push(todo.name);
    idOfLists.push(todo.listId);
    idTasks.push(todo.id);
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
            console.log(idTasks);
            var idTask = JSON.stringify(idTasks);
            console.log(idTask);

            var result = "";
            for(i=0; i<idLists.length; i++){
                for(j=0; j<idOfLists.length; j++){
                    if(idLists[i]==idOfLists[j]){
                            result += generate_tasks(nameTasks[j], idTasks[i]);
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

function delete_task(id)
{
    alert('remove '+id);
}

function add_new_task(id)
{
    var title = document.getElementsByName('nameTask').item(0).value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 201)
        {
            document.location.reload();
        }
    }
    xhttp.open("POST", "apiTasks.php", true);
    xhttp.setRequestHeader("auth-key", "ProgramingIsSooGreat");
    xhttp.setRequestHeader("text", title);
    xhttp.setRequestHeader("status", id);
    xhttp.send();

}

function submit_form()
{
    var title = document.getElementsByName('nameListNew').item(0).value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 201)
        {
            document.location.reload();
        }
    }
    xhttp.open("POST", "api.php", true);
    xhttp.setRequestHeader("auth-key", "ProgramingIsGreat");
    xhttp.setRequestHeader("text", title);
    xhttp.send();

}

load_tasks();
</script>
</body>
</html>