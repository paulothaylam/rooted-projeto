<?php require __DIR__ . '/../layouts/head.php'; ?>

<main class="auth-page d-flex align-items-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-5 col-xl-4">
                <div class="text-center mb-4">
                    <a class="auth-brand fs-4" href="/">
                        <span class="brand-mark" aria-hidden="true">🌱</span> Rooted
                    </a>
                </div>

                <div class="card auth-card border-0 shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h3 mb-2">Bem-vindo de volta</h1>
                        <p class="text-secondary mb-4">Entre para acessar seus eventos e inscrições.</p>

                        <?php if (isset($mensagem)): ?>
                            <div class="alert <?= $tipoMensagem === 'sucesso' ? 'alert-success' : 'alert-danger' ?>" role="alert">
                                <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="/?pagina=login">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email"
                                       placeholder="nome@email.com" autocomplete="email" required>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <label for="senha" class="form-label">Senha</label>
                                    <a class="small text-decoration-none" href="#">Esqueceu a senha?</a>
                                </div>
                                <input type="password" class="form-control" id="senha" name="senha"
                                       autocomplete="current-password" required>
                            </div>
                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="lembrar" name="lembrar">
                                <label class="form-check-label" for="lembrar">Manter conectado</label>
                            </div>
                            <button type="submit" class="btn btn-success w-100 py-2">Entrar</button>
                        </form>

                        <div class="text-center my-4 text-secondary small">
                            <span class="px-2 bg-white">ou</span>
                        </div>

                        <button type="button" class="btn btn-outline-dark w-100" disabled>
                            Continuar com Google
                        </button>

                        <p class="text-center mt-4 mb-0">
                            Ainda não tem conta?
                            <a href="/?pagina=cadastro" class="text-decoration-none">Criar conta</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
