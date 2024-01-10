<?php
class Agent extends Database
{
    private string $table;
    public function __construct()
    {
        $this->table = 'agents';
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
    }
    public function fetchAgent(int $agent_id): object|bool
    {
        $sql = "SELECT * FROM $this->table WHERE agent_id = :agent_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agent_id", $agent_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function fetchAll(int $manager_id): array|bool
    {
        // Get all manager's agents
        $sql = "SELECT * FROM $this->table WHERE manager_id = :manager_id ORDER BY ROLE ASC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":manager_id", $manager_id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_OBJ);
        // Get user, properties, manager and agency info for agents
        foreach ($data as $agent) {
            $sql = "SELECT * FROM users WHERE user_id = :user_id";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(":user_id", $agent->user_id, PDO::PARAM_INT);
            $stmt->execute();
            $agent->user = $stmt->fetch(PDO::FETCH_OBJ);
        }
        foreach ($data as $agent) {
            $sql = "SELECT * FROM properties
            INNER JOIN properties_agents on properties_agents.property_id = properties.property_id
            WHERE agent_id = :agent_id ORDER BY properties.property_id ASC";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(":agent_id", $agent->agent_id, PDO::PARAM_INT);
            $stmt->execute();
            $agent->properties = $stmt->fetchAll(PDO::FETCH_OBJ);
        }
        return $data;
    }
    public function checkAgentExists(array $data): bool
    {
        $sql = "SELECT user_id FROM $this->table WHERE user_id = :user_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $data['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function fetchAllAgentsByAgency(int $agency_id): array
    {
        $sql = "SELECT user_id FROM $this->table WHERE agency_id = :agency_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agency_id", $agency_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function store(array $data): int|bool
    {
        // Get current auto_increment to get agent_id for properties_agent table
        $sql = "SHOW TABLE STATUS LIKE ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$this->table]);
        $agent_id = (int)$stmt->fetch(PDO::FETCH_OBJ)->Auto_increment;

        $sql = "INSERT INTO $this->table (user_id, agency_id, manager_id) VALUES (:user_id, :agency_id, :manager_id)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $data['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(":agency_id", $data['agency_id'], PDO::PARAM_INT);
        $stmt->bindParam(":manager_id", $data['manager_id'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $agent_id;
        } else return false;
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
    public function delete(int $user_id): bool
    {
        $sql = "DELETE FROM $this->table WHERE user_id = :user_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
