<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class PalaroDataSeeder extends Seeder
{
    public function run()
    {

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);


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
            ['name' => 'Brazilian Checkers', 'created_at' => $now, 'updated_at' => $now],
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
            // Arnis
            // ['name' => 'Arnis Men - Labanan', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, ],
            // ['name' => 'Arnis Men - Labanan, Live Stick', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Arnis Men - Labanan, Padded', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Arnis Men - Anyo', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Arnis Men - Anyo, Traditional', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Arnis Men - Anyo, Non-Traditional', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Arnis Women - Labanan', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Arnis Women - Labanan, Live Stick', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Arnis Women - Labanan, Padded', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Arnis Women - Anyo', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Arnis Men - Anyo, Traditional', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Arnis Men - Anyo, Non-Traditional', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            ['name' => 'Arnis Men - Anyo', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 40],
            ['name' => 'Arnis Women - Anyo', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 40],
            ['name' => 'Arnis Men - Labanan', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],
            ['name' => 'Arnis Women - Labanan', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],

            //Athletics
            ['name' => 'Athletics Men - Category 1', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],
            ['name' => 'Athletics Women - Category 1', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],
            ['name' => 'Athletics Men - Category 2', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Athletics Women - Category 2', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Athletics Men - Category 3', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 38],
            ['name' => 'Athletics Women - Category 3', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 38],
            ['name' => 'Athletics Men - Category 4', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 16],
            ['name' => 'Athletics Women - Category 4', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 16],


            //Badminton
            ['name' => 'Badminton Women', 'sport_id' => $sports['Badminton'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 10],
            ['name' => 'Badminton Men', 'sport_id' => $sports['Badminton'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 10],

            //Basketball
            ['name' => 'Basketball Women', 'sport_id' => $sports['Basketball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],
            ['name' => 'Basketball Men', 'sport_id' => $sports['Basketball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],

            //Beach Volleyball
            ['name' => 'Beach Volleyball Women', 'sport_id' => $sports['Beach Volleyball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 8],
            ['name' => 'Beach Volleyball Men', 'sport_id' => $sports['Beach Volleyball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 8],

            //Brazilian Checkers
            ['name' => 'Brazilian Checkers Women', 'sport_id' => $sports['Brazilian Checkers'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 8],
            ['name' => 'Brazilian Checkers Men', 'sport_id' => $sports['Brazilian Checkers'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 8],


            //Chess
            ['name' => 'Chess Women', 'sport_id' => $sports['Chess'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 8],
            ['name' => 'Chess Men', 'sport_id' => $sports['Chess'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 8],

            //COD-M
            ['name' => 'COD-M BR', 'sport_id' => $sports['COD-M'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'COD-M TEAM', 'sport_id' => $sports['COD-M'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'COD-M MP', 'sport_id' => $sports['COD-M'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],


            //Dancesports
            ['name' => 'Dancesports Modern', 'sport_id' => $sports['Dancesports'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Dancesports Latin', 'sport_id' => $sports['Dancesports'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],

            //Dota
            ['name' => 'DOTA Mixed', 'sport_id' => $sports['DOTA Mixed'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 7],

            //Football
            ['name' => 'Football', 'sport_id' => $sports['Football'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 15],

            //Futsal
            ['name' => 'Futsal', 'sport_id' => $sports['Futsal'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 15],

            //Karatedo
            ['name' => 'Karatedo Men - Kumite', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 21],
            ['name' => 'Karatedo Women - Kumite', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 21],
            ['name' => 'Karatedo Men - Kata', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 10],
            ['name' => 'Karatedo Women - Kata', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 10],
            ['name' => 'Karatedo Men - Advance Kata', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 5],
            ['name' => 'Karatedo Women - Advance Kata', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 5],

            //Lawn Tennis
            ['name' => 'Lawn Tennis Men', 'sport_id' => $sports['Lawn Tennis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 10],
            ['name' => 'Lawn Tennis Women', 'sport_id' => $sports['Lawn Tennis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 10],

            //MLBB Men
            ['name' => 'MLBB Men', 'sport_id' => $sports['MLBB'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 12],
            ['name' => 'MLBB Women', 'sport_id' => $sports['MLBB'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 12],

            //Sepak Takraw
            ['name' => 'Sepak Takraw Men', 'sport_id' => $sports['Sepak Takraw'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],
            ['name' => 'Sepak Takraw Women', 'sport_id' => $sports['Sepak Takraw'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],

            //Softball
            ['name' => 'Softball Men', 'sport_id' => $sports['Softball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],
            ['name' => 'Softball Women', 'sport_id' => $sports['Softball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],

            //Swimming
            ['name' => 'Swimming Men - Category 1', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Swimming Women - Category 1', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Swimming Men - Category 2', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Swimming Women - Category 2', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Swimming Men - Category 3', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 68],
            ['name' => 'Swimming Women - Category 3', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 68],

            //Table Tennis
            ['name' => 'Table Tennis Men - TEAM', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 10],
            ['name' => 'Table Tennis Women - TEAM', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 10],
            ['name' => 'Table Tennis Men - SINGLE', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Table Tennis Women - SINGLE', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Table Tennis Men - DOUBLES', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Table Tennis Women - DOUBLES', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Table Tennis Mixed', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],

            //Taekwondo
            ['name' => 'Taekwondo Men - Kirogi', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 16],
            ['name' => 'Taekwondo Women - Kirogi', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 16],
            ['name' => 'Taekwondo Men - Poomsae', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 10],
            ['name' => 'Taekwondo Women - Poomsae', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 10],


            //Volleyball
            ['name' => 'Volleyball Men', 'sport_id' => $sports['Volleyball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],
            ['name' => 'Volleyball Women', 'sport_id' => $sports['Volleyball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],

            //Pickleball
            ['name' => 'Pickleball Men - MLP', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Pickleball Women - MLP', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Pickleball Men - Single', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Pickleball Women - Single', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Pickleball Men - Doubles', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Pickleball Women - Doubles', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Pickleball Mixed ', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],


            //Pencak Silat
            ['name' => 'Pencak Silat - Tanding', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],
            ['name' => 'Pencak Silat - Tanding', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],
            ['name' => 'Pencak Silat - Sine', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Pencak Silat - Sine', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
        ];

        DB::table('event_categories')->insert($eventCategories);
    }
}
