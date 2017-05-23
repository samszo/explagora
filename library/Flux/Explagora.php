<?php
/**
 * Classe qui gère les flux du serious game Explagora
 *
 * @copyright  2016 Samuel Szoniecky
 * @license    "New" BSD License
 * 
 * REFERENCES
 * 
 * THANKS
 */
class Flux_Explagora extends Flux_Site{

	var $idTagRole;
	var $idTagDroit;
	
    /**
     * Constructeur de la classe
     *
     * @param  string $idBase
     * 
     */
	public function __construct($idBase=false, $bTrace=false)
    {
    		parent::__construct($idBase, $bTrace);    	

    		//on récupère la racine des documents
    		if(!$this->dbT)$this->dbT = new Model_DbTable_Flux_Tag($this->db);
    		if(!$this->dbD)$this->dbD = new Model_DbTable_Flux_Doc($this->db);	    	
		if(!$this->dbM)$this->dbM = new Model_DbTable_Flux_Monade($this->db);	    	
		$this->idDocRoot = $this->dbD->ajouter(array("titre"=>__CLASS__));
		$this->idMonade = $this->dbM->ajouter(array("titre"=>__CLASS__),true,false);
		//construction des tags nécessaires
		$this->idTagRole = $this->dbT->ajouter(array("code"=>"Rôles"));
		$this->dbT->ajouter(array("code"=>"administrateur", "parent"=>$this->idTagRole));
		$this->dbT->ajouter(array("code"=>"créateur", "parent"=>$this->idTagRole));
		$this->dbT->ajouter(array("code"=>"joueur", "parent"=>$this->idTagRole));
		$this->dbT->ajouter(array("code"=>"expert", "parent"=>$this->idTagRole));
		$this->idTagDroit = $this->dbT->ajouter(array("code"=>"Droits"));
		$this->dbT->ajouter(array("code"=>"admin", "parent"=>$this->idTagDroit));
		$this->dbT->ajouter(array("code"=>"lecteur", "parent"=>$this->idTagDroit));
		$this->dbT->ajouter(array("code"=>"visiteur", "parent"=>$this->idTagDroit));
		
    }

    /**
     * Récupère les rôles
     *     *
     * @return array
     */
    public function getRoles()
    {
	    	if(!$this->dbT) $this->dbT = new Model_DbTable_Flux_Tag($this->db);
	    	$arr = $this->dbT->findByParent($this->idTagRole);    	 
	    	return $this->setChampW2ui($arr);
    }

    /**
     * Récupère les droits
     *     *
     * @return array
     */
    public function getDroits()
    {
	    	if(!$this->dbT) $this->dbT = new Model_DbTable_Flux_Tag($this->db);
	    	$arr = $this->dbT->findByParent($this->idTagDroit);
	    	return $this->setChampW2ui($arr);
	}
    
    /**
     * ajoute les champ pour w2ui
     * @param array	$vals
     * @param array	$champs
     * 
     * 
     * @return array
     */
    public function setChampW2ui($vals, $champs=array('text'=>'code','recid'=>'tag_id'))
    {
    		for ($i = 0; $i < count($vals); $i++) {
	    		foreach ($champs as $k => $c) {
	    			$vals[$i][$k]=$vals[$i][$c];
	    		}
	    	}
	    	return $vals;
    	}
    
    /**
     * Récupère les acteurs
     * @param int	$idUti
     *
     * @return array
     */
    public function getActeurs($idUti=0)
    {
    		if(!$this->dbD) $this->dbD = new Model_DbTable_Flux_Doc($this->db);
    		$sql = "SELECT 
			    u.uti_id recid,
			    u.login,
			    u.role,
			    u.date_inscription,
			    COUNT(DISTINCT r.dst_id) nbEquipe,
			    COUNT(DISTINCT p.idProbleme) nbProbleme,
			    COUNT(DISTINCT c.idControverse) nbControverse,
			    COUNT(DISTINCT cScore.idControverse) score
			FROM
			    flux_uti u
			        LEFT JOIN
			    flux_rapport r ON r.src_id = u.uti_id
			        AND r.src_obj = 'uti'
			        AND r.dst_obj = 'exi'
			        AND r.pre_obj = 'tag'
			        AND valeur = 'equipe'
			        LEFT JOIN
			    Probleme p ON p.uti_id_auteur = u.uti_id
			        LEFT JOIN
			    Controverse c ON c.uti_id_joueur = u.uti_id
			        LEFT JOIN
			    Controverse cValid ON cValid.idProbleme = c.idProbleme  and cValid.idIndice = c.idIndice 
			        LEFT JOIN
			    Controverse cScore ON cScore.uti_id_joueur = u.uti_id and cScore.idProbleme = c.idProbleme  and cScore.idIndice = c.idIndice and cScore.valide = cValid.valide    
    			";
    		if($idUti) $sql .= " WHERE u.uti_id = ".$idUti;
		$sql .= " GROUP BY u.uti_id";
	    	return $this->dbD->exeQuery($sql);
    }
    
    /**
     * Récupère les equipes
     * @param int	$idUti
     *
     * @return array
     */
    public function getEquipes($idUti=0)
    {
    	if(!$this->dbD) $this->dbD = new Model_DbTable_Flux_Doc($this->db);
    	$sql = "SELECT 
		    e.exi_id recid,
		    e.nom,
		    t.code role,
    			r.maj,
		    COUNT(DISTINCT r.src_id) nbJoueur,    
		    COUNT(DISTINCT c.idProbleme) nbProbleme,
		    COUNT(DISTINCT c.idControverse) nbControverse,
		    COUNT(DISTINCT cScore.idControverse) score    
		FROM
		    flux_exi e
		        LEFT JOIN
		    flux_rapport r ON r.dst_id = e.exi_id
		        AND r.src_obj = 'uti'
		        AND r.dst_obj = 'exi'
		        AND r.pre_obj = 'tag'
		        AND valeur = 'equipe'
		        LEFT JOIN
		    flux_tag t ON t.tag_id = r.pre_id
		        LEFT JOIN
		    Controverse c ON c.exi_id_equipe = e.exi_id
		        LEFT JOIN
		    Controverse cValid ON cValid.idProbleme = c.idProbleme  and cValid.idIndice = c.idIndice 
		        LEFT JOIN
		    Controverse cScore ON cScore.exi_id_equipe = e.exi_id and cScore.idProbleme = c.idProbleme  and cScore.idIndice = c.idIndice and cScore.valide = cValid.valide    
		";
    	if($idUti) $sql .= " WHERE r.src_id = ".$idUti;
    	$sql .= " GROUP BY e.exi_id ";
    	return $this->dbD->exeQuery($sql);
    }
}