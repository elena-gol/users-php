<?php
    $firstName = $lastName = $age = $city = '';
    $errFirstName = $errLastName = $errAge = $errCity = '';    
    
    function rowsNum($conn) {
       return $conn->query('SELECT COUNT(*) FROM users')->fetchColumn();
    }
        
    function getUsersArray($conn) {
        $usersQuery = $conn->query('SELECT * FROM users');
        $users = array();
        $usersNum = rowsNum($conn);
        if ($usersNum > 0) {
            while($user = $usersQuery->fetch(PDO::FETCH_ASSOC)) {
                $users[] = $user;
            }
        }
        return $users;
    }
 
    function addUser($firstName, $lastName, $age, $city, $conn) {
        $add = $conn->prepare("INSERT INTO users (first_name, last_name, age, city) VALUES (:firstName, :lastName, :age, :city)");
        $add->execute(array(':firstName' => $firstName, ':lastName' => $lastName, ':age' => $age, ':city' => $city));
        if ($add) {
            logAddition($conn);
        }
    }
    
    function deleteUser($id, $conn) {
        $del = $conn->prepare("DELETE FROM users WHERE id = :id");
        $del->execute(array(':id' => $id));
        if ($del) {
            logDeletion($conn);
        }
    }
    
    function logDeletion($conn) {
        $conn->prepare("INSERT INTO log (operation) VALUES ('deletion')")->execute();
    }
    
    function logAddition($conn) {
        $conn->prepare("INSERT INTO log (operation) VALUES ('addition')")->execute();
    }
    
    function checkTextValidation($text) {
        $err = '';
        if (empty($text)) {
            $err = "Поле обязательно для заполнения";
        } elseif (!preg_match("/^[a-zA-Zа-яёА-ЯЁ ]*$/u", $text)) {
            $err = "Поле может содержать только символы кириллицы, латиницы и пробелы";
        } elseif (strlen($text) > 60) {
            $err = "Поле не должно содержать более 60 символов";
        }
        return $err;
    }
    
    function checkAgeValidation($age) {
        $err = '';
        if (empty($age)) {
            $err = "Поле обязательно для заполнения";
        } elseif (!preg_match("/^[1-9][0-9]*$/", $age)) {
            $err = "Поле может содержать только целые положительные числа";
        } elseif ($age > 110) {
            $err = "Введено некорректное значение для возраста";
        }
        return $err;
    }
    
    if(isset($_POST['add-user'])) {
        $firstName = trim($_POST['first-name']);
        $errFirstName = checkTextValidation($firstName);
        $lastName = trim($_POST['last-name']);
        $errLastName = checkTextValidation($lastName);
        $age = trim($_POST['age']);
        $errAge = checkAgeValidation($age);
        $city = trim($_POST['city']);
        $errCity = checkTextValidation($city);
        if (empty($errFirstName) && empty($errLastName) && empty($errAge) && empty($errCity)) {
            addUser($firstName, $lastName, $age, $city, $connection);
            $firstName = $lastName = $age = $city = '';
        }
    }
    
    if(isset($_POST['delete-user'])) {
        $userID = $_POST['user-id'];
        if (!empty($userID) && preg_match("/^[1-9][0-9]*$/", $userID)) {
            deleteUser($userID, $connection);
        }
    }
?>