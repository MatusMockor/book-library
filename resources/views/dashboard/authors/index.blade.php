@extends('dashboard.master')

@section('title', 'Authors')

@section('content')
<div id="app">
    <author-list
        :index-url="'{{ route('api.authors.index') }}'"
        :store-url="'{{ route('authors.store') }}'"
        :show-url="'{{ route('authors.show', ['id' => '__id__']) }}'"
        :update-url="'{{ route('authors.update', ['id' => '__id__']) }}'"
        :delete-url="'{{ route('authors.destroy', ['id' => '__id__']) }}'"
    ></author-list>
</div>
@endsection
