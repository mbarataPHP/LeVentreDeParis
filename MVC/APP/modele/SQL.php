<?php
require("ConfigSql.php");
/**
 * Class SQL
 * @see barata mathieu
 * @author barata mathieu <mathieu@barata.fr>
 */
class SQL extends ConfigSql{

    /**
     * Retourne un tableau de resultat
     * @author barata mathieu <mathieu@barata.fr>
     * @param string $requete
     * @param array $tab
     * @return array
     */
    public static function lesValeurs($requete, $tab = array()){
        $tab = self::convertitObjetInString($tab);
        $stmt = self::$bdd->prepare($requete);


        $stmt->execute($tab);
        $lesChamps = $stmt->fetchAll(PDO::FETCH_ASSOC);


        $stmt->closeCursor();

        return $lesChamps;
    }

    /**
     * Execute les requete sans retour de resultat
     * @see delete
     * @see insert
     * @see update
     * @author barata mathieu <mathieu@barata.fr>
     * @param string $requete
     * @param array $tab
     */
    public static function execute($requete, $tab = array()){
        $tab = self::convertitObjetInString($tab);
        $stmt = self::$bdd->prepare($requete);
        $stmt->execute($tab);
        $stmt->closeCursor();
    }
}

