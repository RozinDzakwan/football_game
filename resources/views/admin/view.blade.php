@extends('admin.layout')
@section('title')
    Admin
@endsection
@section('content')
<style>
    li :hover{
        background-color: #891a24!important;
        border-color: #891a24!important;
        color: white!important;
    }
</style>
    <div class="container-admin pt-5">
        <div class="content row justify-content-center">
            <div class="card-body" style="margin-top:60px">
                <form action="{{ route('admin') }}" method="GET">
                    <div class="row pb-5">
                        <div class="col-lg-4 col-6">
                            <label class="pb-2">Email</label>
                            <input type="text" class="form-control" name="email" value="{{ $email }}">
                        </div>
                        <div class="col-lg-3 col-6">
                            <label class="pb-2">Nickname</label>
                            <input type="text" class="form-control" name="nickname" value="{{ $nickname }}">
                        </div>
                        <div class="col-lg-2 col-3" style="place-self: end">
                            <button class="btn btn-info" style="padding: 10px 15px" type="submit"><i class="fas fa-search"></i> Search</button>
                        </div>
                        <div class="col-3" style="place-self: end; text-align:end">
                            <a href="{{ route('printPDF') }}" class="btn btn-success" style="padding: 10px 15px" type="submit"><i class="fas fa-print"></i> Export</a>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-striped-columns text-center">
                        <thead>
                            <th>Email</th>
                            <th>Nickname</th>
                            <th>Age</th>
                            <th>Team</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                            @if (count($data_users) <= 0)
                                <tr>
                                    <td colspan="5">Data empty</td>
                                </tr>
                            @else
                                @foreach ($data_users as $data_user)
                                    <tr>
                                        <td>{{ $data_user->email }}</td>
                                        <td>{{ $data_user->nickname }}</td>
                                        <td>{{ $data_user->age }}</td>
                                        <td>{{ $data_user->player_team }}</td>
                                        <td>
                                            <button class="btn btn-warning" id="edit-user" data-id="{{ $data_user->id }}">
                                                <i class="fas fa-edit"></i>
                                                Edit
                                            </button>
                                            <button class="btn btn-danger" id="delete-user" data-id="{{ $data_user->id }}">
                                                <i class="fas fa-trash"></i>
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <br>
                {{ $data_users->links('vendor.pagination.custom') }}
            </div>
        </div>
    </div>


    {{-- modal edit --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content-admin" style="color: black">
                <div class="modal-header-admin">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: black">Edit User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" id="id_user">
                    <div class="modal-body-admin">
                        <label class="pt-2 pb-1">Email</label>
                        <input type="text" name="email" id="email" class="form-control" required>
                        <label class="pt-2 pb-1">Nickname</label>
                        <input type="text" name="nickname" id="nickname" class="form-control" required>
                        <div class="row">
                            <div class="col-6">
                                <label class="pt-2 pb-1">Age</label>
                                <input type="number" name="age" id="age" class="form-control" min="1">
                            </div>
                            <div class="col-6">
                                <label class="pt-2 pb-1">Team</label>
                                <select name="player_team" id="player_team" class="form-select">
                                    @foreach ($data_teams as $name_team => $data)
                                        <option value="{{ $name_team }}">{{ $name_team }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer-admin">
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- modal delete --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content-admin" style="color: black">
                <div class="modal-header-admin">
                    <h1 class="modal-title fs-5" id="exampleModalLabel" style="color: black">Delete User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="deleteForm" method="GET">
                    @csrf
                    <input type="hidden" name="id" id="id_user">
                    <div class="modal-body-admin">
                        Are you sure to delete this data?
                    </div>
                    <div class="modal-footer-admin">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI="
        crossorigin="anonymous"></script>
    <script>
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });
        $(document).on("click", "#edit-user", function() {
            idReq = $(this).data("id");
            $("#editModal").modal("show");
            $.ajax({
                type: "GET",
                url: "/admin/index/edit/" + idReq,
                dataType: "JSON",
                success: function(response) {
                    $('#id_user').val(response.id);
                    $('#email').val(response.email);
                    $('#nickname').val(response.nickname);
                    $('#age').val(response.age);
                    $('#player_team').val(response.player_team);
                }
            });
        });
        $(document).on("click", "#delete-user", function() {
            idReq = $(this).data("id");
            $("#deleteModal").modal("show");
            $("#deleteForm").attr('action','/admin/index/delete/' + idReq);
        });
    </script>
@endsection
