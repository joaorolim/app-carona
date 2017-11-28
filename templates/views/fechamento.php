<h1>Fechamentos</h1>
<?php
    echo getMessage()
?>
<div class="row">
    <div class='col-md-12'>
        <h3>Cadastrar</h3>
    </div>

        <form  name="form_fecham" id="form_fecham" action="<?php echo getBaseURL().'fechamento' ?>" method="post" accept-charset="utf-8">
            <div class="form-group col-md-2">
                <div>
                    <select class="form-control" name="mes" id="mes" required>
                        <option value="0">Selecionar mês</option>
                        <?php
                            foreach ($meses as $key => $value) {
                                if ( $key == $mesPassado) {
                                    echo '<option value="'.$key.'" selected>'.$value.'</option>';
                                } else {
                                    echo '<option value="'.$key.'">'.$value.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group col-md-1">
                <div>
                    <input type="text" class="form-control" id="ano" name="ano" value="<?php echo ( $ano ?? '' ) ?>" placeholder="ano">
                </div>
            </div>
            <div class="form-group col-md-9">
                <div>
                    <button type="submit" class="btn btn-success">Visualizar</button>
                </div>
            </div>
        </form>


</div>
<br/>

<?php
    echo getMessage()
?>

<div class="row">
    <div class='col-md-12'>
        <?php
            if ( $tables ) {
                foreach ($tables as $carId => $table) {
                    if ( $carId == 'total' ) {
                        continue;
                    }
                    echo $table;
                }
            }
        ?>
        <br/><br/>
        <?php if ( $tables ) { ?>
        <form  name="conf_pag" id="conf_pag" action="<?php echo getBaseURL().'fechamento/cadastrar' ?>" method="post" accept-charset="utf-8">
            <div class="form-group">
                <?php echo $tables['total']; ?>
            </div>
            <div class="form-group">
                <div>
                    <button type="submit" class="btn btn-success">Confirmar Pagamento</button>
                    <a class="btn btn-warning" href="<?php echo getBaseURL().'fechamento/alterar' ?>">Alterar Preços</a>
                </div>
            </div>
        </form>
        <?php } ?>
    </div>
</div>
