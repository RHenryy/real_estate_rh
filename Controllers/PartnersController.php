<?php

class Partners extends Render
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(): void
    {
        // Check if user is connected
        if (!isset($_SESSION['user']) && empty($_SESSION['user'])) {
            $_SESSION['flash_message'] = ["type" => "danger", "message" => "You must be logged in to send an application."];
            redirect("login");
        }
        // check if user is manager and has already applied
        if (getInstance("Manager")->checkManagerExists(["user_id" => $_SESSION['user']->user_id])) {
            $_SESSION['flash_message'] = ["type" => "danger", "message" => "You already applied as a manager."];
            redirect("dashboard");
        }
        if (isAuthorized(null, ['admin', 'agent'])) redirect("dashboard");
        $seo = [
            "title" => "Become a partner | Real Estate RH",
            "description" => "Fill in the form and send us your application to join our network.",
        ];
        $this->render("partner", ["seo" => $seo]);
    }
    public function store(): void
    {
        $agency_id = getInstance("Agencies")->store();
        if (!$agency_id) redirect("partners");

        if (getInstance("Managers")->store($agency_id)) {
            $_SESSION['flash_message'] = ["type" => "success", "message" => "Your application has been sent."];
        } else {
            $_SESSION['flash_message'] = ["type" => "danger", "message" => "Error sending your application. Please try again later."];
        }
        redirect("dashboard");
    }
}
