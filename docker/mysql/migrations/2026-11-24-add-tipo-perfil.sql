ALTER TABLE perfis
    ADD COLUMN tipo_perfil ENUM('participante', 'organizador', 'admin')
        NOT NULL DEFAULT 'participante'
        AFTER telefone;
