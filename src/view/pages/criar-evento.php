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
        <div class="col-lg-9">
            <div class="mb-4">
                <span class="text-success fw-semibold">Organizador</span>
                <h1 class="h2 mt-2 mb-2">Criar evento</h1>
                <p class="text-secondary mb-0">Preencha as informações e salve o evento como rascunho.</p>
            </div>

            <?php if ($mensagem): ?>
                <div class="alert <?= $tipoMensagem === 'sucesso' ? 'alert-success' : 'alert-danger' ?>" role="alert">
                    <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="post" action="/?pagina=criar-evento" class="card dashboard-card">
                <div class="card-body p-4 p-lg-5">
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="nome" class="form-label">Nome do evento</label>
                            <input type="text" class="form-control" id="nome" name="nome" maxlength="180" required>
                        </div>
                        <div class="col-12">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="5" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="tipo" class="form-label">Tipo do evento</label>
                            <select class="form-select" id="tipo" name="tipo">
                                <option value="GRATUITO">Gratuito</option>
                                <option value="PAGO">Pago</option>
                                <option value="BENEFICENTE">Beneficente</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="data_inicio" class="form-label">Data e horário</label>
                            <input type="datetime-local" class="form-control" id="data_inicio" name="data_inicio" required>
                        </div>
                        <div class="col-md-8">
                            <label for="local_nome" class="form-label">Local ou link</label>
                            <input type="text" class="form-control" id="local_nome" name="local_nome" required>
                        </div>
                        <div class="col-md-4">
                            <label for="vagas" class="form-label">Quantidade de ingressos</label>
                            <input type="number" class="form-control" id="vagas" name="vagas" min="1" value="100" required>
                        </div>
                        <div class="col-12">
                            <label for="politica_reembolso" class="form-label">Política de reembolso</label>
                            <select class="form-select" id="politica_reembolso" name="politica_reembolso">
                                <option value="TRANSFERENCIA">Somente transferência</option>
                                <option value="INTEGRAL_ATE_DATA">Integral até uma data</option>
                                <option value="PARCIAL_ATE_DATA">Parcial até uma data</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap justify-content-end gap-2 mt-5">
                        <a class="btn btn-outline-secondary" href="/?pagina=painel-organizador">Cancelar</a>
                        <button class="btn btn-success" type="submit">Salvar como rascunho</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
