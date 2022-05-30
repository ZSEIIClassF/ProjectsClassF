<?php

    require_once('connect.php');

    function decoder($value)
    {
        return htmlspecialchars($value);
    }

    function select($data, $table, $where)
    {
        $data = decoder($data);
        $table = decoder($table);
        $where = decoder($where);
        try
        {
            global $host;
            global $db_user;
            global $db_password;
            global $db_name;
            $conn = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_password);
            $stmt = $conn->prepare("SELECT $data FROM $table WHERE $where");
            $stmt->execute();
            $result = $stmt->fetchAll();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
            return $e->getMessage();
        }
        $conn = 0;
        return $result;
    }

    function single_select($data, $table, $where)
    {
        $data = decoder($data);
        $table = decoder($table);
        $where = decoder($where);
        try
        {
            global $host;
            global $db_user;
            global $db_password;
            global $db_name;
            $conn = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_password);
            $stmt = $conn->prepare("SELECT $data FROM $table WHERE $where LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetch();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
            return $e->getMessage();
        }
        $conn = 0;
        return $result;
    }

    function insert($columns, $table, $what, $iduser)
    {
        $columns = decoder($columns);
        $table = decoder($table);
        $what = decoder($what);
        $iduser = decoder($iduser);
        try
        {
            global $host;
            global $db_user;
            global $db_password;
            global $db_name;
            $conn = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_password);
            $stmt = $conn->prepare("INSERT INTO $table($columns) VALUES $what");
            $stmt->execute();
            $result = $stmt->fetchAll();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch (PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
        $conn = 0;

    }

    function update($what, $table, $where)
    {
        // $what = decoder($what);
        $table = decoder($table);
        $where = decoder($where);
        try
        {
            global $host;
            global $db_user;
            global $db_password;
            global $db_name;
            $conn = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_password);
            $stmt = $conn->prepare("UPDATE $table SET $what WHERE $where");
            $stmt->execute();
            $result = $stmt->fetch();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        }
        catch (PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
        $conn = 0;

    }

    function delete($table, $where)
    {
        $table = decoder($table);
        $where = decoder($where);
        try
        {
            global $host;
            global $db_user;
            global $db_password;
            global $db_name;
            $conn = new PDO("mysql:host=$host;dbname=$db_name", $db_user, $db_password);
            $stmt = $conn->prepare("DELETE FROM $table WHERE $where");
            $stmt->execute();
            $result = $stmt->fetch();
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        }
        catch (PDOException $e)
        {
            echo "Connection failed: " . $e->getMessage();
            return false;
        }
        $conn = 0;
    }

    // delete("lists", "id=23");
    // update("'name'='nowe tst'", 'lists', "'lists'.'id'=41");     DO NAPRAWY

    //  print_r(select('*', 'lists', '1'));
    // echo "<br/>";
    // print(select('*', 'lists', '1')[2]['name']);
    
?>