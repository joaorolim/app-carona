<?php

namespace Fatec\Models;

use Slim\Container;
use Fatec\Models\AbstractModel;
use Fatec\Models\Pessoa;

class Carro extends AbstractModel
{
	protected $table = 'carro';

    protected $id_carro;
    protected $id_pessoa;
    protected $descricao;

    /**
     * Atributos para paginação
     */
    protected $qtd = 2;     // A quantidade de linhas a serem exibidas por página


    public function __construct( Container $c )
    {
        parent::__construct( $c );
        $this->fieldList = array( 'id_carro', 'id_pessoa', 'descricao' );
        $this->pk = 'id_carro';
    }


    /**
     * @return mixed
     */
    public function getTable()
    {
        return htmlspecialchars( $this->table );
    }

    /**
     * @return mixed
     */
    public function getIdCarro()
    {
        return htmlspecialchars( $this->id_carro );
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
    public function getDescricao()
    {
        return htmlspecialchars( $this->descricao );
    }

    public function getMotorista()
    {
        $motorista = $this->c->Pessoa;
        $motorista = $motorista->find( $this->id_pessoa );

        return $motorista;
    }

    public function getCarroPorMotorista( $id_motorista )
    {
        $sql = "SELECT * FROM {$this->table} WHERE id_pessoa = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue( 1, $id_motorista, \PDO::PARAM_INT );
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
}
