<?php

declare(strict_types=1);

final class Perfil
{
    public function __construct(private PDO $pdo)
    {
    }

    public function criar(array $dados): void
    {
        $sql = 'INSERT INTO perfis (nome, email, cpf, telefone, senha_hash)
                VALUES (:nome, :email, :cpf, :telefone, :senha_hash)';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            'nome' => $dados['nome'],
            'email' => $dados['email'],
            'cpf' => $dados['cpf'],
            'telefone' => $dados['telefone'],
            'senha_hash' => password_hash($dados['senha'], PASSWORD_BCRYPT),
        ]);
    }

    public function buscarPorEmail(string $email): array|false
    {
        $statement = $this->pdo->prepare(
            'SELECT id, nome, email, senha_hash FROM perfis WHERE email = :email'
        );
        $statement->execute(['email' => $email]);

        return $statement->fetch();
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

        return $statement->fetch();
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
    }
}
