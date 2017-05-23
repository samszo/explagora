SELECT 
    c.idControverse recid,
    c.idProbleme ,
    c.uti_id_joueur,
    uJ.login,
    c.exi_id_equipe,
    c.created,
    eE.nom,
    Q.valeur,
    c.valide,
    c.idIndice,
    I.valeur indice,
    I.ordre
FROM
    Controverse c
        INNER JOIN
    flux_uti uJ ON uJ.uti_id = c.uti_id_joueur
        INNER JOIN
    flux_exi eE ON eE.exi_id = c.exi_id_equipe
        INNER JOIN
    Probleme P ON P.idProbleme = c.idProbleme
        INNER JOIN
    Question Q ON Q.idQuestion = P.idQuestion
        INNER JOIN
    Indice I ON I.idIndice = c.idIndice
WHERE  c.idProbleme = 4