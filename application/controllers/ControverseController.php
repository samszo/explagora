<?php

class ControverseController extends Zend_Controller_Action
{


    public function indexAction()
    {

    		
    }
    
    public function gererAction()
    {
	    	$this->view->uti = "{'uti_id':1,'role':'admin'}";
    	 
    }    

    public function creerAction()
    {	
    		switch ($this->_getParam('table')) {
    			case "Question":
    				$bdQ = new Model_DbTable_Question();
    				$this->view->id = $bdQ->ajouter(array("valeur"=>$this->_getParam('valeur')));    				
    			break;    			
    			case "Indice":
    				//$bdQ = new Model_DbTable_Indice();
    			
    				break;
    				 default:
    				;
    			break;
    		}
    	
    }
    
}

