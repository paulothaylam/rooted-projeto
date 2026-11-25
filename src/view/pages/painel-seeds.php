<?php require __DIR__ . '/../layouts/head.php'; ?>

<div class="dashboard-shell">
    <nav class="area-topbar" aria-label="Alternar área">
        <div class="container-fluid">
            <div class="nav">
                <a class="nav-link" href="?pagina=painel-participante">Participante</a>
                <a class="nav-link" href="?pagina=painel-organizador">Organizador</a>
                <a class="nav-link active" aria-current="page" href="?pagina=painel-seeds">Seeds</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <aside class="col-lg-3 col-xl-2 dashboard-sidebar p-4">
                <a class="auth-brand fs-4 text-white d-inline-flex align-items-center mb-5" href="/">
                    <span class="brand-mark" aria-hidden="true">🌱</span>
                    <span>Rooted</span>
                </a>

                <nav class="nav flex-column gap-1" aria-label="Menu da Seeds">
                    <a class="nav-link active" href="?pagina=painel-seeds">Visão geral</a>
                    <a class="nav-link" href="#organizacoes">Organizações</a>
                    <a class="nav-link" href="#beneficentes">Eventos beneficentes</a>
                    <a class="nav-link" href="#vendas">Vendas e taxas</a>
                    <a class="nav-link" href="#reembolsos">Reembolsos</a>
                </nav>

                <div class="mt-4 pt-4 border-top border-white border-opacity-25">
                    <a class="nav-link text-white-50" href="/">Página inicial</a>
                    <a class="nav-link text-danger" href="/?pagina=logout">Sair da conta</a>
                </div>

                <div class="mt-5 pt-4 border-top border-white border-opacity-25">
                    <p class="text-white-50 small mb-1">Administrador da Seeds</p>
                    <strong class="text-white d-block"><?= htmlspecialchars($usuarioNome, ENT_QUOTES, 'UTF-8') ?></strong>
                    <small class="text-white-50"><?= htmlspecialchars($usuarioEmail, ENT_QUOTES, 'UTF-8') ?></small>
                </div>
            </aside>

            <main class="col-lg-9 col-xl-10 dashboard-content p-4 p-lg-5">
                <div class="mb-4">
                    <span class="text-success fw-semibold">Administração</span>
                    <h1 class="h2 mt-2 mb-0">Painel da Seeds</h1>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <article class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <span class="text-secondary small">Organizações</span>
                                <strong class="d-block display-6 mt-2"><?= count($organizacoes) ?></strong>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-4">
                        <article class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <span class="text-secondary small">Eventos beneficentes</span>
                                <strong class="d-block display-6 mt-2"><?= count($eventosBeneficentes) ?></strong>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-4">
                        <article class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <span class="text-secondary small">Taxas da Seeds</span>
                                <strong class="d-block display-6 mt-2">R$ 0,00</strong>
                            </div>
                        </article>
                    </div>
                </div>

                <section id="organizacoes" class="card dashboard-card mb-4">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-4">Organizações</h2>
                        <?php if (!$organizacoes): ?>
                            <p class="text-secondary mb-0">Nenhuma organização cadastrada.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Nome</th>
                                            <th>Situação</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($organizacoes as $organizacao): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($organizacao['nome'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                                <td>
                                                    <span class="badge <?= $organizacao['situacao'] === 'BLOQUEADA' ? 'text-bg-danger' : 'text-bg-success' ?>">
                                                        <?= htmlspecialchars($organizacao['situacao'], ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <button class="btn btn-outline-success btn-sm" type="button" disabled>Aprovar</button>
                                                    <button class="btn btn-outline-danger btn-sm" type="button" disabled>Bloquear</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <section id="beneficentes" class="card dashboard-card mb-4">
                    <div class="card-body p-4">
                        <h2 class="h4 mb-4">Eventos beneficentes</h2>
                        <?php if (!$eventosBeneficentes): ?>
                            <p class="text-secondary mb-0">Nenhum evento beneficente aguardando aprovação.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Evento</th>
                                            <th>Organização</th>
                                            <th>Situação</th>
                                            <th class="text-end">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($eventosBeneficentes as $evento): ?>
                                            <tr>
                                                <td><strong><?= htmlspecialchars($evento['nome'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                                                <td><?= htmlspecialchars($evento['organizacao'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><span class="badge text-bg-warning text-dark"><?= htmlspecialchars($evento['situacao'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                                <td class="text-end">
                                                    <button class="btn btn-outline-success btn-sm" type="button" disabled>Aprovar</button>
                                                    <button class="btn btn-outline-danger btn-sm" type="button" disabled>Recusar</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <div class="row g-4">
                    <section id="vendas" class="col-md-6">
                        <article class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <h2 class="h5">Vendas e taxas</h2>
                                <p class="text-secondary mb-0">Resumo financeiro aguardando integração de pagamentos.</p>
                            </div>
                        </article>
                    </section>
                    <section id="reembolsos" class="col-md-6">
                        <article class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <h2 class="h5">Reembolsos com falha</h2>
                                <p class="text-secondary mb-0">Nenhum reembolso pendente nesta versão.</p>
                            </div>
                        </article>
                    </section>
                </div>
            </main>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
