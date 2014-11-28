<html>
    <head>
	<link type="text/css" rel="stylesheet" href="stylesheet.css"/>
    </head>
    <body id="body">
    	<div class="page-wrap">
	    <div id="header">
		    <h1>Universal tool for measuring physical variables based on platform Raspberry Pi</h1>
	    </div>
	    <div id="menu">
		   <a href="/index.php?page=home">Main menu</a>
		   <br>
		   <a href="/index.php?page=basicForm">Data</a>
		   <br>
		   <a href="/index.php?page=configForm">Settings</a>
	    </div>	
	    <div id="content">
	    	<?php
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
	    <div class="site-footer">
		    <p>Made by Lubos Hyzak - 2014</p>
	    </div>
    </body>
</html>
