<?php

//connexion à la base de données
$bdd = new PDO('mysql:host=http://gapai.univ-paris8.fr/phpmyadmin/; dbname=explagora;charset=utf8', 'explagora', 'CDNL2017');

$question = $_POST ['question'] ;
$indices = $_POST['indices'];
