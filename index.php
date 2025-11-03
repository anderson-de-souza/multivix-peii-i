
<?php
    
    require_once __DIR__ . '/database/admindao.php';

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

?>

<html lang="en">
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    
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

                            <?php if ($adminLogged): ?>

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

            <div class="col-12 col-lg-9 d-flex flex-column">
                
                <div class="w-100 text-center p-3">
                    <div class="row g-0 align-items-center justify-content-center">
                        <div class="col-12 col-md-4 pt-5">
                            <img src="./resources/logo.jpg" id="preview" class="img-fluid" style="max-height: 256px;background: none" alt="logo">
                        </div>
                        <div class="col-12 col-md-8 pt-5">
                            <h1>Seja bem vindo ao Espírito Ecológico</h1>
                        </div>
                    </div>
                    
                </div>
                
                <div class="w-100 text-center p-3">
                    <p>
                        Aqui, acreditamos que conhecimento e consciência se transformam em ação.
                        Nosso objetivo é inspirar e conectar pessoas que desejam construir um planeta mais verde,
                        justo e sustentável. Explore, aprenda e faça parte dessa jornada
                        — cada pequena ação conta para um futuro melhor.
                    </p>
                </div>
                
                <div class="w-100">

                    <div class="row g-0 align-items-center justify-content-center">

                        <?php

                            require_once __DIR__ . '/database/posterdao.php';

                            $posters = PosterDAO::getAllPosters();

                        ?>

                        <?php if (empty($posters)): ?>

                        <p class="text-center text-muted my-5">Nenhum cartaz disponível no momento.</p>

                        <?php elseif ($adminLogged): ?>

                            <?php foreach ($posters as $poster): ?>

                            <div class="col-11 col-md-6 col-lg-4 p-3">
                                
                                <div class="card">
                                    
                                    <img src="./resources/poster/cover_img/<?= htmlspecialchars($poster->getCoverImgName() ?: 'img_0.jpg', ENT_QUOTES)?>" class="card-img-top object-fit-cover" style="max-height: 256px;" alt="<?= htmlspecialchars($poster->getTitle(), ENT_QUOTES)?>">
                                    
                                    <div class="card-body p-3">
                                        <h5 class="card-title"><?= htmlspecialchars($poster->getTitle(), ENT_QUOTES)?></h5>
                                        <p class="card-text"><?= htmlspecialchars($poster->getHeadline(), ENT_QUOTES)?></p>
                                        <div class="text-end">
                                            <a href="/poster_add_edit.php?posterId=<?= htmlspecialchars($poster->getId(), ENT_QUOTES)?>" class="btn btn-transparent text-primary">Edit</a>
                                            <a href="/poster_view.php?posterId=<?= htmlspecialchars($poster->getId(), ENT_QUOTES)?>" class="btn btn-transparent text-primary">Abrir</a>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <?php foreach ($posters as $poster): ?>

                            <div class="col-11 col-md-6 col-lg-4 p-3">
                                
                                <div class="card">
                                    
                                    <img src="./resources/poster/cover_img/<?= htmlspecialchars($poster->getCoverImgName() ?: 'img_0.jpg', ENT_QUOTES)?>" class="card-img-top object-fit-cover" style="max-height: 256px;" alt="<?= htmlspecialchars($poster->getTitle(), ENT_QUOTES)?>">
                                    
                                    <div class="card-body p-3">
                                        <h5 class="card-title"><?= htmlspecialchars($poster->getTitle(), ENT_QUOTES)?></h5>
                                        <p class="card-text"><?= htmlspecialchars($poster->getHeadline(), ENT_QUOTES)?></p>
                                        <div class="text-end">
                                            <a href="/poster_view.php?posterId=<?= htmlspecialchars($poster->getId(), ENT_QUOTES)?>" class="btn btn-transparent text-primary">Abrir</a>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <?php endforeach; ?>

                        <?php endif; ?>


                    </div>

                </div>
                
                <div class="w-100">

                    <div class="row g-0 align-items-center justify-content-center">
                        <div class="col-11 col-md-6 col-lg-6 p-3">
                            
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Eco Quiz</h5>
                                    <p id="quizQuestion" class="card-text">Some quick example text to build on the card title and make up the bulk of the card’s content.</p>
                                    <div class="w-100 d-flex flex-row-reverse">
                                        <button id="quizOptionFalseButton" class="btn btn-primary ms-1">Falso</button>
                                        <button id="quizOptionTrueButton" class="btn btn-primary">Verdadeiro</button>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                    </div>
                    
                </div>
            </div>

        </section>

        <footer class="col-12 row g-0 justify-content-center align-items-center">
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
    
    <script>
        
        const pickedIndexes = []
        
        const range = 20
        
        let index = genIndex()
        let lastIndex = index
        
        function genIndex(range = 20) {
            return Math.floor(Math.random() * 15)
        }
        
        function validateNewIndex() {
            lastIndex = index
            index = genIndex()
            while (pickedIndexes.includes(index)) {
                index = genIndex()
            }
            return index
        }
        
        const quizQuestions = JSON.parse(`[
            { "question": "Plantar árvores ajuda a sequestrar dióxido de carbono da atmosfera.", "answer": true },
            { "question": "Lâmpadas LED consomem mais energia que lâmpadas incandescentes.", "answer": false },
            { "question": "Compostagem doméstica reduz a quantidade de resíduos orgânicos enviados a aterros.", "answer": true },
            { "question": "Tomar banhos mais curtos é uma maneira eficaz de economizar água.", "answer": true },
            { "question": "Carros elétricos não emitem gases de escape durante o uso.", "answer": true },
            { "question": "Deixar aparelhos em modo standby não afeta o consumo de energia.", "answer": false },
            { "question": "Produtos com certificação FSC vêm de manejo florestal responsável.", "answer": true },
            { "question": "Comprar roupas novas frequentemente é mais sustentável do que consertar roupas.", "answer": false },
            { "question": "Energia solar é uma fonte de energia renovável.", "answer": true },
            { "question": "Plásticos rotulados como \\\"biodegradáveis\\\" se degradam rapidamente em qualquer ambiente natural.", "answer": false },
            { "question": "Usar sacolas reutilizáveis ajuda a reduzir o consumo de plástico descartável.", "answer": true },
            { "question": "Misturar recicláveis sujos com recicláveis limpos não prejudica o processo de reciclagem.", "answer": false },
            { "question": "O aquecimento global é causado principalmente pelo aumento de gases de efeito estufa devido a atividades humanas.", "answer": true },
            { "question": "Economizar energia em casa normalmente reduz tanto a conta quanto as emissões de carbono.", "answer": true },
            { "question": "Reduzir o consumo de carne pode diminuir a pegada de carbono pessoal.", "answer": true },
            { "question": "Reuso de águas cinzas (por exemplo, de chuveiro) é permitido e seguro em todas as cidades sem restrições.", "answer": false },
            { "question": "Lâmpadas fluorescentes compactas (CFL) não contêm mercúrio.", "answer": false },
            { "question": "Manguezais e pântanos são importantes para armazenar carbono e proteger zonas costeiras.", "answer": true },
            { "question": "Comprar alimentos produzidos localmente tende a reduzir as emissões relacionadas ao transporte.", "answer": true },
            { "question": "Selo \\\"orgânico\\\" garante que a produção não teve qualquer impacto ambiental.", "answer": false }
        ]`);
        
        const quizQuestion = document.getElementById('quizQuestion')
        const quizOptionTrueButton = document.getElementById('quizOptionTrueButton')
        const quizOptionFalseButton = document.getElementById('quizOptionFalseButton')
        
        let quizQuestionButtonClickable = true
        
        quizOptionTrueButton.addEventListener("click", () => {
            if (quizQuestionButtonClickable) {
                quizOptionTrueButton.classList.replace("btn-primary", quizQuestions[index].answer ? "btn-success": "btn-danger")
                updateQuizCardDelayedWithButtons(() => {
                    quizOptionTrueButton.classList.replace(quizQuestions[lastIndex].answer ? "btn-success": "btn-danger", "btn-primary")
                })
            }
        })
        
        quizOptionFalseButton.addEventListener("click", () => {
            if (quizQuestionButtonClickable) {
                quizOptionFalseButton.classList.replace("btn-primary", !quizQuestions[index].answer ? "btn-success": "btn-danger")
                updateQuizCardDelayedWithButtons(() => {
                    quizOptionFalseButton.classList.replace(!quizQuestions[lastIndex].answer ? "btn-success": "btn-danger", "btn-primary")
                })
            }
        })
        
        function updateQuizCard() {
            quizQuestion.innerHTML = quizQuestions[validateNewIndex()].question
        }
        
        function updateQuizCardDelayed(callback, millis = 1500) {
            setTimeout(() => {
                updateQuizCard()
                callback()
            }, millis)
        }
        
        function updateQuizCardDelayedWithButtons(callback, millis = 1500) {
            quizQuestionButtonClickable = false
            updateQuizCardDelayed(() => {
                callback()
                quizQuestionButtonClickable = true
            }, millis)
        }
        
        updateQuizCard()
        
    </script>
    
</body>
</html>