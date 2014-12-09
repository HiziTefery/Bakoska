<?php

/*** begin our session ***/
include "sessionControl.php";
if ($_SESSION['user_id'] != 1) {
    echo "<div id='warningDiv'> <h2>New user can be added just by administrator!!!</h2></div>";
}
else {

if(!isset( $_POST['username'], $_POST['password']))
{
    $message = 'Please enter a valid username and password';
}
elseif (strlen( $_POST['username']) > 20 || strlen($_POST['username']) < 4)
{
    $message = 'Incorrect Length for Username';
}
elseif (strlen( $_POST['password']) > 20 || strlen($_POST['password']) < 4)
{
    $message = 'Incorrect Length for Password';
}
elseif (ctype_alnum($_POST['username']) != true)
{
    $message = "Username must be alpha numeric";
}
elseif (ctype_alnum($_POST['password']) != true)
{
        $message = "Password must be alpha numeric";
}
else
{
    $php_username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $php_password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
    $php_password = crypt($php_password);
    try
    {
        try{
            $conn = new PDO("mysql:host=$dbhost;dbname=$dbname",$dbuser,$dbpass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo 'CONNECTION ERROR: ' . $e->getMessage();
        }

        $data = $conn->prepare("SELECT id,password FROM authentication WHERE username = ?");
        $data->execute(array($php_username));
        $result = $data->rowCount();
        $r = $data->fetchAll();
        if($result >= 1)
        {
            $message = 'User with same name exists';
        }
        else
        {
            $data = $conn->prepare("INSERT INTO authentication(password,username) VALUES(?,?)");
            $data->execute(array($php_password,$php_username));
            $message = "User ".$php_username." was created successfully";
        }
        
    }
    catch(Exception $e)
    {
        $message = 'We are unable to process your request. Please try again later"';
    }
}
    echo "
        </br>
        <form id='userForm' action='".htmlspecialchars($_SERVER['REQUEST_URI'])."' method='post'>
        <div id='addUserDiv'>
        <table id='userTable'>
        <tr>
        <td>
        <h2>Add user</h2>
        </td>
        </tr>
        <tr>
        <td>
        $message
        </td>
        </tr>
        <tr>
        <td>
        <label for='username'>Username</label>
        <input type='text' id='username' name='username' value='' maxlength='20' />
        </td>
        </tr>
        <tr>
        <td>
        <label for='password'>Password</label>
        <input type='text' id='password' name='password' value='' maxlength='20' />
        </td>
        </tr>
        </tradmin>
        <td>
        <input type='submit' value='Create User'/>
        </td>
        </tr>
        </div>
        </form>
        ";

}
?>

