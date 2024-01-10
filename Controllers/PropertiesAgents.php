<?php

use eftec\bladeone\BladeOne;

class PropertiesAgents
{
    private object $model;
    private string $views;
    private string $cache;
    private object $blade;
    public function __construct()
    {
        $this->model = new PropertyAgent();
        $this->views = dirname(__DIR__) . $_ENV['VIEWS_PATH'];
        $this->cache = dirname(__DIR__) . $_ENV['CACHE_PATH'];
        $this->blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_DEBUG);
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
