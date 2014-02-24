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

class XSDParser {
	/*======================================================*/
	const SCHEMA 				= 'xs:schema' ;
	const ELEMENT 				= 'xs:element' ;
	const COMPLEX_TYPE 			= 'xs:complexType' ;
	const SIMPLE_TYPE 			= 'xs:simpleType' ;
	const ANY					= 'xs:any' ;
	const ANY_ATTRIBUTE			= 'xs:anyAttribute';
	/*======================================================*/
	// INDICATORS
	/*ORDER*/
	const SEQUENCE 				= 'xs:sequence' ;
	const ALL 					= 'xs:all' ;
	const CHOICE				= 'xs:choice' ;
	/*OCCURENCE*/
	const MAX_OCCURS			= 'maxOccurs' ;
	const MIN_OCCURS			= 'minOccurs' ;
	/*GROUP*/
	const GROUP					= 'xs:group' ;
	const ATTRIBUTE_GROUP		= 'xs:attributeGroup' ;
	/*======================================================*/
	const RESTRICTION 			= 'xs:restriction' ;
	const ANNOTATION 			= 'xs:annotation' ;
	const DOCUMENTATION 		= 'xs:documentation' ;
	const SIMPLE_CONTENT		= 'xs:simpleContent';
	const COMPLEX_CONTENT		= 'xs:complexContent';
	const EXTENSION				= 'xs:extension';
	/*======================================================*/
	//Constraining Facets
	const LENGTH				= 'xs:length';
	const MIN_LENGTH			= 'xs:minLength';
	const MAX_LENGTH			= 'xs:maxLength';		
	const PATTERN				= 'xs:pattern';
	const ENUMERATION 			= 'xs:enumeration' ;
	const WHITE_SPACE			= 'xs:whiteSpace';
	const MAX_INCLUSIVE			= 'xs:maxInclusive' ;
	const MAX_EXCLUSIVE			= 'xs:maxExclusive';
	const MIN_EXCLISIVE			= 'xs:minExclusive';
	const MIN_INCLUSIVE			= 'xs:minInclusive';
	const TOTAL_DIGITS			= 'xs:totalDigits';
	const FRACTION_DIGITS		= 'xs:fractionDigits';
	/*======================================================*/
	//DATA TYPES
	const DATA_INT				= 'xs:int';
	const DATA_FLOAT			= 'xs:float';
	const DATA_DOUBLE			= 'xs:double';
	const DATA_DECIMAL			= 'xs:decimal';
	const DATA_STRING			= 'xs:string';
	const DATA_BOOL				= 'xs:boolean';
	const DATA_DATE				= 'xs:date';
	const DATA_TIME				= 'xs:time';
	const DATA_DATE_TIME		= 'xs:dateTime';
	const DATA_DURATION			= 'xs:duration';
	const DATA_G_YEAR_MONTH		= 'xs:gYearMonth';
	const DATA_G_YEAR			= 'xs:gYear';
	const DATA_G_MONTH_DAY		= 'xs:gMonthDay';
	const DATA_G_DAY			= 'xs:gDay';
	const DATA_G_MONTH			= 'xs:gMonth';
	const DATA_HEX_BINARY		= 'xs:hexBinary';
	const DATA_BASE_64_BINARY	= 'xs:base64Binary';
	const DATA_ANY_URI			= 'xs:anyURI';
	const DATA_Q_NAME			= 'xs:QName';
	const DATA_NOTATION			= 'xs:NOTATION';
	/*======================================================*/
	const DATA_INTEGER			= 'xs:integer';
	const DATA_NEG_INT			= 'xs:negativeInteger';
	const DATA_NON_NEG_INT 		= 'xs:nonNegativeInteger';
	const DATA_POS_INT			= 'xs:positiveInteger';
	const DATA_NON_POS_INT		= 'xs:nonPositiveInteger';
	const DATA_SHORT			= 'xs:short';
	const DATA_LONG				= 'xs:long';
	const DATA_BYTE				= 'xs:byte';
	const DATA_UNSIGNED_BYTE	= 'xs:unsignedByte';
	const DATA_UNSIGNED_SHORT	= 'xs:unsignedShort';
	const DATA_UNSIGNED_LONG	= 'xs:unsignedLong';
	const DATA_UNSIGNED_INT		= 'xs:unsignedInt';
	const DATA_NAME				= 'xs:Name';
	/*======================================================*/
	const STANDARD_SCHEMA_PREFIX = 'xs';
	/*======================================================*/
	const ERROR_RET_VALUE = -1 ;
	/*======================================================*/
	private $Document ;
	private $XPath ;
	private $schema_prefix = self::STANDARD_SCHEMA_PREFIX ;
	private $schema_uri = 'http://www.w3.org/2001/XMLSchema';
	private $schema_nodelist,$schema_node ;
	private $schema_defined_url ;
	private $iterator_array = array();
	private $error = 0 ;
	
	/*======================================================*/
	public function __construct($url_schema){
		$this->schema_defined_url = $url_schema ;
		$this->Document = new DOMDocument();
		$this->Document->load($url_schema);
		$this->XPath = new DOMXPath($this->Document);
		$this->XPath->registerNamespace($this->schema_prefix, $this->schema_uri);
		$error = $this->prepare();
	}
	/*======================================================*/
	public function get_error(){
		return $this->error;
	}
	/*======================================================*/
	private function regiserNamespace($prefix,$uri){
		$this->XPath->registerNamespace($prefix, $uri);
	}
	/*======================================================*/
	public function set_schema_prefix($prefix){
		$this->schema_prefix = $prefix ;
		$this->regiserNamespace($prefix, $this->schema_uri);
	}
	/*======================================================*/
	public function set_schema_uri($uri){
		$this->schema_uri = $uri ;
		$this->regiserNamespace($this->schema_prefix, $uri);
	}
	/*======================================================*/
	public function get_defined_schema_url(){
		return $this->schema_defined_url;
	}
	/*======================================================*/
	public function prepare(){
		$this->schema_nodelist = $this->XPath->evaluate('/'.$this->schema_prefix.':schema');
		if($this->schema_nodelist->length == 0)return self::ERROR_RET_VALUE;
		$this->schema_node = $this->schema_nodelist->item(0);
	}
	/*======================================================*/
	public function get_schema_attribute($attr_name){
		return $this->schema_node->getAttribute($attr_name);
	}
	/*======================================================*/
	public function get_xpath(){
		return $this->XPath;
	}
	/*======================================================*/
	private function is_this_node($node,$node_name){
		if($node instanceof DOMNode && $node->nodeType == XML_ELEMENT_NODE){
			return (
				($node->nodeName == str_replace(self::STANDARD_SCHEMA_PREFIX, $this->schema_prefix, $node_name))
				 ? true:false) ;
		}else return false;
	}
	/*======================================================*/
	public function is_valid_node($obj){
		if($obj instanceof DOMNode && $obj->nodeType == XML_ELEMENT_NODE){
			return true ;
		}else return false ;
	}
	public function is_element_node($node){
		return $this->is_this_node($node, self::ELEMENT);
	}
	public function is_complextype_node($node){
		return $this->is_this_node($node, self::COMPLEX_TYPE);
	}
	public function is_simpletype_node($node){
		return $this->is_this_node($node, self::SIMPLE_TYPE);
	}
	public function is_sequence_node($node){
		return $this->is_this_node($node, self::SEQUENCE);
	}
	public function is_restriction_node($node){
		return $this->is_this_node($node, self::RESTRICTION);
	}
	public function has_complextype($node){
		if(!$this->is_this_node($node, self::ELEMENT) || !$node->hasChildNodes())return false;
		$sub_node = $this->XPath->evaluate('*',$node)->item(0);
		return $this->is_complextype_node($sub_node);
	}
	public function has_simpletype($node){
		if(!$this->is_this_node($node, self::ELEMENT) || !$node->hasChildNodes())return false;
		$sub_node = $this->XPath->evaluate('*',$node)->item(0);
		return $this->is_simpletype_node($sub_node);
	}
	public function has_sequence($node){
		if(
			!$this->is_this_node($node, self::COMPLEX_TYPE) ||
			/*!$this->is_this_node($node, self::GROUP) ||
			!$this->is_this_node($node, self::CHOICE) ||
			!$this->is_this_node($node, self::SEQUENCE) ||
			!$this->is_this_node($node, self::RESTRICTION) ||
			!$this->is_this_node($node, self::EXTENSION) ||
			!$this->is_this_node($node, self::SIMPLE_CONTENT) ||
			!$this->is_this_node($node, self::COMPLEX_CONTENT)||*/
			
			!$node->hasChildNodes())return false;
		
		$sub_node = $this->XPath->evaluate('*',$node)->item(0);
		return $this->is_sequence_node($sub_node);
	}
	public function get_complextype_sequence($node){
		if($this->has_sequence($node)){
			$sequence = $this->XPath->evaluate(
				str_replace(self::STANDARD_SCHEMA_PREFIX, $this->schema_prefix, self::SEQUENCE).'/*',
				$node
			);
			return $sequence ;
		}else return self::ERROR_RET_VALUE;
	}
	public function get_schema_nodelist(){
		return $this->schema_nodelist ;
	}
	public function get_nodelist($node){
		return $this->XPath->evaluate($this->schema_prefix.':*',$node);
	}
	public function get_next_node($node){
		return $this->get_nodelist($node)->item(0);
	}
	public function get_n_node($node,$number){
		$ret_node = $node ;
		for($i=0;$i<$number;$i++){
			$ret_node = $this->get_next_node($ret_node);
		}
		return $ret_node ;
	}
	public function has_next_node($node){
		return (is_null($this->get_next_node($node)));
	}
	
	private function recursive_iterator($node){
		if($node->nodeType == XML_ELEMENT_NODE){
			array_push($this->iterator_array,$node);
		}
		$list = $this->get_nodelist($node);
		foreach ($list as $i){			
			$this->recursive_iterator($i);
		}
		
	}
	public function get_iterator($node = null){
		$node = is_null($node)?$this->schema_node:$node ;
		$this->iterator_array = array();
		$list = $this->get_nodelist($node);
		foreach($list as $i){
			$this->recursive_iterator($i);
		}
		return new ArrayIterator($this->iterator_array) ;
	}
}

?>