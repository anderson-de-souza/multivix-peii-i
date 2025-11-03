<?php
    
    require_once __DIR__ . '/database/admindao.php';
    require_once __DIR__ . '/database/posterdao.php';

    session_start();

    $adminLogged = false;

    if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
        $adminLogged = true;
    }

    
    if (isset($_GET['logout']) && filter_var($_GET['logout'], FILTER_VALIDATE_BOOLEAN)) {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }

    $poster = null;

    $posterId = filter_input(INPUT_GET, 'posterId', FILTER_VALIDATE_INT);
    
    if ($posterId) {
        $poster = PosterDAO::select($posterId);
    }

?>

<html lang="pt-br">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Espírito Eco</title>

    <link rel="icon" type="image/*" href="./resources/ic_recycling.svg">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=recycling" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" 
        rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">

</head>
<body>


    <main class="row g-0 align-items-center justify-content-center">

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
                                <a class="nav-link" href="/poster_add_edit.php?posterId=<?= htmlspecialchars($poster->getId(), ENT_QUOTES) ?>">Editar Cartaz</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="/poster_add_edit.php">Adicionar Novo Cartaz</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="?logout=true">Admin Logout</a>
                            </li>

                            <?php else: ?>

                            <li class="nav-item">
                                <a class="nav-link" href="/admin_log.php">Log Admin</a>
                            </li>

                            <?php endif; ?>

                        </ul>

                    </div>
                </div>
            </nav>
        </header>
        
        <section class="col-12 row g-0 align-items-center justify-content-center">

            <div class="col-12 col-sm-10 col-md-6 d-flex flex-column">
                
            <?php if ($poster): ?>

            <div class="w-100 text-center p-3 pt-5">
                <img src="./resources/poster/cover_img/<?= htmlspecialchars($poster->getCoverImgName() ?: 'img_0.jpg', ENT_QUOTES) ?>" class="w-100 object-fit-cover" style="max-height: 256px;" alt="<?= htmlspecialchars($poster->getTitle(), ENT_QUOTES) ?>">
            </div>

            <div class="w-100 text-center p-3">
                <h1>
                    <?= htmlspecialchars($poster->getTitle(), ENT_QUOTES) ?>
                </h1>
            </div>

            <div class="w-100 text-center p-3">
                <h2><?= htmlspecialchars($poster->getHeadline(), ENT_QUOTES) ?></h2>
            </div>

            <div class="w-100 p-3">
                <p><?= htmlspecialchars($poster->getDescription(), ENT_QUOTES) ?></p>
            </div>

                <?php endif; ?>

            </div>
 
        </section>

        <footer class="row g-0 justify-content-center align-items-center">
            <div class="col-12 text-center p-3">
                <a href="https://www.instagram.com/espirito_eco" target="_blank" class="text-decoration-none">
                    <i class="bi bi-instagram"></i> espirito_eco © 2025
                </a>
            </div>
        </footer>
        
    </main>


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js"
        integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y"
        crossorigin="anonymous">
    </script>
    
</body>
</html>