<?php

class ControverseController extends Zend_Controller_Action
{


    public function indexAction()
    {

    		
    }
    
    public function gererAction()
    {
	    	$this->view->uti = "{'idUti':1,'role':'admin','nom':'explagora'}";
    	 
    }    

    public function creerAction()
    {	
    		switch ($this->_getParam('table')) {
    			case "Controverse":
    				echo "ICI";
    				$data = $this->_getParam('data');
    				$bdQ = new Model_DbTable_Question();
    				$this->view->id = $bdQ->ajouter(array("valeur"=>$data["question"]));
    				break;
    			case "Question":
    				$bdQ = new Model_DbTable_Question();
    				$this->view->id = $bdQ->ajouter(array("valeur"=>$this->_getParam('valeur')));    				
    			break;    			
    			case "Indice":
    				//$bdQ = new Model_DbTable_Indice();    			
    				break;
    			default:
    				break;
    		}
    	
    }
    
}

