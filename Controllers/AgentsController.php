<?php
class Agents
{
    private Agent $model;
    private string $image_path;
    public function __construct()
    {
        $this->model = getInstance("Agent");
        $this->image_path = "public/assets/images/avatars/user_image_";
    }
    public function fetch(int $user_id): object|bool
    {
        if (is_numeric($user_id) && isAuthorized(null, ['admin', 'manager', 'agent'])) {
            return $this->model->fetch($user_id);
        } else {
            return false;
        }
    }
    public function fetchAll(int $manager_id): array|bool
    {
        if (is_numeric($manager_id) && isAuthorized(null, ['manager'])) {
            return $this->model->fetchAll($manager_id);
        } else {
            return false;
        }
    }
    public function store(array $data): int|bool
    {
        if (!$this->checkAgentExists($data)) {
            return $this->model->store($data);
        } else {
            return false;
        }
    }
    private function checkAgentExists(array $data): bool
    {
        return $this->model->checkAgentExists($data);
    }
}
