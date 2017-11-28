<h1>Caroneiros</h1>
<div class="row">
    <div class='col-md-5'>
        <h3>Cadastrar</h3>
        <form  name="form_alt" id="form_alt" action="<?php echo getBaseURL().'caroneiro/cadastrar' ?>" method="post" accept-charset="utf-8">
            <div class="form-group">
                <div>
                    <input type="text" class="form-control" id="nome" name="nome" Placeholder="Nome do caroneiro..." style="width:70%;" required>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <input type="checkbox" name="motorista"/> É motorista?
                </div>
            </div>
            <div class="form-group">
                <div>
                    <button type="submit" class="btn btn-success">Cadastrar</button>
                </div>
            </div>
        </form>

        <?php
            echo getMessage()
        ?>
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
