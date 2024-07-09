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