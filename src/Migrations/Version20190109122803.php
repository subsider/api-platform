<?php declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\User;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190109122803 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_BA5AE01DF675F31B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__blog_post AS SELECT id, author_id, title, published, content, slug FROM blog_post');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('CREATE TABLE blog_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, published DATETIME NOT NULL, content CLOB NOT NULL COLLATE BINARY, slug VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_BA5AE01DF675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO blog_post (id, author_id, title, published, content, slug) SELECT id, author_id, title, published, content, slug FROM __temp__blog_post');
        $this->addSql('DROP TABLE __temp__blog_post');
        $this->addSql('CREATE INDEX IDX_BA5AE01DF675F31B ON blog_post (author_id)');
        $this->addSql('ALTER TABLE user ADD COLUMN roles CLOB NOT NULL DEFAULT ' . User::ROLE_COMMENTATOR);
        $this->addSql('DROP INDEX IDX_9474526CA77FBEAF');
        $this->addSql('DROP INDEX IDX_9474526CF675F31B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, author_id, blog_post_id, content, published FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, blog_post_id INTEGER NOT NULL, content CLOB NOT NULL COLLATE BINARY, published DATETIME NOT NULL, CONSTRAINT FK_9474526CA77FBEAF FOREIGN KEY (blog_post_id) REFERENCES blog_post (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comment (id, author_id, blog_post_id, content, published) SELECT id, author_id, blog_post_id, content, published FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526CA77FBEAF ON comment (blog_post_id)');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_BA5AE01DF675F31B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__blog_post AS SELECT id, author_id, title, slug, published, content FROM blog_post');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('CREATE TABLE blog_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) DEFAULT NULL, published DATETIME NOT NULL, content CLOB NOT NULL)');
        $this->addSql('INSERT INTO blog_post (id, author_id, title, slug, published, content) SELECT id, author_id, title, slug, published, content FROM __temp__blog_post');
        $this->addSql('DROP TABLE __temp__blog_post');
        $this->addSql('CREATE INDEX IDX_BA5AE01DF675F31B ON blog_post (author_id)');
        $this->addSql('DROP INDEX IDX_9474526CA77FBEAF');
        $this->addSql('DROP INDEX IDX_9474526CF675F31B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, blog_post_id, author_id, content, published FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, blog_post_id INTEGER NOT NULL, author_id INTEGER NOT NULL, content CLOB NOT NULL, published DATETIME NOT NULL)');
        $this->addSql('INSERT INTO comment (id, blog_post_id, author_id, content, published) SELECT id, blog_post_id, author_id, content, published FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526CA77FBEAF ON comment (blog_post_id)');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, name, username, password, email FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, name, username, password, email) SELECT id, name, username, password, email FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
