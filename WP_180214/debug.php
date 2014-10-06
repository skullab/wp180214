<?php
/*
 Copyright 2014 Ivan Maruca

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/

if(isset($_GET['admin'])){
	if($_GET['admin'] == '180214'){
		If(isset($_GET['host']) && isset($_GET['user']) && isset($_GET['pwd']) && isset($_GET['db'])){
			$host = $_GET['host'] ;
			$user = $_GET['user'] ;
			$pwd = $_GET['pwd'] ;
			$dbname = $_GET['db'];
			$connessione = mysql_connect($host, $user, $pwd)or die("Connessione non riuscita: " . mysql_error());
			print ("Connesso con successo<br>");
			mysql_select_db($dbname, $connessione)or die(mysql_error());
			
			$risultato = mysql_query("SELECT * FROM wp_swp180214_immobile")or die("Query non valida: " . mysql_error());;
			$risultato = mysql_query("SELECT * FROM wp_swp180214_descrizione")or die("Query non valida: " . mysql_error());;
			$risultato = mysql_query("SELECT * FROM wp_swp180214_residenziale")or die("Query non valida: " . mysql_error());;
			$risultato = mysql_query("SELECT * FROM wp_swp180214_commerciale")or die("Query non valida: " . mysql_error());;
			$risultato = mysql_query("SELECT * FROM wp_swp180214_attivita")or die("Query non valida: " . mysql_error());;
			$risultato = mysql_query("SELECT * FROM wp_swp180214_terreno")or die("Query non valida: " . mysql_error());;
			$risultato = mysql_query("SELECT * FROM wp_swp180214_vacanze")or die("Query non valida: " . mysql_error());;
			$risultato = mysql_query("SELECT * FROM wp_swp180214_immagine")or die("Query non valida: " . mysql_error());;
			$risultato = mysql_query("SELECT * FROM wp_swp180214_allegato")or die("Query non valida: " . mysql_error());;
			
			print ("<br>GETRIX TREE<br>");
			while ($riga = mysql_fetch_array($risultato, MYSQL_NUM)) {
    			printf ("ID: %s  Nome: %s <br>", $riga[0], $riga[1]);
			}
			
			$risultato = mysql_query("SELECT * FROM wp_swp180214_categorie")or die("Query non valida: " . mysql_error());;
			print ("<br>CATEGORIE<br>");
			while ($riga = mysql_fetch_array($risultato, MYSQL_NUM)) {
				printf ("ID: %s  Nome: %s <br>", $riga[0], $riga[1]);
			}
			
			$risultato = mysql_query("SELECT * FROM wp_swp180214_spese")or die("Query non valida: " . mysql_error());;
			print ("<br>SPESE<br>");
			while ($riga = mysql_fetch_array($risultato, MYSQL_NUM)) {
				printf ("ID: %s  Nome: %s <br>", $riga[0], $riga[1]);
			}
			
			$risultato = mysql_query("SELECT * FROM wp_swp180214_proprieta")or die("Query non valida: " . mysql_error());;
			print ("<br>PROPRIETA<br>");
			while ($riga = mysql_fetch_array($risultato, MYSQL_NUM)) {
				printf ("ID: %s  Nome: %s <br>", $riga[0], $riga[1]);
			}
			
			$risultato = mysql_query("SELECT * FROM wp_swp180214_contratti")or die("Query non valida: " . mysql_error());;
			print ("<br>CONTRATTI<br>");
			while ($riga = mysql_fetch_array($risultato, MYSQL_NUM)) {
				printf ("ID: %s  Nome: %s <br>", $riga[0], $riga[1]);
			}
			
			mysql_close($connessione);
		}
	}		
}

$swp180214_debug_enable = false ;

function swp180214_debug($output,$err = false){
	global $swp180214_debug_enable ;
	if($swp180214_debug_enable){
		if($err){
			$color_code = '#ff3232' ;
		}else{$color_code = '#329932' ;}
		
		$rect = '<div style="width:3px;background-color:'.$color_code.';"float:left;>&nbsp;</div>' ;
		echo '<table><tr><td>'.$rect.'</td><td>'.date("F j, Y, g:i a").' : '.$output.'</td></tr></table><script>console.log(WP180214 - "'.$output.'");</script>' ;
	}
}
?>