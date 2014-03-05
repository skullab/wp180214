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

jQuery(document).ready(function($){
	swp180214_content_list_hide();
	$('#swp180214_generale_content').css('display','block');
	$('#swp180214_display_page').validate();
});

function swp180214_content_list_hide(){
	var content_list = jQuery('#swp180214_content_list').children();
	for(i=0;i<content_list.length;i++){
		jQuery(content_list[i]).css('display','none');
	}
}

function swp180214_show_content(element){
	
	var menu = jQuery('#swp180214_menulist').children();
	var id = jQuery(element).attr('id');
	for(i=0;i< menu.length;i++){
		jQuery(menu[i]).removeClass('active');
	}
	
	jQuery(element).addClass('active');
	swp180214_content_list_hide();
	jQuery('#'+id+'_content').css('display','block');
	
}

function swp180214_show_install_parameters(){
	if(jQuery('#swp180214_install_parameters').css('display') == 'none'){
		jQuery('#swp180214_install_parameters').slideDown(200);
	}else{
		jQuery('#swp180214_install_parameters').slideUp(200);
	}
}

function swp180214_show_uninstall(){
	if(jQuery('#swp180214_uninstall_box').css('display') == 'none'){
		jQuery('#swp180214_uninstall_box').slideDown(200);
	}else{
		jQuery('#swp180214_uninstall_box').slideUp(200);
	}
}

function swp180214_show_advanced(){
	swp180214_content_list_hide();
	jQuery('#swp180214_avanzate_content').css('display','block');
}

function swp180214_hide_advanced(){
	swp180214_show_content(jQuery('#swp180214_generale'));
}

function swp180214_request_update_db(nonce){
	
	jQuery('#swp180214_loader_db').css('display','block');
	
	jQuery.post(swp180214_ajax_placeholder.url,{
		action:'swp180214_action_update_db',
		_dbnonce:nonce,
	},function(response) {
		console.log('RESPONSE FROM '+swp180214_ajax_placeholder.url+' : '+response);
		jQuery('#swp180214_loader_db').css('display','none');
		alert(response);
	});
	
	return true ;
}

function swp180214_request_create_page(nonce){
	
	if(!jQuery('#swp180214_display_page').valid())return false ;
	
	jQuery('#swp180214_loader_page').css('display','block');
	
	setTimeout(function(){
	jQuery.post(swp180214_ajax_placeholder.url,{
		action:'swp180214_action_create_page',
		_nonce:nonce,
	},function(response) {
		console.log('RESPONSE FROM '+swp180214_ajax_placeholder.url+' : '+response);
		jQuery('#swp180214_loader_page').css('display','none');
		location.reload(); 
	});
	},0);
	return true ;
}

function swp180214_request_delete_page(nonce){
	setTimeout(function(){
		jQuery.post(swp180214_ajax_placeholder.url,{
			action:'swp180214_action_delete_page',
			_nonce:nonce,
		},function(response) {
			console.log('RESPONSE FROM '+swp180214_ajax_placeholder.url+' : '+response);
			alert(response);
			location.reload(); 
		});
		},0);
		return true ;
}

function swp180214_request_update_page(nonce){
	setTimeout(function(){
		jQuery.post(swp180214_ajax_placeholder.url,{
			action:'swp180214_action_update_page',
			_nonce:nonce,
		},function(response) {
			console.log('RESPONSE FROM '+swp180214_ajax_placeholder.url+' : '+response);
			//location.reload(); 
		});
		},0);
		return true ;
}