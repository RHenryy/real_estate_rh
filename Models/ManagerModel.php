<?php
class Manager extends Database
{
    private string $table;
    private string $userTable;
    public function __construct()
    {
        $this->table = 'managers';
        $this->userTable = 'users';
        parent::__construct();
    }
    public function fetch(int $user_id): object|bool
    {
        $sql = "SELECT * FROM $this->table WHERE user_id = :user_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
        // if (!$result) {
        //     return $this->fetchWithManagerId($user_id);
        // } else {
        //     return $result;
        // }
    }
    public function fetchWithManagerId(int $manager_id): object|bool
    {
        $sql = "SELECT * FROM $this->table WHERE manager_id = :manager_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":manager_id", $manager_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function fetchManagersWithPendingApplication(): array|bool
    {
        $sql = "SELECT * FROM $this->userTable
                INNER JOIN $this->table ON $this->table.user_id = $this->userTable.user_id
                WHERE $this->table.has_pending_application = 1";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function fetchAll(int $user_id): array|bool
    {
        $sql = "SELECT * FROM $this->table WHERE user_id != :user_id ORDER BY ROLE ASC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function checkManagerExists(array $data): bool
    {
        $sql = "SELECT user_id FROM $this->table WHERE user_id = :user_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $data['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function store(array $data): int|bool
    {
        $sql = "INSERT INTO $this->table (user_id, agency_id) VALUES (:user_id, :agency_id)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(":agency_id", $data['agency_id'], PDO::PARAM_INT);
        return $stmt->execute();
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
    public function updateApplication(int $manager_id): object|bool
    {
        $sql = "UPDATE $this->table SET has_pending_application = 0 WHERE manager_id = :manager_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":manager_id", $manager_id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            $sql = "SELECT agency_id, user_id FROM $this->table WHERE manager_id = :manager_id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(":manager_id", $manager_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } else return false;
    }
    public function delete(int $user_id): bool
    {
        $sql = "DELETE FROM $this->table WHERE user_id = :user_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
