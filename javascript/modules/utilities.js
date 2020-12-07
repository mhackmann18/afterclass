function changeWatchLinkToEmbedLink(link){
  let i = link.split("v=");
  let embedLink = `https://www.youtube.com/embed/${i[1]}`;
  return embedLink;
}

function getCurrentDateString(){
  let date = new Date();
  const year = date.getFullYear();
  let month = date.getMonth() + 1;
  let day = date.getDate();
  if(day < 10){
    day = "0" + String(day);
  }
  return `${month}/${day}/${year}`;
}

function displayBlock(...els){
  for(let el of els)
    el.style.display = "block";
}

function displayNone(...args){
  for(let el of args)
    el.style.display = "none";
}

function getCookie(name) {
  const value = `; ${document.cookie}`;
  const parts = value.split(`; ${name}=`);
  if (parts.length === 2) return parts.pop().split(';').shift();
}

function getUIDateStringFromDBDate(dateString){
  let dateParts = dateString.split("-");
  let year = dateParts[0].slice(-2);
  return `${dateParts[1]}/${dateParts[2].slice(0,2)}/${year}`;
}

export { 
  changeWatchLinkToEmbedLink, 
  getCurrentDateString, 
  displayBlock, 
  displayNone, 
  getCookie, 
  getUIDateStringFromDBDate 
};