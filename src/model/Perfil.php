<?php

declare(strict_types=1);

final class Perfil
{
    private ?int $id = null;
    private ?string $nome = null;
    private ?string $email = null;
    private ?string $cpf = null;
    private ?string $telefone = null;
    private ?string $senhaHash = null;
    private string $nivelConta = 'bronze';
    private string $tipoPerfil = 'participante';
    private ?string $criadoEm = null;

    public function __construct(private PDO $pdo)
    {
    }

    public function criar(array $dados): void
    {
        $tipoPerfil = $dados['tipo_perfil'] ?? '';
        if (!in_array($tipoPerfil, ['participante', 'organizador'], true)) {
            throw new InvalidArgumentException('Tipo de perfil inválido.');
        }

        $senhaHash = password_hash($dados['senha'], PASSWORD_BCRYPT);

        $sql = 'INSERT INTO perfis (nome, email, cpf, telefone, senha_hash, tipo_perfil)
                VALUES (:nome, :email, :cpf, :telefone, :senha_hash, :tipo_perfil)';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'cpf' => $dados['cpf'],
            'telefone' => $dados['telefone'],
            'senha_hash' => $senhaHash,
            'tipo_perfil' => $tipoPerfil,
        ]);

        $this->id = (int) $this->pdo->lastInsertId();
        $this->nome = $dados['nome'];
        $this->email = $dados['email'];
        $this->cpf = $dados['cpf'];
        $this->telefone = $dados['telefone'];
        $this->senhaHash = $senhaHash;
        $this->nivelConta = 'bronze';
        $this->tipoPerfil = $tipoPerfil;
    }

    public function buscarPorEmail(string $email): array|false
    {
        $statement = $this->pdo->prepare(
            'SELECT id, nome, email, senha_hash, tipo_perfil FROM perfis WHERE email = :email'
        );
        $statement->execute(['email' => $email]);

        $perfil = $statement->fetch();

        if ($perfil) {
            $this->id = (int) $perfil['id'];
            $this->nome = $perfil['nome'];
            $this->email = $perfil['email'];
            $this->senhaHash = $perfil['senha_hash'];
            $this->tipoPerfil = $perfil['tipo_perfil'];
        }

        return $perfil;
    }

    public function emailExiste(string $email): bool
    {
        $statement = $this->pdo->prepare('SELECT 1 FROM perfis WHERE email = :email');
        $statement->execute(['email' => $email]);

        return (bool) $statement->fetchColumn();
    }

    public function cpfExiste(string $cpf): bool
    {
        $statement = $this->pdo->prepare('SELECT 1 FROM perfis WHERE cpf = :cpf');
        $statement->execute(['cpf' => $cpf]);

        return (bool) $statement->fetchColumn();
    }

    public function garantirTabela(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS perfis (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nome VARCHAR(150) NOT NULL,
                email VARCHAR(180) NOT NULL UNIQUE,
                cpf VARCHAR(14) NOT NULL UNIQUE,
                telefone VARCHAR(30) NOT NULL,
                senha_hash VARCHAR(255) NOT NULL,
                tipo_perfil ENUM('participante', 'organizador', 'admin') NOT NULL DEFAULT 'participante',
                nivel_conta VARCHAR(20) NOT NULL DEFAULT 'bronze',
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function buscarPorId(int $id): array|false
    {
        $statement = $this->pdo->prepare(
            'SELECT id, nome, email, cpf, telefone FROM perfis WHERE id = :id'
        );
        $statement->execute(['id' => $id]);

        $perfil = $statement->fetch();

        if ($perfil) {
            $this->id = (int) $perfil['id'];
            $this->nome = $perfil['nome'];
            $this->email = $perfil['email'];
            $this->cpf = $perfil['cpf'];
            $this->telefone = $perfil['telefone'];
        }

        return $perfil;
    }

    public function atualizar(int $id, array $dados): void
    {
        $statement = $this->pdo->prepare("
            UPDATE perfis
            SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone
            WHERE id = :id
        ");
        $statement->execute([
            'id' => $id,
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'cpf' => $dados['cpf'],
            'telefone' => $dados['telefone'],
        ]);

        $this->id = $id;
        $this->nome = $dados['nome'];
        $this->email = $dados['email'];
        $this->cpf = $dados['cpf'];
        $this->telefone = $dados['telefone'];
    }

}
