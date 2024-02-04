<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" >
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <link  href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb ">
                <div class="pull-left mb-3 text-center ">
                    <h1>Products CRUD</h1>
                </div>
                <div class="pull-right mb-3">
                    <a class="btn btn-success" onclick="add()" href="javascript:void(0)">Create Product</a>
                </div>
            </div>
        </div>

        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif

        <div class="card-body">
            <table class="table table-bordered" id="products">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>


    <!-- Bootstrap Product Modal -->
    <div class="modal fade" id="product-modal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0)" id="ProductForm" name="ProductForm" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Product Name</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter product name" maxlength="50" required="">
                            </div>
                        </div>  
                        <div class="form-group">
                            <label for="name" class="col-sm-2 control-label">Quantity</label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" id="qty" name="qty" placeholder="Enter the quantity" maxlength="50" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="price" name="price" placeholder="Enter price" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="description" name="description" placeholder="Enter description" required="">
                            </div>
                        </div>
                        <div class="col-sm-offset-2 col-sm-10"><br/>
                            <button type="submit" class="btn btn-primary" id="btn-save">Add Product</button>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>
    <!-- End Bootstrap Product Modal -->

    
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#products').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ url('products') }}",
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'qty', name: 'qty'},
                    {data: 'price', name: 'price'},
                    {data: 'description', name: 'description'},
                    {data: 'action', name: 'action', orderable: false},
                ],
                order: [[0, 'asc']]
            });
        });

        function add() {
            $('#ProductForm').trigger("reset");
            $('#ProductModal').html("Add Product"); 
            $('#product-modal').modal('show'); 
            $('#id').val('');
        }

        function editFunc(id) {
            $.ajax({
                type: "POST",
                url: "{{url('edit')}}",
                data: {id: id},
                dataType: "json",
                success: function(res){
                    console.log(res);
                    $('#ProductModal').html('Edit Product');
                    $('#product-modal').modal('show');
                    $('#id').val(res.id);
                    $('#name').val(res.name);
                    $('#qty').val(res.qty);
                    $('#price').val(res.price);
                    $('#description').val(res.description);
                }
            });
        }

        function deleteFunc(id){
            if(confirm("Delete Record?") == true){
                var id = id;
                $.ajax({
                    type: "POST",
                    url: "{{url('delete')}}",
                    data: {id: id},
                    dataType: "json",
                    success: function(res){
                        var oTable = $('#products').dataTable();
                        oTable.fnDraw(false);
                    }
                })
            }
        }

        $('#ProductForm').submit(function (e) {
            e.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                type: 'POST',
                url: "{{ url('store') }}",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    $("#product-modal").modal('hide');
                    var oTable = $('#products').dataTable();
                    oTable.fnDraw(false);
                    $("#btn-save").html('Submit');
                    $("#btn-save").attr("disabled", false);
                },
                error: function (data) {
                    console.log(data);
                }
            });
        });
    </script>
</body>
</html>
