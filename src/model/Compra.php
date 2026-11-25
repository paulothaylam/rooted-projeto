<?php

declare(strict_types=1);

final class Compra
{
    private ?int $perfilId = null;
    private ?int $eventoId = null;
    private ?int $tipoIngressoId = null;
    private ?int $vagas = null;
    private string $situacaoReserva = 'ATIVA';
    private ?string $venceEm = null;
    private ?int $inscricaoId = null;
    private string $situacaoInscricao = 'ATIVA';

    public function __construct(private PDO $pdo)
    {
    }

    public function garantirTabelas(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS tipos_ingresso (
                id INT AUTO_INCREMENT PRIMARY KEY,
                evento_id INT NOT NULL,
                nome VARCHAR(150) NOT NULL,
                preco DECIMAL(10,2) NOT NULL DEFAULT 0,
                vagas INT NOT NULL DEFAULT 100,
                UNIQUE KEY tipo_evento (evento_id, nome),
                FOREIGN KEY (evento_id) REFERENCES eventos(id)
            )
        ");

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS reservas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                perfil_id INT NOT NULL,
                evento_id INT NOT NULL,
                tipo_ingresso_id INT NOT NULL,
                situacao VARCHAR(20) NOT NULL DEFAULT 'ATIVA',
                vence_em DATETIME NOT NULL,
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (perfil_id) REFERENCES perfis(id),
                FOREIGN KEY (evento_id) REFERENCES eventos(id),
                FOREIGN KEY (tipo_ingresso_id) REFERENCES tipos_ingresso(id)
            )
        ");

        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS inscricoes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                perfil_id INT NOT NULL,
                evento_id INT NOT NULL,
                tipo_ingresso_id INT NOT NULL,
                situacao VARCHAR(20) NOT NULL DEFAULT 'ATIVA',
                criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE KEY inscricao_perfil_evento (perfil_id, evento_id),
                FOREIGN KEY (perfil_id) REFERENCES perfis(id),
                FOREIGN KEY (evento_id) REFERENCES eventos(id),
                FOREIGN KEY (tipo_ingresso_id) REFERENCES tipos_ingresso(id)
            )
        ");
    }

    public function cadastrarTiposPadrao(int $eventoId, string $tipoEvento): void
    {
        $statement = $this->pdo->prepare('SELECT COUNT(*) FROM tipos_ingresso WHERE evento_id = :evento_id');
        $statement->execute(['evento_id' => $eventoId]);
        $quantidade = (int) $statement->fetchColumn();

        if ($quantidade > 0) {
            return;
        }

        $preco = $tipoEvento === 'PAGO' ? 50.00 : 0.00;
        $nome = $tipoEvento === 'PAGO' ? 'Ingresso geral' : ($tipoEvento === 'BENEFICENTE' ? 'Contribuição solidária' : 'Entrada gratuita');

        $insert = $this->pdo->prepare("
            INSERT INTO tipos_ingresso (evento_id, nome, preco, vagas)
            VALUES (:evento_id, :nome, :preco, 100)
        ");
        $insert->execute([
            'evento_id' => $eventoId,
            'nome' => $nome,
            'preco' => $preco,
        ]);
    }

    public function tiposPorEvento(int $eventoId): array
    {
        $statement = $this->pdo->prepare(
            'SELECT id, nome, preco, vagas FROM tipos_ingresso WHERE evento_id = :evento_id ORDER BY preco'
        );
        $statement->execute(['evento_id' => $eventoId]);

        return $statement->fetchAll();
    }

    public function reservar(int $perfilId, int $eventoId, int $tipoIngressoId, bool $gratuito): string
    {
        $this->pdo->beginTransaction();

        try {
            $this->perfilId = $perfilId;
            $this->eventoId = $eventoId;
            $this->tipoIngressoId = $tipoIngressoId;
            $this->situacaoReserva = 'ATIVA';
            $this->situacaoInscricao = $gratuito ? 'ATIVA' : 'ATIVA';

            $statement = $this->pdo->prepare("
                SELECT vagas FROM tipos_ingresso
                WHERE id = :id AND evento_id = :evento_id
                FOR UPDATE
            ");
            $statement->execute(['id' => $tipoIngressoId, 'evento_id' => $eventoId]);
            $tipo = $statement->fetch();

            if (!$tipo || (int) $tipo['vagas'] < 1) {
                $this->pdo->rollBack();
                return 'Não há vagas disponíveis.';
            }

            $this->vagas = (int) $tipo['vagas'] - 1;

            $venceEm = (new DateTime('+15 minutes'))->format('Y-m-d H:i:s');
            $this->venceEm = $venceEm;
            $insertReserva = $this->pdo->prepare("
                INSERT INTO reservas (perfil_id, evento_id, tipo_ingresso_id, situacao, vence_em)
                VALUES (:perfil_id, :evento_id, :tipo_ingresso_id, 'ATIVA', :vence_em)
            ");
            $insertReserva->execute([
                'perfil_id' => $perfilId,
                'evento_id' => $eventoId,
                'tipo_ingresso_id' => $tipoIngressoId,
                'vence_em' => $venceEm,
            ]);

            $update = $this->pdo->prepare(
                'UPDATE tipos_ingresso SET vagas = vagas - 1 WHERE id = :id'
            );
            $update->execute(['id' => $tipoIngressoId]);

            if ($gratuito) {
                $insertInscricao = $this->pdo->prepare("
                    INSERT INTO inscricoes (perfil_id, evento_id, tipo_ingresso_id, situacao)
                    VALUES (:perfil_id, :evento_id, :tipo_ingresso_id, 'ATIVA')
                ");
                $insertInscricao->execute([
                    'perfil_id' => $perfilId,
                    'evento_id' => $eventoId,
                    'tipo_ingresso_id' => $tipoIngressoId,
                ]);
                $this->inscricaoId = (int) $this->pdo->lastInsertId();
            }

            $this->pdo->commit();
            return $gratuito
                ? 'Inscrição gratuita confirmada!'
                : 'Reserva criada por 15 minutos.';
        } catch (Throwable $exception) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            if ($exception instanceof PDOException
                && str_contains($exception->getMessage(), 'inscricao_perfil_evento')) {
                return 'Você já possui uma inscrição neste evento.';
            }

            return 'Não foi possível criar a reserva.';
        }
    }

    public function cancelarInscricao(int $perfilId, int $inscricaoId): string
    {
        $this->pdo->beginTransaction();

        try {
            $this->perfilId = $perfilId;
            $this->inscricaoId = $inscricaoId;

            $statement = $this->pdo->prepare("
                SELECT i.id, i.situacao, i.tipo_ingresso_id
                FROM inscricoes i
                WHERE i.id = :inscricao_id
                  AND i.perfil_id = :perfil_id
                FOR UPDATE
            ");
            $statement->execute([
                'inscricao_id' => $inscricaoId,
                'perfil_id' => $perfilId,
            ]);
            $inscricao = $statement->fetch();

            if (!$inscricao) {
                $this->pdo->rollBack();
                return 'Inscrição não encontrada.';
            }

            $this->tipoIngressoId = (int) $inscricao['tipo_ingresso_id'];

            if ($inscricao['situacao'] !== 'ATIVA') {
                $this->pdo->rollBack();
                return 'Esta inscrição já foi cancelada ou transferida.';
            }

            $cancelar = $this->pdo->prepare(
                "UPDATE inscricoes SET situacao = 'CANCELADA' WHERE id = :id"
            );
            $cancelar->execute(['id' => $inscricaoId]);
            $this->situacaoInscricao = 'CANCELADA';

            $devolverVaga = $this->pdo->prepare(
                'UPDATE tipos_ingresso SET vagas = vagas + 1 WHERE id = :id'
            );
            $devolverVaga->execute([
                'id' => (int) $inscricao['tipo_ingresso_id'],
            ]);

            $this->pdo->commit();
            return 'Inscrição cancelada com sucesso. A vaga voltou a ficar disponível.';
        } catch (Throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return 'Não foi possível cancelar a inscrição.';
        }
    }
}
