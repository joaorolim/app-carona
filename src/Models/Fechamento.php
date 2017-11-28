<?php

namespace Fatec\Models;

use Slim\Container;
use Fatec\Models\AbstractModel;

class Fechamento extends AbstractModel
{
	protected $table = 'fechamento';

    protected $id_fechamento;
    protected $mes;
    protected $ano;


    public function __construct( Container $c )
    {
        parent::__construct( $c );
        $this->fieldList = array( 'id_fechamento', 'mes', 'ano' );
        $this->pk = 'id_fechamento';
    }

    public function getFechamentoPorMes( $mes, $ano, $fromDay, $untilDay )
    {
        $sql = "";
        $sql .= "SELECT tab.*, p.nome AS `caroneiro`, pf.pagou";
        $sql .= " FROM (SELECT aux.*, cp.id_pessoa as `id_caroneiro`";
        $sql .= " FROM (SELECT fm.id_fechamento, fm.id_carona, car.id_carro, car.descricao, car.id_pessoa AS `id_motorista`, p.nome AS `motorista`, fm.data, r.descricao as `rota`, r.km, carf.media_comb, carf.media_km_litro";
        $sql .= " FROM (SELECT c.*, f.id_fechamento";
        $sql .= " FROM carona AS c, fechamento AS f";
        $sql .= " WHERE f.mes = ? AND f.ano = ? AND (c.data BETWEEN ? AND ?)) AS fm";
        $sql .= " JOIN carro AS car ON car.id_carro = fm.id_carro";
        $sql .= " JOIN pessoa AS p ON p.id_pessoa = car.id_pessoa";
        $sql .= " JOIN carro_fechamento AS carf ON (carf.id_fechamento = fm.id_fechamento AND carf.id_carro = fm.id_carro)";
        $sql .= " JOIN rota AS r ON r.id_rota = fm.id_rota";
        $sql .= " ORDER BY p.nome, fm.data) AS aux";
        $sql .= " JOIN carona_pessoa as cp ON cp.id_carona = aux.id_carona) as tab";
        $sql .= " JOIN pessoa as p ON p.id_pessoa = tab.id_caroneiro";
        $sql .= " JOIN pessoa_fechamento as pf ON (pf.id_fechamento = tab.id_fechamento AND pf.id_pessoa = tab.id_caroneiro)";
        $sql .= " ORDER BY tab.id_carro, tab.id_carona, caroneiro";

        $mes = intval($mes);
        $ano = intval($ano);

        $stmt = $this->db->prepare( $sql );
        $stmt->bindValue( 1, $mes );
        $stmt->bindValue( 2, $ano );
        $stmt->bindValue( 3, $fromDay );
        $stmt->bindValue( 4, $untilDay );
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }


    public function getCarrosPorPeriodo( $fromDay, $untilDay )
    {
        $sql = "SELECT c.id_carro FROM carona AS c WHERE c.data BETWEEN ? AND ? ORDER BY c.id_carro";

        $stmt = $this->db->prepare( $sql );
        $stmt->bindValue( 1, $fromDay );
        $stmt->bindValue( 2, $untilDay );
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }


    public function getCaroneirosPorPeriodo( $fromDay, $untilDay )
    {
        $sql = "";
        $sql .= "SELECT * FROM carona_pessoa as cp";
        $sql .= " JOIN carona as c ON c.id_carona = cp.id_carona";
        $sql .= " WHERE c.data BETWEEN ? AND ?";

        $stmt = $this->db->prepare( $sql );
        $stmt->bindValue( 1, $fromDay );
        $stmt->bindValue( 2, $untilDay );
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }


    public function getCarrosPorFechamento()
    {
        $sql = "SELECT * FROM carro_fechamento WHERE id_fechamento = ?";

        $stmt = $this->db->prepare( $sql );
        $stmt->bindValue( 1, $this->id_fechamento );
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }


    public function insert( $dados )
    {
        $dadosFech = $dados['dadosFech'];
        $dadosPreco = $dados['dadosPreco'];
        $dadosCaroneiros = $dados['dadosCaroneiros'];

        $this->db->beginTransaction();

        $lastId = parent::insert( $dadosFech );

        if ($lastId == 0 || $lastId == null) {
            $this->db->rollback();
            return false;
        } else {
            foreach ($dadosPreco as &$ar) {
                $ar['id_fechamento'] = $lastId;
            }

            if ( $this->insertBatch( 'carro_fechamento', $dadosPreco ) ) {

                $dadosBatch = [];
                foreach ($dadosCaroneiros as $value) {
                    $array = [
                        'id_pessoa' => $value,
                        'id_fechamento' => $lastId,
                        'pagou' => 0
                    ];
                    array_push($dadosBatch, $array);
                }

                if ( $this->insertBatch( 'pessoa_fechamento', $dadosBatch ) ) {
                    $_SESSION['idFechamento'] = $lastId;
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

    }

    public function update( $dados, $table = null )
    {
        $this->db->beginTransaction();

        if ( ( parent::delete( $table ) ) ) {

            $dadosBatch = $dados;

            if ( $this->insertBatch( $table, $dadosBatch ) ) {
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
    public function getIdFechamento()
    {
        return $this->id_fechamento;
    }


    /**
     * @return mixed
     */
    public function getMes()
    {
        return $this->mes;
    }

    /**
     * @param mixed $mes
     *
     * @return self
     */
    public function setMes($mes)
    {
        $this->mes = $mes;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * @param mixed $ano
     *
     * @return self
     */
    public function setAno($ano)
    {
        $this->ano = $ano;

        return $this;
    }
}
