<h1>Caronas</h1>
<div class="row">
    <div class='col-md-12'>
        <h3>Cadastrar</h3>
    </div>
    <div class='col-md-12'>
        <form  name="form_carona" id="form_carona" action="<?php echo getBaseURL().'carona/cadastrar' ?>" method="post" accept-charset="utf-8">
            <div class="row">
                <div class="form-group col-md-3" style="margin-right:20px">
                    <div>
                        <input type="date" class="form-control" id="data" name="data" style="width:65%;" required>
                    </div>
                </div>
                <div class="form-group col-md-4 col-md-pull-1">
                    <div>
                        <select class="form-control" name="id_rota" id="id_rota" style="width:95%;" required>
                            <option value="0">Selecionar rota</option>
                            <?php
                                foreach ( $rotas as $rota ) {
                                    echo '<option value="'.$rota->id_rota.'">'.$rota->descricao.'</option>';
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
                                    echo '<option value="'.$motorista->id_pessoa.'">'.$motorista->nome.'</option>';
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
                            <a href="javascript:void(0)" title="Adicionar caroneiro" class="btn btn-success btn-plus" onclick="addCaroneiro()"><span>+</span></a> <a href="javascript:void(0)" title="Remover caroneiro" class="btn btn-danger btn-minus" onclick="removeCaroneiro()"><span>-</span></a>
                        </div>
                    </div>
                    <div class="container-caroneiro">
                        <div class="form-group col-md-3 col-md-pull-3">
                            <div class="campo-caroneiro">
                                <select class="form-control" name="id_caroneiro[]" id="id_caroneiro" onkeydown="disableDriver($(this))" onmousedown="disableDriver($(this))" style="width:95%;" required>
                                    <option value="0">Selecionar caroneiro</option>
                                    <?php
                                        foreach ( $caroneiros as $caroneiro ) {
                                            echo '<option value="'.$caroneiro->id_pessoa.'">'.$caroneiro->nome.'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-12">
                    <div>
                        <button type="submit" class="btn btn-success">Cadastrar</button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
<br/>

<?php
    echo getMessage()
?>

<div class="row">
    <div class='col-md-12'>
        <h3>Lista</h3>
        <form id="form_filtro" name="form_filtro" class="form-inline" action="<?php echo getBaseURL().'carona' ?>" method="post" accept-charset="utf-8">
            <div class="row">
                <div class="form-group col-md-3">
                    <div>
                        <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo $date_from ?? null ?>" style="width:65%;" required>
                    </div>
                </div>
                <div class="form-group col-md-3 col-md-pull-1">
                    <div>
                        <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo $date_to ?? null ?>" style="width:65%;" required>
                    </div>
                </div>
                <div class="form-group col-md-2 col-md-pull-2">
                    <div>
                        <button type="submit" class="btn btn-success btn-filter">Filtrar</button>
                    </div>
                </div>
            </div>
        </form>
        <br/>
        <div class="table-responsive">
            <div class="table">
                <?php echo $table; ?>

                <div class="navegacao">
                    <?php echo $paginacao; ?>
                </div>
            </div>
        </div>
    </div>
</div>
