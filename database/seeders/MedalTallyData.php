<?php

namespace Database\Seeders;

class MedalTallyData
{
    public static function getTally(int $aggiesBullsId, $wildcatsId, $sphinxId, $orcasId, $eaglesId,)
    {
        return [
            [
                'Event' => 'Arnis Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 2,
                        'silver_tally' => 2,
                        'bronze_tally' => 14,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 15,
                        'silver_tally' => 2,
                        'bronze_tally' => 1,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 1,
                        'silver_tally' => 4,
                        'bronze_tally' => 10,
                    ]
                ]
            ],
            [
                'Event' => 'Arnis Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 1,
                        'silver_tally' => 7,
                        'bronze_tally' => 11,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 4,
                        'silver_tally' => 4,
                        'bronze_tally' => 3,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 10,
                        'silver_tally' => 3,
                        'bronze_tally' => 2,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 7,
                        'silver_tally' => 3,
                        'bronze_tally' => 2,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 2,
                        'silver_tally' => 5,
                        'bronze_tally' => 4,
                    ]
                ]
            ],

            // Badminton
            [
                'Event' => 'Badminton Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 5,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 5,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 5,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],
            [
                'Event' => 'Badminton Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 5,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 5,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 5,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],

            // Basketball
            [
                'Event' => 'Basketball Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 12,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 12,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 12,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],
            [
                'Event' => 'Basketball Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 12,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 12,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 12,
                    ]
                ]
            ],

            // Beach Volleyball
            [
                'Event' => 'Beach Volleyball Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 4,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 4,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 4,
                        'bronze_tally' => 0,
                    ]
                ]
            ],
            [
                'Event' => 'Beach Volleyball Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 4,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 4,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 4,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],

            // Brazilian Checkers
            [
                'Event' => 'Brazilian Checkers Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 4,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 4,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 4,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],
            [
                'Event' => 'Brazilian Checkers Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 4,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 4,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 4,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],

            // Chess
            [
                'Event' => 'Chess Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 4,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 4,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 4,
                    ]
                ]
            ],
            [
                'Event' => 'Chess Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 4,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 4,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 4,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],

            // COD-M
            [
                'Event' => 'COD-M BR',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 2,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 2,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 2,
                    ]
                ]
            ],
            [
                'Event' => 'COD-M TEAM',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 4,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 4,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 4,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],
            [
                'Event' => 'COD-M MP',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 5,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 5,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 5,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],

            // Dancesports
            [
                'Event' => 'Dancesports Modern',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 2,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 2,
                        'silver_tally' => 0,
                        'bronze_tally' => 2,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],
            [
                'Event' => 'Dancesports Latin',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 2,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 2,
                        'silver_tally' => 2,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],

            // DOTA
            [
                'Event' => 'DOTA Mixed',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 7,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 7,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 7,
                        'bronze_tally' => 0,
                    ]
                ]
            ],

            // Football
            [
                'Event' => 'Football',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 15,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 15,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 15,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],

            // Futsal
            [
                'Event' => 'Futsal',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 10,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 10,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 10,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],

            // Karatedo
            [
                'Event' => 'Karatedo (Men and Women)',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 12,
                        'silver_tally' => 1,
                        'bronze_tally' => 6,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 7,
                        'silver_tally' => 6,
                        'bronze_tally' => 10,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 4,
                        'silver_tally' => 8,
                        'bronze_tally' => 5,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 4,
                        'silver_tally' => 7,
                        'bronze_tally' => 3,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 5,
                        'bronze_tally' => 2,
                    ]
                ]
            ],

            // Lawn Tennis
            [
                'Event' => 'Lawn Tennis Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 5,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 5,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 5,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],
            [
                'Event' => 'Lawn Tennis Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 5,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 5,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 5,
                    ]
                ]
            ],

            // MLBB
            [
                'Event' => 'MLBB Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 6,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 6,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 6,
                    ]
                ]
            ],
            [
                'Event' => 'MLBB Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 6,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 6,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 6,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                ]
            ],

            // Sepak Takraw
            [
                'Event' => 'Sepak Takraw Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 12,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 12,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 12,
                    ]
                ]
            ],
            [
                'Event' => 'Sepak Takraw Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 12,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 12,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 12,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                    ],
            ],
            // Softball
            [
                'Event' => 'Softball Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 12,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 12,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 12,
                    ]
                    ],
            ],
            [
                'Event' => 'Softball Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 12,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 12,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 12,
                        'bronze_tally' => 0,
                    ]
                    ],
            ],
            //Swimming
            [
                'Event' => 'Swimming Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 7,
                        'silver_tally' => 19,
                        'bronze_tally' => 6,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 9,
                        'silver_tally' => 6,
                        'bronze_tally' => 2,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 8,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 14,
                        'silver_tally' => 7,
                        'bronze_tally' => 10,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 5,
                        'silver_tally' => 4,
                        'bronze_tally' => 8,
                    ]
                    ],
            ],
            [
                'Event' => 'Swimming Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 3,
                        'bronze_tally' => 11,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 4,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 21,
                        'silver_tally' => 12,
                        'bronze_tally' => 6,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 10,
                        'silver_tally' => 13,
                        'bronze_tally' => 4,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 4,
                        'bronze_tally' => 2,
                    ]
                    ],
            ],
            //Table Tennis
            [
                'Event' => 'Table Tennis Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 6,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 6,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 6,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                    ],
            ],
            [
                'Event' => 'Table Tennis Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 6,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 6,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 6,
                        'bronze_tally' => 0,
                    ]
                    ],
            ],
            [
                'Event' => 'Table Tennis Mixed',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 2,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 2,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 2,
                    ]
                    ],
            ],
            //Taekwondo
            [
                'Event' => 'Taekwondo Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 2,
                        'bronze_tally' => 4,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 1,
                        'silver_tally' => 3,
                        'bronze_tally' => 1,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 3,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 3,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 2,
                        'bronze_tally' => 2,
                    ]
                    ],
            ],
            [
                'Event' => 'Taekwondo Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 2,
                        'bronze_tally' => 4,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 1,
                        'silver_tally' => 2,
                        'bronze_tally' => 1,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 2,
                        'bronze_tally' => 1,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 5,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 1,
                    ]
                    ],
            ],
            //Track and Field
            [
                'Event' => 'Track and Field Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 19,
                        'silver_tally' => 10,
                        'bronze_tally' => 6,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 3,
                        'bronze_tally' => 3,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 7,
                        'silver_tally' => 14,
                        'bronze_tally' => 7,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 5,
                        'silver_tally' => 4,
                        'bronze_tally' => 15,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                    ],
            ],
            [
                'Event' => 'Track and Field Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 2,
                        'silver_tally' => 3,
                        'bronze_tally' => 15,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 5,
                        'bronze_tally' => 5,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 8,
                        'silver_tally' => 16,
                        'bronze_tally' => 4,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 20,
                        'silver_tally' => 6,
                        'bronze_tally' => 4,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 2,
                    ]
                    ],
            ],
            //Volleyball
            [
                'Event' => 'Volleyball Men',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 12,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 12,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 12,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                    ],
            ],
            [
                'Event' => 'Volleyball Women',
                'Results' => [
                    [
                        'id' => $aggiesBullsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $wildcatsId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 12,
                    ],
                    [
                        'id' => $sphinxId,
                        'gold_tally' => 0,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $orcasId,
                        'gold_tally' => 0,
                        'silver_tally' => 12,
                        'bronze_tally' => 0,
                    ],
                    [
                        'id' => $eaglesId,
                        'gold_tally' => 12,
                        'silver_tally' => 0,
                        'bronze_tally' => 0,
                    ]
                    ],
            ],
        ];
    }
}
