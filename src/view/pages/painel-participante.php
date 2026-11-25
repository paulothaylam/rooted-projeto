<?php require __DIR__ . '/../layouts/head.php'; ?>

<div class="dashboard-shell">
    <nav class="area-topbar" aria-label="Alternar área">
        <div class="container-fluid">
            <div class="nav">
                <a class="nav-link active" aria-current="page" href="?pagina=painel-participante">Participante</a>
                <a class="nav-link" href="?pagina=painel-organizador">Organizador</a>
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

                <nav class="nav flex-column gap-1" aria-label="Menu do participante">
                    <a class="nav-link active" href="?pagina=painel-participante">Painel</a>
                    <a class="nav-link" href="?pagina=eventos">Explorar eventos</a>
                    <a class="nav-link" href="#meus-eventos">Meus eventos</a>
                    <a class="nav-link" href="#qr-pessoal">Meu QR pessoal</a>
                    <a class="nav-link" href="#perfil">Meu perfil</a>
                </nav>

                <div class="mt-4 pt-4 border-top border-white border-opacity-25">
                    <a class="nav-link" href="?pagina=editar-perfil">Editar perfil e conta</a>
                    <a class="nav-link text-white-50" href="/">Voltar à página inicial</a>
                    <a class="nav-link text-danger" href="/?pagina=logout">Sair da conta</a>
                </div>

                <div class="mt-5 pt-4 border-top border-white border-opacity-25">
                    <p class="text-white-50 small mb-1">Conectado como</p>
                    <strong class="text-white d-block text-truncate"><?= htmlspecialchars($usuarioNome, ENT_QUOTES, 'UTF-8') ?></strong>
                    <small class="text-white-50"><?= htmlspecialchars($usuarioEmail, ENT_QUOTES, 'UTF-8') ?></small>
                </div>
            </aside>

            <main class="col-lg-9 col-xl-10 dashboard-content p-4 p-lg-5">
                <?php if (isset($_SESSION['mensagem_inscricao'])): ?>
                    <div class="alert <?= ($_SESSION['tipo_mensagem_inscricao'] ?? 'erro') === 'sucesso' ? 'alert-success' : 'alert-danger' ?>" role="alert">
                        <?= htmlspecialchars($_SESSION['mensagem_inscricao'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <?php unset($_SESSION['mensagem_inscricao'], $_SESSION['tipo_mensagem_inscricao']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['mensagem_conta'])): ?>
                    <div class="alert <?= ($_SESSION['tipo_mensagem_conta'] ?? 'erro') === 'sucesso' ? 'alert-success' : 'alert-danger' ?>" role="alert">
                        <?= htmlspecialchars($_SESSION['mensagem_conta'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                    <?php unset($_SESSION['mensagem_conta'], $_SESSION['tipo_mensagem_conta']); ?>
                <?php endif; ?>

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                    <div>
                        <span class="text-success fw-semibold">Área do participante</span>
                        <h1 class="h2 mt-2 mb-0">Olá, <?= htmlspecialchars(explode(' ', $usuarioNome)[0], ENT_QUOTES, 'UTF-8') ?>!</h1>
                    </div>
                    <a class="btn btn-outline-success" href="/?pagina=eventos">Explorar eventos</a>
                </div>

                <div class="row g-4">
                    <section id="meus-eventos" class="col-xl-8">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div>
                                        <span class="text-secondary small">Suas inscrições</span>
                                        <h2 class="h4 mb-0">Meus eventos</h2>
                                    </div>
                                    <span class="badge text-bg-success-subtle text-success"><?= count($inscricoes) ?> <?= count($inscricoes) === 1 ? 'inscrição' : 'inscrições' ?></span>
                                </div>
                                <?php if (!$inscricoes): ?>
                                    <div class="text-center border rounded-4 py-5 px-3 bg-light">
                                        <div class="display-6 mb-3" aria-hidden="true">🌱</div>
                                        <h3 class="h5">Você ainda não participa de eventos</h3>
                                        <p class="text-secondary mb-4">Explore os eventos publicados e escolha uma inscrição.</p>
                                        <a class="btn btn-success" href="/?pagina=eventos">Encontrar eventos</a>
                                    </div>
                                <?php else: ?>
                                    <div class="d-grid gap-3">
                                        <?php foreach ($inscricoes as $inscricao): ?>
                                            <?php $dataEvento = new DateTime($inscricao['data_inicio']); ?>
                                            <article class="border rounded-4 p-3">
                                                <div class="d-flex flex-wrap justify-content-between gap-3">
                                                    <div>
                                                        <span class="badge text-bg-success-subtle text-success mb-2"><?= htmlspecialchars($inscricao['situacao'], ENT_QUOTES, 'UTF-8') ?></span>
                                                        <h3 class="h5 mb-1"><?= htmlspecialchars($inscricao['evento_nome'], ENT_QUOTES, 'UTF-8') ?></h3>
                                                        <p class="text-secondary small mb-0">
                                                            <?= $dataEvento->format('d/m/Y \à\s H:i') ?> ·
                                                            <?= htmlspecialchars($inscricao['local_nome'], ENT_QUOTES, 'UTF-8') ?>
                                                        </p>
                                                    </div>
                                                    <div class="text-end">
                                                        <span class="d-block small text-secondary"><?= htmlspecialchars($inscricao['ingresso_nome'], ENT_QUOTES, 'UTF-8') ?></span>
                                                        <a class="btn btn-outline-success btn-sm mt-2" href="/?pagina=evento&id=<?= (int) $inscricao['evento_id'] ?>">Ver evento</a>
                                                        <?php if ($inscricao['situacao'] === 'ATIVA'): ?>
                                                            <form method="post" action="/?pagina=cancelar-inscricao" class="d-inline"
                                                                  onsubmit="return confirm('Deseja cancelar sua inscrição neste evento?');">
                                                                <input type="hidden" name="inscricao_id" value="<?= (int) $inscricao['id'] ?>">
                                                                <button class="btn btn-outline-danger btn-sm mt-2" type="submit">Cancelar inscrição</button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </article>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>

                    <section id="qr-pessoal" class="col-xl-4">
                        <div class="card dashboard-card h-100">
                            <div class="card-body p-4">
                                <span class="text-secondary small">Credenciamento</span>
                                <h2 class="h4 mb-4">Meu QR pessoal</h2>

                                <div class="qr-card">
                                    <div class="qr-placeholder mb-3" aria-label="QR Code pessoal"></div>
                                    <span class="badge text-bg-light text-dark">QR aguardando geração</span>
                                </div>

                                <p class="text-secondary small mt-3 mb-0">
                                    Seu código será usado em todos os eventos, sem mostrar seu CPF.
                                </p>
                            </div>
                        </div>
                    </section>

                    <section id="perfil" class="col-12">
                        <div class="card dashboard-card">
                            <div class="card-body p-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                                    <div>
                                        <span class="text-secondary small">Minha conta</span>
                                        <h2 class="h4 mb-2">Perfil e dados pessoais</h2>
                                        <p class="text-secondary mb-0">
                                            <span class="status-dot me-2"></span>
                                            Conta participante ativa
                                        </p>
                                    </div>
                                    <a class="btn btn-outline-dark" href="/?pagina=editar-perfil">Editar perfil</a>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
