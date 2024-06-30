<?php
class Etudiants 
{
    private $table = "etudiant";
    private $connexion = null;

    // les propriétés de la table etudiant
    private $id;
    private $nom;
    private $prenom;
    private $age;
    private $niveau_id;
    private $niveau_nom;
    private $created_at;

    // le constructeur de la classe
    public function __construct($db) {
        if ($this->connexion == null) {
            $this->connexion = $db;
        }
    }

    /**
     * cette méthode readAll permet d'afficher tout les étudiants
     * @return mixed
     */
    public function readAll() {
        try {
            $sql = "SELECT e.id,e.nom,e.prenom,e.age,e.niveau_id
            FROM $this->table e LEFT JOIN niveaux n
            ON e.niveau_id=n.id
            ORDER BY e.created_at DESC";
            $query = $this->connexion->prepare($sql);
            $query->execute();
            return $query;
        } catch (PDOException $e) {
            die(json_encode("Erreur d'affichage de la liste des etudiant.e.s : ".$e->getMessage(),JSON_PRETTY_PRINT));
        }
    }
    /**
     * cette méthode create permet de créer un etudiant
     */
    public function create() {
        $sql = "INSERT INTO $this->table(nom,prenom,age,niveau_id,created_at) VALUES(:n,:p,:a,:ni,NOW())";
        $options = [
            ":n" => $this->nom,
            ":p" => $this->prenom,
            ":a" => $this->age,
            ":ni" => $this->niveau_id
        ];
        // préparation de la requete
        try {
            $query = $this->connexion->prepare($sql);
            $r = $query->execute($options);
            return $r;
        } catch (PDOException $e) {
            die(json_encode(["message","Erreur d'ajout d'un etudiant.e.s : "].$e->getMessage(),JSON_PRETTY_PRINT));
        }
    }
    /**
     * cette méthode permet de faire la mise à jour
     */
    public function update() {
        $sql = "UPDATE $this->table SET nom=:n,prenom=:p,age=:a,niveau_id=:ni WHERE id=:id";
        $options = [
            ":n" => $this->nom,
            ":p" => $this->prenom,
            ":a" => $this->age,
            ":ni" => $this->niveau_id,
            ":id" => $this->id
        ];
        try {
            $query = $this->connexion->prepare($sql);
            $r = $query->execute($options);
            return $r;
        } catch (PDOException $e) {
            die(json_encode(["message" => "Erreur de mise à jour de l'étudiant".$e->getMessage()],JSON_PRETTY_PRINT));
        }
    }

    /**
     * cette méthode permet de supprimer 
     */
    public function delete() {
        $sql = "DELETE FROM $this->table WHERE id=:id";
        $options = [":id" => $this->id];
        try {
            $query = $this->connexion->prepare($sql);
            $r = $query->execute($options);
            return $r;
        } catch (PDOException $e) {
            die(json_encode(["message" => "Erreur de suppression de l'étudiant : ".$e->getMessage()],JSON_PRETTY_PRINT));
        }
    }
    
    /**
     * Set the value of nom
     *
     * @return  self
     */ 
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Set the value of prenom
     *
     * @return  self
     */ 
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Set the value of age
     *
     * @return  self
     */ 
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Set the value of niveau_id
     *
     * @return  self
     */ 
    public function setNiveau_id($niveau_id)
    {
        $this->niveau_id = $niveau_id;

        return $this;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }
}