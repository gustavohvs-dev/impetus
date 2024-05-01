<?php

    use app\components\Core;

?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <?php Core::headerLogin(); ?>
  </head>

  <body>
    <div class="page-loading active">
      <div class="page-loading-inner">
        <div class="page-spinner"></div><span>Aguarde</span>
      </div>
    </div>
    <main class="page-wrapper">

      <section class="position-relative h-100 pb-4">

        <div class="container d-flex flex-wrap justify-content-center justify-content-xl-start h-100">
          <div class="w-100 align-self-end pt-1 pt-md-4 pb-4" style="max-width: 526px;">
            <div class="text-center mb-2">
              <img src="app/public/assets/loginLogo.png" alt="Logo" width="500"/>
            </div>
            <form action="auth" class="user" method="POST">
                <div class="position-relative mb-4">
                    <label for="username" class="form-label fs-base">Usuário</label>
                    <input type="text" id="username" name="username" class="form-control form-control-lg" value="">
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label fs-base">Senha</label>
                    <div class="password-toggle">
                    <input type="password" id="password" name="password" class="form-control form-control-lg">
                    </div>
                </div>
                <button type="submit" class="btn btn-info shadow-info btn-lg w-100">Entrar</button>
            </form>
            <?php 
              $urlParams = Core::urlParams();
              if(isset($urlParams['error'])){
                echo '<div class="alert alert-danger mt-3" role="alert">Falha ao autenticar, verifique o usuário e senha</div>';
              }else{
                echo '<div class="alert alert-info mt-3" role="alert">Informe seu usuário e senha para se autenticar</div>';
              }
            ?>
            <!--<a href="#" class="btn btn-link btn-lg w-100 text-primary">Esqueceu sua senha?</a>-->
            <!--<hr class="my-4">-->
            <!--<p class="text-center text-xl-start pb-3 mb-3">Não possui usuário? <a href="account-signup.html">Registre-se aqui.</a></p>-->
          </div>
          <div class="w-100 align-self-end">
            <p class="nav d-block fs-xs text-center text-xl-start pb-2 mb-0">
              &copy; Todos os direitos reservados.
              <a class="nav-link d-inline-block p-0" href="#" rel="noopener">Impetus Framework</a>
            </p>
          </div>
        </div>

        <!-- Background -->
        <div class="position-absolute top-0 end-0 w-50 h-100 bg-position-center bg-repeat-0 bg-size-cover d-none d-xl-block" style="background-image: url(app/public/assets/loginBackground.jpg);"></div>

      </section>
    </main>

  </body>
</html>
