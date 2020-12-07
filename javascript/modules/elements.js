const postPageElements = {
  form: document.getElementById("new-post-form"),
  groupSelect: document.querySelector("select"),
  ytLinkInput: document.getElementById("yt-link-input"),
  addImgBtn: document.getElementById("post-img-btn"),
  previewBtn: document.getElementById("preview-new-post-btn"),
  postTextarea: document.getElementById("new-post-text"),
  errMsgP: document.querySelector(".err-msg"),
  overlay: document.getElementById("overlay"),
  previewContainer: document.querySelector(".outer"),
  cancelPreviewBtn: document.getElementById("cancel-preview-btn"),
  changePostBtn: document.getElementById("change-post-btn"),
  fileInput: document.getElementById("file-upload"),
  mediaDiv: document.querySelector(".post-media"),
  postBtn: document.getElementById("confirm-post-btn")
}

const loggedInUserProfilePageElements = {
  changeImgBtn: document.getElementById("change-profile-image"),
  fileForm: document.getElementById("upload-image-form"),
  fileFormBtn: document.querySelector("#upload-image-form button"),
  fileFormInput: document.querySelector("#upload-image-form input"),
  editProfileBtn: document.getElementById("edit-profile-btn"),
  userInfo: document.getElementById("profile-page-info"),
  bio: document.querySelector("#profile-right p"),
  bioInput: document.querySelector("textarea"),
  buttonDiv: document.getElementById("profile-left"),
  cancelEditBtn: document.getElementById("cancel-edit-profile-btn"),
  saveChangesBtn: document.getElementById("save-edit-profile-btn"),
  inputsUl: document.getElementById("profile-page-inputs"),
  usernameInput: document.getElementById("username-input"),
  majorInput: document.getElementById("major-input"),
  inputErrMsg: document.getElementById("input-err-msg"),
  bioErrMsg: document.getElementById("bio-err-msg")
}

const userGroupsPageElements = {
  createGroupForm: document.getElementById("create-group-form"),
  overlay: document.getElementById("overlay"),
  nameInput: document.querySelector("#create-group-form input"),
  bioInput: document.querySelector("#create-group-form textarea"),
  createGroupErr: document.getElementById("create-group-err-msg"),
  leaveGroupWindow: document.getElementById("leave-group-confirmation-window"),
  spinner: document.getElementById("spinner")
}

export { 
  postPageElements, 
  loggedInUserProfilePageElements,
  userGroupsPageElements
};