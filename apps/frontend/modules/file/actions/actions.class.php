<?php

/**
 * file actions.
 *
 * @package    fb
 * @subpackage file
 * @author     Joe Junkin
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class fileActions extends sfActions{

  public function preExecute(){
    $this->fbLib = new fbLib($this->getRequest());
    $this->user = $this->fbLib->restoreUser();
  }
  
  public function executeIndex(sfWebRequest $request){
    $this->forward('default', 'module');
  }
  
  ## non-ajax upload
  public function executeUpload(sfWebRequest $request){
    $file = new fbPhotoFile();
    $file->create();
    $this->json = $file->jsonResponse();
    $this->setTemplate(sfConfig::get('sf_app_template_dir').DIRECTORY_SEPARATOR . 'uploadResponse');
  }

  ## ajax photo
  public function executeAPhotoGetAll(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbPhotoFile();
    $file->getPhotosAjax();
    return $this->renderText($file->jsonResponse());
  }

  public function executeAPhotoEdit(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbPhotoFile();
    $file->edit();
    return $this->renderText($file->jsonResponse());
  }

  public function executeAPhotoEditGeo(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbPhotoFile();
    $file->editGeo();
    return $this->renderText($file->jsonResponse());
  }

  public function executeAPhotoDelete(sfWebRequest $request){
    $this->setLayout(null);
    $this->getResponse()->setHttpHeader('Content-type', 'application/json');
    $file = new fbPhotoFile();
    $file->delete();
    return $this->renderText($file->jsonResponse());
  }

}
