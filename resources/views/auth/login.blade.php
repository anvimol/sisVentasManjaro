@extends('auth.contenido')

@section('login')
<div class="row justify-content-center">
<div class="col-md-8">
    @if ($errors->any())
    <div class="alert alert-danger">
        <h5>Por favor corrige los errores</h5>
        {{-- <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul> --}}
    </div> 
    @endif
    <div class="card-group mb-0">
    <div class="card p-4">
    <form class="form-horizontal was-validate" method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}   
        <div class="card-body">
        <h1>Acceder</h1>
        <p class="text-muted">Control de acceso al sistema</p>
        <div class="input-group mb-3 {{$errors->has('usuario' ? 'is-invalid' : '')}}">
            <span class="input-group-addon"><i class="icon-user"></i></span>
        <input type="text" value="{{old('usuario')}}" name="usuario" id="usuario" class="@error('usuario') is-invalid @enderror form-control" placeholder="Usuario">
            {{-- {!!$errors->first('usuario','<span class="invalid-feedback">:message</span>')!!} --}}
            @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror    
        </div>
        @if ($errors->has('usuario'))
        <div class="alert alert-danger">
            <p>{{ $errors->first('usuario') }}</p>
        </div>
        @endif
        <div class="input-group mb-4  {{$errors->has('password' ? 'is-invalid' : '')}}">
            <span class="input-group-addon"><i class="icon-lock"></i></span>
            <input type="password" name="password" id="password" class="@error('password') is-invalid @enderror form-control" placeholder="Password">
            {{-- {!!$errors->first('password','<span class="invalid-feedback">:message</span>')!!} --}}
            @error('title')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror 
        </div>
        <div class="row">
            <div class="col-6">
            <button type="submit" class="btn btn-primary px-4">Acceder</button>
            </div>
            <div class="col-6 text-right">
            <button type="button" class="btn btn-link px-0">Olvidaste tu password?</button>
            </div>
        </div>
        </div>
    </form>
    </div>
    <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
        <div class="card-body text-center">
        <div>
            <h2>Sistema de Ventas IncanatoIT</h2>
            <p>Sistema de compras, Ventas desarrollado en PHP utilizando el Framework Laravel y Vue Js, con el gestor de base de datos MariaDB.</p>
            <a href="https://www.udemy.com/user/juan-carlos-arcila-diaz/" target="_blank" class="btn btn-primary active mt-3">Ver el curso!</a>
        </div>
        </div>
    </div>
    </div>
</div>
</div>
@endsection
