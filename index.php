<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Smart's Task</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="navbar-brand" href="#">Dashboard</a>
      <button class="navbar-toggler d-lg-none" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
          </li>
        </ul>
        <form class="form-inline mt-2 mt-md-0">
          <input class="form-control mr-sm-2" type="text" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
        </form>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-sm-3 col-md-2 d-none d-sm-block bg-light sidebar">
          <ul class="nav nav-pills flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="#">Overview <span class="sr-only">(current)</span></a>
            </li>
          </ul>


        </nav>

        <main class="col-sm-9 ml-sm-auto col-md-10 pt-3" role="main">
          <h1>Dashboard</h1>
            <div class="row">
                <div class="col-6">
          <section class="row text-center placeholders">
              <form method="POST" enctype=multipart/form-data action="upload.php" id="">
                  <div class="form-group files">
                      <label>Upload Your File </label>
                      <input type="file" class="form-control" multiple="" id="xmlFile" name="xmlFile">

                  </div>
                  <button type="button" class="btn btn-primary" id="uploadit">Upload</button>
              </form>
          </section>
                </div>

                <div class="col-6">
              <div class="form-group">
                  <label>Uploaded Files </label>
                  <ul class="list-group uploaded">
                  </ul>

              </div>
                    <button type="button" class="btn btn-info" id="parse" name="parse">Parse File/s</button>
                </div>


            </div>

            <h2>Search Box</h2>
            <div class="input-group">
                <input type="text" class="form-control typeahead" id="typeahead" data-provide="typeahead">
            </div>
            <div id="suggesstion-box"></div>

            <hr>

            <div class="row">
                <div class="col-12 distance_table">
                </div>
            </div>

        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>


    <script>
        $(document).on("click", "#uploadit", function(event){
            var formData = new FormData();
            formData.append('file', $('#xmlFile')[0].files[0]);

                $.ajax({
                    contentType: false,
                    processData: false,
                    type:'POST',
                    url:'upload.php',
                    data:formData

                }).done(function(results){
                    //var str = JSON.stringify(results);
                    //alert(results);
                    location.reload();
                });


        });


        $(document).on("click", "#parse", function(event){
            $.ajax({
                contentType: false,
                processData: false,
                type:'POST',
                url:'parser.php',
                data:'parse='+'parse'

            }).done(function(results){
                //var str = JSON.stringify(results);
                //alert(results);
                alert("All files successfully parsed!");
            });


        });
    </script>

    <script>
        $(document).ready(function(){
            $.ajax({
                type: "POST",
                url: "helper.php",
                data:'data='+'fileList',
                success: function(data){

                    $(".uploaded").html(data);
                }
            });


            $('#typeahead').keyup(function(){
                $.ajax({
                    type: "POST",
                    url: "fetch.php",
                    data:'query='+$(this).val(),
                    beforeSend: function(){
                        $("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
                    },
                    success: function(data){
                        $("#suggesstion-box").show();
                        $("#suggesstion-box").html(data);
                        $("#search-box").css("background","#FFF");
                    }
                });
            });
            });

        function selectCountry(item) {
            //To select country name
                var val = $(item).text();
                var id = $(item).data('id');
                //alert(id);
                $("#typeahead").val(val);
                $("#suggesstion-box").hide();

            $.ajax({
                type: "POST",
                url: "measure.php",
                data:'id='+id,
                success: function(data){
                    var tableData = "";

                    $('.distance_table').html(data);

                }
            });

        }

    </script>

  </body>
</html>
