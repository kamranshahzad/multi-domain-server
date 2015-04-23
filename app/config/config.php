<?php


class config{
	
	const ENV               = 'development'; // development, production
	const EMAIL_DEBUG       = false;
	const LOCAL_DOMAIN		= 'mlserver';
	const LIVE_DOMAIN		= 'LIVE_DOMAIN_FULL_URL';    
	const SITE_NAME			= 'SITE_TITLE';
	
	
	public $restrictedPages = array('index','home','news','page preview','contents');
	
	public $registeredPages = array('index.php','media.php','news.php','gallery.php');
	
	private $dbConfig		= array(
								'development'=>array('domain'=>'localhost' ,'dsn'=>array('host'=>'localhost','dbname'=>'mlserver','username'=>'user','password'=>'pass')) ,
								'production'=>array('domain'=>'localhost' ,'dsn'=>array('host'=>'localhost','dbname'=>'mediaus_server','username'=>'user','password'=>'pass'))
							);
							
	public $debugConfig	= array(
								'debugemails'=>array('admin_email_address'),
								'errors'=>'',
							);
							
	public $mediaConfig	= array(
								'allowimgtypes' =>  array( 'image/png', 'image/jpeg', 'image/jpeg', 'image/jpeg', 'image/gif'),
								'gridimage'=> array('size'=>array( 'width'=>140,'height'=>70 )), 
								'companylogo'=>array('size'=>array( 'width'=>274,'height'=>45 ) , 'dir'=>'/'),
								'banner'=>array('size'=>array( 'width'=>957,'height'=>300 )),
								'gallery'=>array('size'=>array( 'width'=>215, 'height'=>215)),
								'adminimgs' => 'public/images/'
							);
							
	private $scriptConfig	= array();
	
	private $stylesConfig	= array();
	
	private $mediaDirTree   = array('media',
									'media/banner','media/banner/grid','media/banner/large','media/banner/raw','media/banner/thumbs',
									'media/block','media/block/large','media/block/thumbs',
									'media/news','media/news/large' ,'media/news/thumbs',
									'media/portfolio','media/portfolio/grid','media/portfolio/large','media/portfolio/raw','media/portfolio/thumbs',
									'media/tmp'
									);	
	
	private $vendersConfig =  array('jquery'=>array('js'=>array('jquery/jquery.min')),
									'timepicker'=>array('js'=>array('jquery/jquery-ui-timepicker')),
									'editor'=>array('js'=>array('editor/ckeditor/ckeditor','editor/ckfinder/ckfinder')),
									'tooltip'=>array('js'=>array('tooltip/jquery.tooltip'),'css'=>array('tooltip/theme.tooltip')),
									'fancybox'=>array('js'=>array('fancybox/jquery.fancybox') , 'css'=>array('fancybox/jquery.fancybox')),
									'colorbox'=>array('js'=>array('colorbox/jquery.colorbox-min') , 'css'=>array('colorbox/colorbox')),
									'lightbox'=>array('js'=>array('lightbox/jquery.lightbox-0.5') , 'css'=>array('lightbox/jquery.lightbox-0.5'))
									);
	
	private $siteImages    = '/public/siteimages/';
	private $admImages     = '/public/images/';
						

	function __construct(){
	}
	
	public function getDbConfig(){
		return 	(self::ENV == 'development') ? $this->dbConfig['development'] : $this->dbConfig['production'];
	}
	
	public static function Db(){
					
	}
	
	public function getBasePath(){
		return 	(self::ENV == 'development') ? 'http://localhost/'.self::LOCAL_DOMAIN : 'http://www.'.self::LIVE_DOMAIN;	
	}
	
	public function getVender($vender){
		if(array_key_exists($vender, $this->vendersConfig)){
			return $this->vendersConfig[$vender];
		}
	}
	
	public function getSiteImages(){
		return $this->siteImages;	
	}
	
	
	public function getImages(){
		return $this->admImages;	
	}
	
	public function getMediaDirectoryTree(){
		return $this->mediaDirTree;	
	}
	
	
	
}//$






?>