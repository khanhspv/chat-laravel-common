@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"> Chat Room </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif


                        @foreach ($users as $key => $user)
                            @if (auth()->user()->id != $user->id)
                                <a href="{{route('room-chat', ['id' => $user->id])}}" class="btn btn-outline-secondary">
                                    {{ $user->name }}
                                </a>
                            @endif
                        @endforeach


                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
