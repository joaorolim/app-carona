<h1>Carros</h1>
<div class="row">
    <div class='col-md-5'>
        <h3>Cadastrar</h3>
        <form  name="form_rota" id="form_rota" action="<?php echo getBaseURL().'carro/cadastrar' ?>" method="post" accept-charset="utf-8">
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" id="descricao" name="descricao" Placeholder="Descrição do carro..." style="width:70%;" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <select class="form-control" name="id_motorista" id="id_motorista" style="width:70%;" required>
                        <option value="0">Selecionar motorista</option>
                        <?php
                            foreach ( $motoristas as $motorista ) {
                                echo '<option value="'.$motorista->id_pessoa.'">'.$motorista->nome.'</option>';
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <button type="submit" class="btn btn-success">Cadastrar</button>
                </div>
            </div>
        </form>

    </div>

    <div class='col-md-7'>
        <h3>Lista</h3>
        <div class="table-responsive">
            <?php echo $table; ?>

            <div class="navegacao">
                <?php echo $paginacao; ?>
            </div>
        </div>
    </div>
</div>

