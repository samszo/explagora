<?php

class ControverseController extends Zend_Controller_Action
{


    public function indexAction()
    {

    		
    }
    
    public function gererAction()
    {
	    	$this->view->uti = "{'idUti':1,'role':'admin','login':'explagora'
	    			, 'equipes':[{'id':3,'text':'équipe 1'},{'id':5,'text':'équipe 2'}]}";
	}    

	public function problemeAction(){
	
		//récupère les problemes
		$bd = new Model_DbTable_Probleme();
		$arr = $bd->getAll($this->_getParam("idProb"),$this->_getParam("idListe"),$this->_getParam("idLivre"));
		//print_r($arr);
		$arr = $this->groupConcatIndiceToColonne($arr);
		$this->view->result = json_encode($arr);
	
	}
	
	public function questionAction(){
		
		//récupère les controverses
		$bdC = new Model_DbTable_Controverse();
		$arr = $bdC->getAllQuestion();
		$arr = $this->groupConcatIndiceToColonne($arr);
		$this->view->result = json_encode($arr);		
		
	}

	public function reponseAction(){
	
		//récupère les reponse
		$bdC = new Model_DbTable_Controverse();
		$arr = $bdC->getReponseForProbleme($this->_getParam('idProbleme'));
		$this->view->result = json_encode($arr);
	
	}
	
    public function creerAction()
    {	
    		switch ($this->_getParam('table')) {
    			case "probleme":
    				//création des tables
    				$bdP = new Model_DbTable_Probleme();
    				$bdI = new Model_DbTable_Indice();
    				$bdQ = new Model_DbTable_Question();
    				$bdC = new Model_DbTable_Controverse();
    				//récupère les paramètres
    				$data = $this->_getParam('data');
    				//ajoute la question
    				$idQ = $bdQ->ajouter(array("valeur"=>$data["question"]));
    				//ajoute le probleme
    				$arrP = array(
    						"doc_id_livre"=>$data["idLivre"]
    						,"doc_id_liste"=>$data["idListe"]
    						,"idQuestion"=>$idQ
    						,"uti_id_auteur"=>$data["idAuteur"]
    				);
    				$idP = $bdP->ajouter($arrP);    				
    				//ajoute les indices 
    				$i = 0;
    				while (isset($data["indice".$i])) {
    					//élimine les valeur vide
    					if($data["indice".$i]){
	    					$idI = $bdI->ajouter(array("valeur"=>$data["indice".$i],"ordre"=>$i));
	    					//ajoute la controverse
	    					$arrC = array(
							"idProbleme"=>$idP
		    					,"idIndice"=>$idI
	    						,"valide"=>$data["valid".$i]	
	    					);
	    					$idC = $bdC->ajouter($arrC);
    					}
    					$i++;
    				}
    				$arr = $this->groupConcatIndiceToColonne($bdP->getAll($idP));    				
    				$this->view->result = json_encode($arr);
    				break;
    			case "question":
    				$bdQ = new Model_DbTable_Question();
    				$this->view->id = $bdQ->ajouter(array("valeur"=>$this->_getParam('valeur')));    				
	    			break;    			
			case "controverse":
				$bdC = new Model_DbTable_Controverse();
				$data = $this->_getParam('data');
				$i = 0;
				while (isset($data["idInd".$i])) {
					//ajoute la controverse
					$arrC = array(
							"idProbleme"=>$data["idP"]
							,"idIndice"=>$data["idInd".$i]
							,"exi_id_equipe"=>$data["idE"]
							,"uti_id_joueur"=>$data["idJ"]
							,"valide"=>$data["valid".$i]
					);
					$idC = $bdC->ajouter($arrC);
					$i++;
				}
				$arr = $bdC->getReponseForProbleme($this->_getParam('idP'));
				$this->view->result = json_encode($arr);				
				break;
			default:
    				$this->view->result = $this->_getParam('table');
    				break;
    		}
    	
    }
    
  	function groupConcatIndiceToColonne($arr){
  		//formate la réponse pour afficher des colonnes pour chaque indice
  		for ($j = 0; $j < count($arr); $j++) {
  			$v = $arr[$j];
  				
  			$gcIdC = explode(',',$v['idsC']);
  			$gcV = explode(',',$v['valids']);
  			$gcIdI = explode(',',$v['idsInd']);
  			$gcValI = explode(',',$v['valInd']);
  			for ($i = 0; $i < count($gcV); $i++) {
  				$arr[$j]['idC'.$i]=$gcIdC[$i];
  				$arr[$j]['idInd'.$i]=$gcIdI[$i];
  				$arr[$j]['valInd'.$i]=$gcValI[$i];
  				$arr[$j]['validInd'.$i]=$gcV[$i];
  			}
  		}
  		return $arr;
  	}
}

