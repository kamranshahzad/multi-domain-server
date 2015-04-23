<?php

	class Sitemap{
			
			public $dom;
			public $fileAsset = '';
			
			public function __construct($filename='Sitemap.xml') {
				if(file_exists($filename)){
					$this->fileAsset = $filename;
				}else{
					throw new Exception("No Sitemap.xml file found.");	
				}
			}	
			
			public function load(){
				
				$this->generate();
				
				$this->dom = new DOMDocument;
				$this->dom->preserveWhiteSpace = true;
				$this->dom->loadXML(file_get_contents($this->fileAsset));
			}
			
			public function generate(){
				
				global $urlset;
				$this->dom = new DOMDocument();
				$this->dom->loadXML('<?xml version="1.0" encoding="UTF-8"?>
				
				<urlset
				xml:ns="http://www.sitemaps.org/schemas/sitemap/0.9"
				xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
				http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"> </urlset>
				');
				$this->dom->save($this->fileAsset);
			}
			 
			public function addrow($vars){
				
				global $urlset;
				if(!isset($urlset))
				$urlset = $this->dom->getElementsByTagName('urlset')->item(0);
				$node = $this->dom->createElement('url');
				$node->setAttribute('id',md5($vars['loc']));
				foreach($vars as $key => $var)
				{
				$node2 = $this->dom->createElement($key);
				$node3 = $this->dom->createTextNode($var);
				$node2->appendChild($node3);
				$node->appendChild($node2);
				}
				$newnode = $urlset->appendChild($node);
				$vars['md5'] = md5($vars['loc']);
			}
			 
			public function editrow($id){
				
				$xpath = new DOMXPath($this->dom);
				$mod = $xpath->query("/urlset/url[@id='$id']/lastmod");
				$mod->item(0)->nodeValue = strftime("%Y-%m-%d",time() - gmmktime() + mktime());
				$this->dom->save($this->fileAsset);
			}
			 
			public function deleterow($id){
				
				$xpath = new DOMXPath($this->dom);
				$urlset = $this->dom->getElementsByTagName('urlset')->item(0);
				$row = $xpath->query("/urlset/url[@id='$id']")->item(0);
				if($row)
				$row->parentNode->removeChild($row);
				$this->dom->save($this->fileAsset);
			}
			
			
			public function refresh(){
								$xpath = new DOMXPath($this->dom);
				$row = $xpath->query("/urlset/");
				foreach($row as $element){
    				$element->parentNode->removeChild($element); 
				}
				$this->dom->save($this->fileAsset);
				
			}
			
}