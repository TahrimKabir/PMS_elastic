<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="scss/reset.css">
    <link rel="stylesheet" href="scss/index.css">
</head>

<body>
    <div class="container-full">
        <div class="container-header">
            hi
        </div>
        <div class="container-row">
            <div class="profile-image">
                <img src="{{asset('images/profile1.jpg')}}" alt="">
            </div>
            <div class="register">
                <form action="{{route('store')}}" method="post">
                    @csrf
                <div class="rf">
                    <h1 class="clr-blue">Register <span class="clr-dblue">your</span> <span class="clr-d1blue">profile</span></h1>
                </div>
                <div class="rf-input">
                    <div class="p1">
                        <input type="text" name="fname" id="" placeholder="First Name">
                    </div>
                    <div class="p2">
                        <input type="text" name="lname" id="" placeholder="Last Name">
                    </div>
                </div>
                <div class="rf-input">
                    <div class="punit">
                        <input type="email" name="email" id="" placeholder="Enter your email">
                    </div>
                </div>
                <div class="rf-input">
                    <div class="p1">
                        <input type="password" name="pass1" id="" placeholder="Enter Password">
                    </div>
                    <div class="p2">
                        <input type="password" name="pass2" id="" placeholder="Confirm Password">
                    </div>
                </div>
                <div class="rf-input">
                    <div class="punit">
                        <button type="submit">Register</button>
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