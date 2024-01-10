<?php
// Display php errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/**
 * die(var_dump) like laravels keyword
 * @param    mixed $var The variable to dump
 * @return   void
 */
function dd($var): void
{
    echo "<pre>";
    var_dump($var);
    echo "</pre>";
    die();
}
/**
 * Requires the controllers in the dir Controllers
 * @return   void
 */
function requireControllers(): void
{
    $controllers = scandir("Controllers");
    foreach ($controllers as $controller) {
        if ($controller !== "." && $controller !== ".." && pathinfo($controller, PATHINFO_EXTENSION) === 'php') {
            require_once "Controllers/$controller";
        }
    }
}
/**
 * Requires the models in the dir Models (except Database.php)
 * @return   void
 */
function requireModels(): void
{
    $models = scandir("Models");
    foreach ($models as $model) {
        if ($model !== "." && $model !== ".." && pathinfo($model, PATHINFO_EXTENSION) === 'php') {
            if ($model !== "Database.php") {
                require_once "Models/$model";
            }
        }
    }
}
// /**
//  * Create instance of a class to directly use any of its methods on one line
//  * @param    string  $class The class to instantiate
//  * @return   object
//  */
// function getInstance(string $class = "Database"): ?object
// {
//     if (class_exists($class)) {
//         try {
//             return new $class();
//         } catch (Throwable $e) {
//             error_log("Error instantiating class $class: " . $e->getMessage());
//         }
//     }
//     return null;
// }
/**
 * Require file and create instance of a class to directly use any of its methods on one line
 * @param    string  $class The class to instantiate
 * @return   object
 */
function getInstance(string $class = "Database"): ?object
{
    switch ($class) {
        case "Database":
            break;
        case "Router":
            break;
        case "Render":
            break;
        default:
            $file = "Controllers/$class" . "Controller.php";
            if (!file_exists($file)) {
                $file = "Models/$class" . "Model.php";
            }
            require_once($file);
            break;
    }
    return new $class();
}
/**
 * Create inputs for forms
 * @param string $label        The label 
 * @param string $type         The type of the input
 * @param string $name         The name attribute 
 * @param string $placeholder  The placeholder 
 * @param mixed  $value        The default value 
 * @return array An array with form config
 */
function generateInput($label, $type, $name, $placeholder, $value = ''): array
{
    return [
        'label' => $label,
        'type' => $type,
        'name' => $name,
        'placeholder' => $placeholder,
        'value' => $value,
    ];
}
/**
 * Resize image and convert to webp
 * @param string $sourceImagePath Source image
 * @param string $destinationImagePath Path to save webp
 * @return bool 
 */
function formatImage($sourceImagePath, $destinationImagePath, $targetHeight, $targetWidth)
{
    $sourceImageInfo = getimagesize($sourceImagePath);
    if ($sourceImageInfo === false) {
        return false;
    }
    $sourceMimeType = $sourceImageInfo['mime'];
    if ($sourceMimeType === 'image/webp') {
        return copy($sourceImagePath, $destinationImagePath);
    }
    switch ($sourceMimeType) {
        case 'image/jpeg':
            $sourceImage = imagecreatefromjpeg($sourceImagePath);
            break;
        case 'image/png':
            $sourceImage = imagecreatefrompng($sourceImagePath);
            break;
        case 'image/gif':
            $sourceImage = imagecreatefromgif($sourceImagePath);
            break;
        default:
            return false;
    }
    $sourceWidth = imagesx($sourceImage);
    $sourceHeight = imagesy($sourceImage);
    // $targetWidth = ($sourceWidth / $sourceHeight) * $targetHeight;

    $targetImage = imagecreatetruecolor($targetWidth, $targetHeight);

    imagecopyresampled($targetImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $sourceWidth, $sourceHeight);

    $success = imagewebp($targetImage, $destinationImagePath);
    imagedestroy($sourceImage);
    imagedestroy($targetImage);
    return $success;
}
