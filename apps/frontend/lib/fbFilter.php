<?php

class fbFilter extends sfFilter{

  public function execute($filterChain) {
    if ( $this->isFirstCall() ) {
      if($this->getContext()->getRequest()->getCookie('fbValid') == 'true'){
	if( ! $this->getContext()->getUser()->getAttribute('fbUserId') ){
	  $this->getContext()->getResponse()->setCookie('fbValid','false',time() + 31536000,'/','.fishblab.com');
	}
      }
    }
    $filterChain->execute();
  }

}
