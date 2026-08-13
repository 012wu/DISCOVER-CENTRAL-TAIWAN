@extends ('admin.layout')
@section('title','中彰投生活圈/後臺管理/景點列表')
@section('content')
{{-- 左上小標 --}}
<div class="admin-breadcrumb">
    <a href="/admin/home">後臺管理</a><span> / </span><a href="#">景點列表</a>
</div>
<div class="admin-page-title">景點列表</div>
<div class="admin-page-desc">管理所有旅遊景點資料</div>
{{-- 下拉式選單＋關鍵字篩選區 --}}
<form method="GET" class="row admin-filter-bar">
    <div class="col-2">
        <select name="city" id="city" class="form-select form-control" onchange="this.form.submit()">
            <option value="全部縣市">全部縣市</option>
            <option value="臺中市" @selected(request('city')==='臺中市' )>臺中市</option>
            <option value="彰化縣" @selected(request('city')==='彰化縣' )>彰化縣</option>
            <option value="南投縣" @selected(request('city')==='南投縣' )>南投縣</option>
        </select>
    </div>
    <div class="col-2">
        <select name="attractionClass" id="attractionClassName2" class="form-select form-control"
            onchange="this.form.submit()">
            <option value="熱門分類">熱門分類</option>
            <option value="自然與生態景觀" @selected(request('attractionClass')==='自然與生態景觀' )>自然與生態景觀</option>
            <option value="人文歷史與藝文" @selected(request('attractionClass')==='人文歷史與藝文' )>人文歷史與藝文</option>
            <option value="休閒與公共設施" @selected(request('attractionClass')==='休閒與公共設施' )>休閒與公共設施</option>
            <option value="產業與交通觀光" @selected(request('attractionClass')==='產業與交通觀光' )>產業與交通觀光</option>
        </select>
    </div>
    {{-- 關鍵字篩選：比對名稱/詳細描述/地區/詳細地址(延遲1秒後即時搜尋) --}}
    <div class="col-2">
        <input type="text" name="keyword" placeholder="搜尋關鍵字" class="form-control" value="{{ request('keyword') }}"
            onkeyup="setTimeout(() => this.form.submit(), 1000)">
    </div>
    {{-- ms-auto自動推到最右側 --}}
    <div class="col-2 ms-auto">
        <select name="rank" id="rank" class="form-select" onchange="this.form.submit()">
            <option value="排序方式">排序方式</option>
            <option value="景點編號（小->大）" @selected(request('rank')==='景點編號（小->大' )>景點編號（小->大）</option>
            <option value="名稱 (筆劃少->多)" @selected(request('rank')==='名稱 (筆劃少->多)' )>名稱 (筆劃少->多)</option>
            <option value="最新上架" @selected(request('rank')==='最新上架' )>最新上架</option>
        </select>
    </div>
</form>
<div class="d-flex">
    {{-- 總共筆數 --}}
    <div class="result-count ms-3 mt-2">
        共 <span id="totalCount">{{ $list->total() }}</span> 筆資料
    </div>
    <form method="GET">
        <select class="form-select ms-3"
            id="pageSizeSelect"
            name="pageSize"
            style="width: 120px; height: 30px; font-size: 12px"
            onchange="this.form.submit()">

            <option value="15" {{ request('pageSize', 15) == 15 ? 'selected' : '' }}>
                每頁15筆
            </option>

            <option value="30" {{ request('pageSize') == 30 ? 'selected' : '' }}>
                每頁30筆
            </option>

            <option value="50" {{ request('pageSize') == 50 ? 'selected' : '' }}>
                每頁50筆
            </option>

        </select>
    </form>
    {{-- 新增按鈕 --}}
    <button type="button" class="btn btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="fa-solid fa-plus"></i>
        新增
    </button>
</div>
{{-- 列表 --}}
<div class="admin-table-wrap">
    <table class="admin-table">
        {{-- 標題 --}}
        <thead>
            <tr>
                <th>id</th>
                <th>景點編號</th>
                <th>名稱</th>
                <th>分類代碼</th>
                <th>景點分類代碼名稱</th>
                <th>景點分類代碼名稱_網頁</th>
                <th>緯度</th>
                <th>經度</th>
                <th>郵遞區號</th>
                <th>縣市</th>
                <th>地區</th>
                <th>街道地址</th>
                <th>電話</th>
                <th>網站連結</th>
                <th>圖片</th>
                <th>詳細描述</th>
                <th>建立時間</th>
                <th>更新時間</th>
                <th>操作</th>
            </tr>
        </thead>
        {{-- 內容 --}}
        <tbody id="attractionList">
            @if ($list->count() > 0)
            @foreach ($list as $item)
            <tr id="row-{{ $item->id }}">
                <td>{{ $item->id }}</td>
                <td>{{ $item->attractionID }}</td>
                <td>{{ $item->attractionName }}</td>
                <td>{{ $item->attractionClassNo }}</td>
                <td>{{ $item->attractionClassName }}</td>
                <td>{{ $item->attractionClassName2 }}</td>
                <td>{{ $item->positionLat }}</td>
                <td>{{ $item->positionLon }}</td>
                <td>{{ $item->zipCode }}</td>
                <td>{{ $item->city }}</td>
                <td>{{ $item->town }}</td>
                <td>{{ $item->streetAddress }}</td>
                <td>{{ $item->tel }}</td>
                <td>
                    <a href="{{ $item->websiteURL }}" target="_blank">前往網站</a>
                </td>
                <td>
                    <img src="{{ $item->img1 }}" class="row-thumb" alt="">
                </td>
                <td title="{{ $item->description }}">
                    {{ $item->description && mb_strlen($item->description) > 20 ? mb_substr($item->description, 0, 20) . '...' : $item->description }}
                </td>
                <td>{{ $item->createTime }}</td>
                <td>{{ $item->updateTime }}</td>
                <td>
                    <button type="button" class="btn btn-row-edit" data-bs-toggle="modal"
                        data-bs-target="#editModal{{ $item->id }}">
                        修改
                    </button>

                    <button type="button" class="btn btn-row-delete"
                        onclick="deleteData({{ $item->id }})">
                        刪除
                    </button>
                </td>
            </tr>
            @endforeach
            @else
            {{-- 沒有搜尋結果 --}}
            <tr>
                <td colspan="19" class="text-center">
                    查無資料
                </td>
            </tr>
            @endif
        </tbody>
    </table>
    @foreach ($list as $item)
    <div class="modal fade" id="editModal{{ $item->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">修改景點</h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <form onsubmit="submitEdit(event, {{$item ->id}})">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">景點編號</label>
                            <input type="text" name="attractionID" class="form-control" required
                                value="{{ $item->attractionID }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">名稱</label>
                            <input type="text" name="attractionName" class="form-control"
                                value="{{ $item->attractionName }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">分類代碼(請用 "," 隔開)</label>
                            <input type="text" name="attractionClassNo" id="editClassNo{{ $item->id }}" class="form-control" required
                                value="{{ $item->attractionClassNo }}"
                                onkeyup="lookupClassName('editClassNo{{ $item->id }}', 'editClassName{{ $item->id }}', 'editClassName2{{ $item->id }}')">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">景點分類代碼名稱(自動帶入)</label>
                            <input type="text" name="attractionClassName" id="editClassName{{ $item->id }}" class="form-control"
                                value="{{ $item->attractionClassName }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">景點分類代碼名稱_new(自動帶入)</label>
                            <input type="text" name="attractionClassName2" id="editClassName2{{ $item->id }}" class="form-control"
                                value="{{ $item->attractionClassName2 }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">緯度</label>
                            <input type="text" name="positionLat" id="editPositionLat{{ $item->id }}" class="form-control" required
                                pattern="-?\d+\.\d+"
                                placeholder="例如 24.1234"
                                value="{{ $item->positionLat }}"
                                oninput="checkDecimalFormat('editPositionLat{{ $item->id }}', 'editLatHint{{ $item->id }}', -90, 90)">
                            <div id="editLatHint{{ $item->id }}" class="form-text text-muted">請輸入含小數點的數字，例如 24.1234（範圍 -90 ~ 90）</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">經度</label>
                            <input type="text" name="positionLon" id="editPositionLon{{ $item->id }}" class="form-control" required
                                pattern="-?\d+\.\d+"
                                placeholder="例如 120.5678"
                                value="{{ $item->positionLon }}"
                                oninput="checkDecimalFormat('editPositionLon{{ $item->id }}', 'editLonHint{{ $item->id }}', -180, 180)">
                            <div id="editLonHint{{ $item->id }}" class="form-text text-muted">請輸入含小數點的數字，例如 120.5678（範圍 -180 ~ 180）</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">郵遞區號</label>
                            <input type="text"
                                name="zipCode"
                                class="form-control"
                                pattern="[0-9]{3,6}"
                                title="請輸入3~6碼數字郵遞區號"
                                inputmode="numeric"
                                value="{{ $item->zipCode }}"
                                oninput="checkZipCode(this)">

                            <div class="form-text text-muted">請輸入3~6碼數字</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">縣市</label>
                            <input type="text" name="city" class="form-control" required
                                value="{{ $item->city }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">地區</label>
                            <input type="text" name="town" class="form-control"
                                value="{{ $item->town }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">街道地址</label>
                            <input type="text" name="streetAddress" class="form-control"
                                value="{{ $item->streetAddress }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">電話</label>
                            <input type="tel" name="tel" class="form-control"
                                pattern="[0-9()#-]+" title="只能輸入數字、-、()、#"
                                value="{{ $item->tel }}">
                            <div class="form-text text-muted">只能輸入數字、-、()、#</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">網站連結</label>
                            <input type="url" name="websiteURL" class="form-control"
                                placeholder="https://..."
                                value="{{ $item->websiteURL }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">圖片</label>
                            <input type="url" name="img1" class="form-control" placeholder="https://..."
                                value="{{ $item->img1 }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">詳細描述</label>
                            <textarea name="description" class="form-control">{{ $item->description }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary me-2">
                            儲存
                        </button>

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            取消
                        </button>

                    </form>

                </div>
            </div>
        </div>
    </div>
    @endforeach
    {{--分頁--}}
    {{ $list->links()}}
</div>

{{-- 新增 --}}
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">新增景點</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form onsubmit="submitAdd(event)">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">景點編號</label>
                        <input type="text" name="attractionID" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">名稱</label>
                        <input type="text" name="attractionName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">分類代碼(請用 "," 隔開)</label>
                        <input type="text" name="attractionClassNo" id="addClassNo" class="form-control" required
                            onkeyup="lookupClassName('addClassNo', 'addClassName', 'addClassName2')">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">景點分類代碼名稱(自動帶入)</label>
                        <input type="text" name="attractionClassName" id="addClassName" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">景點分類代碼名稱_new(自動帶入)</label>
                        <input type="text" name="attractionClassName2" id="addClassName2" class="form-control" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">緯度</label>
                        <input type="text" name="positionLat" id="addPositionLat" class="form-control" required
                            pattern="-?\d+\.\d+"
                            placeholder="例如 24.1234"
                            oninput="checkDecimalFormat('addPositionLat', 'addLatHint', -90, 90)">
                        <div id="addLatHint" class="form-text text-muted">請輸入含小數點的數字，例如 24.1234（範圍 -90 ~ 90）</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">經度</label>
                        <input type="text" name="positionLon" id="addPositionLon" class="form-control" required
                            pattern="-?\d+\.\d+"
                            placeholder="例如 120.5678"
                            oninput="checkDecimalFormat('addPositionLon', 'addLonHint', -180, 180)">
                        <div id="addLonHint" class="form-text text-muted">請輸入含小數點的數字，例如 120.5678（範圍 -180 ~ 180）</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">郵遞區號</label>
                        <input type="text"
                            name="zipCode"
                            class="form-control"
                            pattern="[0-9]{3,6}"
                            title="請輸入3~6碼數字郵遞區號"
                            inputmode="numeric"
                            oninput="checkZipCode(this)">

                        <div class="form-text text-muted">請輸入3~6碼數字</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">縣市</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">地區</label>
                        <input type="text" name="town" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">街道地址</label>
                        <input type="text" name="streetAddress" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">電話</label>
                        <input type="tel" name="tel" class="form-control"
                            pattern="[0-9()#-]+" title="只能輸入數字、-、()、#">
                        <div class="form-text text-muted">只能輸入數字、-、()、#</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">網站連結</label>
                        <input type="url" name="websiteURL" class="form-control" placeholder="https://...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">圖片</label>
                        <input type="url" name="img1" class="form-control" placeholder="https://...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">詳細描述</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary me-2">新增</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // 新增:呼叫 POST /api/attraction
    function submitAdd(event) {
        event.preventDefault();
        const form = event.target;
        const data = Object.fromEntries(new FormData(form));

        const errorMsg = validateAttractionForm(data);
        if (errorMsg) {
            Swal.fire('請檢查輸入內容', errorMsg, 'warning');
            return;
        }

        fetch('/api/attraction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    Swal.fire({
                        title: '新增成功',
                        text: '資料已成功新增',
                        icon: 'success',
                        confirmButtonText: '確定'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('新增失敗', result.message || '請檢查輸入內容', 'error');
                }
            })
            .catch(() => {
                Swal.fire('發生錯誤', '請稍後再試', 'error');
            });
    }

    // 修改:呼叫 PUT /api/attraction/{id}
    function submitEdit(event, id) {
        event.preventDefault();
        const form = event.target;
        const data = Object.fromEntries(new FormData(form));

        const errorMsg = validateAttractionForm(data);
        if (errorMsg) {
            Swal.fire('請檢查輸入內容', errorMsg, 'warning');
            return;
        }

        fetch(`/api/attraction/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(result => {
                if (result.success) {
                    Swal.fire({
                        title: '修改成功',
                        text: '資料已成功修改',
                        icon: 'success',
                        confirmButtonText: '確定'
                    }).then(() => {
                        location.reload();
                    });

                } else {
                    Swal.fire('更新失敗', result.message || '請檢查輸入內容', 'error');
                }
            })
            .catch(() => {
                Swal.fire('發生錯誤', '請稍後再試', 'error');
            });
    }

    // 刪除:呼叫 DELETE /api/attraction/{id}
    function deleteData(id) {
        Swal.fire({
            title: '確定要刪除嗎？',
            text: '刪除後無法復原',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '確定刪除',
            cancelButtonText: '取消'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/api/attraction/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(result => {
                        if (result.success) {
                            document.getElementById('row-' + id).remove();
                            Swal.fire({
                                title: '刪除成功',
                                text: '資料已成功刪除',
                                icon: 'success',
                                confirmButtonText: '確定'
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('刪除失敗', result.message || '請稍後再試', 'error');
                        }
                    })
                    .catch(() => {
                        Swal.fire('發生錯誤', '請稍後再試', 'error');
                    });
            }
        });
    }


    // 依輸入的分類代碼，自動查詢分類名稱並填入
    function lookupClassName(noId, nameId, name2Id) {
        // 加一個小延遲，避免打太多次 API
        clearTimeout(window.lookupTimer);
        window.lookupTimer = setTimeout(() => {
            const codes = document.getElementById(noId).value;

            if (!codes) {
                document.getElementById(nameId).value = '';
                document.getElementById(name2Id).value = '';
                return;
            }

            fetch(`/api/attractionClass/lookup?codes=${codes}`)
                .then(res => res.json())
                .then(result => {
                    if (result.success) {
                        document.getElementById(nameId).value = result.attractionClassName;
                        document.getElementById(name2Id).value = result.attractionClassName2;
                    }
                });
        }, 500);
    }

    // 即時檢查緯度/經度格式（含小數點 + 範圍），並在 input 下方即時顯示提示文字
    function checkDecimalFormat(inputId, hintId, min, max) {
        const input = document.getElementById(inputId);
        const hint = document.getElementById(hintId);
        const value = input.value;

        if (!value) {
            hint.textContent = `請輸入含小數點的數字（範圍 ${min} ~ ${max}）`;
            hint.className = 'form-text text-muted';
            return;
        }

        const isFormatValid = /^-?\d+\.\d+$/.test(value);
        const isRangeValid = isFormatValid && Number(value) >= min && Number(value) <= max;

        if (isFormatValid && isRangeValid) {
            hint.textContent = '✓ 格式正確';
            hint.className = 'form-text text-success';
        } else if (!isFormatValid) {
            hint.textContent = '✗ 格式錯誤，需為含小數點的數字，例如 24.1234';
            hint.className = 'form-text text-danger';
        } else {
            hint.textContent = `✗ 範圍需介於 ${min} ~ ${max} 之間`;
            hint.className = 'form-text text-danger';
        }
    }

    function checkZipCode(input) {
        const value = input.value;
        const hint = input.nextElementSibling;

        if (!value) {
            hint.textContent = '請輸入3~6碼數字';
            hint.className = 'form-text text-muted';
            return;
        }

        if (!/^\d+$/.test(value)) {
            hint.textContent = '✗ 只能輸入數字';
            hint.className = 'form-text text-danger';
            return;
        }

        if (value.length < 3) {
            hint.textContent = '✗ 至少需要3碼';
            hint.className = 'form-text text-danger';
            return;
        }

        if (value.length > 6) {
            hint.textContent = '✗ 最多只能6碼';
            hint.className = 'form-text text-danger';
            return;
        }

        hint.textContent = '✓ 格式正確';
        hint.className = 'form-text text-success';
    }

    // 送出前驗證，回傳錯誤訊息字串；沒問題回傳 null
    function validateAttractionForm(data) {
        if (!data.attractionID) return '景點編號為必填欄位';
        if (!data.attractionName) return '名稱為必填欄位';
        if (!data.attractionClassNo) return '分類代碼為必填欄位';

        if (!data.positionLat || !/^-?\d+\.\d+$/.test(data.positionLat)) {
            return '緯度格式錯誤，必須為含小數點的數字，例如 24.1234';
        }
        if (Number(data.positionLat) < -90 || Number(data.positionLat) > 90) {
            return '緯度範圍必須介於 -90 到 90 之間';
        }

        if (!data.positionLon || !/^-?\d+\.\d+$/.test(data.positionLon)) {
            return '經度格式錯誤，必須為含小數點的數字，例如 120.5678';
        }
        if (Number(data.positionLon) < -180 || Number(data.positionLon) > 180) {
            return '經度範圍必須介於 -180 到 180 之間';
        }

        if (data.zipCode && (!/^\d+$/.test(data.zipCode) || data.zipCode.length < 3 || data.zipCode.length > 6)) {
            return '郵遞區號格式錯誤，請輸入3~6碼數字';
        }
        if (!data.city) return '縣市為必填欄位';
        if (data.tel && !/^[0-9()#-]+$/.test(data.tel)) {
            return '電話格式錯誤，只能輸入數字、-、()、#';
        }
        if (data.websiteURL && !/^https?:\/\/.+/.test(data.websiteURL)) {
            return '網站連結格式錯誤，需以 http:// 或 https:// 開頭';
        }
        if (data.img1 && !/^https?:\/\/.+/.test(data.img1)) {
            return '圖片必須為網址格式，需以 http:// 或 https:// 開頭';
        }
        return null;
    }
</script>
@endpush
@endsection