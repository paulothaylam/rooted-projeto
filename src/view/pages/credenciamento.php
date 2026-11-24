<?php require __DIR__ . '/../layouts/head.php'; ?>

<header class="border-bottom bg-white">
    <nav class="navbar navbar-expand-lg container py-3">
        <a class="navbar-brand fw-bold text-success" href="/">
            <span class="brand-mark" aria-hidden="true">🌱</span> Rooted
        </a>
        <div class="ms-auto d-flex gap-2">
            <a class="btn btn-outline-secondary" href="/?pagina=painel-organizador">Painel do organizador</a>
            <a class="btn btn-outline-success" href="/?pagina=painel-participante">Painel do participante</a>
        </div>
    </nav>
</header>

<main class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <span class="text-success fw-semibold">Equipe de credenciamento</span>
                <h1 class="h2 mt-2 mb-2">Validar entrada</h1>
                <p class="text-secondary mb-0">Busque por nome ou CPF do participante.</p>
            </div>

            <?php if ($resultado): ?>
                <?php
                $classe = $resultado['tipo'] === 'sucesso'
                    ? 'alert-success'
                    : ($resultado['tipo'] === 'aviso' ? 'alert-warning' : 'alert-danger');
                ?>
                <div class="alert <?= $classe ?>" role="alert">
                    <strong><?= htmlspecialchars($resultado['titulo'], ENT_QUOTES, 'UTF-8') ?></strong>
                    <div><?= htmlspecialchars($resultado['texto'], ENT_QUOTES, 'UTF-8') ?></div>
                </div>
            <?php endif; ?>

            <div class="card dashboard-card mb-4">
                <div class="card-body p-4 p-lg-5">
                    <form method="post" action="/?pagina=credenciamento">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label for="evento_id" class="form-label">Evento</label>
                                <select class="form-select" id="evento_id" name="evento_id" required>
                                    <option value="">Selecione</option>
                                    <?php foreach ($eventos as $evento): ?>
                                        <option value="<?= (int) $evento['id'] ?>">
                                            <?= htmlspecialchars($evento['nome'], ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label for="participante" class="form-label">Nome ou CPF</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="participante"
                                           name="participante" placeholder="Digite para buscar" required>
                                    <button class="btn btn-success" type="submit">Validar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <section class="card border-0 shadow-sm">
                <div class="card-body p-4 text-center">
                    <div class="display-5 mb-3" aria-hidden="true">📷</div>
                    <h2 class="h5">Leitura pelo QR Code</h2>
                    <p class="text-secondary mb-0">
                        Esta versão usa busca manual. A câmera será adicionada em uma etapa posterior.
                    </p>
                </div>
            </section>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
