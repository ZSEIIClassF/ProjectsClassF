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
        <a class="loggedLink" href="logout.php" title="Wyloguj się">Wyloguj</a>
    </header>
    
    <!-- tylko do testow -->
    <div class="Hi">
        <?php
            echo "<span class='logged '>Witaj ".ucfirst($_SESSION['user']).'!</span>';
        ?>
        <hr class="hiLines">
    </div>
    
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
    res +=                '<input type="text" name="nameListNew" placeholder="Nazwa listy">';
    res +=            '</div>';
    res +=            '<br/>';
    res +=           '<div class="task">';
    res +=                '<button onclick="submit_form();">Dodaj</button>';   
    res +=         '</div>'
    res +=      '</div>';

    document.getElementById("column1").innerHTML = res;
}

function generate_tasks(names, id, done)
{
    name = names.charAt(0).toUpperCase() + names.slice(1);
    res = "";

    res +=          '<div class="task">';
    res +=                '<i class="fa-solid fa-trash hide" onclick="delete_task('+id+');"></i>';
    res +=                '<input type="checkbox" id="'+id+'" '+done+' onclick="is_checked('+id+');">';
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
    res +=                '<i class="fas fa-pencil-alt fa-xl" onclick="edit_list('+ids+')"></i>';
    res +=                '<h2 id="title">'+ name +'</h2>';
    res +=                '<i class="fa-solid fa-plus fa-xl" onclick="add_task('+ids+');"></i>';
    res +=                '<i class="fa-solid fa-trash hide" onclick="delete_list('+ids+');"></i>';
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
isDone = [];
function prepare_task_html(todo)
{
    nameTasks.push(todo.name);
    idOfLists.push(todo.listId);
    idTasks.push(todo.id);
    isDone.push(todo.done);
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
            var idTask = JSON.stringify(idTasks);

            var result = "";
            for(i=0; i<idLists.length; i++){
                for(j=0; j<idOfLists.length; j++){
                    if(idLists[i]==idOfLists[j]){
                        var done = "";
                        if(isDone[j]==true) done = "checked";
                        result += generate_tasks(nameTasks[j], idTasks[j], done);
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
            // console.log(objectsTasks);
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

function is_checked(id)
{
    box = document.getElementById(id);
    done = 0;
    if(box.checked==true) done = 1; else done = 0;
    if(done==1 || done == 0){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() 
        {
            if (this.readyState == 4 && this.status == 200)
            {
                // document.location.reload();
            }
        }
        xhttp.open("PATCH", "apiTasks.php", true);
        xhttp.setRequestHeader("auth-key", "ProgramingIsSooGreat");
        xhttp.setRequestHeader("id", id);
        xhttp.setRequestHeader("status", done);
        xhttp.send();
    }
}

function delete_task(id)
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 204)
        {
            document.location.reload();
        }
    }
    xhttp.open("DELETE", "apiTasks.php", true);
    xhttp.setRequestHeader("auth-key", "ProgramingIsSooGreat");
    xhttp.setRequestHeader("id", id);
    xhttp.send();
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

function delete_list(id)
{
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() 
    {
        if (this.readyState == 4 && this.status == 204)
        {
            document.location.reload();
        }
    }
    xhttp.open("DELETE", "api.php", true);
    xhttp.setRequestHeader("auth-key", "ProgramingIsGreat");
    xhttp.setRequestHeader("id", id);
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



function edit_list(List_id)
{
    var tasksContainer = document.getElementById(List_id);
    var tasks = tasksContainer.children;
    var listTrash = tasksContainer.parentElement.firstChild.lastChild;
    var listAdd = tasksContainer.parentElement.firstChild.children[2];
    
    if(listTrash.classList.contains('hide'))
    {
        listTrash.classList.remove('hide');
        listAdd.classList.add('hide');
    }
    else
    {
        listAdd.classList.remove('hide');
        listTrash.classList.add('hide');
    }
    
    

    for (const current of tasks) {
        var trash = current.firstChild;
        var checkbox = current.children[1];
        
        if(trash.classList.contains('hide'))
        {
            trash.classList.remove('hide');
            checkbox.classList.add('hide');
        }
        else
        {
            checkbox.classList.remove('hide');
            trash.classList.add('hide');
        }
    }

}






load_tasks();
</script>
</body>
</html>