<?php
class Servicio_FeedController extends Devnet_Controller_Action
{ 
  function indexAction ()
  { 
    if (!($result = $this->cache->load('servicio_feed'))){
      /****************************************************************************************************/
      $type = 'rss';
      $author = array(
                      'name'  => 'Comercio',
                      'email' => 'webmaster@kotear.pe',
                      'uri'   => 'http://www.kotear.pe',
                      );
      $mod = time();
      $frontController = Zend_Controller_Front::getInstance();
      $app = $frontController->getParam('bootstrap')->getOption('app');
      $url = $app['url'];
      $entries = array(
                       array('title' => 'Busqueda',
                             'link' => $url.'/busqueda/?q=',
                             'description' => 'Busquedas',
                             'content' => 'Busquedas'),
                       array('title' => 'Categorias',
                             'link' => $url.'/busqueda/categoria',
                             'description' => 'Busquedas',
                             'content' => 'Busquedas'),
                       array('title' => 'Consolas',
                             'link' => $url.'/busqueda/?categs=208',
                             'description' => 'Consolas',
                             'content' => 'Consolas'),
                       array('title' => 'Cámaras',
                             'link' => $url.'/busqueda/?categs=229',
                             'description' => 'Cámaras',
                             'content' => 'Cámaras'),
                       array('title' => 'Carteras',
                             'link' => $url.'/busqueda/?categs=3042',
                             'description' => 'Carteras',
                             'content' => 'Carteras'),
                       array('title' => 'Perfumes',
                             'link' => $url.'/busqueda/?categs=436',
                             'description' => 'Perfumes',
                             'content' => 'Perfumes'),
                       array('title' => 'Ropa',
                             'link' => $url.'/busqueda/?categs=231',
                             'description' => 'Ropa',
                             'content' => 'Ropa'),
                       array('title' => 'Ipods y Mp3',
                             'link' => $url.'/busqueda/?categs=1381',
                             'description' => 'Ipods y Mp3',
                             'content' => 'Ipods y Mp3'),
                       array('title' => 'Laptops',
                             'link' => $url.'/busqueda/?categs=277',
                             'description' => 'Laptops',
                             'content' => 'Laptops'),
                       array('title' => 'Celulares',
                             'link' => $url.'/busqueda/?categs=448',
                             'description' => 'Celulares',
                             'content' => 'Celulares'),
                       array('title' => 'Memorias',
                             'link' => $url.'/busqueda/?categs=2355',
                             'description' => 'Memorias',
                             'content' => 'Memorias')
                       );
      /****************************************************************************************************/

      $feed = new Zend_Feed_Writer_Feed();
      $feed->setTitle('Kotear.pe');
      $feed->setDescription('Kotear.pe');
      $feed->setLink($url);
      $feed->setFeedLink($url.'/servicio/feed', $type);
      $feed->addAuthor($author);
      $feed->setDateModified($mod);
      foreach($entries as $e){
        $entry = $feed->createEntry();
        $entry->setTitle($e['title']);
        $entry->setLink($e['link']);
        $entry->addAuthor($author);
        $entry->setDateModified($mod);
        $entry->setDateCreated($mod);
        $entry->setDescription($e['description']);
        $entry->setContent($e['content']);
        $feed->addEntry($entry);
      }
      $result = $feed->export($type);
      $this->cache->save($result, 'servicio_feed');
      }
    echo $result;die();
  } //end function indexAction
  
  } //end class Servicio_FeedController