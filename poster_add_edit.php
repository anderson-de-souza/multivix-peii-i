<?php
    
    require_once __DIR__ . '/database/admindao.php';
    require_once __DIR__ . '/database/posterdao.php';

    session_start();

    $adminLogged = false;

    if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
        $adminLogged = true;
    }

    $logout = false;
    $logout = filter_input(INPUT_GET, 'logout', FILTER_VALIDATE_BOOLEAN);
    
    if ($logout) {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }

    $poster = null;
    $posterId = filter_input(INPUT_GET, 'posterId', FILTER_VALIDATE_INT);

    if ($posterId && $posterId > 0) {
        $poster = PosterDAO::select($posterId);
    }

    $delete = false;
    $delete = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_BOOLEAN);

    if ($delete) {
        PosterDAO::delete($posterId);
        header('Location: index.php');
        exit;
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

    <?php
    
        require_once __DIR__ . '/database/posterdao.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $adminLogged) {
            
            if (isset($_POST['posterTitle'], $_POST['posterHeadline'], $_POST['posterDescription'])) {
                
                $coverImgName = saveCoverImg();
        
                if ($poster) {

                    $poster->setTitle($_POST['posterTitle']);
                    $poster->setHeadline($_POST['posterHeadline']);
                    $poster->setDescription($_POST['posterDescription']);
                    
                    if (isset($coverImgName)) {
                        $poster->setCoverImgName($coverImgName);
                    }

                    PosterDAO::update($poster);

                } else {
                    $poster = new Poster(
                        $_POST['posterTitle'],
                        $_POST['posterHeadline'],
                        $_POST['posterDescription'],
                        $coverImgName
                    );
                    PosterDAO::insert($poster);
                }
                
                header("Location: index.php");
                exit;
                
            }
            
        }

        function saveCoverImg(): ?string {
            
            if (isset($_FILES['posterCoverImg']) && $_FILES['posterCoverImg']['error'] === UPLOAD_ERR_OK) {
                
                $tmpFileName = $_FILES['posterCoverImg']['tmp_name'];
                $originFileName = basename($_FILES['posterCoverImg']['name']);
                $targetDir = __DIR__ . '/resources/poster/cover_img';
                
                $fileExtension = strtolower(pathinfo($originFileName, PATHINFO_EXTENSION));
                $fileExtensionAllowed = ['png', 'jpeg', 'jpg'];
                
                if (in_array($fileExtension, $fileExtensionAllowed) && getimagesize($tmpFileName)) {
                    
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }
                    
                    $newName = uniqid("img_") . bin2hex(random_bytes(8)) . '.' . $fileExtension;
                    $path = $targetDir . '/' . $newName;
                    
                    if (move_uploaded_file($tmpFileName, $path)) {
                        return $newName;
                    }
                    
                }
                
            }

            return null;
            
        }
    
    ?>
        
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

                            <?php if ($adminLogged && $poster): ?>

                            <li class="nav-item">
                                <a class="nav-link" href="?posterId=<?= htmlspecialchars($poster->getId(), ENT_QUOTES) ?>&delete=true">Remover este Cartaz</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="/poster_add_edit.php">Adicionar Novo Cartaz</a>
                            </li>

                            <?php endif; ?>

                            <?php if ($adminLogged): ?>

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

                <div class="w-100 text-center p-3 pt-5">

                    <h1><?= $poster ? 'Editar Cartaz' : 'Adicionar Novo Cartaz' ?></h1>

                </div>

                <div class="w-100 text-center p-3 pt-5">

                    <form action="?posterId=<?= $poster ? $poster->getId(): 0 ?>" method="post" enctype="multipart/form-data">

                        <?php if ($poster): ?>

                        <div class="mb-3 text-start fw-bold">
                            <label for="posterId" class="form-label">Id</label>
                            <input type="number" class="form-control" id="posterId" name="posterId" value="<?= $poster->getId() ?>" disabled>
                        </div>

                        <?php endif; ?>

                        <div class="mb-3 text-start fw-bold">
                            <label for="posterTitle" class="form-label">Título</label>
                            <input type="text" class="form-control" id="posterTitle" name="posterTitle" placeholder="Título" value="<?= $poster ? htmlspecialchars($poster->getTitle(), ENT_QUOTES) : 'Título padrão' ?>">
                        </div>

                        <div class="mb-3 text-start fw-bold">
                            <label for="posterHeadline" class="form-label">Manchete</label>
                            <textarea class="form-control" id="posterHeadline" name="posterHeadline" rows="3"><?= $poster ? htmlspecialchars($poster->getHeadline(), ENT_QUOTES) : 'Manchete padrão' ?></textarea>
                        </div>

                        <div class="mb-3 text-start fw-bold">
                            <label for="posterDescription" class="form-label">Conteúdo/Descrição</label>
                            <textarea class="form-control" id="posterDescription" name="posterDescription" rows="12"><?= $poster ? htmlspecialchars($poster->getDescription(), ENT_QUOTES) : 'Descrição padrão' ?></textarea>
                        </div>

                        <div class="w-100 text-center p-3 pt-5">
                            <img src="./resources/poster/cover_img/<?= $poster ? htmlspecialchars($poster->getCoverImgName(), ENT_QUOTES) : 'img_0.jpg' ?>" id="preview" class="w-100 rounded-3 object-fit-cover" style="max-height: 256px;" alt="<?= $poster ? htmlspecialchars($poster->getTitle(), ENT_QUOTES) : 'Título padrão' ?>">
                        </div>

                        <div class="mb-3 text-start fw-bold">
                            <label for="posterCoverImg" class="form-label">Capa</label>
                            <input class="form-control" type="file" id="posterCoverImg" name="posterCoverImg">
                            <div class="form-text">
                                Arquivo atual: "<?= $poster ? htmlspecialchars($poster->getCoverImgName(), ENT_QUOTES) : 'img_0.jpg' ?>"
                            </div>
                        </div>

                        <div class="d-flex flex-row-reverse">
                            <button type="submit" class="btn btn-primary ms-3"><?= $poster ? 'Editar': 'Criar' ?></button>
                            <a class="btn btn-primary" href="/index.php">Cancelar</a>
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
        
    </main>

    <script>
        const fileInput = document.querySelector('#posterCoverImg');
        const preview = document.querySelector('#preview');

        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };

            reader.readAsDataURL(file);

        });

    </script>
    
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