<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $states = [
            ['state_id' => 1,  'name' => 'Andaman and Nicobar Islands', 'country_id' => 101],
            ['state_id' => 2,  'name' => 'Andhra Pradesh',              'country_id' => 101],
            ['state_id' => 3,  'name' => 'Arunachal Pradesh',           'country_id' => 101],
            ['state_id' => 4,  'name' => 'Assam',                       'country_id' => 101],
            ['state_id' => 5,  'name' => 'Bihar',                       'country_id' => 101],
            ['state_id' => 6,  'name' => 'Chandigarh',                  'country_id' => 101],
            ['state_id' => 7,  'name' => 'Chhattisgarh',                'country_id' => 101],
            ['state_id' => 8,  'name' => 'Dadra and Nagar Haveli',      'country_id' => 101],
            ['state_id' => 9,  'name' => 'Daman and Diu',               'country_id' => 101],
            ['state_id' => 10, 'name' => 'Delhi',                       'country_id' => 101],
            ['state_id' => 11, 'name' => 'Goa',                         'country_id' => 101],
            ['state_id' => 12, 'name' => 'Gujarat',                     'country_id' => 101],
            ['state_id' => 13, 'name' => 'Haryana',                     'country_id' => 101],
            ['state_id' => 14, 'name' => 'Himachal Pradesh',            'country_id' => 101],
            ['state_id' => 15, 'name' => 'Jammu and Kashmir',           'country_id' => 101],
            ['state_id' => 16, 'name' => 'Jharkhand',                   'country_id' => 101],
            ['state_id' => 17, 'name' => 'Karnataka',                   'country_id' => 101],
            ['state_id' => 18, 'name' => 'Kenmore',                     'country_id' => 101],
            ['state_id' => 19, 'name' => 'Kerala',                      'country_id' => 101],
            ['state_id' => 20, 'name' => 'Lakshadweep',                 'country_id' => 101],
            ['state_id' => 21, 'name' => 'Madhya Pradesh',              'country_id' => 101],
            ['state_id' => 22, 'name' => 'Maharashtra',                 'country_id' => 101],
            ['state_id' => 23, 'name' => 'Manipur',                     'country_id' => 101],
            ['state_id' => 24, 'name' => 'Meghalaya',                   'country_id' => 101],
            ['state_id' => 25, 'name' => 'Mizoram',                     'country_id' => 101],
            ['state_id' => 26, 'name' => 'Nagaland',                    'country_id' => 101],
            ['state_id' => 27, 'name' => 'Narora',                      'country_id' => 101],
            ['state_id' => 28, 'name' => 'Natwar',                      'country_id' => 101],
            ['state_id' => 29, 'name' => 'Odisha',                      'country_id' => 101],
            ['state_id' => 30, 'name' => 'Paschim Medinipur',           'country_id' => 101],
            ['state_id' => 31, 'name' => 'Pondicherry',                 'country_id' => 101],
            ['state_id' => 32, 'name' => 'Punjab',                      'country_id' => 101],
            ['state_id' => 33, 'name' => 'Rajasthan',                   'country_id' => 101],
            ['state_id' => 34, 'name' => 'Sikkim',                      'country_id' => 101],
            ['state_id' => 35, 'name' => 'Tamil Nadu',                  'country_id' => 101],
            ['state_id' => 36, 'name' => 'Telangana',                   'country_id' => 101],
            ['state_id' => 37, 'name' => 'Tripura',                     'country_id' => 101],
            ['state_id' => 38, 'name' => 'Uttar Pradesh',               'country_id' => 101],
            ['state_id' => 39, 'name' => 'Uttarakhand',                 'country_id' => 101],
            ['state_id' => 40, 'name' => 'Vaishali',                    'country_id' => 101],
            ['state_id' => 41, 'name' => 'West Bengal',                 'country_id' => 101],
        ];

        foreach ($states as $state) {
            $existing = DB::table('states')->where('state_id', $state['state_id'])->first();

            if ($existing) {
                DB::table('states')
                    ->where('state_id', $state['state_id'])
                    ->update(['name' => $state['name'], 'country_id' => $state['country_id']]);
                continue;
            }

            DB::table('states')->insert($state);
        }
    }
}
