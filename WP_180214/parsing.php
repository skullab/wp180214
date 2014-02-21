<?php
/*$attributes = array();
$xsdstring = "http://feed.getrix.it/xml/feed_2_0_0.xsd";
$XSDDOC = new DOMDocument();
$XSDDOC->preserveWhiteSpace = false;
if ($XSDDOC->load($xsdstring))
{
    $xsdpath = new DOMXPath($XSDDOC);
    $attributeNodes =
              $xsdpath->
              query('//xs:schema/xs:element/xs:complexType')
              ->item(0);
    foreach ($attributeNodes->childNodes as $attr)
    {
        $attributes[ $attr->getAttribute('value') ] = $attr->getAttribute('name');
    }
    unset($xsdpath);
}
print_r($attributes); */


/*function echoElements($indent, $elementDef) {
 global $doc, $xpath;
echo "<div>" . $indent . $elementDef->getAttribute('name') . "</div>\n";
$elementDefs = $xpath->evaluate("xs:complexType/xs:sequence/xs:element", $elementDef);
foreach($elementDefs as $elementDef) {
echoElements($indent . "&nbsp;&nbsp;&nbsp;&nbsp;", $elementDef);
}
}

$elementDefs = $xpath->evaluate("/xs:schema/xs:element");
foreach($elementDefs as $elementDef) {
echoElements("", $elementDef);
}    */

$url = "http://feed.getrix.it/xml/feed_2_0_0.xsd";

$doc = new DOMDocument();
$doc->load($url);
$xpath = new DOMXPath($doc);
$schema_prefix = 'xs' ;
$schema_uri = 'http://www.w3.org/2001/XMLSchema' ;
$xpath->registerNamespace($schema_prefix,$schema_uri);

$schema_tables = array();

function is_element_node($node){return ($node->nodeName == 'xs:element'?true:false) ;}
function is_complextype_node($node){return ($node->nodeName == 'xs:complexType'?true:false) ;}
function is_simpletype_node($node){return ($node->nodeName == 'xs:simpleType'?true:false) ;}
function is_sequence_node($node){return ($node->nodeName == 'xs:sequence'?true:false) ;}
function is_restriction_node($node){return ($node->nodeName == 'xs:restriction'?true:false) ;}
function is_root_element($node){
	$parent = $node->parentNode->nodeName ;
	if(strpos($parent,'xs:schema') !== false){
		return true ;
	}else return false ;
}
function has_complextype($node){
	global $xpath ;
	if(!is_element_node($node) || !$node->hasChildNodes())return false ;
	
	$sub = $xpath->evaluate('*',$node)->item(0);
	return (is_complextype_node($sub));
	
}

function has_simpletype($node){return (is_simpletype_node($node->firstChild));}

function perform_schema($indent,$node){	
	global $xpath,$schema_prefix,$schema_tables ;
	if($node->nodeType == XML_ELEMENT_NODE){
		if(has_complextype($node)){
			if(is_root_element($node)){
				echo $indent.'ROOT ELEMENT : '.$node->getAttribute('name').'<br>';
			}else {
				echo $indent.'COMPLEX NODE '.$node->getAttribute('name').'<br>';
				array_push($schema_tables, $node->getAttribute('name'));
				$attrs = $node->attributes ;
				foreach ($attrs as $attr){
					//echo $indent.$node->getAttribute('name').' => '.$attr->name.' => '.$attr->value.'<br>' ;
				}		
			}
		}elseif(is_element_node($node)){
			echo $indent.'SIMPLE NODE : '.$node->getAttribute('name').'<br>';
		}
		
	}
	$sub_nodes = $xpath->evaluate('*',$node);
	foreach ($sub_nodes as $node){				
		perform_schema($indent.'&nbsp;&nbsp;',$node);
	}
}

$schema = $xpath->evaluate('/'.$schema_prefix.':*');
if($schema->length == 0)exit; // no schema !

//echo '<style>table{display:inline;}table,td{border:1px solid black;border-collapse:collapse;}</style>';
foreach ($schema as $node){
	perform_schema('',$node);
}
echo '=======================================<br>';
foreach ($schema_tables as $table){
	//echo 'creare tabella :'.$table.'<br>' ;
}
?>