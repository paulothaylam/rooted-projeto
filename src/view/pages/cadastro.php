<?php require __DIR__ . '/../layouts/head.php'; ?>

<main class="auth-page d-flex align-items-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-7 col-xl-6">
                <div class="text-center mb-4">
                    <a class="auth-brand fs-4" href="/">
                        <span class="brand-mark" aria-hidden="true">🌱</span> Rooted
                    </a>
                </div>

                <div class="card auth-card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h3 mb-2">Criar sua conta</h1>
                        <p class="text-secondary mb-4">Cadastre-se para encontrar eventos e participar.</p>

                        <?php if (isset($mensagem)): ?>
                            <div class="alert <?= $tipoMensagem === 'sucesso' ? 'alert-success' : 'alert-danger' ?>" role="alert">
                                <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/?pagina=cadastro">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="nome" class="form-label">Nome completo</label>
                                    <input type="text" class="form-control" id="nome" name="nome"
                                           autocomplete="name" required>
                                </div>
                                <div class="col-md-7">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           placeholder="nome@email.com" autocomplete="email" required>
                                </div>
                                <div class="col-md-5">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="tel" class="form-control" id="telefone" name="telefone"
                                           placeholder="(98) 99999-9999" autocomplete="tel" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" class="form-control" id="cpf" name="cpf"
                                           inputmode="numeric" maxlength="14" placeholder="000.000.000-00" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="senha" class="form-label">Senha</label>
                                    <input type="password" class="form-control" id="senha" name="senha"
                                           minlength="8" autocomplete="new-password" required>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="termos" name="termos" required>
                                        <label class="form-check-label" for="termos">
                                            Li e aceito os Termos de Uso e a Política de Privacidade.
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success w-100 py-2">Criar conta</button>
                                </div>
                            </div>
                        </form>

                        <p class="text-center mt-4 mb-0">
                            Já tem uma conta?
                            <a href="/?pagina=login" class="text-decoration-none">Entrar</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
