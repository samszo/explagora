<?php

class Bup8Controller extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body

	    $buP8 = new Flux_Bup8($this->_getParam('idBase','flux_explagora'));
	    $buP8->bTrace = false;
	    $buP8->trace(__METHOD__." : ".$this->_getParam('obj'));
	     
	    switch ($this->_getParam('obj')) {
	    	case 'getListeLivre':
	    		$this->view->reponse = $buP8->getListeLivre($this->_getParam('idListe'));
	    		break;
	    	case 'getListe':
	    		$this->view->reponse = json_encode($buP8->getListe());
	    		//$this->view->reponse = $bnf->getUrlBodyContent("http://data.bnf.fr/search-letter/?term=".urlencode($this->_getParam('term')));
	    		break;
	    	case 'setListe':
	    		$buP8->trace('idListe='.$this->_getParam('idListe'));
	    		$arr = $buP8->setListe($this->_getParam('idListe'));
	    		$buP8->trace('reponse',$arr);
	    		$this->view->reponse = json_encode($arr);
	    		break;
	    	case 'getLivre':
	    		$this->view->reponse = $bnf->getUrlBodyContent("http://data.bnf.fr/search-letter/?term=".urlencode($this->_getParam('term')));
	    		break;
	    	case 'setLivre':
	    		$this->view->reponse = $buP8->setInfoPageLivre($this->_getParam('idLivre'));
	    		break;
	    	default:
	    		break;
	    }
	    $buP8->trace('FIN');
    }
}

