const userButton = document.getElementById("user-btn");
const userMenu = document.getElementById("user-menu");
const logoutBtn = document.getElementById("log-out");
const navbar = document.getElementById("main-navbar");

logoutBtn.onclick = () => {
  $.post('./php/PROCESS.php', { action: 'logout' });
}

userButton.onclick = () => {
  window.location.replace("./profile.php");
}

// Credit to https://stackoverflow.com/questions/31223341/detecting-scroll-direction/31223774

let lastScrollTop = 0;

window.onscroll = () => {
  let st = window.pageYOffset || document.documentElement.scrollTop;
  if (st > lastScrollTop){
    navbar.style.top = "-6vw";
    userMenu.style.top = "-10vw";
  } else {
    navbar.style.top = "0";
    userMenu.style.top = "5vw";
  }
  lastScrollTop = st <= 0 ? 0 : st;
}