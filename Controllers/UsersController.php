<?php

class Users extends Render
{
    private User $model;
    private string $image_path;

    public function __construct()
    {
        $this->model = getInstance("User");
        $this->image_path = "public/assets/images/avatars/";
        parent::__construct();
    }
    public function index(): void
    {
        if (!isset($_SESSION['user']) && !empty($_SESSION['user'])) redirect(404);
        // Check if user exists
        if (!$user = $this->model->fetch($_SESSION['user']->user_id)) redirect(404);
        $manager = getInstance("Manager")->fetch($_SESSION['user']->user_id);
        $user = $this->getUserImage($user, $this->image_path);
        $seo = [
            "title" => "Your profile | Real Estate RH",
            "description" => "Manage your profile and personal information.",
        ];
        $this->render("dashboard", ["user" => $user, "manager" => $manager, "seo" => $seo]);
    }
    private function filterUsers(array $users): array
    {
        $managers = [];
        $agents = [];
        $basic_users = [];
        foreach ($users as $user) {
            if ((int)$user->role === 1) {
                $managers[] = $user;
            } elseif ((int)$user->role === 2) {
                $agents[] = $user;
            } else {
                $basic_users[] = $user;
            }
        }
        return [$managers, $agents, $basic_users];
    }
    public function manageUsers(): void
    {
        // Case admin, manage all users
        if (isAuthorized()) {
            if ($users = $this->model->fetchAll($_SESSION['user']->user_id)) {
                [$managers, $agents, $basic_users] = $this->filterUsers($users);
                $this->getAllUsersImage($users, $this->image_path);
                $seo = [
                    "title" => "Manage users | Real Estate RH",
                    "description" => "Manage users and their information.",
                ];
                $this->render("users", ["managers" => $managers, "agents" => $agents, "users" => $basic_users, "seo" => $seo]);
            } else {
                $_SESSION['flash_message'] = ["message" => "No users to manage yet.", "type" => "danger"];
                redirect("dashboard");
            }
            // Case manager to manage agents
        } elseif (isAuthorized(null, ['manager'])) {
            $manager = getInstance("Manager")->fetch($_SESSION['user']->user_id);
            $properties = getInstance("Property")->fetchByAgencyId($manager->agency_id);
            $agents = getInstance("Agent")->fetchAll($manager->manager_id);
            $this->getAgentImagesAndProperties($properties, $agents, $this->image_path);
            $seo = [
                "title" => "Manage your agents | Real Estate RH",
                "description" => "Manage your agents and their properties.",
            ];
            $this->render("users", ["agents" => $agents, "properties" => $properties, "manager" => $manager, "seo" => $seo]);
        } else {
            redirect(403);
        }
    }
    public function assignAgent(): void
    {
        if (!isAuthorized(null, ['manager'])) redirect(403);
        if (empty($_POST['agent_property'][0])) {
            $_SESSION['flash_message'] = ["message" => "No property was selected.", "type" => "danger"];
            redirect("users");
        }
        if (!empty($_POST) && checkIssetInputs([$_POST['agent_id'], $_POST['manager_id'], $_POST['agent_property']])) {
            // Check if manager belongs to agency
            if ((int)getInstance("Manager")->fetch($_SESSION['user']->user_id)->manager_id === (int)$_POST['manager_id']) {
                foreach ($_POST['agent_property'] as $property_id) {
                    $data = [
                        "agent_id" => (int)$_POST['agent_id'],
                        "property_id" => (int)$property_id
                    ];
                    getInstance("PropertiesAgents")->store($data['property_id'], $data['agent_id']);
                }
                $_SESSION['flash_message'] = ["message" => "Agent successfully assigned.", "type" => "success"];
                redirect("users");
            }
        } else redirect(403);
    }

    public function unassignAgent(string $agent_property_id): void
    {
        if (!isAuthorized(null, ['manager'])) redirect(403);
        [$agent_id, $property_id] = explode("-", $agent_property_id);
        if (is_numeric($agent_id) && is_numeric($property_id)) {
            // Check if manager belongs to agency
            if ((int)getInstance("Manager")->fetch($_SESSION['user']->user_id)->manager_id === (int)getInstance("Agent")->fetchAgent($agent_id)->manager_id) {
                if (getInstance("PropertiesAgents")->destroy($property_id, $agent_id)) {
                    $_SESSION['flash_message'] = ["message" => "Agent successfully unassigned.", "type" => "success"];
                    redirect("users");
                } else {
                    $_SESSION['flash_message'] = ["message" => "Error unassigning agent. Please try again later.", "type" => "danger"];
                    redirect("users");
                }
            } else redirect(403);
        } else {
            redirect(404);
        }
    }
    public function showApplications(): void
    {
        if (isAuthorized()) {
            if ($users = $this->getManagersWithPendingApplication()) {
                $this->getAllUsersImage($users, $this->image_path);
                $seo = [
                    "title" => "Manage applications | Real Estate RH",
                    "description" => "Manage user applications.",
                ];
                $this->render("applications", ["users" => $users, "seo" => $seo]);
            } else {
                $this->render("applications");
            }
        } else {
            redirect(403);
        }
    }
    public function store(): void
    {
        if (!empty($_POST) && checkIssetInputs([$_POST['fname'], $_POST['lname'], $_POST['register_email'], $_POST['register_password'], $_POST['confirm_password']]) && empty($_POST['confirm_email'])) {
            $fname = htmlspecialchars(trim($_POST['fname']));
            $lname = htmlspecialchars(trim($_POST['lname']));
            $email = htmlspecialchars(trim(filter_var($_POST['register_email'], FILTER_SANITIZE_EMAIL)));
            $password = $_POST['register_password'];
            $confirm_password = $_POST['confirm_password'];
            $role = isset($_POST['role']) ? $_POST['role'] : 3;
            // Check if there is any existing users : else make him admin
            $users = $this->model->fetchAll(0);
            if (empty($users)) {
                $role = 0;
            }
            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['email'] = $email;
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Check if email is already used
                if (!$this->model->checkEmailExists($email)) {
                    if ($password === $confirm_password) {
                        // Verify password strength (8 characters, 1 uppercase, 1 number, 1 special character)
                        if (verifyPassword($password)) {
                            $options = [
                                "cost" => 12,
                            ];
                            // Hash password
                            $hashedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
                            $data = [
                                "fname" => $fname,
                                "lname" => $lname,
                                "email" => $email,
                                "password" => $hashedPassword,
                                "role" => $role,
                            ];
                            if ($this->model->store($data)) {
                                if ($role === 0) {
                                    $user_id = $this->model->getSoleUser()->user_id;
                                    getInstance("Admin")->store($user_id);
                                }
                                $_SESSION["flash_message"] = ["message" => "Successfully registered.", "type" => "success"];
                                redirect("login");
                            } else {
                                $_SESSION["flash_message"] = ["message" => "Could not register. Please try again later.", "type" => "danger"];
                                redirect("register");
                            }
                        } else {
                            $_SESSION["flash_message"] = ["message" => "Password must contain at least 8 characters, 1 uppercase letter, 1 number and 1 special character", "type" => "danger"];
                            redirect("register");
                        }
                    } else {
                        $_SESSION["flash_message"] = ["message" => "Passwords do not match.", "type" => "danger"];
                        redirect("register");
                    }
                } else {
                    $_SESSION["flash_message"] = ["message" => "Email is already in use.", "type" => "danger"];
                    redirect("register");
                }
            } else {
                $_SESSION["flash_message"] = ["message" => "Please enter a valid email address.", "type" => "danger"];
                redirect("register");
            }
        } else {
            $_SESSION["flash_message"] = ["message" => "Please fill in all the fields.", "type" => "danger"];
            redirect("register");
        }
    }
    public function storeAgent(): void
    {
        if (!isAuthorized(null, ['manager'])) redirect(403);
        if (isset($_POST) && checkIssetInputs([
            $_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['password'], $_POST['confirm_password'], $_POST['agency_id'],
            $_POST['manager_id']
        ]) && empty($_POST['confirm_email'])) {
            $fname = htmlspecialchars(trim($_POST['fname']));
            $lname = htmlspecialchars(trim($_POST['lname']));
            $email = htmlspecialchars(trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)));
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            $role = 2;
            if (!$this->model->checkEmailExists($email)) {
                if ($password === $confirm_password) {
                    if (verifyPassword($password)) {
                        $options = [
                            "cost" => 12,
                        ];
                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT, $options);
                        $data = [
                            "fname" => $fname,
                            "lname" => $lname,
                            "email" => $email,
                            "password" => $hashedPassword,
                            "role" => $role,
                            "agency_id" => (int)$_POST['agency_id'],
                            "manager_id" => (int)$_POST['manager_id'],
                            "property_id" => (int)$_POST['agent_property'],
                        ];
                        if ($this->model->store($data)) {
                            $user_id = $this->model->fetchUserId($data['email']);
                            $data['user_id'] = $user_id;
                            if ($agent_id = getInstance("Agents")->store($data)) {
                                // Insert into junction table if agent assigned to a property when created
                                if (checkIssetInputs([$_POST['agent_property']])) {
                                    getInstance("PropertiesAgents")->store($data['property_id'], $agent_id);
                                }
                                $_SESSION['flash_message'] = ["message" => "Agent successfully created.", "type" => "success"];
                                redirect("users");
                            } else {
                                $_SESSION['flash_message'] = ["message" => "Error creating agent. Please try again later.", "type" => "danger"];
                            }
                        } else {
                            $_SESSION['flash_message'] = ["message" => "Agent could not be created. Please try again later.", "type" => "danger"];
                            redirect("users");
                        }
                    } else {
                        $_SESSION["flash_message"] = ["message" => "Password must contain at least 8 characters, 1 uppercase letter, 1 number and 1 special character", "type" => "danger"];
                        redirect("users");
                    }
                } else {
                    $_SESSION["flash_message"] = ["message" => "Passwords do not match.", "type" => "danger"];
                    redirect("users");
                }
            } else {
                $_SESSION["flash_message"] = ["message" => "Email is already in use.", "type" => "danger"];
                redirect("users");
            }
        } else {
            $_SESSION["flash_message"] = ["message" => "Please fill in all the fields.", "type" => "danger"];
            redirect("users");
        }
    }
    public function login(): void
    {
        if (checkIssetInputs([$_POST['login_email'], $_POST['login_password']]) && empty($_POST['confirm_email'])) {
            $email = htmlspecialchars(trim(filter_var($_POST['login_email'], FILTER_SANITIZE_EMAIL)));
            $password = $_POST['login_password'];
            $_SESSION['email'] = $email;
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data = [
                    "email" => $email,
                ];
                $user = $this->model->login($data);
                if ($user && password_verify($password, $user->password)) {
                    $_SESSION['user'] = $user;
                    $_SESSION['user']->role = (int)$_SESSION['user']->role;
                    $_SESSION['user']->user_id = (int)$_SESSION['user']->user_id;
                    $_SESSION['user']->has_connected = (int)$_SESSION['user']->has_connected;
                    // Get number of unseen applications
                    if ($_SESSION['user']->role === 0) {
                        $nb_applications = count($this->getManagersWithPendingApplication());
                        if ($nb_applications > 0) {
                            $_SESSION['pending_applications'] = $nb_applications;
                        }
                    }
                    if ($_SESSION['user']->role === 1) {
                        $agency_id = getInstance("Manager")->fetch($_SESSION['user']->user_id)->agency_id;
                        if ($agency_id !== false && $agency_id !== 0) {
                            $_SESSION['agency_id'] = (int)$agency_id;
                        }
                    }
                    if ($_SESSION['user']->role === 2) {
                        $agency_id = getInstance("Agent")->fetch($_SESSION['user']->user_id)->agency_id;
                        if ($agency_id !== false && $agency_id !== 0) {
                            $_SESSION['agency_id'] = (int)$agency_id;
                        }
                    }
                    if ((int)$user->has_connected === 0) {
                        $_SESSION['flash_message'] = ["message" => "Welcome, " . $user->fname . "!", "type" => "success"];
                        if ($this->model->updateHasConnected($user->user_id)) {
                            $_SESSION['user']->has_connected = 1;
                        }
                    } else {
                        $_SESSION["flash_message"] = ["message" => "Welcome back, " . $user->fname . "!", "type" => "success"];
                    }
                    redirect("dashboard");
                } else {
                    $_SESSION["flash_message"] = ["message" => "Email or password is incorrect.", "type" => "danger"];
                    sleep(2);
                    redirect("login");
                }
            } else {
                $_SESSION["flash_message"] = ["message" => "Please enter a valid email address.", "type" => "danger"];
                redirect("login");
            }
        } else {
            $_SESSION["flash_message"] = ["message" => "Please fill in all the fields.", "type" => "danger"];
            redirect("login");
        }
    }
    public function show(int $user_id): void
    {
        if (!is_numeric($user_id)) redirect(404);
        if (!isAuthorized($user_id, ['admin'])) redirect(403);
        $user = $this->model->fetch($user_id);

        $this->render("dashboard", ["user" => $user]);
    }
    public function getAgencyId($user_id): int|bool
    {
        if (is_numeric($user_id)) {
            return $this->model->getAgencyId($user_id);
        } else {
            return false;
        }
    }
    private function storeUserAvatar(int $user_id): void
    {
        if (isset($_FILES['update_image']) && !empty($_FILES['update_image']['tmp_name'])) {
            $sourcePath = $_FILES['update_image']['tmp_name'];
            $destinationPath = __DIR__ . "/../public/assets/images/avatars/user_image_$user_id.webp";
            formatImage($sourcePath, $destinationPath, 250, 200);
        }
    }
    public function update(int $user_id): void
    {
        if (!is_numeric($user_id)) redirect(404);
        if (!isAuthorized($user_id)) redirect(403);
        if (!empty($_POST) && checkIssetInputs([$_POST['update_fname'], $_POST['update_lname'], $_POST['update_email']])) {
            // Manage user image
            $this->storeUserAvatar($user_id);
            $fname = htmlspecialchars(trim($_POST['update_fname']));
            $lname = htmlspecialchars(trim($_POST['update_lname']));
            $email = htmlspecialchars(trim(filter_var($_POST['update_email'], FILTER_SANITIZE_EMAIL)));
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                if (!$this->model->checkEmailExistsForUpdate($email, $user_id)) {
                    $data = [
                        "fname" => $fname,
                        "lname" => $lname,
                        "email" => $email,
                        "user_id" => (int)$user_id,
                    ];
                    if ($this->model->update($data)) {
                        if (str_contains($_SERVER['HTTP_REFERER'], "/users") && !str_contains($_SERVER['HTTP_REFERER'], "/users/")) {
                            $_SESSION["flash_message"] = ["message" => "User successfully updated.", "type" => "success"];
                            redirect("users");
                        } else {
                            $_SESSION["flash_message"] = ["message" => "Profile successfully updated.", "type" => "success"];
                            if ($user_id === $_SESSION['user']->user_id) {
                                $_SESSION['user']->fname = $fname;
                                $_SESSION['user']->lname = $lname;
                                $_SESSION['user']->email = $email;
                            }
                            redirect("dashboard");
                        }
                    } else {
                        $_SESSION["flash_message"] = ["message" => "User could not be updated. Please try again later.", "type" => "danger"];
                        redirect("dashboard");
                    }
                } else {
                    $_SESSION["flash_message"] = ["message" => "Email is already in use.", "type" => "danger"];
                    redirect("dashboard");
                }
            } else {
                $_SESSION["flash_message"] = ["message" => "Please enter a valid email address.", "type" => "danger"];
                redirect("dashboard");
            }
        } else {
            $_SESSION["flash_message"] = ["message" => "Please fill in all the fields.", "type" => "danger"];
            redirect("dashboard");
        }
    }
    public function updateRole(int $role, int $manager_id): bool
    {
        if (is_numeric($role) && is_numeric($manager_id)) {
            $user_id = getInstance("Manager")->fetch($manager_id)->user_id;
            return $this->model->updateRole($role, $user_id);
        } else {
            return false;
        }
    }
    public function destroy(int $user_id): void
    {
        if (!is_numeric($user_id)) redirect(404);
        // Case admin, can delete any user
        if (isAuthorized()) {
            if ($this->model->delete($user_id)) {
                if (file_exists(__DIR__ . "/../public/assets/images/avatars/user_image_$user_id.webp")) {
                    unlink(__DIR__ . "/../public/assets/images/avatars/user_image_$user_id.webp");
                }
                $_SESSION["flash_message"] = ["message" => "User successfully deleted.", "type" => "success"];
                redirect("users");
            } else {
                $_SESSION["flash_message"] = ["message" => "User could not be deleted. Please try again later.", "type" => "danger"];
                redirect("users");
            }
            // Case manager, can only delete agents assigned to his agency
        } elseif (isAuthorized(null, ['manager'])) {
            $agent_agency_id = getInstance("Agent")->fetch($user_id)->agency_id;
            $manager_agency_id = getInstance("Manager")->fetch($_SESSION['user']->user_id)->agency_id;
            if ((int)$agent_agency_id === (int)$manager_agency_id) {
                if ($this->model->delete($user_id)) {
                    if (file_exists(__DIR__ . "/../public/assets/images/avatars/user_image_$user_id.webp")) {
                        unlink(__DIR__ . "/../public/assets/images/avatars/user_image_$user_id.webp");
                    }
                    $_SESSION["flash_message"] = ["message" => "Agent successfully deleted.", "type" => "success"];
                    redirect("users");
                } else {
                    $_SESSION["flash_message"] = ["message" => "Agent could not be deleted. Please try again later.", "type" => "danger"];
                    redirect("users");
                }
            } else {
                redirect(403);
            }
        } else {
            redirect(403);
        }
    }
    private function getManagersWithPendingApplication(): array|bool
    {
        return getInstance("Manager")->fetchManagersWithPendingApplication();
    }
    private function getUserImage(object $user, string $image_path): object
    {
        $file_name = "user_image_$user->user_id.webp";
        if (file_exists($image_path . $file_name)) {
            $user->image = $image_path . $file_name . '?' . rand(1, 1000);
        } else {
            $user->image = $image_path . 'avatar_placeholder.jpg';
        }
        return $user;
    }
    private function getAllUsersImage(array $users, string $image_path): array
    {
        foreach ($users as $user) {
            if (isset($user->agency_id)) {
                $user->agency = getInstance("Agency")->fetch($user->agency_id);
            } elseif (isset($user->manager_agency_id)) {
                $user->agency = getInstance("Agency")->fetch($user->manager_agency_id);
                $user->agency_id = $user->manager_agency_id;
            } elseif (isset($user->agent_agency_id)) {
                $user->agency = getInstance("Agency")->fetch($user->agent_agency_id);
                $user->agency_id = $user->agent_agency_id;
            }
        }
        foreach ($users as $user) {
            $user = $this->getUserImage($user, $image_path);
        }
        return $users;
    }
    private function getAgentImagesAndProperties(array $properties, array $agents, string $image_path): array
    {
        foreach ($agents as $agent) {
            if (file_exists($image_path . "user_image_" . $agent->user_id . ".webp")) {
                $agent->image = $image_path . "user_image_" . $agent->user_id . ".webp";
            } else {
                $agent->image = $image_path . "avatar_placeholder.jpg";
            }
            foreach ($properties as $property) {
                if (!getInstance("PropertyAgent")->fetch($property->property_id, $agent->agent_id)) {
                    $agent->assignableProperties[] = $property;
                }
            }
        }
        return $agents;
    }
}
