<?php

class Managers
{
    private Manager $model;
    public function __construct()
    {
        $this->model = getInstance("Manager");
    }
    public function acceptApplication(int $manager_id): void
    {
        if (!is_numeric($manager_id)) redirect(404);
        if (!isAuthorized()) redirect(403);
        $ids = $this->model->updateApplication($manager_id);
        $agency_id = $ids->agency_id;
        $user_id = $ids->user_id;
        getInstance("Users")->updateRole(1, $user_id);
        if (getInstance("Agencies")->updateDisplay($agency_id)) {
            // Manage applications notifications
            if (isset($_SESSION['pending_applications'])) {
                $_SESSION['pending_applications'] = $_SESSION['pending_applications'] - 1;
                if ($_SESSION['pending_applications'] <= 0) unset($_SESSION['pending_applications']);
            };
            $_SESSION['flash_message'] = ["type" => "success", "message" => "Application successfully updated."];
            redirect("applications");
        } else {
            $_SESSION['flash_message'] = ["type" => "danger", "message" => "Could not update agency table"];
            redirect("applications");
        }
    }
    public function cancelSubscription(int $agency_id): void
    {
        if (!is_numeric($agency_id)) redirect(404);
        if (!isAuthorized(null, ["manager"]) && $_SESSION['agency_id'] !== (int)$agency_id) redirect(403);
        if (getInstance("Agency")->delete($agency_id)) {
            getInstance("User")->updateRole(3, $_SESSION['user']->user_id);
            $_SESSION['user']->role = 3;
            $_SESSION['flash_message'] = ["message" => "Subscription cancelled successfully.", "type" => "success"];
            redirect("myagency");
        } else {
            $_SESSION['flash_message'] = ["message" => "Error cancelling subscription. Please try again later or contact our support team.", "type" => "danger"];
            redirect("myagency");
        }
    }
    public function store(int $agency_id): bool
    {
        $data = [
            "user_id" => $_SESSION['user']->user_id,
            "agency_id" => (int)$agency_id,
        ];
        if (!$this->model->checkManagerExists($data)) {
            return $this->model->store($data);
        } else {
            $_SESSION['flash_message'] = ["type" => "danger", "message" => "You already applied as a manager."];
            return false;
        }
    }
}
