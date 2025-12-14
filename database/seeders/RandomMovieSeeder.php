<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;

class RandomMovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $movies = [
            'Загадка Прошлого',
            'Бесконечный Горизонт',
            'Шепот Забытых Звезд',
            'Тень в Лабиринте',
            'Последний Код',
            'Эхо Безмолвия',
            'Путешествие к Центру Солнца',
            'Город Спящих Мечтаний',
            'Хранители Времени',
            'Секрет Седьмого Моря',
            'Край Вселенной',
            'Под Влиянием Луны',
            'Дорога Без Возврата',
            'Когда Проснется Легенда',
            'Искра Надежды',
            'Забытый Протокол',
            'Зеркало Души',
            'Остров Потерянных Снов',
            'Врата Судьбы',
            'Хроники Неизведанного',
        ];

        foreach ($movies as $title) {
            Movie::create([
                'title' => $title,
                'overview' => 'Это сгенерированное описание для фильма "' . $title . '".',
                'poster_path' => null,
                'vote_average' => round(mt_rand(50, 95) / 10, 1),
                'type' => 'movie',
            ]);
        }
    }
}
