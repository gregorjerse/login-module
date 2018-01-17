@extends('layouts.admin')

@section('content')
    <form method="GET" action="{{ url('/admin/users') }}" class="form-inline">
        <div class="form-group">
            <label>User ID</label>
            <input type="text" class="form-control input-sm" name="id" value="{{ request()->input('id') }}"/>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="text" class="form-control input-sm" name="email" value="{{ request()->input('email') }}"/>
        </div>
        <div class="form-group">
            <label>Login</label>
            <input type="text" class="form-control input-sm" name="login" value="{{ request()->input('login') }}"/>
        </div>
        <div class="form-group">
            <label>First name</label>
            <input type="text" class="form-control input-sm" name="first_name" value="{{ request()->input('first_name') }}"/>
        </div>
        <div class="form-group">
            <label>Last  name</label>
            <input type="text" class="form-control input-sm" name="last_name" value="{{ request()->input('last_name') }}"/>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Find</button>
    </form>

    <hr/>

    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>ID</th>
                <th>Login</th>
                <th>Name</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->login }}</td>
                    <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                    <td>{{ $user->role }}</td>
                    <td>
                        <a href="{{ url('/admin/users/'.$user->id) }}" class="btn btn-xs btn-primary" title="View details">View</a>
                        <a href="{{ url('/admin/users/'.$user->id.'/emails') }}" class="btn btn-xs btn-primary" title="Send recovery email to user">Send recovery</a>
                        <a href="{{ url('/admin/users/'.$user->id.'/password') }}" class="btn btn-xs btn-primary" title="Change user password">Password</a>
                        <a href="{{ url('/admin/users/'.$user->id.'/teacher_status') }}" class="btn btn-xs btn-primary" title="Teacher status">Teacher status</a>
                        <form action="{{ url('/admin/users/'.$user->id) }}" method="POST" style="display: inline" role="delete">
                            {{ csrf_field() }}
                            <input name="_method" type="hidden" value="DELETE"/>
                            <button type="submit" class="btn btn-xs btn-danger" role="delete" title="Delete user permanently">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}

    <script type="text/javascript">
        $("form[role=delete]").submit(function(e) {
            if(!confirm('Are you sure you want to delete this user?')) {
                e.preventDefault();
                return false;
            }
        });
    </script>
@endsection