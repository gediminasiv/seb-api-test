$(document).ready(function(){
  $('.btn-scan-activate').click(function() {
    scanner();
  });
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
