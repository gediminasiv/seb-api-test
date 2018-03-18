$(document).ready(function(){
  $('.btn-scan-activate').click(function() {
    scanner();
  });

  $('.pay-button').click(function(){
      $.get('/set-amount', { amount: $(this).data('sum') }, function (data) {
        window.location.href='/select-account';
      });
  });

  if ($('.console-output')) {
    setTimeout(function() {

      $('.console-output').append('<li class="list-group-item" style="color: green;">Authentication confirmed</li>');

      setTimeout(function() {
        $('.console-output').append('<li class="list-group-item">Authorizing account... (POST https://test.api.ob.baltics.sebgroup.com/v1/user/authorization/xx/token)</li>');

        setTimeout(function(){
          $('.console-output').append('<li class="list-group-item" style="color: green;">Account authenticated</li>');

          setTimeout(function() {
            $('.console-output').append('<li class="list-group-item">Fetching available client accounts... (GET https://test.api.ob.baltics.sebgroup.com/v1/bics/CBVILT2X/accounts)</li>');

            $('.console-output').hide();
            $('.select-account').show();
          }, 1100)
        }, 800);
      }, 300)
    }, 1200);
  }
});

function scanner() {
    let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
    scanner.addListener('scan', function (content) {
      $.get('/scan-data', { content: content }, function (data) {
        window.location.href='/qr-info';
      });
    });
    Instascan.Camera.getCameras().then(function (cameras) {
      if (cameras.length > 0) {
        scanner.start(cameras[0]);
      } else {
        console.error('No cameras found.');
      }
    }).catch(function (e) {
      console.error(e);
    });
}
