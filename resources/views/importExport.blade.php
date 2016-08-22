<html lang="en">
<head>
  <title>Import - Export Laravel 5</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" >
</head>
<body>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
        <a class="navbar-brand" href="#">Import Bill of Materials (BOM)</a>
      </div>
    </div>
  </nav>
  <div class="container">
    <form style="border: 4px solid #a1a1a1;margin-top: 15px;padding: 10px;" action="{{ URL::to('importExcel') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
      <input type="file" name="import_file" />
      <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
      <button class="btn btn-primary">Import File</button>
    </form>
  </div>
</body>
</html>
