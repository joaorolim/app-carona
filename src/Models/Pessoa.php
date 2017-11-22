<?php

namespace Fatec\Models;

use Slim\Container;
use Fatec\Models\AbstractModel;

class Pessoa extends AbstractModel
{
	protected $table = 'pessoa';

    protected $id_pessoa;
    protected $nome;
    protected $is_driver;

    /**
     * Atributos para paginação
     */
    protected $qtd = 2;     // A quantidade de linhas a serem exibidas por página


    public function __construct( Container $c )
    {
        parent::__construct( $c );
        $this->fieldList = array( 'id_pessoa', 'nome', 'is_driver' );
        $this->pk = 'id_pessoa';
    }


    /**
     * @return mixed
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return mixed
     */
    public function getIdPessoa()
    {
        return htmlspecialchars( $this->id_pessoa );
    }

    /**
     * @return mixed
     */
    public function getNome()
    {
        return htmlspecialchars( $this->nome );
    }

    /**
     * @return mixed
     */
    public function getIsDriver()
    {
        return htmlspecialchars( $this->is_driver );
    }

    /**
     * Retorna todas as pessoas que são motoristas
     * @param
     * @return type $array como os dados sas pessoas que são motoristas
     */
    public function getMotoristas()
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_driver=1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
