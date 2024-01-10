<?php
class PropertiesAgents
{
    private object $model;
    public function __construct()
    {
        $this->model = getInstance("PropertyAgent");
    }
    public function fetch(int $property_id, int $agent_id): object|bool
    {
        return $this->model->fetch($property_id, $agent_id);
    }
    public function store(int $property_id, int $agent_id): bool
    {
        return $this->model->store($property_id, $agent_id);
    }
    public function destroy(int $property_id, int $agent_id): bool
    {
        return $this->model->destroy($property_id, $agent_id);
    }
}
