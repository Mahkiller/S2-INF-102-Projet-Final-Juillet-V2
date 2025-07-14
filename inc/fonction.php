<?php
require_once("../inc/connect.php");
function emailExiste($email, $conn) {
    $sql = "SELECT * FROM exam2_membre WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_num_rows($result) > 0;
}

function inscrireMembre($nom, $date, $genre, $email, $ville, $mdp, $conn) {
    $sql = "INSERT INTO exam2_membre (nom, date_de_naissance, genre, email, ville, mdp) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $nom, $date, $genre, $email, $ville, $mdp);
    return mysqli_stmt_execute($stmt);
}

function verifierConnexion($email, $mdp, $conn) {
    $sql = "SELECT * FROM exam2_membre WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        if ( $mdp==$row['mdp']) {
            return $row;
        }
    }
    return false;
}


// requete -> table :
 function getRequestTab($request) {
    $conn=dbconnect();
    if (!$request) {
        die('Erreur SQL : ' . mysqli_error($conn));
    }
    while ($row = mysqli_fetch_assoc($request)) {
        $tab[] = $row;
    }
   // je ne sais pas comment utiliser mysqli_close($conn);
    return $tab;
}

function getObjetsAvecEmprunt($conn) {
    $sql = "SELECT o.nom_objet, c.nom_categorie, m.nom AS proprietaire,
                   MAX(e.date_retour) AS date_retour
            FROM exam2_objet o
            JOIN exam2_categorie_objet c ON o.id_categorie = c.id_categorie
            JOIN exam2_membre m ON o.id_membre = m.id_membre
            LEFT JOIN exam2_emprunt e ON o.id_objet = e.id_objet
            GROUP BY o.id_objet
            ORDER BY o.nom_objet";
    $result = mysqli_query($conn, $sql);
    $tab = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $tab[] = $row;
    }
    return $tab;
}

function getObjetsFiltres($conn, $id_categorie = 0, $statut = 'tous') {
    $where = [];
    if ($id_categorie > 0) {
        $where[] = "o.id_categorie = " . intval($id_categorie);
    }

    // Statut : "emprunte" = a une date de retour future, "disponible" = pas d'emprunt en cours
    if ($statut == 'emprunte') {
        $where[] = "e.date_retour >= CURDATE()";
    } elseif ($statut == 'disponible') {
        $where[] = "e.date_retour IS NULL OR e.date_retour < CURDATE()";
    }

    $where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

$sql = "SELECT o.id_objet, o.nom_objet, c.nom_categorie, m.nom AS proprietaire,
               MAX(e.date_retour) AS date_retour
        FROM exam2_objet o
        JOIN exam2_categorie_objet c ON o.id_categorie = c.id_categorie
        JOIN exam2_membre m ON o.id_membre = m.id_membre
        LEFT JOIN exam2_emprunt e ON o.id_objet = e.id_objet
        $where_sql
        GROUP BY o.id_objet
        ORDER BY o.nom_objet";

    $result = mysqli_query($conn, $sql);
    $tab = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $tab[] = $row;
    }
    return $tab;
}

// ajout de jour dans sql
function modifierDateRetour($id, $nbJ){
    $conn = dbconnect();

    $sql = sprintf(
        "UPDATE exam2_emprunt SET date_retour = DATE_ADD(date_emprunt, INTERVAL %d DAY) WHERE id_objet = %d",
        $nbJ,
        $id
    );

    return mysqli_query($conn, $sql);
}
