const userButton = document.getElementById("user-btn");
const userMenu = document.getElementById("user-menu");
const logoutBtn = userMenu.querySelector("ul").firstElementChild;

logoutBtn.onclick = () => {
  $.post('./process.php', { action: 'logout' }, () => {
    window.location.replace("./login.html");
  });
}


