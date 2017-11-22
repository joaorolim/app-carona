<div class="login-card"><img src="<?php echo getBaseURL(); ?>assets/img/Logo_sistema_ ajustado.png" class="profile-img-card">
    <p class="profile-name-card"> </p>
    <form id="form_login" class="form-signin" action="/login" method="post">
        <span class="reauth-email"> </span>
        <input class="form-control" type="email"    name="email" id="email" placeholder="Email address" required="true" autofocus="">
        <input class="form-control" type="password" name="pass"  id="pass"  placeholder="Password"      required="true">
        <div class="checkbox">
            <div class="checkbox">
                <label>
                <input type="checkbox">Remember me</label>
            </div>
        </div>
        <a class="btn btn-primary btn-block btn-lg btn-signin" role="button" href="javascript:{}" onclick="document.getElementById('form_login').submit(); return false;">Entrar</a>
    </form>
    <a href="#" class="forgot-password">Esqueceu sua senha?</a>
</div>
<span class="caret"></span>
