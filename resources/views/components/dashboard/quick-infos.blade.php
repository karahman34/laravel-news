<div class="row">
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-success">
        <i class="fas fa-user"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Total Admin</h4>
        </div>
        <div class="card-body">
          {{ $totalAdmin }}
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-primary">
        <i class="fas fa-pencil-ruler"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Author</h4>
        </div>
        <div class="card-body">
          {{ $totalAuthor }}
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-danger">
        <i class="far fa-newspaper"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>News</h4>
        </div>
        <div class="card-body">
          {{ $totalNews }}
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-md-6 col-sm-6 col-12">
    <div class="card card-statistic-1">
      <div class="card-icon bg-warning">
        <i class="fas fa-tags"></i>
      </div>
      <div class="card-wrap">
        <div class="card-header">
          <h4>Tags</h4>
        </div>
        <div class="card-body">
          {{ $totalTags }}
        </div>
      </div>
    </div>
  </div>
</div>
