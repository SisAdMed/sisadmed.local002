<!DOCTYPE html>
<html lang="<?= SITE_LANG ?>">

<head>
    <?= get_favicon(); ?><br>
    <meta charset="<?= SITE_CHARSET ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= SITE_DESC ?>">
    <meta name="author" conten="<?= SITE_DESC ?>">
    <meta name="generator" content="<?= SITE_VERSION ?>">
    <title><?= SITE_DESC ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= ASSETS ?>/app/fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="<?= ASSETS ?>/app/fontawesome/css/brands.css">
    <link rel="stylesheet" href="<?= ASSETS ?>/app/fontawesome/css/solid.css">
    <link rel="stylesheet" href="<?= ASSETS ?>/app/css/style.css">
    <!-- Plugins -->
    <link href="<?= PLUGINS ?>/noty/noty.css" rel="stylesheet">
</head>

<body>
    <img class="wave" src="<?= IMG ?>fondo.png" alt="">
    <div class="container">
        <div class="content-login">
            <form id="loginForm" class="form-signin" novalidate method="POST">
                <!--<img src="<?= get_logo() ?>" alt="">-->
                <h2 style="color: #39686c;"><?= SITE_NAME ?></h2>
                <div class="input-div login">
                    <div class="i">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="div">
                        <h5>Usuario</h5>
                        <input type="text" name="usuario" id="usuario" class="input">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fa fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Contraseña</h5>
                        <input type="password" name="password" id="password" class="input">
                    </div>
                </div>
                <input type="submit" class="btn" value="Iniciar Sesión">
            </form>
        </div>
        <div class="img">
            <img src="<?= IMG ?>/logo.png" alt="">
        </div>
    </div>
</body>
<script>
    const base_url = '<?= base_url; ?>';
</script>
<!-- PLUGINS -->
<script src="<?= PLUGINS ?>/noty/noty.min.js"></script>
<script src="<?= ASSETS ?>/app/js/<?= $data['function_js'] ?>"></script>

</html>