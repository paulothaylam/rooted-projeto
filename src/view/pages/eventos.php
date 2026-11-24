<?php require __DIR__ . '/../layouts/head.php'; ?>

<header class="border-bottom bg-white">
    <nav class="navbar navbar-expand-lg container py-3" aria-label="Navegação principal">
        <a class="navbar-brand fw-bold text-success" href="/">
            <span class="brand-mark" aria-hidden="true">🌱</span> Rooted
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuEventos"
                aria-controls="menuEventos" aria-expanded="false" aria-label="Abrir menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuEventos">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="/?pagina=eventos">Vitrine</a></li>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="/?pagina=painel-participante">Meu painel</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/?pagina=login">Entrar</a></li>
                    <li class="nav-item"><a class="btn btn-success" href="/?pagina=cadastro">Criar conta</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>

<main>
    <section class="events-hero border-bottom">
        <div class="container py-5">
            <div class="row justify-content-between align-items-end g-4">
                <div class="col-lg-6">
                    <span class="text-success fw-semibold">Vitrine pública</span>
                    <h1 class="display-5 fw-bold mt-2 mb-3">Encontre seu próximo evento</h1>
                    <p class="lead text-secondary mb-0">
                        Conheça experiências gratuitas e pagas publicadas pelas organizações.
                    </p>
                </div>
                <div class="col-lg-5">
                    <form class="event-search card border-0 p-3" method="get" action="/">
                        <input type="hidden" name="pagina" value="eventos">
                        <div class="row g-2">
                            <div class="col-md-8">
                                <label class="form-label small text-secondary" for="busca">Nome ou organização</label>
                                <input class="form-control" id="busca" name="busca" type="search"
                                       placeholder="Buscar eventos" value="<?= htmlspecialchars($_GET['busca'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button class="btn btn-success w-100" type="submit">Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="container py-5">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <span class="text-secondary small">Eventos publicados</span>
                <h2 class="h3 mb-0">Próximas experiências</h2>
            </div>
            <div class="btn-group" role="group" aria-label="Filtros de evento">
                <?php $filtroAtual = $_GET['tipo'] ?? 'todos'; ?>
                <a class="btn btn-outline-success <?= $filtroAtual === 'todos' ? 'active' : '' ?>"
                   href="/?pagina=eventos<?= isset($_GET['busca']) && $_GET['busca'] !== '' ? '&busca=' . urlencode((string) $_GET['busca']) : '' ?>">Todos</a>
                <a class="btn btn-outline-success <?= $filtroAtual === 'pagos' ? 'active' : '' ?>"
                   href="/?pagina=eventos&tipo=pagos<?= isset($_GET['busca']) && $_GET['busca'] !== '' ? '&busca=' . urlencode((string) $_GET['busca']) : '' ?>">Pagos</a>
                <a class="btn btn-outline-success <?= $filtroAtual === 'gratuitos' ? 'active' : '' ?>"
                   href="/?pagina=eventos&tipo=gratuitos<?= isset($_GET['busca']) && $_GET['busca'] !== '' ? '&busca=' . urlencode((string) $_GET['busca']) : '' ?>">Gratuitos</a>
            </div>
        </div>

        <?php if (!$eventos): ?>
            <div class="event-empty text-center p-4">
                <div>
                    <div class="display-5 mb-3" aria-hidden="true">🌱</div>
                    <h3 class="h4">Nenhum evento publicado ainda</h3>
                    <p class="text-secondary mb-4">
                        Quando uma organização publicar um evento, ele aparecerá nesta vitrine.
                    </p>
                    <a class="btn btn-outline-success" href="/?pagina=login">Sou organizador? Entrar</a>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($eventos as $item): ?>
                    <?php
                    $data = new DateTime($item['data_inicio']);
                    $tipoLabel = $item['tipo'] === 'PAGO' ? 'Evento pago' : ($item['tipo'] === 'BENEFICENTE' ? 'Beneficente' : 'Evento gratuito');
                    ?>
                    <div class="col-md-6 col-xl-4">
                        <article class="card dashboard-card h-100 overflow-hidden">
                            <div class="event-preview mb-0 rounded-0" style="min-height: 180px; display:grid; place-items:center; background:linear-gradient(135deg,#173f2a,#2f8f5b); color:white; font-weight:800; letter-spacing:.12em;">
                                <?= htmlspecialchars(strtoupper(substr($item['nome'], 0, 18)), ENT_QUOTES, 'UTF-8') ?>
                            </div>
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between gap-2 mb-3">
                                    <span class="badge text-bg-success-subtle text-success"><?= htmlspecialchars($tipoLabel, ENT_QUOTES, 'UTF-8') ?></span>
                                    <small class="text-secondary"><?= $data->format('d/m/Y') ?></small>
                                </div>
                                <h3 class="h5"><?= htmlspecialchars($item['nome'], ENT_QUOTES, 'UTF-8') ?></h3>
                                <p class="text-secondary small">
                                    <?= htmlspecialchars($item['organizacao'], ENT_QUOTES, 'UTF-8') ?> ·
                                    <?= htmlspecialchars($item['local_nome'], ENT_QUOTES, 'UTF-8') ?>
                                </p>
                                <p class="text-secondary flex-grow-1">
                                    <?= htmlspecialchars(substr($item['descricao'], 0, 120), ENT_QUOTES, 'UTF-8') ?>...
                                </p>
                                <p class="small fw-semibold text-success mb-3">
                                    <?= (int) $item['vagas'] ?> ingressos disponíveis
                                </p>
                                <a class="btn btn-outline-success" href="/?pagina=evento&id=<?= (int) $item['id'] ?>">Ver detalhes</a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
