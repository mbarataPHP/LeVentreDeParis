<?php
/**
 * Created by PhpStorm.
 * User: Mathieu
 * Date: 05/11/2016
 * Time: 11:42:32
 */

class ConfigSql{

    /**
     * L'objet PDO
     * @author barata mathieu <mathieu@barata.fr>
     * @var PDO $bdd
     */
    protected static $bdd;

    /**
     * L'adresse ip ou le nom domaine de la base de donnée
     * @author barata mathieu <mathieu@barata.fr>
     * @var string $host
     */
    protected static $host;

    /**
     * Le nom de la base de données ciblée
     * @author barata mathieu <mathieu@barata.fr>
     * @see use <BDD>
     * @var string $db
     */
    protected static $db;
    /**
     * L'utilisateur du SQL
     * @author barata mathieu <mathieu@barata.fr>
     * @var string $user
     */
    protected static $user;
    /**
     * Le mot passe de la base de donnée
     * @author barata mathieu <mathieu@barata.fr>
     * @var string $pwd
     */
    protected static $pwd;
    /**
     * Le type de SQL
     * @author barata matieu <mathieu@barata.fr>
     * @var string $TYPE_SQL
     */
    protected static $TYPE_SQL;

    /**
     * @author barata mathieu <mathieu@barata.fr>
     * @param string $host
     */
    public static function setHost($host)
    {
        self::$host = $host;
    }

    /**
     * @author barata mathieu <mathieu@barata.fr>
     * @param string $db
     */
    public static function setDb($db)
    {
        self::$db = $db;
    }

    /**
     * @author barata mathieu <mathieu@barata.fr>
     * @param string $user
     */
    public static function setUser($user)
    {
        self::$user = $user;
    }

    /**
     * @author barata mathieu <mathieu@barata.fr>
     * @param string $pwd
     */
    public static function setPwd($pwd)
    {
        self::$pwd = $pwd;
    }

    /**
     * @author barata mathieu <mathieu@barata.fr>
     * @param string $TYPE_SQL
     */
    public static function setTYPESQL($TYPE_SQL)
    {
        $tab = array("mysql");
        if(in_array($TYPE_SQL, $tab)){
            self::$TYPE_SQL = $TYPE_SQL;
        }

    }

    /**
     * Initilise l'objet PDO dans l'attribut $bdd
     * @see self::$bdd
     * @author barata mathieu <mathieu@barata.fr>
     */
    public static function connection(){
        try
        {
            self::$bdd = new PDO(self::$TYPE_SQL.':host='.self::$host.';dbname='.self::$db, self::$user, self::$pwd);
        }
        catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }
    }

    /**
     * Convertie les objet en string
     * @author barata mathieu <mathieu@barata.fr>
     * @see datetime
     * @param array $tab
     * @return array
     */
    public static function convertitObjetInString($tab = array()){
        $returnTab = array();
        foreach($tab as $key=>$val){
            if ($val instanceof DateTime) {
                $returnTab[$key] = $val->format("Y-m-d H:i:s");
            }else{
                if(is_string($val)){
                    $returnTab[$key] = $val;
                }
            }
        }

        return $returnTab;
    }
}
ConfigSql::setTYPESQL($_SQL_TYPE);
ConfigSql::setDb($_SQL_BDD);
ConfigSql::setHost($_SQL_HOST);
ConfigSql::setPwd($_SQL_PWD);
ConfigSql::setUser($_SQL_USER);
ConfigSql::connection();