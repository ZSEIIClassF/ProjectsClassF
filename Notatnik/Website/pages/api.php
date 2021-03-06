<?php
// Author Szymon Skrzypek
session_start();

function check_auth()
{
    if(isset($_SERVER["HTTP_AUTH_KEY"]))
    {
        if($_SERVER["HTTP_AUTH_KEY"] == "ProgramingIsGreat")
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
            $result = single_select("name", "lists", "id=".$_SERVER["HTTP_ID"]);
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
        else if(isset($_SERVER["HTTP_FULL_LIST"]) && $_SERVER["HTTP_FULL_LIST"] == "true")
        {
            $userid = $_SESSION['id'];
            if(!isset($_SESSION['id'])) $userid = 23;
            $result = select("*", "lists", "userId=".$userid);    
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
            $status = $_SESSION['id'];
            if(isset($_SERVER["HTTP_STATUS"]) /*&& in_array($_SERVER["HTTP_STATUS"] ,[1,2,3, "1", "2", "3"])*/) $status = $_SERVER["HTTP_STATUS"];
            if(insert("name, userId", "lists", "".$_SERVER["HTTP_TEXT"]."", "".$status.""))
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
            $result = single_select("*", "lists", "id=".$_SERVER["HTTP_ID"]);
            if($result != [])
            {
                if((isset($_SERVER["HTTP_TEXT"]) && $_SERVER["HTTP_TEXT"] != "") || (isset($_SERVER["HTTP_STATUS"]) /*&& in_array($_SERVER["HTTP_STATUS"] ,[1,2,3, "1", "2", "3"])*/)) 
                {
                    $new_text = "";
                    if(isset($_SERVER["HTTP_TEXT"]) && $_SERVER["HTTP_TEXT"] != "") $new_text = $_SERVER["HTTP_TEXT"];
                    $status = "";  
                    if(isset($_SERVER["HTTP_STATUS"]) /*&& in_array($_SERVER["HTTP_STATUS"] ,[1,2,3, "1", "2", "3"])*/) $status = $_SERVER["HTTP_STATUS"];
                    $update_string = "";
                    if($new_text != "") $update_string .= "name='".$new_text."'";
                    if($status != "")
                    {
                        if($update_string != "") $update_string .=", ";
                        $update_string .= "userId='".$status."'";
                    }
                    if($update_string != "")
                    {
                        if(update($update_string, "lists", "id=".$_SERVER["HTTP_ID"].""))
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
            $result = single_select("*", "lists", "id=".$_SERVER["HTTP_ID"]."");
            if($result != [])
            {
                if(delete("lists", "id LIKE ".$_SERVER["HTTP_ID"].""))
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