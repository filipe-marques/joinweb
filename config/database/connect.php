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

// The names of the .php files are the same as the names of classes 
spl_autoload_register(function($class){
	require_once("config/classes/" . $class . ".php");
});

if (class_exists('Store')){
	$str = new Store();
}

$db = new mysqli($str->local, $str->user, $str->password, $str->database);
if ($db->connect_error){
	die("Erro em aceder a base de dados !");
	//echo "Erro em aceder a base de dados (" . $db->connect_errno . ")" . $db->connect_error;
}
?>
