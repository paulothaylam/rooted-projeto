<?php require __DIR__ . '/../layouts/head.php'; ?>

<header class="border-bottom bg-white">
    <nav class="navbar navbar-expand-lg container py-3" aria-label="Navegação principal">
        <a class="navbar-brand fw-bold text-success" href="/">
            <span class="brand-mark" aria-hidden="true">🌱</span> Rooted
        </a>
        <div class="ms-auto d-flex align-items-center gap-2">
            <a class="btn btn-outline-secondary" href="/?pagina=eventos">Todos os eventos</a>
            <?php if (!isset($_SESSION['usuario_id'])): ?>
                <a class="btn btn-success" href="/?pagina=login">Entrar</a>
            <?php else: ?>
                <a class="btn btn-success" href="/?pagina=painel-participante">Meu painel</a>
            <?php endif; ?>
        </div>
    </nav>
</header>

<main class="container py-4 py-lg-5">
    <nav aria-label="Caminho da página" class="mb-4">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="/?pagina=eventos" class="text-decoration-none">Eventos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Detalhes do evento</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="event-cover mb-4">
                <span class="event-cover-logo"><?= $evento ? htmlspecialchars(strtoupper(substr($evento['nome'], 0, 18)), ENT_QUOTES, 'UTF-8') : 'ROOTED' ?></span>
            </div>

            <section class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <span class="badge text-bg-success"><?= $evento ? htmlspecialchars($evento['tipo'] === 'PAGO' ? 'Evento pago' : ($evento['tipo'] === 'BENEFICENTE' ? 'Beneficente' : 'Evento gratuito'), ENT_QUOTES, 'UTF-8') : 'Evento gratuito' ?></span>
                        <span class="badge text-bg-light text-dark border">Local presencial</span>
                    </div>
                    <h1 class="h2 mb-3"><?= $evento ? htmlspecialchars($evento['nome'], ENT_QUOTES, 'UTF-8') : 'Evento não encontrado' ?></h1>
                    <p class="lead text-secondary">
                        <?= $evento ? htmlspecialchars($evento['descricao'], ENT_QUOTES, 'UTF-8') : 'Este evento não está disponível.' ?>
                    </p>
                    <hr>
                    <h2 class="h5 mt-4">Sobre o evento</h2>
                    <p class="text-secondary mb-0">
                        Organizadora: <?= $evento ? htmlspecialchars($evento['organizacao'], ENT_QUOTES, 'UTF-8') : 'Não informada' ?>
                    </p>
                </div>
            </section>
        </div>

        <aside class="col-lg-4">
            <div class="card border-0 shadow-sm position-sticky" style="top: 1.5rem;">
                <div class="card-body p-4">
                    <span class="text-success fw-semibold">Informações</span>

                    <dl class="mt-3 mb-4">
                        <div class="d-flex gap-3 mb-3">
                            <span aria-hidden="true">📅</span>
                            <div><dt class="small text-secondary">Data</dt><dd class="mb-0"><?= $evento ? htmlspecialchars((new DateTime($evento['data_inicio']))->format('d/m/Y \à\s H:i'), ENT_QUOTES, 'UTF-8') : 'A definir' ?></dd></div>
                        </div>
                        <div class="d-flex gap-3 mb-3">
                            <span aria-hidden="true">📍</span>
                            <div><dt class="small text-secondary">Local</dt><dd class="mb-0"><?= $evento ? htmlspecialchars($evento['local_nome'], ENT_QUOTES, 'UTF-8') : 'A definir' ?></dd></div>
                        </div>
                        <div class="d-flex gap-3">
                            <span aria-hidden="true">🎟️</span>
                            <div><dt class="small text-secondary">Ingressos</dt><dd class="mb-0">A definir</dd></div>
                        </div>
                    </dl>

                    <h2 class="h5">Tipo de ingresso</h2>
                    <label class="ticket-option selected d-flex justify-content-between align-items-center p-3 mb-3">
                        <span>
                            <strong>Entrada gratuita</strong>
                            <small class="d-block text-secondary">Sem cobrança</small>
                        </span>
                        <input type="radio" name="ingresso" checked disabled>
                    </label>

                    <?php if (!isset($_SESSION['usuario_id'])): ?>
                        <a class="btn btn-success w-100" href="/?pagina=login">Entrar para se inscrever</a>
                    <?php else: ?>
                        <?php if ($evento): ?>
                            <a class="btn btn-success w-100" href="/?pagina=reservar&id=<?= (int) $evento['id'] ?>">
                                Continuar
                            </a>
                        <?php else: ?>
                            <button class="btn btn-success w-100" type="button" disabled>Indisponível</button>
                        <?php endif; ?>
                    <?php endif; ?>

                    <p class="text-secondary small text-center mt-3 mb-0">
                        Nenhum pagamento realizado nesta tela.
                    </p>
                </div>
            </div>
        </aside>
    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
