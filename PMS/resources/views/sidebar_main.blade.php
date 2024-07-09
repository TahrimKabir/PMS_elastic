<div class="sidebar-main" style="display: flex;justify-content:center; flex-direction:column;align-items:center;">
                        <img src="{{asset('/images/profile_user.png')}}" alt="" style="height:100px; width:100px;">
                        <div style="margin-top: 3vh;">
                            <a href="{{asset('/dashboard')}}" style="color: blue;">Home</a>
                        </div>
                        <div style="margin-top: 3vh;">
                            <a href="{{asset('edit/'.session('user')['email'])}}" style="color: blue;">Change Information</a>
                        </div>
                        @if(Session::get('user_role')!='admin')
                        <div style="margin-top: 3vh;">
                            <a href="{{asset('delete/'.session('user')['email'])}}" style="color: blue;">Delete profile</a>
                        </div>
                        @endif
                        @if(Session::get('user_role')==='admin')
                        <div style="margin-top: 3vh;">
                            <a href="{{asset('/user-list')}}" style="color: blue;">User List</a>
                        </div>
                        @endif
                    </div>