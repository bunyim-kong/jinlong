@extends('layouts.app')

@section('title', 'My Properties')
@section('page-title', 'My Properties')

@section('content')

<div class="w-full">
    <h1></h1>
    @foreach($nonCollection as $property)
        <h1>
            Name: {{$property->name}}
        </h1> <br><br>
        <h2>
            Type: {{$property->type}}
        </h2>
    @endforeach
    {{$nonCollection->links() }}
</div>

<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <form action="{{ route('properties.store') }}" method="post" class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input type="text" name="name" placeholder="Enter your name"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" placeholder="Enter your email"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input type="password" name="password" placeholder="********"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
        </div>

        <div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                Submit
            </button>
        </div>
    </form>
</div>

@endsection