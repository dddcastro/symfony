<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240829180603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE beneficiarios (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, data_nascimento DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE consultas (id INT AUTO_INCREMENT NOT NULL, beneficiario_id INT DEFAULT NULL, medico_id INT DEFAULT NULL, hospital_id INT DEFAULT NULL, data DATE NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_7AC3CEE74B64ABC7 (beneficiario_id), INDEX IDX_7AC3CEE7A7FB1C0C (medico_id), INDEX IDX_7AC3CEE763DBB69 (hospital_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hospitais (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, endereco VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE medicos (id INT AUTO_INCREMENT NOT NULL, hospital_id INT DEFAULT NULL, nome VARCHAR(255) NOT NULL, especialidade VARCHAR(255) NOT NULL, INDEX IDX_6450272163DBB69 (hospital_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE observacoes (id INT AUTO_INCREMENT NOT NULL, consulta_id INT DEFAULT NULL, descricao LONGTEXT NOT NULL, anexo VARCHAR(255) NOT NULL, INDEX IDX_95DC4C27E38D288B (consulta_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE consultas ADD CONSTRAINT FK_7AC3CEE74B64ABC7 FOREIGN KEY (beneficiario_id) REFERENCES beneficiarios (id)');
        $this->addSql('ALTER TABLE consultas ADD CONSTRAINT FK_7AC3CEE7A7FB1C0C FOREIGN KEY (medico_id) REFERENCES medicos (id)');
        $this->addSql('ALTER TABLE consultas ADD CONSTRAINT FK_7AC3CEE763DBB69 FOREIGN KEY (hospital_id) REFERENCES hospitais (id)');
        $this->addSql('ALTER TABLE medicos ADD CONSTRAINT FK_6450272163DBB69 FOREIGN KEY (hospital_id) REFERENCES hospitais (id)');
        $this->addSql('ALTER TABLE observacoes ADD CONSTRAINT FK_95DC4C27E38D288B FOREIGN KEY (consulta_id) REFERENCES consultas (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultas DROP FOREIGN KEY FK_7AC3CEE74B64ABC7');
        $this->addSql('ALTER TABLE consultas DROP FOREIGN KEY FK_7AC3CEE7A7FB1C0C');
        $this->addSql('ALTER TABLE consultas DROP FOREIGN KEY FK_7AC3CEE763DBB69');
        $this->addSql('ALTER TABLE medicos DROP FOREIGN KEY FK_6450272163DBB69');
        $this->addSql('ALTER TABLE observacoes DROP FOREIGN KEY FK_95DC4C27E38D288B');
        $this->addSql('DROP TABLE beneficiarios');
        $this->addSql('DROP TABLE consultas');
        $this->addSql('DROP TABLE hospitais');
        $this->addSql('DROP TABLE medicos');
        $this->addSql('DROP TABLE observacoes');
    }
}
