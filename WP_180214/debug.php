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

$swp180214_debug_enable = true ;

function swp180214_debug($output,$err = false){
	global $swp180214_debug_enable ;
	if($swp180214_debug_enable){
		if($err){
			$color_code = '#ff3232' ;
		}else{$color_code = '#329932' ;}
		
		$rect = '<div style="width:3px;background-color:'.$color_code.';"float:left;>&nbsp;</div>' ;
		echo '<table><tr><td>'.$rect.'</td><td>'.date("F j, Y, g:i a").' : '.$output.'</td></tr></table>' ;
	}
}
?>