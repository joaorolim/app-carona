<?php

namespace Fatec\Controllers;

use Slim\Container;
use Fatec\Controllers\ControllerAbstract;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class FechamentoController extends ControllerAbstract
{

    public function __construct( Container $c )
    {
        parent::__construct( $c );
    }

    public function index( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        $postVars = $request->getParsedBody();
        $mes = $postVars['mes'] ?? null;
        $ano = $postVars['ano'] ?? null;

        $_SESSION['idFechamento'] = null;
        unset($_SESSION['idFechamento']);

        if ( $mes && $ano) {
            $mesPassado = $mes;

            // Montar parâmetro do SQL => BETWEEN '2017-10-01 00:00:00' AND '2017-10-31 00:00:00'
            $lastDay = getUltimoDiaMes( $mes, $ano );  // último dia do mês

            $fromDay = $ano.'-'.$mes.'-01';
            $untilDay = $ano.'-'.$mes.'-'.$lastDay;

            $fechamento = $this->c->Fechamento;

            // Verifica se já há fechamento nesse período
            $dadosFechamento = $fechamento->getFechamentoPorMes( $mes, $ano, $fromDay, $untilDay );

            $carro = $this->c->Carro;

            if ( $dadosFechamento ) {

                $_SESSION['idFechamento'] = $dadosFechamento[0]->id_fechamento;

                $arrayTable = [];
                foreach ($dadosFechamento as $fech) {
                    $arrayTable['total'][$fech->caroneiro]['id'] = $fech->id_caroneiro;

                    if ( isset($arrayTable[$fech->id_carro][$fech->caroneiro]['dias']) ) {
                        $arrayTable[$fech->id_carro][$fech->caroneiro]['dias'] += 1;
                        $arrayTable['total'][$fech->caroneiro]['dias'] += 1;
                    } else {
                        $arrayTable[$fech->id_carro][$fech->caroneiro]['dias'] = 1;
                        if( isset($arrayTable['total'][$fech->caroneiro]['dias']) ) {
                            $arrayTable['total'][$fech->caroneiro]['dias'] += 1;
                        } else {
                            $arrayTable['total'][$fech->caroneiro]['dias'] = 1;
                        }
                    }

                    if ( isset($arrayTable[$fech->id_carro][$fech->caroneiro]['km']) ) {
                        $arrayTable[$fech->id_carro][$fech->caroneiro]['km'] += $fech->km;
                        $arrayTable['total'][$fech->caroneiro]['km'] += $fech->km;
                    } else {
                        $arrayTable[$fech->id_carro][$fech->caroneiro]['km'] = $fech->km;

                        if( isset($arrayTable['total'][$fech->caroneiro]['km']) ) {
                            $arrayTable['total'][$fech->caroneiro]['km'] += $fech->km;
                        } else {
                            $arrayTable['total'][$fech->caroneiro]['km'] = $fech->km;;
                        }
                    }

                    if ( isset($arrayTable[$fech->id_carro][$fech->caroneiro]['valor']) ) {
                        $arrayTable[$fech->id_carro][$fech->caroneiro]['valor'] += ($fech->km / $fech->media_km_litro) * $fech->media_comb;
                    } else {
                        $arrayTable[$fech->id_carro][$fech->caroneiro]['valor'] = ($fech->km / $fech->media_km_litro) * $fech->media_comb;
                    }

                    if ( isset($arrayTable['total'][$fech->caroneiro]['valor']) ) {
                        $arrayTable['total'][$fech->caroneiro]['valor'] += ($fech->km / $fech->media_km_litro) * $fech->media_comb;
                    } else {
                        $arrayTable['total'][$fech->caroneiro]['valor'] = ($fech->km / $fech->media_km_litro) * $fech->media_comb;
                    }

                    $arrayTable[$fech->id_carro][$fech->caroneiro]['pagou'] = $fech->pagou;
                    $arrayTable['total'][$fech->caroneiro]['pagou'] = $fech->pagou;
                }

                $tables = [];
                $columns = array( "Caroneiro", "Dias", "KM", "Valor (R$)" );
                foreach ($arrayTable as $carId => $array) {
                    if ($carId == 'total') {
                        $columnsForm = array( "Caroneiro", "Dias", "KM", "Valor (R$)", "Pagou" );
                        $tables[$carId] = $this->makeFechTableForm( $columnsForm, $array );
                    } else {
                        $tables[$carId] = $this->makeFechTable( $columns, $array, $carId );
                    }
                }

            } else {
                $carrosUtilizados = $fechamento->getCarrosPorPeriodo( $fromDay, $untilDay );

                if ( ! $carrosUtilizados ) {
                    setMessage( 'Não há dados para este período!', 'info' );
                    return $response->withRedirect( '/fechamento' );
                }

                $aux = [];
                foreach ($carrosUtilizados as $cars) {
                    foreach ($cars as $value) {
                        $aux[] = $value;
                    }
                }

                $carrosUtilizados = array_unique($aux);

                $aux = [];
                foreach ($carrosUtilizados as $idCar) {
                    $obj = new \stdClass;
                    $obj->id_fechamento = 0;
                    $obj->id_carro = $idCar;
                    $obj->media_comb = null;
                    $obj->media_km_litro = null;
                    $obj->motorista = $carro->find( $idCar )->getMotorista()->getNome();
                    array_push($aux, $obj);
                    $obj = null;
                }

                $carrosUtilizados = $aux;

                $mesArray = getAllMeses($mes);

                $mes = $mesArray[$mes];

                return $this->c->renderer->render($response, 'template.php', [
                    'mes' => $mes,
                    'ano' => $ano,
                    'fechamento' => $fechamento,
                    'viewName' => 'fechamento_precos_form',
                    'carrosUtilizados' => $carrosUtilizados,
                ]);
            }

        } else {
            $ano = date('Y');
            $mesPassado = date('m') - 1;
        }

        return $this->c->renderer->render($response, 'template.php', [
            'meses' => getAllMeses(),
            'mesPassado' => $mesPassado,
            'ano' => $ano,
            'dadosFechamento' => $dadosFechamento ?? null,
            'viewName' => 'fechamento',
            'tables' => $tables ?? null
        ]);
    }


    private function makeFechTable( $columns, $array, $carId )
    {
        $carro = $this->c->Carro;
        $carro = $carro->find( $carId );

        $table = '<table>';
        $table .= '<caption>Motorista: '.$carro->getMotorista()->getNome().'</caption>';

        // head of the table
        $table .=  '<tr>';
        foreach ($columns as $column)
        {
            $table .= '<th>'.$column.'</th>';
        }
        $table .=  '</tr>';


        // body of the table
        foreach ($array as $p => $ar)
        {
            $table .=  '<tr>';
            $table .= '<td>'.htmlspecialchars( $p ).'</td>';

            foreach ($ar as $key => $value)
            {
                if ( $key == 'pagou') {
                    continue;
                }

                if ( $key == 'valor') {
                    $table .= '<td>'.htmlspecialchars( reais( $value ) ).'</td>';
                } else {
                    $table .= '<td>'.htmlspecialchars( $value ).'</td>';
                }
            }
            $table .=  '</tr>';
        }

        $table .=  '</table>';

        return $table;
    }


    private function makeFechTableForm( $columnsForm, $array)
    {
        $_SESSION['payers'] = null;
        unset($_SESSION['payers']);

        $table = '<div class="table-responsive">';
        $table .= '<div class="table">';
        $table .= '<table>';
        $table .= '<caption> TOTAL </caption>';

        // head of the table
        $table .=  '<tr>';
        foreach ($columnsForm as $column)
        {
            $table .= '<th>'.$column.'</th>';
        }
        $table .=  '</tr>';


        // body of the table
        foreach ($array as $p => $ar)
        {
            $table .=  '<tr>';
            $table .= '<td>'.htmlspecialchars( $p ).'</td>';

            foreach ($ar as $key => $value)
            {
                if ($key == 'id') {
                    continue;
                }

                if ( $key == 'valor') {
                    $table .= '<td>'.htmlspecialchars( reais( $value ) ).'</td>';
                } elseif ( $key == 'pagou' ) {
                    $_SESSION['payers'][] = $ar['id'];
                    $table .= '<td>'.( ($value == 1) ? '<input type="checkbox" name="pagou[id]['.$ar['id'].']" checked/>' : '<input type="checkbox" name="pagou[id]['.$ar['id'].']"/>' ).'</td>';
                } else {
                    $table .= '<td>'.htmlspecialchars( $value ).'</td>';
                }
            }
            $table .=  '</tr>';
        }

        $table .=  '</table>';
        $table .= '</div>';
        $table .= '</div>';

        return $table;
    }


    public function alterar( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        $idFech = $_SESSION['idFechamento'] ?? 0;
        $fechamento = $this->c->Fechamento;
        $fechamento = $fechamento->find( $idFech );

        $carro = $this->c->Carro;

        $carrosUtilizados = $fechamento->getCarrosPorFechamento();

        foreach ($carrosUtilizados as $car) {
            foreach ($car as $key => $value) {
                if ( $key == 'id_carro' ) {
                    $car->motorista = $carro->find( $value )->getMotorista()->getNome();
                }
            }
        }

        $mesArray = getAllMeses($fechamento->getMes());
        $mes = $mesArray[$fechamento->getMes()];

        return $this->c->renderer->render($response, 'template.php', [
            'mes' => $mes,
            'ano' => $fechamento->getAno(),
            'fechamento' => $fechamento,
            'viewName' => 'fechamento_precos_form',
            'carrosUtilizados' => $carrosUtilizados,
        ]);

    }


    public function cadastrar( ServerRequestInterface $request, ResponseInterface $response, array $args )
    {
        $postVars = $request->getParsedBody();
        $pagou = $postVars['pagou']['id'] ?? array();
        if ( $pagou ) {
            $pagou = array_keys($pagou);
        }

        $comb = $postVars['comb']['id'] ?? array();
        $kml = $postVars['kml']['id'] ?? array();

        $idFech = $_SESSION['idFechamento'] ?? 0;
        $payers = $_SESSION['payers'] ?? null;

        $dados = [];
        foreach ( $payers as $id_payer ) {
            if ( in_array($id_payer, $pagou) ) {
                $array = [
                    'id_pessoa' => $id_payer,
                    'id_fechamento' => $idFech,
                    'pagou' => 1
                ];
            } else {
                $array = [
                    'id_pessoa' => $id_payer,
                    'id_fechamento' => $idFech,
                    'pagou' => 0
                ];
            }
            array_push($dados, $array);
        }

        $dadosPreco = [];
        foreach ( $comb as $key => $value ) {
            $array = [
                'id_fechamento' => $idFech,
                'id_carro' => $key,
                'media_comb' => $value,
                'media_km_litro' => $kml[$key]
            ];
            array_push($dadosPreco, $array);
        }

        $fechamento = $this->c->Fechamento;

        if ( $idFech != 0 ) {
            // UPDATE
            $fechamento = $fechamento->find( $idFech );

            if ( $comb && $kml ) {
                if ($fechamento->update( $dadosPreco, 'carro_fechamento' )) {
                    return $response->withRedirect('/fechamento');
                } else {
                    setMessage('Erro ao tentar atualizar preços!', 'error');
                    return $response->withRedirect('/fechamento');
                }
            } else {
                if ($fechamento->update( $dados, 'pessoa_fechamento' )) {
                    return $response->withRedirect( '/fechamento' );
                } else {
                    setMessage( 'Erro ao tentar atualizar fechamento!', 'error' );
                    return $response->withRedirect( '/fechamento' );
                }
            }

        } else {
            // INSERT
            $mes = $postVars['mes'] ?? null;
            $mesArray = getAllMeses( null,$mes );
            $mes = array_search( $mes, $mesArray );

            $ano = $postVars['ano'] ?? null;

            $dadosFech = [
                'mes' => $mes,
                'ano' => $ano
            ];

            $lastDay = getUltimoDiaMes( $mes, $ano );  // último dia do mês
            $fromDay = $ano.'-'.$mes.'-01';
            $untilDay = $ano.'-'.$mes.'-'.$lastDay;

            $arrayCaroneiros = $fechamento->getCaroneirosPorPeriodo( $fromDay, $untilDay );

            $aux = [];
            foreach ($arrayCaroneiros as $ar) {
                foreach ($ar as $key => $value) {
                    if ( $key == 'id_pessoa') {
                        array_push($aux, $value);
                    }
                }
            }

            $dadosCaroneiros = array_unique($aux);

            $dados = array(
                'dadosFech' => $dadosFech,
                'dadosPreco' => $dadosPreco,
                'dadosCaroneiros' => $dadosCaroneiros
            );

            if ($fechamento->insert($dados)) {
                return $response->withRedirect('/fechamento');
            } else {
                setMessage('Erro ao tentar inserir fechamento!', 'error');
                return $response->withRedirect('/fechamento');
            }
        }
    }

}
