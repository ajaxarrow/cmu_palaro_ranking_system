<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PalaroDataSeeder extends Seeder
{
    public function run()
    {
        $now = now(); // Get current timestamp once to use across inserts

        // Insert Teams
        $teamsData = [
            'Bulls' => ['desc' => 'Strong and resilient team'],
            'Wildcats' => ['desc' => 'Agile and fearless competitors'],
            'Eagles' => ['desc' => 'High-flying champions'],
            'Sphinx' => ['desc' => 'Wise and strategic thinkers'],
            'Orcas' => ['desc' => 'Swift and powerful swimmers'],
        ];

        foreach ($teamsData as $name => $team) {
            $teamsData[$name]['id'] = DB::table('teams')->insertGetId([
                'name' => $name,
                'desc' => $team['desc'],
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        // Insert Colleges
        $collegesData = [
            'College of Agriculture' => 'CA',
            'College of Arts and Sciences' => 'CAS',
            'College of Business and Management' => 'CBM',
            'College of Education' => 'COED',
            'College of Engineering' => 'CE',
            'College of Forestry and Environmental Sciences' => 'CFES',
            'College of Human Ecology' => 'CHE',
            'College of Information Sciences and Computing' => 'CISC',
            'College of Nursing' => 'CON',
            'College of Veterinary Medicine' => 'CVM',
        ];

        foreach ($collegesData as $name => $acronym) {
            $collegesData[$name] = [
                'id' => DB::table('colleges')->insertGetId([
                    'name' => $name,
                    'acronym' => $acronym,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]),
                'acronym' => $acronym,
            ];
        }

        // Insert Team Compositions
        $teamCompositionsData = [
            'Bulls' => ['CA'],
            'Wildcats' => ['CISC', 'CE'],
            'Eagles' => ['CBM'],
            'Sphinx' => ['CAS', 'CHE', 'CON'],
            'Orcas' => ['COED', 'CFES', 'CVM'],
        ];

        $teamCompositions = [];
        foreach ($teamCompositionsData as $teamName => $collegeAcronyms) {
            foreach ($collegeAcronyms as $acronym) {
                $college = collect($collegesData)->firstWhere('acronym', $acronym);
                $teamCompositions[] = [
                    'team_id' => $teamsData[$teamName]['id'],
                    'college_id' => $college['id'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::table('team_compositions')->insert($teamCompositions);

        // Insert Medal Categories
        $medalCategories = [
            ['category_no' => 1, 'name' => 'High Complexity', 'gold_weight' => 1.50, 'silver_weight' => 0.75, 'bronze_weight' => 0.38],
            ['category_no' => 2, 'name' => 'Moderate Complexity', 'gold_weight' => 1.30, 'silver_weight' => 0.65, 'bronze_weight' => 0.33],
            ['category_no' => 3, 'name' => 'Mid-Level Complexity', 'gold_weight' => 1.10, 'silver_weight' => 0.55, 'bronze_weight' => 0.28],
            ['category_no' => 4, 'name' => 'Low Complexity', 'gold_weight' => 1.00, 'silver_weight' => 0.50, 'bronze_weight' => 0.25],
            ['category_no' => 5, 'name' => 'No Value', 'gold_weight' => 0.00, 'silver_weight' => 0.00, 'bronze_weight' => 0.00],
        ];

        foreach ($medalCategories as &$category) {
            $category['created_at'] = $now;
            $category['updated_at'] = $now;
        }

        DB::table('medal_weight_categories')->insert($medalCategories);
        $medalCategories = DB::table('medal_weight_categories')->pluck('id', 'name')->toArray();

        $sports = [
            ['name' => 'Arnis', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Athletics', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Badminton', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Basketball', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Beach Volleyball', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Brazilian Checker', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chess', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'COD-M', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Dancesports', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'DOTA Mixed', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Football', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Futsal', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Karatedo', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lawn Tennis', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'MLBB', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sepak Takraw', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Softball', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Swimming', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Table Tennis', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Taekwondo', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Volleyball', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pickleball', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Pencak Silat', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('sports')->insert($sports);
        $sports = DB::table('sports')->pluck('id', 'name');


        $eventCategories = [
            //Arnis
            // ['name' => 'Arnis Men - Labanan', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arnis Men - Labanan, Live Stick', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arnis Men - Labanan, Padded', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Arnis Men - Anyo', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arnis Men - Anyo, Traditional', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arnis Men - Anyo, Non-Traditional', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Arnis Women - Labanan', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arnis Women - Labanan, Live Stick', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arnis Women - Labanan, Padded', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Arnis Women - Anyo', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arnis Men - Anyo, Traditional', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arnis Men - Anyo, Non-Traditional', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],


            //Badminton
            ['name' => 'Badminton Women', 'sport_id' => $sports['Badminton'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Badminton Men', 'sport_id' => $sports['Badminton'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Basketball
            ['name' => 'Basketball Women', 'sport_id' => $sports['Basketball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Basketball Men', 'sport_id' => $sports['Basketball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Beach Volleyball
            ['name' => 'Beach Volleyball Women', 'sport_id' => $sports['Beach Volleyball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Beach Volleyball Men', 'sport_id' => $sports['Beach Volleyball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            ['name' => 'Modern', 'sport_id' => $sports['Dancesports'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Latin', 'sport_id' => $sports['Dancesports'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('event_categories')->insert($eventCategories);
    }
}
