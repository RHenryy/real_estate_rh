<?php
class PropertyAgent extends Database
{
    private string $table;
    public function __construct()
    {
        $this->table = 'properties_agents';
        parent::__construct();
    }
    public function fetch(int $property_id, int $agent_id): object|bool
    {
        $sql = "SELECT * FROM $this->table WHERE agent_id = :agent_id AND property_id = :property_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agent_id", $agent_id, PDO::PARAM_INT);
        $stmt->bindParam(":property_id", $property_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }
    public function store($property_id, $agent_id): bool
    {
        $sql = "INSERT INTO $this->table (property_id, agent_id) VALUES (:property_id, :agent_id)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":property_id", $property_id, PDO::PARAM_INT);
        if ($agent_id !== null) {
            $stmt->bindParam(":agent_id", $agent_id, PDO::PARAM_INT);
        } else {
            $stmt->bindParam(":agent_id", $agent_id, PDO::PARAM_NULL);
        }
        return $stmt->execute();
    }
    public function destroy(int $property_id, int $agent_id): bool
    {
        $sql = "DELETE FROM $this->table WHERE property_id = :property_id AND agent_id = :agent_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":property_id", $property_id, PDO::PARAM_INT);
        $stmt->bindParam(":agent_id", $agent_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
