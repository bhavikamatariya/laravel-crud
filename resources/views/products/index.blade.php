@extends('products.layout')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Products</h2>
                </div>
                <div class="pull-right">
                    <a class="btn btn-success" href="{{ route('products.create') }}">Create New Product</a>
                </div>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <table class="table table-bordered">
            <tr>
                <th>Id</th>
                <th>Image</th>
                <th>Title</th>
                <th>Category</th>
                <th>Description</th>
                <th width="280px">Action</th>
            </tr>

            @foreach($products as $product)
                <tr>
                    <td>{{ ++$i }}</td>
                    <td>
                        @if (!empty($product->image))
                            <img width="50px" src="{{ URL::asset('/public/images/' . $product->image) }}">
                        @else
                            <img width="50px" src="{{ URL::asset('/public/images/noimage.png') }}">
                        @endif
                    </td>
                    <td>{{ $product->title }}</td>
                    <td>{{ $product->category_title }}</td>
                    <td>{{ $product->description }}</td>
                    <td>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                            <a class="btn btn-info" href="{{ route('products.show', $product->id) }}">Show</a>
                            <a class="btn btn-primary" href="{{ route('products.edit', $product->id) }}">Edit</a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

        {!! $products->links() !!}

    </div>
@endsection
