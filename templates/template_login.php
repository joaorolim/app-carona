<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bora-lá</title>
    <link href="<?php echo getBaseURL(); ?>assets/img/Logo_sistema_ ajustado.png" rel="shortcut icon" type="image/ico" />
    <link rel="stylesheet" href="<?php echo getBaseURL(); ?>assets/bootstrap/css/bootstrap.min.css">
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

    <script src="<?php echo getBaseURL(); ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo getBaseURL(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
</body>

</html>
