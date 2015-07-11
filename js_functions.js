 // Facebook
 /* // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      databaseCheck();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
      FB.init({
        appId      : '1576331335963540',
        cookie     : true,  // enable cookies to allow the server to access 
                            // the session
        xfbml      : true,  // parse social plugins on this page
        version    : 'v2.2' // use version 2.2
      });

      // Now that we've initialized the JavaScript SDK, we call 
      // FB.getLoginStatus().  This function gets the state of the
      // person visiting this page and can return one of three states to
      // the callback you provide.  They can be:
      //
      // 1. Logged into your app ('connected')
      // 2. Logged into Facebook, but not your app ('not_authorized')
      // 3. Not logged into Facebook and can't tell if they are logged into
      //    your app or not.
      //
      // These three cases are handled in the callback function.

      FB.getLoginStatus(function(response) {
        statusChangeCallback(response);
      });
  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function databaseCheck() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
        console.log(response);
        $.get("entry.php?action=facebook, function() { 
            alert ("Uspješna prijava preko Facebooka!"); 
        });
    });
  }

*/
// funkcije za provjeru forme

function checkUsername() {
    var name = $("input[name=txtUsername]").val();

    if (name.length <= 6)
        $("#spanUsername").text("Korisničko ime mora biti duljine barem 6!");
    else
        $("#spanUsername").text("");
        
}

function checkMail() {
    var mail = $("input[name=regMail]").val();
    console.log(mail);

    regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(mail))
        $("#spanMail").text("Pogrešna e-mail adresa!");
    else 
        $("#spanMail").text("");
}

function checkPasswordMatch() {
    var password = $("input[name=regPassword]").val();
    var confirmPassword = $("input[name=regConfirmPassword]").val();

    if (password != confirmPassword)
        $("#spanPasswordMatch").text("Lozinke nisu jednake!");
    else
        $("#spanPasswordMatch").text("");
}

  // funkcije za crtanje
  var canvas, ctx, flag = false,
      prevX = 0,
      currX = 0,
      prevY = 0,
      currY = 0,
      dot_flag = false;

  var x = "black",
      y = 2;

  function init() {
      canvas = document.getElementById('can');
      ctx = canvas.getContext("2d");
      w = canvas.width;
      h = canvas.height;

      canvas.addEventListener("mousemove", function (e) {
          findxy('move', e)
      }, false);
      canvas.addEventListener("mousedown", function (e) {
          findxy('down', e)
      }, false);
      canvas.addEventListener("mouseup", function (e) {
          findxy('up', e)
      }, false);
      canvas.addEventListener("mouseout", function (e) {
          findxy('out', e)
      }, false);
  }

  function color(obj) {
      switch (obj.id) {
          case "green":
              x = "green";
              break;
          case "blue":
              x = "blue";
              break;
          case "red":
              x = "red";
              break;
          case "yellow":
              x = "yellow";
              break;
          case "orange":
              x = "orange";
              break;
          case "black":
              x = "black";
              break;
          case "white":
              x = "white";
              break;
      }
      if (x == "white") y = 14;
      else y = 2;

  }

  function draw() {
      ctx.beginPath();
      ctx.moveTo(prevX, prevY);
      ctx.lineTo(currX, currY);
      ctx.strokeStyle = x;
      ctx.lineWidth = y;
      ctx.stroke();
      ctx.closePath();
  }

  function erase() {
      var m = confirm("Želite li stvarno obrisati to prekrasno što ste nacrtali?");
      if (m) {
          ctx.clearRect(0, 0, w, h);
          document.getElementById("canvasimg").style.display = "none";
      }
  }

  function findxy(res, e) {
      if (res == 'down') {
          prevX = currX;
          prevY = currY;
          currX = e.clientX - canvas.offsetLeft;
          currY = e.clientY - canvas.offsetTop;

          flag = true;
          dot_flag = true;
          if (dot_flag) {
              ctx.beginPath();
              ctx.fillStyle = x;
              ctx.fillRect(currX, currY, 2, 2);
              ctx.closePath();
              dot_flag = false;
          }
      }
      if (res == 'up' || res == "out") {
          flag = false;
      }
      if (res == 'move') {
          if (flag) {
              prevX = currX;
              prevY = currY;
              currX = e.clientX - canvas.offsetLeft;
              currY = e.clientY - canvas.offsetTop;
              draw();
          }
      }
  }

  function uploadImage() {
    
    // Generate the image data
    var Pic = document.getElementById("can").toDataURL("image/png");

   // Pic = Pic.replace(/^data:image\/(png|jpg);base64,/, "");
  //  alert(Pic);
    // Sending the image data to server
    $.ajax({
        type: 'POST',
        url: 'save.php',
        data:  Pic ,
     //   contentType: 'application/json; charset=utf-8',
       // dataType: 'json',
        success: function (msg) {
            alert("Done, Picture Uploaded.");
        }
    });
}
