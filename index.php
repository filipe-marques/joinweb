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

if (session_start()){
	$proc->check_session_idiom();
}

$sess->full();

if (!isset($_GET['lang'])) {
	if (!isset($_COOKIE['lang'])){
		require_once ("config/lang/pt.php");
	} else {
		//idiom_geoip();
		$proc->idiom_without_session($_COOKIE['lang']);
	}
}

// login the user
if (isset($_POST['submit_login'])) {
	$emai = $db->real_escape_string(htmlspecialchars(trim($_POST['email']), ENT_QUOTES));
	$pasword = $db->real_escape_string(htmlspecialchars(trim($_POST['password']), ENT_QUOTES));
	
	$active = 1;
	
	// objecto acede á propriedade pública da classe
	// $st->has;
	
	$hashpass = hash($st->has, $pasword);
	$hashpass2 = crypt($hashpass, $st->hass);
	
	$db->query("START TRANSACTION");
	
	if (($result = $db->query("SELECT * FROM users WHERE email='{$emai}' AND password='{$hashpass2}' AND active='{$active}'"))){
		if (($count = $result->num_rows)){
			while($row = $result->fetch_object()){
				$_SESSION['id'] = $row->id;
				$_SESSION['nivel'] = $row->nivel;
				$_SESSION['nick'] = $row->nickname;
				$_SESSION['prinome'] = $row->primeiro_nome;
				$_SESSION['ultnome'] = $row->ultimo_nome;
				$_SESSION['email'] = $row->email;
				$_SESSION['idade'] = $row->idade;
				$_SESSION['sexo'] = $row->sexo;
				$_SESSION['registado'] = $row->registado;
				$_SESSION['pais'] = $row->pais;
			}
			if ($_SESSION['nivel'] == $st->level_zone_acess) {
				$db->query("COMMIT");
				header("Location: youxuse/admin/admin.php");
				exit();
			} elseif ($_SESSION['nivel'] == $st->level_zon_acess) {
				$ip_adr = $proc->ip_adress();
				$use_date = date('Y-m-d');
				$sql = $db->query("INSERT INTO ip_adress (users_id, ip_adress, data) VALUES ('" . $_SESSION['id'] . "','$ip_adr','$use_date')");
				$db->query("COMMIT");
				header("Location: user.php");
				exit();
			}
			$result->free();
			$result->close();
			$db->close();
		}
	}  else {
		echo 'No were found in the table!';
		$db->query("ROLLBACK");
		$db->close();
	}
}
	/*
    } else {
        echo ("<!--<div class=\"alert alert-block\">
                <h2>
                    <p class=\"text-center\">" . LABEL_SIGNIN_TEXT1 . "</p>
                    <p class=\"text-center\">" . LABEL_SIGNIN_TEXT2 . "</p>
                    <p class=\"text-center\">" . LABEL_SIGNIN_TEXT3 . "</p>
                </h2>
            </div>
            <div class=\"alert alert-info\">
                <h2>
                    <p class=\"text-center\"><a href=\"signup.php\">" . LABEL_SIGNIN_TEXT4 . "</a>" . LABEL_SIGNIN_TEXT5 . "</p>
                    <p class=\"text-center\">" . LABEL_SIGNIN_TEXT6 . "</p>
                    <p class=\"text-center\">" . LABEL_SIGNIN_TEXT7 . "</p>
                </h2>
            </div>-->");
            $db->query("ROLLBACK");
    }
    */

// insert the new user and then login that new user
if (isset($_POST['submit_signup'])) {
	$pri_nome = $db->real_escape_string(htmlspecialchars(trim($_POST['prinome']), ENT_QUOTES));
	$ult_nome = $db->real_escape_string(htmlspecialchars(trim($_POST['ultnome']), ENT_QUOTES));
	$email = $db->real_escape_string(htmlspecialchars(htmlentities(trim($_POST['email'])), ENT_QUOTES));
	$password = $db->real_escape_string(htmlspecialchars(htmlentities(trim($_POST['password'])), ENT_QUOTES));
	$idade = trim($_POST['idade']);
	$genero = trim($_POST['genero']);
	$pais = trim($_POST['pais']);
	$regis = date('Y-m-d');
	
	$hashpass = hash($st->has, $password);
	$hashpass2 = crypt($hashpass, $st->hass);
	
	$active = 1;
	
	if ($proc->spam_out($email) == FALSE) {
		header("Location: index.php");
		exit();
	}
	
	if (strlen($password) < 10) {
		echo ("<div class=\"alert alert-error\"><h2>" . LABEL_SIGNUP_TEXT1 . "</h2>
			<a href=\"signup.php\">" . LABEL_SIGNUP_TEXT2 . "</a></div>");
	} else {
		$db->query("START TRANSACTION");
		if (!($in = $db->query("INSERT INTO users (primeiro_nome, ultimo_nome, email, password, idade, sexo, registado, pais, active) VALUES ('{$pri_nome}','{$ult_nome}','{$email}','{$hashpass2}','{$idade}','{$genero}','{$regis}','{$pais}','{$active}')"))){
			echo("Operation failed, try again!");
			$db->query("ROLLBACK");
			$db->close();
		}else {
			$db->query("COMMIT");
			$db->query("START TRANSACTION");
			if (($result = $db->query("SELECT * FROM users WHERE email='{$email}' AND password='{$hashpass2}' AND active='{$active}'"))){
				if (($count = $result->num_rows)){
					while($row = $result->fetch_object()){
						$_SESSION['id'] = $row->id;
						$_SESSION['nivel'] = $row->nivel;
						$_SESSION['nick'] = $row->nickname;
						$_SESSION['prinome'] = $row->primeiro_nome;
						$_SESSION['ultnome'] = $row->ultimo_nome;
						$_SESSION['email'] = $row->email;
						$_SESSION['idade'] = $row->idade;
						$_SESSION['sexo'] = $row->sexo;
						$_SESSION['registado'] = $row->registado;
						$_SESSION['pais'] = $row->pais;
					}
					if ($_SESSION['nivel'] == $st->level_zon_acess) {
						$ip_adr = $proc->ip_adress();
						$use_date = date('Y-m-d');
						$db->query("START TRANSACTION");
						$sql = $db->query("INSERT INTO ip_adress (users_id, ip_adress, data) VALUES ('" . $_SESSION['id'] . "','$ip_adr','$use_date')");
						$db->query("COMMIT");
						header("Location: user.php");
						exit();
					}
					$result->free();
					$result->close();
					$db->close();
				}
			}
			$db->close();
			$db->query("COMMIT");
		}
	}
}
        /*if (($consulta)) {
            //exec("python3 signup_mail.pyc $email $pri_nome $ult_nome $hashpass");
            echo ("<div class=\"alert alert-success\">
                	<h2>
                    <p class=\"text-center\">" . LABEL_SIGNUP_TEXT3 . "</p>
                    <!--<p class=\"text-center\">" . LABEL_SIGNUP_TEXT4 . "</p>
                    <p class=\"text-center\">" . LABEL_SIGNUP_TEXT5 . "</p>
                    <p class=\"text-center\">" . LABEL_SIGNUP_TEXT6 . "</p>-->
                </h2>
            </div>");
            mysql_query("COMMIT");
        } else {
            mysql_query("ROLLBACK");
            header("Location: signup.php");
            exit();
        }
        mysql_free_result($consulta);
        mysql_close();
    }
}*/
?>
<!DOCTYPE html>
<html class="full" lang="en">
<!-- The full page image background will only work if the html has the custom class set to it! Don't delete it! -->

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="refresh" content="60"/>
	<meta name="description" content="">
	<meta name="author" content="">
	
	<title><?php echo LABEL_INDEX_TEXT0; ?></title>
	
	<!-- Bootstrap core CSS -->
	<link href="<?php echo $files['css']; ?>" rel="stylesheet">
	
	<!-- Custom CSS for the 'Full' Template -->
	<link href="<?php echo $files['css-theme']; ?>" rel="stylesheet">
	<?php
		require_once("youxuse/analytic.php");
	?>
	<link rel="shortcut icon" href="youxuse/resources/img/youxuse-icon.png">
</head>

<body>
	<nav class="navbar navbar-fixed-bottom navbar-inverse" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="youxuse/index.php">YouXuse<!--http://youxuse.com--></a>
				<a class="navbar-brand" href="nostress/index.php">NoStress<!--http://youxuse.com/nostress--></a>
			</div>
			
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav">
					<li><a href="youxuse/terms.php">Terms</a></li>
					<li><a href="youxuse/apps.php">Apps</a></li>
					<li><a href="https://plus.google.com/u/0/b/116778377892072300095">YouXuse in Google+</a></li>
					<li><a href="https://www.facebook.com/youxuse">YouXuse in Facebook</a></li>
					<li><a href="http://manuelforjaz.com/">YouXuse&trade;&copy; 2013-2014 version 2.5.1 - Codename: Manuel Forjaz</a></li>
				</ul>
			</div>
			<!-- /.navbar-collapse -->
		</div>
		<!-- /.container -->
	</nav>
	
	<div class="container">
			<?php // tag like and share?>
			<iframe src="//www.facebook.com/plugins/like.php?href=https://www.facebook.com/youxuse&amp;width&amp;layout=standard&amp;action=like&amp;show_faces=false&amp;share=true&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:25px;" allowTransparency="true"></iframe>
			<?php // tag +1 button ?>
			<div class="g-plusone" data-size="tall" data-annotation="inline" data-width="300" data-href="https://plus.google.com/116778377892072300095"></div>
			<script type="text/javascript">
				(function() {
					var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
					po.src = 'https://apis.google.com/js/plusone.js';
					var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
				})();
			</script>
			<?php	
				// counting the users that are active
				$db->query("START TRANSACTION");
				if (($result = $db->query("SELECT * FROM users WHERE active='1'"))){ // the COUNT(*) function doesn't work with INNODB
					if (($count = $result->num_rows)){
						echo '<p class=\"text-center\"><h2>Actualmente estão ', $count ,' utilizadores registados, junta-te a nós!</h2></p>';
						$db->query("COMMIT");
						$result->free();
					}
					$db->close();
				}
			?>
			<br>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
				<p class="lead"><?php echo LABEL_DONATE_TEXT1; ?></p>
					<p class="text-center">
					<input type="hidden" name="cmd" value="_s-xclick">
					<input type="hidden" name="hosted_button_id" value="2KBQ47J6VHFJ4">
					<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
					<img alt="" border="0" src="https://www.paypalobjects.com/pt_PT/i/scr/pixel.gif" width="1" height="1">
				</p>
			</form>
	</div>
	<br>
	<div class="container">
		<form class="form-inline" role="form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
			<p class="text-center"><h2 class="form-signin-heading"><?php echo LABEL_SIGNIN_TEXT11; ?></h2></p>
			<div class="form-group">
				<input type="email" class="form-control" id="email" name="email" placeholder="<?php echo LABEL_SIGNIN_TEXT13; ?>" required>
			</div>
			<div class="form-group">
				<input type="password" class="form-control" id="password" name="password" placeholder="<?php echo LABEL_SIGNIN_TEXT14; ?>" required>
			</div>
			<button class="btn btn-large btn-success" type="submit" name="submit_login"><?php echo LABEL_SIGNIN_TEXT12; ?> <span class="glyphicon glyphicon-user"></span></button>
		</form>
		<h2>OU</h2>
		<form class="form-horizontal" role="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" name="form" id="form">
			<h2 class="form-signin-heading"><?php echo LABEL_SIGNUP_TEXT8; ?></h2>
			<legend><?php echo LABEL_SIGNUP_TEXT9; ?><a data-toggle="tooltip" title="<?php echo LABEL_SIGNUP_TEXT10; ?> Filipe Marques">
				<i class="icon-question-sign"></i></a></legend>
			<input type="text" class="form-control" name="prinome" id="prinome" placeholder="<?php echo LABEL_SIGNUP_TEXT32; ?>" required />
			<br>
			<input type="text" class="form-control" name="ultnome" id="ultnome" placeholder="<?php echo LABEL_SIGNUP_TEXT33; ?>" required />
			<br>
			<legend><?php echo LABEL_SIGNUP_TEXT11; ?> <a data-toggle="tooltip" title="<?php echo LABEL_SIGNUP_TEXT12; ?> anA231.$&#.qWxSWdfTvg:,;-_?=)(/&%$#! - <?php echo LABEL_SIGNUP_TEXT34; ?>">
				<i class="icon-question-sign"></i></a></legend>
			<input type="password" class="form-control" name="password" id="password" placeholder="<?php echo LABEL_SIGNUP_TEXT11; ?>" required />
			<br>
			<legend><?php echo LABEL_SIGNUP_TEXT13; ?> <a data-toggle="tooltip" title="<?php echo LABEL_SIGNUP_TEXT14; ?> exemplo@mailman.com">
				<i class="icon-question-sign"></i></a></legend>
			<input type="email" class="form-control" name="email" id="email" placeholder="<?php echo LABEL_SIGNUP_TEXT13; ?>" required />
			<br>
			<legend><?php echo LABEL_SIGNUP_TEXT15; ?></legend>
			<select class="form-control" name="idade" id="idade" required />
				<option value=""><?php echo LABEL_SIGNUP_TEXT16; ?></option>
				<option value="18">18</option>
				<option value="19">19</option>
				<option value="20">20</option>
				<option value="21">21</option>
				<option value="22">22</option>
				<option value="23">23</option>
				<option value="24">24</option>
				<option value="25">25</option>
				<option value="26">26</option>
				<option value="27">27</option>
				<option value="28">28</option>
				<option value="29">29</option>
				<option value="30">30</option>
				<option value="31">31</option>
				<option value="32">32</option>
				<option value="33">33</option>
				<option value="34">34</option>
				<option value="35">35</option>
				<option value="36">36</option>
				<option value="37">37</option>
				<option value="38">38</option>
				<option value="39">39</option>
				<option value="40">40</option>
				<option value="41">41</option>
				<option value="42">42</option>
				<option value="43">43</option>
				<option value="44">44</option>
				<option value="45">45</option>
				<option value="46">46</option>
				<option value="47">47</option>
				<option value="48">48</option>
				<option value="49">49</option>
				<option value="50">50</option>
				<option value="51">51</option>
				<option value="52">52</option>
				<option value="53">53</option>
				<option value="54">54</option>
				<option value="55">55</option>
				<option value="56">56</option>
				<option value="57">57</option>
				<option value="58">58</option>
				<option value="59">59</option>
				<option value="60">60</option>
				<option value="61">61</option>
				<option value="62">62</option>
				<option value="63">63</option>
				<option value="64">64</option>
				<option value="65">65</option>
				<option value="66">66</option>
				<option value="67">67</option>
				<option value="68">68</option>
				<option value="69">69</option>
				<option value="70">70</option>
				<option value="71">71</option>
				<option value="72">72</option>
				<option value="73">73</option>
				<option value="74">74</option>
				<option value="75">75</option>
				<option value="76">76</option>
				<option value="77">77</option>
				<option value="78">78</option>
				<option value="79">79</option>
				<option value="80">80</option>
				<option value="81">81</option>
				<option value="82">82</option>
				<option value="83">83</option>
				<option value="84">84</option>
				<option value="85">85</option>
				<option value="86">86</option>
				<option value="87">87</option>
				<option value="88">88</option>
				<option value="89">89</option>
				<option value="90">90</option>
				<option value="91">91</option>
				<option value="92">92</option>
				<option value="93">93</option>
				<option value="94">94</option>
				<option value="95">95</option>
				<option value="96">96</option>
				<option value="97">97</option>
				<option value="98">98</option>
				<option value="99">99</option>
				<option value="100">100</option>     
			</select>
				
			<legend><?php echo LABEL_SIGNUP_TEXT17; ?> <a data-toggle="tooltip" title="<?php echo LABEL_SIGNUP_TEXT18; ?>">
				<i class="icon-question-sign"></i></a></legend>
			<select class="form-control" name="genero" id="genero" required />
				<option value=""><?php echo LABEL_SIGNUP_TEXT19; ?></option>
				<option value="M"><?php echo LABEL_SIGNUP_TEXT20; ?></option>
				<option value="F"><?php echo LABEL_SIGNUP_TEXT21; ?></option>
			</select>
				
			<legend><?php echo LABEL_SIGNUP_TEXT22; ?> <a data-toggle="tooltip" title="<?php echo LABEL_SIGNUP_TEXT23; ?>">
				<i class="icon-question-sign"></i></a></legend>
			<select class="form-control" name="pais" id="pais" required />
				<option value=""><?php echo LABEL_SIGNUP_TEXT24; ?></option>
				<option value="pt"><?php echo LABEL_SIGNUP_TEXT25; ?></option>
				<option value="es"><?php echo LABEL_SIGNUP_TEXT26; ?></option>
				<option value="fr"><?php echo LABEL_SIGNUP_TEXT27; ?></option>
				<option value="uk"><?php echo LABEL_SIGNUP_TEXT28; ?></option>
				<option value="us"><?php echo LABEL_SIGNUP_TEXT29; ?></option>
				<option value="br"><?php echo LABEL_SIGNUP_TEXT30; ?></option>
			</select>
			<br>
			<br>
			<p class="text-center"><a href="youxuse/terms.php"><?php echo LABEL_SIGNUP_TEXT35; ?></a></p>
			<br>
			<p class="text-center"><button class="btn btn-large btn-success btn-lg" type="submit" name="submit_signup"><?php echo LABEL_SIGNUP_TEXT31; ?> <span class="glyphicon glyphicon-user"></span></button></p>
		</form>
			
		</div> <!-- /container -->
	<!-- JavaScript -->
	<script src="<?php echo $files['jquery']; ?>"></script>
	<script src="<?php echo $files['js']; ?>"></script>
</body>
</html>
