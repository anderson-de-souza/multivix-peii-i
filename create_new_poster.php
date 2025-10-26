<!DOCTYPE html>
<html lang="pt-br">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Espirito Eco</title>
    
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

                    <span class="navbar-brand mb-0 h1">Espírito Eco</span>
                    
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
                                <a class="nav-link active" aria-current="page" href="/index.html">Home</a>
                            </li>

                        </ul>

                    </div>
                </div>
            </nav>
        </header>
        
        <section class="col-12 row g-0 align-items-center justify-content-center">

            <div class="col-12 col-sm-10 col-md-6 d-flex flex-column">
                
                <div class="w-100 text-center p-3 pt-5">
                    <h1>Adicionar Novo Cartaz</h1>
                </div>

                <div class="w-100 text-center p-3 pt-5">

                    <form action="action_create_new_poster.php" method="post" enctype="multipart/form-data">
                    
                        <div class="mb-3 text-start fw-bold">
                            <label for="posterTitle" class="form-label">Título do Cartaz</label>
                            <input type="text" class="form-control" id="posterTitle" name="posterTitle" placeholder="Título" value="A Vida Mais Verde">
                        </div>
                        
                        <div class="mb-3 text-start fw-bold">
                            <label for="posterHeadline" class="form-label">Manchete</label>
                            <textarea class="form-control" id="posterHeadline" name="posterHeadline" rows="3">Descubra como cultivar cebolinha <strong>verde</strong> em um pote de margarina.</textarea>
                        </div>
                        
                        <div class="mb-3 text-start fw-bold">
                            <label for="posterDescription" class="form-label">Conteúdo/Descrição</label>
                            <textarea class="form-control" id="posterDescription" name="posterDescription" rows="12">
    Aqui vai um passo a passo simples em 4 passos:
    <ul>
        <li>Preparar o pote – Fure o fundo do pote de margarina para drenagem e coloque um pouco de terra fértil.</li>
        <li>Plantar – Plante as cebolinhas, enterrando apenas a raiz (ou base branca) na terra.</li>
        <li>Regar – Mantenha o solo úmido, mas sem encharcar, regando diariamente ou quando a terra estiver seca.</li>
        <li>Cuidar – Coloque em local com luz indireta ou sol algumas horas, e corte as folhas quando estiverem grandes para estimular novo crescimento.</li>
    </ul>
                            </textarea>
                        </div>
                        
                        <div class="mb-3 text-start fw-bold">
                            <label for="posterCoverImg" class="form-label">Capa</label>
                            <input class="form-control" type="file" id="posterCoverImg" name="posterCoverImg">
                        </div>
                        
                        <div class="d-flex flex-row-reverse">
                            <button type="submit" class="btn btn-primary">Criar</button>
                        </div>
                        
                    </form>
                </div>
                
            </div>
            
        </section>

        <footer class="row g-0 justify-content-center align-items-center">
            <div class="col-12 text-center p-3">
                © 2025
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