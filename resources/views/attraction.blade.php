@extends('layout')
@section('title', '中彰投生活圈/景點探索')
@section('content')
<section>
    {{-- ========== 列表頁標題橫幅 ========== --}}
    <div class="list-header-banner">
        <div class="container">
            <div class="list-title">景點探索</div>
            <div>整理中彰投最精選的旅遊目的地</div>
        </div>
    </div>
    <div class="container my-4">
        {{-- ========== 篩選列 ========== --}}
        <div class="filter-bar mb-4">
            <select class="form-select" id="citySelect" v-model="selectedCity" style="flex:1;">
                <option value="">全部縣市</option>
                <option value="臺中市">臺中市</option>
                <option value="彰化縣">彰化縣</option>
                <option value="南投縣">南投縣</option>
            </select>

            <select class="form-select" id="categorySelect" v-model="selectedCategory" style="flex:1;">
                <option value="">熱門分類</option>
                <option value="自然與生態景觀">自然與生態景觀</option>
                <option value="人文歷史與藝文">人文歷史與藝文</option>
                <option value="休閒與公共設施">休閒與公共設施</option>
                <option value="產業與交通觀光">產業與交通觀光</option>
            </select>

            <input type="text" class="form-control" id="keywordInput" v-model="keyword" placeholder="搜尋名稱、關鍵字，或分類"
                style="flex:2;">

            <select class="form-select" id="rankSelect" v-model="selectedRank" style="flex:1;">
                <option value="">排序方式</option>
                <option value="最多瀏覽人次">最多瀏覽人次</option>
                <option value="名稱 (筆劃少到多)">名稱 (筆劃少到多</option>
                <option value="最新上架">最新上架</option>
            </select>

            <button type="button" class="btn btn-add" id="searchBtn" @click="searchData">
                <i class="fa-solid fa-magnifying-glass"></i> 搜尋
            </button>
        </div>
        {{-- ========== 結果數量 + 每頁筆數 ========== --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="result-count ms-3 mt-2">
                共 <span id="totalCount">[[ filter.length ]]</span> 筆資料
            </div>
            <select class="form-select ms-3" id="pageSizeSelect" v-model.number="pageSize" @change="currentPage = 1"
                style="width: 120px;height: 30px;font-size: 12px">
                <option value="15">每頁15筆</option>
                <option value="30">每頁30筆</option>
                <option value="50">每頁50筆</option>
            </select>
        </div>

        {{-- ========== 卡片列表（用 JS 動態渲染） ========== --}}
        <section class="list-section" id="list-section">
            <div class="list-container">
                <div class="row g-4" id="attractionList">
                    <div class="list-container">
                        <div class="list-grid">
                            <div class="list-card" v-for="item in pageData" :key="item.id">
                                <a href="#" @click.prevent="showDetail(item)">
                                    <div class="list-thumb"
                                        :style="{
                                                backgroundImage: 'url(' + item.img1 + ')'
                                            }">
                                    </div>
                                    <div class="list-body">
                                        <h4 class="fw-bold">[[ item.attractionName ]]</h4>
                                        {{-- 只抓80個字 --}}
                                        <p>[[ item.description.length > 80 ? item.description.slice(0, 80) + '...' :
                                            item.description ]]
                                        </p>
                                        <div class="list-date">
                                            [[ item.zipCode || '' ]]
                                            [[ item.city || '' ]]
                                            [[ item.town || '' ]]
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div v-if="pageData.length == 0" class="text-center">
                                查無資料
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    {{-- ========== 詳細內容彈跳視窗（Modal） ========== --}}
    <div class="modal fade" id="detailModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content detail-modal">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title" id="detailName">景點名稱</h5>
                        <div id="detailLocation" style="font-size: 13px; color: #cfe0d4;">縣市 · 地區</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img id="detailImg" src="" alt="" class="detail-image mb-3 w-70">
                    <div class="info-card mb-3">
                        <div class="info-item">
                            <span>📍</span>
                            <div>
                                <div class="info-label">地址</div>
                                <div id="detailAddress">-</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <span>☎</span>
                            <div>
                                <div class="info-label">電話</div>
                                <div id="detailTel">-</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <span>🧭</span>
                            <div>
                                <div class="info-label">經緯度</div>
                                <div id="detailLatLng">-</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <span>🧭</span>
                            <div>
                                <div class="info-label">景點連結</div>
                                <a id="websiteLink" href="#" target="_blank" rel="noopener noreferrer">前往網站</a>
                            </div>
                        </div>
                    </div>

                    <div class="detail-intro-box" id="detailDesc">
                        介紹內容...
                    </div>

                    <div class="mt-3">
                        <div class="fw-bold mb-2">附近景點</div>
                        <div class="row g-2" id="relatedList">
                            {{-- 附近景點會自動塞進這裡 --}}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</section>

@push('scripts')
<script>

</script>
@endpush
@endsection