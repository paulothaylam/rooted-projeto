<?php require __DIR__ . '/../layouts/head.php'; ?>

<header class="border-bottom bg-white">
    <nav class="navbar navbar-expand-lg container py-3">
        <a class="navbar-brand fw-bold text-success" href="/">
            <span class="brand-mark" aria-hidden="true">🌱</span> Rooted
        </a>
        <a class="btn btn-outline-secondary ms-auto" href="/?pagina=painel-participante">Voltar ao painel</a>
    </nav>
</header>

<main class="container py-4 py-lg-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="mb-4">
                <span class="text-success fw-semibold">Minha conta</span>
                <h1 class="h2 mt-2 mb-2">Editar perfil</h1>
            </div>

            <?php if (!$perfil): ?>
                <div class="alert alert-danger">Perfil não encontrado.</div>
            <?php else: ?>
                <?php if ($mensagem): ?>
                    <div class="alert <?= $tipoMensagem === 'sucesso' ? 'alert-success' : 'alert-danger' ?>" role="alert">
                        <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="/?pagina=editar-perfil" class="card dashboard-card mb-4">
                    <div class="card-body p-4 p-lg-5">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="nome" class="form-label">Nome completo</label>
                                <input type="text" class="form-control" id="nome" name="nome"
                                       value="<?= htmlspecialchars($perfil['nome'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="col-md-7">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       value="<?= htmlspecialchars($perfil['email'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="col-md-5">
                                <label for="telefone" class="form-label">Telefone</label>
                                <input type="tel" class="form-control" id="telefone" name="telefone"
                                       value="<?= htmlspecialchars($perfil['telefone'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="col-12">
                                <label for="cpf" class="form-label">CPF</label>
                                <input type="text" class="form-control" id="cpf" name="cpf"
                                       value="<?= htmlspecialchars($perfil['cpf'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-5">
                            <a class="btn btn-outline-secondary" href="/?pagina=painel-participante">Cancelar</a>
                            <button class="btn btn-success" type="submit">Salvar alterações</button>
                        </div>
                    </div>
                </form>

                <div class="card border-danger-subtle">
                    <div class="card-body p-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h2 class="h5 mb-1 text-danger">Excluir minha conta</h2>
                            <p class="text-secondary mb-0">Esta ação exige que você não tenha inscrições no momento.</p>
                        </div>
                        <form method="post" action="/?pagina=excluir-conta"
                              onsubmit="return confirm('Deseja realmente excluir sua conta?');">
                            <button class="btn btn-outline-danger" type="submit">Excluir conta</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
