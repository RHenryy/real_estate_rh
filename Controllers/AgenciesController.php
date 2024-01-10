<?php
class Agencies extends Render
{
    private Agency $model;
    private string $image_path;
    private array $extensions;
    public function __construct()
    {
        $this->model = getInstance("Agency");
        $this->image_path = "public/assets/images/agencies/agency_image_";
        $this->extensions = [".webp", ".jpg", ".jpeg", ".png"];
        parent::__construct();
    }
    public function index()
    {
        if (isAuthorized(null, ["manager", "agent"])) {
            redirect("myagency");
        }
        $agencies = $this->model->fetchAll();
        $nb_agencies = count($agencies);
        $this->getImages($agencies);
        $seo = [
            "title" => "Our $nb_agencies partner agencies | Real Estate RH",
            "description" => "Discover our $nb_agencies partner agencies and find the best properties offered by them.",
        ];
        $this->render("agencies", ["agencies" => $agencies, "seo" => $seo]);
    }
    public function myAgency(): void
    {
        if (!isAuthorized(null, ["manager", "agent"])) {
            redirect("agencies");
        }
        $agency_id = getInstance("Users")->getAgencyId($_SESSION['user']->user_id);
        if ($agency_id) {
            $agency = $this->model->fetch($agency_id);
            $this->getImages([$agency]);
            $properties = getInstance("Property")->fetchByAgencyId($agency->agency_id);
            $this->getNumberOfTypes($agency, $properties);
            $seo = [
                "title" => "Your agency | Real Estate RH",
                "description" => "Your agency page.",
            ];
            $this->render("agency", ["agency" => $agency, "seo" => $seo]);
        } else {
            redirect(404);
        }
    }
    public function fetch($agency_id)
    {
        if (is_numeric($agency_id)) {
            return $this->model->fetch($agency_id);
        } else {
            redirect(404);
        }
    }
    public function show($agency_id): void
    {
        if (is_numeric($agency_id)) {
            $agency = $this->model->fetch($agency_id);
            if ($agency) {
                $this->getImages([$agency]);
                if ((int)$agency->is_displayed === 0 && !isAuthorized()) redirect("agencies");
                $properties = getInstance("Property")->fetchByAgencyId($agency->agency_id);
                $this->getNumberOfTypes($agency, $properties);
                $seo = [
                    "title" => "$agency->name | Real Estate RH",
                    "description" => "Discover the best properties offered by $agency->name.",
                ];
                $this->render("agency", ["agency" => $agency, "seo" => $seo]);
            } else {
                redirect(404);
            }
        } else redirect(404);
    }
    // Backoffice methods - only for admins and/or managers 
    private function checkAgencyExists($data): bool
    {
        return $this->model->checkAgencyExists($data);
    }
    public function store(): int|bool
    {
        // Verify inputs and sanitize data
        if (!empty($_POST) && checkIssetInputs([$_POST['agency_name'], $_POST['agency_address'], $_POST['agency_city'], $_POST['agency_zipcode'], $_POST['agency_email'], $_POST['agency_phone'], $_FILES['agency_image']]) && empty($_POST['confirm_email'])) {
            $data = sanitizeData($_POST);
            // Verify if agency already exists
            if ($this->checkAgencyExists($data)) {
                $_SESSION['flash_message'] = ["type" => "danger", "message" => "This agency already exists."];
                return false;
            }
            // Store agency and get its id to rename and store the image and use it to reference manager agency_id in table managers
            $agency_id = $this->model->store($data);
            // Store, redimension and convert image to webp
            $this->storeAgencyImage($agency_id);
            $_SESSION['flash_message'] = ["type" => "success", "message" => "Agency created successfully."];
            return $agency_id;
        } else {
            // Save input data for user experience
            $_SESSION['flash_message'] = ["type" => "danger", "message" => "Please fill in all the fields."];
            $_SESSION['agency_name'] = $_POST['agency_name'];
            $_SESSION['agency_address'] = $_POST['agency_address'];
            $_SESSION['agency_city'] = $_POST['agency_city'];
            $_SESSION['agency_zipcode'] = $_POST['agency_zipcode'];
            $_SESSION['agency_email'] = $_POST['agency_email'];
            $_SESSION['agency_phone'] = $_POST['agency_phone'];
            return false;
        }
    }
    public function edit(int $agency_id): void
    {
        if (!is_numeric($agency_id)) redirect(404);
        // Make sure manager belongs to agency
        if (isAuthorized(null, ["manager"]) && $_SESSION['agency_id'] === (int)$agency_id) {
            $agency = $this->model->fetch($agency_id);
            if ($agency) {
                $seo = [
                    "title" => "Edit agency | Real Estate RH",
                    "description" => "Edit your agency information.",
                ];
                $this->render("editAgency", ["agency" => $agency, "seo" => $seo]);
            } else {
                redirect(404);
            }
        } else {
            redirect(403);
        }
    }
    public function update(int $agency_id): void
    {
        // Make sure manager belongs to agency
        if (!is_numeric($agency_id)) redirect(404);
        if (!isAuthorized(null, ["manager"]) && $_SESSION['agency_id'] !== (int)$agency_id) redirect(403);
        if (!empty($_POST) && checkIssetInputs([$_POST['agency_name'], $_POST['agency_address'], $_POST['agency_city'], $_POST['agency_zipcode'], $_POST['agency_email'], $_POST['agency_phone']])) {
            $data = sanitizeData($_POST);
            $data['agency_id'] = (int)$agency_id;
            if ($this->model->update($data)) {
                if (isset($_FILES['agency_image'])) {
                    // Store, redimension and convert image to webp
                    $this->storeAgencyImage($agency_id);
                }
                $_SESSION['flash_message'] = ["message" => "Agency updated successfully.", "type" => "success"];
                redirect("myagency");
            } else {
                $_SESSION['flash_message'] = ["message" => "Error updating agency. Please try again later or contact our support team.", "type" => "danger"];
                redirect("myagency");
            }
        } else {
            $_SESSION['flash_message'] = ["message" => "Please fill in all the fields.", "type" => "danger"];
            redirect("myagency");
        }
    }
    public function updateDisplay(int $agency_id): bool
    {
        return $this->model->updateDisplay($agency_id);
    }
    public function destroy(int $manager_id): void
    {
        if (!is_numeric($manager_id)) redirect(404);
        if (!isAuthorized()) redirect(403);
        $manager = getInstance("Manager")->fetchWithManagerId($manager_id);
        if (!$manager) redirect(404);
        $agency_id = $manager->agency_id;
        $user_id = $manager->user_id;
        $agentsToDelete = getInstance("Agent")->fetchAllAgentsByAgency($agency_id);
        foreach ($agentsToDelete as $agent) {
            getInstance("Users")->delete($agent->user_id);
        }
        getInstance("User")->delete($user_id);
        if ($this->model->delete($agency_id)) {
            // Delete agency image
            foreach ($this->extensions as $ext) {
                if (file_exists($this->image_path . $agency_id . $ext)) {
                    unlink($this->image_path . $agency_id . $ext);
                }
            }
            // Delete properties images and folder if any
            $properties = getInstance("Property")->fetchByAgencyId($agency_id);
            if ($properties) {
                foreach ($properties as $property) {
                    getInstance("Properties")->slayDirectory(getInstance("Properties")->getImagePath(), $property->id);
                }
            }
            // Delete user avatar
            if (file_exists("public/assets/images/avatars/user_image_$user_id.webp")) unlink("public/assets/images/avatars/user_image_$user_id.webp");
            // Manage applications notifications
            if (isset($_SESSION['pending_applications'])) {
                $_SESSION['pending_applications'] = $_SESSION['pending_applications'] - 1;
                if ($_SESSION['pending_applications'] === 0) unset($_SESSION['pending_applications']);
            };
            $_SESSION['flash_message'] = ["message" => "Application denied.", "type" => "danger"];
            redirect("applications");
        } else {
            $_SESSION['flash_message'] = ["message" => "Error deleting agency", "type" => "danger"];
            redirect("applications");
        }
    }
    // Private methods for convenience
    private function getImages(object|array $agencies): array
    {
        foreach ($agencies as $agency) {
            $file_name = "$agency->agency_id.webp";
            if (file_exists($this->image_path . $file_name)) {
                $agency->image = "/" . $this->image_path . $file_name;
            }
        }
        return $agencies;
    }
    private function storeAgencyImage(int $agency_id): void
    {
        if (isset($_FILES['agency_image']) && !empty($_FILES['agency_image']['tmp_name'])) {
            $sourcePath = $_FILES['agency_image']['tmp_name'];
            $destinationPath = dirname(__DIR__) . "/public/assets/images/agencies/agency_image_$agency_id.webp";
            formatImage($sourcePath, $destinationPath, 1200, 1200);
        }
    }
    private function getNumberOfTypes(object $agency, array $properties): object
    {
        $agency->nb_properties = count($properties);
        $agency->house_count = 0;
        $agency->appartment_count = 0;
        $agency->land_count = 0;
        foreach ($properties as $property) {
            if ($property->type === "house") {
                $agency->house_count++;
            } elseif ($property->type === "appartment" || $property->type === "studio") {
                $agency->appartment_count++;
            } elseif ($property->type === "land") {
                $agency->land_count++;
            }
        }
        return $agency;
    }
}
