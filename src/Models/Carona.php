<?php

namespace Fatec\Models;

use Slim\Container;
use Fatec\Models\AbstractModel;

class Carona extends AbstractModel
{
	protected $table = 'carona';

    protected $id_carona;
    protected $id_rota;
    protected $data;
    protected $id_carro;

    /**
     * Atributos para paginação
     */
    protected $qtd = 5;     // A quantidade de linhas a serem exibidas por página


    public function __construct( Container $c )
    {
        parent::__construct( $c );
        $this->fieldList = array( 'id_carona', 'id_rota', 'data', 'id_carro' );
        $this->pk = 'id_carona';
    }


    public function getMotorista()
    {
        $carro = $this->c->Carro;
        $carro = $carro->find( $this->id_carro );
        $motorista = $carro->getMotorista();

        return $motorista;
    }

    public function getCaroneirosPorCarona()
    {
        // SELECT * FROM carona_pessoa WHERE id_carona = 1
        $sql = "SELECT * FROM carona_pessoa WHERE {$this->pk} = ?";
        $stmt = $this->db->prepare( $sql );
        $stmt->bindValue( 1, $this->id_carona, \PDO::PARAM_INT );
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function insert($dados)
    {
        $dadosCarona = $dados['dadosCarona'];
        $arrayCaroneiros = $dados['arrayCaroneiros'];

        $this->db->beginTransaction();

        $lastId = parent::insert( $dadosCarona );

        if ($lastId == 0 || $lastId == null) {
            $this->db->rollback();
            return false;
        } else {
            $dadosBatch = [];
            foreach ($arrayCaroneiros as $value) {
                $array = [
                    'id_carona' => $lastId,
                    'id_pessoa' => $value
                ];
                array_push($dadosBatch, $array);
            }

            if ( $this->insertBatch( 'carona_pessoa', $dadosBatch ) ) {
                $this->db->commit();
                return true;
            } else {
                $this->db->rollback();
                return false;
            }
        }

    }

    public function update( $dados )
    {
        $this->db->beginTransaction();

        if ( ( parent::delete( 'carona_pessoa' ) ) ) {

            if ( parent::update( $dados['dadosCarona'] ) ) {

                $dadosBatch = [];
                foreach ($dados['arrayCaroneiros'] as $value) {
                    $array = [
                        'id_carona' => $this->getIdCarona(),
                        'id_pessoa' => $value
                    ];
                    array_push($dadosBatch, $array);
                }

                if ( $this->insertBatch( 'carona_pessoa', $dadosBatch ) ) {
                    $this->db->commit();
                    return true;
                } else {
                    $this->db->rollback();
                    return false;
                }

            } else {
                $this->db->rollback();
                return false;
            }

        } else {
            $this->db->rollback();
            return false;
        }
    }


    public function delete($table = NULL)
    {
        $this->db->beginTransaction();

        if ( parent::delete( 'carona_pessoa' ) ) {

            if ( parent::delete() ) {
                $this->db->commit();
                return true;
            } else {
                $this->db->rollback();
                return false;
            }

        } else {
            $this->db->rollback();
            return false;
        }
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
    public function getIdCarona()
    {
        return htmlspecialchars( $this->id_carona );
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
    public function getData()
    {

        return date( "Y-m-d", strtotime( $this->data ) );
    }

    /**
     * @return mixed
     */
    public function getIdCarro()
    {
        return htmlspecialchars( $this->id_carro );
    }
}
