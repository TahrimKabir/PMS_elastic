<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('scss/reset.css')}}">
    <link rel="stylesheet" href="{{asset('scss/index.css')}}">
</head>

<body>
    <div class="container-full">
        <div class="container-header">
        @if(session()->has('user'))
            <!-- Accessing session data using the session() helper function -->
            <!-- <p>User Email: {{ session('user')['email'] }}</p> -->
            <p>logged in as <span style="color: white;">{{ session('user')['name'] }}</span></p>
            @endif
            <form action="{{route('logout')}}" method="POST">
                @csrf
                <button>logout</button>
            </form>
        </div>
        <div class="container-row">
            <div class="profile-image">
                <img src="{{asset('images/profile1.jpg')}}" alt="">
            </div>
            <div class="register">
                <form action="{{ route('updateUser') }}" method="post">
                    @csrf
                    <div class="rf">
                        <h1 class="clr-blue">Edit <span class="clr-dblue">your</span> <span class="clr-d1blue">profile</span></h1>
                    </div>
                    <div class="rf-input">
                        <div class="punit">
                            <input type="text" name="name" id="" placeholder="Enter your name" value="{{ $user['name'] }}">
                        </div>
                    </div>
                    <div class="rf-input">
                        <div class="punit">
                            <input type="email" name="email" id="" placeholder="Enter your email" value="{{ $user['email'] }}">
                        </div>
                    </div>
                    <div class="rf-input">
                        <div class="punit">
                            <input type="password" name="password" id="" placeholder="Enter your Password">
                        </div>
                    </div>
                    <!-- <div class="rf-input">
                    <div class="punit">
                        <input type="password" name="email" id="" placeholder="Enter your email" value="{{ $user['email'] }}">
                    </div>
                </div> -->
                    <div class="rf-input">
                        <div class="punit">
                            <button type="submit">Update</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
        <!-- <div class="container-footer">
            hi
        </div> -->
    </div>
</body>

</html>