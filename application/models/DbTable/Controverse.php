<?php
/**
 * Classe ORM qui représente la table '	Controverse'.
 *
 * @author Samuel Szoniecky
 * @category   Zend
 * @package Zend\DbTable\Omk
 * @license https://creativecommons.org/licenses/by-sa/2.0/fr/ CC BY-SA 2.0 FR
 * @version  $Id:$
 */
class Model_DbTable_Controverse extends Zend_Db_Table_Abstract
{
    
    /*
     * Nom de la table.
     */
    protected $_name = 'Controverse';
    
    /*
     * Clef primaire de la table.
     */
    protected $_primary = 'idControverse';
    protected $_dependentTables = array();
    
        
    /**
     * Vérifie si une entrée Controverse existe.
     *
     * @param array $data
     *
     * @return integer
     */
    public function existe($data)
    {
		$select = $this->select();
		$select->from($this, array('idControverse'));
		foreach($data as $k=>$v){
			$select->where($k.' = ?', $v);
		}
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->idControverse; else $id=false;
        return $id;
    } 
        
    /**
     * Ajoute une entrée Controverse.
     *
     * @param array $data
     * @param boolean $existe
     *  
     * @return integer
     */
    public function ajouter($data, $existe=true)
    {    	
	    	$id=false;
	    	if($existe)$id = $this->existe($data);
	    	if(!$id){
	    		if(!isset($data["created"])) $data["created"] = new Zend_Db_Expr('NOW()');
	    		$id = $this->insert($data);
	    	}
	    	return $id;
    } 
           
    /**
     * Recherche une entrée Controverse avec la clef primaire spécifiée
     * et modifie cette entrée avec les nouvelles données.
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function edit($id, $data)
    {        
   	
    		$this->update($data, 'Controverse.idControverse = ' . $id);
    }
    
    /**
     * Recherche une entrée Controverse avec la clef primaire spécifiée
     * et supprime cette entrée.
     *
     * @param integer $id
     *
     * @return void
     */
    public function remove($id)
    {    	
	    	$this->delete('Controverse.idControverse = ' . $id);
    }

    /**
     * Recherche toutes les entrées Questions des Controverses 
     *
     *
     * @return array
     */
    public function getAllQuestion()
    {
    		$sql = "SELECT 
		    c.idControverse ,
		    c.doc_id_liste,
		    dLis.titre titreListe,
		    c.doc_id_livre,
		    dLiv.titre titreLivre,
		    c.uti_id_auteur,
		    uA.login,
		    c.idQuestion recid,
		    Q.valeur,
		    GROUP_CONCAT(c.valide) valids,
		    GROUP_CONCAT(c.idIndice) idsInd,
		    GROUP_CONCAT(I.valeur) valInd,
		    COUNT(cRep.idControverse) nbReponse
		FROM
		    Controverse c
		        INNER JOIN
		    flux_doc dLiv ON dLiv.doc_id = c.doc_id_livre
		        INNER JOIN
		    flux_doc dLis ON dLis.doc_id = c.doc_id_liste
		        INNER JOIN
		    flux_uti uA ON uA.uti_id = c.uti_id_auteur
		        INNER JOIN
		    Question Q ON Q.idQuestion = c.idQuestion
		        INNER JOIN
		    Indice I ON I.idIndice = c.idIndice
		        LEFT JOIN
		    Controverse cRep ON cRep.doc_id_liste = c.doc_id_liste
		        AND cRep.doc_id_livre = c.doc_id_livre
		        AND cRep.idQuestion = c.idQuestion
		        AND cRep.uti_id_auteur IS NULL
		GROUP BY c.doc_id_liste , c.doc_id_livre , c.idQuestion";
    		$stmt = $this->_db->query($sql);
    		return $stmt->fetchAll();
    }
    
    		/**
    		 * Recherche toutes les réponses pour un probleme
    		 *
    		 *
    		 * @return array
    		 */
    		public function getReponseForProbleme()
    		{
    			$sql = "SELECT
		    c.idProbleme recid,
    			c.uti_id_jouer,		
		    uJ.login,
    			c.exi_id_equipe,
		    eE.login,
		    Q.valeur,
		    GROUP_CONCAT(c.idControverse) idsCont,
    			GROUP_CONCAT(c.valide) valids,
		    GROUP_CONCAT(c.idIndice) idsInd,
		    GROUP_CONCAT(I.valeur) valInd
		FROM
		    Controverse c
		        INNER JOIN
		    flux_uti uJ ON uJ.uti_id = c.uti_id_jouer
		        INNER JOIN
		    flux_exi eE ON eE.exi_id = c.exi_id_equipe
    				INNER JOIN
		    Question Q ON Q.idQuestion = c.idQuestion
		        INNER JOIN
		    Indice I ON I.idIndice = c.idIndice
		GROUP BY c.idProbleme";
    			$stmt = $this->_db->query($sql);
    			return $stmt->fetchAll();
    		}
    		
    		
}