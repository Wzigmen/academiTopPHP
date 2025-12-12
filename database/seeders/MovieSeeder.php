<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Movie::insert([
            [
                'title' => 'Начало',
                'overview' => 'Вор, который крадет корпоративные секреты с помощью технологии обмена снами, получает обратную задачу - внедрить идею в сознание генерального директора.',
                'poster_path' => '/p31k23ftwQ3bCg6g5y8i5v4p5.jpg',
                'vote_average' => 8.8,
                'type' => 'movie',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Криминальное чтиво',
                'overview' => 'Жизни двух наемных убийц, боксера, жены гангстера и пары бандитов из закусочной переплетаются в четырех историях о насилии и искуплении.',
                'poster_path' => '/pB8u0y9s2o4iM2tG3s3x4p.jpg',
                'vote_average' => 8.9,
                'type' => 'movie',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Во все тяжкие',
                'overview' => 'Учитель химии с диагнозом неизлечимый рак легких начинает производить и продавать метамфетамин, чтобы обеспечить будущее своей семьи.',
                'poster_path' => '/p31k23ftwQ3bCg6g5y8i5v4p5.jpg',
                'vote_average' => 9.5,
                'type' => 'tv',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Игра престолов',
                'overview' => 'Девять знатных семейств сражаются за контроль над мифической землей Вестерос, в то время как древний враг возвращается после тысячелетнего сна.',
                'poster_path иллюзионистов',
                'vote_average' => 8.4,
                'type' => 'tv',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}