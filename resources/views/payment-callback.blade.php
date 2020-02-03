<html>
      <head></head>
      <body>
      	<p>Payment successful! Redirecting you to Tembo..</p>
        <script>
          setTimeout(function () {
            window.ReactNativeWebView.postMessage("{{$dt}}");
          }, 2000)
        </script>
      </body>
      </html>