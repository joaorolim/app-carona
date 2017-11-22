<h1>Rotas</h1>
<div class="row">
    <div class='col-md-5'>
        <h3>Alterar</h3>
        <form  name="form_rota_alt" id="form_rota_alt" action="<?php echo getBaseURL().'rota/cadastrar' ?>" method="post" accept-charset="utf-8">
            <input type="hidden" id="id_rota" name="id_rota" value="<?php echo ($rota->getIdRota() ?? 0); ?>">
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" id="descricao" name="descricao" value="<?php echo $rota->getDescricao() ?? 'erro' ?>" Placeholder="Descrição da rota..." style="width:70%;" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" id="km" name="km" value="<?php echo $rota->getKm() ?? 'erro' ?>" Placeholder="Km da rota..." style="width:70%;" required>
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

