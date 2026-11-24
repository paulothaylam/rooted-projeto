<?php

declare(strict_types=1);

final class TesteConexao
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @return array<int, array{id: int, mensagem: string, criado_em: string}>
     */
    public function listarMensagens(): array
    {
        $statement = $this->pdo->query(
            'SELECT id, mensagem, criado_em FROM teste_conexao ORDER BY id DESC LIMIT 5'
        );

        return $statement->fetchAll();
    }
}

