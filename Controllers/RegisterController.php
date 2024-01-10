<?php
class Register extends Render
{

    public function __construct()
    {
        parent::__construct();
    }
    public function index(): void
    {
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
            redirect("dashboard");
        }
        $seo = [
            "title" => "Register | Real Estate RH",
            "description" => "Register to Real Estate RH",
        ];
        $this->render("register", ["seo" => $seo]);
    }
}
