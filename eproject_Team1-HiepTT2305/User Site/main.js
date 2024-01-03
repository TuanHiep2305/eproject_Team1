
document.getElementById('userIcon').addEventListener('click', function () {
    var loginOptions = document.getElementById('loginOptions');
    if (loginOptions.style.display === 'none' || loginOptions.style.display === '') {
        loginOptions.style.display = 'block';
    } else {
        loginOptions.style.display = 'none';
    }
});
