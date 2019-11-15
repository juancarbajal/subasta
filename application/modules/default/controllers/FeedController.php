<?php
/**
 * Descripción Corta
 *
 * Descripción Larga
 *
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer el archivo LICENSE
 * @version    1.0
 * @since      Archivo disponible desde su version 1.0
 */

/**
 * Descripción Corta
 * Descripción Larga
 * @category
 * @package
 * @subpackage
 * @copyright  Leer archivo COPYRIGHT
 * @license    Leer archivo LICENSE
 * @version    Release: @package_version@
 * @link
 */
class FeedController
    extends Devnet_Controller_Action_Default
{

    public function busquedaAction()
    {
        $this->_helper->layout->setLayout('clear');
        require_once 'Prueba.php';
        require_once 'Zend/Feed.php';
        $avisos = new Prueba();
        $select = $avisos->getAvisos(1);
        //Create an array for our feed
        /*$feed = array();
        foreach ($select as $r) :
            $entry = array(); //Container for the entry before we add it on
            $entry['title'] 	= $r->TIT; //The title that will be displayed for the entry
            $feed['entries'][] 	= $entry;
        endforeach;
        var_dump($feed['entries']);*/
        //Create an array for our feed
        $feed = array();

        //Setup some info about our feed
        $feed['title']        	= "Prueba de rss";
        $feed['link']         	= 'http://192.168.1.95/rss/rss.rss';
        $feed['charset']   	= 'utf-8';
        $feed['language'] 	= 'en-es';
        $feed['published'] 	= time();
        $feed['entries']   	= array();//Holds the actual items

        //Loop through the stories, adding them to the entries array
        foreach ($select as $story) {
                $entry = array(); //Container for the entry before we add it on

                $entry['title'] 	= $story->TIT; //The title that will be displayed for the entry
                echo $entry['title'];
                /*$entry['link'] 		= $story->URL; //The url of the entry

                $entry['description'] 	= $story->SUBTIT; //Short description of the entry

                $entry['content'] 	= $story->PRECIO; //Long description of the entry

                //Some optional entries, usually the more info you can provide, the better
                $entry['lastUpdate'] 	= $story->modified; //Unix timestamp of the last modified date

                $entry['comments'] 	= $story->commentsUrl; //Url to the comments page of the entry

                $entry['commentsRss'] 	= $story->commentsRssUrl; //Url of the comments pages rss feed*/

                $feed['entries'][] 	= $entry;
        }
        $feedObj = Zend_Feed::importArray($feed, 'rss');
        //$feedObj = Zend_Feed::importArray($feed['entries'], 'rss'); //Or importArray($feed, 'atom');
        //Return the feed as a string, we're not ready to output yet
        print $feedString = $feedObj->saveXML();
        var_dump($feedString);
        //Or we can output the whole thing, headers and all, with
        $feedObj->send();
    }

    public function pruebas1Action()
    {
        /*$format = $this->getRequest()->getParam('format');
        $format = in_array($format, array('rss', 'atom')) ? $format : 'rss';*/
        $format = 'rss';

        require_once 'Prueba.php';
        require_once 'Zend/Feed.php';
        $avisos = new Prueba();
        $rowset = $avisos->getAvisos(1);

        $channel = array(
            'title'       => 'Places',
            'link'        => 'http://places/',
            'description' => 'All the latest articles',
            'charset'     => 'UTF-8',
            'entries'     => array()
        );

        foreach ($rowset as $item) {
            $channel['entries'][] = array(
                'title'       => $item->TIT,
                'link'        => 'http://places/article/index/id/' . $item->ID_AVISO . '/',
                'description' => $item->TAG
                );
        }
        var_dump($channel);
        //$feed = Zend_Feed::importArray($channel, $format);
       // header('Content-Type: text/xml; charset=UTF-8');
        echo $feed->saveXML();
        //$feed->send;
        /*$this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();*/
    }

    public function pruebasAction()
    {
        /*$format = $this->getRequest()->getParam('format');
        $format = in_array($format, array('rss', 'atom')) ? $format : 'rss';*/
        $format = 'rss';

        require_once 'Prueba.php';
        require_once 'Zend/Feed.php';
        $feed = Zend_Feed::import('http://elcomercio.pe/feed/portada/mundo.xml');
        header('Content-Type: text/xml; charset=UTF-8');
        echo $feed->saveXML();
        $feed->send;
        /*$this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();*/
    }
}