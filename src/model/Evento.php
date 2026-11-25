<?php

declare(strict_types=1);

final class Evento
{
    private ?int $id = null;
    private ?int $organizacaoId = null;
    private ?string $nome = null;
    private ?string $descricao = null;
    private ?string $tipo = null;
    private string $situacao = 'PUBLICADO';
    private ?string $dataInicio = null;
    private ?string $localNome = null;
    private ?string $organizacao = null;
    private ?string $criadoEm = null;

    public function __construct(private PDO $pdo)
    {
    }

    public function garantirTabelas(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS organizacoes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(150) NOT NULL,
                situacao VARCHAR(20) NOT NULL DEFAULT 'APROVADA'
            )
        ");

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS eventos (
                id INT AUTO_INCREMENT PRIMARY KEY,
                organizacao_id INT NOT NULL,
                nome VARCHAR(180) NOT NULL,
                descricao TEXT NOT NULL,
                capa VARCHAR(255) DEFAULT NULL,
                tipo VARCHAR(20) NOT NULL DEFAULT 'GRATUITO',
                situacao VARCHAR(20) NOT NULL DEFAULT 'PUBLICADO',
                data_inicio DATETIME NOT NULL,
                local_nome VARCHAR(180) NOT NULL,
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (organizacao_id) REFERENCES organizacoes(id)
            )
        ");
    }

    public function cadastrarExemplos(): void
    {
        $quantidade = (int) $this->pdo->query('SELECT COUNT(*) FROM eventos')->fetchColumn();
        if ($quantidade > 0) {
            return;
        }

        $this->pdo->exec("
            INSERT INTO organizacoes (nome) VALUES
            ('Oxygeni Hub'),
            ('Seeds Tecnologia'),
            ('Ceuma Universidade')
        ");

        $organizacaoIds = $this->pdo
            ->query('SELECT id, nome FROM organizacoes ORDER BY id')
            ->fetchAll();

        $porNome = [];
        foreach ($organizacaoIds as $organizacao) {
            $porNome[$organizacao['nome']] = (int) $organizacao['id'];
        }

        $statement = $this->pdo->prepare("
            INSERT INTO eventos
            (organizacao_id, nome, descricao, tipo, situacao, data_inicio, local_nome)
            VALUES (:organizacao_id, :nome, :descricao, :tipo, 'PUBLICADO', :data_inicio, :local_nome)
        ");

        $exemplos = [
            [
                'nome' => 'Conecta Innovation',
                'descricao' => 'Um encontro para conhecer projetos, empreendedores e oportunidades no ecossistema de inovação.',
                'tipo' => 'GRATUITO',
                'data_inicio' => '2026-12-12 19:00:00',
                'local_nome' => 'Oxygeni Hub - São Luís',
                'organizacao' => 'Oxygeni Hub',
            ],
            [
                'nome' => 'Rooted Experience',
                'descricao' => 'Experiência prática sobre tecnologia, networking e construção de comunidades.',
                'tipo' => 'PAGO',
                'data_inicio' => '2027-01-24 09:00:00',
                'local_nome' => 'Centro de Convenções',
                'organizacao' => 'Seeds Tecnologia',
            ],
            [
                'nome' => 'Futuro Digital',
                'descricao' => 'Palestras e oficinas sobre carreira, inovação e as tecnologias que transformam o mercado.',
                'tipo' => 'BENEFICENTE',
                'data_inicio' => '2027-02-08 14:00:00',
                'local_nome' => 'Universidade Ceuma',
                'organizacao' => 'Ceuma Universidade',
            ],
        ];

        foreach ($exemplos as $exemplo) {
            $statement->execute([
                'organizacao_id' => $porNome[$exemplo['organizacao']],
                'nome' => $exemplo['nome'],
                'descricao' => $exemplo['descricao'],
                'tipo' => $exemplo['tipo'],
                'data_inicio' => $exemplo['data_inicio'],
                'local_nome' => $exemplo['local_nome'],
            ]);
        }
    }

    public function listar(): array
    {
        return $this->listarFiltrado('', 'todos');
    }

    public function listarTodosParaOrganizador(): array
    {
        $statement = $this->pdo->query("
            SELECT e.id, e.nome, e.descricao, e.tipo, e.situacao,
                   e.data_inicio, e.local_nome,
                   o.nome AS organizacao,
                   COALESCE(SUM(ti.vagas), 0) AS vagas
            FROM eventos e
            INNER JOIN organizacoes o ON o.id = e.organizacao_id
            LEFT JOIN tipos_ingresso ti ON ti.evento_id = e.id
            GROUP BY e.id, e.nome, e.descricao, e.tipo, e.situacao,
                     e.data_inicio, e.local_nome, o.nome
            ORDER BY e.criado_em DESC
        ");

        return $statement->fetchAll();
    }

    public function publicar(int $id): array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, tipo, situacao FROM eventos WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
        $evento = $statement->fetch();

        if (!$evento) {
            return ['tipo' => 'erro', 'mensagem' => 'Evento não encontrado.'];
        }

        $this->id = (int) $evento['id'];
        $this->tipo = $evento['tipo'];
        $this->situacao = $evento['situacao'];

        if ($evento['situacao'] === 'PUBLICADO') {
            return ['tipo' => 'aviso', 'mensagem' => 'Este evento já está publicado.'];
        }

        if ($evento['tipo'] === 'PAGO') {
            return [
                'tipo' => 'erro',
                'mensagem' => 'Evento pago bloqueado: conecte o Mercado Pago antes da publicação.',
            ];
        }

        if ($evento['tipo'] === 'BENEFICENTE') {
            $update = $this->pdo->prepare(
                "UPDATE eventos SET situacao = 'EM_APROVACAO' WHERE id = :id"
            );
            $update->execute(['id' => $id]);
            $this->situacao = 'EM_APROVACAO';
            $this->situacao = 'PUBLICADO';

            return [
                'tipo' => 'sucesso',
                'mensagem' => 'Evento beneficente enviado para aprovação da Seeds.',
            ];
        }

        $update = $this->pdo->prepare(
            "UPDATE eventos SET situacao = 'PUBLICADO' WHERE id = :id"
        );
        $update->execute(['id' => $id]);

        return [
            'tipo' => 'sucesso',
            'mensagem' => 'Evento gratuito publicado na vitrine.',
        ];
    }

    public function listarFiltrado(string $busca = '', string $tipo = 'todos'): array
    {
        $sql = "
            SELECT e.id, e.nome, e.descricao, e.tipo, e.data_inicio, e.local_nome,
                   o.nome AS organizacao,
                   COALESCE(SUM(ti.vagas), 0) AS vagas
            FROM eventos e
            INNER JOIN organizacoes o ON o.id = e.organizacao_id
            LEFT JOIN tipos_ingresso ti ON ti.evento_id = e.id
            WHERE e.situacao = 'PUBLICADO'
        ";

        $parametros = [];

        if ($busca !== '') {
            $sql .= ' AND (e.nome LIKE :busca OR o.nome LIKE :busca OR e.local_nome LIKE :busca)';
            $parametros['busca'] = '%' . $busca . '%';
        }

        if ($tipo === 'pagos') {
            $sql .= " AND e.tipo = 'PAGO'";
        } elseif ($tipo === 'gratuitos') {
            $sql .= " AND e.tipo IN ('GRATUITO', 'BENEFICENTE')";
        }

        $sql .= ' GROUP BY e.id, e.nome, e.descricao, e.tipo, e.data_inicio, e.local_nome, o.nome
                  ORDER BY e.data_inicio';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($parametros);

        return $statement->fetchAll();
    }

    public function buscarPorId(int $id): array|false
    {
        $statement = $this->pdo->prepare("
            SELECT e.id, e.nome, e.descricao, e.tipo, e.data_inicio, e.local_nome,
                   o.nome AS organizacao
            FROM eventos e
            INNER JOIN organizacoes o ON o.id = e.organizacao_id
            WHERE e.id = :id AND e.situacao = 'PUBLICADO'
        ");
        $statement->execute(['id' => $id]);
        $evento = $statement->fetch();

        if ($evento) {
            $this->id = (int) $evento['id'];
            $this->nome = $evento['nome'];
            $this->descricao = $evento['descricao'];
            $this->tipo = $evento['tipo'];
            $this->dataInicio = $evento['data_inicio'];
            $this->localNome = $evento['local_nome'];
            $this->organizacao = $evento['organizacao'];
        }

        return $evento;
    }
}
