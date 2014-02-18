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

function swp180214_show_warning(){
	//Verificare le impostazioni corrette per il plugin
	//Altrimenti avvisa con un WARNING BOX
	if (!get_option(SWP180214_DB_OPTION_NAME)) {		
		function swp180214_warning_first_install(){
			global $hook_suffix, $current_user;
			if ( $hook_suffix == 'plugins.php' ) {
				echo '<div class="updated" style="padding: 0; margin: 0; border: none; background: none;">
				<style type="text/css">
				.swp180214_install{min-width:825px;border:1px solid #303e87;padding:5px;margin:15px 0;background:#2c3342;background-image:-webkit-gradient(linear,0 0,80% 100%,from(#2c3342),to(#565b67));background-image:-moz-linear-gradient(80% 100% 120deg,#565b67,#2c3342);-moz-border-radius:3px;border-radius:3px;-webkit-border-radius:3px;position:relative;overflow:hidden}.swp180214_install .swp180214_a{position:absolute;top:30px;right:10px;font-size:100px;color:#6b707a;font-family:Georgia,"Times New Roman",Times,serif;z-index:1}.swp180214_install .swp180214_button{font-weight:700;border:1px solid #029DD6;border-top:1px solid #06B9FD;font-size:15px;text-align:center;padding:9px 0 8px;color:#FFF;background:#029DD6;background-image:-webkit-gradient(linear,0 0,0 100%,from(#029DD6),to(#0079B1));background-image:-moz-linear-gradient(0% 100% 90deg,#0079B1,#029DD6);-moz-border-radius:2px;border-radius:2px;-webkit-border-radius:2px}.swp180214_install .swp180214_button:hover{text-decoration:none!important;border:1px solid #029DD6;border-bottom:1px solid #00A8EF;font-size:15px;text-align:center;padding:9px 0 8px;color:#F0F8FB;background:#0079B1;background-image:-webkit-gradient(linear,0 0,0 100%,from(#0079B1),to(#0092BF));background-image:-moz-linear-gradient(0% 100% 90deg,#0092BF,#0079B1);-moz-border-radius:2px;border-radius:2px;-webkit-border-radius:2px}.swp180214_install .swp180214_button_border{border:1px solid #069;-moz-border-radius:2px;border-radius:2px;-webkit-border-radius:2px;background:#029DD6;background-image:-webkit-gradient(linear,0 0,0 100%,from(#029DD6),to(#0079B1));background-image:-moz-linear-gradient(0% 100% 90deg,#0079B1,#029DD6)}.swp180214_install .swp180214_button_container{cursor:pointer;display:inline-block;background:#DEF1B8;padding:1px;-moz-border-radius:2px;border-radius:2px;-webkit-border-radius:2px;width:266px}.swp180214_install .swp180214_description{position:absolute;top:22px;left:285px;margin-left:25px;color:#ffff9c;text-shadow:-1px 0 black,0 1px black,1px 0 black,0 -1px #000;font-size:15px;z-index:1000}.swp180214_install .swp180214_description strong{color:#FFF;font-weight:400}
				</style>
				<div class="swp180214_install">
				<div class="swp180214_a">'.SWP180214_DISPLAY_NAME.'</div>
				<div class="swp180214_button_container" onclick="window.location.href = \'admin.php?page='.SWP180214_SLUG_NAME.'-settings\';">
				<div class="swp180214_button_border">
				<div class="swp180214_button">Installa '.SWP180214_DISPLAY_NAME.' !</div>
				</div>
				</div>
				<div class="swp180214_description">
				<strong>'.SWP180214_DISPLAY_NAME.'</strong> -
				Questa &egrave; la prima installazione del plugin. Cliccare sul pulsante per procedere all\'installazione.
				</div>
				</div>
				</div>  ' ;
			}
		}
		add_action('admin_notices', 'swp180214_warning_first_install');
		return;
	}
}
?>