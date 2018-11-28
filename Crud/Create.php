<?php
/**
 * <b>Create [ INSERT ]</b>
 * Classe responsável por cadastros genéricos no banco de dados!
 * User: Hilton L. Bacelar : hilton.bacelar@h9web.com.br
 * Date: 26/11/2018
 * Time: 16:02
 */

namespace Source\Crud;

USE \PDO;
USE \PDOException;


/**
 * Class Create
 * @package Source\Crud
 */
class Create
{
    /** Atributos da classe */

    private $table;
    /**
     * @var
     */
    private $data;
    /**
     * @var
     */
    private $result;

    /** @var PDOStatement */
    private $create;

    /** @var PDO */
    private $conn;

    /**
     * Obter a conexão com banco de dados Singleton
     * Create constructor.
     */
    public function __construct()
    {
        $this->conn = Conn::getConn();
    }

    /**
     * <b>create:</b> Executa um cadastro simplificado na base de dados utilizando prepared statements.
     * @param $table = Informe o nome da tabela no banco
     * @param array $data = array atribuitivo. ( Nome da coluna => Valor)
     *
     */
    public function create($table, array $data)
    {
        $this->table = (string)$table;
        $this->data = $data;

        $this->getSintax();
        $this->execute();
    }

    /**
     * <b>createMultiple:</b> Executa um cadastro múltiplo no bamco de dados utilizando prepared statements.
     * Nome da tabela e um array multidimensional. ([] = Key =>Value)
     * @param $table
     * @param array $data
     */
    public function createMultiple($table, array $data)
    {
        $this->table = (string)$table;
        $this->data = $data;

        $fields = implode(', ', array_keys($this->data[0]));
        $places = null;
        $inserts = null;
        $links = count(array_keys($this->data[0]));

        foreach ($data as $valueMult) {
            $places .= '(';
            $places .= str_repeat('?,', $links);
            $places .= '),';

            foreach ($valueMult as $valueSingle) {
                $inserts[] = $valueSingle;
            }
        }
        $places = str_replace(',)', ')', $places);
        $places = substr($places, 0, -1);
        $this->data = $inserts;

        $this->create = "INSERT INTO {$this->table} ({$fields}) VALUES {$places}";
        $this->execute();
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /** PRIVATE METHODS */

    private function connect()
    {
        $this->create = $this->conn->prepare($this->create);
    }

    /**
     *
     */
    private function getSintax()
    {
        $fields = implode(', ', array_keys($this->data));
        $places = ':' . implode(', :', array_keys($this->data));
        $this->create = "INSERT INTO {$this->table} ({$fields}) VALUES ({$places})";
    }

    /**
     *
     */
    private function execute()
    {
        $this->connect();

        try{
            $this->create->execute($this->data);
            $this->result = $this->conn->lastInsertId();

        }catch (PDOException $exception){
           $this->result = null;
           echo"<b>Erro ao Cadastrar:</b> {$exception->getMessage()} {$exception->getCode()}";
        }
    }
}