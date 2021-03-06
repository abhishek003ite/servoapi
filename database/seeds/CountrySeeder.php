<?php

use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = [
            [ "display_name" => "Afganistan" ],
            [ "display_name" => "Albania" ],
            [ "display_name" => "Algeria" ],
            [ "display_name" => "American Samoa" ],
            [ "display_name" => "Andorra" ],
            [ "display_name" => "Angola" ],
            [ "display_name" => "Anguilla" ],
            [ "display_name" => "Antarctica" ],
            [ "display_name" => "Antigua and Barbuda" ],
            [ "display_name" => "Argentina" ],
            [ "display_name" => "Armenia" ],
            [ "display_name" => "Aruba" ],
            [ "display_name" => "Australia" ],
            [ "display_name" => "Austria" ],
            [ "display_name" => "Azarbaycan" ],
            [ "display_name" => "Bahamas" ],
            [ "display_name" => "Bahrain" ],
            [ "display_name" => "Bangladesh" ],
            [ "display_name" => "Barbados" ],
            [ "display_name" => "Belarus" ],
            [ "display_name" => "Belgium" ],
            [ "display_name" => "Belize" ],
            [ "display_name" => "Benin" ],
            [ "display_name" => "Bermuda" ],
            [ "display_name" => "Bhutan" ],
            [ "display_name" => "Bolivia" ],
            [ "display_name" => "Bosna i Hercegovina" ],
            [ "display_name" => "Botswana" ],
            [ "display_name" => "Brazil" ],
            [ "display_name" => "Brunei Darussalam" ],
            [ "display_name" => "Bulgaria" ],
            [ "display_name" => "Burkina Faso" ],
            [ "display_name" => "Burundi" ],
            [ "display_name" => "Cambodia" ],
            [ "display_name" => "Cameroon" ],
            [ "display_name" => "Canada" ],
            [ "display_name" => "Cape Verde" ],
            [ "display_name" => "Cayman Islands" ],
            [ "display_name" => "Central African Republic" ],
            [ "display_name" => "Chad" ],
            [ "display_name" => "Chile" ],
            [ "display_name" => "China" ],
            [ "display_name" => "Christmas Island" ],
            [ "display_name" => "Cocos Islands" ],
            [ "display_name" => "Colombia" ],
            [ "display_name" => "Comoros" ],
            [ "display_name" => "Cook Islands" ],
            [ "display_name" => "Costa Rica" ],
            [ "display_name" => "Croatia" ],
            [ "display_name" => "Cuba" ],
            [ "display_name" => "Curacao" ],
            [ "display_name" => "Cyprus" ],
            [ "display_name" => "Czech Republic" ],
            [ "display_name" => "Democratic Republic of the Congo" ],
            [ "display_name" => "Denmark" ],
            [ "display_name" => "Djibouti" ],
            [ "display_name" => "Dominica" ],
            [ "display_name" => "Dominican Republic" ],
            [ "display_name" => "East Timor" ],
            [ "display_name" => "Ecuador" ],
            [ "display_name" => "Egypt" ],
            [ "display_name" => "El Salvador" ],
            [ "display_name" => "Equatorial Guinea" ],
            [ "display_name" => "Eritrea" ],
            [ "display_name" => "Estonia" ],
            [ "display_name" => "Ethiopia" ],
            [ "display_name" => "Falkland Islands" ],
            [ "display_name" => "Faroe Islands" ],
            [ "display_name" => "Fiji" ],
            [ "display_name" => "Finland" ],
            [ "display_name" => "France" ],
            [ "display_name" => "French Polynesia" ],
            [ "display_name" => "Gabon" ],
            [ "display_name" => "Gambia" ],
            [ "display_name" => "Georgia" ],
            [ "display_name" => "Germany" ],
            [ "display_name" => "Ghana" ],
            [ "display_name" => "Gibraltar" ],
            [ "display_name" => "Greece" ],
            [ "display_name" => "Greenland" ],
            [ "display_name" => "Grenada" ],
            [ "display_name" => "Guam" ],
            [ "display_name" => "Guatemala" ],
            [ "display_name" => "Guernsey" ],
            [ "display_name" => "Guinea" ],
            [ "display_name" => "Guinea-Bissau" ],
            [ "display_name" => "Guyana" ],
            [ "display_name" => "Haiti" ],
            [ "display_name" => "Honduras" ],
            [ "display_name" => "Hong Kong" ],
            [ "display_name" => "Hungary" ],
            [ "display_name" => "Iceland" ],
            [ "display_name" => "India" ],
            [ "display_name" => "Indonesia" ],
            [ "display_name" => "Iran" ],
            [ "display_name" => "Iraq" ],
            [ "display_name" => "USA" ],
            [ "display_name" => "UK" ],
            [ "display_name" => "UAE"]
        ];
        
        Country::insert($countries);
    }
}
