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
	//Aggiornamento upload dir
	swp180214_upload_dir();
	// Verifica aggiornamento cartelle
	if(get_option(SWP180214_OPT_GLOBAL_ERROR) != false){
		return;
	}
	
	require_once 'parsers/GetrixFeedParser.php';
	
	$upload_dir = get_option(SWP180214_OPT_UPLOAD_DIR);
	$file = $upload_dir['wp180214_feed_path'].'/feed.xml' ;
	
	$getrix_feed = new GetrixFeedParser($file);
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
	}
	
}
?>