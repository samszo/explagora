<?php

class CrudController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function readAction()
    {
    		$this->view->nom = "Franck Zappa";

    		$dbA = new Model_DbTable_articles();
    		$this->view->arr = $dbA->getAll();	    		
    }
    
}

