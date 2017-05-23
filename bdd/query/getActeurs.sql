SELECT 
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
GROUP BY u.uti_id