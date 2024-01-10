<?php
class Admin extends Database
{
    private string $table;
    private string $userTable;
    public function __construct()
    {
        $this->table = 'admins';
        $this->userTable = 'users';
        parent::__construct();
    }
    public function store(int $user_id): bool
    {
        $sql = "INSERT INTO $this->table (user_id) VALUES (:user_id)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
