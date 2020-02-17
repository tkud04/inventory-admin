<html>
      <head>
	     <link rel="stylesheet" href="css/bootstrap.min.css">
	  </head>
      <body>
	    <div class="container">
		  <div class="col-lg-12" style="margin-top: 20%;">
		    <div class="card">
			  <div class="card-body">
			    <center><img style="height: 100px; align:center;" class="img img-fluid" src="img/icon.png"> </center>
			    <h1 class="text-success text-center">Payment successful! Redirecting you to Tembo.. <img style="margin-left: 10px; height: 25px;" class="img img-fluid" src="img/loading.gif"></h1>
				
			  </div>
			</div>
		  </div>
		</div>
      	
		<input type="hidden" id="tmb" value="{{$dt}}"/>
		<script src="js/jquery-3.3.1.min.js"></script>
		 <script src="js/bootstrap.min.js"></script>
        <script>
          setTimeout(function () {
            window.ReactNativeWebView.postMessage(document.querySelector('#tmb').value);
          }, 2000)
        </script>
		
      </body>
      </html>