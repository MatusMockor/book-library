@extends('dashboard.master')

@section('title', 'Authors')

@section('content')
<div id="app">
    <author-list
        :index-url="'{{ route('api.authors.index') }}'"
        :store-url="'{{ route('api.authors.store') }}'"
        :show-url="'{{ route('api.authors.show', ['author' => '__id__']) }}'"
        :update-url="'{{ route('api.authors.update', ['author' => '__id__']) }}'"
        :delete-url="'{{ route('api.authors.destroy', ['author' => '__id__']) }}'"
        :is-admin="{{ auth()->user()->is_admin ? 'true' : 'false' }}"
    ></author-list>
</div>
@endsection
