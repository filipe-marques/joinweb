<?php
/*
 *  This (website) software includes code from: 
 *		Eagle PHP Bootstrap Copyright (C) 2013-2014 Filipe Marques - eagle.software3@gmail.com
 *		
 * This file is part of youxuse.com
 * 
 * Copyright (C) <2013 - 2014>  <Filipe Marques> <eagle.software3@gmail.com>
 *
 * YouXuse is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * YouXuse is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * For full reading of the license see the folder "license" 
 * 
 */
// DEVELOPMENT PURPOSES - DO NOT USE THIS IN PRODUCTION ENVIRONMENT
//error_reporting(E_ALL);
//ini_set('display_errors', true);
//ini_set('html_errors', false);
//--------------------------------------------------------

session_name("YouXuse");

if(file_exists("init.php")){
	// requiring the init file
	require_once("init.php");
}else{
	die("Not found init.php file!");
}

if (class_exists('Process') and class_exists('Sessions') and class_exists('Store')){
	$sess = new Sessions();
	$proc = new Process();
	$st = new Store();
}

$sess->full();

// check if it has session created, if yes search for the strings of country, if no do nothing
if (session_start()){
	$proc->check_session_idiom();
}

$sess->nothing();
$sess->is_admin();
$sess->generate_new_session_id();

//$complete_id = $_SESSION['id'];

//setcookie("active", $complete_id, time()+3600, "/");

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo ($sess->sexo() . " " . $_SESSION['prinome'] . " " . $_SESSION['ultnome']); ?> &dash; <?php echo LABEL_PAGE_TITLE_TEXT1;?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="<?php echo $files['css']; ?>" rel="stylesheet">
        <style type="text/css">
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }

            .form-signin {
                max-width: 600px;
                padding: 19px 29px 29px;
                margin: 0 auto 20px;
                background-color: #fff;
                border: 1px solid #e5e5e5;
                -webkit-border-radius: 5px;
                -moz-border-radius: 5px;
                border-radius: 5px;
                -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
            }
            .form-signin .form-signin-heading,
            .form-signin .checkbox {
                margin-bottom: 10px;
            }
        </style>
        <link href="<?php echo $files['css-theme']; ?>" rel="stylesheet">
        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="../assets/js/html5shiv.js"></script>
        <![endif]-->

        <!-- Fav and touch icons -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="resources/img/youxuse-icon-144.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="resources/img/youxuse-icon-114.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="resources/img/youxuse-icon-72.png">
        <link rel="apple-touch-icon-precomposed" href="resources/img/youxuse-icon-57.png">
        <link rel="shortcut icon" href="youxuse/resources/img/youxuse-icon.png">
		<?php
			require_once("youxuse/analytic.php");
		?>
    </head>

    <body>

        <div class="container">
			<p class="text-center">
				<?php
					$size = 40;
					$ddd = "identicon";
					$rr = "g";
					echo ("<h1>" . $sess->sexo() . " " . $_SESSION['prinome'] . " " . $_SESSION['ultnome'] . " " . $proc->get_gravatar($_SESSION['email'], $size, $ddd, $rr, true, '')."</h1>"); 
				?>
				<br>
				<h2><?php echo LABEL_PAGE_TITLE_TEXT1; ?></h2>
				<br>
				<a href="youxuse/index.php" type="button" class="btn btn-primary btn-lg">YouXuse</a>
				<a href="nostress/index.php" type="button" class="btn btn-primary btn-lg">NoStress</a>
				<br>
				<br>
				<h2><?php echo LABEL_LOGOUT; ?></h2>
				<br>
				<a href="logout.php" type="button" class="btn btn-danger btn-lg">Logout</a>
			</p>
		</div> <!-- /container -->
	
	<script src="<?php echo $files['jquery']; ?>"></script>
	<script src="<?php echo $files['js']; ?>"></script>
	</body>
</html>
