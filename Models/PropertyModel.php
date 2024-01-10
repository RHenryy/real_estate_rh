<?php
class Property extends Database
{
    private string $table;
    public function __construct()
    {
        $this->table = 'properties';
        parent::__construct();
    }
    public function fetch(int $property_id): object|bool
    {
        $sql = "SELECT * FROM $this->table WHERE property_id = :property_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":property_id", $property_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function fetchAll(): array|bool
    {
        $sql = "SELECT * FROM $this->table ORDER BY created_at DESC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function fetchLimit(int $limit): array|bool
    {
        $sql = "SELECT * FROM $this->table ORDER BY property_id DESC LIMIT :limit";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function fetchByAgencyId(int $agency_id): array|bool
    {
        $sql = "SELECT * FROM $this->table WHERE agency_id = :agency_id ORDER BY created_at DESC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agency_id", $agency_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function fetchByAgentId(int $agent_id): array|bool
    {
        $sql = "SELECT * FROM $this->table 
        JOIN properties_agents ON properties_agents.property_id = $this->table.property_id
        WHERE properties_agents.agent_id = :agent_id ORDER BY $this->table.created_at DESC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agent_id", $agent_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function checkPropertyExists(array $data): bool
    {
        $sql = "SELECT * FROM $this->table WHERE reference = :reference AND agency_id = :agency_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":reference", $data['property_reference'], PDO::PARAM_STR);
        $stmt->bindParam(":agency_id", $data['agency_id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function store(array $data): int|bool
    {
        // get next auto increment value
        $sql = "SHOW TABLE STATUS LIKE ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$this->table]);
        $property_id = (int)$stmt->fetch(PDO::FETCH_OBJ)->Auto_increment;
        // Store property
        $sql = "INSERT INTO $this->table 
        (agency_id, reference, address, city, zip, offer, type, title, description, price, int_surface, ext_surface, rooms) 
        VALUES 
        (:agency_id, :reference, :street, :city, :zip_code, :offer, :type, :title, :description, :price, :int_surface, :ext_surface, :rooms)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agency_id", $data['agency_id'], PDO::PARAM_INT);
        $stmt->bindParam(":reference", $data['property_reference'], PDO::PARAM_STR);
        $stmt->bindParam(":street", $data['property_street'], PDO::PARAM_STR);
        $stmt->bindParam(":city", $data['property_city'], PDO::PARAM_STR);
        $stmt->bindParam(":zip_code", $data['property_zipcode'], PDO::PARAM_INT);
        $stmt->bindParam(":offer", $data['property_offer'], PDO::PARAM_STR);
        $stmt->bindParam(":type", $data['property_type'], PDO::PARAM_STR);
        $stmt->bindParam(":title", $data['property_title'], PDO::PARAM_STR);
        $stmt->bindParam(":description", $data['property_description'], PDO::PARAM_STR);
        $stmt->bindParam(":price", $data['property_price'], PDO::PARAM_INT);
        $stmt->bindParam(":int_surface", $data['property_surface_int'], PDO::PARAM_INT);
        $stmt->bindParam(":ext_surface", $data['property_surface_ext'], PDO::PARAM_INT);
        $stmt->bindParam(":rooms", $data['property_rooms'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            return $property_id;
        } else {
            return false;
        }
    }
    public function update(array $data): bool
    {
        $sql = "UPDATE $this->table 
        SET reference = :reference, address = :street, city = :city, zip = :zip_code, offer = :offer, type = :type, title = :title, description = :description, price = :price, int_surface = :int_surface, ext_surface = :ext_surface, rooms = :rooms
        WHERE property_id = :property_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":reference", $data['property_reference'], PDO::PARAM_STR);
        $stmt->bindParam(":street", $data['property_street'], PDO::PARAM_STR);
        $stmt->bindParam(":city", $data['property_city'], PDO::PARAM_STR);
        $stmt->bindParam(":zip_code", $data['property_zipcode'], PDO::PARAM_INT);
        $stmt->bindParam(":offer", $data['property_offer'], PDO::PARAM_STR);
        $stmt->bindParam(":type", $data['property_type'], PDO::PARAM_STR);
        $stmt->bindParam(":title", $data['property_title'], PDO::PARAM_STR);
        $stmt->bindParam(":description", $data['property_description'], PDO::PARAM_STR);
        $stmt->bindParam(":price", $data['property_price'], PDO::PARAM_INT);
        $stmt->bindParam(":int_surface", $data['property_surface_int'], PDO::PARAM_INT);
        $stmt->bindParam(":ext_surface", $data['property_surface_ext'], PDO::PARAM_INT);
        $stmt->bindParam(":rooms", $data['property_rooms'], PDO::PARAM_INT);
        $stmt->bindParam(":property_id", $data['property_id'], PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function destroy(int $property_id): bool
    {
        $sql = "DELETE FROM $this->table WHERE property_id = :property_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":property_id", $property_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
