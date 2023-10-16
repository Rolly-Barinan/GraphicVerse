@extends('layouts.adminlayout')

@section('admin-content')

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-right mb-4">
                    <h2>
                        Categories
                        <span style="margin: 0 25px;"> <!-- Add space between icons -->
                            <a href="#" data-bs-toggle="modal" data-bs-target="#addCategoryModal" style="cursor: pointer; text-decoration: none;">
                                <i class="fas fa-plus-square"></i> <!-- Font Awesome plus icon -->
                            </a>
                        </span>
                    </h2> 
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    
        <div class="card rounded" style="border-left: 10px solid #333;">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->cat_name }}</td>
                                    <td>{{ $category->created_at->format('Y-m-d') }}</td>
                                    <td class="text-center">
                                        <a href="#" style="text-decoration: none; color: green;" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                            <i class="fas fa-edit"></i> <!-- Font Awesome edit icon -->
                                        </a>
                                        <span style="margin: 0 10px;"> <!-- Add space between icons -->
                                            <a href="{{ route('admin.deleteCategory', $category->id) }}" onclick="return confirm('Are you sure you want to delete this category?')" style="text-decoration: none; color: red;">
                                                <i class="fas fa-trash-alt"></i> <!-- Font Awesome delete icon -->
                                            </a>
                                        </span>
                                    </td>                                               
                                </tr>

                                <!-- Modal for Editing Category -->
                                <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="editCategoryModalLabel{{ $category->id }}">Edit Category</h5>
                                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form method="post" action="{{ route('admin.updateCategory', $category->id) }}">
                                                @csrf
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="category">Category name:</label>
                                                        <input type="text" id="category" name="category" value="{{ $category->cat_name }}" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update Category</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pagination Links -->
        <div class="d-flex justify-content-center mt-4">
            {{ $categories->links('pagination::bootstrap-4') }}
        </div>


        <!-- Add Category Modal -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCategoryModalLabel">Add a Category</h5>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form method="POST" action="{{ route('admin.addCategory') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="category">Category name:</label>
                                <input type="text" id="category" name="category" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Add Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        

    </div>
</body>
@endsection