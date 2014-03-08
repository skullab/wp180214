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

var swp180214_install_process = swp180214_js_placeholder.process ;

jQuery(document).ready(function($){
	$('#swp180214_install_form').validate({
		rules:{
			swp180214_opt_getrix_schema_version:{
				regex:/[0-9]+(\.)[0-9]+(\.)[0-9]+/
			},
			swp180214_opt_getrix_user:{
				regex:/[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}/
			}
		},
		messages:{
			swp180214_opt_getrix_schema_version:{
				regex:"Inserire un numero di versione valida, nel formato X.Y.Z"
			},
			swp180214_opt_getrix_user:{
				regex:"Inserire il codice alfanumerico nel formato specificato dal fornitore del feed"
			}
		}
	});
});

function swp180214_restore_default_install_values(){
	jQuery('input[name=swp180214_opt_getrix_schema_uri]').val('http://feed.getrix.it/xml/feed_2_0_0.xsd');
	jQuery('input[name=swp180214_opt_getrix_schema_version]').val('2.0.0');
	jQuery('input[name=swp180214_opt_getrix_user]').val(swp180214_js_placeholder.usercode);
}
function swp180214_restore_default_feed_values(){
	jQuery('input[name=swp180214_opt_getrix_feed_uri]').val(swp180214_js_placeholder.feeduri);
}

function swp180214_onsubmit(settings,nonce){
	
	if(!jQuery('#swp180214_install_form').valid())return false ;
	jQuery('#swp180214_loader').css('display','block');
	
	if(nonce == null && nonce == ''){
		nonce = swp180214_ajax_placeholder.nonce ;
	}
	
	if(swp180214_install_process == 0 || settings == 1){
		setTimeout(function(){
			jQuery.post(swp180214_ajax_placeholder.url,{
				action:'swp180214_action_submit_install',
				_nonce:nonce,
			},function(response) {
				//console.log('RESPONSE FROM '+swp180214_ajax_placeholder.url+' : '+response);
				if(response != '')alert(response);
			});
		},0);
	}else if(swp180214_install_process == 2 || settings == 2){
		setTimeout(function(){
			jQuery.post(swp180214_ajax_placeholder.url,{
				action:'swp180214_action_submit_feed',
				_nonce:nonce,
			},function(response) {
				//console.log('RESPONSE FROM '+swp180214_ajax_placeholder.url+' : '+response);
				if(response != '')alert(response);
			});
		},0);
	}
	
	return true;
}