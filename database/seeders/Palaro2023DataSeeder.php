<?php

namespace Database\Seeders;

use Database\Seeders\MedalTallyData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Palaro2023DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $now = now();
        $year = 2023;

        // Insert Teams
        $teamsData = [
            'Aggies Bulls' => ['desc' => 'Strong and resilient team'],
            'Wildcats' => ['desc' => 'Agile and fearless competitors'],
            'CBM Eagles' => ['desc' => 'High-flying champions'],
            'Striking Sphinx' => ['desc' => 'Wise and strategic thinkers'],
            'Mighty Orcas' => ['desc' => 'Swift and powerful swimmers'],
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
            'Aggies Bulls' => ['CA'],
            'Wildcats' => ['CISC', 'CE'],
            'CBM Eagles' => ['CBM'],
            'Striking Sphinx' => ['CAS', 'CHE', 'CON'],
            'Mighty Orcas' => ['COED', 'CFES', 'CVM'],
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
            ['category_no' => 1, 'name' => 'High Complexity', 'gold_weight' => 1.5, 'silver_weight' => 1.2, 'bronze_weight' => 1],
            ['category_no' => 2, 'name' => 'Moderate Complexity', 'gold_weight' => 1.30, 'silver_weight' => 1.1, 'bronze_weight' => 0.9],
            ['category_no' => 3, 'name' => 'Mid-Level Complexity', 'gold_weight' => 1.1, 'silver_weight' => 0.9, 'bronze_weight' => 0.8],
            ['category_no' => 4, 'name' => 'Low Complexity', 'gold_weight' => 1.00, 'silver_weight' => 0.8, 'bronze_weight' => 0.6],
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
            ['name' => 'Badminton', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Basketball', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Beach Volleyball', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Brazilian Checkers', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chess', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'COD-M', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Dancesports', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'DOTA', 'created_at' => $now, 'updated_at' => $now],
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
            ['name' => 'Track and Field', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Volleyball', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('sports')->insert($sports);
        $sports = DB::table('sports')->pluck('id', 'name');


        $eventCategories = [
            // Arnis
            ['name' => 'Arnis Men', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Arnis Women', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],


            //Badminton
            ['name' => 'Badminton Women', 'sport_id' => $sports['Badminton'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Badminton Men', 'sport_id' => $sports['Badminton'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Basketball
            ['name' => 'Basketball Women', 'sport_id' => $sports['Basketball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Basketball Men', 'sport_id' => $sports['Basketball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Beach Volleyball
            ['name' => 'Beach Volleyball Women', 'sport_id' => $sports['Beach Volleyball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Beach Volleyball Men', 'sport_id' => $sports['Beach Volleyball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Brazilian Checkers
            ['name' => 'Brazilian Checkers Women', 'sport_id' => $sports['Brazilian Checkers'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Brazilian Checkers Men', 'sport_id' => $sports['Brazilian Checkers'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Chess
            ['name' => 'Chess Women', 'sport_id' => $sports['Chess'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Chess Men', 'sport_id' => $sports['Chess'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //COD-M
            ['name' => 'COD-M BR', 'sport_id' => $sports['COD-M'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'COD-M TEAM', 'sport_id' => $sports['COD-M'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'COD-M MP', 'sport_id' => $sports['COD-M'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],


            //Dancesports
            ['name' => 'Dancesports Modern', 'sport_id' => $sports['Dancesports'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Dancesports Latin', 'sport_id' => $sports['Dancesports'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Dota
            ['name' => 'DOTA Mixed', 'sport_id' => $sports['DOTA'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Football
            ['name' => 'Football', 'sport_id' => $sports['Football'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Futsal
            ['name' => 'Futsal', 'sport_id' => $sports['Futsal'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Karatedo
            ['name' => 'Karatedo (Men and Women)', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Lawn Tennis
            ['name' => 'Lawn Tennis Men', 'sport_id' => $sports['Lawn Tennis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lawn Tennis Women', 'sport_id' => $sports['Lawn Tennis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //MLBB Men
            ['name' => 'MLBB Men', 'sport_id' => $sports['MLBB'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'MLBB Women', 'sport_id' => $sports['MLBB'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Sepak Takraw
            ['name' => 'Sepak Takraw Men', 'sport_id' => $sports['Sepak Takraw'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sepak Takraw Women', 'sport_id' => $sports['Sepak Takraw'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Softball
            ['name' => 'Softball Men', 'sport_id' => $sports['Softball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Softball Women', 'sport_id' => $sports['Softball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Swimming
            ['name' => 'Swimming Men', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Swimming Women', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Table Tennis
            ['name' => 'Table Tennis Men', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Table Tennis Women', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Table Tennis Mixed', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Taekwondo
            ['name' => 'Taekwondo Men', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Taekwondo Women', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Track and Field
            ['name' => 'Track and Field Men', 'sport_id' => $sports['Track and Field'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Track and Field Women', 'sport_id' => $sports['Track and Field'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],

            //Volleyball
            ['name' => 'Volleyball Men', 'sport_id' => $sports['Track and Field'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Volleyball Women', 'sport_id' => $sports['Track and Field'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now]
        ];

        DB::table('event_categories')->insert($eventCategories);
        $eventCategories = DB::table('event_categories')->pluck('id', 'name');

        $aggiesBullsId = DB::table('teams')->where('name', 'Aggies Bulls')->value('id');
        $wildcatsId = DB::table('teams')->where('name', 'Wildcats')->value('id');
        $sphinxId = DB::table('teams')->where('name', 'Striking Sphinx')->value('id');
        $orcasId = DB::table('teams')->where('name', 'Mighty Orcas')->value('id');
        $eaglesId = DB::table('teams')->where('name', 'CBM Eagles')->value('id');


        $tally = MedalTallyData::getTally($aggiesBullsId, $wildcatsId, $sphinxId, $orcasId, $eaglesId);
        $medalRecords = [];

        foreach ($tally as $event) {
            $eventName = $event['Event']; // Get event name
            $eventCategoryId = $eventCategories[$eventName];

            foreach ($event['Results'] as $result) {
                $medalRecords[] = [
                    'year' => $year,
                    'team_id' => $result['id'],
                    'event_category_id' => $eventCategoryId,
                    'gold_tally' => $result['gold_tally'],
                    'silver_tally' => $result['silver_tally'],
                    'bronze_tally' => $result['bronze_tally'],
                    'entry_by' => 1,
                    'posted_by' => 1,
                    'is_posted' => true,
                    'number_of_participant' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Insert all medal records into the database
        DB::table('medal_records')->insert($medalRecords);
    }
}
