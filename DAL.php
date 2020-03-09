<?php

require_once "config.php";

class DAL
{

    /** @var PDO */
    private $dbh = null;

    /** @var PDOStatement */
    private $lastStmt = null;

    /**
     * Connexion à la BDD
     * @return bool
     */
    public function connect()
    {
        try {
            $this->dbh = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, USERNAME, PASSWORD);
        }
        catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
        return $this->isConnected();
    }

    /**
     * Check si on est connecté
     * @return bool
     */
    public function isConnected()
    {
        return $this->dbh !== null;
    }

    /**
     * Déconnexion de la BDD
     * @return bool
     */
    public function disconnect()
    {
        $this->dbh = null;
        return true;
    }

    /**
     * Exécute une requête
     * @param string $query
     * @param array $data
     * @return bool
     */
    public function execute(string $query, array $data)
    {
        // TODO: vérifier qu'on est bien connecté
        try {
            $stmt = $this->dbh->prepare($query);

            foreach ($data as $key => $value) {
                $stmt->bindParam(':' . $key, $data[$key]);
            }
            // On sauvegarde le statement
            // pour pouvoir faire un fetch dessus si demandé
            $this->lastStmt = $stmt;

            return $stmt->execute();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Retourne le dernier ID inséré
     * (à utiliser dans le cas d'un INSERT)
     * @return int|string
     */
    public function lastInsertId()
    {
        try {
            return $this->dbh->lastInsertId();
        }
        catch (PDOException $e) {
            echo $e->getMessage();
            return 0;
        }
    }

    /**
     * Récupère les données de la dernière requête exécutée
     * (à utiliser dans le cas d'un SELECT)
     * @return array|null
     */
    public function fetchData()
    {
        if ($this->lastStmt == null) {
            return null;
        }

        return $this->lastStmt->fetchAll();
    }




}
