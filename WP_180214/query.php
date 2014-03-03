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

// Safe execution...
if ( !function_exists( 'swp180214_table_prefix' ) ) {
	exit;
}
/*****************************************************************************************************/
$sql_create_table_categorie = "CREATE TABLE ".swp180214_table_prefix()."categorie (
  categoria ENUM('1','2','3','4','5') NOT NULL,
  annotazione VARCHAR(50) NOT NULL,
  UNIQUE INDEX categoria (categoria ASC)
)ENGINE = InnoDB;" ;
		
$sql_create_table_comuni = "CREATE TABLE ".swp180214_table_prefix()."comuni (
  codice_nazione CHAR(2) NOT NULL,
  codice_comune CHAR(6) NOT NULL,
  comune VARCHAR(50) NOT NULL,
  regione VARCHAR(50) NOT NULL,
  provincia VARCHAR(50) NOT NULL,
  latitudine FLOAT NOT NULL,
  longitudine FLOAT NOT NULL
)ENGINE=InnoDB;";

$sql_create_table_contratti = "CREATE TABLE ".swp180214_table_prefix()."contratti (
  contratto ENUM('A','V') NOT NULL,
  annotazione VARCHAR(50) NOT NULL,
  UNIQUE INDEX contratto (contratto ASC)
)ENGINE=InnoDB;";

$sql_create_table_proprieta = "CREATE TABLE ".swp180214_table_prefix()."proprieta (
  tipo ENUM('1','2','3','4','5','6') NOT NULL,
  annotazione VARCHAR(50) NOT NULL,
  UNIQUE INDEX tipo (tipo ASC)
)ENGINE=InnoDB;";

$sql_create_table_quartieri = "CREATE TABLE ".swp180214_table_prefix()."quartieri (
  codice_quartiere INT NOT NULL,
  codice_nazione CHAR(2) NOT NULL,
  codice_comune CHAR(6) NOT NULL,
  quartiere VARCHAR(50) NOT NULL
)ENGINE=InnoDB;";

$sql_create_table_spese = "CREATE TABLE ".swp180214_table_prefix()."spese (
  tipo ENUM('0','1') NOT NULL,
  annotazione VARCHAR(50) NOT NULL,
  UNIQUE INDEX tipo (tipo ASC)
)ENGINE=InnoDB;";

$sql_create_table_tipologie = "CREATE TABLE ".swp180214_table_prefix()."tipologie (
  id_tipologia INT NOT NULL,
  tipologia VARCHAR(50) NOT NULL,
  categoria ENUM('1','2','3','4','5') NOT NULL
)ENGINE=InnoDB;";
/*****************************************************************************************************/
$sql_insert_categorie = "INSERT INTO ".swp180214_table_prefix()."categorie (categoria, annotazione) VALUES ('1', 'Immobili Residenziali');
INSERT INTO ".swp180214_table_prefix()."categorie (categoria, annotazione) VALUES ('2', 'Immobili Commerciali');
INSERT INTO ".swp180214_table_prefix()."categorie (categoria, annotazione) VALUES ('3', 'Attivita Commerciali');
INSERT INTO ".swp180214_table_prefix()."categorie (categoria, annotazione) VALUES ('4', 'Case Vacanza');
INSERT INTO ".swp180214_table_prefix()."categorie (categoria, annotazione) VALUES ('5', 'Terreni')";

$sql_insert_contratti = "INSERT INTO ".swp180214_table_prefix()."contratti (contratto, annotazione) VALUES ('A', 'Affitto');
INSERT INTO ".swp180214_table_prefix()."contratti (contratto, annotazione) VALUES ('V', 'Vendita')";

$sql_insert_proprieta = "INSERT INTO ".swp180214_table_prefix()."proprieta (tipo, annotazione) VALUES ('1', 'intera proprieta');
INSERT INTO ".swp180214_table_prefix()."proprieta (tipo, annotazione) VALUES ('2', 'nuda proprieta');
INSERT INTO ".swp180214_table_prefix()."proprieta (tipo, annotazione) VALUES ('3', 'parziale proprieta');
INSERT INTO ".swp180214_table_prefix()."proprieta (tipo, annotazione) VALUES ('4', 'usufrutto');
INSERT INTO ".swp180214_table_prefix()."proprieta (tipo, annotazione) VALUES ('5', 'multiproprieta');
INSERT INTO ".swp180214_table_prefix()."proprieta (tipo, annotazione) VALUES ('6', 'diritto di superficie')";

$sql_insert_spese = "INSERT INTO ".swp180214_table_prefix()."spese (tipo, annotazione) VALUES ('0', 'mensili');
INSERT INTO ".swp180214_table_prefix()."spese (tipo, annotazione) VALUES ('1', 'annuali')";


?>