<h1>Caronas</h1>
<div class="row">
    <div class='col-md-12'>
        <h3>Alterar</h3>
    </div>
    <div class='col-md-12'>
        <form  name="form_carona" id="form_carona" action="<?php echo getBaseURL().'carona/cadastrar' ?>" method="post" accept-charset="utf-8">
            <input type="hidden" id="id_carona" name="id_carona" value="<?php echo ($carona->getIdCarona() ?? 0); ?>">
            <div class="row">
                <div class="form-group col-md-3" style="margin-right:20px">
                    <div>
                        <input type="date" class="form-control" id="data" name="data" value="<?php echo ( $carona->getData() ?? null ); ?>" style="width:65%;" placeholder="dd/mm/aaaa" required>
                    </div>
                </div>
                <div class="form-group col-md-4 col-md-pull-1">
                    <div>
                        <select class="form-control" name="id_rota" id="id_rota" style="width:95%;" required>
                            <option value="0">Selecionar rota</option>
                            <?php
                                foreach ( $rotas as $rota ) {
                                    if ( $rota->id_rota == $carona->getIdRota() ) {
                                        echo '<option value="'.$rota->id_rota.'" selected>'.$rota->descricao.'</option>';
                                    } else {
                                        echo '<option value="'.$rota->id_rota.'">'.$rota->descricao.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group col-md-4 col-md-pull-1">
                    <div>
                        <select class="form-control" name="id_motorista" id="id_motorista" onchange="setDefaultDriver()" style="width:95%;" required>
                            <option value="0">Selecionar motorista</option>
                            <?php
                                foreach ( $motoristas as $motorista ) {
                                    if ( $motorista->id_pessoa == $carona->getMotorista()->getIdPessoa() ) {
                                        echo '<option value="'.$motorista->id_pessoa.'" selected>'.$motorista->nome.'</option>';
                                    } else {
                                        echo '<option value="'.$motorista->id_pessoa.'">'.$motorista->nome.'</option>';
                                    }
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <br/>
            <div><h3>Bora-<span style="color:purple">lá</span></h3></div>

            <div id="caroneiros">
                <div class="row">
                    <div class="form-group col-md-3 col-md-push-3">
                        <div class="btn-plus-minus">
                            <a href="javascript:void(0)" title="Adicionar caroneiro" class="btn btn-success btn-plus" onclick="addCaroneiro(true)"><span>+</span></a> <a href="javascript:void(0)" title="Remover caroneiro" class="btn btn-danger btn-minus" onclick="removeCaroneiro()"><span>-</span></a>
                        </div>
                    </div>


                    <div class="container-caroneiro">
                        <div class="form-group col-md-3 col-md-pull-3">
                            <div class="campo-caroneiro">
                                <select class="form-control" name="id_caroneiro[]" id="id_caroneiro" onkeydown="disableDriver($(this))" onmousedown="disableDriver($(this))" style="width:95%;" required>
                                    <option value="0">Selecionar caroneiro</option>
                                    <?php
                                        if ( isset( $num ) && $num > 0 ) {
                                            foreach ( $caroneiros as $caroneiro ) {
                                                if ( $caroneiro->id_pessoa == $caroneiros_atuais[0]->id_pessoa ) {
                                                    echo '<option value="'.$caroneiro->id_pessoa.'" selected>'.$caroneiro->nome.'</option>';
                                                } else {
                                                    echo '<option value="'.$caroneiro->id_pessoa.'">'.$caroneiro->nome.'</option>';
                                                }
                                            }
                                        } else {
                                            foreach ( $caroneiros as $caroneiro ) {
                                                echo '<option value="'.$caroneiro->id_pessoa.'">'.$caroneiro->nome.'</option>';
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                if ( isset( $num ) && $num > 1 ) {
                    for ($i=1; $i < $num; $i++) {
                ?>
                        <div class="row row-caroneiro">
                            <div class="form-group col-md-3">
                                <div class="campo-caroneiro">
                                    <select class="form-control" name="id_caroneiro[]" id="id_caroneiro" onkeydown="disableDriver($(this))" onmousedown="disableDriver($(this))" style="width:95%;" required>
                                        <option value="0">Selecionar caroneiro</option>
                                        <?php
                                            foreach ( $caroneiros as $caroneiro ) {
                                                if ( $caroneiro->id_pessoa == $caroneiros_atuais[$i]->id_pessoa ) {
                                                    echo '<option value="'.$caroneiro->id_pessoa.'" selected>'.$caroneiro->nome.'</option>';
                                                } else {
                                                    echo '<option value="'.$caroneiro->id_pessoa.'">'.$caroneiro->nome.'</option>';
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>

            </div>
            <br/>
            <div class="row">
                <div class="form-group col-md-2 col-xs-3">
                    <button type="submit" class="btn btn-success" style="width:80px">Alterar</button>
                </div>
                <div class="form-group col-md-2 col-md-pull-1 col-xs-3">
                    <a href="javascript:history.go(-1)" class="btn btn-warning">Cancelar</a>
                </div>
            </div>
        </form>

    </div>
</div>
