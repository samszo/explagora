SELECT 
    c.idControverse recid,
    c.doc_id_liste,
    dLis.titre,
    c.doc_id_livre,
    dLiv.titre,
    c.uti_id_auteur,
    uA.login,
    c.idQuestion,
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
GROUP BY c.doc_id_liste , c.doc_id_livre , c.idQuestion
