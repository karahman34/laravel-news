<div id="trending-news-chart" class="card chart">
  <div class="card-header">
    <h4>
      <i class="fas fa-newspaper mr-1"></i>
      Trending News In The Last 7 Days
    </h4>
  </div>

  <div class="card-body">
    {{-- Loading --}}
    <i class="fas fa-spinner fa-spin loading-indicator"></i>

    {{-- Chart --}}
    <canvas id="trendingNewsChart" class="d-none">Your browser does not support the canvas
      element.</canvas>
  </div>
</div>

@push('script')
  <script>
    // Getting news 
    $.get("{{ route('administrator.dashboard.trending_news') }}")
      .done(res => {
        // Remove loading indicator.
        $('#trending-news-chart .loading-indicator').remove()

        const news = res.data
        const chartEl = document.getElementById('trendingNewsChart')
        const ctx = chartEl.getContext('2d');

        // Remove d-none class from Chart.
        chartEl.classList.remove('d-none')

        const trendingNewsChart = new Chart(ctx, {
          type: 'horizontalBar',
          data: {
            labels: news.map(_news => _news.title),
            datasets: [{
              label: 'Views',
              data: news.map(_news => _news.views),
              backgroundColor: [
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(255, 206, 86)',
                'rgb(75, 192, 192)',
                'rgb(153, 102, 255)',
                'rgb(255, 159, 64)',
                'rgb(173,216,230)',
                'rgb(210,105,30)',
                'rgb(70,130,180)',
                'rgb(32,178,170)',
                'rgb(0,255,0)',
                'rgb(165,42,42)',
                'rgb(54, 162, 235)',
                'rgb(173,216,230)',
              ],
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }],
            }
          }
        })
      })
      .fail(err => Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: err?.responseJSON?.message || 'Failed to get trending news data.',
      }))

  </script>
@endpush
