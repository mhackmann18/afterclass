import showPost from './modules/showPost.js';
import { displayBlock, displayNone } from './modules/utilities.js';

const feedDiv = document.getElementById("feed");
const spinner = document.getElementById("spinner");

document.querySelector('body').onload = async function(){
  displayBlock(spinner);
  let posts = JSON.parse(await $.get("./php/process.php", { action: 'get-logged-in-user-feed-posts' }));
  
  if(posts.length){
    for(let post of posts){
      await showPost(post, feedDiv, true);
    }
    displayNone(spinner);
  } else {
    displayNone(spinner);
    displayBlock(document.getElementById("home-no-posts-msg"));
  }
}