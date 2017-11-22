<?php

namespace Fatec\Controllers;

use Fatec\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class PessoaController extends ControllerAbstract
{
    public function index( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        //a pagina atual
        $pagina = ( isset( $args['page'] ) ) ? (int)$args['page'] : 1;

        $pessoa = $this->c->Pessoa;
        $result = $pessoa->list( $pagina );

        if ( count( $result ) > 0 ) {
            $columns = array( "ID","Nome","Motorista ?" );
            $table = $this->makeTable( $columns, $result, 'id_pessoa', 'caroneiro/excluir/', 'caroneiro/alterar/' );
        } else {
            return $response->withRedirect('/caroneiro');
        }

        $arrayPaginacao = $pessoa->getArrayPaginacao();

        $paginacao = $this->makePageControllers( $arrayPaginacao, 'caroneiro' );

        return $this->c->renderer->render($response, 'template.php', [
            'paginacao' => $paginacao,
            'viewName' => 'caroneiro',
            'table' => $table
        ]);
    }


    public function alterar( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        $id = $args['id'] ?? 0;
        $pessoa = $this->c->Pessoa;

        $pessoa = $pessoa->find( $id );

        if ( $pessoa ) {
            return $this->c->renderer->render($response, 'template.php', [
                'pessoa' => $pessoa,
                'viewName' => 'caroneiro_alterar'
            ]);
        }

        return $this->c->renderer->render($response, 'template.php', [
            'viewName' => 'error/caroneiro_not_found'
        ]);
    }


    public function cadastrar( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        $postVars = $request->getParsedBody();
        $id = $postVars['id_pessoa'] ?? 0;
        $nome = $postVars['nome'] ?? null;
        $motorista = ( isset( $postVars['motorista'] ) && $postVars['motorista'] == 'on' ) ? 1 : 0;

        // As chaves do vetor devem ser as mesmas do DB
        $dados = [
            'nome' => $nome,
            'is_driver' => $motorista
        ];

        if ( $id != 0 ) {
            // UPDATE
            $pessoa = $this->c->Pessoa;
            $pessoa = $pessoa->find( $id );

            if ( $pessoa != null && $pessoa->update( $dados ) ) {
                return $response->withRedirect('/caroneiro');
            } else {
                setMessage('Erro ao tentar atualizar caroneiro!', 'error');
                return $response->withRedirect('/caroneiro');
            }

        } else {
            // INSERT
            $pessoa = $this->c->Pessoa;

            if ($pessoa->insert($dados)) {
                setMessage('Caroneiro salvo com sucesso!', 'success');
                return $response->withRedirect('/caroneiro');
            } else {
                setMessage('Erro ao tentar salvar caroneiro!', 'error');
                return $response->withRedirect('/caroneiro');
            }
        }
    }


    public function deletar(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $id = $args['id'] ?? 0;

        $pessoa = $this->c->Pessoa;
        $pessoa = $pessoa->find( $id );

        if ( $pessoa != null && $pessoa->delete() ) {
            return $response->withRedirect('/caroneiro');
        } else {
            setMessage('Erro ao tentar deletar caroneiro!', 'error');
            return $response->withRedirect('/caroneiro');
        }
    }

}
