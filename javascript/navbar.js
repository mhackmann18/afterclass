const userButton = document.getElementById("user-btn");
const userMenu = document.getElementById("user-menu");
const logoutBtn = document.getElementById("log-out");

logoutBtn.onclick = () => {
  $.post('./php/PROCESS.php', { action: 'logout' }, () => {
    window.location.replace("./login.php");
  });
}