<?php

session_start();

function check_auth()
{
    if(isset($_SERVER["HTTP_AUTH_KEY"]))
    {
        if($_SERVER["HTTP_AUTH_KEY"] == "ProgramingIsSooGreat")
        {
            return true;
        }
        else
        {
            http_response_code(403);
            echo "Wrong authorization key!";
            return false;
        }
    } 
    else
    {
        http_response_code(403);
        echo "Missing authorization key!";
        return false;
    }
}

// print_r($_SERVER["REQUEST_METHOD"]); 
if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(check_auth())
    {
        require("database.php");
        if(isset($_SERVER["HTTP_ID"]))
        {
            $result = single_select("name", "tasks", "id=".$_SERVER["HTTP_ID"]);
            if($result != [])
            {
                http_response_code(200);
                header('Content-type: application/json');
                echo json_encode($result); 
            }
            else
            {
                http_response_code(404);
                echo "No item with id equal ".$_SERVER["HTTP_ID"];
            }
        }
        else if(isset($_SERVER["HTTP_FULL_LIST"]) && $_SERVER["HTTP_FULL_LIST"] == "true" && isset($_SERVER['HTTP_STATUS']))
        {
            // listid z xhttp.setRequestHeader("status", ids); -> ids tablica idLists  = do wyswietlania
            $listidstr = $_SERVER['HTTP_STATUS'];
            $listidint = array_map('intval', json_decode($listidstr, true));
            $result = select("*", "tasks", "listId IN (".implode(', ', $listidint).")");    
            if($result != []) 
            {
                http_response_code(200);
                header('Content-type: application/json');
                echo json_encode($result);
            }
            else
            {
                http_response_code(404);
                echo "No item on list";
            }
        }
        else
        {
            http_response_code(500);
            echo "Wrong endpoint!";
        }
    }
}
else if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(check_auth())
    {
        require("database.php");
        if(isset($_SERVER["HTTP_TEXT"]) && $_SERVER["HTTP_TEXT"] != "" )
        {
            $status = $_SERVER['HTTP_STATUS'];  
            // if(isset($_SERVER["HTTP_STATUS"]) && in_array($_SERVER["HTTP_STATUS"] ,[0,1, "0", "1"])) $status = $_SERVER["HTTP_STATUS"];
            if(insert("name, listId", "tasks", "".$_SERVER["HTTP_TEXT"]."", "".$status.""))   // TUTAJ BEDZIE TRZEBA DOPISAC listid
            {
                http_response_code(201);
                echo "Item has been added to list";
            }
            else
            {
                http_response_code(500);
                echo "Item hasn't been added to list";
            }
        }
        else
        {
            http_response_code(500);
            echo "Wrong endpoint!";
        }
        
    }
}
else if($_SERVER["REQUEST_METHOD"] == "PUT" || $_SERVER["REQUEST_METHOD"] == "PATCH"){
    if(check_auth())
    {
        require("database.php");
        if(isset($_SERVER["HTTP_ID"]))
        {
            $result = single_select("*", "tasks", "id=".$_SERVER["HTTP_ID"]);
            if($result != [])
            {
                if((isset($_SERVER["HTTP_STATUS"]) /*&& in_array($_SERVER["HTTP_STATUS"] ,[1,2,3, "1", "2", "3"])*/)) 
                {
                    $update = "";
                    if(isset($_SERVER["HTTP_STATUS"]) && in_array($_SERVER["HTTP_STATUS"] ,[0,1, "0", "1"])) $status = $_SERVER["HTTP_STATUS"];
                    if($status != "")
                    {
                        $update .= "done='".$status."'";
                    }
                    if($update != "")
                    {
                        if(update("done=".$status."", "tasks", "id=".$_SERVER["HTTP_ID"].""))
                        {
                            http_response_code(200);
                            echo "Item updated successfully";
                        }
                        else
                        {
                            http_response_code(500);
                            echo "Item hasn't been updated";
                        }
                    }
                    else
                    {
                        http_response_code(404);
                        echo "Missing data to update item";
                    }
                }
                else
                {
                    http_response_code(404);
                    echo "Missing data to update item";
                }  
            }
            else
            {
                http_response_code(404);
                echo "No item with id equal ".$_SERVER["HTTP_ID"];
            }
        }
        else
        {
            http_response_code(500);
            echo "Wrong endpoint!";
        }
    }
}
else if($_SERVER["REQUEST_METHOD"] == "DELETE"){
    if(check_auth())
    {
        require("database.php");
        if(isset($_SERVER["HTTP_ID"]))
        {
            $result = single_select("*", "tasks", "id=".$_SERVER["HTTP_ID"]."");
            if($result != [])
            {
                if(delete("tasks", "id LIKE ".$_SERVER["HTTP_ID"].""))
                {
                    http_response_code(204);
                    echo "Item has been deleted successfully";
                }
                else
                {
                    http_response_code(500);
                    echo "Item hasn't been deleted!";
                }
            }
            else
            {
                http_response_code(404);
                echo "No item with id equal ".$_SERVER["HTTP_ID"];
            }
        }
        else
        {
            http_response_code(500);
            echo "Wrong endpoint!";
        }
    }   
}
else{
    http_response_code(500);
    echo "Nie zdefiniowano metody ".$_SERVER["REQUEST_METHOD"];
}


?>