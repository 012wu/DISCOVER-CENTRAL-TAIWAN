<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>中彰投生活圈/後臺管理系統登入介面</title>
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/front.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">


</head>

<body>

    <div class="login-split" id="app">
        {{-- 登入說明區塊(左側) login-left --}}
        <div class="login-left">
            <div class="login-box">
                {{-- 標題 --}}
                <div class="title">
                    <h1 class="">歡迎使用</h1>
                    <h1 class="">後臺管理系統</h1>
                </div>
                {{-- 介面說明 --}}
                <p>在這裡您可以管理中彰投旅遊平臺的景點資料，新增、編輯或刪除景點，並查看即時統計報表。</p>
            </div>

            {{-- 版權聲明 --}}
            <small class="copyrightLogin text m-2 ms-5">© 2025 中彰投生活圈 · 後臺管理系統</small>
        </div>
        {{-- 輸入帳密區域 (右側) login-right--}}
        <div class="login-right">
            {{-- 標題 --}}
            <div class="login-keyin">
                <a href="/home"><i class="fa-solid fa-left-long"></i> 返回首頁</a>
                <h5 class="">管理員登入</h5>
                <small class="">請輸入您的帳號與密碼</small>
                {{-- 帳密輸入區域 --}}
                <div class="text mt-3 mb-5">
                    <div class="login-form">
                        {{-- 帳號，未輸入紅字預設為is-valid --}}
                        {{-- autocomplete詢問是否要儲存輸入的帳號密碼 --}}
                        <div class="account-keyin">
                            <label for="account" class="">帳號<span class=" text-danger">*</span></label>
                            <input id="account" type="text" class="form-control" placeholder="輸入帳號" v-model="account"
                                :class="{'is-invalid' : !account, 'is-valid': account}" autocomplete="username">
                            <div class=" invalid-feedback">請輸入帳號
                            </div>
                        </div>
                        {{-- 密碼，未輸入紅字預設為is-valid--}}
                        {{-- mt-3調整與帳號的間距 --}}
                        {{-- autocomplete詢問是否要儲存輸入的帳號密碼 --}}
                        <div class="pwd-keyin">
                            <label for="pwd" class="mt-3" style="color: var(--primary-green);">密碼<span
                                    class="text-danger">*</span></label>
                            {{-- type改為password，讓將密碼隱藏 （資安考量） --}}
                            <input id="pwd" type="password" class="form-control" placeholder="輸入密碼" v-model="pwd"
                                :class="{'is-invalid' : !pwd , 'is-valid' : pwd}" autocomplete="current-password">
                            <div class="invalid-feedback mb-3">請輸入密碼</div>
                        </div>
                        {{-- 登入按鈕 --}}
                        {{-- :disabled設定帳密都要輸入後按鈕才可以點擊，且css設定disabled顏色；驗證資料時會顯示驗證中 --}}
                        <button class="btn-login form-control mt-3" :disabled="!account || !pwd || loading"
                            @click="login">
                            [[loading ? "驗證中" : "登入"]]
                        </button>
                    </div>


                </div>
            </div>


        </div>
    </div>


    <!-- Font Awesome -->
    <script src="{{ asset('js/all.min.js') }}"></script>
    <!-- bootstrap -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <!-- jquery -->
    <script src="{{ asset('js/jquery-4.0.0.min.js') }}"></script>
    <!-- vue -->
    <script src="{{ asset('js/vue.global.min.js') }}"></script>
    <!-- sweetalert2 -->
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>

    {{-- vue 語法--}}
    <script>
        const App = {
            delimiters: ['[[', ']]'], // Vue 才會認得 [[ ]] 這個符號，為了避免vue跟blade誤用
            data() {
                return {

                    account: "",
                    pwd: "",
                    loading: false,
                }
            },
            mounted() {

            },
            methods: {
                login() {
                    const vm = this;
                    // 點擊登入按鈕後顯示驗證中
                    vm.loading = true
                    // 進入後台確認帳密是否正確
                    $.ajax({
                        url: "/admin/login",
                        type: "POST",
                        dataType: "json",
                        data: {
                            account: vm.account,
                            pwd: vm.pwd,
                            _token: "{{csrf_token()}}",
                        },

                        //驗證成功
                        success: function(response) {
                            console.log(response);
                            vm.loading = false;

                            if (response.success) {
                                // 登入成功，跳到後台首頁
                                location.href = "/admin/adminhome"
                                // 失敗顯示訊息回到原狀態
                            } else {
                                Swal.fire({
                                    title: "驗證失敗",
                                    text: "請重新確認帳號密碼",
                                    icon: "error"
                                });
                                vm.loading = false;
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
                            vm.loading = false;

                        }
                    });
                }
            },
            computed: {

            },
            watch: {

            }


        }


        Vue.createApp(App).mount("#app")
    </script>


</body>

</html>