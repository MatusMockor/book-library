@extends('dashboard.master')

@section('title', 'Books')

@section('content')
<div id="app">
    <book-list
        :index-url="'{{ route('api.books.index') }}'"
        :store-url="'{{ route('api.books.store') }}'"
        :show-url="'{{ route('api.books.show', ['book' => '__id__']) }}'"
        :update-url="'{{ route('api.books.update', ['book' => '__id__']) }}'"
        :delete-url="'{{ route('api.books.destroy', ['book' => '__id__']) }}'"
        :toggle-borrowed-url="'{{ route('api.books.toggle-borrowed', ['book' => '__id__']) }}'"
        :authors-url="'{{ route('api.authors.index') }}'"
        :is-admin="{{ auth()->user()->is_admin ? 'true' : 'false' }}"
        :user-id="{{ auth()->id() }}"
    ></book-list>
</div>
@endsection
