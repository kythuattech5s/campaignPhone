<style type="text/css">
    .title_all span {
        display: block;
        width: 100px;
        height: 3px;
        background: #fa4410;
        margin: 5px 0px;
    }
    #maincontent .wrapper {
        border: 1px solid #E7E7E7;
        overflow-y: scroll;
        overflow-x: hidden;
        margin-bottom: 10px;
        height: 400px;
    }
    #maincontent .wrapper::-webkit-scrollbar {
    width: 10px; }
    #maincontent .wrapper::-webkit-scrollbar-track {
    border-radius: 10px; }
    #maincontent .wrapper::-webkit-scrollbar-thumb {
    background: #ebebeb;
    border-radius: 10px; }
    #maincontent .wrapper::-webkit-scrollbar-thumb:hover {
    background: #c5c5c5; 
    }
    #maincontent .loader {
        display: inline-block;
        border: 5px solid #f3f3f3;
        border-radius: 50%;
        border-top: 5px solid #3498db;
        width: 30px;
        height: 30px;
        -webkit-animation: spin 2s linear infinite;
        /* Safari */
        animation: spin 2s linear infinite;
    }
    .load-analytic {
        text-align: center;
    }
    @-webkit-keyframes spin {
        0% {
            -webkit-transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
        }
    }
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<div id="maincontent">
	<div class="analytics_admin">
    <div class="row">
        <div class="col-lg-6">
            <div class="wrapper">
                <p class="title_all">Trang chủ Google analytics<span></span></p>
                <div id="home_analytic" class="load-analytic">
                    <div class='loader'></div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
        	<div class="user_at_today">
            <div class="wrapper">
                <p class="title_all">Số người dùng hoạt động ngay bây giờ<span></span></p>
                <div id="active-users-container" class="load-analytic">
                    <div class='loader'></div>
                </div>
                <p class="title_all">Số lượt online mỗi tiếng<span></span></p>
                <div id="time_active_page" class="load-analytic">
                    <div class='loader'></div>
                </div>
            </div>
        	</div>
        </div>
        <div class="col-lg-3">
            <div class="wrapper">
                <p class="title_all">Số phiên theo thiết bị<span></span></p>
                <div id="operatingSystem" class="load-analytic">
                    <div class='loader'></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="wrapper">
                <p class="title_all">Bạn có được người dùng từ đâu<span></span></p>
                <div id="chart-2-container" class="load-analytic">
                    <div class='loader'></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="wrapper">
                <p class="title_all">Người dùng bạn đang ở đâu<span></span></p>
                <div id="chart-1-container" class="load-analytic">
                    <div class='loader'></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="wrapper">
                <p class="title_all">Người dùng của bạn truy cập những trang nào<span></span></p>
                <div id="access-pages" class="load-analytic">
                    <div class='loader'></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="wrapper">
                <p class="title_all">Chiến dịch analytics<span></span></p>
                <div id="campaign_analytic" class="load-analytic">
                    <div class='loader'></div>
                </div>
            </div>
        </div>
    </div>
  	@include('vh::static.footer')
	</div>
</div>
<script>
    (function(w,d,s,g,js,fs){
      g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
      js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
      js.src='https://apis.google.com/js/platform.js';
      fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
    }(window,document,'script'));
</script>
<script src="admin/js/analytics-active-users.js"></script>
<script src="admin/js/analytics-view-selector2.js"></script>
<script>
    function getNewAccessTokenAnalytic(){
        var action = 'https://staredu.tech5s.net/tech5s-access-token';
        return httpGet(action);
    }
    function httpGet(theUrl){
        var xmlHttp = new XMLHttpRequest();
        xmlHttp.open( "GET", theUrl, false );
        xmlHttp.send( null );
        return xmlHttp.responseText;
    }
    gapi.analytics.ready(function() {
        
        gapi.analytics.auth.authorize({
            'serverAuth': {
                'access_token': getNewAccessTokenAnalytic()
            }
        });
        var home_analytic = new gapi.analytics.googleCharts.DataChart({
            query: {
              'ids': 'ga:{{$gaViewKey}}', // <-- Replace with the ids value for your view.
              'start-date': '30daysAgo',
              'end-date': 'yesterday',
              'metrics': 'ga:sessions,ga:users',
              'dimensions': 'ga:date'
            },
            chart: {
              'container': 'home_analytic',
              'type': 'LINE',
              options: {
                width: '100%',
                colors: [
                	'#fa4410',
                	'#EA5275'
                ],
              },
            },
        });
        home_analytic.execute();
        var dataChart1 = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:{{$gaViewKey}}',
                'start-date': '30daysAgo',
                'end-date': 'yesterday',
                'metrics': 'ga:users',
                'dimensions': 'ga:country',
                'sort': '-ga:users',
                'max-results': 7
            },
            chart: {
                'container': 'chart-1-container',
                'type': 'BAR',
                options: {
                  width: '100%',
                  colors: ['#fa4410'],
                },
            }
        });
        dataChart1.execute();
        var dataChart2 = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:{{$gaViewKey}}', 
                'start-date': '30daysAgo',
                'end-date': 'yesterday',
                'dimensions': 'ga:source',
                'metrics': 'ga:users',
            },
            chart: {
                'container': 'chart-2-container',
                'type': 'COLUMN',
                'options': {
                    'width': '100%',
                    'pieHole': 4/9,
                    colors: ['#fa4410'],
                }
            }
        });
        dataChart2.execute();
        var dataChart3 = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:{{$gaViewKey}}',
                'start-date': '30daysAgo',
                'end-date': 'yesterday',
                'metrics': 'ga:pageviews,ga:pageValue',
                'dimensions': 'ga:pagePathLevel1',
                'sort': '-ga:pageviews',
                'filters': 'ga:pagePathLevel1!=/',
                'max-results': 7
            },
            chart: {
                'container': 'access-pages',
                'type': 'TABLE',
                options: {
                	
                	width: '100%',
                	colors: ['#fa4410'],
              },
            }
        });
        dataChart3.execute();
        var operatingSystem = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:{{$gaViewKey}}',
                'start-date': '30daysAgo',
                'end-date': 'yesterday',
                'metrics': 'ga:users',
                'dimensions': 'ga:operatingSystem',
            },
            chart: {
                'container': 'operatingSystem',
                'type': 'PIE',
                options: {
                width: '100%',
               	colors: ['#fa4410', '#EA5275']
              },
            }
        });
        operatingSystem.execute();
        var campaign_analytic = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:{{$gaViewKey}}',
                'start-date': '30daysAgo',
                'end-date': 'yesterday',
                'metrics': 'ga:adClicks',
                'dimensions': 'ga:adGroup',
            },
            chart: {
                'container': 'campaign_analytic',
                'type': 'LINE',
                options: {
                width: '100%',
                colors: ['#fa4410'],
              },
            }
        });
        campaign_analytic.execute();
        
        var activeUsers = new gapi.analytics.ext.ActiveUsers({
            'ids': 'ga:{{$gaViewKey}}',
            'container': 'active-users-container',
            'pollingInterval': 5
        });
        activeUsers.once('success', function() {
            var element = this.container.firstChild;
            var timeout;
            this.on('change', function(data) {
                var element = this.container.firstChild;
                var animationClass = data.delta > 0 ? 'is-increasing' : 'is-decreasing';
                element.className += (' ' + animationClass);
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                element.className =
                    element.className.replace(/ is-(increasing|decreasing)/g, '');
                }, 3000);
            });
        });
        activeUsers.execute();
        var time_active_page = new gapi.analytics.googleCharts.DataChart({
            query: {
                'ids': 'ga:{{$gaViewKey}}',
                'dimensions': 'ga:hour',
                'start-date': 'today',
                'end-date': 'today',
                'metrics': 'ga:pageviews',
                'max-results': 24
            },
            chart: {
                type: 'COLUMN',
                container: 'time_active_page',
                options: {
                    fontSize: 12,
                    width: '100%',
                    colors: ['#fa4410']
                }
            }
        });
        time_active_page.execute();
    });
</script>