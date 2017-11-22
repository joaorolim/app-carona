<?php

namespace Fatec\Controllers;

use Slim\Container;
use Fatec\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class CarroController extends ControllerAbstract
{
    protected $motoristas;


    public function __construct( Container $c )
    {
        parent::__construct( $c );

        $pessoa = $this->c->Pessoa;
        $this->motoristas = $pessoa->getMotoristas();
    }

    public function index( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        //a pagina atual
        $pagina = ( isset( $args['page'] ) ) ? (int)$args['page'] : 1;

        $carro = $this->c->Carro;
        $result = $carro->list( $pagina );

        if ( count( $result ) > 0 ) {
            $columns = array( "ID","Motorista","Descrição" );

            $pessoa = $this->c->Pessoa;

            $aux = [];
            foreach ($result as $cars) {
                $obj = new \stdClass;
                foreach ($cars as $key => $value) {
                    if ($key != 'id_pessoa') {
                        $obj->{$key} = $value;
                    } else {
                        $pessoa = $pessoa->find( $value );
                        $obj->motorista = $pessoa->getNome();
                    }
                }
                array_push($aux, $obj);
                $obj = null;
            }

            $result = $aux;

            $table = $this->makeTable( $columns, $result, 'id_carro', 'carro/excluir/', 'carro/alterar/' );
        } else {
            return $response->withRedirect('/carro');
        }

        $arrayPaginacao = $carro->getArrayPaginacao();

        $paginacao = $this->makePageControllers( $arrayPaginacao, 'carro' );

        return $this->c->renderer->render($response, 'template.php', [
            'paginacao' => $paginacao,
            'viewName' => 'carro',
            'table' => $table,
            'motoristas' => $this->motoristas
        ]);
    }


    public function alterar( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        $id = $args['id'] ?? 0;
        $carro = $this->c->Carro;
        $carro = $carro->find( $id );

        if ( $carro ) {
            return $this->c->renderer->render($response, 'template.php', [
                'carro' => $carro,
                'viewName' => 'carro_alterar',
                'motoristas' => $this->motoristas
            ]);
        }

        return $this->c->renderer->render($response, 'template.php', [
            'viewName' => 'error/carro_not_found'
        ]);
    }


    public function cadastrar( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        $postVars = $request->getParsedBody();
        $id = $postVars['id_carro'] ?? 0;
        $id_motorista = $postVars['id_motorista'] ?? 0;
        $desc = $postVars['descricao'] ?? null;

        // As chaves do vetor devem ser as mesmas do DB
        $dados = [
            'id_pessoa' => $id_motorista,
            'descricao' => $desc
        ];

        if ( $id != 0 ) {
            // UPDATE
            $carro = $this->c->Carro;
            $carro = $carro->find( $id );

            if ( $carro != null && $carro->update( $dados ) ) {
                return $response->withRedirect('/carro');
            } else {
                echo "Erro ao tentar atualizar carro!";
                exit();
            }

        } else {
            // INSERT
            $carro = $this->c->Carro;

            if ($carro->insert($dados)) {
                return $response->withRedirect('/carro');
            } else {
                echo "Erro ao tentar inserir carro!";
                exit();
            }
        }
    }


    public function deletar(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $id = $args['id'] ?? 0;

        $carro = $this->c->Carro;
        $carro = $carro->find( $id );

        if ( $carro != null && $carro->delete() ) {
            return $response->withRedirect('/carro');
        } else {
            echo "Erro ao tentar deletar carro!";
            exit();
        }
    }

}
