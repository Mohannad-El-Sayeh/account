const fields = document.querySelectorAll("input");
const submit = document.querySelector("#submit");
const error = document.getElementById("error");
const agree = document.getElementById("agree");
const gender = document.getElementById("gender");
const switchModeButton = document.getElementById("login_instead");

const LOGIN = 1;
const SIGNUP = 0;

var mode = SIGNUP; // 0 = Sign up and 1 = Log in

switchModeButton.addEventListener("click", switchMode);

function switchMode() {
  if (mode === SIGNUP) {
    const form = document.querySelector("form");
    form.removeChild(document.getElementById("name_div"));
    form.removeChild(document.getElementById("gender-div"));
    form.removeChild(document.getElementById("agree-div"));
    submit.value = "Login";
    submit.setAttribute('name', 'login');
    document.getElementById("header").innerText = "Login";
    document.getElementById("switch").innerHTML =
      '<p id="switch" style="cursor: context-menu;">Don\'t have an account?<br /><span id="signup_instead" style="color: rgb(72, 72, 206); text-decoration: underline; cursor: pointer;">Create one now.</span></p>';
    document
      .getElementById("signup_instead")
      .addEventListener("click", switchMode);
    document.title = "Login";
    mode = LOGIN;
  } else if (mode === LOGIN) {
    mode = SIGNUP;
    window.location.reload();
  }
}