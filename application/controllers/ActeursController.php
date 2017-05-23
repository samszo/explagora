<?php

class ActeursController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function gererAction()
    {
	    	$this->view->uti = "{'idUti':1,'role':'admin','nom':'explagora'}";
    }

    public function datasAction()
    {
    		$e = new Flux_Explagora();
    	 	switch ($this->_getParam('q','init')) {
	    		case 'init':
	    			$arr = array();
	    			$arr["roles"]=$e->getRoles(); 
	    			$arr["droits"]=$e->getDroits();
	    			$arr["acteurs"]=$e->getActeurs();
	    			break;
	    		case 'equipe':
	    			$arr=$e->getEquipes($this->_getParam("idActeur"));
	    			break;
	    	}
	    	$this->view->result = json_encode($arr);
    }
    
    public function creerAction()
    {
    		$e = new Flux_Explagora();
    	 	switch ($this->_getParam('table')) {
	    		case "acteur":
	    			//création des tables
	    			$bdU = new Model_DbTable_Flux_Uti();
	    			//ajoute l'acteur
	    			$idU = $bdU->ajouter(array("login"=>$this->_getParam("login"),"role"=>$this->_getParam("droit")));
	    			$arr = $e->getActeurs($idU);
	    			$this->view->result = json_encode($arr);
	    			break;
    			case "equipe":
    				//création des tables
    				$dbE = new Model_DbTable_Flux_Exi();
    				$dbR = new Model_DbTable_Flux_Rapport();
    				//ajoute l'équipe
    				$idExi = $dbE->ajouter(array("nom"=>$this->_getParam("equipe")));
    				//ajoute le rapport entre l'équipe, l'acteur et le role
    				$idRap = $dbR->ajouter(array("monade_id"=>$e->idMonade
    						,"src_id"=>$this->_getParam("idUti"),"src_obj"=>"uti"
    						,"dst_id"=>$idExi,"dst_obj"=>"exi"
    						,"pre_id"=>$this->_getParam("role"),"pre_obj"=>"tag"
    						,"valeur"=>'equipe'
    				));
    				$arr=$e->getEquipes($this->_getParam("idUti"));
    				$this->view->result = json_encode($arr);
    				break;
    			default:
	    			$this->view->result = $this->_getParam('table');
	    			break;
	    	}
    	 
    }
    
}

