<?php require __DIR__ . '/../layouts/head.php'; ?>

<div class="dashboard-shell">
    <nav class="area-topbar" aria-label="Alternar área">
        <div class="container-fluid">
            <div class="nav">
                <a class="nav-link" href="?pagina=painel-participante">Participante</a>
                <a class="nav-link active" aria-current="page" href="?pagina=painel-organizador">Organizador</a>
                <?php if (($_SESSION['usuario_tipo'] ?? '') === 'admin'): ?>
                    <a class="nav-link" href="?pagina=painel-seeds">Seeds</a>
                <?php endif; ?>
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

                <nav class="nav flex-column gap-1" aria-label="Menu do organizador">
                    <a class="nav-link active" href="?pagina=painel-organizador">Visão geral</a>
                    <a class="nav-link" href="#eventos">Meus eventos</a>
                    <a class="nav-link" href="?pagina=inscritos">Inscritos</a>
                    <a class="nav-link" href="#vendas">Vendas e reembolsos</a>
                    <a class="nav-link" href="?pagina=credenciamento">Credenciamento</a>
                </nav>

                <div class="mt-4 pt-4 border-top border-white border-opacity-25">
                    <a class="nav-link" href="?pagina=editar-perfil">Editar perfil e conta</a>
                    <a class="nav-link text-danger" href="/?pagina=logout">Sair da conta</a>
                    <a class="nav-link text-white-50" href="/">Página inicial</a>
                </div>

                <div class="mt-5 pt-4 border-top border-white border-opacity-25">
                    <p class="text-white-50 small mb-1">Organizador</p>
                    <strong class="text-white d-block"><?= htmlspecialchars($usuarioNome, ENT_QUOTES, 'UTF-8') ?></strong>
                    <small class="text-white-50"><?= htmlspecialchars($usuarioEmail, ENT_QUOTES, 'UTF-8') ?></small>
                </div>
            </aside>

            <main class="col-lg-9 col-xl-10 dashboard-content p-4 p-lg-5">
                <?php if (isset($_SESSION['mensagem_publicacao'])): ?>
                    <?php
                    $tipoPublicacao = $_SESSION['tipo_mensagem_publicacao'] ?? 'erro';
                    $classePublicacao = $tipoPublicacao === 'sucesso'
                        ? 'alert-success'
                        : ($tipoPublicacao === 'aviso' ? 'alert-warning' : 'alert-danger');
                    ?>
                    <div class="alert <?= $classePublicacao ?>" role="alert">
                        <?= htmlspecialchars($_SESSION['mensagem_publicacao'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <?php unset($_SESSION['mensagem_publicacao'], $_SESSION['tipo_mensagem_publicacao']); ?>
                <?php endif; ?>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div>
                        <span class="text-success fw-semibold">Área do organizador</span>
                        <h1 class="h2 mt-2 mb-0">Visão geral</h1>
                    </div>
                    <a class="btn btn-success" href="?pagina=criar-evento">Criar evento</a>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <article class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <span class="text-secondary small">Eventos publicados</span>
                                <strong class="d-block display-6 mt-2"><?= count(array_filter($eventos, static fn (array $evento): bool => $evento['situacao'] === 'PUBLICADO')) ?></strong>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-4">
                        <article class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <span class="text-secondary small">Inscritos</span>
                                <strong class="d-block display-6 mt-2">0</strong>
                            </div>
                        </article>
                    </div>
                    <div class="col-md-4">
                        <article class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <span class="text-secondary small">Vendas</span>
                                <strong class="d-block display-6 mt-2">R$ 0,00</strong>
                            </div>
                        </article>
                    </div>
                </div>

                <section id="eventos" class="card dashboard-card mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2 class="h4 mb-0">Meus eventos</h2>
                            <span class="badge text-bg-light text-dark border"><?= count($eventos) ?> eventos</span>
                        </div>

                        <?php if (!$eventos): ?>
                            <div class="text-center border rounded-4 py-5 px-3 bg-light">
                                <h3 class="h5">Nenhum evento criado</h3>
                                <p class="text-secondary mb-0">Os eventos publicados aparecerão nesta área.</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead>
                                        <tr>
                                            <th>Evento</th>
                                            <th>Tipo</th>
                                            <th>Data</th>
                                            <th>Situação</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($eventos as $evento): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($evento['nome'], ENT_QUOTES, 'UTF-8') ?></strong>
                                                    <small class="d-block text-secondary"><?= htmlspecialchars($evento['organizacao'], ENT_QUOTES, 'UTF-8') ?></small>
                                                </td>
                                                <td><?= htmlspecialchars($evento['tipo'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= (new DateTime($evento['data_inicio']))->format('d/m/Y H:i') ?></td>
                                                <td>
                                                    <?php
                                                    $situacoes = [
                                                        'PUBLICADO' => 'text-bg-success',
                                                        'RASCUNHO' => 'text-bg-secondary',
                                                        'EM_APROVACAO' => 'text-bg-warning',
                                                        'CANCELADO' => 'text-bg-danger',
                                                    ];
                                                    $classeSituacao = $situacoes[$evento['situacao']] ?? 'text-bg-secondary';
                                                    ?>
                                                    <span class="badge <?= $classeSituacao ?>"><?= htmlspecialchars($evento['situacao'], ENT_QUOTES, 'UTF-8') ?></span>
                                                </td>
                                                <td class="text-end">
                                                    <a class="btn btn-outline-success btn-sm" href="/?pagina=evento&id=<?= (int) $evento['id'] ?>">Ver</a>
                                                    <a class="btn btn-outline-dark btn-sm" href="/?pagina=gerenciar-evento&id=<?= (int) $evento['id'] ?>">Gerenciar</a>
                                                    <?php if ($evento['situacao'] === 'RASCUNHO'): ?>
                                                        <form class="d-inline" method="post" action="/?pagina=publicar-evento">
                                                            <input type="hidden" name="evento_id" value="<?= (int) $evento['id'] ?>">
                                                            <button class="btn btn-success btn-sm" type="submit">
                                                                <?= $evento['tipo'] === 'PAGO' ? 'Solicitar publicação' : 'Publicar' ?>
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>
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
                    <section id="inscritos" class="col-md-6">
                        <article class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <h2 class="h5">Inscritos</h2>
                                <p class="text-secondary mb-0">A lista e exportação de participantes serão exibidas nesta área.</p>
                            </div>
                        </article>
                    </section>
                    <section id="vendas" class="col-md-6">
                        <article class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <h2 class="h5">Vendas e reembolsos</h2>
                                <p class="text-secondary mb-0">O painel financeiro será conectado em uma etapa posterior.</p>
                            </div>
                        </article>
                    </section>
                    <section id="equipe" class="col-12">
                        <article class="card dashboard-card">
                            <div class="card-body p-4">
                                <h2 class="h5">Equipe de credenciamento</h2>
                                <p class="text-secondary mb-0">Convites e permissões da equipe serão gerenciados nesta área.</p>
                            </div>
                        </article>
                    </section>
                </div>
            </main>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
