<h1>Caroneiros</h1>
<div class="row">
    <div class='col-md-5'>
        <h3>Alterar</h3>
        <form  name="form_alt" id="form_alt" action="<?php echo getBaseURL().'caroneiro/cadastrar' ?>" method="post" accept-charset="utf-8">
            <input type="hidden" name="id_pessoa" value="<?php echo ($pessoa->getIdPessoa() ?? 0); ?>">
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?php echo $pessoa->getNome() ?? 'erro' ?>" Placeholder="Nome do caroneiro..." style="width:70%;" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="checkbox" name="motorista" <?php echo (($pessoa->getIsDriver() == 1) ? 'checked' : '') ?> /> É motorista?
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

