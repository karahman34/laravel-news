<div id="popular-tags-chart" class="card chart">
  <div class="card-header">
    <h4>
      <i class="fas fa-tags mr-1"></i>
      Popular Tags
    </h4>
  </div>

  <div class="card-body">
    {{-- Loading --}}
    <i class="fas fa-spinner fa-spin loading-indicator"></i>

    {{-- Chart --}}
    <canvas id="popularTagsChart" class="d-none">Your browser does not support the canvas
      element.</canvas>
  </div>
</div>

@push('script')
  <script>
    // Getting tags 
    $.get("{{ route('administrator.dashboard.popular_tags') }}")
      .done(res => {
        // Remove loading indicator.
        $('#popular-tags-chart .loading-indicator').remove()

        const tags = res.data
        const chartEl = document.getElementById('popularTagsChart')
        const ctx = chartEl.getContext('2d');

        // Remove d-none class from Chart.
        chartEl.classList.remove('d-none')

        const popularTagsChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: tags.map(tag => tag.name),
            datasets: [{
              label: 'Views',
              data: tags.map(tag => tag.views),
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
                'rgb(255, 99, 132)',
                'rgb(255, 159, 64)',
                'rgb(210,105,30)',
                'rgb(165,42,42)',
                'rgb(54, 162, 235)',
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
              }]
            }
          }
        })
      })
      .fail(err => Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: err?.responseJSON?.message || 'Error while getting popular tags data.',
      }))

  </script>
@endpush
