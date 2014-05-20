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

class Process{
	
	public function translate_country($country){
    	switch ($country) {
        	case 'pt':
        	    echo LABEL_TRANSLATE_COUNTRY1;
        	    break;
        	case 'es':
        	    echo LABEL_TRANSLATE_COUNTRY2;
        	    break;
        	case 'fr':
            	echo LABEL_TRANSLATE_COUNTRY3;
            	break;
        	case 'uk':
            	echo LABEL_TRANSLATE_COUNTRY4;
            	break;
        	case 'us':
            	echo LABEL_TRANSLATE_COUNTRY5;
            	break;
        	case 'br':
            	echo LABEL_TRANSLATE_COUNTRY6;
            	break;
   		}
	}
	
	public function idiom_geoip(){
		$country = geoip_country_code_by_name($_SERVER['REMOTE_ADDR']);
		switch ($country) {
			case 'pt':
				require_once ("lang/pt.php");
				break;
			case 'es':
				require_once ("lang/es.php");
				break;
			case 'fr':
				require_once ("lang/fr.php");
				break;
			case 'uk':
				require_once ("lang/uk.php");
				break;
			case 'us':
				require_once ("lang/us.php");
				break;
			case 'br':
				require_once ("lang/br.php");
				break;
			default:
				require_once ("lang/uk.php");
		}
	}
	
	public function idiom_without_session($id){
		switch ($id) {
			case 'pt':
				require_once ("lang/pt.php");
				break;
			case 'es':
				require_once ("lang/es.php");
				break;
			case 'fr':
				require_once ("lang/fr.php");
				break;
			case 'uk':
				require_once ("lang/uk.php");
				break;
			case 'us':
				require_once ("lang/us.php");
				break;
			case 'br':
				require_once ("lang/br.php");
				break;
		}
	}
	
	public function check_session_idiom(){
		if (isset($_SESSION['pais'])){
			$pais = $_SESSION['pais'];
			switch ($pais) {
				case 'pt':
					require_once ("config/lang/pt.php");
					break;
				case 'es':
					require_once ("config/lang/es.php");
					break;
				case 'fr':
					require_once ("config/lang/fr.php");
					break;
				case 'uk':
					require_once ("config/lang/uk.php");
					break;
				case 'us':
					require_once ("config/lang/us.php");
					break;
				case 'br':
					require_once ("config/lang/br.php");
					break;
			}
		}
	}
	
	public function ip_adress(){
		$http_client_ip = $_SERVER['HTTP_CLIENT_IP'];
		$http_x_forwarded_for = $_SERVER['HTTP_X_FORWARDED_FOR'];
		$remote_adress = $_SERVER['REMOTE_ADDR'];
		
		if (!empty($http_client_ip)){
			$ip = $http_client_ip;
		} elseif (!empty($http_x_forwarded_for)){
			$ip = $http_x_forwarded_for;
		}else{
			$ip = $remote_adress;
		}
		return $ip;
	}
	
	
	public function venda($termo) {
    	if ($termo === 'N') {
        	echo (LABEL_VENDA1);
    	} elseif ($termo === 'S') {
        	echo (LABEL_VENDA2);
    	}
	}
	
	public function resize_image($filename) {
		$newwidth = 300;
		$newheight = 200;
		list($width, $height) = getimagesize($filename);
		$thumb = imagecreatetruecolor($newwidth, $newheight);
		$source = imagecreatefrompng($filename);
		imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
		imagepng($thumb);
    	imagedestroy($thumb);
	}
	
	public function spam_out($emai) {
    	$emai = filter_var($emai, FILTER_SANITIZE_EMAIL);
    	if (filter_var($emai, FILTER_VALIDATE_EMAIL)) {
        	return TRUE;
    	} else {
        	return FALSE;
    	}
	}
	
	public function search($sear) {
		switch ($sear) {
		    case '':
		        echo LABEL_;
		        break;
		    }
	}
	
	public function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array()) {
		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5(strtolower(trim($email)));
		$url .= "?s=$s&d=$d&r=$r";
		if ($img) {
			$url = '<img src="' . $url . '"';
			foreach ($atts as $key => $val)
				$url .= ' ' . $key . '="' . $val . '"';
			$url .= ' />';
		}
		return $url;
	}
}

?>

