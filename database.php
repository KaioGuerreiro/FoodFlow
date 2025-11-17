<?php
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct()
    {
        try {
            $dbPath = __DIR__ . '/database.sqlite';
            $this->pdo = new PDO('sqlite:' . $dbPath);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->createTables();
        } catch (PDOException $e) {
            die('Erro de conexão com o banco SQLite: ' . $e->getMessage());
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public function create($table, $arr)
    {
        try {
            if (!$this->tableExists($table)) {
                throw new Exception("Tabela $table não existe!");
            }
            $fields = array_keys($arr);
            $placeholders = rtrim(str_repeat('?,', count($fields)), ',');
            $values = array_values($arr);
            $sql = "INSERT INTO $table (" . implode(', ', $fields) . ") VALUES ($placeholders)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($values);
            return ['success' => true, 'id' => $this->pdo->lastInsertId(), 'message' => 'Registro criado com sucesso!'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function read($table, $conditions = [], $limit = null, $offset = null)
    {
        try {
            if (!$this->tableExists($table)) {
                throw new Exception("Tabela $table não existe!");
            }
            $sql = "SELECT * FROM $table";
            $params = [];
            if (!empty($conditions)) {
                $where = [];
                foreach ($conditions as $field => $value) {
                    $where[] = "$field = ?";
                    $params[] = $value;
                }
                $sql .= ' WHERE ' . implode(' AND ', $where);
            }
            if ($limit !== null) {
                $sql .= ' LIMIT ' . (int)$limit;
                if ($offset !== null) {
                    $sql .= ' OFFSET ' . (int)$offset;
                }
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $data = $stmt->fetchAll();
            return ['success' => true, 'data' => $data, 'count' => count($data)];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function update($table, $data, $conditions = [])
    {
        try {
            if (!$this->tableExists($table)) {
                throw new Exception("Tabela $table não existe!");
            }
            $fields = [];
            $params = [];
            foreach ($data as $key => $value) {
                $fields[] = "$key = ?";
                $params[] = $value;
            }
            $sql = "UPDATE $table SET " . implode(', ', $fields);
            if (!empty($conditions)) {
                $where = [];
                foreach ($conditions as $key => $value) {
                    $where[] = "$key = ?";
                    $params[] = $value;
                }
                $sql .= ' WHERE ' . implode(' AND ', $where);
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return ['success' => true, 'message' => $stmt->rowCount() > 0 ? 'Registro atualizado!' : 'Nenhum registro alterado.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function delete($table, $conditions = [])
    {
        try {
            if (!$this->tableExists($table)) {
                throw new Exception("Tabela $table não existe!");
            }
            $sql = "DELETE FROM $table";
            $params = [];
            if (!empty($conditions)) {
                $where = [];
                foreach ($conditions as $key => $value) {
                    $where[] = "$key = ?";
                    $params[] = $value;
                }
                $sql .= ' WHERE ' . implode(' AND ', $where);
            }
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return ['success' => true, 'message' => $stmt->rowCount() > 0 ? 'Registro deletado!' : 'Nenhum registro removido.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    private function tableExists($table)
    {
        $stmt = $this->pdo->prepare("SELECT name FROM sqlite_master WHERE type='table' AND name=?");
        $stmt->execute([$table]);
        return $stmt->fetch() !== false;
    }

    private function createTables()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT,
            email TEXT UNIQUE,
            senha TEXT NOT NULL,
            tipo_usuario TEXT DEFAULT 'comum'
        )");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS produtos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nome TEXT NOT NULL,
            descricao TEXT,
            preco REAL DEFAULT 0.0,
            categoria TEXT DEFAULT 'prato',
            criado_em DATETIME DEFAULT (datetime('now'))
        )");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS pedidos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            total REAL DEFAULT 0.0,
            status TEXT DEFAULT 'pendente',
            criado_em DATETIME DEFAULT (datetime('now')),
            FOREIGN KEY(user_id) REFERENCES users(id)
        )");

        $this->pdo->exec("CREATE TABLE IF NOT EXISTS pedido_items (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pedido_id INTEGER NOT NULL,
            produto_id INTEGER NOT NULL,
            quantidade INTEGER DEFAULT 1,
            preco_unitario REAL DEFAULT 0.0,
            FOREIGN KEY(pedido_id) REFERENCES pedidos(id),
            FOREIGN KEY(produto_id) REFERENCES produtos(id)
        )");
    }
}

// Inicializa a instância ao incluir o arquivo
$db = Database::getInstance();

?>
