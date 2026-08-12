<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>中彰投生活圈/關於我們</title>
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
            <a href="/home" class="navbar-brand-custom" aria-label="中彰投生活圈首頁">
                <img src="img/logoothers.svg" class="logo" alt="中彰投生活圈">
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
                    <input type="text" placeholder="關鍵字搜尋" v-model="keyword">
                </div>
            </div>

        </section>
        <!-- ========== 頁首橫幅 ========== -->
        <div class="page-header-banner">
            <div class="container">
                <div class="page-header-title">關於我們</div>
                <div class="">歡迎來到中彰投！跟著我們一起發掘台中、彰化與南投的日常美景與美好驚喜</div>
            </div>
        </div>

        <!-- ========== 麵包屑 ========== -->
        <div class="container mt-4">
            <div class="breadcrumb-custom">
                <a href="/home">首頁</a> &gt; 關於我們
            </div>
        </div>

        <!-- ========== 平台介紹 ========== -->
        <div class="container my-5">
            <div class="row align-items-center g-4">
                <div class="col-md-6">
                    <div class="section-subtitle text-start pb-4">你好！</div>
                    <h2 class="section-title text-start fw-bold">陪你隨心所欲，輕鬆玩遍中彰投</h2>
                    <p class="mt-3" style="color: var(--color-text-secondary); line-height: 1.9;">
                        「假日該去哪裡晃晃？」這是我們最常問自己的一句話。
                        中彰投生活圈，就是為了想好好玩中臺灣的你而生的陪伴小夥伴！
                        我們幫你把台中、彰化、南投優質的私房景點、溫馨旅宿與巷弄美食全部整理在一起，
                        還貼心準備了主題篩選與一日遊懶人包。
                    </p>
                    <p class="mt-3" style="color: var(--color-text-secondary); line-height: 1.9;">
                        無論你是熱愛的在地人，還是剛好安排休假來玩的旅客，
                        希望透過這裡簡單清晰的整理，讓你的每次出門都不必做繁雜功課，
                        隨時都能來一場說走就走的放鬆小旅行！
                    </p>
                </div>
                <div class="col-md-6">
                    <img src="/img/about-ch-canva0802-2.jpg" alt="中彰投風景" class="w-100"
                        style="border-radius: var(--radius-md);">
                </div>
            </div>
        </div>

        <!-- ========== 服務特色 ========== -->
        <div class="container my-5">
            <!-- <div class="section-subtitle">OUR SERVICE</div> -->
            <div class="section-title mb-4">我們能幫你準備什麼？</div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="about-feature-card">
                        <a href="/attraction">
                            <div class="feature-icon">📍</div>
                            <div class="feature-title">好玩景點</div>
                            <div class="feature-desc">收錄大自然風光、古蹟巡禮與文青打卡熱點，按城市與喜好一鍵快速篩選。</div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-feature-card">
                        <a href="/hotel">
                            <div class="feature-icon">🏨</div>
                            <div class="feature-title">舒適旅宿</div>
                            <div class="feature-desc">從質感民宿到高CP值飯店，明確標示價格區間與聯絡方式，安心睡好覺。</div>
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-feature-card">
                        <a href="/restaurant">
                            <div class="feature-icon">🍜</div>
                            <div class="feature-title">在地美食</div>
                            <div class="feature-desc">網羅傳承多年的老字號與口碑爆棚的特色餐館，滿足你的在地饕客胃。</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========== CTA：引導去逛景點 ========== -->
        <div class="container mb-5">
            <div class="about-cta-card">
                <div class="about-cta-title">準備好了嗎？</div>
                <div class="about-cta-desc">現在就開始規劃你的中彰投之旅</div>
                <a href="/attraction" class="btn-about-explore">開始探索</a>
            </div>
        </div>
        </section>
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
                            <a href="/" class="admin-link">管理員登出</a>
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
        <div class="modal" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel"
            aria-hidden="true">
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
