<?php
/**
 * Default class file
 *
 * PHP Version 5.3
 *
 * @category PHP
 * @package  Admin
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */

/**
 * Default class
 *
 * The class holding the root Recipe class definition
 *
 * @category PHP
 * @package  Admin
 * @author   Ander <anderson.poccorpachi@ec.pe>
 * @license  http://kotear.pe/ License
 * @link     http://kotear.pe/
 */
class Devnet_Controller_Action_Default
    extends Devnet_Controller_Action
{
    public $defHeadMeta;

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
    public function postDispatch()
    {
        parent::postDispatch();
        
        $dataAll = $this->getConfig()->headMeta->toArray();
        $datanew = $this->defHeadMeta['data'];
        $data = !empty($datanew)?(array_merge($dataAll, $datanew)):$dataAll;
        
        $data['title'] = $data['title'] . " | Kotear";
        $data['ogUrl'] = empty($data['ogUrl'])?$data['siteUrlFull']:$data['ogUrl'];
        $data['ogImg'] = empty($data['ogImg'])
            ?URL_STATIC . $data['ogImage'] . App_Config::getStaticVersion()
            :$data['ogImg'];
        
        $this->view->headMeta()->setName("title", $data['title']);
        $this->view->headMeta()->setName("description", $data['description']);
        $this->view->headMeta()->setName("keywords", $data['description']);
        $this->view->headMeta()->setName("author", $data['siteUrl']);
        $this->view->headMeta()->setName("viewport", $data['viewport']);
        $this->view->headMeta()->setName("geo.region", $data['geoRegion']);
        $this->view->headMeta()->setName("geo.placename", $data['geoPlacename']);
        $this->view->headMeta()->setName("geo.position", $data['geoPosition']);
        $this->view->headMeta()->setName("ICBM", $data['geoPosition']);
        $this->view->headMeta()->setName("DC.Title", $data['title']);
        $this->view->headMeta()->setName("DC.Creator", $data['siteUrl']);
        $this->view->headMeta()->setName("DC.Description", $data['description']);
        $this->view->headMeta()->setName("DC.Publisher", $data['name']);
        $this->view->headMeta()->setName("DC.Language", $data['dcLanguage']);
        $this->view->headMeta()->setName(
            'robots', $data['robots']
        );
        $this->view->headMeta()->setName(
            'google-site-verification', $this->getConfig()->apis->google->siteVerification
        );//"noindex, nofollow, noimageindex
        
        $this->view->headMeta()->setProperty('og:site_name', $data['name']);
        $this->view->headMeta()->setProperty('og:type', $data['ogType']);
        $this->view->headMeta()->setProperty('og:title', $data['title']);
        $this->view->headMeta()->setProperty('og:url', $data['ogUrl']);
        $this->view->headMeta()->setProperty('og:description', $data['description']);
        $this->view->headMeta()->setProperty('og:image', $data['ogImg']
        );
        
        $this->view->headTitle($data['title']);
        
    }
}