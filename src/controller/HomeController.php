<?php

declare(strict_types=1);

require_once __DIR__ . '/../model/Database.php';
require_once __DIR__ . '/../model/Perfil.php';
require_once __DIR__ . '/../model/Evento.php';
require_once __DIR__ . '/../model/Compra.php';

final class HomeController
{
    public function mostrar(string $pagina): void
    {
        if (isset($_SESSION['usuario_id'])) {
            $tipoPerfil = (string) ($_SESSION['usuario_tipo'] ?? '');

            if ($pagina === 'painel-seeds' && $tipoPerfil !== 'admin') {
                header('Location: /?pagina=painel-participante');
                exit;
            }
        }

        if ($pagina === 'login') {
            $this->login();
            return;
        }

        if ($pagina === 'cadastro') {
            $this->cadastro();
            return;
        }

        if ($pagina === 'painel-participante') {
            $this->painelParticipante();
            return;
        }

        if ($pagina === 'eventos') {
            $this->eventos();
            return;
        }

        if ($pagina === 'evento') {
            $this->evento();
            return;
        }

        if ($pagina === 'reservar') {
            $this->reservar();
            return;
        }

        if ($pagina === 'painel-organizador') {
            $this->painelOrganizador();
            return;
        }

        if ($pagina === 'publicar-evento') {
            $this->publicarEvento();
            return;
        }

        if ($pagina === 'gerenciar-evento') {
            $this->gerenciarEvento();
            return;
        }

        if ($pagina === 'excluir-evento') {
            $this->excluirEvento();
            return;
        }

        if ($pagina === 'criar-evento') {
            $this->criarEvento();
            return;
        }

        if ($pagina === 'inscritos') {
            $this->inscritos();
            return;
        }

        if ($pagina === 'credenciamento') {
            $this->credenciamento();
            return;
        }

        if ($pagina === 'painel-seeds') {
            $this->painelSeeds();
            return;
        }

        if ($pagina === 'editar-perfil') {
            $this->editarPerfil();
            return;
        }

        if ($pagina === 'excluir-conta') {
            $this->excluirConta();
            return;
        }

        if ($pagina === 'cancelar-inscricao') {
            $this->cancelarInscricao();
            return;
        }

        if ($pagina === 'logout') {
            $this->logout();
            return;
        }

        $this->index();
    }

    public function index(): void
    {
        $pageTitle = 'Rooted | Plataforma de eventos';
        require __DIR__ . '/../view/pages/home.php';
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        header('Location: /?pagina=login&logout=sucesso');
        exit;
    }

    public function login(): void
    {
        $pageTitle = 'Entrar | Rooted';
        $mensagem = null;
        $tipoMensagem = null;

        if (($_GET['cadastro'] ?? null) === 'sucesso') {
            $mensagem = 'Cadastro realizado com sucesso! Agora faça login.';
            $tipoMensagem = 'sucesso';
        }

        if (($_GET['logout'] ?? null) === 'sucesso') {
            $mensagem = 'Você saiu da conta com sucesso.';
            $tipoMensagem = 'sucesso';
        }

        if (($_GET['conta'] ?? null) === 'excluida') {
            $mensagem = 'Sua conta foi excluída.';
            $tipoMensagem = 'sucesso';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim((string) ($_POST['email'] ?? ''));
            $senha = (string) ($_POST['senha'] ?? '');

            try {
                $pdo = Database::conectar();
                $perfil = new Perfil($pdo);
                $usuario = $perfil->buscarPorEmail($email);

                if (!$usuario || !password_verify($senha, $usuario['senha_hash'])) {
                    $mensagem = 'E-mail ou senha incorretos.';
                    $tipoMensagem = 'erro';
                } else {
                    $_SESSION['usuario_id'] = (int) $usuario['id'];
                    $_SESSION['usuario_nome'] = $usuario['nome'];
                    $_SESSION['usuario_email'] = $usuario['email'];
                    $_SESSION['usuario_tipo'] = $usuario['tipo_perfil'];
                    $paginaInicial = match ($usuario['tipo_perfil']) {
                        'admin' => 'painel-seeds',
                        'organizador' => 'painel-organizador',
                        default => 'painel-participante',
                    };
                    header('Location: /?pagina=' . $paginaInicial);
                    exit;
                }
            } catch (PDOException $exception) {
                $mensagem = 'Erro ao conectar ao banco de dados.';
                $tipoMensagem = 'erro';
            }
        }

        require __DIR__ . '/../view/pages/login.php';
    }

    public function cadastro(): void
    {
        $pageTitle = 'Criar conta | Rooted';
        $mensagem = null;
        $tipoMensagem = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim((string) ($_POST['nome'] ?? ''));
            $email = strtolower(trim((string) ($_POST['email'] ?? '')));
            $cpf = trim((string) ($_POST['cpf'] ?? ''));
            $telefone = trim((string) ($_POST['telefone'] ?? ''));
            $senha = (string) ($_POST['senha'] ?? '');
            $tipoPerfil = (string) ($_POST['tipo_perfil'] ?? '');
            $termos = isset($_POST['termos']);

            if ($nome === '' || $email === '' || $cpf === '' || $telefone === '' || $senha === '') {
                $mensagem = 'Preencha todos os campos obrigatórios.';
                $tipoMensagem = 'erro';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mensagem = 'Informe um e-mail válido.';
                $tipoMensagem = 'erro';
            } elseif (strlen($senha) < 8) {
                $mensagem = 'A senha deve ter pelo menos 8 caracteres.';
                $tipoMensagem = 'erro';
            } elseif (!$termos) {
                $mensagem = 'Você precisa aceitar os Termos de Uso.';
                $tipoMensagem = 'erro';
            } elseif (!in_array($tipoPerfil, ['participante', 'organizador'], true)) {
                $mensagem = 'Selecione Participante ou Organizador.';
                $tipoMensagem = 'erro';
            } else {
                try {
                    $pdo = Database::conectar();
                    $perfil = new Perfil($pdo);
                    $perfil->garantirTabela();

                    if ($perfil->emailExiste($email)) {
                        $mensagem = 'Já existe uma conta com este e-mail.';
                        $tipoMensagem = 'erro';
                    } elseif ($perfil->cpfExiste($cpf)) {
                        $mensagem = 'Já existe uma conta com este CPF.';
                        $tipoMensagem = 'erro';
                    } else {
                        $perfil->criar([
                            'nome' => $nome,
                            'email' => $email,
                            'cpf' => $cpf,
                            'telefone' => $telefone,
                            'senha' => $senha,
                            'tipo_perfil' => $tipoPerfil,
                        ]);
                        header('Location: /?pagina=login&cadastro=sucesso');
                        exit;
                    }
                } catch (PDOException $exception) {
                    $mensagem = 'Erro ao salvar o cadastro no banco de dados.';
                    $tipoMensagem = 'erro';
                }
            }
        }

        require __DIR__ . '/../view/pages/cadastro.php';
    }

    public function painelParticipante(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /?pagina=login');
            exit;
        }

        $pageTitle = 'Meu painel | Rooted';
        $usuarioNome = (string) $_SESSION['usuario_nome'];
        $usuarioEmail = (string) $_SESSION['usuario_email'];
        $inscricoes = [];

        try {
            $pdo = Database::conectar();
            $compraModel = new Compra($pdo);
            $compraModel->garantirTabelas();

            $statement = $pdo->prepare("
                SELECT i.id, i.situacao, i.criado_em,
                       e.id AS evento_id, e.nome AS evento_nome, e.data_inicio,
                       e.local_nome, ti.nome AS ingresso_nome
                FROM inscricoes i
                INNER JOIN eventos e ON e.id = i.evento_id
                INNER JOIN tipos_ingresso ti ON ti.id = i.tipo_ingresso_id
                WHERE i.perfil_id = :perfil_id
                ORDER BY e.data_inicio ASC
            ");
            $statement->execute(['perfil_id' => (int) $_SESSION['usuario_id']]);
            $inscricoes = $statement->fetchAll();
        } catch (Throwable) {
            $inscricoes = [];
        }

        require __DIR__ . '/../view/pages/painel-participante.php';
    }

    public function eventos(): void
    {
        $pageTitle = 'Eventos | Rooted';
        $eventos = [];

        try {
            $pdo = Database::conectar();
            $model = new Evento($pdo);
            $model->garantirTabelas();
            $model->cadastrarExemplos();
            $busca = trim((string) ($_GET['busca'] ?? ''));
            $tipo = (string) ($_GET['tipo'] ?? 'todos');
            $tipo = in_array($tipo, ['todos', 'pagos', 'gratuitos'], true) ? $tipo : 'todos';

            $compraModel = new Compra($pdo);
            $compraModel->garantirTabelas();

            $todasTabelas = $model->listar();
            foreach ($todasTabelas as $evento) {
                $compraModel->cadastrarTiposPadrao((int) $evento['id'], $evento['tipo']);
            }

            $eventos = $model->listarFiltrado($busca, $tipo);
        } catch (Throwable) {
            $eventos = [];
        }

        require __DIR__ . '/../view/pages/eventos.php';
    }

    public function evento(): void
    {
        $pageTitle = 'Detalhes do evento | Rooted';
        $evento = null;

        try {
            $pdo = Database::conectar();
            $model = new Evento($pdo);
            $model->garantirTabelas();
            $model->cadastrarExemplos();
            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            $evento = $id > 0 ? $model->buscarPorId($id) : null;

            if (!$evento) {
                $todos = $model->listar();
                $evento = $todos[0] ?? null;
            }
        } catch (Throwable) {
            $evento = null;
        }

        require __DIR__ . '/../view/pages/detalhes-evento.php';
    }

    public function reservar(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /?pagina=login');
            exit;
        }

        $pageTitle = 'Reserva | Rooted';
        $mensagem = null;
        $tipoMensagem = null;
        $evento = null;
        $tipos = [];

        try {
            $pdo = Database::conectar();
            $eventoModel = new Evento($pdo);
            $eventoModel->garantirTabelas();
            $eventoModel->cadastrarExemplos();

            $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
            $evento = $id > 0 ? $eventoModel->buscarPorId($id) : null;

            $compraModel = new Compra($pdo);
            $compraModel->garantirTabelas();

            if ($evento) {
                $compraModel->cadastrarTiposPadrao($id, $evento['tipo']);
                $tipos = $compraModel->tiposPorEvento($id);

                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $tipoIngressoId = (int) ($_POST['tipo_ingresso_id'] ?? 0);
                    $resultado = $compraModel->reservar(
                        (int) $_SESSION['usuario_id'],
                        $id,
                        $tipoIngressoId,
                        $evento['tipo'] !== 'PAGO'
                    );

                    $tipoMensagem = str_contains($resultado, 'sucesso')
                        || str_contains($resultado, 'confirmada')
                        || str_contains($resultado, 'Reserva criada')
                        ? 'sucesso'
                        : 'erro';
                    $mensagem = $resultado;
                }
            }
        } catch (Throwable $exception) {
            $mensagem = 'Não foi possível acessar a reserva.';
            $tipoMensagem = 'erro';
        }

        require __DIR__ . '/../view/pages/reserva.php';
    }

    public function painelOrganizador(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /?pagina=login');
            exit;
        }

        $pageTitle = 'Painel do organizador | Rooted';
        $usuarioNome = (string) $_SESSION['usuario_nome'];
        $usuarioEmail = (string) $_SESSION['usuario_email'];
        $eventos = [];

        try {
            $pdo = Database::conectar();
            $eventoModel = new Evento($pdo);
            $eventoModel->garantirTabelas();
            $eventoModel->cadastrarExemplos();
            $eventos = $eventoModel->listarTodosParaOrganizador();
        } catch (Throwable) {
            $eventos = [];
        }

        require __DIR__ . '/../view/pages/painel-organizador.php';
    }

    public function publicarEvento(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /?pagina=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?pagina=painel-organizador');
            exit;
        }

        $id = (int) ($_POST['evento_id'] ?? 0);

        try {
            $pdo = Database::conectar();
            $eventoModel = new Evento($pdo);
            $eventoModel->garantirTabelas();
            $resultado = $id > 0
                ? $eventoModel->publicar($id)
                : ['tipo' => 'erro', 'mensagem' => 'Selecione um evento válido.'];

            $_SESSION['mensagem_publicacao'] = $resultado['mensagem'];
            $_SESSION['tipo_mensagem_publicacao'] = $resultado['tipo'];
        } catch (Throwable) {
            $_SESSION['mensagem_publicacao'] = 'Não foi possível publicar o evento.';
            $_SESSION['tipo_mensagem_publicacao'] = 'erro';
        }

        header('Location: /?pagina=painel-organizador');
        exit;
    }

    public function criarEvento(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /?pagina=login');
            exit;
        }

        $pageTitle = 'Criar evento | Rooted';
        $mensagem = null;
        $tipoMensagem = null;
        $usuarioNome = (string) $_SESSION['usuario_nome'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim((string) ($_POST['nome'] ?? ''));
            $descricao = trim((string) ($_POST['descricao'] ?? ''));
            $tipo = (string) ($_POST['tipo'] ?? 'GRATUITO');
            $dataInicio = trim((string) ($_POST['data_inicio'] ?? ''));
            $localNome = trim((string) ($_POST['local_nome'] ?? ''));
            $vagas = max(1, (int) ($_POST['vagas'] ?? 100));
            $reembolso = trim((string) ($_POST['politica_reembolso'] ?? 'TRANSFERENCIA'));

            if ($nome === '' || $descricao === '' || $dataInicio === '' || $localNome === '') {
                $mensagem = 'Preencha nome, descrição, data e local.';
                $tipoMensagem = 'erro';
            } else {
                try {
                    $pdo = Database::conectar();
                    $pdo->beginTransaction();

                    $organizacao = $pdo->prepare(
                        "INSERT INTO organizacoes (nome) VALUES (:nome)"
                    );
                    $organizacao->execute(['nome' => 'Organização de ' . $usuarioNome]);
                    $organizacaoId = (int) $pdo->lastInsertId();

                    $evento = $pdo->prepare("
                        INSERT INTO eventos
                        (organizacao_id, nome, descricao, tipo, situacao, data_inicio, local_nome)
                        VALUES (:organizacao_id, :nome, :descricao, :tipo, 'RASCUNHO', :data_inicio, :local_nome)
                    ");
                    $evento->execute([
                        'organizacao_id' => $organizacaoId,
                        'nome' => $nome,
                        'descricao' => $descricao,
                        'tipo' => in_array($tipo, ['PAGO', 'GRATUITO', 'BENEFICENTE'], true) ? $tipo : 'GRATUITO',
                        'data_inicio' => str_replace('T', ' ', $dataInicio) . ':00',
                        'local_nome' => $localNome,
                    ]);
                    $eventoId = (int) $pdo->lastInsertId();

                    $tipoIngresso = $pdo->prepare("
                        INSERT INTO tipos_ingresso (evento_id, nome, preco, vagas)
                        VALUES (:evento_id, :nome, :preco, :vagas)
                    ");
                    $preco = $tipo === 'PAGO' ? 50.00 : 0.00;
                    $tipoIngresso->execute([
                        'evento_id' => $eventoId,
                        'nome' => $tipo === 'PAGO' ? 'Ingresso geral' : 'Entrada',
                        'preco' => $preco,
                        'vagas' => $vagas,
                    ]);

                    $pdo->commit();
                    $mensagem = 'Evento criado e salvo como rascunho.';
                    $tipoMensagem = 'sucesso';
                } catch (Throwable) {
                    if (isset($pdo) && $pdo->inTransaction()) {
                        $pdo->rollBack();
                    }
                    $mensagem = 'Não foi possível criar o evento.';
                    $tipoMensagem = 'erro';
                }
            }
        }

        require __DIR__ . '/../view/pages/criar-evento.php';
    }

    public function inscritos(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /?pagina=login');
            exit;
        }

        $pageTitle = 'Inscritos | Rooted';
        $usuarioNome = (string) $_SESSION['usuario_nome'];
        $usuarioEmail = (string) $_SESSION['usuario_email'];
        $inscritos = [];
        $eventos = [];
        $eventoSelecionado = isset($_GET['evento']) ? (int) $_GET['evento'] : 0;

        try {
            $pdo = Database::conectar();
            $eventoModel = new Evento($pdo);
            $eventoModel->garantirTabelas();
            $eventos = $eventoModel->listar();

            $sql = "
                SELECT i.id, i.situacao, i.criado_em,
                       p.nome AS participante_nome, p.email, p.cpf,
                       e.id AS evento_id, e.nome AS evento_nome,
                       ti.nome AS ingresso_nome
                FROM inscricoes i
                INNER JOIN perfis p ON p.id = i.perfil_id
                INNER JOIN eventos e ON e.id = i.evento_id
                INNER JOIN tipos_ingresso ti ON ti.id = i.tipo_ingresso_id
            ";
            $parametros = [];

            if ($eventoSelecionado > 0) {
                $sql .= ' WHERE i.evento_id = :evento_id';
                $parametros['evento_id'] = $eventoSelecionado;
            }

            $sql .= ' ORDER BY i.criado_em DESC';
            $statement = $pdo->prepare($sql);
            $statement->execute($parametros);
            $inscritos = $statement->fetchAll();
        } catch (Throwable) {
            $inscritos = [];
            $eventos = [];
        }

        require __DIR__ . '/../view/pages/inscritos.php';
    }

    public function credenciamento(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /?pagina=login');
            exit;
        }

        $pageTitle = 'Credenciamento | Rooted';
        $usuarioNome = (string) $_SESSION['usuario_nome'];
        $usuarioEmail = (string) $_SESSION['usuario_email'];
        $eventos = [];
        $resultado = null;

        try {
            $pdo = Database::conectar();
            $eventoModel = new Evento($pdo);
            $eventoModel->garantirTabelas();
            $eventos = $eventoModel->listar();

            $pdo->exec("
                CREATE TABLE IF NOT EXISTS checkins (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    inscricao_id INT NOT NULL UNIQUE,
                    realizado_por INT NOT NULL,
                    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (inscricao_id) REFERENCES inscricoes(id),
                    FOREIGN KEY (realizado_por) REFERENCES perfis(id)
                )
            ");

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $eventoId = (int) ($_POST['evento_id'] ?? 0);
                $termo = trim((string) ($_POST['participante'] ?? ''));

                if ($eventoId === 0 || $termo === '') {
                    $resultado = [
                        'tipo' => 'erro',
                        'titulo' => 'Dados incompletos',
                        'texto' => 'Selecione o evento e informe nome ou CPF.',
                    ];
                } else {
                    $statement = $pdo->prepare("
                        SELECT i.id, i.situacao,
                               p.nome AS participante_nome, p.cpf,
                               ti.nome AS ingresso_nome
                        FROM inscricoes i
                        INNER JOIN perfis p ON p.id = i.perfil_id
                        INNER JOIN tipos_ingresso ti ON ti.id = i.tipo_ingresso_id
                        WHERE i.evento_id = :evento_id
                          AND (p.nome LIKE :termo OR p.cpf LIKE :termo)
                        LIMIT 1
                    ");
                    $statement->execute([
                        'evento_id' => $eventoId,
                        'termo' => '%' . $termo . '%',
                    ]);
                    $inscricao = $statement->fetch();

                    if (!$inscricao) {
                        $resultado = [
                            'tipo' => 'erro',
                            'titulo' => 'Sem ingresso neste evento',
                            'texto' => 'Nenhuma inscrição foi encontrada para os dados informados.',
                        ];
                    } elseif ($inscricao['situacao'] !== 'ATIVA') {
                        $resultado = [
                            'tipo' => 'erro',
                            'titulo' => 'Inscrição cancelada ou transferida',
                            'texto' => 'Esta inscrição não está ativa.',
                        ];
                    } else {
                        $checkin = $pdo->prepare(
                            'SELECT criado_em FROM checkins WHERE inscricao_id = :inscricao_id'
                        );
                        $checkin->execute(['inscricao_id' => (int) $inscricao['id']]);
                        $existente = $checkin->fetch();

                        if ($existente) {
                            $resultado = [
                                'tipo' => 'aviso',
                                'titulo' => 'Entrada já registrada',
                                'texto' => 'O check-in foi realizado em ' . $existente['criado_em'] . '.',
                            ];
                        } else {
                            $insert = $pdo->prepare("
                                INSERT INTO checkins (inscricao_id, realizado_por)
                                VALUES (:inscricao_id, :realizado_por)
                            ");
                            $insert->execute([
                                'inscricao_id' => (int) $inscricao['id'],
                                'realizado_por' => (int) $_SESSION['usuario_id'],
                            ]);

                            $cpf = (string) $inscricao['cpf'];
                            $cpfParcial = strlen($cpf) >= 11
                                ? substr($cpf, 0, 3) . '.***.***-' . substr($cpf, -2)
                                : '***';

                            $resultado = [
                                'tipo' => 'sucesso',
                                'titulo' => 'Entrada registrada',
                                'texto' => $inscricao['participante_nome'] . ' · CPF ' . $cpfParcial
                                    . ' · ' . $inscricao['ingresso_nome'],
                            ];
                        }
                    }
                }
            }
        } catch (Throwable) {
            $resultado = [
                'tipo' => 'erro',
                'titulo' => 'Erro no credenciamento',
                'texto' => 'Não foi possível consultar ou registrar o check-in.',
            ];
        }

        require __DIR__ . '/../view/pages/credenciamento.php';
    }

    public function gerenciarEvento(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /?pagina=login');
            exit;
        }

        $pageTitle = 'Gerenciar evento | Rooted';
        $mensagem = null;
        $tipoMensagem = null;
        $evento = null;
        $vagas = 100;
        $id = (int) ($_GET['id'] ?? 0);

        try {
            $pdo = Database::conectar();
            $statement = $pdo->prepare("
                SELECT e.*, o.nome AS organizacao,
                       COALESCE(ti.id, 0) AS tipo_ingresso_id,
                       COALESCE(ti.vagas, 100) AS vagas
                FROM eventos e
                INNER JOIN organizacoes o ON o.id = e.organizacao_id
                LEFT JOIN tipos_ingresso ti ON ti.evento_id = e.id
                WHERE e.id = :id
                LIMIT 1
            ");
            $statement->execute(['id' => $id]);
            $evento = $statement->fetch();
            if ($evento) {
                $vagas = (int) $evento['vagas'];
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $evento) {
                $nome = trim((string) ($_POST['nome'] ?? ''));
                $descricao = trim((string) ($_POST['descricao'] ?? ''));
                $tipo = (string) ($_POST['tipo'] ?? 'GRATUITO');
                $dataInicio = trim((string) ($_POST['data_inicio'] ?? ''));
                $localNome = trim((string) ($_POST['local_nome'] ?? ''));
                $novasVagas = max(1, (int) ($_POST['vagas'] ?? 100));

                if ($nome === '' || $descricao === '' || $dataInicio === '' || $localNome === '') {
                    $mensagem = 'Preencha todos os campos obrigatórios.';
                    $tipoMensagem = 'erro';
                } else {
                    $pdo->beginTransaction();
                    $updateEvento = $pdo->prepare("
                        UPDATE eventos
                        SET nome = :nome, descricao = :descricao, tipo = :tipo,
                            data_inicio = :data_inicio, local_nome = :local_nome
                        WHERE id = :id
                    ");
                    $updateEvento->execute([
                        'nome' => $nome,
                        'descricao' => $descricao,
                        'tipo' => in_array($tipo, ['PAGO', 'GRATUITO', 'BENEFICENTE'], true) ? $tipo : 'GRATUITO',
                        'data_inicio' => str_replace('T', ' ', $dataInicio),
                        'local_nome' => $localNome,
                        'id' => $id,
                    ]);

                    if ((int) $evento['tipo_ingresso_id'] > 0) {
                        $updateTipo = $pdo->prepare('UPDATE tipos_ingresso SET vagas = :vagas WHERE id = :id');
                        $updateTipo->execute(['vagas' => $novasVagas, 'id' => (int) $evento['tipo_ingresso_id']]);
                    } else {
                        $insertTipo = $pdo->prepare("
                            INSERT INTO tipos_ingresso (evento_id, nome, preco, vagas)
                            VALUES (:evento_id, :nome, :preco, :vagas)
                        ");
                        $insertTipo->execute([
                            'evento_id' => $id,
                            'nome' => $tipo === 'PAGO' ? 'Ingresso geral' : 'Entrada',
                            'preco' => $tipo === 'PAGO' ? 50.00 : 0.00,
                            'vagas' => $novasVagas,
                        ]);
                    }

                    $pdo->commit();
                    $mensagem = 'Evento atualizado com sucesso.';
                    $tipoMensagem = 'sucesso';
                    $statement->execute(['id' => $id]);
                    $evento = $statement->fetch();
                    $vagas = (int) $evento['vagas'];
                }
            }
        } catch (Throwable) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $mensagem = 'Não foi possível acessar ou atualizar o evento.';
            $tipoMensagem = 'erro';
        }

        require __DIR__ . '/../view/pages/gerenciar-evento.php';
    }

    public function excluirEvento(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /?pagina=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?pagina=painel-organizador');
            exit;
        }

        $id = (int) ($_POST['evento_id'] ?? 0);

        try {
            $pdo = Database::conectar();
            $inscricoes = $pdo->prepare('SELECT COUNT(*) FROM inscricoes WHERE evento_id = :id');
            $inscricoes->execute(['id' => $id]);

            if ((int) $inscricoes->fetchColumn() > 0) {
                $_SESSION['mensagem_publicacao'] = 'Não é possível excluir um evento com inscrições.';
                $_SESSION['tipo_mensagem_publicacao'] = 'erro';
            } else {
                $pdo->beginTransaction();
                $pdo->prepare('DELETE FROM reservas WHERE evento_id = :id')->execute(['id' => $id]);
                $pdo->prepare('DELETE FROM tipos_ingresso WHERE evento_id = :id')->execute(['id' => $id]);
                $pdo->prepare('DELETE FROM eventos WHERE id = :id')->execute(['id' => $id]);
                $pdo->commit();
                $_SESSION['mensagem_publicacao'] = 'Evento excluído com sucesso.';
                $_SESSION['tipo_mensagem_publicacao'] = 'sucesso';
            }
        } catch (Throwable) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $_SESSION['mensagem_publicacao'] = 'Não foi possível excluir o evento.';
            $_SESSION['tipo_mensagem_publicacao'] = 'erro';
        }

        header('Location: /?pagina=painel-organizador');
        exit;
    }

    public function cancelarInscricao(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /?pagina=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?pagina=painel-participante');
            exit;
        }

        $inscricaoId = (int) ($_POST['inscricao_id'] ?? 0);

        try {
            $pdo = Database::conectar();
            $compraModel = new Compra($pdo);
            $compraModel->garantirTabelas();
            $resultado = $inscricaoId > 0
                ? $compraModel->cancelarInscricao((int) $_SESSION['usuario_id'], $inscricaoId)
                : 'Inscrição não encontrada.';

            $tipo = str_starts_with($resultado, 'Inscrição cancelada')
                ? 'sucesso'
                : 'erro';

            $_SESSION['mensagem_inscricao'] = $resultado;
            $_SESSION['tipo_mensagem_inscricao'] = $tipo;
        } catch (Throwable) {
            $_SESSION['mensagem_inscricao'] = 'Não foi possível cancelar a inscrição.';
            $_SESSION['tipo_mensagem_inscricao'] = 'erro';
        }

        header('Location: /?pagina=painel-participante');
        exit;
    }

    public function editarPerfil(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /?pagina=login');
            exit;
        }

        $pageTitle = 'Editar perfil | Rooted';
        $mensagem = null;
        $tipoMensagem = null;
        $perfil = null;

        try {
            $pdo = Database::conectar();
            $model = new Perfil($pdo);
            $model->garantirTabela();
            $perfil = $model->buscarPorId((int) $_SESSION['usuario_id']);

            if ($_SERVER['REQUEST_METHOD'] === 'POST' && $perfil) {
                $nome = trim((string) ($_POST['nome'] ?? ''));
                $email = strtolower(trim((string) ($_POST['email'] ?? '')));
                $cpf = trim((string) ($_POST['cpf'] ?? ''));
                $telefone = trim((string) ($_POST['telefone'] ?? ''));

                if ($nome === '' || $email === '' || $cpf === '' || $telefone === '') {
                    $mensagem = 'Preencha todos os campos.';
                    $tipoMensagem = 'erro';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $mensagem = 'Informe um e-mail válido.';
                    $tipoMensagem = 'erro';
                } else {
                    $emailEmUso = $pdo->prepare(
                        'SELECT id FROM perfis WHERE email = :email AND id <> :id'
                    );
                    $emailEmUso->execute([
                        'email' => $email,
                        'id' => (int) $_SESSION['usuario_id'],
                    ]);

                    $cpfEmUso = $pdo->prepare(
                        'SELECT id FROM perfis WHERE cpf = :cpf AND id <> :id'
                    );
                    $cpfEmUso->execute([
                        'cpf' => $cpf,
                        'id' => (int) $_SESSION['usuario_id'],
                    ]);

                    if ($emailEmUso->fetch()) {
                        $mensagem = 'Este e-mail já está em uso.';
                        $tipoMensagem = 'erro';
                    } elseif ($cpfEmUso->fetch()) {
                        $mensagem = 'Este CPF já está em uso.';
                        $tipoMensagem = 'erro';
                    } else {
                        $model->atualizar((int) $_SESSION['usuario_id'], [
                            'nome' => $nome,
                            'email' => $email,
                            'cpf' => $cpf,
                            'telefone' => $telefone,
                        ]);

                        $_SESSION['usuario_nome'] = $nome;
                        $_SESSION['usuario_email'] = $email;
                        $mensagem = 'Perfil atualizado com sucesso.';
                        $tipoMensagem = 'sucesso';
                        $perfil = $model->buscarPorId((int) $_SESSION['usuario_id']);
                    }
                }
            }
        } catch (Throwable) {
            $mensagem = 'Não foi possível atualizar o perfil.';
            $tipoMensagem = 'erro';
        }

        require __DIR__ . '/../view/pages/editar-perfil.php';
    }

    public function excluirConta(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /?pagina=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /?pagina=painel-participante');
            exit;
        }

        $id = (int) $_SESSION['usuario_id'];

        try {
            $pdo = Database::conectar();
            $inscricoes = $pdo->prepare('SELECT COUNT(*) FROM inscricoes WHERE perfil_id = :id');
            $inscricoes->execute(['id' => $id]);

            if ((int) $inscricoes->fetchColumn() > 0) {
                $_SESSION['mensagem_conta'] = 'Não é possível excluir a conta enquanto houver inscrições.';
                $_SESSION['tipo_mensagem_conta'] = 'erro';
            } else {
                $pdo->beginTransaction();
                $pdo->prepare('DELETE FROM reservas WHERE perfil_id = :id')->execute(['id' => $id]);
                $pdo->prepare('DELETE FROM perfis WHERE id = :id')->execute(['id' => $id]);
                $pdo->commit();

                session_destroy();
                header('Location: /?pagina=login&conta=excluida');
                exit;
            }
        } catch (Throwable) {
            if (isset($pdo) && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $_SESSION['mensagem_conta'] = 'Não foi possível excluir a conta.';
            $_SESSION['tipo_mensagem_conta'] = 'erro';
        }

        header('Location: /?pagina=painel-participante');
        exit;
    }

    public function painelSeeds(): void
    {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /?pagina=login');
            exit;
        }

        if (($_SESSION['usuario_tipo'] ?? '') !== 'admin') {
            header('Location: /?pagina=painel-participante');
            exit;
        }

        $pageTitle = 'Painel da Seeds | Rooted';
        $usuarioNome = (string) $_SESSION['usuario_nome'];
        $usuarioEmail = (string) $_SESSION['usuario_email'];
        $organizacoes = [];
        $eventosBeneficentes = [];

        try {
            $pdo = Database::conectar();

            $pdo->exec("
                CREATE TABLE IF NOT EXISTS organizacoes (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    nome VARCHAR(150) NOT NULL,
                    situacao VARCHAR(20) NOT NULL DEFAULT 'APROVADA'
                )
            ");

            $organizacoes = $pdo
                ->query('SELECT id, nome, situacao FROM organizacoes ORDER BY nome')
                ->fetchAll();

            $eventoModel = new Evento($pdo);
            $eventoModel->garantirTabelas();
            $eventosBeneficentes = $pdo
                ->query("
                    SELECT e.id, e.nome, e.situacao, o.nome AS organizacao
                    FROM eventos e
                    INNER JOIN organizacoes o ON o.id = e.organizacao_id
                    WHERE e.tipo = 'BENEFICENTE'
                    ORDER BY e.data_inicio
                ")
                ->fetchAll();
        } catch (Throwable) {
            $organizacoes = [];
            $eventosBeneficentes = [];
        }

        require __DIR__ . '/../view/pages/painel-seeds.php';
    }
}

