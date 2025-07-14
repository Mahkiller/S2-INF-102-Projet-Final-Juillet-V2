-- creation de tables :
-- membre (id_membre, nom , date de naissance, genre, émail, ville, mdp, image_profil)
CREATE DATABASE examV3;
USE examV3;

CREATE TABLE exam2_membre (
    id_membre INT AUTO_INCREMENt PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    date_de_naissance DATE,
    genre ENUM('Homme', 'Femme', 'Autre') NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    ville VARCHAR(100),
    mdp VARCHAR(255) NOT NULL,
    image_profil VARCHAR(255)
);

-- categorie_objet (id_categorie, nom_categorie )
CREATE TABLE exam2_categorie_objet (
    id_categorie INT AUTO_INCREMENT PRIMARY KEY,
    nom_categorie VARCHAR(100) NOT NULL
);
-- objet (id_objet , nom_objet , id_categorie, id_membre)
CREATE TABLE exam2_objet (
    id_objet INT AUTO_INCREMENT PRIMARY KEY,
    nom_objet VARCHAR(100) NOT NULL,
    id_categorie INT,
    id_membre INT,
    FOREIGN KEY (id_categorie) REFERENCES exam2_categorie_objet(id_categorie),
    FOREIGN KEY (id_membre) REFERENCES exam2_membre(id_membre)
);

-- images_objet(id_image, id_objet, nom_image)
CREATE TABLE exam2_images_objet(
    id_images_objet INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    nom_image VARCHAR(255),
    FOREIGN KEY (id_objet ) REFERENCES exam2_objet(id_objet)
);

-- emprunt(id_emprunt, id_objet, id_membre, date_emprunt, date_retour)
CREATE TABLE exam2_emprunt (
    id_emprunt INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT,
    id_membre INT,
    date_emprunt DATE,
    date_retour DATE,
    FOREIGN KEY (id_membre) REFERENCES exam2_membre(id_membre),
    FOREIGN KEY (id_objet) REFERENCES exam2_objet(id_objet)
);

-- insertion des donnees data :
--4  membres
INSERT INTO exam2_membre (nom , date_de_naissance, genre, email, ville, mdp)  VALUES
('Andri','2000-01-01',1,'andri@gmail.com','Antananarivo','andri!'),
('Nomena','2000-01-01',2,'nomena@gmail.com','Antananarivo','nomena!'),
('Fitia','2000-01-01',1,'fitia@gmail.com','Antananarivo','fitia!'),
('Dera','2000-01-01',2,'dera@gmail.com','Antananarivo','dera!');

--4 catégories ( esthétique, bricolage, mécanique , cuisine )
INSERT INTO exam2_categorie_objet (nom_categorie) VALUES
('Esthétique'),
('Bricolage'),
('Mécanique'),
('Cuisine');

--10 objets par membre à répartir sur les catégories
-- Objets pour Andri (id_membre = 1)
INSERT INTO exam2_objet (nom_objet , id_categorie, id_membre) VALUES
('Tournevis',2,1),
('Crème visage',1,1),
('Clé à molette',3,1),
('Fouet de cuisine',4,1),
('Mascara',1,1),
('Marteau',2,1),
('Batteur électrique',4,1),
('Frein à disque',3,1),
('Rouge à lèvres',1,1),
('Perceuse',2,1);

-- Objets pour Nomena (id_membre = 2)
INSERT INTO exam2_objet (nom_objet , id_categorie, id_membre) VALUES
('Blush',1,2),
('Ponceuse',2,2),
('Plaques de cuisson',4,2),
('Bougie parfumée',1,2),
('Scie sauteuse',2,2),
('Volant de voiture',3,2),
('Pâte à tartiner',4,2),
('Parfum',1,2),
('Clé dynamométrique',3,2),
('Poêle antiadhésive',4,2);

-- Objets pour Fitia (id_membre = 3)
INSERT INTO exam2_objet (nom_objet , id_categorie, id_membre) VALUES
('Mascara',1,3),
('Scie manuelle',2,3),
('Amortisseur',3,3),
('Moule à gâteau',4,3),
('Fond de teint',1,3),
('Clé plate',3,3),
('Friteuse',4,3),
('Marteau piqueur',2,3),
('Lisseur',1,3),
('Mixeur',4,3);

-- Objets pour Dera (id_membre = 4)
INSERT INTO exam2_objet (nom_objet , id_categorie, id_membre) VALUES
('Eyeliner',1,4),
('Tournevis électrique',2,4),
('Embrayage',3,4),
('Casserole',4,4),
('Crayon à sourcils',1,4),
('Pince multiprise',2,4),
('Radiateur voiture',3,4),
('Robot pâtissier',4,4),
('Brosse à cheveux',1,4),
('Perceuse-visseuse',2,4);

-- 10 emprunts, un par objet, avec une date de retour différente
INSERT INTO exam2_emprunt (id_objet, id_membre, date_emprunt, date_retour) VALUES
(1, 2, '2025-07-01', '2025-07-11'),
(2, 3, '2025-07-02', '2025-07-12'),
(3, 4, '2025-07-03', '2025-07-13'),
(4, 1, '2025-07-04', '2025-07-14'),
(5, 2, '2025-07-05', '2025-07-15'),
(6, 3, '2025-07-06', '2025-07-16'),
(7, 4, '2025-07-07', '2025-07-17'),
(8, 1, '2025-07-08', '2025-07-18'),
(9, 2, '2025-07-09', '2025-07-19'),
(10, 3, '2025-07-10', '2025-07-20');

INSERT INTO exam2_emprunt (id_objet, id_membre, date_emprunt, date_retour)
    SELECT o.id_objet, o.id_membre, '2025-07-10', '2025-07-20'
    FROM exam2_objet o
    WHERE o.id_objet NOT IN (SELECT id_objet FROM exam2_emprunt);

-- alea2
CREATE TABLE exam2_etatobject (
    id_etat INT AUTO_INCREMENT PRIMARY KEY,
    id_objet INT NOT NULL,
    id_emprunt INT NOT NULL,
    etat ENUM('Bon', 'Moyen', 'Mauvais') NOT NULL,
    FOREIGN KEY (id_objet) REFERENCES exam2_objet(id_objet),
    FOREIGN KEY (id_emprunt) REFERENCES exam2_emprunt(id_emprunt)
);

ALTER TABLE exam2_etatobject MODIFY COLUMN id_emprunt INT NULL;

MariaDB [examV3]> INSERT INTO exam2_etatobject (id_objet, id_emprunt, etat)
    -> VALUES
    -> (1, 1, 'Bon'),
    -> (2, 2, 'Moyen'),
    -> (3, 3, 'Mauvais');