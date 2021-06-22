@extends('layouts.store')

@section('content')
    <x-menu>
        @include('includes.menu-categories')
        @include('includes.menu-categories-select')
        <div class="w-full mt-4 text-coolGray-700 text-4xl flex items-center justify-center font-semibold">
            Choose a category
        </div>
    </x-menu>
@endsection
