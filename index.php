
<?php
    
    require_once __DIR__ . '/database/admindao.php';

    session_start();

    $adminLogged = false;

    if (isset($_SESSION['admin_logged']) && $_SESSION['admin_logged'] === true) {
        $adminLogged = true;
    }

    $logout = filter_input(INPUT_GET, 'logout', FILTER_VALIDATE_BOOLEAN);
    
    if ($logout) {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }

    $searchQuery = filter_input(INPUT_POST, 'searchQuery', FILTER_UNSAFE_RAW);

    error_log("Search Query: $searchQuery");

    $currentPage = filter_input(INPUT_GET, 'currentPage', FILTER_VALIDATE_INT);

    if (!$currentPage) {
        $currentPage = 1;
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

            <div class="col-12 col-lg-6">

                <nav class="navbar" data-bs-theme="dark">

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

            </div>
            
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

                <?php

                    require_once __DIR__ . '/database/posterdao.php';

                    $postersCount = PosterDAO::getCount();
                    if ($postersCount > 3):

                ?>

                <div class="w-100">

                    <div class="row g-0 align-items-center justify-content-center">
                        <div class="col-11 col-md-6 col-lg-6 p-3">
                            
                            <div class="w-100">
                                <form method="post" class="d-flex" role="search">

                                    <?php

                                        if (isset($_POST['back'])) {
                                            $searchQuery = null;
                                        }

                                        if (!empty($searchQuery)):

                                    ?>

                                    <button class="btn btn-outline-success me-2" type="submit" name="back"><i class="bi bi-arrow-left"></i></button>
                                    
                                    <?php endif; ?>

                                    <input class="form-control me-2" type="search" name="searchQuery" placeholder="Ex: A vida Mais Verde..." aria-label="Buscar Cartaz" value="<?= htmlspecialchars($searchQuery, ENT_QUOTES) ?>"/>
                                    <button class="btn btn-outline-success" type="submit">Buscar</button>
                                </form>
                            </div>
                            
                        </div>

                    </div>
                    
                </div>
                
                <?php endif; ?>
                
                <div class="w-100">

                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-0 align-items-stretch justify-content-center">

                        <?php

                            $posters = [];

                            if (isset($searchQuery)) {
                                $posters = PosterDAO::search($searchQuery, $currentPage);
                            } else {
                                $posters = PosterDAO::getPage($currentPage);
                            }

                        ?>

                        <?php if (empty($posters)): ?>

                        <p class="text-center text-muted my-5">Nenhum cartaz disponível no momento.</p>

                        <?php else: ?>

                            <?php foreach ($posters as $poster): ?>

                            <div class="col-11 col-md-6 col-lg-4 d-flex align-items-stretch justify-content-center p-3">
                                
                                <div class="card w-100 h-100 d-flex flex-column">
                                    
                                    <img src="./resources/poster/cover_img/<?= htmlspecialchars($poster->getCoverImgName() ?: 'img_0.jpg', ENT_QUOTES) ?>" class="card-img-top" style="height: 180px; object-fit: cover;" alt="<?= htmlspecialchars($poster->getTitle(), ENT_QUOTES)?>">
                                    
                                    <div class="card-body p-3 d-flex flex-column">
                                        <h5 class="card-title mb-2 text-truncate"><?= htmlspecialchars($poster->getTitle(), ENT_QUOTES)?></h5>
                                        <p class="card-text flex-grow-1"><?= htmlspecialchars($poster->getHeadline(), ENT_QUOTES)?></p>
                                        <div class="text-end mt-auto">
                                            <?php if ($adminLogged): ?>
                                                <a href="/poster_add_edit.php?posterId=<?= htmlspecialchars($poster->getId(), ENT_QUOTES)?>" class="btn btn-transparent text-primary">Editar</a>
                                            <?php endif; ?>
                                            <a href="/poster_view.php?posterId=<?= htmlspecialchars($poster->getId(), ENT_QUOTES)?>" class="btn btn-transparent text-primary">Abrir</a>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <?php endforeach; ?>

                        <?php endif; ?>


                    </div>

                </div>
                
                <?php

                    if ($postersCount > 12):
                    
                        $pagesCount = ceil($postersCount / 12);
                    
                ?>

                <div class="w-100">

                    <div class="row g-0 align-items-center justify-content-center">
                        <div class="col-11 col-md-6 col-lg-6 p-3">

                            <nav class="w-100">
                                <ul class="pagination d-flex flex-wrap align-items-center justify-content-center">
                                    <?php for ($i = 1; $i <= $pagesCount; $i++): ?>
                                        <li class="page-item <?= ($i === $currentPage ? 'active' : '') ?>">
                                            <a class="page-link" href="?currentPage=<?= $i ?>" aria-current="page">
                                                <?= $i ?>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                            
                        </div>

                    </div>
                    
                </div>

                <?php endif; ?>

                <div class="w-100 text-center p-3 bg-success text-white rounded-4 mt-3 py-5">

                    <h1>Educação Ambiental e Práticas Sustentáveis para a Transformação Social</h1>

                    <p class="px-3 mt-3" style="text-align: justify;">
                        A degradação ambiental se intensificou nos últimos anos em razão do modelo de desenvolvimento pautado no consumo desenfreado e na exploração de recursos naturais. As consequências desse comportamento são visíveis: mudanças climáticas, perda da biodiversidade, escassez de recursos e aumento das desigualdades sociais. Diante dessa problemática, se faz necessário promover uma nova consciência coletiva, com valores éticos, solidários e sustentáveis. A educação ambiental tem como desempenho um papel essencial na formação de indivíduos analíticos e responsáveis, com a capacidade de compreender a relação entre a sociedade e natureza.
                    </p>

                    <p class="px-3" style="text-align: justify;">
                        A educação ambiental é definida pela Política Nacional de Educação Ambiental (Lei n° 9.795/1999) como o processo pelo qual indivíduos e coletividades constroem valores, conhecimentos, habilidades e atitudes voltadas para a conservação do meio ambiente e a melhoria da qualidade de vida. É destacado a importância de uma abordagem contínua, crítica e participativa, com a capacidade de gerar mudanças concretas na sociedade.
                    </p>

                    <p class="px-3" style="text-align: justify;">
                        Segundo Jacobi (2003), a educação ambiental deve ser entendida como uma prática social e política, que vai muito além do ensino sobre ecologia. Tendo como foco o fortalecimento da cidadania e da justiça social, por meio da sensibilização, comoção e da participação ativa da população em ações que visem o bem coletivo.
                    </p>

                    <p class="px-3" style="text-align: justify;">
                        As práticas sustentáveis têm, por sua vez, o resultado prático da conscientização ambiental. Incluem a separação de resíduos, o reaproveitamento de água, o uso de energias limpas, o incentivo à economia local e o consumo consciente. Essas ações, quando implementadas no cotidiano, contribuem para reduzir impactos ambientais e promover uma cultura de responsabilidade social.
                    </p>

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