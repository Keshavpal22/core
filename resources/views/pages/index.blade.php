@extends('layouts.main')
@section('title', 'Books')

@section('content')
<div class="container-fluid">

    <x-ag-grid.base-grid
        gridId="booksGrid"
        :columns="$columns"
        :rows="$books"
        viewRoute="books.show"
        editRoute="books.edit"
    />
</div>
@endsection
