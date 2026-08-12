<!DOCTYPE html>
<html lang="zh-TW">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>中彰投生活圈/首頁</title>
    {{--Font Awesome --}}
    <link rel="stylesheet" href="css/all.min.css">
    {{--bootstrap --}}
    <link rel="stylesheet" href="css/bootstrap.min.css">
    {{--google front --}}
    <link rel="stylesheet" href="css/front.css">
</head>

<body>
    <div class="front" id="app">
        {{--導覽列 --}}
        <section class="navbar-custom" id="siteHeader">
            {{--Logo --}}
            <img src="img/logo-others.svg" class="logo" alt="中彰投生活圈">
            {{--導覽選單 --}}
            <nav class="nav-link-custom">
                <a href="/home" class="">
                    <i class="fa-regular fa-house"></i>
                    <span>首頁</span>
                </a>
                <a href="/attraction" class="">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>景點</span>
                </a>
                <a href="/hotel" class="">
                    <i class="fa-solid fa-bed"></i>
                    <span>旅宿</span>
                </a>
                <a href="/restaurant" class="">
                    <i class="fa-solid fa-utensils"></i>
                    <span>餐飲</span>
                </a>
            </nav>
            {{--搜尋框 --}}
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" placeholder="關鍵字搜尋" v-model="keyword">
            </div>

        </section>

        <section class="hero" id="top">
            <!-- 預設開始為第0張 -->
            <div class="hero-banner slide-0" data-index="0">
                <div class="hero-image" style="background-image: url('img/tc-homepage-canva0802.jpg');"></div>
                <div class="hero-content">
                    <div class="hero-tag">台中市 ｜ 高美濕地</div>
                    <div class="hero-title">走向海天一色的天空之鏡</div>
                    <div class="hero-title-en">GAOMEI WETLAND</div>
                    <p class="hero-desc">踏上木棧步道,當橘紅霞光染紅海面,聽風車在夕陽下溫柔低語</p>
                </div>
            </div>

            <div class="hero-banner slide-1" data-index="1">
                <div class="hero-image" style="background-image: url('img/nt-homepage-canva0802.jpg');"></div>
                <div class="hero-content">
                    <div class="hero-tag">南投縣 ｜ 清境農場</div>
                    <div class="hero-title">漫步兩千公尺的雲端草原</div>
                    <div class="hero-title-en">QINGJING FARM</div>
                    <p class="hero-desc">飄渺雲霧與綿延綠草交織,與可愛羊群共度高山愜意時光</p>
                </div>
            </div>
            <div class="hero-banner slide-2" data-index="2">
                <div class="hero-image" style="background-image: url('img/nt-homepage-canva0802-2.jpg');"></div>
                <div class="hero-content">
                    <div class="hero-tag">南投縣 ｜ 溪頭自然教育園區</div>
                    <div class="hero-title">走進千年的綠色療癒</div>
                    <div class="hero-title-en">XITOU NATURE EDUCATION AREA</div>
                    <p class="hero-desc">高聳入雲的柳杉林間,讓滿滿的芬多精帶走所有都市疲憊</p>
                </div>
            </div>
            <div class="hero-dots">
                <button class="hero-dot active" data-slide="0" aria-label="第0張"></button>
                <button class="hero-dot" data-slide="1" aria-label="第1張"></button>
                <button class="hero-dot" data-slide="2" aria-label="第2張"></button>
            </div>
        </section>
        <section class="city" id="city">
            <div class="city-container">
                <div class="city-header">
                    <span class="city-title">探索中彰投</span>
                    <h2 class=" city-subtitle">每個城市都有獨特故事</h2>
                </div>
                <div class="city-grid">
                    <a href="/attraction?city=臺中市">
                        <div class="city-card city-card-1">
                            <img class="city-img" src="/img/city-tc-canva0802.jpg" alt="">
                            <div class="city-card-body">
                                <h3>臺中市</h3>
                                <p>台灣第二大城市,文化藝術與自然生態並存,從高美濕地夕陽到歌劇院建築之美</p>
                            </div>
                        </div>
                    </a>
                    <a href="/attraction?city=彰化縣">
                        <div class="city-card city-card-2">
                            <img class="city-img" src="/img/city-ch-canva0802.jpg" alt="">
                            <div class="city-card-body">
                                <h3>彰化縣</h3>
                                <p>保存最完整的清代歷史文化,鹿港老街、龍山寺,感受台灣最深厚的人文底蘊</p>
                            </div>
                        </div>
                    </a>
                    <a href="/attraction?city=南投縣">
                        <div class="city-card city-card-3">
                            <img class="city-img" src="/img/city-nt-canva0802.jpg" alt="">
                            <div class="city-card-body">
                                <h3>南投縣</h3>
                                <p>台灣唯一不靠海的縣,日月潭、清境農場、溪頭森林,大自然的恩賜盡在於此</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </section>
        <!-- 直接抓json裡面的資料 -->
        <section class="event-section" id="event-section">
            <div class="event-container">
                <div class="event-header">
                    <h2 class="event-title fw-bold">最新活動</h2>
                </div>
                <div class="event-grid">
                    <div class="event-card" v-for="event in events" :key="event.eventID">
                        <a :href="event.websiteURL" target="_blank">
                            <div class="event-thumb"
                                :style="{
                                backgroundImage: 'url(' + event.photo[0].uRL + ')'
                            }">
                            </div>
                            <div class="event-body">
                                <h4 class="fw-bold">[[ event . eventName ]]</h4>
                                <!-- 只抓80個字 -->
                                <p>[[ event . description . length > 80 ? event . description . slice(0, 80) + '...' : event .
                                    description ]]
                                </p>
                                <div class="event-date">
                                    [[ event . startDateTime . substring(0, 10) ]]
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section class="cta-section">
            <div class="container reveal">
                <h2>規劃你的中彰投之旅</h2>
                <p>選擇您的目的地,讓 AI 為您規劃最佳旅遊行程</p>
                <div class="city-pills">
                    <button class="city-pill selected" data-city="臺中市">臺中市</button>
                    <button class="city-pill" data-city="彰化縣">彰化縣</button>
                    <button class="city-pill" data-city="南投縣">南投縣</button>
                </div>
                <button class="generate-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 12h16M14 6l6 6-6 6" />
                    </svg>
                    生成一日遊行程
                    <span class="divider">···</span>
                    <span id="selectedCityLabel">臺中市</span>
                </button>
            </div>
        </section>

        <section class="weather-section" id="stay">
            <div class="container">
                <div class="weather-grid">
                    <div class="weather-card reveal reveal-delay-1">
                        <div class="weather-left">
                            <span class="weather-status status-sun" id="weather-status-taichung">載入中</span>
                            <span class="weather-city">臺中市</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:14px;">
                            <span class="weather-temp" id="weather-temp-taichung">--°C</span>
                            <span id="weather-icon-taichung"></span>
                        </div>
                    </div>

                    <div class="weather-card reveal reveal-delay-2">
                        <div class="weather-left">
                            <span class="weather-status status-cloud" id="weather-status-changhua">載入中</span>
                            <span class="weather-city">彰化縣</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:14px;">
                            <span class="weather-temp" id="weather-temp-changhua">--°C</span>
                            <span id="weather-icon-changhua"></span>
                        </div>
                    </div>

                    <div class="weather-card reveal reveal-delay-3">
                        <div class="weather-left">
                            <span class="weather-status status-rain" id="weather-status-nantou">載入中</span>
                            <span class="weather-city">南投縣</span>
                        </div>
                        <div style="display:flex; align-items:center; gap:14px;">
                            <span class="weather-temp" id="weather-temp-nantou">--°C</span>
                            <span id="weather-icon-nantou"></span>
                        </div>
                    </div>
                </div>

                <div class="partners-row reveal" id="food">
                    <a href="https://www.taiwan.net.tw/">
                        <div class="partner-logo" style="background-image: url(img/icon1.png);"></div>
                    </a>
                    <a href="https://www.sunmoonlake.gov.tw/">
                        <div class="partner-logo" style="background-image: url(img/icon2.png);"></div>
                    </a>
                    <a href="https://www.trimt-nsa.gov.tw/zh-tw/">
                        <div class="partner-logo" style="background-image: url(img/icon3.png);"></div>
                    </a>
                    <a href="https://recreation.forest.gov.tw/">
                        <div class="partner-logo" style="background-image: url(img/icon4.png);"></div>
                    </a>
                    <a href="https://ezgo.ardswc.gov.tw/en/">
                        <div class="partner-logo" style="background-image: url(img/icon5.png);"></div>
                    </a>
                    <a href="https://travel.taichung.gov.tw/">
                        <div class="partner-logo" style="background-image: url(img/icon6.png);"></div>
                    </a>
                    <a href="https://tourism.chcg.gov.tw/">
                        <div class="partner-logo" style="background-image: url(img/icon7.png);"></div>
                    </a>
                    <a href="https://travel.nantou.gov.tw/">
                        <div class="partner-logo" style="background-image: url(img/icon1\ \(1\).png);"></div>
                    </a>
                    <a href="https://www.taiwantrip.com.tw/">
                        <div class="partner-logo" style="background-image: url(img/icon2\ \(1\).png);"></div>
                    </a>
                    <a href="https://www.taiwantourbus.com.tw/C/us/home">
                        <div class="partner-logo" style="background-image: url(img/icon3\ \(1\).png);"></div>
                    </a>
                    <a href="https://www.taiwanstay.net.tw/TSA/web_html/index.html">
                        <div class="partner-logo" style="background-image: url(img/icon4\ \(1\).png);"></div>
                    </a>
                    <a href="https://guide.michelin.com/tw/zh_TW">
                        <div class="partner-logo" style="background-image: url(img/icon5\ \(1\).png);"></div>
                    </a>
                    <a href="https://camp.tad.gov.tw/CMA/web_page/CMA010100.jsp">
                        <div class="partner-logo" style="background-image: url(img/icon6\ \(1\).png);">露營場資訊平台</div>
                    </a>

                </div>
            </div>
        </section>

        <footer>
            <div class="container">
                <div class="footer-top">
                    <div class="footer-logo">
                        <img src="img/logo-landingpage.svg" class="" alt="中彰投生活圈">
                    </div>
                    <div class="footer-links">
                        <a href="/about">關於我們</a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#contactModal">聯絡我們</a>
                        @if(empty(session()->get("account")))
                        <a href="/admin/login" class="admin-link">管理員登入</a>
                        @endif
                        @if(!empty(session()->get("account")))
                        <a href="/admin/login" class="admin-link">管理員登出</a>
                        @endif
                        @if(!empty(session()->get("account")))
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
        {{--聯絡我們彈跳視窗 --}}
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
                                    type="email" class="form-control" id="cEmail" placeholder="請輸入您的 Email" required>
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
                            <div class="mb-3"> <label for="cSubject" class="form-label"> 主旨 </label> <input type="text"
                                    class="form-control" id="cSubject" placeholder="請輸入問題主旨" required>
                                <div class="invalid-feedback"> 請輸入主旨 </div>
                            </div>
                            <div class="mb-3"> <label for="cMessage" class="form-label"> 內文 </label> <textarea
                                    class="form-control" id="cMessage" rows="6" placeholder="請詳細描述您遇到的問題..."
                                    required></textarea>
                                <div class="invalid-feedback"> 請輸入問題內容 </div>
                            </div>
                            <div class="text-end"> <button type="button" class="btn-submit-contact" id="submitBtn"> 送出
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


    {{--抓活動資料 --}}
    <script>
        const App = {
            delimiters: ['[[', ']]'],
            data() {
                return {
                    selectedCity: new URLSearchParams(window.location.search).get('city') || '',
                    selectedCategory: '',
                    keyword: "",
                    events: [],
                }
            },
            mounted() {
                const vm = this;
                vm.eventList();
            },
            methods: {
                eventList() {
                    const vm = this;
                    fetch('/data/eventlist.json')
                        .then(response => response.json())
                        .then(data => {
                            vm.events = data.events
                                // 1. 篩選縣市
                                .filter(event => {
                                    const cities = ['臺中市', '彰化縣', '南投縣'];
                                    return cities.includes(
                                        event.PostalAddress.city
                                    );
                                })
                                // 2. 今天到 5 天後
                                .filter(event => {
                                    const now = new Date();
                                    const fiveDaysLater = new Date();
                                    fiveDaysLater.setDate(now.getDate() + 5);
                                    const eventDate = new Date(event.startDateTime);
                                    return eventDate >= fiveDaysLater;
                                })
                                // 3. 日期由近到遠
                                .sort((a, b) => {
                                    return new Date(a.startDateTime) -
                                        new Date(b.startDateTime);
                                })

                                // 4. 取前 9 筆
                                .slice(0, 9);
                        })
                        .catch(error => {
                            console.log('取得活動資料失敗', error);
                        });
                },
            }
        };
        Vue.createApp(App).mount("#app");
    </script>

    {{--banner輪播 --}}
    <script>
        // 取得所有輪播圖片
        let banners = document.querySelectorAll(".hero-banner");

        // 取得所有輪播圓點
        let dots = document.querySelectorAll(".hero-dot");

        // 目前顯示第幾張
        let current = 0;

        // 切換圖片
        function showSlide(index) {

            // 隱藏所有圖片
            banners.forEach((banner) => {
                banner.style.opacity = "0";
                banner.style.visibility = "hidden";
            });

            // 顯示指定圖片
            banners[index].style.opacity = "1";
            banners[index].style.visibility = "visible";

            // 更新 dot
            dots.forEach((dot, i) => {
                dot.classList.toggle("active", i === index);
            });

            current = index;
        }

        // 點擊 dot 切換圖片
        dots.forEach((dot, index) => {
            dot.addEventListener("click", function() {
                showSlide(index);
            });
        });

        // 初始化
        showSlide(0);

        // 每 5 秒自動輪播
        setInterval(function() {
            current++;
            if (current >= banners.length) {
                current = 0;
            }
            showSlide(current);
        }, 5000);
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
    </script>

    <script>
        fetch("/api/weather")
            .then(function(response) {
                console.log("API 狀態：", response.status);
                return response.json();
            })
            .then(function(data) {
                console.log("API 資料：", data);

                const locations = data.records.location;

                locations.forEach(function(item) {
                    const city = item.locationName;

                    console.log("城市：", city);

                    if (city !== "臺中市" && city !== "彰化縣" && city !== "南投縣") {
                        return;
                    }

                    let weather = "";
                    let maxTemp = "";

                    item.weatherElement.forEach(function(element) {
                        if (element.elementName === "Wx") {
                            weather = element.time[0].parameter.parameterName;
                        }

                        if (element.elementName === "MaxT") {
                            maxTemp = element.time[0].parameter.parameterName;
                        }
                    });

                    const status = getWeatherStatus(weather);
                    const icon = getWeatherIcon(weather);

                    if (city === "臺中市") {
                        document.querySelector("#weather-status-taichung").textContent = status;
                        document.querySelector("#weather-temp-taichung").textContent = maxTemp + "°C";
                        document.querySelector("#weather-icon-taichung").innerHTML = icon;
                    }

                    if (city === "彰化縣") {
                        document.querySelector("#weather-status-changhua").textContent = status;
                        document.querySelector("#weather-temp-changhua").textContent = maxTemp + "°C";
                        document.querySelector("#weather-icon-changhua").innerHTML = icon;
                    }

                    if (city === "南投縣") {
                        document.querySelector("#weather-status-nantou").textContent = status;
                        document.querySelector("#weather-temp-nantou").textContent = maxTemp + "°C";
                        document.querySelector("#weather-icon-nantou").innerHTML = icon;
                    }
                });
            })
            .catch(function(error) {
                console.log("錯誤：", error);
            });

        function getWeatherStatus(weather) {
            if (weather.includes("雨")) {
                return "雨";
            }

            if (weather.includes("陰") || weather.includes("雲")) {
                return "陰";
            }

            if (weather.includes("晴")) {
                return "晴";
            }

            return "陰";
        }

        function getWeatherIcon(weather) {
            if (weather.includes("雨")) {
                return `
            <svg class="weather-icon" viewBox="0 0 24 24" fill="none">
                <path d="M6 15a4 4 0 01-.6-7.96A5 5 0 0115 5a4.5 4.5 0 011 8.9" fill="#8FB9DA"/>
                <g stroke="#4C8FC9" stroke-width="1.6" stroke-linecap="round">
                    <path d="M8 18l-1 3M12 18l-1 3M16 18l-1 3"/>
                </g>
            </svg>
        `;
            }

            if (weather.includes("陰") || weather.includes("雲")) {
                return `
            <svg class="weather-icon" viewBox="0 0 24 24" fill="none">
                <path d="M6 18a4 4 0 01-.6-7.96A5 5 0 0115 8a4.5 4.5 0 011 8.9" fill="#B7BEC7"/>
            </svg>
        `;
            }

            return `
        <svg class="weather-icon" viewBox="0 0 24 24" fill="none">
            <circle cx="12" cy="12" r="5" fill="#F5B942"/>
            <g stroke="#F5B942" stroke-width="2" stroke-linecap="round">
                <path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M4.9 19.1L7 17M17 7l2.1-2.1"/>
            </g>
        </svg>
    `;
        }
    </script>
</body>

</html>