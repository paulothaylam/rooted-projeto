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
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <span class="text-success fw-semibold">Organizador</span>
            <h1 class="h2 mt-2 mb-0">Inscritos</h1>
        </div>
        <button class="btn btn-outline-success" type="button" disabled>Exportar planilha</button>
    </div>

    <form method="get" action="/" class="card dashboard-card mb-4">
        <input type="hidden" name="pagina" value="inscritos">
        <div class="card-body p-4">
            <label class="form-label" for="evento">Filtrar por evento</label>
            <select class="form-select" id="evento" name="evento" onchange="this.form.submit()">
                <option value="0">Todos os eventos</option>
                <?php foreach ($eventos as $evento): ?>
                    <option value="<?= (int) $evento['id'] ?>" <?= $eventoSelecionado === (int) $evento['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($evento['nome'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <section class="card dashboard-card">
        <div class="card-body p-0">
            <?php if (!$inscritos): ?>
                <div class="text-center py-5 px-3">
                    <div class="display-6 mb-3" aria-hidden="true">🌱</div>
                    <h2 class="h5">Nenhum inscrito encontrado</h2>
                    <p class="text-secondary mb-0">Altere o filtro ou aguarde novas inscrições.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Participante</th>
                                <th>Evento</th>
                                <th>Ingresso</th>
                                <th>CPF</th>
                                <th>Situação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($inscritos as $inscrito): ?>
                                <tr>
                                    <td class="ps-4">
                                        <strong><?= htmlspecialchars($inscrito['participante_nome'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        <small class="d-block text-secondary"><?= htmlspecialchars($inscrito['email'], ENT_QUOTES, 'UTF-8') ?></small>
                                    </td>
                                    <td><?= htmlspecialchars($inscrito['evento_nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars($inscrito['ingresso_nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><?= htmlspecialchars(substr((string) $inscrito['cpf'], 0, 7) . '***', ENT_QUOTES, 'UTF-8') ?></td>
                                    <td><span class="badge text-bg-success"><?= htmlspecialchars($inscrito['situacao'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
