@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">Введите код подтверждения</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('password.code.verify') }}" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') }}">

                        <div class="mb-3">
                            <label for="code" class="form-label">Код из письма</label>
                            <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" required autofocus>
                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Проверить код</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
