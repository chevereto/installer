<?php

final class Database
{
    const PRIVILEGES = ['ALTER', 'CREATE', 'DELETE', 'DROP', 'INDEX', 'INSERT', 'SELECT', 'UPDATE'];

    private string $host;

    private string $port;

    private string $name;

    private string $user;

    private string $userPassword;

    private PDO $pdo;

    public function __construct(string $host, string $port, string $name, string $user, string $userPassword)
    {
        $pdoAttrs = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        $this->pdo = new PDO("mysql:host=$host;port=$port;dbname=$name", $user, $userPassword, $pdoAttrs);
        $this->host = $host;
        $this->port = $port;
        $this->name = $name;
        $this->user = $user;
        $this->userPassword = $userPassword;
    }

    public function checkEmpty(): void
    {
        $query = $this->pdo->query("SHOW TABLES FROM `$this->name`;");
        $tables = $query->fetchAll(PDO::FETCH_COLUMN);
        if (!empty($tables)) {
            throw new LogicException(sprintf('Database "%s" is not empty.', $this->name));
        }
    }

    public function checkPrivileges(): void
    {
        $query = $this->pdo->query('SHOW GRANTS FOR CURRENT_USER;');
        $tables = $query->fetchAll(PDO::FETCH_COLUMN, 0);

        foreach ($tables as $v) {
            if (false === preg_match_all('#^GRANT ([\w\,\s]*) ON (.*)\.(.*) TO *#', $v, $matches)) {
                continue;
            }
            $database = $this->unquote($matches[2][0]);
            if (in_array($database, ['%', '*'])) {
                $database = $this->name;
            }
            if ($database != $this->name) {
                continue;
            }
            $privileges = $matches[1][0];
            if ($privileges == 'ALL PRIVILEGES') {
                return;
            } else {
                $missed = [];
                $privileges = explode(', ', $matches[1][0]);
                foreach (static::PRIVILEGES as $privilege) {
                    if (!in_array($privilege, $privileges)) {
                        $missed[] = $privilege;
                    }
                }
                if (empty($missed)) {
                    return;
                }
            }
        }
        throw new Exception(strtr('Database user `%user%` doesn\'t have %privilege% privilege on the `%dbName%` database.', [
            '%user%' => $this->user,
            '%privilege%' => implode(', ', $missed),
            '%dbName%' => $this->name,
        ]));
    }

    private function unquote(string $quoted): string
    {
        return str_replace(['`', "'"], '', stripslashes($quoted));
    }
}
