<?php
/**
 * Classe ORM qui représente la table 'Probleme'.
 *
 * @author Samuel Szoniecky
 * @category   Zend
 * @package Zend\DbTable\Omk
 * @license https://creativecommons.org/licenses/by-sa/2.0/fr/ CC BY-SA 2.0 FR
 * @version  $Id:$
 */
class Model_DbTable_Probleme extends Zend_Db_Table_Abstract
{
    
    /*
     * Nom de la table.
     */
    protected $_name = 'Probleme';
    
    /*
     * Clef primaire de la table.
     */
    protected $_primary = 'idProbleme';
    protected $_dependentTables = array();
    
        
    /**
     * Vérifie si une entrée Probleme existe.
     *
     * @param array $data
     *
     * @return integer
     */
    public function existe($data)
    {
		$select = $this->select();
		$select->from($this, array('idProbleme'));
		foreach($data as $k=>$v){
			$select->where($k.' = ?', $v);
		}
	    $rows = $this->fetchAll($select);        
	    if($rows->count()>0)$id=$rows[0]->idProbleme; else $id=false;
        return $id;
    } 
        
    /**
     * Ajoute une entrée Probleme.
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
     * Recherche une entrée Probleme avec la clef primaire spécifiée
     * et modifie cette entrée avec les nouvelles données.
     *
     * @param integer $id
     * @param array $data
     *
     * @return void
     */
    public function edit($id, $data)
    {        
   	
    		$this->update($data, 'Probleme.idProbleme = ' . $id);
    }
    
    /**
     * Recherche une entrée Probleme avec la clef primaire spécifiée
     * et supprime cette entrée.
     *
     * @param integer $id
     *
     * @return void
     */
    public function remove($id)
    {    	
	    	$this->delete('Probleme.idProbleme = ' . $id);
    }
    
    /**
     * Recherche toutes les entrées avec toute les dépendances 
     * suivant les paramètre
     *
     * @param	int		$idProb
     *
     * @return array
     */
    public function getAll($idProb=0)
    {
    	$sql = "SELECT 
		    p.idProbleme recid,
		    p.doc_id_liste,
		    dLis.titre titreListe,
		    p.doc_id_livre,
		    dLiv.titre titreLivre,
		    p.uti_id_auteur,
		    uA.login,
		    p.idQuestion,
		    Q.valeur,
		    GROUP_CONCAT(c.idControverse) idsC,
		    GROUP_CONCAT(c.valide) valids,
		    GROUP_CONCAT(c.idIndice) idsInd,
		    GROUP_CONCAT(I.valeur) valInd,
		    COUNT(cRep.idControverse) nbReponse
		FROM
		    Probleme p
		        INNER JOIN
		    flux_doc dLiv ON dLiv.doc_id = p.doc_id_livre
		        INNER JOIN
		    flux_doc dLis ON dLis.doc_id = p.doc_id_liste
		        INNER JOIN
		    flux_uti uA ON uA.uti_id = p.uti_id_auteur
		        INNER JOIN
		    Question Q ON Q.idQuestion = p.idQuestion
		        INNER JOIN
		    Controverse c ON c.idProbleme = p.idProbleme AND c.uti_id_joueur = p.uti_id_auteur
		        INNER JOIN
		    Indice I ON I.idIndice = c.idIndice
		        LEFT JOIN
		    Controverse cRep ON cRep.idProbleme = p.idProbleme AND cRep.uti_id_joueur <> p.uti_id_auteur
		    ";
		if($idProb) $sql .= " WHERE p.idProbleme = ".$idProb;
		$sql .= " GROUP BY p.idProbleme ";
	    	$stmt = $this->_db->query($sql);
	    	return $stmt->fetchAll();
    }
    
}