@extends ('admin.layout')
@section('title','中彰投生活圈/後臺管理')
@section('content')
<!-- 左上小標 -->
<div class="admin-breadcrumb">
    <a href="/admin/home">後臺管理</a><span> / </span><a href="#">數據總覽</a>
</div>
<div class="admin-page-title">數據總覽</div>
<div class="admin-page-desc">景點｜旅宿｜餐飲全站統計</div>
<!-- 景點資料統計 -->
<div class="chart-container mb-4">
    <h5>景點縣市統計</h5>
    <canvas id="attractionChart"></canvas>
    <h5>旅宿縣市統計</h5>
    <canvas id="hotelChart"></canvas>
    <h5>餐飲縣市統計</h5>
    <canvas id="restaurantChart"></canvas>
</div>

@push('scripts')
<script>
    // 共用的圓餅圖顏色組
    const pieColors = [
        "#4a7c59", "#7fb069", "#c4d7b2", "#a3c9a8",
        "#5b8c5a", "#8fbc94", "#3d5a3f", "#6a994e",
        "#2f5233", "#9dc183", "#588157", "#a7c957",
        "#264653", "#606c38", "#283618", "#bc6c25"
    ];

    // 共用的圓餅圖繪製函式
    // 共用的圓餅圖繪製函式
    function renderPieChart(canvasId, cityCount, label) {
        new Chart(document.getElementById(canvasId), {
            type: "pie",
            plugins: [ChartDataLabels], // 註冊 datalabels 插件

            data: {
                labels: Object.keys(cityCount),
                datasets: [{
                    label: label,
                    data: Object.values(cityCount),
                    backgroundColor: Object.keys(cityCount).map(
                        (_, i) => pieColors[i % pieColors.length]
                    )
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        position: "right"
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const value = context.parsed;
                                const percentage = ((value / total) * 100).toFixed(1);
                                return `${context.label}：${value} 筆 (${percentage}%)`;
                            }
                        }
                    },
                    // 圓餅圖上直接顯示百分比
                    datalabels: {
                        color: '#ffffff',
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: (value, context) => {
                            const datapoints = context.chart.data.datasets[0].data;
                            const total = datapoints.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return percentage > 3 ? `${percentage}%` : ''; // 小於 3% 不顯示避免擠在一起
                        }
                    }
                }
            }
        });
    }

    // 統計各縣市數量的共用函式
    function countByCity(items) {
        const cityCount = {};
        items.forEach(item => {
            const city = item.city || "其他";
            cityCount[city] = (cityCount[city] || 0) + 1;
        });
        return cityCount;
    }

    // 景點圖表
    fetch("/api/attraction")
        .then(response => response.json())
        .then(result => {
            console.log("景點 API 回傳資料：", result);

            const attractions = result.msg.data;
            console.log("景點數量：", attractions.length);

            const cityCount = countByCity(attractions);
            console.log("景點縣市統計：", cityCount);

            renderPieChart("attractionChart", cityCount, "景點數量");
        })
        .catch(error => {
            console.error("景點資料抓取失敗：", error);
        });

    // 旅宿圖表
    fetch("/api/hotel")
        .then(response => response.json())
        .then(result => {
            console.log("旅宿 API 回傳資料：", result);

            const hotels = result.msg.data;
            console.log("旅宿數量：", hotels.length);

            const cityCount = countByCity(hotels);
            console.log("旅宿縣市統計：", cityCount);

            renderPieChart("hotelChart", cityCount, "旅宿數量");
        })
        .catch(error => {
            console.error("旅宿資料抓取失敗：", error);
        });

    // 餐飲圖表
    fetch("/api/restaurant")
        .then(response => response.json())
        .then(result => {
            console.log("餐飲 API 回傳資料：", result);

            const restaurants = result.msg.data;
            console.log("餐飲數量：", restaurants.length);

            const cityCount = countByCity(restaurants);
            console.log("餐飲縣市統計：", cityCount);

            renderPieChart("restaurantChart", cityCount, "餐飲數量");
        })
        .catch(error => {
            console.error("餐飲資料抓取失敗：", error);
        });
</script>
@endpush
@endsection