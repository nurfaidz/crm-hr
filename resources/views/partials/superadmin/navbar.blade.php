<nav class="header-navbar navbar navbar-expand-lg align-items-center floating-nav navbar-custom shadow-none">
    <div class="navbar-container content px-0">
        <div class="row align-items-center m-auto">
            
            <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 col-8 p-0">
                <div class="col-12 align-items-center m-auto">
                    <div class="row">
                        <div class="col-lg-3 col-md-1 col-sm-1 col-1 m-auto px-0">
                            <img class="logo" src="/img/dashboard/logo.svg"
                                    alt="hamburger"></a>
                        </div>
                        
                        <div
                            class="col-xl-9 col-lg-11 col-md-11 col-sm-11 col-11 bg-white m-auto px-0 shadow rounded-lg">
                            <div class="input-group row m-0">
                                <div class="input-group-prepend col-sm-1 col-2 px-lg-1 p-0">
                                    <span class="input-group-text border-0 rounded-lg" id="basic-addon1"><img
                                            src="/img/icons/search.svg" alt="search"></span>
                                </div>
                                <input type="text" class="form-control-lg col-sm-11 col-10 border-0"
                                    placeholder="Search feature..." data-search="search">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-2 col-sm-2 col-4 p-0">
                <ul class="nav navbar-nav align-items-center justify-content-end m-auto">
                    <li class="nav-item dropdown dropdown-notification pr-1"><a class="nav-link"
                            href="javascript:void(0);" data-toggle="dropdown"><img src="/img/icons/notification.svg"
                                alt="notification"><span class="badge badge-pill badge-danger badge-up span-count-new">0</span></a>
                        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
                            <li class="dropdown-menu-header">
                                <div class="dropdown-header d-flex">
                                    <h4 class="notification-title mb-0 mr-auto">Notifications</h4>
                                    <div id="count-read" class="badge badge-pill badge-light-primary"><span class="span-count-new">0</span> New</div>
                                </div>
                            </li>
                            <li id="list-notifications" class="scrollable-container media-list">
                                <a class="d-flex" href="javascript:void(0)">
                                    <div class="media d-flex align-items-center justify-content-center flex-column">
                                        <div><img src="{{asset('img/icons/notifications_off.svg')}}" alt="no-notif" width="96" height="96"></div>
                                        <div class="text-center">
                                            <p class="media-heading my-0 mt-2"><span class="font-weight-bolder text-dark">No Notification</span></p>
                                            <small class="text-dark">We'll notify you when something arrives</small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                            <li class="dropdown-menu-footer">
                                <button id="btn-read-notif-all" class="btn btn-primary btn-block disabled">Read all notifications</button>
                            </li>
                        </ul>
                    </li>
                <li class="nav-item dropdown dropdown-user" id="nav-user">
                    <a class="nav-link dropdown-toggle dropdown-user-link" id="dropdown-user"
                        href="javascript:void(0);" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        @php
                            $data = Auth::user()
                                ->join('employees', 'users.id', '=', 'employees.user_id')
                                ->where('user_id', Auth::user()->id)
                                ->first();
                            
                            $imageSrc = $data->image == null || $data->image == '' ? asset('img/profile.png') : asset('uploads/' . $data['image']);
                        @endphp
                        <span class="avatar">
                            <img class="round" id="profile_image" src="{{ $imageSrc }}" alt="avatar"
                                height="40" width="40">
                            <span class="avatar-status-online"></span>
                        </span>
                        <div class="user-nav d-lg-flex d-none align-items-start pl-1">

                            <span
                                class="user-name font-weight-bolder mx-0">{{ $data->first_name . ' ' . $data->last_name }}</span>
                            <span class="user-status">
                                {{ isset(Auth::user()->roles[0]->name) ? Auth::user()->roles[0]->name : '' }}</span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown-user"
                        style="width:200px">
                        <a class="dropdown-item" href="{{ url('profile') }}"><i class="mr-50"
                                data-feather="user"></i> Profile</a>
                        <a class="dropdown-item" href="{{ url('changePassword') }}"><i class="mr-50"
                                data-feather="lock"></i>Change Password</a>
                        <a class="dropdown-item" href="app-email.html"><i class="mr-50" data-feather="mail"></i>
                            Inbox</a>
                        <a class="dropdown-item" href="app-todo.html"><i class="mr-50"
                                data-feather="check-square"></i> Task</a>
                        <a class="dropdown-item" href="app-chat.html"><i class="mr-50"
                                data-feather="message-square"></i>
                            Chats</a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="page-account-settings.html"><i class="mr-50"
                                data-feather="settings"></i>Settings</a>
                        <a class="dropdown-item" href="page-pricing.html"><i class="mr-50"
                                data-feather="credit-card"></i> Pricing</a>
                        <a class="dropdown-item" href="page-faq.html"><i class="mr-50"
                                data-feather="help-circle"></i> FAQ</a>
                        <a class="dropdown-item" href="{{ url('logout') }}"><i class="mr-50"
                                data-feather="power"></i>Logout</a>
                        <ul class="menu-content">

                        </ul>
                    </div>
            </div>
        </div>
</nav>