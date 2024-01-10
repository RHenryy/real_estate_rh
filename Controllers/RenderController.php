<?php

use eftec\bladeone\BladeOne;

abstract class Render
{
    private string $views;
    private string $cache;
    private object $blade;
    public function __construct()
    {
        $this->views = dirname(__DIR__) . $_ENV['VIEWS_PATH'];
        $this->cache = dirname(__DIR__) . $_ENV['CACHE_PATH'];
        $this->blade = new BladeOne($this->views, $this->cache, BladeOne::MODE_DEBUG);
    }
    protected function render(string $view, array $data = []): void
    {
        echo $this->blade->run($view, $data);
    }
}
