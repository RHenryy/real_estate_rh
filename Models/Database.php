<?php
class Database
{
    protected ?PDO $connection;
    private ?string $db_host;
    private ?string $db_name;
    private ?string $db_user;
    private ?string $db_pass;

    public function __construct(
        $connection = null,
        $db_host = null,
        $db_name = null,
        $db_user = null,
        $db_pass = null
    ) {
        $this->db_host = $db_host ?? $_ENV['DB_HOST'] ?? null;
        $this->db_name = $db_name ?? $_ENV['DB_NAME'] ?? null;
        $this->db_user = $db_user ?? $_ENV['DB_USER'] ?? null;
        $this->db_pass = $db_pass ?? $_ENV['DB_PASS'] ?? null;
        try {
            $this->connection = new PDO("mysql:host=$this->db_host", $this->db_user, $this->db_pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    public function getConnection()
    {
        return $this->connection;
    }
    public function connect()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_user, $this->db_pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_PERSISTENT, true);
            return $this->connection;
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }
    public function firstInitialization()
    {
        try {
            $this->connection = new PDO("mysql:host=$this->db_host;dbname=$this->db_name", $this->db_user, $this->db_pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return true;
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), "Unknown database")) {
                $html = 'Database not found. Please run migrations to proceed. <a href="migrations/migration.php">Run migrations</a>';
                die($html);
            } else {
                die("Connection failed: " . $e->getMessage());
            }
        }
    }
    public function implementSchemaAndDatabase()
    {
        // $this->createDatabase();
        $this->connect();
        return $this->createTables();
    }

    private function createDatabase()
    {
        $sql = "CREATE DATABASE IF NOT EXISTS $this->db_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
        $result = $this->connection->exec($sql);
        if ($result === false) {
            $errorInfo = $this->connection->errorInfo();
            error_log("Error creating database: " . $errorInfo[2]);
            echo "Failed to create database.";
        }
    }
    private function createTables()
    {
        $table_array = ["agencies", "properties", "users", "admins", "managers", "agents", "properties_agents"];
        $success = true;
        foreach ($table_array as $tableName) {
            switch ($tableName) {
                case "agencies":
                    $sql = "
                CREATE TABLE IF NOT EXISTS $tableName (
                    agency_id INT PRIMARY KEY AUTO_INCREMENT,
                    name VARCHAR(255) NOT NULL,
                    address VARCHAR(255) NOT NULL,
                    city VARCHAR(255) NOT NULL,
                    zip VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    phone VARCHAR(255) NOT NULL,
                    is_displayed TINYINT DEFAULT 0,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                    break;
                case "properties":
                    $sql = "
                CREATE TABLE IF NOT EXISTS $tableName (
                    property_id INT PRIMARY KEY AUTO_INCREMENT, 
                    agency_id INT NOT NULL,
                    reference VARCHAR(255) NOT NULL,
                    address VARCHAR(255) DEFAULT NULL,
                    city VARCHAR(255) NOT NULL,
                    zip VARCHAR(255) NOT NULL,
                    offer VARCHAR(255) NOT NULL,
                    type VARCHAR(255) NOT NULL,
                    title VARCHAR(255) NOT NULL,
                    description TEXT NOT NULL,
                    price INT UNSIGNED NOT NULL,
                    int_surface INT NOT NULL,
                    ext_surface INT NOT NULL,
                    rooms INT NOT NULL,
                    rented TINYINT DEFAULT 0,
                    rented_for INT DEFAULT NULL,
                    rented_at TIMESTAMP DEFAULT NULL,
                    sold TINYINT DEFAULT 0,
                    sold_for INT DEFAULT NULL,
                    sold_at TIMESTAMP DEFAULT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (agency_id) REFERENCES agencies(agency_id) ON DELETE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                    break;
                case "users":
                    $sql = "
                CREATE TABLE IF NOT EXISTS $tableName (
                    user_id INT PRIMARY KEY AUTO_INCREMENT, 
                    fname VARCHAR(255) NOT NULL,
                    lname VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    password VARCHAR(255) NOT NULL,
                    role TINYINT DEFAULT 3,
                    has_connected TINYINT DEFAULT 0,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                    break;
                case "admins":
                    $sql = "
                    CREATE TABLE IF NOT EXISTS $tableName (
                        admin_id INT PRIMARY KEY AUTO_INCREMENT, 
                        user_id INT NOT NULL,
                        role TINYINT DEFAULT 0,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                    break;
                case "managers":
                    $sql = "
                    CREATE TABLE IF NOT EXISTS $tableName (
                        manager_id INT PRIMARY KEY AUTO_INCREMENT, 
                        user_id INT NOT NULL,
                        agency_id INT NOT NULL,
                        role TINYINT DEFAULT 1,
                        has_pending_application TINYINT DEFAULT 1,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
                        FOREIGN KEY (agency_id) REFERENCES agencies(agency_id) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                    break;
                case "agents":
                    $sql = "
                    CREATE TABLE IF NOT EXISTS $tableName (
                        agent_id INT PRIMARY KEY AUTO_INCREMENT, 
                        user_id INT NOT NULL,
                        agency_id INT NOT NULL,
                        manager_id INT NOT NULL,
                        role TINYINT DEFAULT 2,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
                        FOREIGN KEY (agency_id) REFERENCES agencies(agency_id) ON DELETE CASCADE,
                        FOREIGN KEY (manager_id) REFERENCES managers(manager_id) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                    break;
                    // case "agencies_managers":
                    //     $sql = "
                    //     CREATE TABLE IF NOT EXISTS $tableName (
                    //         agency_manager_id INT PRIMARY KEY AUTO_INCREMENT,
                    //         agency_id INT NOT NULL,
                    //         manager_id INT NOT NULL,
                    //         created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    //         updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    //         FOREIGN KEY (agency_id) REFERENCES agencies(agency_id) ON DELETE CASCADE,
                    //         FOREIGN KEY (manager_id) REFERENCES managers(manager_id) ON DELETE CASCADE
                    //     ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                    //     break;
                case "properties_agents":
                    $sql = "
                    CREATE TABLE IF NOT EXISTS $tableName (
                        property_agent_id INT PRIMARY KEY AUTO_INCREMENT,
                        property_id INT NOT NULL,
                        agent_id INT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (property_id) REFERENCES properties(property_id) ON DELETE CASCADE,
                        FOREIGN KEY (agent_id) REFERENCES agents(agent_id) ON DELETE CASCADE
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
                    break;
            }
            $result = $this->connection->exec($sql);
            if ($result === false) {
                $errorInfo = $this->connection->errorInfo();
                dd("Error creating table $tableName: " . $errorInfo[2]);
                $success = false;
            }
        }
        return $success;
    }
}
