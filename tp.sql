CREATE DATABASE tp2;
use tp2;
CREATE TABLE categories(
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom varchar(255) NOT NULL
) engine=InnoDB;

INSERT INTO categories(nom)
VALUES ("Alimentation"),
        ("Electronique"),
        ("TV"),
        ("Chemises"),
        ("TV");

UPDATE categories SET nom = "Mangues"
WHERE id =3;

CREATE TABLE produits(
    id INT PRIMARY KEY AUTO_INCREMENT,
    categorie_id INT NOT NULL,
    designation VARCHAR(255) NOT NULL,
    prix_unitaire INT NOT NULL,
    quantite INT NOT NULL
) engine=InnoDB;

ALTER TABLE produits ADD CONSTRAINT p_fk FOREIGN KEY (categorie_id) REFERENCES categories (id) ON DELETE CASCADE ON UPDATE NO ACTION;

INSERT INTO produits(categorie_id, designation, prix_unitaire,quantite)
VALUES (1,"Biscuit",5000,20),
        (5,"Biscuit",5000,1),
        (4,"Biscuit",5000,4);


SELECT c.nom "Categorie", p.designation "Designation", p.prix_unitaire "Prix", p.quantite "Qte"
FROM categories c INNER JOIN produits p ON c.id = p.categorie_id;