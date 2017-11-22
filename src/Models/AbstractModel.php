<?php

namespace Fatec\Models;

use Slim\Container;

abstract class AbstractModel
{
    //https://www.tonymarston.net/php-mysql/databaseobjects.html
    /**
     * @var Container
     */
    protected $c;

	protected $db;
	protected $table;
    protected $fieldList;
    protected $pk;

    /**
     * Atributos para paginação
     */
    protected $qtd = 5;         // A quantidade de linhas a serem exibidas por página
    protected $numTotal;        // Total de registro da tabela (do banco de dados)
    protected $exibir = 3;      // Define o valor máximo a ser exibida na página tanto para direita quando para esquerda (class="navegação")
    protected $arrayPaginacao;  // Array com os itens de paginação que serão enviados para a view

	public function __construct(Container $c)
	{
        $this->c = $c;
		$this->db = $this->c->db;
        $this->db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
	}

    public function count( $date_from = null, $date_to = null )
    {
        if ($date_from && $date_to) {
            $sql = "SELECT COUNT(*) FROM {$this->table} WHERE (data BETWEEN ? AND ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue( 1, $date_from );
            $stmt->bindValue( 2, $date_to );
        } else {
            $sql = "SELECT COUNT(*) FROM {$this->table}";
            $stmt = $this->db->prepare($sql);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

	public function list( $pagina, $date_from = null, $date_to = null )
	{

        // echo "<pre>list<br/>";
        // print_r($pagina);
        // echo "<br/>date_from<br/>";
        // print_r($date_from);
        // echo "<br/>date_to<br/>";
        // print_r($date_to);
        // echo "<pre>";
        // exit();

        //Calcula à partir de qual valor será exibido
        $inicio = ( $this->qtd * $pagina ) - $this->qtd;

        if ($date_from && $date_to) {
            $this->numTotal = $this->count( $date_from, $date_to );

            $sql = "SELECT * FROM {$this->table} WHERE (data BETWEEN ? AND ?) LIMIT ?, ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue( 1, $date_from );
            $stmt->bindValue( 2, $date_to );
            $stmt->bindValue( 3, $inicio, \PDO::PARAM_INT );
            $stmt->bindValue( 4, $this->qtd, \PDO::PARAM_INT );
        } else {
            $this->numTotal = $this->count();

            $sql = "SELECT * FROM {$this->table} LIMIT ?, ?";
            $stmt = $this->db->prepare( $sql );
            $stmt->bindValue( 1, $inicio, \PDO::PARAM_INT );
            $stmt->bindValue( 2,  $this->qtd, \PDO::PARAM_INT );
        }

        $this->makeArrayPagination( $pagina );

		$stmt->execute();
		return $stmt->fetchAll(\PDO::FETCH_OBJ);
	}

    public function listAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    public function makeArrayPagination( $pagina )
    {
        //Monta o array que será enviado para a view

        //O calculo do Total de página ser exibido
        $totalPagina= ceil( $this->numTotal / $this->qtd );

        /**
        * Aqui montará o link que voltará uma página
        * Caso o valor seja zero, por padrão ficará o valor 1
        */
        $anterior  = ( ( $pagina - 1 ) == 0 ) ? 1 : $pagina - 1;

        /**
        * Aqui montará o link que vai para próxima página
        * Caso página +1 for maior ou igual ao total, ele terá o valor do total
        * caso contrário, ele pega o valor da página + 1
        */
        $posterior = ( ( $pagina+1 ) >= $totalPagina ) ? $totalPagina : $pagina+1;

        $this->arrayPaginacao = [
            'anterior' => $anterior,
            'pagina' => $pagina,
            'exibir' => $this->exibir,
            'totalPagina' => $totalPagina,
            'posterior' => $posterior
        ];

        return true;
    }

	public function find( $id )
	{
		$sql = "SELECT * FROM {$this->table} WHERE {$this->pk} = ?";
		$stmt = $this->db->prepare( $sql );
		$stmt->bindValue( 1, $id, \PDO::PARAM_INT );
		$stmt->execute();

        $rows = $stmt->fetchAll( \PDO::FETCH_OBJ );

        if ( count( $rows ) <= 0 )
        {
            return null;
        }

        $model = $rows[0];

        foreach ( $model as $modelField => $modelValue )
        {
            $this->{$modelField} = $modelValue;
        }

        return $this;
	}

    public function insert($dados)
    {
        foreach ( $dados as $modelField => $modelValue )
        {
            $this->{$modelField} = $modelValue;
        }

        //"INSERT INTO {$this->table} (campo1, campo2, campo3) VALUES (?, ?, ?)";
        $sql = "INSERT INTO {$this->table} (";

        $placeHolders = [];
        foreach ( $this->fieldList as $modelField ) {
            if ( $modelField != $this->pk ) {
                $sql .= "{$modelField}, ";
                array_push( $placeHolders, '?' );
            }
        }

        $sql = rtrim( $sql, ', ' );
        $placeHolders = " (".implode( ', ', $placeHolders ).") ";

        $sql .= ") VALUES ".$placeHolders;

        $stmt = $this->db->prepare( $sql );

        $pos = 0; // placeholder position
        foreach ( $this->fieldList as $modelField ) {
            if ( $modelField != $this->pk ) {
                $stmt->bindValue( ++$pos, $this->{$modelField} );
            }
        }

        $stmt->execute();

        $lastId = $this->db->lastInsertId();

        return $lastId;
    }


    protected function insertBatch( $table, $arrayFieldsValues )
    {
        try {
            //INSERT INTO table (fielda, fieldb, ... ) VALUES (?,?...), (?,?...)....;
            $sql = "INSERT INTO {$table} (";

            $qM = "("; // questionMarks
            foreach ( $arrayFieldsValues[0] as $key => $value ) {
                $sql .= "{$key}, ";
                $qM .= "?, ";
            }
            $qM = rtrim( $qM, ', ' );
            $qM .= ")";

            $sql = rtrim( $sql, ', ' );
            $sql .= ") VALUES ";

            $n = count($arrayFieldsValues);
            for ($i=0; $i < $n; $i++) {
                $sql .= "{$qM}, ";
            }
            $sql = rtrim( $sql, ', ' );

            $stmt = $this->db->prepare( $sql );

            $pos = 0; // questionMark position
            foreach ( $arrayFieldsValues as $array ) {
                foreach ($array as $key => $value) {
                    $stmt->bindValue( ++$pos, $value );
                }
            }

            return $stmt->execute();

        } catch (\PDOException $e) {
            setMessage( $e->getMessage(), 'error' );
            return false;
        }
    }


    public function update($dados)
    {
        try {

            foreach ( $dados as $modelField => $modelValue )
            {
                $this->{$modelField} = $modelValue;
            }

            //"UPDATE {$this->table} SET campo1=?, campo2=? WHERE campo3=?";
            $sql = "UPDATE {$this->table} SET";

            foreach ( $this->fieldList as $modelField ) {
                if ( $modelField != $this->pk ) {
                    $sql .= " {$modelField}=?,";
                }
            }

            $sql = rtrim($sql, ', ');
            $sql .= " WHERE {$this->pk}=?";

            $stmt = $this->db->prepare($sql);

            $pos = 0; // placeholder position
            foreach ( $this->fieldList as $modelField ) {
                if ( $modelField != $this->pk ) {
                    $stmt->bindValue( ++$pos, $this->{$modelField} );
                }
            }

            $stmt->bindValue( ++$pos, $this->{$this->pk}, \PDO::PARAM_INT );

            return $stmt->execute();

        } catch (\PDOException $e) {
            setMessage( $e->getMessage(), 'error' );
            return false;
        }
    }


    public function delete( $table = null )
    {
        try {
            //"DELETE FROM {$this->table} WHERE campo=?";
            if ( $table ) {
                $sql = "DELETE FROM {$table} WHERE {$this->pk}=?";
            } else {
                $sql = "DELETE FROM {$this->table} WHERE {$this->pk}=?";
            }

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue( 1, $this->{$this->pk}, \PDO::PARAM_INT );

            return $stmt->execute();

        } catch (\PDOException $e) {
            setMessage( $e->getMessage(), 'error' );
            return false;
        }
    }


    public function getArrayPaginacao()
    {
        return $this->arrayPaginacao;
    }
}
