<h1>Carros</h1>
<div class="row">
    <div class='col-md-5'>
        <h3>Alterar</h3>
        <form  name="form_carro_alt" id="form_carro_alt" action="<?php echo getBaseURL().'carro/cadastrar' ?>" method="post" accept-charset="utf-8">
            <input type="hidden" id="id_carro" name="id_carro" value="<?php echo ($carro->getIdCarro() ?? 0); ?>">
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" id="descricao" name="descricao" value="<?php echo $carro->getDescricao() ?? 'erro' ?>" Placeholder="Descrição do carro..." style="width:70%;" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <select class="form-control" name="id_motorista" id="id_motorista" style="width:70%;" required>
                        <option value="0">Selecionar motorista</option>
                        <?php
                            foreach ( $motoristas as $motorista ) {
                                if ( $motorista->id_pessoa == $carro->getIdPessoa() ) {
                                    echo '<option value="'.$motorista->id_pessoa.'" selected>'.$motorista->nome.'</option>';
                                } else {
                                    echo '<option value="'.$motorista->id_pessoa.'">'.$motorista->nome.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <button type="submit" class="btn btn-success">Alterar</button>
                </div>
            </div>
        </form>

    </div>
</div>

