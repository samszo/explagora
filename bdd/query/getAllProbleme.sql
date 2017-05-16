SELECT 
    p.idProbleme recid,
    p.doc_id_liste,
    dLis.titre,
    p.doc_id_livre,
    dLiv.titre,
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
GROUP BY p.idProbleme
