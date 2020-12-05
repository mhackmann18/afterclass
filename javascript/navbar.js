const userButton = document.getElementById("user-btn");
const userMenu = document.getElementById("user-menu");
const logoutBtn = document.getElementById("log-out");
const navbar = document.getElementById("main-navbar");

logoutBtn.onclick = () => {
  $.post('./php/process.php', { action: 'logout' });
}

userButton.onclick = () => {
  window.location.replace("./yourProfile.php");
}

// Credit to https://stackoverflow.com/questions/31223341/detecting-scroll-direction/31223774

let lastScrollTop = 0;

window.onscroll = () => {
  let body = document.body, html = document.documentElement;

  let documentHeight = Math.max(body.scrollHeight, body.offsetHeight, html.clientHeight, html.scrollHeight, html.offsetHeight );

  let windowHeight = window.innerHeight || html.clientHeight || body.clientHeight;

  let st = window.pageYOffset || document.documentElement.scrollTop;

  // If the document height is significantly larger than the window height, and the user is scrolling down, collapse the navbar
  if(st > lastScrollTop && windowHeight + 100 < documentHeight){
    navbar.style.top = "-6vw";
    userMenu.style.top = "-10vw";
  } else {
    navbar.style.top = "0";
    userMenu.style.top = "5vw";
  }
  lastScrollTop = st <= 0 ? 0 : st;
}