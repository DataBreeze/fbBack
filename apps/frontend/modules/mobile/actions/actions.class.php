<?php

/**
 * mobile actions.
 *
 * @package    fb
 * @subpackage mobile
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mobileActions extends sfActions{

  const RECORD_LIMIT = 50;

  public function preExecute(){
    $this->fbLib = new fbLib($this->getRequest());
    $this->user = $this->fbLib->restoreUser();
    $this->loc = $this->fbLib->getLoc();
    $this->cfg = array('page' => 'mobile');
    $this->footer = array();
  }

  public function initFooter(){
    $this->footer['json_user'] = json_encode($this->fbLib->userSafe($this->user));
    $this->footer['json_cfg'] = json_encode($this->cfg);
    $this->footer['json_loc'] = json_encode($this->loc);
  }

  public function executeIndex(sfWebRequest $request){
    $this->initFooter();    
  }

  public function executePageMenu(sfWebRequest $request){
    $this->initFooter();
  }

  public function executePage(sfWebRequest $request){
    $page_name = $request->getParameter('pageName');
    if($page_name == 'data.html'){
      $this->pageCaption = 'Fish Data';
      $this->pageContent = $this->getPartial('page/fish_data');
    }elseif($page_name == 'corporate.html'){
      $this->pageCaption = 'Corporate';
      $this->pageContent = $this->getPartial('page/corporate');
    }elseif($page_name == 'contact.html'){
      $this->pageCaption = 'Contact';
      $this->pageContent = $this->getPartial('page/contact');
    }elseif($page_name == 'terms.html'){
      $this->pageCaption = 'Terms';
      $this->pageContent = $this->getPartial('page/terms');
    }elseif($page_name == 'privacy.html'){
      $this->pageCaption = 'Privacy';
      $this->pageContent = $this->getPartial('page/privacy');
    }else{
      $this->pageCaption = 'About';
      $this->pageContent = $this->getPartial('page/fishblab');
    }
  }

  private function transfer($rec,$source){
    $recNew = array();
    $recNew['id'] = $rec['id'];
    $recNew['date'] = $rec['date'];
    $recNew['date_time'] = $rec['date_time'];
    $recNew['uts'] = $rec['uts'];
    $recNew['username'] = $rec['username'];
    $recNew['caption'] = $rec['caption'];
    $recNew['fb_source'] = $source;
    return $recNew;
  }

  public function executeAGetAllBB(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $ret = $this->fbLib->getActAllBB(array('limit' => 50));
    $json = json_encode($ret);
    return $this->renderText($json);
  }
  
}
