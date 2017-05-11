<?php

/**
 * map actions.
 *
 * @package    fb
 * @subpackage map
 * @author     Joe Junkin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class mapActions extends sfActions{

  public function preExecute(){
    $this->fbLib = new fbLib($this->getRequest());
    $this->user = $this->fbLib->restoreUser();
    $this->json = array();
  }

  public function executeIndex(sfWebRequest $request){
    $this->guest = True;
    $this->loc = $this->fbLib->getLoc();
    $this->geo = $this->loc['geo'];
    $this->json['geo'] = json_encode($this->loc);
    $this->getResponse()->setTitle('FishBlab Exploring Fishing Activity In ' . $this->geo['input']);
    $this->cfg = array();
    $this->cfg['head_text'] = 'Local Fishing Activity';
    $this->cfg['page'] = 'map';
    $cfg_js = array('page' => 'map');
    $this->json['cfg'] = json_encode($cfg_js);
    $this->json['user'] = json_encode($this->fbLib->userSafe($this->user));
    $notes = Doctrine_Core::getTable('UserNotify')->getNotifies($this->user['id']);
    $this->json['notes'] = json_encode($notes);
    $this->param = array('geo' => $this->geo, 'user' => $this->user);
  }

}
