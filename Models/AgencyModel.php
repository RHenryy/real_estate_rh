<?php
class Agency extends Database
{
    private string $table;
    public function __construct()
    {
        $this->table = 'agencies';
        parent::__construct();
    }
    public function fetch($agency_id): object|bool
    {
        $sql = "SELECT * FROM $this->table WHERE agency_id = :agency_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agency_id", $agency_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function fetchLimit(int $limit): array|bool
    {
        $sql = "SELECT * FROM $this->table WHERE is_displayed = 1 ORDER BY agency_id DESC LIMIT :limit";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function fetchAll(): array|bool
    {
        $sql = "SELECT * FROM $this->table WHERE is_displayed = 1 ORDER BY agency_id ASC";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function checkAgencyExists(array $data)
    {
        $sql = "SELECT agency_id FROM $this->table WHERE email = :agency_email OR phone = :agency_phone OR name = :agency_name";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agency_email", $data['agency_email'], PDO::PARAM_STR);
        $stmt->bindParam(":agency_phone", $data['agency_phone'], PDO::PARAM_STR);
        $stmt->bindParam(":agency_name", $data['agency_name'], PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function updateDisplay(int $agency_id): bool
    {
        // Get is_displayed current value 
        $sql = "SELECT is_displayed FROM $this->table WHERE agency_id = :agency_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agency_id", $agency_id, PDO::PARAM_INT);
        $stmt->execute();
        $is_displayed = (int)$stmt->fetch(PDO::FETCH_OBJ)->is_displayed;
        $is_displayed = $is_displayed === 1 ? 0 : 1;
        // Update is_displayed value
        $sql = "UPDATE $this->table SET is_displayed = :is_displayed WHERE agency_id = :agency_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agency_id", $agency_id, PDO::PARAM_INT);
        $stmt->bindParam(":is_displayed", $is_displayed, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function store(array $data): int|bool
    {
        // Get current auto_increment to get agency_id for agencies_manager table
        $sql = "SHOW TABLE STATUS LIKE ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$this->table]);
        $agency_id = (int)$stmt->fetch(PDO::FETCH_OBJ)->Auto_increment;

        $sql = "INSERT INTO $this->table (name, address, city, zip, email, phone) VALUES (:agency_name, :agency_address, :agency_city, :agency_zip, :agency_email, :agency_phone)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agency_name", $data['agency_name'], PDO::PARAM_STR);
        $stmt->bindParam(":agency_address", $data['agency_address'], PDO::PARAM_STR);
        $stmt->bindParam(":agency_city", $data['agency_city'], PDO::PARAM_STR);
        $stmt->bindParam(":agency_zip", $data['agency_zipcode'], PDO::PARAM_STR);
        $stmt->bindParam(":agency_email", $data['agency_email'], PDO::PARAM_STR);
        $stmt->bindParam(":agency_phone", $data['agency_phone'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return $agency_id;
        } else return false;
    }
    public function update(array $data): bool
    {
        $sql = "UPDATE $this->table SET name = :agency_name, address = :agency_address, city = :agency_city, zip = :agency_zipcode, phone = :agency_phone, email = :agency_email WHERE agency_id = :agency_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agency_id", $data['agency_id'], PDO::PARAM_INT);
        $stmt->bindParam(":agency_name", $data['agency_name'], PDO::PARAM_STR);
        $stmt->bindParam(":agency_address", $data['agency_address'], PDO::PARAM_STR);
        $stmt->bindParam(":agency_city", $data['agency_city'], PDO::PARAM_STR);
        $stmt->bindParam(":agency_zipcode", $data['agency_zipcode'], PDO::PARAM_STR);
        $stmt->bindParam(":agency_phone", $data['agency_phone'], PDO::PARAM_STR);
        $stmt->bindParam(":agency_email", $data['agency_email'], PDO::PARAM_STR);
        return $stmt->execute();
    }
    public function delete(int $agency_id): bool
    {
        $sql = "DELETE FROM $this->table WHERE agency_id = :agency_id";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(":agency_id", $agency_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
