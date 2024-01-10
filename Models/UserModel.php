<?php
class User extends Database
{
    private string $table;
    public function __construct()
    {
        $this->table = 'users';
        parent::__construct();
    }
    public function fetch(int $user_id): object|bool
    {
        $sql = "SELECT * FROM $this->table WHERE user_id = :user_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function fetchAll(int $user_id): array|bool
    {
        $sql = "SELECT DISTINCT $this->table.*, managers_agencies.agency_id AS manager_agency_id, agents_agencies.agency_id AS agent_agency_id
                FROM $this->table 
                LEFT JOIN managers ON managers.user_id = $this->table.user_id
                LEFT JOIN agents ON agents.user_id = $this->table.user_id
                LEFT JOIN agencies as agents_agencies ON agents.agency_id = agents_agencies.agency_id
                LEFT JOIN agencies as managers_agencies ON managers.agency_id = managers_agencies.agency_id
                
                WHERE $this->table.user_id != :user_id 
                ORDER BY $this->table.ROLE ASC";

        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public function getAgencyId(int $user_id): int|bool
    {
        // COALESCE returns first non-null result in list (in case manager or agent is null)
        $sql = "SELECT COALESCE(managers.agency_id, agents.agency_id) AS agency_id FROM $this->table 
        LEFT JOIN managers ON managers.user_id = users.user_id
        LEFT JOIN agents ON agents.user_id = users.user_id 
        WHERE users.user_id = :user_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ)->agency_id;
    }
    public function fetchAgents(int $user_id): array|bool
    {
        $sql = "SELECT * FROM $this->table 
        INNER JOIN agents ON agents.user_id = users.user_id
        WHERE agents.user_id = :user_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function fetchUserId(string $email): int|bool
    {
        $sql = "SELECT user_id FROM $this->table WHERE email = :email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ)->user_id;
    }

    public function store(array $data): bool
    {
        $sql = "INSERT INTO $this->table (fname, lname, email, password, role) VALUES (:fname, :lname, :email, :password, :role)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":fname", $data['fname'], PDO::PARAM_STR);
        $stmt->bindParam(":lname", $data['lname'], PDO::PARAM_STR);
        $stmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(":password", $data['password'], PDO::PARAM_STR);
        $stmt->bindParam(":role", $data['role'], PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function checkEmailExists(string $email): bool
    {
        $sql = "SELECT user_id FROM $this->table WHERE email = :email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function checkEmailExistsForUpdate(string $email, int $user_id): bool
    {
        $sql = "SELECT user_id FROM $this->table WHERE email = :email AND user_id != :user_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function login(array $data): object|bool
    {
        $sql = "SELECT * FROM $this->table WHERE email = :email";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        } else {
            return false;
        }
    }
    public function getSoleUser(): object|bool
    {
        $sql = "SELECT * FROM $this->table WHERE role = 0";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_OBJ);
        } else {
            return false;
        }
    }
    public function update(array $data): bool
    {
        $sql = "UPDATE $this->table SET fname = :fname, lname = :lname, email = :email WHERE user_id = :user_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":fname", $data['fname'], PDO::PARAM_STR);
        $stmt->bindParam(":lname", $data['lname'], PDO::PARAM_STR);
        $stmt->bindParam(":email", $data['email'], PDO::PARAM_STR);
        $stmt->bindParam(":user_id", $data['user_id'], PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function updateRole(int $role, int $user_id): bool
    {
        $sql = "UPDATE $this->table SET role = :role WHERE user_id = :user_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":role", $role, PDO::PARAM_INT);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function updateHasConnected(int $user_id): bool
    {
        $sql = "UPDATE $this->table SET has_connected = 1 WHERE user_id = :user_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function delete(int $user_id): bool
    {
        $sql = "DELETE FROM $this->table WHERE user_id = :user_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
