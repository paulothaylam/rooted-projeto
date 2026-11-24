<?php require __DIR__ . '/../layouts/head.php'; ?>

<header class="border-bottom bg-white">
    <nav class="navbar navbar-expand-lg container py-3">
        <a class="navbar-brand fw-bold text-success" href="/">
            <span class="brand-mark" aria-hidden="true">🌱</span> Rooted
        </a>
        <a class="btn btn-outline-secondary ms-auto" href="/?pagina=painel-organizador">Voltar ao painel</a>
    </nav>
</header>

<main class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <?php if (!$evento): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        <h1 class="h3">Evento não encontrado</h1>
                        <a class="btn btn-success" href="/?pagina=painel-organizador">Voltar</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <span class="text-success fw-semibold">Gerenciamento do evento</span>
                        <h1 class="h2 mt-2 mb-2"><?= htmlspecialchars($evento['nome'], ENT_QUOTES, 'UTF-8') ?></h1>
                        <span class="badge text-bg-secondary"><?= htmlspecialchars($evento['situacao'], ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                </div>

                <?php if ($mensagem): ?>
                    <div class="alert <?= $tipoMensagem === 'sucesso' ? 'alert-success' : 'alert-danger' ?>" role="alert">
                        <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="/?pagina=gerenciar-evento&id=<?= (int) $evento['id'] ?>" class="card dashboard-card mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="row g-4">
                            <div class="col-12">
                                <label for="nome" class="form-label">Nome do evento</label>
                                <input type="text" class="form-control" id="nome" name="nome"
                                       value="<?= htmlspecialchars($evento['nome'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="col-12">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="5" required><?= htmlspecialchars($evento['descricao'], ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="tipo" class="form-label">Tipo do evento</label>
                                <select class="form-select" id="tipo" name="tipo">
                                    <option value="GRATUITO" <?= $evento['tipo'] === 'GRATUITO' ? 'selected' : '' ?>>Gratuito</option>
                                    <option value="PAGO" <?= $evento['tipo'] === 'PAGO' ? 'selected' : '' ?>>Pago</option>
                                    <option value="BENEFICENTE" <?= $evento['tipo'] === 'BENEFICENTE' ? 'selected' : '' ?>>Beneficente</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="data_inicio" class="form-label">Data e horário</label>
                                <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio"
                                       value="<?= htmlspecialchars((new DateTime($evento['data_inicio']))->format('Y-m-d\TH:i'), ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="col-md-8">
                                <label for="local_nome" class="form-label">Local ou link</label>
                                <input type="text" class="form-control" id="local_nome" name="local_nome"
                                       value="<?= htmlspecialchars($evento['local_nome'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label for="vagas" class="form-label">Quantidade de ingressos</label>
                                <input type="number" class="form-control" id="vagas" name="vagas" min="1"
                                       value="<?= (int) $vagas ?>" required>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap justify-content-end gap-2 mt-5">
                            <a class="btn btn-outline-secondary" href="/?pagina=painel-organizador">Cancelar</a>
                            <button class="btn btn-success" type="submit">Salvar alterações</button>
                        </div>
                    </div>
                </form>

                <div class="card border-danger-subtle mb-4">
                    <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h2 class="h5 mb-1 text-danger">Excluir evento</h2>
                            <p class="text-secondary mb-0">A exclusão só é permitida sem inscrições vinculadas.</p>
                        </div>
                        <form method="post" action="/?pagina=excluir-evento"
                              onsubmit="return confirm('Deseja realmente excluir este evento?');">
                            <input type="hidden" name="evento_id" value="<?= (int) $evento['id'] ?>">
                            <button class="btn btn-outline-danger" type="submit">Excluir evento</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
