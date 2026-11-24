<?php require __DIR__ . '/../layouts/head.php'; ?>

<header class="border-bottom bg-white">
    <nav class="navbar navbar-expand-lg container py-3" aria-label="Navegação principal">
        <a class="navbar-brand fw-bold text-success" href="/">
            <span class="brand-mark" aria-hidden="true">🌱</span> Rooted
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
                aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link" href="?pagina=eventos">Eventos</a></li>
                <li class="nav-item"><a class="nav-link" href="#como-funciona">Como funciona</a></li>
                <li class="nav-item"><a class="nav-link" href="#para-quem">Para quem</a></li>
                <li class="nav-item"><a class="nav-link" href="#fases">Fases</a></li>
                <?php if (isset($_SESSION['usuario_id'])): ?>
                    <li class="nav-item ms-lg-2"><a class="btn btn-success px-3" href="?pagina=painel-participante">Meu painel</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="?pagina=login">Entrar</a></li>
                    <li class="nav-item ms-lg-2"><a class="btn btn-success px-3" href="?pagina=cadastro">Criar conta</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</header>

<main>
    <section class="hero-section">
        <div class="container py-5">
            <div class="row align-items-center g-5 py-lg-4">
                <div class="col-lg-7">
                    <span class="badge text-bg-success-subtle text-success border border-success-subtle mb-3 px-3 py-2">
                        Plataforma de eventos
                    </span>
                    <h1 class="display-4 fw-bold mb-4">Conecte pessoas, experiências e oportunidades.</h1>
                    <p class="lead text-secondary mb-4">
                        A Rooted ajuda organizações a criar eventos, vender ingressos e realizar o credenciamento
                        pelo QR Code pessoal de cada participante.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a class="btn btn-success btn-lg px-4" href="#eventos">Encontrar eventos</a>
                        <a class="btn btn-outline-dark btn-lg px-4" href="#para-quem">Conhecer a plataforma</a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="card border-0 shadow-lg hero-card">
                        <div class="card-body p-4">
                            <small class="text-uppercase text-success fw-semibold">Próximo evento</small>
                            <h2 class="h5 mt-2 mb-4">Experiências do Oxygeni Hub</h2>
                            <div class="event-preview mb-4"><span>ROOTED</span></div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-secondary">Inscrição e credenciamento</span>
                                <span class="badge text-bg-success">Ativo</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="como-funciona" class="container py-5">
        <div class="text-center mx-auto section-heading mb-5">
            <span class="text-success fw-semibold">Como funciona</span>
            <h2 class="mt-2">Uma jornada simples do anúncio ao check-in</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <article class="feature-card card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <span class="step-number">01</span>
                        <h3 class="h5 mt-4">Descubra eventos</h3>
                        <p class="text-secondary mb-0">Conheça eventos publicados por organizações do ecossistema.</p>
                    </div>
                </article>
            </div>
            <div class="col-md-4">
                <article class="feature-card card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <span class="step-number">02</span>
                        <h3 class="h5 mt-4">Compre ou se inscreva</h3>
                        <p class="text-secondary mb-0">Pagamentos são processados pelo Mercado Pago.</p>
                    </div>
                </article>
            </div>
            <div class="col-md-4">
                <article class="feature-card card h-100 border-0 shadow-sm">
                    <div class="card-body p-4">
                        <span class="step-number">03</span>
                        <h3 class="h5 mt-4">Entre pelo QR Code</h3>
                        <p class="text-secondary mb-0">Use o QR pessoal para agilizar o credenciamento na entrada.</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section id="para-quem" class="audience-section py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6 col-lg-3"><article class="audience-card h-100"><h3 class="h5">Participante</h3><p>Descubra eventos, compre ingressos e gerencie suas inscrições.</p></article></div>
                <div class="col-md-6 col-lg-3"><article class="audience-card h-100"><h3 class="h5">Organizador</h3><p>Publique eventos e acompanhe inscritos, vendas e check-ins.</p></article></div>
                <div class="col-md-6 col-lg-3"><article class="audience-card h-100"><h3 class="h5">Credenciamento</h3><p>Valide entradas pelo QR Code em celulares autorizados.</p></article></div>
                <div class="col-md-6 col-lg-3"><article class="audience-card h-100"><h3 class="h5">Seeds</h3><p>Aprove organizações e eventos, e acompanhe vendas e taxas.</p></article></div>
            </div>
        </div>
    </section>

    <section id="eventos" class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-8">
                <span class="text-success fw-semibold">Vitrine de eventos</span>
                <h2 class="mt-2 mb-3">A área de eventos será construída nesta fase do projeto.</h2>
                <p class="text-secondary mb-0">Esta página acadêmica apresenta o propósito e os públicos da plataforma.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a class="btn btn-dark btn-lg" href="/docs/diagramas/contexto-uml-rooted.md">Ver contexto do projeto</a>
            </div>
        </div>
    </section>

    <section id="fases" class="container pb-5">
        <div class="card bg-dark text-white border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <span class="text-success-emphasis">Escopo atual</span>
                        <h2 class="h3 mt-2">Fase 1: venda de ingressos e check-in</h2>
                        <p class="text-white-50 mb-0">Login, organizações, eventos, compra, inscrições, credenciamento e painel administrativo.</p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                        <span class="badge rounded-pill text-bg-success px-3 py-2">RF01–RF47</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
