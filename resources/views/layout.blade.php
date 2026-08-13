<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '首頁')</title>
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="css/all.min.css">
    {{-- bootstrap --}}
    <link rel="stylesheet" href="css/bootstrap.min.css">
    {{-- google front --}}
    <link rel="stylesheet" href="css/front.css">
</head>

<body>
    <div class="front" id="app">
        {{-- 導覽列 --}}
        <section class="navbar-custom navbar navbar-expand-md" id="siteHeader">
            <a href="#" class="navbar-brand-custom" aria-label="中彰投生活圈首頁">
                <img src="img/logoothers.svg" class="logoothers" alt="中彰投生活圈">
            </a>
            <button class="navbar-toggler nav-toggle" type="button" data-bs-toggle="collapse"
                data-bs-target="#primaryNav" aria-controls="primaryNav" aria-expanded="false" aria-label="切換導覽選單">
                <span></span><span></span><span></span>
            </button>
            <div class="collapse navbar-collapse" id="primaryNav">
                <nav class="nav-link-custom" aria-label="主要導覽">
                    <a href="/home" class="{{ request()->is('home') ? 'active' : '' }}" {!! request()->is('home') ? 'aria-current="page"' : '' !!}>
                        <i class="fa-regular fa-house"></i>
                        <span>首頁</span>
                    </a>
                    <a href="/attraction" class="{{ request()->is('attraction') ? 'active' : '' }}"
                        {!! request()->is('attraction') ? 'aria-current="page"' : '' !!}>
                        <i class="fa-solid fa-location-dot"></i>
                        <span>景點</span>
                    </a>
                    <a href="/hotel" class="{{ request()->is('hotel') ? 'active' : '' }}" {!! request()->is('hotel') ? 'aria-current="page"' : '' !!}>
                        <i class="fa-solid fa-bed"></i>
                        <span>旅宿</span>
                    </a>
                    <a href="/restaurant" class="{{ request()->is('restaurant') ? 'active' : '' }}"
                        {!! request()->is('restaurant') ? 'aria-current="page"' : '' !!}>
                        <i class="fa-solid fa-utensils"></i>
                        <span>餐飲</span>
                    </a>
                </nav>
                {{-- 搜尋框 --}}
                <div class="search-box">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text"
                        placeholder="關鍵字搜尋"
                        v-model="keyword"
                        @input="searchData">
                </div>
            </div>

        </section>

        @yield('content')

        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item">
                    <a class="page-link" href="#" @click.prevent="changePage(currentPage - 1)">
                        上一頁
                    </a>
                </li>

                <li class="page-item" v-for="item in totalPage" :key="item"
                    :class="{ active: currentPage == item }">
                    <a class="page-link" href="#" @click.prevent="changePage(item)">
                        [[ item ]]
                    </a>
                </li>

                <li class="page-item">
                    <a class="page-link" href="#" @click.prevent="changePage(currentPage + 1)">
                        下一頁
                    </a>
                </li>
            </ul>
        </nav>

        <footer>
            <div class="container">
                <div class="footer-top">
                    <div class="footer-logo">
                        <img src="img/logolandingpage.svg" class="" alt="中彰投生活圈">
                    </div>
                    <div class="footer-links">
                        <a href="/about">關於我們</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#contactModal">聯絡我們</a>
                        @if (empty(session()->get('account')))
                        <a href="/admin/login" class="admin-link">管理員登入</a>
                        @endif
                        @if (!empty(session()->get('account')))
                        <a href="#" class="admin-link" @click="logout">管理員登出</a>
                        @endif
                        @if (!empty(session()->get('account')))
                        <a href="/admin/adminhome" class="admin-link">後臺管理系統</a>
                        @endif
                    </div>
                </div>
                <p class="copyright">© 2025 中彰投生活圈 · DISCOVER CENTRAL TAIWAN</p>
            </div>
        </footer>


        <button class="back-to-top" id="backToTop" aria-label="回到頂部">
            <i class="fa-regular fa-circle-up" style="color: rgb(97, 167, 146);"></i>
        </button>
        {{-- 聯絡我們彈跳視窗 --}}
        <div class="modal" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content contact-modal">
                    <div class="modal-header">
                        <div>
                            <div class="section-subtitle">CONTACT US</div>
                            <h2 class="modal-title" id="contactModalLabel"> 聯絡我們 </h2>
                        </div> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="section-title text-start mb-4"> 問題填寫表單 </div>
                        <form id="contactForm" novalidate>
                            <div class="mb-3"> <label for="cEmail" class="form-label"> Email </label> <input
                                    type="email" class="form-control" id="cEmail" placeholder="請輸入您的 Email"
                                    required>
                                <div class="invalid-feedback"> 請輸入正確的 Email </div>
                            </div>
                            <div class="mb-3"> <label for="cType" class="form-label"> 問題類型 </label> <select
                                    class="form-select" id="cType" required>
                                    <option value="" selected disabled> 請選擇問題類型 </option>
                                    <option value="網站問題"> 網站問題 </option>
                                    <option value="資料錯誤"> 資料錯誤 </option>
                                    <option value="功能建議"> 功能建議 </option>
                                    <option value="帳號問題"> 帳號問題 </option>
                                    <option value="其他"> 其他 </option>
                                </select>
                                <div class="invalid-feedback"> 請選擇問題類型 </div>
                            </div>
                            <div class="mb-3"> <label for="cSubject" class="form-label"> 主旨 </label> <input
                                    type="text" class="form-control" id="cSubject" placeholder="請輸入問題主旨"
                                    required>
                                <div class="invalid-feedback"> 請輸入主旨 </div>
                            </div>
                            <div class="mb-3"> <label for="cMessage" class="form-label"> 內文 </label>
                                <textarea class="form-control" id="cMessage" rows="6" placeholder="請詳細描述您遇到的問題..." required></textarea>
                                <div class="invalid-feedback"> 請輸入問題內容 </div>
                            </div>
                            <div class="text-end"> <button type="button" class="btn-submit-contact" id="submitBtn">
                                    送出
                                </button> </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script src="{{ asset('js/all.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery-4.0.0.min.js') }}"></script>
    <script src="{{ asset('js/vue.global.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>


    {{-- 抓活動資料 --}}
    <script>
        const App = {
            delimiters: ['[[', ']]'],
            data() {
                return {
                    keyword: "",
                    attractionList: [],
                    hotelList: [],
                    restaurantList: [],
                    filter: [],
                    //每頁顯示的筆數
                    pageSize: 15,
                    //目前頁數
                    currentPage: 1,
                    selectedCity: "",
                    selectedCategory: "",
                    selectedRank: "",

                }
            },
            mounted() {
                const vm = this;
                if (location.pathname === "/attraction") {
                    vm.getAttractionList();
                }
                if (location.pathname === "/hotel") {
                    vm.gethotelList();
                }
                if (location.pathname === "/restaurant") {
                    vm.getrestaurantList();
                }
                vm.searchData();
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
                                vm.filter = vm.attractionList;
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
                                vm.filter = vm.hotelList;
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
                                vm.filter = vm.restaurantList;
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


                changePage(page) {
                    const vm = this;
                    if (page < 1) return;
                    if (page > vm.totalPage) return;

                    this.currentPage = page;
                },
                searchData() {
                    const vm = this;
                    vm.currentPage = 1;
                    let source = [];
                    if (location.pathname === "/attraction") source = vm.attractionList;
                    else if (location.pathname === "/hotel") source = vm.hotelList;
                    else if (location.pathname === "/restaurant") source = vm.restaurantList;

                    vm.filter = source.filter(function(item) {
                        if (vm.selectedCity != "" && item.city != vm.selectedCity) return false;

                        const category = item.attractionClassName2 || item.hotelClassName || item
                            .restaurantClassName || "";
                        if (vm.selectedCategory != "" && category != vm.selectedCategory) return false;

                        if (vm.keyword != "") {
                            const keyword = vm.keyword.toLowerCase();
                            const name = item.attractionName || item.hotelName || item.restaurantName || "";
                            const description = item.description || "";
                            if (!name.toLowerCase().includes(keyword) &&
                                !description.toLowerCase().includes(keyword) &&
                                !category.toLowerCase().includes(keyword)) {
                                return false;
                            }
                        }
                        return true;
                    });

                    vm.filter.sort(function(a, b) {
                        if (vm.selectedRank === "最多瀏覽人次") {
                            const aViews = Number(a.viewCount || a.views || a.browseCount || a.view || 0);
                            const bViews = Number(b.viewCount || b.views || b.browseCount || b.view || 0);
                            return bViews - aViews;
                        }

                        if (vm.selectedRank === "名稱 (筆劃少到多)") {
                            const aName = a.attractionName || a.hotelName || a.restaurantName || "";
                            const bName = b.attractionName || b.hotelName || b.restaurantName || "";
                            return aName.localeCompare(bName, "zh-Hant", {
                                usage: "sort",
                                collation: "stroke"
                            });
                        }

                        if (vm.selectedRank === "最新上架") {
                            const aDate = a.created_at || a.createdAt || a.publishDate || a.createDate || "";
                            const bDate = b.created_at || b.createdAt || b.publishDate || b.createDate || "";
                            return new Date(bDate) - new Date(aDate);
                        }

                        return 0;
                    });
                },
                showDetail(item) {
                    const vm = this;
                    console.log(item);

                    // 依頁面類型取得對應的名稱欄位
                    const name = item.attractionName || item.hotelName || item.restaurantName || "-";

                    document.getElementById("detailName").textContent = name;
                    document.getElementById("detailLocation").textContent =
                        (item.city || "") + " · " + (item.town || "");

                    document.getElementById("detailImg").src = item.img1 || "";
                    document.getElementById("detailAddress").textContent = item.fullAddress || "-";
                    document.getElementById("detailTel").textContent = item.tel || "-";

                    document.getElementById("detailLatLng").textContent =
                        (item.positionLat || "-") + ", " + (item.positionLon || "-");

                    const websiteLink = document.getElementById("websiteLink");
                    if (websiteLink) {
                        websiteLink.href = item.websiteURL || "#";
                    }

                    // 防止其他介面沒有 detailPrice
                    const detailPrice = document.getElementById("detailPrice");
                    if (detailPrice) {
                        detailPrice.textContent =
                            (item.lowestPrice || "-") + " ~ " +
                            (item.ceilingPrice || "-");
                    }

                    document.getElementById("detailDesc").textContent =
                        item.description || "暫無介紹";

                    const relatedList = document.getElementById("relatedList");
                    relatedList.innerHTML = "";

                    // 依頁面判斷要從哪個清單找相關項目
                    let sourceList = [];
                    if (location.pathname === "/attraction") sourceList = vm.attractionList;
                    else if (location.pathname === "/hotel") sourceList = vm.hotelList;
                    else if (location.pathname === "/restaurant") sourceList = vm.restaurantList;

                    const related = sourceList.filter(function(other) {
                        return other.id != item.id && other.city == item.city;
                    }).slice(0, 4);

                    if (related.length == 0) {
                        relatedList.innerHTML = "<div class='text-muted'>附近暫無其他資料</div>";
                    } else {
                        related.forEach(function(other) {
                            const otherName = other.attractionName || other.hotelName || other.restaurantName ||
                                "-";
                            relatedList.innerHTML += `
        <div class="col-md-6">
            <a href="${other.websiteURL || '#'}" target="_blank" class="related-card">
                <img src="${other.img1 || ''}" alt="">
                <div class="related-body">
                    <div class="fw-bold">
                        ${otherName}
                    </div>
                    <small>
                        ${other.town || ''}
                    </small>
                </div>
            </a>
        </div>
    `;
                        });
                    }

                    const modal = new bootstrap.Modal(
                        document.getElementById("detailModal")
                    );
                    modal.show();
                },

            },
            computed: {
                totalPage() {
                    return Math.ceil(this.filter.length / this.pageSize);
                },

                pageData() {
                    const start = (this.currentPage - 1) * this.pageSize;
                    const end = start + this.pageSize;

                    return this.filter.slice(start, end);
                }
            },
        };
        Vue.createApp(App).mount("#app");
    </script>

    <script>
        // ---------- Scroll reveal ----------
        const revealEls = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in-view');
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.15
        });
        revealEls.forEach(el => observer.observe(el));
        // ---------- Header scroll shadow ----------
        const header = document.getElementById('siteHeader');
        const backToTop = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            const scrolled = window.scrollY > 30;
            header.classList.toggle('scrolled', scrolled);
            backToTop.classList.toggle('show', window.scrollY > 100);
        });

        backToTop.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // ---------- City pill selection ----------
        const pills = document.querySelectorAll('.city-pill');
        const selectedLabel = document.getElementById('selectedCityLabel');
        pills.forEach(pill => {
            pill.addEventListener('click', () => {
                pills.forEach(p => p.classList.remove('selected'));
                pill.classList.add('selected');
                selectedLabel.textContent = pill.dataset.city;
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // 1. 取得網址列的參數 (例如：?city=臺中市)
            const urlParams = new URLSearchParams(window.location.search);
            const cityParam = urlParams.get('city');

            // 2. 如果有抓到 city 參數
            if (cityParam) {
                const citySelect = document.getElementById('citySelect');

                if (citySelect) {
                    // 將下拉選單的值設定為該城市
                    citySelect.value = cityParam;

                    // 3. 觸發 change 事件（讓 Vue 的 v-model 能夠同步抓到變更）
                    citySelect.dispatchEvent(new Event('change'));

                    // 4. 自動點擊搜尋按鈕，執行原本的篩選與渲染邏輯
                    const searchBtn = document.getElementById('searchBtn');
                    if (searchBtn) {
                        searchBtn.click();
                    }
                }
            }
        });
    </script>

</body>

</html>