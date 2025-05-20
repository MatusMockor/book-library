@extends('dashboard.master')

@section('title', 'Books')

@section('content')
<div id="app">
    <book-list
        :index-url="'{{ route('api.books.index') }}'"
        :store-url="'{{ route('books.store') }}'"
        :show-url="'{{ route('books.show', ['id' => '__id__']) }}'"
        :update-url="'{{ route('books.update', ['id' => '__id__']) }}'"
        :delete-url="'{{ route('books.destroy', ['id' => '__id__']) }}'"
        :toggle-borrowed-url="'{{ route('books.toggle-borrowed', ['id' => '__id__']) }}'"
        :authors-url="'{{ route('api.authors.index') }}'"
    ></book-list>
</div>
@endsection
