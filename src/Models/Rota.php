<?php

namespace Fatec\Models;

use Slim\Container;
use Fatec\Models\AbstractModel;

class Rota extends AbstractModel
{
	protected $table = 'rota';

    protected $id_rota;
    protected $descricao;
    protected $km;

    /**
     * Atributos para paginação
     */
    protected $qtd = 2;     // A quantidade de linhas a serem exibidas por página


    public function __construct( Container $c )
    {
        parent::__construct( $c );
        $this->fieldList = array( 'id_rota', 'descricao', 'km' );
        $this->pk = 'id_rota';
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
    public function getIdRota()
    {
        return htmlspecialchars( $this->id_rota );
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return htmlspecialchars( $this->descricao );
    }

    /**
     * @return mixed
     */
    public function getKm()
    {
        return htmlspecialchars( $this->km );
    }
}
