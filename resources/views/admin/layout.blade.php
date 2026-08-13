<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '後臺管理系統')</title>
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="../css/all.min.css">
    {{-- bootstrap --}}
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    {{-- google front --}}
    <link rel="stylesheet" href="../css/front.css">
    <link href="https://fonts.googleapis.com/css2?family=Inder&family=Noto+Sans+TC:wght@100..900&display=swap"
        rel="stylesheet">
    {{-- 後端專用css，figma網頁圖片丟claud產出，並依照實際畫面調整 --}}
    <link rel="stylesheet" href="../css/admin.css">

</head>

<body>
    <div class="admin_home" id="app">
        <div class="admin-topbar">
            <h4 class="me-auto">後臺管理系統</h4>
            {{-- router-link點擊導到首頁 --}}
            <a href="/home"><i class="fa-solid fa-left-long"></i> 返回首頁</a>
            @if (!empty(session()->get('account')))
            <button class="btn-logout" @click="logout">管理員登出</button>
            @endif
        </div>
        {{-- 將sidebar跟右邊內文並台再一起，父層為layout 用flex --}}
        <div class="admin-layout">
            {{-- 左側導覽列 --}}
            <div class="admin-sidebar">
                <button class="btn sidebar-title form-control"><a href="/admin/adminhome" class=""><i
                            class="fa-solid fa-chart-simple" style="color: rgb(52, 119, 55);"></i>
                        數據總覽</a></button>
                {{-- 下拉式選單固定在一起 --}}
                <div class="sidebar-nav mb-5" id="sidebar-nav">
                    {{-- 希望點開後下面按鈕會向下效果，所以使用collapse而非dropdowns --}}
                    {{-- data-bs-target、aria-controls、id使用不同的命名才可以分開控制 --}}
                    {{-- 景點管理 --}}
                    <button class="btn sidebar-nav-btn" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseAttraction" aria-expanded="false" aria-controls="collapseAttraction">
                        景點管理<i class="fa-solid fa-chevron-up" style="color: rgb(255, 255, 255);"></i>
                    </button>
                    <ul class="collapse sidebar-submenu" id="collapseAttraction">
                        <li><a class="dropdown-item" href="/admin/attraction">景點列表</a></li>
                        <li><a class="dropdown-item" href="/admin/attractionClass">景點分類</a></li>
                    </ul>
                    {{-- 旅宿管理 --}}
                    <button class="btn sidebar-nav-btn" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseHotel" aria-expanded="false" aria-controls="collapseHotel">
                        旅宿管理<i class="fa-solid fa-chevron-up" style="color: rgb(255, 255, 255);"></i>
                    </button>
                    <ul class="collapse sidebar-submenu" id="collapseHotel">
                        <li><a class="dropdown-item" href="/admin/hotel">旅宿列表</a></li>
                        <li><a class="dropdown-item" href="/admin/hotelClass">旅宿分類</a></li>
                    </ul>
                    {{-- 餐飲管理 --}}
                    <button class="btn sidebar-nav-btn" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseRestaurant" aria-expanded="false" aria-controls="collapseRestaurant">
                        餐飲管理<i class="fa-solid fa-chevron-up" style="color: rgb(255, 255, 255);"></i>
                    </button>
                    <ul class="collapse sidebar-submenu" id="collapseRestaurant">
                        <li><a class="dropdown-item" href="/admin/restaurant">餐飲列表</a></li>
                        <li><a class="dropdown-item" href="/admin/restaurantClass">餐飲分類</a></li>
                    </ul>
                </div>
                {{-- fixed-bottom固定在下方 --}}

                <div class="sidebar-footer">
                    <hr>
                    <p class="pb-4">已登入
                        <br>{{ session('account') }}
                    </p>
                    <small>© 2025 中彰投生活圈 · 後臺管理系統</small>
                </div>
            </div>

            <div class="admin-content">
                @yield('content')
            </div>


        </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- 圖數據 --}}
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
    <script src="{{ asset('js/all.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery-4.0.0.min.js') }}"></script>
    <script src="{{ asset('js/vue.global.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>



    <script>
        const App = {
            delimiters: ['[[', ']]'], //為了避免vue跟blade誤用
            data() {
                return {
                    attractionList: [],
                    attractionClassList: [],
                    hotelList: [],
                    hotelClassList: [],
                    restaurantList: [],
                    restaurantClassList: [],
                }
            },
            mounted() {
                const vm = this;
                //指定路徑才開啟該function
                if (location.pathname === "/admin/attraction") {
                    vm.getAttractionList();
                }
                if (location.pathname === "/admin/attractionClass") {
                    vm.getAttractionClassList();
                }
                if (location.pathname === "/admin/hotel") {
                    vm.gethotelList();
                }
                if (location.pathname === "/admin/hotelClass") {
                    vm.gethotelClassList();
                }
                if (location.pathname === "/admin/restaurant") {
                    vm.getrestaurantList();
                }
                if (location.pathname === "/admin/restaurantClass") {
                    vm.getrestaurantClassList();
                }
            },
            methods: {
                logout() {
                    // 先把 this 存起來，因為進到 ajax 的 function 裡面 this 會改變，
                    // 所以要先存一份給 vm，之後在裡面才能繼續用 vm.xxx 存取 data 的資料
                    const vm = this;
                    $.ajax({
                        url: "/admin/logout",
                        method: "POST",
                        dataType: "json",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        //驗證成功
                        success: function(response) {
                            console.log(response);
                            vm.loading = false;

                            if (response.success) {
                                Swal.fire({
                                    title: "登出成功",
                                    text: "已登出",
                                    icon: "success",
                                    timer: 1000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.href = "/home";
                                });
                            } else {
                                Swal.fire({
                                    title: "登出失敗",
                                    text: "請稍後再試",
                                    icon: "error"
                                });
                            }

                        },
                        //系統錯誤
                        error: function(error) {
                            console.log(error);
                            Swal.fire({
                                title: "發生錯誤",
                                text: "請稍後再試",
                                icon: "error"
                            });

                        }

                    })
                },
                getAttractionList() {
                    const vm = this;

                    $.ajax({
                        url: "/api/attraction",
                        method: "GET",
                        dataType: "json",

                        success: function(response) {
                            if (response.success) {
                                vm.attractionList = response.msg.data;
                            }
                        },

                        error: function(xhr) {
                            console.log(xhr.status);
                            console.log(xhr.responseText);
                        }
                    });
                },
                getAttractionClassList() {
                    const vm = this;

                    $.ajax({
                        url: "/api/attractionClass",
                        method: "GET",
                        dataType: "json",

                        success: function(response) {
                            if (response.success) {
                                vm.attractionClassList = response.msg.data;
                            }
                        },

                        error: function(xhr) {
                            console.log(xhr.status);
                            console.log(xhr.responseText);
                        }
                    });
                },


                gethotelList() {
                    const vm = this;

                    $.ajax({
                        url: "/api/hotel",
                        method: "GET",
                        dataType: "json",

                        success: function(response) {
                            console.log(response);

                            if (response.success) {
                                vm.hotelList = response.msg.data;
                            }
                        },

                        error: function(error) {
                            console.log(error);

                            Swal.fire({
                                title: "取得資料失敗",
                                text: "請稍後再試",
                                icon: "error"
                            });
                        }
                    });
                },
                gethotelClassList() {
                    const vm = this;

                    $.ajax({
                        url: "/api/hotelClass",
                        method: "GET",
                        dataType: "json",

                        success: function(response) {
                            console.log(response);

                            if (response.success) {
                                vm.hotelClassList = response.msg.data;
                            }
                        },

                        error: function(error) {
                            console.log(error);

                            Swal.fire({
                                title: "取得資料失敗",
                                text: "請稍後再試",
                                icon: "error"
                            });
                        }
                    });
                },
                getrestaurantList() {
                    const vm = this;

                    $.ajax({
                        url: "/api/restaurant",
                        method: "GET",
                        dataType: "json",

                        success: function(response) {
                            console.log(response);

                            if (response.success) {
                                vm.restaurantList = response.msg.data;
                            }
                        },

                        error: function(error) {
                            console.log(error);

                            Swal.fire({
                                title: "取得資料失敗",
                                text: "請稍後再試",
                                icon: "error"
                            });
                        }
                    });
                },
                getrestaurantClassList() {
                    const vm = this;

                    $.ajax({
                        url: "/api/restaurantClass",
                        method: "GET",
                        dataType: "json",

                        success: function(response) {
                            console.log(response);

                            if (response.success) {
                                vm.restaurantClassList = response.msg.data;
                            }
                        },

                        error: function(error) {
                            console.log(error);

                            Swal.fire({
                                title: "取得資料失敗",
                                text: "請稍後再試",
                                icon: "error"
                            });
                        }
                    });
                }


            }
        }
        Vue.createApp(App).mount("#app");
    </script>
    @stack('scripts')

</body>

</html>