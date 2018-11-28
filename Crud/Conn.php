<?php
/**
 * <b>Conn [ CONNECTION ]</b>
 * Classe de conexão no padrão Singleton com banco de dados mysql/mariadb.
 * User: Hilton Bacelar - https://h9web.com.br
 * Date: 24/11/2018
 * Time: 18:16
 *
 */

namespace Source\Crud;

Use \PDOException;
Use \PDO;

class Conn
{
    /** Atributos da classe */

    private static $host;
    private static $user;
    private static $pass;
    private static $name;

    /** @var PDO */

    private static $connect = null;

    /**
     * <b>getConn:</b> Retorna um objeto PDO Singleton Pattern.
     * @return PDO
     */
    public static function getConn()
    {
        return self::connect();
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */

    /**
     * <b>connect:</b> Conecta com o banco de dados com o pattern singleton.
     * @return PDO
     */
    private static function connect()
    {
        self::$host = DATABASE['HOST'];
        self::$user = DATABASE['USER'];
        self::$pass = DATABASE['PASS'];
        self::$name = DATABASE['NAME'];

        try {
            if (self::$connect == null) {
                $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$name;
                $options = [
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_CASE => PDO::CASE_NATURAL
                ];
                self::$connect = new PDO($dsn, self::$user, self::$pass, $options);
            }

        } catch (PDOException $exception) {
            echo $exception->getMessage();
            die;
        }

        return self::$connect;
    }

    /**
     * <b>__construct:</b> Construtor protegido previne que uma nova instância da
     * classe seja criada através do operador `new` de fora dessa classe.
     * @return void
     */

    final private function __construct()
    {
    }

    /**
     * <b>__clone:</b> Método clone privado previne a clonagem dessa instância
     * da classe
     * @return void
     */

    final private function __clone()
    {
    }

    /**
     * <b>__wakeup:</b> Método unserialize privado para previnir que desserialização
     * da instância dessa classe.
     * @return void
     */

    private function __wakeup()
    {
    }
}