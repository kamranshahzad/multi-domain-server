<?php

	class Domains extends Model {
		
		const _TABLE = 'system_domains';
		private $_db = NULL;
			
		public function __construct() {}	

		public function initDb(){
			$configObj 	= new config();
			$_dbinfo 	= $configObj->getDbConfig();
			try {
				$this->_db = new Pdodb($_dbinfo);
			}catch(PDOException $e) {  
				echo $e->getMessage();  
			}
		}
		
		public function dispose(){
			$this->_db = null;
		}
		
		
		/*
			_Helper functions
		*/
		
		
		public function fetchById($id){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "domain_id='$id'");
			$this->dispose();
			if(count($dataArray) > 0){
				return $dataArray[0];
			}
		}
		
		public function fetchDomain(){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "domain_id IS NOT NULL ORDER BY domain_id DESC");
			$this->dispose();
			if(count($dataArray) > 0){
				$tmpArray = array();
				foreach($dataArray as $array){
					$tmpArray[$array['domain_id']] = $array['domain_url'];
				}
				return $tmpArray;
			}
		}
		
		public function fetchDomainFTP($DOMAIN_ID){
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE, "domain_id='$DOMAIN_ID'");
			$this->dispose();
			if(count($dataArray) > 0){
				return array('ftphost'=>$dataArray[0]['domain_url'] , 'ftpusername'=>$dataArray[0]['ftp_username'] , 'ftppassword'=>$dataArray[0]['ftp_password'] );	
			}
		}
		
		
		
		/*
			_htmls
		*/
		public function drawGrid(){
			$htmlString = '';
			$this->initDb();
			$dataArray = $this->_db->select(self::_TABLE ,"domain_id IS NOT NULL order by domain_id DESC");
			$this->dispose();
			$pointer = 1;
			
			$totalDomain = count($dataArray);
			
			if($totalDomain > 0){
				$htmlString .= $this->gridHeader();
				foreach($dataArray as $array){
					$class = 'even';
					if($pointer%2 == 0){
						$class = 'odd';	
					}
					
					$statusLink = ($array['access_enable'] == 'Y') ? '<span class="enabled">'.Link::Action('Domains', 'disable' , 'Yes' , array('did'=>$array['domain_id']) , "Are you sure you want to disable selected domain?").'</span>' : '<span class="disenabled">'.Link::Action('Domains', 'enable' , 'No' , array('did'=>$array['domain_id']) , "Are you sure you want to active selected domain?").'</span>';
					
					$htmlString .= '<tr>';
					$htmlString .= '<td class="'.$class.'"  align="center">'.$pointer.'</td>';
					$htmlString .= '<td class="'.$class.'" >'.$array['domain_url'].'</td>';
					$htmlString .= '<td class="'.$class.'" >'.$array['cp_username'].'</td>';
					$htmlString .= '<td class="'.$class.'"  >'.$array['ftp_username'].'</td>';
					$htmlString .= '<td class="'.$class.'"  align="center">'.$statusLink.'</td>';
					$htmlString .= '<td class="'.$class.'"  >'.DateUtil::format($array['date_modified']) .'</td>';
					$htmlString .= '<td class="'.$class.'"  >'.DateUtil::format($array['date_created']) .'</td>';
					$htmlString .= '<td class="'.$class.'"  align="center">
									<span>
									<a href="manage-domains.php?q=modify&did='.$array['domain_id'].'">modify</a>
									</span>
									&nbsp;&nbsp;|
									<span class="removeredlink">
									'.Link::Action('Domains', 'remove' , 'remove' , array('did'=>$array['domain_id']), "Are you sure you want to remove selected domain?").'
									</a>
									</td>';
					$htmlString .= '</tr>';
					$pointer++;	
				}
				$htmlString .= '</table>';
				$htmlString .= '<br/><div class="totalGridRecords round"><strong>'.$totalDomain.'</strong> domains found.</div>';
			}else{
				$htmlString .= '<div class="totalGridRecords round">No domain found.</div>';	
			}
			
			return $htmlString;	
		}
		public function gridHeader(){
			$htmlString = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td class="head" width="30" align="center">#</td>
								<td class="head" width="200">&nbsp;&nbsp;Domain Url</td>
								<td class="head" width="200">&nbsp;&nbsp;CP Username </td>
								<td class="head" width="160">&nbsp;&nbsp;FTP Username</td>
								<td class="head" width="100" align="center">Enable Access?</td>
								<td class="head" width="100">&nbsp;&nbsp;Date Modified</td>
								<td class="head" width="100">&nbsp;&nbsp;Date Created</td>
								<td class="head" width="150" align="center">Actions</td>
							</tr>';
			return $htmlString;	
		}
		
		
		
				
		
	}  // $
