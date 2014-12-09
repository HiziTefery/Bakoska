<?PHP
    include "sessionControl.php";
?>
<html>


    <head>
	<link type="text/css" rel="stylesheet" href="stylesheet.css"/>
	<script>
	    function isInt(value) {
	      return !isNaN(value) && 
		     parseInt(Number(value)) == value && 
		     !isNaN(parseInt(value, 10));
	    }
	    function validateForm() {
		var x = document.forms["form4"]["baudText"].value;
		if (x==null || x=="") {
		    alert("Baudrate name must be filled out");
		    return false;
		}
		if (!isInt(x)) {
		    alert("Baudrate must be number!");
		    return false;
		}
		if ((x>200000) || (x<3000)) {
		    alert("Baudrate must be in interval  between 3000");
		    return false;
		}
	}
	</script>
    </head>
    <body id="body">
    	<div class="page-wrap">
	    <div id="header">
		    <h2>Universal tool for measuring physical variables based on platform Raspberry Pi</h1>
	    </div>
	    <div id="menu">
		   <a href="/index.php?page=home">Main menu</a>
		   <br>
		   <a href="/index.php?page=basicForm">Data</a>
		   <br>
		   <a href="/index.php?page=configForm">Settings</a>
           <br>
           <a href="/index.php?page=addNewUser">Add User</a>
           <br>
           <a href="login2.php">Log out</a>
	    </div>	
	    <div id="content">
	    	<?php

                    include "dbConfig.php";

                            if (isset($_GET['page'])) {
                                $file=$_GET['page'];
                                $file2= dirname($_SERVER['SCRIPT_FILENAME'])."/".$file.".php";
                                if(file_exists($file2)) {
                                if(substr_count($file,"../") > 0) {
                                    echo "Error";
                                } elseif($file=="index" or $file=="/index"){
                                    echo "Error";
                                } else {
                                    include $file2;
                                }
                                } else {
                                    include "inc/error404.php";
                                }
                            } else {
                                include "home.php";
                            }
            ?>
	    </div>
	</div>
    </body>
</html>
