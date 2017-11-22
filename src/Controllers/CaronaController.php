<?php

namespace Fatec\Controllers;

use Slim\Container;
use Fatec\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class CaronaController extends ControllerAbstract
{
    protected $motoristas;
    protected $caroneiros;
    protected $rotas;
    protected $date_from;
    protected $date_to;


    public function __construct( Container $c )
    {
        parent::__construct( $c );

        $pessoa = $this->c->Pessoa;
        $this->motoristas = $pessoa->getMotoristas();
        $this->caroneiros = $pessoa->listAll();

        $rota = $this->c->Rota;
        $this->rotas = $rota->listAll();

        if ( isset( $_SESSION['filter'] ) ) {
            $this->date_from = $_SESSION['filter']['date_from'];
            $this->date_to = $_SESSION['filter']['date_to'];
        }
    }

    public function index( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        $postVars = $request->getParsedBody();
        $date_from = $postVars['date_from'] ?? null;
        $date_to = $postVars['date_to'] ?? null;

        if ( isset( $args['page'] ) ) {
            // se a rota for /carona/pagina/{numero}  (quando clicar nos itens de paginação)
            $pagina = (int)$args['page'];
        } else {
            // se a rota for somente /carona   (quando clicar em Caronas ou Filtrar)
            $pagina = 1;

            if ( $date_from && $date_to ) {  // Se selecionar datas e clicar em filtrar
                $_SESSION['filter'] = [
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                ];

                $this->date_from = $_SESSION['filter']['date_from'];
                $this->date_to = $_SESSION['filter']['date_to'];
            } else {
                $_SESSION['filter'] = null;
                unset($_SESSION['filter']);
                $this->date_from = null;
                $this->date_to = null;
            }
        }

        $carona = $this->c->Carona;

        if ( isset( $_SESSION['filter'] ) ) {
            // $result = $carona->list( $pagina, $_SESSION['filter']['date_from'], $_SESSION['filter']['date_to'] );
            $result = $carona->list( $pagina, $this->date_from, $this->date_to );
        } else {
            $result = $carona->list( $pagina );
        }

        if ( count( $result ) > 0 ) {
            $columns = array( "ID", "Data", "Motorista", "Rota", "Caroneiros" );

            $carro = $this->c->Carro;
            $rota = $this->c->Rota;
            $pessoa = $this->c->Pessoa;

            $aux = [];
            foreach ($result as $cr) {
                $obj = new \stdClass;
                $obj->id_carona = "";
                $obj->data = "";
                $obj->motorista = "";
                $obj->rota = "";
                $obj->caroneiros = "";

                foreach ($cr as $key => $value) {
                    if ($key == 'id_carro') {
                        $carro = $carro->find( $value );
                        $obj->motorista = $carro->getMotorista()->getNome();
                    } elseif ($key == 'id_rota') {
                        $rota = $rota->find( $value );
                        $obj->rota = $rota->getDescricao();
                    } elseif ($key == 'id_carona') {
                        $obj->id_carona = $value;
                        $carona = $carona->find( $value );
                        $caroneiros = $carona->getCaroneirosPorCarona();

                        // $obj->caroneiros = "";
                        foreach ($caroneiros as $caroneiro) {
                            foreach ($caroneiro as $k => $v) {
                                if ($k == 'id_pessoa') {
                                    $pessoa = $pessoa->find( $v );
                                    $obj->caroneiros .= "{$pessoa->getNome()}, ";
                                }
                            }
                        }
                        $obj->caroneiros = rtrim( $obj->caroneiros, ', ' );
                    } elseif ($key == 'data') {
                        $obj->data = dataMySQL_to_dataBr( $value );
                    } else {
                        $obj->{$key} = $value;
                    }
                }
                array_push($aux, $obj);
                $obj = null;
            }

            $result = $aux;

            $table = $this->makeTable( $columns, $result, 'id_carona', 'carona/excluir/', 'carona/alterar/' );
        } else {
            return $response->withRedirect('/carona');
        }

        $arrayPaginacao = $carona->getArrayPaginacao();

        $paginacao = $this->makePageControllers( $arrayPaginacao, 'carona' );

        return $this->c->renderer->render($response, 'template.php', [
            'paginacao' => $paginacao,
            'viewName' => 'carona',
            'table' => $table,
            'motoristas' => $this->motoristas,
            'caroneiros' => $this->caroneiros,
            'rotas' => $this->rotas,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to
        ]);
    }


    public function alterar( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        $id = $args['id'] ?? 0;
        $carona = $this->c->Carona;
        $carona = $carona->find( $id );

        if ( $carona ) {

            $num_caroneiros_atuais = count($carona->getCaroneirosPorCarona()) - 1;
            $caroneiros_atuais = $carona->getCaroneirosPorCarona();

            $aux = [];
            $id_caroneiros_atuais = [];
            foreach ($caroneiros_atuais as $cr_a) {
                $obj = new \stdClass;
                $obj->id_carona = "";
                $obj->id_pessoa = "";

                if ( $cr_a->id_pessoa == $carona->getMotorista()->getIdPessoa() ) {
                    continue;
                }

                foreach ( $cr_a as $key => $value ) {
                    if ( $key == 'id_carona' ) {
                        $obj->id_carona = $value;
                    } elseif ( $key == 'id_pessoa' ) {
                        $obj->id_pessoa = $value;
                        array_push($id_caroneiros_atuais, $value);
                    }
                }
                array_push($aux, $obj);
                $obj = null;
            }

            $caroneiros_atuais = $aux;

            /******************************************************************
             *  Esses valores serão usados para veriricar se houve alteração **
             *****************************************************************/
            array_unshift( $id_caroneiros_atuais, $carona->getMotorista()->getIdPessoa() );

            $dadosCaronaAtual = [
                'id_rota' => $carona->getIdRota(),
                'data' => $carona->getData(),
                'id_carro' => $carona->getIdCarro()
            ];

            $dadosAtuais = array(
                'dadosCarona' => $dadosCaronaAtual,
                'arrayCaroneiros' => $id_caroneiros_atuais
            );

            $_SESSION['caronaAtual'] = [
                'id' => $carona->getIdCarona(),
                'dados' => $dadosAtuais
            ];
            /******************************************************************
             * /Esses valores serão usados para veriricar se houve alteração **
             *****************************************************************/

            return $this->c->renderer->render($response, 'template.php', [
                'carona' => $carona,
                'viewName' => 'carona_alterar',
                'motoristas' => $this->motoristas,
                'caroneiros' => $this->caroneiros,
                'rotas' => $this->rotas,
                'caroneiros_atuais' => $caroneiros_atuais,
                'num' => $num_caroneiros_atuais
            ]);
        }

        return $this->c->renderer->render($response, 'template.php', [
            'viewName' => 'error/carona_not_found'
        ]);
    }


    public function cadastrar( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        $postVars = $request->getParsedBody();

        $id = $postVars['id_carona'] ?? 0;
        $data = $postVars['data'] ?? null;
        $id_rota = $postVars['id_rota'] ?? 0;
        $id_motorista = $postVars['id_motorista'] ?? 0;
        $arrayCaroneiros = $postVars['id_caroneiro'] ?? array();

        array_unshift($arrayCaroneiros, $id_motorista);
        $arrayCaroneiros = array_unique($arrayCaroneiros);

        $arrayCaroneiros = array_filter($arrayCaroneiros, function($value) {
            return $value > 0;
        });

        if ( ! $arrayCaroneiros || $data == null || $id_rota == 0) {
            return $this->c->renderer->render($response, 'template.php', [
                'viewName' => 'error/carona_empty_data'
            ]);
        }

        $carro = $this->c->Carro;
        $carro = $carro->getCarroPorMotorista( $id_motorista );

        if ( $carro == null || $carro == 0) {
            return $this->c->renderer->render($response, 'template.php', [
                'viewName' => 'error/carro_empty'
            ]);
        }

        $id_carro = $carro[0]->id_carro;

        // As chaves do vetor devem ser as mesmas do DB
        $dadosCarona = [
            'id_rota' => $id_rota,
            'data' => $data,
            'id_carro' => $id_carro
        ];

        $dados = array(
            'dadosCarona' => $dadosCarona,
            'arrayCaroneiros' => $arrayCaroneiros
        );

        if ( $id != 0 ) {
            // UPDATE

            if ( $id == $_SESSION['caronaAtual']['id'] ) {  // checa se não houve alteração do id

                // checa se houve alteração dos dados da carona ou dos caroneiros
                $comparaCarona = comparaArrays( $dados['dadosCarona'], $_SESSION['caronaAtual']['dados']['dadosCarona'] );
                $comparaCaroneiros = comparaArrays( $dados['arrayCaroneiros'], $_SESSION['caronaAtual']['dados']['arrayCaroneiros'] );

                if ( $comparaCarona && $comparaCaroneiros ) {
                    // Não houve alteração em nenhum dos dados...
                    setMessage('Não houve nenhuma alteração!', 'info');

                    $_SESSION['caronaAtual'] = null;
                    unset($_SESSION['caronaAtual']);

                    return $response->withRedirect('/carona');

                } else {
                    // Houve alteração em pelo menos um dos dados...
                    $carona = $this->c->Carona;
                    $carona = $carona->find( $id );

                    if ( $carona != null && $carona->update( $dados ) ) {
                        setMessage('Dados da carona atualizados com sucesso!', 'success');
                        return $response->withRedirect('/carona');
                    } else {
                        if ( ! hasMessage() ) {
                            setMessage( 'Erro ao tentar atualizar carona!', 'error' );
                        }

                        $_SESSION['caronaAtual'] = null;
                        unset($_SESSION['caronaAtual']);

                        return $response->withRedirect('/carona');
                    }
                }

            } else {
                setMessage('Erro ao tentar alterar carona... Id diferente!', 'error');

                $_SESSION['caronaAtual'] = null;
                unset($_SESSION['caronaAtual']);

                return $response->withRedirect('/carona');
            }

        } else {
            // INSERT
            $carona = $this->c->Carona;

            if ($carona->insert($dados)) {
                return $response->withRedirect('/carona');
            } else {
                setMessage('Erro ao tentar inserir carona!', 'error');
                return $response->withRedirect('/carona');
            }
        }
    }


    public function deletar(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        $id = $args['id'] ?? 0;

        $carona = $this->c->Carona;
        $carona = $carona->find( $id );

        if ( $carona != null && $carona->delete() ) {
            setMessage('Carona deletada com sucesso!', 'success');
            return $response->withRedirect('/carona');
        } else {
            if ( ! hasMessage() ) {
                setMessage( 'Erro ao tentar deletar carona!', 'error' );
            }
            return $response->withRedirect('/carona');
        }
    }

}
