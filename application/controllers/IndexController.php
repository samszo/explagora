<?php

class IndexController extends Zend_Controller_Action
{
	var $idBase = "flux_excode";
	var $urlRedir = 'excode/plateau';
	
    public function indexAction()
    {
        // action body
	    	$this->view->idBase = $this->_getParam('idBase', $this->idBase);
	    	$this->view->urlConnect = "auth/cas?idBase=".$this->view->idBase."&redir=".$this->urlRedir;
    	 
    	
    }


}

