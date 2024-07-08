<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="scss/reset.css">
    <link rel="stylesheet" href="scss/dashboard.css">
</head>
<style>
    table,
    th,
    td {
        border: 1px solid gray;

    }

    table {
        width: 100%;
    }

    th,
    td {
        padding: 0.35rem;
        text-align: center;
    }

    th {
        background-color: #032966;
        color: white;
    }
    .postbar{
        width: 100%!important;
        .post{
            overflow: scroll;
            a{
                text-decoration: none;
                border: 1px solid #032966;
                padding: 0.12rem;
            }
        }
    }
</style>

<body>
    <div class="container-full">
        <div class="container-header" style="display: flex;">
            @if(session()->has('user'))
            <!-- Accessing session data using the session() helper function -->
            <!-- <p>User Email: {{ session('user')['email'] }}</p> -->
            <p>logged in as <span style="color: white;">{{ session('user')['name'] }}</span></p>
            @endif
            <form action="{{route('logout')}}" method="POST">
                @csrf
                <button>logout</button>
            </form>

            <a href="{{asset('edit/'.session('user')['email'])}}">Edit</a>
            <a href="{{asset('delete/'.session('user')['email'])}}">Delete</a>
        </div>
        <div class="container-row">
            <div class="container-center">
                <!-- <div class="sidebar">
                    <div class="sidebar-main">
                        <img src="" alt="">
                    </div>
                </div> -->
                <div class="postbar">
                    <!-- <button id="add" style="display: block;">post here</button> -->


                    <div class="post">
                       <h1 style="text-align: center; display:block; color:#032966; font-size:large;font-weight:bold;margin-bottom:1rem;">User List</h1>
                        @if(!empty($userList))
                        <table>
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userList as $user)
                                <tr>
                                    <td>{{ $user['email'] }}</td>
                                    <td>{{ $user['name'] }}</td>
                                    <td>
                                        <a href="{{asset('edit/'.$user['email'])}}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
</svg>
                                        </a>
                                        <a href="{{asset('delete/'.$user['email'])}}" style="margin-left:3px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
  <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
  <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
</svg>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        <p>No users found.</p>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        var addButton = document.getElementById('add');

        addButton.addEventListener('click', function() {

            document.getElementById('form').style.display = 'block';


        });
    </script>
</body>

</html>