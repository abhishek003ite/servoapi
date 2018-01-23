<?php

use Illuminate\Database\Seeder;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Service;

class ServicesSeeder extends Seeder
{
    public function run()
    {
        // Mapping of categories -> subcategories -> services in a single array

        $category_array = [
            
            //category
            'Home Services' => [
                
                // sub-category
                'Key Making' => [
                    //services
                    'Car Key',
                    'Door Key',
                    'Bike Key',
                    'Other key making'
                ],
                
                // sub-category
                'Carpenter' => [
                    //services
                    'Furniture repair/manufacturing',
                    'Modular kitchen',
                    'Door/gate repairing and manufacturing',
                    'Interior decoration',
                    'Antique wooden work',
                    'Other wooden work',
                ],

                //sub-category
                'Electrician' => [
                    //services
                    'AC/refrigerator repairing',
                    'Geyser repairing',
                    'Juicer/mixture repairing',
                    'Home Wiring',
                    'Other electrician service',
                ],

                //sub-category
                'Plumber' => [
                    'Tap Repair',
                    'Flush Repair',
                    'Leakage',
                    'Installation of Sanitaryware',
                    'Other plumber service',
                ]
            ],

            //category
            'Road Services' => [
                
                //sub-category
                'Crane/Towing' => [
                    
                    //services
                    'Hydraulic/Rope',
                    'Chain',
                    'Zero degree',
                    'Other towing service'
                ]
            ],

            //category
            'Office Services' => [

                //sub-category
                'Key Making' => [

                    //services
                    'Car Key',
                    'Door Key',
                    'Bike Key',
                    'Others key making',
                ],

                //sub-category
                'Carpenter' => [

                    //services
                    'Flooring',
                    'Furniture repair/manufacturing',
                    'Modular kitchen',
                    'Door/gate repairing and manufacturing',
                    'Interior decoration',
                    'Antique wooden work',
                    'Other wooden work',
                ],

                //sub-category
                'Electrician' => [

                    //services
                    'AC/refrigerator repairing',
                    'Geyser repairing',
                    'Juicer/mixer repairing',
                    'Home Wiring',
                    'Other electrician service',
                ],
            ]
        ];

        foreach ($category_array as $category_name => $sub_category_array) {
            $category = new Category();
            $category->name = $category_name;
            $category->save();

            foreach ($sub_category_array as $sub_category_name => $services_array) {
                $sub_category = new SubCategory();
                $sub_category->name = $sub_category_name;
                $sub_category->category_id = $category->id;
                $sub_category->save();

                $total_services = count($services_array);
                for ($i = 0; $i < $total_services; $i++) {
                    $service = new Service();
                    $service->name = $services_array[$i];
                    $service->sub_category_id = $sub_category->id;
                    $service->save();
                }
            }
        }
    }
}
