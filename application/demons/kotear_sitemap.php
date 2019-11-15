<?php
define ('APPLICATION_ENV','production');
class SiteMap
{ 
  public $sitemapFileName = "sitemap.xml";
  public $sitemapIndexFileName = "sitemap-index.xml";
  public $robotsFileName = "robots.txt";
  //public $maxURLsPerSitemap = 50000;
  //public $createGZipFile = false;  
  private $_baseUrl;    
  private $_basePath;
  private $_searchEngines = array(
                                  array("http://search.yahooapis.com/SiteExplorerService/V1/updateNotification?appid=USERID&url=",
                                        "http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap="),
                                  "http://www.google.com/webmasters/tools/ping?sitemap=",
                                  "http://submissions.ask.com/ping?sitemap=",
                                  "http://www.bing.com/webmaster/ping.aspx?siteMap="
                                  );
  private $_fileSitemap;
  private $_fileSitemapIndex;
  public function SiteMap ($baseUrl, $basePath = '')
  { 
    $this->_baseUrl= $baseUrl;
    $this->_basePath = $basePath;        
  } //end function __constructor

  public function init ()
  { 
    $this->_openFileSitemapIndex();
    $this->_openFileSitemap();
    fwrite($this->_fileSitemapIndex, "<sitemap><loc>{$this->_baseUrl}/{$this->sitemapFileName}</loc></sitemap>");
  } //end function init
  public function done ()
  { 
    $this->_closeFileSitemapIndex();
    $this->_closeFileSitemap();
  } //end function done
  
  function addUrl ($url, $lastModified = null, $changeFrequency = null, $priority = null)
  { 
    fwrite($this->_fileSitemap, "<url>\n"
           . "\t<loc>".htmlspecialchars($url,ENT_QUOTES,'UTF-8')."</loc>\n" 
           . (($lastModified)?"\t<lastmod>$lastModified</lastmod>\n":'') 
           . (($changeFrequency)?"\t<changefreq>$changeFrequency</changefreq>\n":'') 
           . (($priority)?"\t<priority>$priority</priority>":'') 
           . "</url>\n");
  } //end function addUrl
  protected function _openFileSitemapIndex ()
  { 
    $this->_fileSitemapIndex = fopen($this->_basePath . $this->sitemapIndexFileName,'w');
    $sitemapIndexHeader = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
      . '<sitemapindex '."\n"
      . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n"
      . 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' . "\n" 
      . 'http://www.sitemaps.org/schemas/sitemap/0.9/siteindex.xsd"' . "\n" 
      . 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
    fwrite($this->_fileSitemapIndex, $sitemapIndexHeader);
  } //end function initFileSitemapIndex
  protected function _closeFileSitemapIndex ()
  { 
    fwrite($this->_fileSitemapIndex, "\n</sitemapindex>\n");
    fclose($this->_fileSitemapIndex);
  } //end function _closeFileSitemapIndex
  public function addSitemap ($filename)
  { 
    if ( $this->_fileSitemap ){  
      $this->_closeFileSitemap();
    } //end if $this->_fileSitemap
    fwrite($this->_fileSitemapIndex, "\n<sitemap><loc>{$this->_baseUrl}/$filename</loc></sitemap>");
    $this->sitemapFileName = $filename;
    $this->_openFileSitemap();
    return true;
  } //end function addSitemap
  
  protected function _openFileSitemap ()
  { 
    $sitemapHeader = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" 
      . '<urlset' ."\n" 
      . 'xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n" 
      . 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' . "\n" 
      . 'http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"' . "\n" 
      . 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

    $this->_fileSitemap = fopen($this->_basePath . $this->sitemapFileName,'w');
    fwrite($this->_fileSitemap, $sitemapHeader);
  } //end function initFileSitemap

  protected function _closeFileSitemap ()
  { 
    fwrite($this->_fileSitemap,"\n</urlset>\n");
    fclose($this->_fileSitemap);
  } //end function _closeFileSitemap  

  public function send($yahooAppId = null) {
    if (!extension_loaded('curl'))
      throw new BadMethodCallException("cURL library is needed to do submission.");
    $sitemapFullURL = $this->_baseUrl.'/'.$this->sitemapIndexFileName;
    $searchEngines = $this->_searchEngines;
    $searchEngines[0] = isset($yahooAppId) ? str_replace("USERID", $yahooAppId, $searchEngines[0][0]) : $searchEngines[0][1];
    $result = array();
    for($i=0;$i<sizeof($searchEngines);$i++) {
      $submitSite = curl_init($searchEngines[$i].htmlspecialchars($sitemapFullURL,ENT_QUOTES,'UTF-8'));
// curl_setopt($submitSite, CURLOPT_HTTPPROXYTUNNEL, 1); 
// curl_setopt($submitSite, CURLOPT_PROXYPORT, 9090); 
// curl_setopt($submitSite, CURLOPT_PROXY, 'http://172.19.0.4:9090'); 
// curl_setopt($submitSite, CURLOPT_PROXYUSERPWD, 'pacmamhe:draco12'); 
      curl_setopt($submitSite, CURLOPT_RETURNTRANSFER, true);
      $responseContent = curl_exec($submitSite);
      $response = curl_getinfo($submitSite);
      $submitSiteShort = array_reverse(explode(".",parse_url($searchEngines[$i], PHP_URL_HOST)));
      $result[] = array("site" => $submitSiteShort[1].".".$submitSiteShort[0],
                        "fullsite "=> $searchEngines[$i].htmlspecialchars($sitemapFullURL, ENT_QUOTES,'UTF-8'),
                        "http_code" => $response['http_code'],
                        "message" => str_replace("\n", " ", strip_tags($responseContent)));
    }
    return $result;
  }

  public function updateRobots() {
    $sampleRobotsFile = "User-agent: *\nAllow: /";
    $sitemapFullURL = $this->_baseUrl.'/'.$this->sitemapIndexFileName;
    if (file_exists($this->_basePath . $this->robotsFileName)) {
      $robotsFile = explode("\n", file_get_contents($this->_basePath . $this->robotsFileName));
      $robotsFileContent = "";
      foreach($robotsFile as $key=>$value) {
        if(substr($value, 0, 8) == 'Sitemap:') unset($robotsFile[$key]);
        else $robotsFileContent .= $value."\n";
      }
      $robotsFileContent .= "Sitemap: $sitemapFullURL";
      file_put_contents($this->_basePath . $this->robotsFileName,$robotsFileContent);
    }
    else {
      $sampleRobotsFile = $sampleRobotsFile."\n\nSitemap: ".$sitemapFullURL;
      file_put_contents($this->_basePath . $this->robotsFileName, $sampleRobotsFile);
    }
  }
} //end class SiteMap


defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/..'));
require_once('Zend/Loader/Autoloader.php');
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);
$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV); $config = $config->toArray();
$dbConfig = $config['resources']['multidb']['db'];
$db = Zend_Db::factory($dbConfig['adapter'], $dbConfig);
$baseUrl = $config['app']['url'];

$time = explode(" ",microtime());
$time = $time[1];
$sm = new SiteMap($baseUrl, APPLICATION_PATH .'/../public/');
//$sm = new SiteMap('http://kotear.pagos', APPLICATION_PATH .'/../public/');
$maxPerFile = 20000;
$avisoSitemapCount = 1;
$usuarioSitemapCount = 1;
$avisoCount = 0;
$usuarioCount = 0;
$sm->sitemapIndexFileName = 'sitemap_index.xml' ;
$sm->sitemapFileName = 'avisos'.str_pad($avisoSitemapCount,2,'0',STR_PAD_LEFT).'_sitemap.xml';
$sm->init();

$maxId = $db->fetchOne('select max(AVI.ID_AVISO) from KO_AVISO AVI INNER JOIN KO_USUARIO_PORTAL UPO ON AVI.ID_USR=UPO.ID_USR 
      AND UPO.ID_ESTADO_USUARIO IN (2,4) AND AVI.EST IN (1,5)');
//$maxId = 100;
while ($maxId>0){
  $aviso = $db->fetchOne("select convert(varchar,AVI.ID_AVISO)+'-'+AVI.URL from 
      KO_AVISO AVI INNER JOIN KO_USUARIO_PORTAL UPO ON AVI.ID_USR=UPO.ID_USR 
      AND UPO.ID_ESTADO_USUARIO IN (2,4) AND AVI.ID_AVISO = ? AND AVI.EST IN (1,5)", array($maxId));
  if ($aviso!=null) {    
    $sm->addUrl($baseUrl.'/aviso/'.$aviso);      
    //$sm->addUrl('http://kotear.pagos'.'/aviso/'.$aviso);  
    $avisoCount++; //Añadimos avisos revisados
    if ($avisoCount>$maxPerFile){
      $avisoCount = 0;
      $avisoSitemapCount++;
      $sm->addSitemap('avisos'.str_pad($avisoSitemapCount,2,'0',STR_PAD_LEFT).'_sitemap.xml');  
    }
  }
  $maxId--;
 }
$maxId = $db->fetchOne('select max(ID_USR) from KO_USUARIO_PORTAL where ID_ESTADO_USUARIO in (2,4)');
//$maxId = 100;
$sm->addSitemap('usuarios'.str_pad($usuarioSitemapCount,2,'0',STR_PAD_LEFT).'_sitemap.xml');
while($maxId>0){
  // Solo se selecciona los usuario que estan ACTIVOS O SUSPENDIDOS  
  $usuario = $db->fetchOne("select ID_USR from KO_USUARIO_PORTAL where ID_USR=? and ID_ESTADO_USUARIO in (2,4)", array($maxId));
  if ($usuario!=null) {    
    $sm->addUrl($baseUrl.'/usuario/reputacion/ver/cod/'.$usuario);
    //$sm->addUrl('http://kotear.pagos'.'/usuario/reputacion/ver/cod/'.$usuario);
    $usuarioCount++;
    if ($usuarioCount>$maxPerFile){
      $usuarioCount = 0;
      $usuarioSitemapCount++;
      $sm->addSitemap('usuarios'.str_pad($usuarioSitemapCount,2,'0',STR_PAD_LEFT).'_sitemap.xml');
    }  
  }
  $maxId--;
 }
$sm->updateRobots();
$result = $sm->send();
print_r($result);
$sm->done();

echo "\nUso de memoria: ".number_format(memory_get_peak_usage()/(1024*1024),2)."MB\n";
$time2 = explode(" ",microtime());
$time2 = $time2[1];
echo "\nTiempo de ejecución: ".number_format($time2-$time)."s\n";


die();

