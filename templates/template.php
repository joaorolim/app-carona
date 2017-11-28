<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <meta property="og:image" content="<?php echo getBaseURL(); ?>assets/img/Logo_sistema_ ajustado.png"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bora-lá</title>
    <link href="<?php echo getBaseURL(); ?>assets/img/Logo_sistema_ ajustado.png" rel="shortcut icon" type="image/ico" />

    <link rel="stylesheet" href="<?php echo getBaseURL(); ?>assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link rel="stylesheet" href="<?php echo getBaseURL(); ?>assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo getBaseURL(); ?>assets/css/Pretty-Header.css">
    <link rel="stylesheet" href="<?php echo getBaseURL(); ?>assets/css/Google-Style-Login.css">
    <link rel="stylesheet" href="<?php echo getBaseURL(); ?>assets/css/Hero-Technology.css">
    <link rel="stylesheet" href="<?php echo getBaseURL(); ?>assets/css/Pretty-Header-1.css">
    <link rel="stylesheet" href="<?php echo getBaseURL(); ?>assets/css/Pretty-Footer.css">
    <link rel="stylesheet" href="<?php echo getBaseURL(); ?>assets/css/Pretty-Registration-Form.css">
</head>

<body>
    <nav class="navbar navbar-default custom-header">
        <div class="container-fluid">
            <div class="navbar-header"><a class="navbar-brand navbar-link" href="/home">Bora-<span class="text-success">lá </span> </a>
                <button class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
            </div>
            <div class="collapse navbar-collapse" id="navbar-collapse">
                <ul class="nav navbar-nav navbar-left links">
                    <li class="active" role="presentation"><a href="/caroneiro"> Caroneiros </a></li>
                    <li class="active" role="presentation"><a href="/carro"> Carros </a></li>
                    <li class="active" role="presentation"><a href="/rota"> Rotas </a></li>
                    <li class="active" role="presentation"><a href="/carona"> Caronas </a></li>
                    <li class="active" role="presentation"><a href="/fechamento"> Fechamento </a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false" href="#"> <span class="caret"></span><img src="<?php echo getBaseURL(); ?>assets/img/Vetor_usuario_AJUSTADO_branco.png" class="dropdown-image"></a>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            <li role="presentation"><a href="03_altsenha.html">Alterar Senha</a></li>
                            <li role="presentation" class="active"><a href="/logout">Sair </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container">

    <?php
      if ( isset( $viewName ) )
      {
        $path = viewsPath() . $viewName . '.php';
        if ( file_exists( $path ) )
        {
          require_once $path;
        }
      }
    ?>

    </div>

    <!-- Modal de Confirmação de Exclusão de Registros-->
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3><span class="glyphicon glyphicon-trash"></span> Deletar Registro</h3>
                </div>
                <div class="modal-body" style="text-align:center;">
                    <h4>Deseja realmente excluir este registro?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                    <a class="btn btn-danger btn-ok">Sim</a>
                </div>
            </div>
        </div>
    </div>
    <!-- / Modal de Confirmação de Exclusão de Registros -->

    <script src="<?php echo getBaseURL(); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo getBaseURL(); ?>assets/js/helper.js"></script>
    <script src="<?php echo getBaseURL(); ?>assets/bootstrap/js/bootstrap.min.js"></script>

    <!-- Script Modal de Confirmação de Exclusão -->
    <script>
        $(document).ready(function(){
            $('#confirm-delete').on('show.bs.modal', function(e) {
                $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
            });
        });
    </script>
    <!-- / Script Modal de Confirmação de Exclusão -->

    <script>
        function addCaroneiro( edit = false) {
            // alert('Adicionar mais 1 caroneiro');
            // var campoCaroneiro = $(".container-caroneiro:last").html();
            var campoCaroneiro = $(".campo-caroneiro:last").html();
            var campoCaroneiro = '<div class="form-group col-md-3"><div class="campo-caroneiro">'+campoCaroneiro+'</div></div>';

            $("#caroneiros").append(containerCaroneiro);
            $(".row-caroneiro:last").append(campoCaroneiro);

            if ( edit ) {
                setDefaultDriver(edit);
            }
        }

        function removeCaroneiro() {
            $(".row-caroneiro:last").remove();
        }

        function disableDriver(thisObj) {
            // var selectedDriver = $('#id_motorista').find(":selected").text();
            var selectedDriver = $("#id_motorista option:selected").val();
            // alert(selectedDriver);
            console.log(thisObj);
            thisObj.children().each(function() {
                // alert($(this).val());
                if ( $(this).val() == selectedDriver) {
                    $(this).attr('disabled', 'disabled');
                } else {
                    $(this).removeAttr('disabled');
                }
            });
        }

        function setDefaultDriver( edit = false) {
            if ( edit ) {
                selectObj = $('.campo-caroneiro:last');
                // console.log( selectObj.children().val(0) );
                selectObj.children().val(0);
                return 1;
            }

            var selectedDriver = $("#id_motorista option:selected").val();

            $('.campo-caroneiro').each(function(i, obj) {
                selectObj = $(this).children().children("option:selected");

                if ( selectObj.val() == selectedDriver) {
                    div = selectObj.parent().parent().parent().parent();

                    if (div[0].className == 'row row-caroneiro') {
                        div.remove();
                    } else {
                        div = selectObj.parent().parent().parent().parent().parent();
                        divToRemove = div.next();
                        select = div.next().children().children().children();

                        if (select.length > 0) {
                            value = select[0].value;
                            divToRemove.remove();
                            selectObj.parent().val(value);
                        } else {
                            selectObj.parent().val(0);
                        }
                    }
                }
            });
        }
    </script>
</body>

</html>
