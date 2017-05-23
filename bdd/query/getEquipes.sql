SELECT 
    e.exi_id recid,
    e.nom,
    t.code role,
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
GROUP BY e.exi_id