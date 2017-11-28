<h1>Fechamentos</h1>
<?php
    echo getMessage()
?>
<div class="row">
    <div class='col-md-12'>
        <h3><?php echo ( $fechamento->getIdFechamento() != null ) ? 'Alterar Preços' : 'Cadastrar Preços'; ?></h3>
    </div>
</div>

<form  name="form_fecham_preco" id="form_fecham_preco" action="<?php echo getBaseURL().'fechamento/cadastrar' ?>" method="post" accept-charset="utf-8">
    <div class="form-group col-md-2">
        <div>
            <input type="text" class="form-control" <?php echo ( $fechamento->getIdFechamento() != null ) ? 'disabled' : 'name="mes"'; ?> value="<?php echo ( $mes ?? '' ) ?>">
        </div>
    </div>
    <div class="form-group col-md-2">
        <div>
            <input type="text" class="form-control" <?php echo ( $fechamento->getIdFechamento() != null ) ? 'disabled' : 'name="ano"'; ?> value="<?php echo ( $ano ?? '' ) ?>">
        </div>
    </div>
    <div class="clearfix"> </div>
    <br/>

    <?php foreach ($carrosUtilizados as $car) { ?>
    <div class="form-group col-md-3">
        <label for="car">Motorista:</label>
        <input type="text" class="form-control" id="car" value="<?php echo ( $car->motorista ?? '' ) ?>" disabled>
    </div>
    <div class="form-group col-md-3">
        <label for="comb">Média Combustível(R$)</label>
        <input type="text" class="form-control" id="comb" name="<?php echo 'comb[id]['.$car->id_carro.']' ?>" value="<?php echo ( $car->media_comb ?? 0 ) ?>">
    </div>
    <div class="form-group col-md-3">
        <label for="comb">Média Km/l(R$)</label>
        <input type="text" class="form-control" id="kml" name="<?php echo 'kml[id]['.$car->id_carro.']' ?>" value="<?php echo ( $car->media_km_litro ?? 0 ) ?>">
    </div>
    <div class="clearfix"> </div>
    <br/>
    <?php } ?>

    <div class="form-group col-md-12">
        <div>
            <button type="submit" class="btn btn-success">Salvar Preços</button>
        </div>
    </div>

</form>
