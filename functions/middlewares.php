<?php

/**
 * Absolute header location
 * @param    mixed $path The url to redirect to
 * @return   void
 */
function redirect(string|int $path): void
{
    $base_url = "https://real-estate-rh.alwaysdata.net";
    header("Location: $base_url/$path");
    exit();
}
/**
 * Check user role and authorization based on the array given and redirects if necessary
 * @param    int $id The user id 
 * @param    array $authorizedRoles The authorized roles
 * @return   bool
 */
function isAuthorized(int $id = null, array $authorizedRoles = ["admin"]): bool
{
    if (!isset($_SESSION['user'])) {
        return false;
    }
    switch ((int)$_SESSION['user']->role) {
        case 0:
            $user_role = "admin";
            break;
        case 1:
            $user_role = "manager";
            break;
        case 2:
            $user_role = "agent";
            break;
        case 3:
            $user_role = "user";
            break;
    }
    if ($id === (int)$_SESSION['user']->user_id) {
        return true;
    }
    if (!in_array($user_role, $authorizedRoles)) {
        return false;
    }
    return true;
}
/**
 * Verify password for special chars, capitals and numbers
 * @param    string  $password The password
 * @return   bool
 */
function verifyPassword($password)
{
    $uppercaseRegex = '/[A-Z]/';
    $numberRegex = '/[0-9]/';
    $specialCharRegex = '/[!@#$%^&*()_+\-=\[\]{};\\:\'"\\|,.<>\/?]/';

    $hasUppercase = preg_match($uppercaseRegex, $password);
    $hasNumber = preg_match($numberRegex, $password);
    $hasSpecialChar = preg_match($specialCharRegex, $password);

    return $hasUppercase && $hasNumber && $hasSpecialChar;
}
/**
 * Check if required inputs are set and not empty
 * @param    array  $inputs The form inputs
 * @return   bool
 */
function checkIssetInputs(array $inputs): bool
{
    foreach ($inputs as $input) {
        $input = is_numeric($input) ? (int)$input : $input;
        if (!isset($input) || (empty($input) && $input !== 0)) {
            return false;
        }
    }
    return true;
}
function sanitizeData(array $inputs): array
{
    $data = [];
    foreach ($inputs as $index => $value) {
        if (is_numeric($value) && stripos($index, "zipcode") !== false && stripos($index, "reference") && stripos($index, "phone")) {
            $data[$index] = (int)$value;
        } elseif (stripos($index, "email") !== false) {
            $data[$index] = htmlspecialchars(trim(filter_var($value, FILTER_SANITIZE_EMAIL)));
        } else {
            $data[$index] = htmlspecialchars(trim($value));
        }
    }
    return $data;
}
