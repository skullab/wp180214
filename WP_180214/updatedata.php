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

function swp180214_upload_dir(){
	if(get_option(SWP180214_OPT_FIRST_INSTALL))return;
	
	$upload_dir = wp_upload_dir();
	
	if($upload_dir['error'] != false){
		update_option(SWP180214_OPT_UPLOAD_DIR,false);
		swp180214_show_warning();
		return;	
	}
	
	$upload_dir['wp180214'] = $upload_dir['path'].'/wp180214' ;
	$upload_dir['wp180214_feed_path'] = $upload_dir['wp180214'].'/feed' ;
	
	if(!is_dir($upload_dir['wp180214_feed_path'])){
		if(!wp_mkdir_p($upload_dir['wp180214'])){
			update_option(SWP180214_OPT_GLOBAL_ERROR,SWP180214_ERROR_MKDIR);
			swp180214_show_warning();
			return;
		}
		if(!wp_mkdir_p($upload_dir['wp180214_feed_path'])){
			update_option(SWP180214_OPT_GLOBAL_ERROR,SWP180214_ERROR_MKDIR);
			swp180214_show_warning();
			return;
		}
	}
	
	update_option(SWP180214_OPT_UPLOAD_DIR,$upload_dir);
	update_option(SWP180214_OPT_GLOBAL_ERROR,false);
}

function swp180214_download_feed(){
	$upload_dir = get_option(SWP180214_OPT_UPLOAD_DIR);
	$download_path = get_option(SWP180214_OPT_GETRIX_FEED_URI);
	$extension = strtolower(substr($download_path,strlen($download_path)-3));
	
	switch ($extension){
		case 'zip':
			$destination = $upload_dir['wp180214'].'/data_'.date('Y-m-d').'.zip' ;
			break;
		case 'xml':
			$destination = $upload_dir['wp180214_feed_path'].'/feed.xml' ;
			break;
		default:
			update_option(SWP180214_OPT_INSTALL_PROCESS,2);
			update_option(SWP180214_OPT_GLOBAL_ERROR,SWP180214_ERROR_MALFORMED_FEED_URI);
			swp180214_show_warning();
			return;
	}
	
	if (@copy($download_path, $destination) ){
		if($extension == 'zip'){
			$zip = new ZipArchive;
			$res = @$zip->open($destination);			
			if ($res === TRUE) {
				$zip->extractTo($upload_dir['wp180214_feed_path']);
				$zip->close();
			}else{
				update_option(SWP180214_OPT_GLOBAL_ERROR,SWP180214_ERROR_UNZIP_FEED);
				swp180214_show_warning();
				return;
			}

			if(!rename($upload_dir['wp180214_feed_path'].'/'.get_option(SWP180214_OPT_GETRIX_USER).'.xml',$upload_dir['wp180214_feed_path'].'/feed.xml')){
				update_option(SWP180214_OPT_GLOBAL_ERROR,SWP180214_ERROR_RENAME_FEED);
				swp180214_show_warning();
				return;
			};
		}
	}else{
		update_option(SWP180214_OPT_INSTALL_PROCESS,2);
		update_option(SWP180214_OPT_GLOBAL_ERROR,SWP180214_ERROR_DOWNLOAD_FEED_URI);
		swp180214_show_warning();
		return;
	}
	
	update_option(SWP180214_OPT_GLOBAL_ERROR,false);
}

function swp180214_populate_database(){
	
	swp180214_upload_dir();
	if(get_option(SWP180214_OPT_GLOBAL_ERROR) != false){
		return;
	}
	
	swp180214_download_feed();
	if(get_option(SWP180214_OPT_GLOBAL_ERROR) != false){
		return;
	}
	
	// ==================================================================================
	require_once 'parsers/GetrixFeedParser.php';
	
	$upload_dir = get_option(SWP180214_OPT_UPLOAD_DIR);
	$file = $upload_dir['wp180214_feed_path'].'/feed.xml' ;
	
	$getrix_feed = new GetrixFeedParser($file);
	$getrix_version = $getrix_feed->get_version();
	$getrix_user = $getrix_feed->get_user();
	// ==================================================================================
	if(get_option(SWP180214_OPT_GETRIX_SCHEMA_VERSION) != $getrix_version){
		update_option(SWP180214_OPT_GLOBAL_ERROR,SWP180214_ERROR_FEED_VERSION);
		update_option(SWP180214_OPT_INSTALL_PROCESS,0);
		swp180214_show_warning();
		return;
	}
	
	if(get_option(SWP180214_OPT_GETRIX_USER) != $getrix_user){
		update_option(SWP180214_OPT_GLOBAL_ERROR,SWP180214_ERROR_FEED_USER);
		update_option(SWP180214_OPT_INSTALL_PROCESS,0);
		swp180214_show_warning();
		return;
	}
	// ==================================================================================
	global $wpdb ;
	
	$immobili = $getrix_feed->get_immobili();
	foreach ($immobili as $immobile){
		$idimmobile = $immobile['idimmobile'];
		$immobile_data = array();
		$immobile_descrizione = array();
		$immobile_residenziale = array();
		$immobile_commerciale = array();
		$immobile_attivita = array();
		$immobile_terreno = array();
		$immobile_vacanze = array();
		$immobile_immagini = array();
		$immobile_allegati = array();
		foreach ($immobile as $key => $value){
			if($key == 'datainserimento' || $key == 'datamodifica'){
				$value = str_replace('T', ' ', $value);
			}
			if(is_string($value) && (strtolower($value) == 'true' || strtolower($value) == 'false')){
				$value = $value === 'true'? 1 : 0;
			}
			if($value instanceof DOMElement){
				switch ($key){
					case GetrixFeedParser::DESCRIZIONI:
						$immobile_descrizione = $getrix_feed->retrieve_table_data($immobile, GetrixFeedParser::DESCRIZIONI);
						$immobile_descrizione = $getrix_feed->inflate_data_table($immobile_descrizione, array('idimmobile'=>$idimmobile));
						break;
					case GetrixFeedParser::RESIDENZIALE:
						$immobile_residenziale = $getrix_feed->retrieve_table_data($immobile, GetrixFeedParser::RESIDENZIALE);
						$immobile_residenziale = $getrix_feed->inflate_data_table($immobile_residenziale, array('idimmobile'=>$idimmobile));
						break;
					case GetrixFeedParser::COMMERCIALE:
						$immobile_commerciale = $getrix_feed->retrieve_table_data($immobile, GetrixFeedParser::COMMERCIALE);
						$immobile_commerciale = $getrix_feed->inflate_data_table($immobile_commerciale, array('idimmobile'=>$idimmobile));
						break;
					case GetrixFeedParser::ATTIVITA:
						$immobile_attivita = $getrix_feed->retrieve_table_data($immobile, GetrixFeedParser::ATTIVITA);
						$immobile_attivita = $getrix_feed->inflate_data_table($immobile_attivita, array('idimmobile'=>$idimmobile));
						break;
					case GetrixFeedParser::TERRENO;
					$immobile_terreno = $getrix_feed->retrieve_table_data($immobile, GetrixFeedParser::TERRENO);
					$immobile_terreno = $getrix_feed->inflate_data_table($immobile_terreno, array('idimmobile'=>$idimmobile));
					break;
					case GetrixFeedParser::VACANZE:
						$immobile_vacanze = $getrix_feed->retrieve_table_data($immobile, GetrixFeedParser::VACANZE);
						$immobile_vacanze = $getrix_feed->inflate_data_table($immobile_vacanze, array('idimmobile'=>$idimmobile));
						break;
					case GetrixFeedParser::IMMAGINI:
						$immobile_immagini = $getrix_feed->retrieve_table_data($immobile, GetrixFeedParser::IMMAGINI);
						$immobile_immagini = $getrix_feed->inflate_data_table($immobile_immagini, array('idimmobile'=>$idimmobile));
						break;
					case GetrixFeedParser::ALLEGATI:
						$immobile_allegati = $getrix_feed->retrieve_table_data($immobile, GetrixFeedParser::ALLEGATI);
						$immobile_allegati = $getrix_feed->inflate_data_table($immobile_allegati, array('idimmobile'=>$idimmobile));
						break;
				}
			}else{
				$immobile_data[$key] = $value ;
			}
		}
		// ==================================================================================
		$table_immobile = swp180214_table_prefix().'immobile';
		$table_descrizione = swp180214_table_prefix().'descrizione';
		$table_residenziale = swp180214_table_prefix().'residenziale';
		$table_commerciale = swp180214_table_prefix().'commerciale';
		$table_attivita = swp180214_table_prefix().'attivita';
		$table_terreno = swp180214_table_prefix().'terreno';
		$table_vacanze = swp180214_table_prefix().'vacanze';
		$table_immagine = swp180214_table_prefix().'immagine';
		$table_allegato = swp180214_table_prefix().'allegato';
		// ==================================================================================
		$db_immobile = $wpdb->get_row("SELECT * FROM $table_immobile WHERE idimmobile = $idimmobile");
		if($db_immobile != null){
			//UPDATE
			// ==================================================================================
			$datamodifica = $wpdb->get_var("SELECT datamodifica FROM $table_immobile WHERE idimmobile = $idimmobile");
			//$datamodifica = "1970-01-01 00:00:00" ;
			
			if($datamodifica != null && $datamodifica != $immobile_data['datamodifica']){
				//swp180214_debug('UPDATE');
				swp180214_debug('AGGIORNAMENTO NECESSARIO PER IDIMMOBILE '.$idimmobile);
				$where = array('idimmobile'=>$idimmobile);
				
				//unset($immobile_data['idimmobile']);
				swp180214_debug('UPDATE IMMOBILE');
				swp180214_populate_database_update($table_immobile, $immobile_data, $where);
				
				foreach ($immobile_descrizione as $descrizione_data){
					//unset($descrizione_data['idimmobile']);
					swp180214_debug('UPDATE DESCRIONE');
					swp180214_populate_database_update($table_descrizione, $descrizione_data, $where);
				}
				
				$is_residenziale = $wpdb->get_var("SELECT _id FROM $table_residenziale WHERE idimmobile = $idimmobile");
				if($is_residenziale != null && !empty($immobile_residenziale)){
					foreach ($immobile_residenziale as $residenziale_data){
						//unset($residenziale_data['idimmobile']);
						swp180214_debug('UPDATE RESIDENZIALE');
						swp180214_populate_database_update($table_residenziale, $residenziale_data, $where);
					}
				}
				
				$is_commerciale = $wpdb->get_var("SELECT _id FROM $table_commerciale WHERE idimmobile = $idimmobile");
				if($is_commerciale != null && !empty($immobile_commerciale)){
					foreach ($immobile_commerciale as $commerciale_data){
						//unset($commerciale_data['idimmobile']);
						swp180214_debug('UPDATE COMMERCIALE');
						swp180214_populate_database_update($table_commerciale, $commerciale_data, $where);
					}
				}
				
				$is_attivita = $wpdb->get_var("SELECT _id FROM $table_attivita WHERE idimmobile = $idimmobile");
				if($is_attivita != null && !empty($immobile_attivita)){
					foreach ($immobile_attivita as $attivita_data){
						//unset($attivita_data['idimmobile']);
						swp180214_debug('UPDATE ATTIVITA');
						swp180214_populate_database_update($table_attivita, $attivita_data, $where);
					}
				}
				
				$is_terreno = $wpdb->get_var("SELECT _id FROM $table_terreno WHERE idimmobile = $idimmobile");
				if($is_terreno != null && !empty($immobile_terreno)){
					foreach ($immobile_terreno as $terreno_data){
						//unset($terreno_data['idimmobile']);
						swp180214_debug('UPDATE TERRENO');
						swp180214_populate_database_update($table_terreno, $terreno_data, $where);
					}
				}
				
				$is_vacanze = $wpdb->get_var("SELECT _id FROM $table_vacanze WHERE idimmobile = $idimmobile");
				if($is_vacanze != null && !empty($immobile_vacanze)){
					foreach ($immobile_vacanze as $vacanze_data){
						//unset($vacanze_data['idimmobile']);
						swp180214_debug('UPDATE VACANZE');
						swp180214_populate_database_update($table_vacanze, $vacanze_data, $where);
					}
				}
				
				$is_immagine = $wpdb->get_var("SELECT COUNT(*) FROM $table_immagine WHERE idimmobile = $idimmobile");
				if($is_immagine != null && !empty($immobile_immagini)){
					foreach ($immobile_immagini as $immagine_data){
						//unset($immagine_data['idimmobile']);
						swp180214_debug('UPDATE IMMAGINI');
						swp180214_populate_database_update($table_immagine, $immagine_data, $where);
					}
				}
				
				$is_allegato = $wpdb->get_var("SELECT COUNT(*) FROM $table_allegato WHERE idimmobile = $idimmobile");
				if($is_allegato != null && !empty($immobile_allegati)){
					foreach ($immobile_allegati as $allegato_data){
						//unset($allegato_data['idimmobile']);
						swp180214_debug('UPDATE ALLEGATO');
						swp180214_populate_database_update($table_allegato, $allegato_data, $where);
					}
				}
			}
		}else{
			//INSERT
			// ==================================================================================
			swp180214_populate_database_insert($table_immobile, $immobile_data);
			foreach ($immobile_descrizione as $descrizione_data){
				swp180214_populate_database_insert($table_descrizione, $descrizione_data);
			}
			
			if(!empty($immobile_residenziale)){
				foreach ($immobile_residenziale as $residenziale_data){
					swp180214_populate_database_insert($table_residenziale, $residenziale_data);
				}
			}
			if(!empty($immobile_commerciale)){
				foreach ($immobile_commerciale as $commerciale_data){
					swp180214_populate_database_insert($table_commerciale, $commerciale_data);
				}
			}
			if(!empty($immobile_attivita)){
				foreach ($immobile_attivita as $attivita_data){
					swp180214_populate_database_insert($table_attivita, $attivita_data);
				}
			}
			if(!empty($immobile_terreno)){
				foreach ($immobile_terreno as $terreno_data){
					swp180214_populate_database_insert($table_terreno, $terreno_data);
				}
			}
			if(!empty($immobile_vacanze)){
				foreach ($immobile_vacanze as $vacanze_data){
					swp180214_populate_database_insert($table_vacanze, $vacanze_data);
				}
			}
			if(!empty($immobile_immagini)){
				foreach ($immobile_immagini as $immagine_data){
					swp180214_populate_database_insert($table_immagine, $immagine_data);
				}
			}
			if(!empty($immobile_allegati)){
				foreach ($immobile_allegati as $allegato_data){
					swp180214_populate_database_insert($table_allegato, $allegato_data);
				}
			}
			
		}
	}
	
}

function swp180214_populate_database_insert($tablename,$data){
	global $wpdb ;
	$wpdb->insert($tablename,$data);
	if(!$wpdb->insert_id){
		swp180214_debug('errore inserimento dati per tabella : '.$tablename.' ID : '.$data['idimmobile']);
	}
}

function swp180214_populate_database_update($tablename,$data,$where){
	global $wpdb ;
	if(!$wpdb->update($tablename,$data,$where)){
		swp180214_debug('errore aggiornamento dati per tabella : '.$tablename.' ID : '.$data['idimmobile']);
	};
}
?>