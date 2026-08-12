@extends('layout')
@section('title', '關於我們')
@section('content')
<section>
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
        <div class="cta-section">
            <div class="cta-title">準備好了嗎？</div>
            <div class="cta-subtitle">現在就開始規劃你的中彰投之旅</div>
            <a href="/front/attraction" class="btn-hero">開始探索</a>
        </div>
    </div>
</section>

@endsection