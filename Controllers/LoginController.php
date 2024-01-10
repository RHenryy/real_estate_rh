<?php
class Login extends Render
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index(): void
    {
        if (isset($_SESSION['user']) && !empty($_SESSION['user'])) redirect("dashboard");
        $seo = [
            "title" => "Login | Real Estate RH",
            "description" => "Login to your account and join our network.",
        ];
        $this->render("login", ["seo" => $seo]);
    }
}
