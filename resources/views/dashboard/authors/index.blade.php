@extends('dashboard.master')

@section('title', 'Authors')

@section('content')
<div id="app">
    <author-list
        :index-url="'{{ route('api.authors.index') }}'"
        :store-url="'{{ route('authors.store') }}'"
        :show-url="'{{ route('authors.show', ['author' => '__id__']) }}'"
        :update-url="'{{ route('authors.update', ['author' => '__id__']) }}'"
        :delete-url="'{{ route('authors.destroy', ['author' => '__id__']) }}'"
        :is-admin="{{ auth()->user()->is_admin ? 'true' : 'false' }}"
    ></author-list>
</div>
@endsection
