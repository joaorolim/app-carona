<?php

namespace Fatec\Controllers;

use Slim\Container;

abstract class ControllerAbstract
{
    /**
     * @var Container
     */
    protected $c;

    /**
     * ControllerAbstract constructor.
     * @param Container $c
     */
    public function __construct(Container $c)
    {
        $this->c = $c;
    }


    protected function makeTable($columns, $rows, $desc_id, $rota_exc, $rota_alt)
    {
        $table = '<table>';

        // head of the table
        $table .=  '<tr>';
        foreach ($columns as $column)
        {
            $table .= '<th>'.$column.'</th>';
        }
        $table .= '<th> </th>';
        $table .= '<th> </th>';
        $table .=  '</tr>';


        // body of the table
        foreach ($rows as $obj)
        {
            $table .=  '<tr>';

            foreach ($obj as $key => $value)
            {
                if ( $key == 'is_driver' )
                {
                    $table .= '<td>'.htmlspecialchars( (($value == 1) ? 'sim' : 'não') ).'</td>';
                }
                else
                {
                    $table .= '<td>'.htmlspecialchars( $value ).'</td>';
                }
            }
            // $table .= '<td><a class="exc_tbl" href="/caroneiro/excluir/'.$obj->id_pessoa.'">Excluir</td>';
            $table .= '<td><a href="#" data-href="'.getBaseURL().$rota_exc.$obj->$desc_id.'" title="Excluir" data-toggle="modal" data-target="#confirm-delete" class="btn btn-xs"><span class="glyphicon glyphicon-trash"></span></a></td>';
            $table .= '<td><a class="btn btn-xs" title="Alterar" href="'.getBaseURL().$rota_alt.$obj->$desc_id.'"><span class="glyphicon glyphicon-pencil"></span></td>';
            $table .=  '</tr>';
        }

        $table .=  '</table>';

        return $table;
    }


    protected function makePageControllers( $array, $rota )
    {
        $paginacao = '';
        $paginacao .= '<a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/1'.'" title="primeira">&lt;&lt;</a> | ';
        $paginacao .= '<a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/'.$array['anterior'].'" title="anterior">&lt;</a> | ';

        /**
        * O loop para exibir os valores à esquerda
        */
        for($i = $array['pagina']-$array['exibir']; $i <= $array['pagina']-1; $i++){
            if($i > 0) {
                $paginacao .= '<a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/'.$i.'"> '.$i.' </a>';
            }
        }

        /**
        * Depois o link da página atual
        */
        $paginacao .= '<a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/'.$array['pagina'].'" style="background-color:lightgrey"><strong>'.$array['pagina'].'</strong></a>';

        /**
        * O loop para exibir os valores à direita
        */
        for($i = $array['pagina']+1; $i < $array['pagina']+$array['exibir']; $i++){
            if($i <= $array['totalPagina']) {
                $paginacao .= '<a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/'.$i.'"> '.$i.' </a>';
            }
        }

        /**
        * Agora monta o Link para Próxima Página
        * Depois O link para Última Página
        */
        $paginacao .= ' | <a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/'.$array['posterior'].'" title="próxima">&gt;</a> | ';
        $paginacao .= '  <a class="naveg-link" href="'.getBaseURL().$rota.'/pagina/'.$array['totalPagina'].'" title="última">&gt;&gt;</a>';

        return $paginacao;
    }
}
