@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">О проекте</h2>
                </div>
                <div class="card-body">
                    <p class="lead">Меня зовут Чумаченко Павел Евгеньевич.</p>
                    <p>Данный проект разработан в рамках курсовой работы Академии ТОП.</p>
                    <hr>
                    <h4>Цель проекта</h4>
                    <p>Изучение и практическое применение современных веб-технологий, а также получение навыков разработки серверной части веб-приложений. В процессе работы реализованы основные функции сайта, включая обработку данных и взаимодействие с пользователем.</p>
                    <h4 class="mt-4">🛠 Используемые технологии</h4>
                    <ul class="list-group">
                        <li class="list-group-item">Laravel (PHP-фреймворк)</li>
                        <li class="list-group-item">HTML / CSS</li>
                        <li class="list-group-item">JavaScript</li>
                        <li class="list-group-item">SQLITE</li>
                    </ul>
                    <h4 class="mt-4">📞 Контакты</h4>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <i class="bi bi-envelope-fill me-2"></i>
                            <strong>Email:</strong> p.chumachenko.dev@gmail.com
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-phone-fill me-2"></i>
                            <strong>Телефон:</strong> +7 (912) 483-26-71
                        </li>
                        <li class="list-group-item">
                            <i class="bi bi-telegram me-2"></i>
                            <strong>Telegram:</strong> @pchumachenko_dev
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
