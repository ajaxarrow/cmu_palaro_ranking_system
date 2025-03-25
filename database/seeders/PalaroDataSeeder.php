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
            'name' => 'Administrator - Main',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('_adMin_palar0_$$_maiN'),
        ]);


        User::factory()->create([
            'name' => 'Administrator - Khent',
            'email' => 'adminkhent@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('_adMin_palar0_$$_khnT'),
        ]);


        User::factory()->create([
            'name' => 'Administrator - Edgar',
            'email' => 'adminedgar@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('_adMin_palar0_$$_edgR'),
        ]);


        User::factory()->create([
            'name' => 'Administrator - Axl',
            'email' => 'adminaxl@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('_adMin_palar0_$$_aXl'),
        ]);

        User::factory()->create([
            'name' => '',
            'email' => '',
            'role' => 'tournament_director',
            'password' => Hash::make('CMUpalaroTD@2025'),
        ]);




        $now = now(); // Get current timestamp once to use across inserts

        // Insert Teams
        $teamsData = [
            'Aggie Bulls' => ['desc' => 'Strong and resilient team'],
            'Wildcats' => ['desc' => 'Kambras Wildcats!'],
            'CBM Eagles' => ['desc' => 'High-flying champions'],
            'Striking Sphinx' => ['desc' => 'Wise and strategic thinkers'],
            'Mighty Orcas' => ['desc' => 'Salom sa lawod, hampak sa balod!'],
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
            'Aggie Bulls' => ['CA'],
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
            ['name' => 'Valorant', 'created_at' => $now, 'updated_at' => $now]
        ];

        DB::table('sports')->insert($sports);
        $sports = DB::table('sports')->pluck('id', 'name');

        $directors = [
            ['name' => 'Mindell Rey C. Aguirre', 'email' => 'aguirremindell@cmu.edu.ph', 'sport' => 'Table Tennis'],
            ['name' => 'Flordeliza N. Aguirre', 'email' => 'f.flordeliza.aguirre@cmu.edu.ph', 'sport' => 'Table Tennis'],
            ['name' => 'Jose Hermis P. Patricio', 'email' => 'f.josehermis.patricio@cmu.edu.ph', 'sport' => 'Volleyball'],
            ['name' => 'Rhandie S. Balane', 'email' => 'f.rhandie.balane@cmu.edu.ph', 'sport' => 'Dancesports'],
            ['name' => 'Mark Bryan T. Inso', 'email' => 'f.markbryan.inso@cmu.edu.ph', 'sport' => 'Pickleball'],
            ['name' => 'Dianne Salvaleon', 'email' => 'f.dianne.salvaleon@cmu.edu.ph', 'sport' => 'Swimming'],
            ['name' => 'Princes Dhe L. Perez', 'email' => 'f.princesdhe.perez@cmu.edu.ph', 'sport' => 'Karatedo'],
            ['name' => 'Kara Angela E. Opiso', 'email' => 'f.kara.egdalin@gmail.com', 'sport' => 'Football'],
            ['name' => 'Shaira L. Ganancial', 'email' => 'f.shaira.ganancial@cmu.edu.ph', 'sport' => 'Sepak Takraw'],
            ['name' => 'Griezl Jean H. Bercero', 'email' => 'f.griezljean@cmu.edu.ph', 'sport' => 'Taekwondo'],
            ['name' => 'Pamela Christine H. Escañr', 'email' => 'f.pamelachristine.escaner@cmu.edu.ph', 'sport' => 'Futsal'],
            ['name' => 'Felito A. Batoctoy', 'email' => 'batoctoyfelito@gmail.com', 'sport' => 'Basketball'],
            ['name' => 'Nonilon G. Obedencio', 'email' => 'doodz61@cmu.edu.ph', 'sport' => 'Lawn Tennis'],
            ['name' => 'Neptune Roy B. Comoda', 'email' => 'neptune.comoda@cmu.edu.ph', 'sport' => 'COD-M'],
            ['name' => 'Dana Rica P. Cabahug', 'email' => 'danaricacabahug@gmail.com', 'sport' => 'Beach Volleyball'],
        ];

        foreach ($directors as $director) {
            User::factory()->create([
                'name' => $director['name'],
                'email' => $director['email'],
                'role' => 'tournament_director',
                'password' => Hash::make('CMUpalaroTD@2025'),
                'sport_id' => $sports[$director['sport']],
            ]);
        }


        $eventCategories = [
            ['name' => 'Valorant - Mixed', 'sport_id' => $sports['Valorant'], 'medal_weight_category_id' => $medalCategories['No Value'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 0],

            // Arnis Events
            // ANYO (Category 4)
            // NON TRADITIONAL
            ['name' => 'Arnis Men - Anyo Individual Weapon', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Anyo Individual Weapon', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Anyo Individual Double Weapon', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Anyo Individual Double Weapon', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Anyo Team Single Weapon', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],
            ['name' => 'Arnis Women - Anyo Team Single Weapon', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],
            ['name' => 'Arnis Men - Anyo Team Double Weapon', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],
            ['name' => 'Arnis Women - Anyo Team Double Weapon', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],

            // TRADITIONAL
            ['name' => 'Arnis Men - Anyo Individual Open Weapon', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Anyo Individual Open Weapon', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Anyo Team Open Weapon', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],
            ['name' => 'Arnis Women - Anyo Team Open Weapon', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],

            // COMBATANT/LABANAN (Category 2)
            // FULL CONTACT PADDED STICK - MEN
            ['name' => 'Arnis Men - Combatant Padded Stick 47kg Below/Pin Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Padded Stick Over 47kg to 52kg/Bantam Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Padded Stick Over 52kg to 56kg/Feather Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Padded Stick Over 56kg to 61kg/Extra Light Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Padded Stick Over 61kg to 66kg/Half Light Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Padded Stick Over 66kg to 71kg/Light Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Padded Stick Over 71kg to 78kg/Half Middle Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Padded Stick Over 78kg to 86kg/Middle Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Padded Stick Over 86kg to 95kg/Light Heavy Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Padded Stick Over 95kg to 105kg/Heavy Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            // FULL CONTACT PADDED STICK - WOMEN
            ['name' => 'Arnis Women - Combatant Padded Stick 42kg Below/Pin Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Padded Stick Over 42kg to 45kg/Bantam Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Padded Stick Over 45kg to 48kg/Feather Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Padded Stick Over 48kg to 52kg/Extra Light Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Padded Stick Over 52kg to 56kg/Half Light Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Padded Stick Over 56kg to 61kg/Light Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Padded Stick Over 61kg to 66kg/Half Middle Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Padded Stick Over 66kg to 72kg/Middle Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Padded Stick Over 72kg to 78kg/Light Heavy Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Padded Stick Over 78kg to 88kg/Heavy Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            // FULL CONTACT LIVE STICK - MEN
            ['name' => 'Arnis Men - Combatant Live Stick 47kg Below/Pin Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Live Stick Over 47kg to 52kg/Bantam Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Live Stick Over 52kg to 56kg/Feather Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Live Stick Over 56kg to 61kg/Extra Light Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Live Stick Over 61kg to 66kg/Half Light Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Live Stick Over 66kg to 71kg/Light Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Live Stick Over 71kg to 78kg/Half Middle Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Live Stick Over 78kg to 86kg/Middle Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Live Stick Over 86kg to 95kg/Light Heavy Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Men - Combatant Live Stick Over 95kg to 105kg/Heavy Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            // FULL CONTACT LIVE STICK - WOMEN
            ['name' => 'Arnis Women - Combatant Live Stick 42kg Below/Pin Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Live Stick Over 42kg to 45kg/Bantam Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Live Stick Over 45kg to 48kg/Feather Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Live Stick Over 48kg to 52kg/Extra Light Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Live Stick Over 52kg to 56kg/Half Light Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Live Stick Over 56kg to 61kg/Light Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Live Stick Over 61kg to 66kg/Half Middle Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Live Stick Over 66kg to 72kg/Middle Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Live Stick Over 72kg to 78kg/Light Heavy Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Arnis Women - Combatant Live Stick Over 78kg to 88kg/Heavy Weight', 'sport_id' => $sports['Arnis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            //Athletics
            ['name' => 'Athletics Men - 5000 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Women - 5000 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Men - 10000 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            // CATEGORY 2 - Moderate Complexity
            ['name' => 'Athletics Men - 1500 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Women - 1500 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Men - 3000 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Women - 3000 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            // CATEGORY 3 - Mid-Level Complexity
            ['name' => 'Athletics Men - 400 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Women - 400 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Men - 800 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Women - 800 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Men - 4X400 M Relay', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Athletics Women - 4X400 M Relay', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Athletics Mixed - 4X400 M Relay', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Athletics Men - Classical Relay', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Athletics Women - Classical Relay', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Athletics Mixed - Classical Relay', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Athletics Men - Javelin', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Women - Javelin', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Men - Shot Put', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Women - Shot Put', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Men - Discus', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Women - Discus', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Men - Long Jump', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Women - Long Jump', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Men - Triple Jump', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Women - Triple Jump', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            // CATEGORY 4 - Low Complexity
            ['name' => 'Athletics Men - 100 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Women - 100 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Men - 200 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Women - 200 M', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Athletics Men - 4X100 M Relay', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Athletics Women - 4X100 M Relay', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Athletics Mixed - 4X100 M Relay', 'sport_id' => $sports['Athletics'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],


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
            // KATA INDIVIDUAL (Category 4, except Advanced* which is Category 3)
            ['name' => 'Karatedo Men - Novice Kata', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Men - Intermediate Kata', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Men - Advanced Kata', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            ['name' => 'Karatedo Women - Novice Kata', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Women - Intermediate Kata', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Women - Advanced Kata', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            // KATA TEAM (Mixed) (Category 4, except Advanced* which is Category 3)
            ['name' => 'Karatedo Team - Novice Kata', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],
            ['name' => 'Karatedo Team - Intermediate Kata', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],
            ['name' => 'Karatedo Team - Advanced Kata', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],

            // KUMITE INDIVIDUAL (Category 2)
            // Novice Male
            ['name' => 'Karatedo Men - Novice Kumite -60kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Men - Novice Kumite +61kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Men - Novice Kumite +68kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            // Novice Female
            ['name' => 'Karatedo Women - Novice Kumite -50kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Women - Novice Kumite -55kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Women - Novice Kumite +55kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            // Intermediate Male
            ['name' => 'Karatedo Men - Intermediate Kumite -60kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Men - Intermediate Kumite +61kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Men - Intermediate Kumite +68kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            // Intermediate Female
            ['name' => 'Karatedo Women - Intermediate Kumite -50kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Women - Intermediate Kumite -55kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Women - Intermediate Kumite +55kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            // Advanced Male
            ['name' => 'Karatedo Men - Advanced Kumite -60kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Men - Advanced Kumite +61kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Men - Advanced Kumite +68kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            // Advanced Female
            ['name' => 'Karatedo Women - Advanced Kumite -50kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Women - Advanced Kumite -55kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Karatedo Women - Advanced Kumite +55kg', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            // KUMITE TEAM
            ['name' => 'Karatedo Team - Male Kumite', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],
            ['name' => 'Karatedo Team - Female Kumite', 'sport_id' => $sports['Karatedo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],


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
            ['name' => 'Swimming Men - 4X50 MEDLEY RELAY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Swimming Women - 4X50 MEDLEY RELAY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Swimming Men - 100M BACKSTROKE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 100M BACKSTROKE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 200M FREESTYLE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 200M FREESTYLE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 100M BREASTROKE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 100M BREASTROKE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 100M INDIVIDUAL MEDLEY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 100M INDIVIDUAL MEDLEY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 200M BUTTERFLY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 200M BUTTERFLY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 50M FREESTYLE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 50M FREESTYLE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 4X100M FREESTYLE RELAY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Swimming Women - 4X100M FREESTYLE RELAY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Swimming Men - 1500M FREESTYLE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 800M FREESTYLE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 200M BACKSTROKE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 200M BACKSTROKE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 100M BUTTERFLY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 100M BUTTERFLY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 200M BREASTSTROKE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 200M BREASTSTROKE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 100M FREESTYLE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 100M FREESTYLE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 50M BACKSTROKE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 50M BACKSTROKE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 4X50M FREESTYLE RELAY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Swimming Women - 4X50M FREESTYLE RELAY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Swimming Men - 4X100M MEDLEY RELAY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Swimming Women - 4X100M MEDLEY RELAY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Swimming Men - 400M FREESTYLE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 400M FREESTYLE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 50M BUTTERFLY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 50M BUTTERFLY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 50M BREASTSTROKE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 50M BREASTSTROKE', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 200M INDIVIDUAL MEDLEY RELAY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Women - 200M INDIVIDUAL MEDLEY RELAY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Swimming Men - 4X200M FREESTYLE RELAY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Swimming Women - 4X200M FREESTYLE RELAY', 'sport_id' => $sports['Swimming'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],



            //Table Tennis
            ['name' => 'Table Tennis Men - TEAM', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 10],
            ['name' => 'Table Tennis Women - TEAM', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 10],
            ['name' => 'Table Tennis Men - SINGLE', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Table Tennis Women - SINGLE', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Table Tennis Men - DOUBLES', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Table Tennis Women - DOUBLES', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Table Tennis Mixed', 'sport_id' => $sports['Table Tennis'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],

            //Taekwondo

            ['name' => 'Taekwondo Men - Kyorugi: Fin, 54 kg and below', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Taekwondo Women - Kyorugi: Fin, 46 kg and below', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            ['name' => 'Taekwondo Men - Kyorugi: Fly, over 54 kg to 58 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Taekwondo Women - Kyorugi: Fly, over 46 kg to 49 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            ['name' => 'Taekwondo Men - Kyorugi: Bantam, over 58 kg to 63 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Taekwondo Women - Kyorugi: Bantam, over 49 kg to 53 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            ['name' => 'Taekwondo Men - Kyorugi: Feather, over 63 kg to 68 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Taekwondo Women - Kyorugi: Feather, over 53 kg to 57 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            ['name' => 'Taekwondo Men - Kyorugi: Light, over 68 kg to 74 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Taekwondo Women - Kyorugi: Light, over 57 kg to 62 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            ['name' => 'Taekwondo Men - Kyorugi: Welter, over 74 kg to 80 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Taekwondo Women - Kyorugi: Welter, over 62 kg to 67 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            ['name' => 'Taekwondo Men - Kyorugi: Middle, over 80 kg to 87 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Taekwondo Women - Kyorugi: Middle, over 67 kg to 73 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],

            ['name' => 'Taekwondo Men - Kyorugi: Heavy, over 87 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Taekwondo Women - Kyorugi: Heavy, over 73 kg', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],


            ['name' => 'Taekwondo Men - Poomsaem Individual', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Taekwondo Women - Poomsae Individual', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Taekwondo Men - Poomsaem Mixed', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Taekwondo Women - Poomsae Mixed', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Taekwondo Men - Poomsaem Team', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],
            ['name' => 'Taekwondo Women - Poomsae Team', 'sport_id' => $sports['Taekwondo'], 'medal_weight_category_id' => $medalCategories['Low Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 3],


            //Volleyball
            ['name' => 'Volleyball Men', 'sport_id' => $sports['Volleyball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],
            ['name' => 'Volleyball Women', 'sport_id' => $sports['Volleyball'], 'medal_weight_category_id' => $medalCategories['High Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 24],

            //Pickleball
            ['name' => 'Pickleball Men - MLP', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Pickleball Women - MLP', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Pickleball Men - Single', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Pickleball Women - Single', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],
            ['name' => 'Pickleball Men - Doubles', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Pickleball Women - Doubles', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 4],
            ['name' => 'Pickleball Mixed ', 'sport_id' => $sports['Pickleball'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 2],


            //Pencak Silat
           // Pencak Silat - Men
            ['name' => 'Pencak Silat Men - Class S: 45 kg & below', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Class A: 45 kg - 50 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Class B: 50 kg - 55 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Class C: 55 kg - 60 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Class D: 60 kg - 65 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Class E: 65 kg - 70 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Class F: 70 kg - 75 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Class G: 75 kg - 80 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Class H: 80 kg - 85 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Class I: 85 kg - 90 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Class J: 90 kg - 95 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Open 1: 95 kg - 110 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Open 2: 110 kg & above', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => false, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],


        // Pencak Silat - Women

        ['name' => 'Pencak Silat Women - Class S: 45 kg & below', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
        ['name' => 'Pencak Silat Women - Class A: 45 kg - 50 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
        ['name' => 'Pencak Silat Women - Class B: 50 kg - 55 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
        ['name' => 'Pencak Silat Women - Class C: 55 kg - 60 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
        ['name' => 'Pencak Silat Women - Class D: 60 kg - 65 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
        ['name' => 'Pencak Silat Women - Class E: 65 kg - 70 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
        ['name' => 'Pencak Silat Women - Class F: 70 kg - 75 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
        ['name' => 'Pencak Silat Women - Class G: 75 kg - 80 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
        ['name' => 'Pencak Silat Women - Class H: 80 kg - 85 kg', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Moderate Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],


            ['name' => 'Pencak Silat Men - Regu', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Women - Regu', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Bebas', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Women - Bebas', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Tunggal', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Women - Tunggal', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Men - Ganda', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1],
            ['name' => 'Pencak Silat Women - Ganda', 'sport_id' => $sports['Pencak Silat'], 'medal_weight_category_id' => $medalCategories['Mid-Level Complexity'], 'is_double_bronze' => true, 'created_at' => $now, 'updated_at' => $now, 'medal_count' => 1]
        ];

        DB::table('event_categories')->insert($eventCategories);
    }
}
