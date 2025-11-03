
<?php
    require_once __DIR__ . '/database/admindao.php';

    session_start();

    $adminLogged = false;

    $admin = AdminDAO::getAdmin();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        if (isset($_POST['adminEmail'], $_POST['adminPassword'])) {

            if ($admin) {

                if ($_POST['adminEmail'] === $admin->getEmail() && password_verify($_POST['adminPassword'], $admin->getToken())) {
                    $_SESSION['admin_logged'] = true;
                    $adminLogged = true;

                    header('Location: index.php');

                } else {
                    header('Location: admin_log.php?error=invalid_password');
                    exit;
                }

            } else {

                $newAdmin = new Admin($_POST['adminEmail'], $_POST['adminPassword']);
                AdminDAO::insert($newAdmin);

                $_SESSION['admin_logged'] = true;
                $adminLogged = true;

                header('Location: index.php');

            }

        }

    } else {
        
        if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
            
            $adminLogged = true;

            header('Location: index.php');

        }

        
        if (isset($_GET['logout']) && filter_var($_GET['logout'], FILTER_VALIDATE_BOOLEAN)) {
            session_unset();
            session_destroy();
            header('Location: admin_log.php');
            exit;
        }

    }

?>


<html lang="pt-br">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Espírito Eco</title>

    <link rel="icon" type="image/*" href="./resources/ic_recycling.svg">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=recycling" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">

</head>
<body>
    
    <main class="row g-0">

        <header class="col-12 row g-0 align-items-center justify-content-center bg-success">

            <nav class="col-12 col-lg-6 navbar" data-bs-theme="dark">

                <div class="container-fluid">

                    <span class="material-symbols-outlined text-center text-light">recycling</span>

                    <span class="mb-0 h4 text-center text-light">Espírito Eco</span>
                    
                    <button class="navbar-toggler" 
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarNavDropdown"
                        aria-controls="navbarNavDropdown"
                        aria-expanded="false"
                        aria-label="Toggle navigation">

                        <span class="navbar-toggler-icon"></span>

                    </button>
                    
                    <div class="collapse navbar-collapse" id="navbarNavDropdown">

                        <ul class="navbar-nav">

                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="/index.php">Home</a>
                            </li>

                            <?php if ($adminLogged): ?>

                            <li class="nav-item">
                                <a class="nav-link" href="/poster_add_edit.php">Adicionar Novo Cartaz</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="?logout=true">Admin Logout</a>
                            </li>

                            <?php endif; ?>

                        </ul>

                    </div>
                </div>
            </nav>
        </header>

        <section class="col-12 row g-0 align-items-center justify-content-center">

            <div class="col-12 col-sm-10 col-md-6 d-flex flex-column">
                
                <div class="w-100 text-center p-3 pt-5">
                    <h1>Log Admin</h1>
                </div>

                <div class="w-100 text-center p-3 pt-5">

                    <form method="post">
                        <div class="mb-3 text-start fw-bold">
                            <label for="adminEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="adminEmail" name="adminEmail" placeholder="name@example.com">
                        </div>
                        <div class="mb-3 text-start fw-bold">
                            <label for="adminPassword" class="form-label">Password</label>
                            <input type="password" id="adminPassword" name="adminPassword" class="form-control" aria-describedby="passwordHelpBlock">
                            <div id="passwordHelpBlock" class="form-text">
                                Sua senha deve ter entre 8 e 20 caracteres, 
                                conter letras e números, e não deve conter espaços, 
                                caracteres especiais ou emojis.
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-primary" type="submit">Log in</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <footer class="row g-0 justify-content-center align-items-center">
            <div class="col-12 text-center p-3">
                <a href="https://www.instagram.com/espirito_eco" target="_blank" class="text-decoration-none">
                    <i class="bi bi-instagram"></i> espirito_eco © 2025
                </a>
            </div>
        </footer>

        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <img src="..." class="rounded me-2" alt="...">
                    <strong class="me-auto">Espírito Eco</strong>
                    <small>Mensagem</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    Email ou Senha Inválidos!
                </div>
            </div>
        </div>
        
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y"
        crossorigin="anonymous">
    </script>

    <script>
        const params = new URLSearchParams(window.location.search);
        if (params.get('error') === 'invalid_password') {
            const toastLive = document.getElementById('liveToast')
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLive)
            setTimeout(() => toastBootstrap.show(), 500);
        }
    </script>

</body>
</html>