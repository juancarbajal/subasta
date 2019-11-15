<?php
/**
 * Sitemap class file
 *
 * PHP Version 5.3
 *
 * @category PHP
 * @package  Admin
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/admin/auth
 */

/**
 * Sitemap class
 *
 * The class holding the root Recipe class definition
 *
 * @category PHP
 * @package  Admin
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/admin/auth
 */
class SitemapController extends App_Controller_Action
{
    private $_xmlns = "http://www.sitemaps.org/schemas/sitemap/0.9";
    private $_xmlns_image = "http://www.google.com/schemas/sitemap-image/1.1";

    /**
     * Descripcion
     * 
     * @return void
     */
    public function init()
    {
        parent::init();
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    
    public function indexAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        $mSitemapCategoria = new App_Sitemap_Categoria();
        $sitemapItems = $mSitemapCategoria->listarUrl();
        
        $urlset = new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="'.$this->_xmlns.'" />'
        );
        
        foreach ($sitemapItems as $val) {
            $url = $urlset->addChild('sitemap');
            $url->addChild('loc', URL_SITE . '/' . $val["loc"] . '.xml');
        }
        
        $dom = new DomDocument();
        $dom->loadXML($urlset->asXML());
        $dom->formatOutput = true;
        $output = $dom->saveXML();
        
        $this->_response->setHeader('Content-Type', 'text/xml; charset=utf-8')->setBody($output);
    }
    
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function categoriaAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        $mSitemapCategoria = new App_Sitemap_Categoria();
        $sitemapItems = $mSitemapCategoria->listarUrl();
        
        $idCategoria = $this->_getParam('idCategoria', '');
        
        $urlset = new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="'.$this->_xmlns.'" />'
        );
        
        $url = $urlset->addChild('sitemap');
        $url->addChild('loc', URL_SITE . '/' . $sitemapItems[$idCategoria]["loc"] . '_normal.xml');
        $url = $urlset->addChild('sitemap');
        $url->addChild('loc', URL_SITE . '/' . $sitemapItems[$idCategoria]["loc"] . '_images.xml');
        
        $dom = new DomDocument();
        $dom->loadXML($urlset->asXML());
        $dom->formatOutput = true;
        $output = $dom->saveXML();
        
        $this->_response->setHeader('Content-Type', 'text/xml; charset=utf-8')->setBody($output);
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function categoriaAvisoAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        
        $mAviso = new Application_Model_Sp_Aviso();
        $zNavigation = new Zend_Navigation();
        
        $idCategoria = $this->_getParam('idCategoria', '');
        
        $sitemapItems = $mAviso->getSitemapByIdCategoria($idCategoria);
        
        foreach ($sitemapItems as $item) {
            $newSite = new Zend_Navigation_Page_Uri();
            //$newSite->uri = 'http://' . $_SERVER['HTTP_HOST'] . $item->getSpeakingUrl();
            $newSite->uri = URL_SITE . '/aviso/' . $item->K_URL;
            $newSite->lastmod = date("c", strtotime($item->K_FEC_PUB));//'2010-03-11T17:47:30+01:00';
            $newSite->changefreq = 'daily';
            $newSite->priority = '0.6';
            
            $zNavigation->addPage($newSite);
        }
        
        $this->getResponse()->setHeader('Content-Type', 'text/xml; charset=utf-8');
        $sitemap = $this->view->navigation()->sitemap($zNavigation)->setFormatOutput(true);
        $this->getResponse()->appendBody($sitemap);
    }
    
    /**
     * Descripcion
     * 
     * @return void
     */
    public function categoriaAvisoImagenAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout->disableLayout();
        
        $mAviso = new Application_Model_Sp_Aviso();
        $fileshare = $this->getConfig()->fileshare->toArray();
        
        $urlset = new SimpleXMLElement(
            '<?xml version="1.0" encoding="UTF-8"?>
                <urlset xmlns="'.$this->_xmlns.'" xmlns:image="'.$this->_xmlns_image.'" />'
        );
        
        $idCategoria = $this->_getParam('idCategoria', '');
        
        $sitemapItems = $mAviso->getSitemapByIdCategoria($idCategoria);
        
        foreach ($sitemapItems AS $item) {
            $url = $urlset->addChild('url');
            $url->addChild('loc', URL_SITE . '/aviso/' . $item->K_URL);
            $image = $url->addChild('image:image', null, $this->_xmlns_image);
            $image->addChild(
                'image:loc',
                $fileshare['url'] . '/' . $fileshare['thumbnails'] . '/' . $item->K_FOTO,
                $this->_xmlns_image
            );
            $image->addChild('image:title', $item->K_TITULO, $this->_xmlns_image);
        }
        
        $dom = new DomDocument();
        $dom->loadXML($urlset->asXML());
        $dom->formatOutput = true;
        $output = $dom->saveXML();
        
        $this->_response->setHeader('Content-Type', 'text/xml; charset=utf-8')->setBody($output);
    }
    
}