<?php require __DIR__ . '/../layouts/head.php'; ?>

<header class="border-bottom bg-white">
    <nav class="navbar navbar-expand-lg container py-3" aria-label="Navegação da reserva">
        <a class="navbar-brand fw-bold text-success" href="/">
            <span class="brand-mark" aria-hidden="true">🌱</span> Rooted
        </a>
        <?php if ($evento): ?>
            <a class="btn btn-outline-secondary ms-auto" href="/?pagina=evento&id=<?= (int) $evento['id'] ?>">Voltar ao evento</a>
        <?php endif; ?>
    </nav>
</header>

<main class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <?php if ($mensagem): ?>
                <div class="alert <?= $tipoMensagem === 'sucesso' ? 'alert-success' : 'alert-danger' ?>" role="alert">
                    <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if (!$evento): ?>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        <h1 class="h3">Evento não encontrado</h1>
                        <p class="text-secondary">Selecione um evento publicado na vitrine.</p>
                        <a class="btn btn-success" href="/?pagina=eventos">Ver eventos</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="mb-4">
                    <span class="text-success fw-semibold">Finalizar inscrição</span>
                    <h1 class="h2 mt-2"><?= htmlspecialchars($evento['nome'], ENT_QUOTES, 'UTF-8') ?></h1>
                    <p class="text-secondary mb-0"><?= htmlspecialchars($evento['organizacao'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 p-lg-5">
                        <div class="alert alert-light border mb-4">
                            <strong>Regra da reserva:</strong>
                            <?php if ($evento['tipo'] === 'PAGO'): ?>
                                pagamentos reservam a vaga por 15 minutos.
                            <?php else: ?>
                                eventos gratuitos criam a inscrição diretamente.
                            <?php endif; ?>
                        </div>

                        <form method="post" action="/?pagina=reservar&id=<?= (int) $evento['id'] ?>">
                            <h2 class="h5 mb-3">Escolha o ingresso</h2>

                            <?php foreach ($tipos as $tipo): ?>
                                <label class="ticket-option d-flex justify-content-between align-items-center p-3 mb-3">
                                    <span>
                                        <strong><?= htmlspecialchars($tipo['nome'], ENT_QUOTES, 'UTF-8') ?></strong>
                                        <small class="d-block text-secondary"><?= (int) $tipo['vagas'] ?> vagas disponíveis</small>
                                    </span>
                                    <span class="d-flex align-items-center gap-3">
                                        <strong><?= $tipo['preco'] == 0 ? 'Grátis' : 'R$ ' . number_format((float) $tipo['preco'], 2, ',', '.') ?></strong>
                                        <input type="radio" name="tipo_ingresso_id" value="<?= (int) $tipo['id'] ?>" required>
                                    </span>
                                </label>
                            <?php endforeach; ?>

                            <?php if ($evento['tipo'] === 'PAGO'): ?>
                                <button class="btn btn-success w-100 py-2" type="submit">
                                    Reservar vaga por 15 minutos
                                </button>
                                <p class="text-secondary small text-center mt-3 mb-0">
                                    A integração com o Mercado Pago será feita em uma etapa posterior.
                                </p>
                            <?php else: ?>
                                <button class="btn btn-success w-100 py-2" type="submit">
                                    Confirmar inscrição gratuita
                                </button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
