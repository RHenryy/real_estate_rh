<?php
class Properties extends Render
{
    private object $model;
    private string $image_path;
    public function __construct()
    {
        $this->model = getInstance("Property");
        $this->image_path = "public/assets/images/properties/";
        parent::__construct();
    }
    public function index(): void
    {
        if (isAuthorized(null, ['manager', 'agent'])) redirect("myproperties");

        $properties = $this->model->fetchAll();
        $nb_properties = count($properties);
        $properties = getInstance("Home")->getPropertyCardInfo($properties, $this->image_path);
        $seo = [
            "title" => "Our $nb_properties properties | Real Estate RH",
            "description" => "Discover our $nb_properties properties available for rent or for sale.",
        ];
        $this->render("properties", ["properties" => $properties, "seo" => $seo]);
    }
    public function getImagePath(): string
    {
        return $this->image_path;
    }
    public function fetchByAgencyId(int $agency_id): void
    {
        if (is_numeric($agency_id)) {
            $agency_id = (int)$agency_id;
            $properties = $this->model->fetchByAgencyId($agency_id);
            $nb_properties = count($properties);
            $properties = getInstance("Home")->getPropertyCardInfo($properties, $this->image_path);
            $titleAgency = getInstance("Agencies")->fetch($agency_id)->name;
            $seo = [
                "title" => "Properties by $titleAgency | Real Estate RH",
                "description" => "Discover the $nb_properties properties available for rent or for sale by $titleAgency.",
            ];
            $this->render("properties", ["properties" => $properties, "titleAgency" => $titleAgency, "agency_id" => $agency_id, "seo" => $seo]);
        } else {
            redirect(404);
        }
    }
    public function store(): void
    {
        // Check if agent or manager actually belongs to target agency 
        if (!isAuthorized(null, ['manager', 'agent']) || $_SESSION['agency_id'] !== (int)$_POST['agency_id']) redirect(403);
        if ($_SESSION['agency_id'] !== (int)$_POST['agency_id']) redirect(403);

        // Verify inputs and sanitize data
        if (!empty($_POST) && checkIssetInputs([$_POST['property_reference'], $_POST['property_address'], $_POST['property_city'], $_POST['property_zipcode'], $_POST['property_price'], $_POST['property_offer'], $_POST['property_type'], $_POST['property_title'], $_POST['property_rooms'], $_POST['property_description'], $_POST['property_type'], $_POST['agency_id'], $_POST['property_surface_int'], $_POST['property_surface_ext'], $_POST['property_street'], $_FILES['property_image']]) && empty($_POST['confirm-property'])) {
            // Define $agent_id
            $agent_id = isset($_POST['agent_id']) ? (int)$_POST['agent_id'] : null;
            // Sanitize all $_POST data
            $data = sanitizeData($_POST);
            // Make sure property does not already exist
            if ($this->model->checkPropertyExists($data)) {
                $_SESSION['flash_message'] = ["type" => "danger", "message" => "This property already exists."];
                redirect("properties");
            }
            // Insert property, get its id, format and store images 
            $property_id = $this->model->store($data);
            if (is_numeric($property_id) && $property_id !== false) {
                $this->storePropertyImages($property_id);
                // Insert into junction table properties_agents if $agent_id
                if ($agent_id !== null) {
                    getInstance("PropertiesAgents")->store($property_id, $agent_id);
                }
                $_SESSION['flash_message'] = ["type" => "success", "message" => "Property successfully added."];
                redirect("properties");
            } else {
                $_SESSION['flash_message'] = ["type" => "danger", "message" => "Error adding property. Contact our support if the error persists."];
                redirect("properties");
            }
        } else {
            $_SESSION['flash_message'] = ["type" => "danger", "message" => "Please fill in all fields."];
            redirect("properties");
        }
    }
    public function show(int $id): void
    {
        if (!is_numeric($id)) redirect(404);
        $property = $this->model->fetch($id);
        if (($property)) {
            [$property, $image_src] = $this->getPropertyImages($property, $this->image_path);

            $this->render("property", ["property" => $property, "image_src" => $image_src]);
        } else {
            redirect(404);
        }
    }
    public function edit(int $property_id): void
    {
        if (!isAuthorized(null, ['manager', 'agent'])) redirect(403);
        // Property to edit
        $editProperty = $this->model->fetch($property_id);
        // Make sure that manager or agent belongs to property's agency
        if (isAuthorized(null, ['manager'])) {
            $manager_agency_id = getInstance("Manager")->fetch($_SESSION['user']->user_id);
            if ($manager_agency_id) $manager_agency_id = (int)$manager_agency_id->agency_id;
            if ((int)$editProperty->agency_id !== (int)$manager_agency_id) redirect(403);
        }
        if (isAuthorized(null, ['agent'])) {
            $agent_id = getInstance("Agents")->fetch($_SESSION['user']->user_id);
            if ($agent_id) $agent_id = (int)$agent_id->agent_id;
            $property_agent_id = getInstance("PropertiesAgents")->fetch($property_id, $agent_id);
            if ($property_agent_id) $property_agent_id = (int)$property_agent_id->agent_id;
            if ((int)$property_agent_id !== (int)$agent_id) redirect(403);
            $editProperty->agent_id = $agent_id;
        }
        // Get property images
        [$editProperty, $image_src] = $this->getPropertyImages($editProperty, $this->image_path);
        $this->render("editProperty", ["editProperty" => $editProperty, "image_src" => $image_src]);
    }
    public function deleteImg(string $id): void
    {
        if (!isAuthorized(null, ['manager', 'agent'])) redirect(403);
        [$property_id, $image_number] = explode("-", $id);
        // Case manager
        if (isAuthorized(null, ['manager'])) {
            $manager_agency_id = GetInstance("Manager")->fetch($_SESSION['user']->user_id)->agency_id;
            $property_agency_id = $this->model->fetch($property_id)->agency_id;
            if ((int)$manager_agency_id !== (int)$property_agency_id) redirect(403);
        }
        // Case agent
        if (isAuthorized(null, ['agent'])) {
            $agent_id = getInstance("Agent")->fetch($_SESSION['user']->user_id)->agent_id;
            $rowExists = getInstance("PropertiesAgents")->fetch($property_id, $agent_id);
            if (!$rowExists) redirect(403);
        }
        $property_image = "$this->image_path$property_id/property_" . $property_id . "_image_$image_number.webp";
        // get number of images
        if (file_exists($property_image)) unlink($property_image);
        $images = array_diff(scandir($this->image_path . $property_id), ['.', '..']);
        $image_count = count($images);
        for ($i = 1; $i <= $image_count; $i++) {
            if (!file_exists($this->image_path . $property_id . "/property_" . $property_id . "_image_$i.webp")) {
                rename($this->image_path . $property_id . "/property_" . $property_id . "_image_" . ($i + 1) . ".webp", $this->image_path . $property_id . "/property_" . $property_id . "_image_$i.webp");
            }
        }
        $_SESSION['flash_message'] = ["type" => "success", "message" => "Image successfully deleted."];
        redirect("properties/edit/$property_id");
    }
    public function myProperties(): void
    {
        if (!isAuthorized(null, ['manager', 'agent'])) redirect(403);
        if ($_SESSION['user']->role === 1) {
            $agency_id = getInstance("Manager")->fetch($_SESSION['user']->user_id)->agency_id;
            $agency_id = $agency_id !== false ? (int)$agency_id : $agency_id;
            $properties = $this->model->fetchByAgencyId($agency_id);
            $properties = getInstance("Home")->getPropertyCardInfo($properties, $this->image_path);

            $this->render("properties", ["properties" => $properties, "agency_id" => $agency_id]);
        } else {
            $agent_id = (int)getInstance("Agent")->fetch($_SESSION['user']->user_id)->agent_id;
            $agency_id = getInstance("Agent")->fetchAgent($agent_id)->agency_id;
            $agency_id = $agency_id !== false ? (int)$agency_id : $agency_id;
            $properties = $this->model->fetchByAgentId($agent_id);
            $properties = getInstance("Home")->getPropertyCardInfo($properties, $this->image_path);

            $this->render("properties", ["properties" => $properties, "agency_id" => $agency_id, "agent_id" => $agent_id]);
        }
    }
    public function update(int $property_id): void
    {
        if (!isAuthorized(null, ['manager', "agent"])) redirect(403);
        $property_agency_id = $this->model->fetch($property_id)->agency_id;
        // Make sure that manager belongs to property's agency or agent is assigned to property
        if ($property_agency_id === getInstance("Manager")->fetch($_SESSION['user']->user_id)->agency_id || getInstance("PropertiesAgents")->fetch($property_id, (int)getInstance("Agents")->fetch($_SESSION['user']->user_id)->agent_id)) {
            // Verify inputs and sanitize data
            if (!empty($_POST) && checkIssetInputs([$_POST['property_id'], $_POST['property_reference'], $_POST['property_address'], $_POST['property_city'], $_POST['property_zipcode'], $_POST['property_price'], $_POST['property_offer'], $_POST['property_type'], $_POST['property_title'], $_POST['property_rooms'], $_POST['property_description'], $_POST['property_type'], $_POST['agency_id'], $_POST['property_surface_int'], $_POST['property_surface_ext'], $_POST['property_street']])) {
                // Define $agent_id
                $agent_id = isset($_POST['agent_id']) ? (int)$_POST['agent_id'] : null;
                // Sanitize all $_POST data
                $data = sanitizeData($_POST);
                // Manage images 
                if (isset($_FILES) && !empty($_FILES)) {
                    $this->storeDynamicPropertyImages($property_id);
                }
                if ($this->model->update($data)) {
                    // Update junction table properties_agents if agent_id 
                    if ($agent_id !== null) {
                        getInstance("PropertiesAgents")->store($property_id, (int)$_POST['agent_id']);
                    }
                    $_SESSION['flash_message'] = ["type" => "success", "message" => "Property successfully updated."];
                    redirect("properties");
                }
            } else {
                $_SESSION['flash_message'] = ["type" => "danger", "message" => "Please fill in all fields."];
                redirect("properties");
            }
        } else {
            redirect(403);
        }
    }
    public function destroy(int $property_id): void
    {
        if (!isAuthorized(null, ['manager', 'agent'])) redirect(403);
        $property_agency_id = $this->model->fetch($property_id)->agency_id;
        // Case manager
        if (isAuthorized(null, ['manager'])) {
            $manager_agency_id = GetInstance("Manager")->fetch($_SESSION['user']->user_id)->agency_id;
            if ((int)$property_agency_id !== (int)$manager_agency_id) redirect(403);
        }
        // Case agent
        if (isAuthorized(null, ['agent'])) {
            $agent_id = getInstance("Agent")->fetch($_SESSION['user']->user_id)->agent_id;
            $rowExists = getInstance("PropertiesAgents")->fetch($property_id, $agent_id);
            if (!$rowExists) redirect(403);
        }
        // Remove dir with images 
        $this->slayDirectory($this->image_path . $property_id);
        if ($this->model->destroy($property_id)) {
            $_SESSION['flash_message'] = ["type" => "success", "message" => "Property successfully deleted."];
            redirect("properties");
        } else {
            $_SESSION['flash_message'] = ["type" => "danger", "message" => "Error deleting property."];
            redirect("properties");
        }
    }
    // Private functions
    private function storePropertyImages(int $property_id): void
    {
        $image_count = 1;
        foreach ($_FILES['property_image']['tmp_name'] as $key => $tmp_name) {
            if (!is_dir(dirname(__DIR__) . "/public/assets/images/properties/$property_id")) {
                mkdir(dirname(__DIR__) . "/public/assets/images/properties/$property_id");
            }
            $sourcePath = $tmp_name;
            $destinationPath = dirname(__DIR__) . "/public/assets/images/properties/$property_id/property_" . $property_id . "_image_$image_count.webp";
            formatImage($sourcePath, $destinationPath, 1200, 1200);
            $image_count++;
        }
    }
    public function slayDirectory(string $path): void
    {
        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            foreach ($files as $file) {
                unlink("$path/$file");
            }
            rmdir($path);
        }
    }
    private function storeDynamicPropertyImages(int $property_id): void
    {
        foreach ($_FILES as $input_name => $file) {
            if (isset($_FILES[$input_name]) && !empty($_FILES[$input_name]['tmp_name'])) {
                $image_number = explode("_", $input_name)[1];
                $sourcePath = $file['tmp_name'];
                $destinationPath = dirname(__DIR__) . "/public/assets/images/properties/$property_id/property_" . $property_id . "_image_$image_number.webp";
                formatImage($sourcePath, $destinationPath, 1200, 1200);
            }
        }
    }
    private function getPropertyImages(object $property, string $image_path): array
    {
        $image_src = [];
        $property_image = "$image_path$property->property_id/";
        // Get number of images
        if (is_dir($property_image)) {
            $images = scandir($property_image);
            $image_number = count(array_diff($images, ['.', '..']));
        } else {
            $image_number = 0;
        }
        for ($i = 1; $i <= $image_number; $i++) {
            $file_name = "property_$property->property_id" . "_image_$i.webp";
            if (file_exists($property_image . $file_name)) {
                $image_src[$i] = "/" . $property_image . $file_name;
            }
        }
        $property->offer = $property->offer === 'sale' ? 'For sale' : 'For rent';
        return [$property, $image_src];
    }
}
