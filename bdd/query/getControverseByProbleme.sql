SELECT
		    c.idProbleme recid,
    			c.uti_id_joueur,		
		    uJ.login,
    			c.exi_id_equipe,
		    eE.nom,
		    GROUP_CONCAT(c.idControverse) idsCont,
			GROUP_CONCAT(c.valide) valids,
		    GROUP_CONCAT(c.idIndice) idsInd,
		    GROUP_CONCAT(I.valeur) valInd
		FROM
		    Controverse c
		        INNER JOIN
		    flux_uti uJ ON uJ.uti_id = c.uti_id_joueur
		        INNER JOIN
		    flux_exi eE ON eE.exi_id = c.exi_id_equipe
		        INNER JOIN
		    Indice I ON I.idIndice = c.idIndice
		GROUP BY c.idProbleme