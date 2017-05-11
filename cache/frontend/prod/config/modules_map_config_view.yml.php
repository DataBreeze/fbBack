<?php
// auto-generated by sfViewConfigHandler
// date: 2012/10/01 12:25:11
$response = $this->context->getResponse();


  $templateName = sfConfig::get('symfony.view.'.$this->moduleName.'_'.$this->actionName.'_template', $this->actionName);
  $this->setTemplate($templateName.$this->viewName.$this->getExtension());



  if (null !== $layout = sfConfig::get('symfony.view.'.$this->moduleName.'_'.$this->actionName.'_layout'))
  {
    $this->setDecoratorTemplate(false === $layout ? false : $layout.$this->getExtension());
  }
  else if (null === $this->getDecoratorTemplate() && !$this->context->getRequest()->isXmlHttpRequest())
  {
    $this->setDecoratorTemplate('' == 'layout_map' ? false : 'layout_map'.$this->getExtension());
  }
  $response->addHttpMeta('content-type', 'text/html', false);
  $response->addMeta('title', 'FishBlab Map', false, false);
  $response->addMeta('description', 'Fishing Discussion, Fishing Reports, Fishing Locations, Fish Catch Data and charts', false, false);
  $response->addMeta('keywords', 'Fishing,fish,fish species,catch fish,find fish,locate fishing spots,fish data,fish species,fish graphs,fish charts', false, false);
  $response->addMeta('viewport', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0', false, false);




