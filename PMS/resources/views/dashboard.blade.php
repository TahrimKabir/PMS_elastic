<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="scss/reset.css">
    <link rel="stylesheet" href="scss/dashboard.css">
</head>

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
                <div class="sidebar">
                    <div class="sidebar-main">
                        <img src="" alt="">
                    </div>
                </div>
                <div class="postbar">
                    <button id="add" style="display: block;">post here</button>


                    <div class="post">

                        <form action="{{route('post')}}" method="post" style="display: none;" id="form">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('user')['email'] }}">
                            <textarea name="post" id="" rows="10">

                                 </textarea>
                            <button>post</button>
                        </form>

                    </div>

                    @foreach ($posts as $post)
                    <div class="post">
                        {{$post['email']}} 
                        <p>{{$post['post']}}</p>
                    </div>
                    @endforeach
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