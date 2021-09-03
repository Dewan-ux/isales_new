'use strict'
function logoutMessage(){
    return confirm('Apakah anda yakin akan keluar?');
}
$('.logout').click(function() {
    return logoutMessage();
});
