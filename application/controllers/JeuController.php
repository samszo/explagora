<?php

class JeuController extends Zend_Controller_Action
{


    public function indexAction()
    {

    		
    }
    
    public function plateauAction()
    {
	    	$ssUti = new Zend_Session_Namespace('uti');
	    //	echo "redir=".$this->_getParam('idUti');
	    	//if(!$ssUti->uti)	$this->_redirect('excode');
    
    }
        
    
}

