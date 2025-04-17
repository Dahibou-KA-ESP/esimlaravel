
@extends('layouts.authentication.master')
@section('title', 'Login-one')

@section('css')
@endsection

@section('style')
@endsection

@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-xl-7"><img class="bg-img-cover bg-center" src="{{asset('assets/images/login/6.svg')}}" alt="looginpage"></div>
      <div class="col-xl-5 p-0">
         <div class="login-card">
            <div>
               <div><a class="logo text-start" href=""><center><img class="img-fluid for-light" style="width: 30%;" src="{{ asset('assets/images/platine.png') }}" alt="looginpage"></center><img class="img-fluid for-dark" src="{{asset('assets/images/logo/logo_dark.png')}}" alt="looginpage"></a></div>
               <div class="login-main">
               @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                  <form class="theme-form" method="POST" action="{{ route('login') }}">
                    @csrf
                     <h4>Connectez-vous</h4>
                     <p>Entrer votre email et votre mot de passe</p>
                     <div class="form-group">
                        <label class="col-form-label">E-mail</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                     </div>
                     <div class="form-group">
                        <label class="col-form-label">Mot de passe</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        <div class="show-hide"></div>
                     </div>
                     <div class="form-group mb-0">
                        <div class="checkbox p-0">
                           <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                           <label class="text-muted" for="checkbox1">Se souvenir</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                     </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@section('script')  
@endsection