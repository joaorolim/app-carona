<?php

namespace Fatec\Controllers;

use Fatec\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class RotaController extends ControllerAbstract
{
    public function index( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        //a pagina atual
        $pagina = ( isset( $args['page'] ) ) ? (int)$args['page'] : 1;

        $rota = $this->c->Rota;
        $result = $rota->list( $pagina );

        if ( count( $result ) > 0 ) {
            $columns = array( "ID","Descrição","Km" );
            $table = $this->makeTable( $columns, $result, 'id_rota', 'rota/excluir/', 'rota/alterar/' );
        } else {
            return $response->withRedirect('/rota');
        }

        $arrayPaginacao = $rota->getArrayPaginacao();

        $paginacao = $this->makePageControllers( $arrayPaginacao, 'rota' );

        return $this->c->renderer->render($response, 'template.php', [
            'paginacao' => $paginacao,
            'viewName' => 'rota',
            'table' => $table
        ]);
    }


    public function alterar( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        $id = $args['id'] ?? 0;
        $rota = $this->c->Rota;

        $rota = $rota->find( $id );

        if ( $rota ) {
            return $this->c->renderer->render($response, 'template.php', [
                'rota' => $rota,
                'viewName' => 'rota_alterar'
            ]);
        }

        return $this->c->renderer->render($response, 'template.php', [
            'viewName' => 'error/rota_not_found'
        ]);
    }


    public function cadastrar( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        $postVars = $request->getParsedBody();
        $id = $postVars['id_rota'] ?? 0;
        $desc = $postVars['descricao'] ?? null;
        $km = $postVars['km'] ?? null;

        // As chaves do vetor devem ser as mesmas do DB
        $dados = [
            'descricao' => $desc,
            'km' => $km
        ];

        if ( $id != 0 ) {
            // UPDATE
            $rota = $this->c->Rota;
            $rota = $rota->find( $id );

            if ( $rota != null && $rota->update( $dados ) ) {
                return $response->withRedirect('/rota');
            } else {
                echo "Erro ao tentar atualizar rota!";
                exit();
            }

        } else {
            // INSERT
            $rota = $this->c->Rota;

            if ($rota->insert($dados)) {
                return $response->withRedirect('/rota');
            } else {
                echo "Erro ao tentar inserir rota!";
                exit();
            }
        }
    }


    public function deletar(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $id = $args['id'] ?? 0;

        $rota = $this->c->Rota;
        $rota = $rota->find( $id );

        if ( $rota != null && $rota->delete() ) {
            return $response->withRedirect('/rota');
        } else {
            echo "Erro ao tentar deletar rota!";
            exit();
        }
    }

}
