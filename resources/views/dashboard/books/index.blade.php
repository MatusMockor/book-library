@extends('dashboard.master')

@section('title', 'Books')

@section('content')
<div id="app">
    <book-list
        :index-url="'{{ route('api.books.index') }}'"
        :store-url="'{{ route('books.store') }}'"
        :show-url="'{{ route('books.show', ['book' => '__id__']) }}'"
        :update-url="'{{ route('books.update', ['book' => '__id__']) }}'"
        :delete-url="'{{ route('books.destroy', ['book' => '__id__']) }}'"
        :toggle-borrowed-url="'{{ route('books.toggle-borrowed', ['book' => '__id__']) }}'"
        :authors-url="'{{ route('api.authors.index') }}'"
        :is-admin="{{ auth()->user()->is_admin ? 'true' : 'false' }}"
    ></book-list>
</div>
@endsection
