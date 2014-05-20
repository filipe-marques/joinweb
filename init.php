<?php
/* This (website) software includes code from: 
 *		Eagle PHP Bootstrap Copyright (C) 2013-2014 Filipe Marques - eagle.software3@gmail.com
 * 
 * This file is part of YouXuse - Nostress
 *
 * <YouXuse - web application to sell & buy componnents of tecnology>
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
session_name("YouXuse");

require_once ("config/database/connect.php");

// The names of the .php files are the same as the names of classes 
spl_autoload_register(function($class){
	require_once("config/classes/" . $class . ".php");
});

// accessing $files associative array
// example: $files['header'];
$files = array(
	"css" => "config/resources/css/bootstrap.css",
	"css-theme" => "config/resources/css/the-big-picture.css",
	"js" => "config/resources/js/bootstrap.min.js",
	"jquery" => "config/resources/js/jquery-1.10.2.js",
	"header" => "config/hf/header.php",
	"footer" => "config/hf/footer.php",
);

?>
