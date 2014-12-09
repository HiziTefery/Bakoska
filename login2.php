
<script>
function createCookie(name, value, days) {
    var expires;
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else expires = "";
    document.cookie = name + "=" + value + expires + "; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function areCookiesEnabled() {
    var r = false;
    createCookie("testing", "Hello", 1);
    if (readCookie("testing") != null) {
        r = true;
        eraseCookie("testing");
    }
    return r;
}
if(!areCookiesEnabled()) {
    alert("Please enable cookies!");
}
</script>
<?php
session_start();
session_unset();
session_destroy();
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
    include "dbConfig.php";
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
        /*** if we have no result then fail boat ***/
        if($result != 1)
        {
            $message = 'Login Failed';
        }
        
        else
        {
            foreach($r as $value){
               $pwdHash =  $value['password'];
               $user_id = $value['id'];
            }
            if(crypt($php_password,$pwdHash) == $pwdHash) {
                /*** tell the user we are logged in ***/
                session_start();
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user_id;
                header ("Location: index.php");

            }
            else {
                $message = 'Login Failed - incorrect password!';
                session_start();
                $_SESSION['login'] = '';
            }

        }
    }
    catch(Exception $e)
    {
        /*** if we are here, something has gone wrong with the database ***/
        $message = 'We are unable to process your request. Please try again later"';
    }
}
?>
<noscript>
    <style type="text/css">
        .pagecontainer {display:none;}
    </style>
    <div class="noscriptmsg">
    You don't have javascript enabled.Please enable Javascript!!!
    </br>
    Application doesn't work without javascript enabled!!!
    </div>
</noscript>
<html>
<head>
    <title>Login</title>
</head>

<body>
<h2>Login Here</h2>
<form action="login2.php" method="post">
<fieldset>
<p>
<label for="username">Username</label>
<input type="text" id="username" name="username" value="" maxlength="20" />
</p>
<p>
<label for="password">Password</label>
<input type="password" id="password" name="password" value="" maxlength="20" />
</p>
<p>
<input type="submit" value="Login" />
</p>
<p><?php echo $message;?>
</fieldset>
</form>
</body>
</html>
