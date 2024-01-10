<?php
class Errors extends Render
{
    public function __construct()
    {
        parent::__construct();
    }
    public function E404(): void
    {
        $seo = [
            "title" => "404 | Real Estate RH",
            "description" => "Page not found.",
        ];
        $this->render("404", ["seo" => $seo]);
    }
    public function E403(): void
    {
        $seo = [
            "title" => "403 | Real Estate RH",
            "description" => "Not authorized",
        ];
        $this->render("403", ["seo" => $seo]);
    }
}
