<?php
class Home extends Render
{
    private string $agency_image_path = "public/assets/images/agencies/";
    private string $property_image_path = "public/assets/images/properties/";
    public function __construct()
    {
        parent::__construct();
    }
    public function index(): void
    {
        $agencies = getInstance("Agency")->fetchLimit(3);
        $agencies = $this->getAgencyCardInfo($agencies, $this->agency_image_path);

        $properties = getInstance("Property")->fetchLimit(3);
        $properties = $this->getPropertyCardInfo($properties, $this->property_image_path);

        $seo = [
            "title" => "Real Estate RH",
            "description" => "Discover the best properties in the country at the best price. The biggest network of real estate agencies in the country since 1999.",
        ];
        $this->render("home", ["agencies" => $agencies, "properties" => $properties, "seo" => $seo]);
    }
    public function getAgencyCardInfo(array $agencies, string $image_path): array
    {
        foreach ($agencies as $agency) {
            $file_name = "agency_image_$agency->agency_id.webp";
            if (file_exists($image_path . $file_name)) {
                $agency->image = "/" . $image_path . $file_name;
            } else {
                $agency->image = '';
            }
        }
        return $agencies;
    }
    public function getPropertyCardInfo(array $properties, string $image_path): array
    {
        foreach ($properties as $property) {
            $file_name = "$property->property_id/property_$property->property_id" . "_image_1.webp";
            if (file_exists($image_path . $file_name)) {
                $property->image = "/" . $image_path . $file_name;
            } else {
                $property->image = '';
            }
            // Format offer
            $property->offer = $property->offer === 'sale' ? 'For sale' : 'For rent';
            $property->agency_name = getInstance("Agencies")->fetch($property->agency_id)->name;
        }
        return $properties;
    }
}
