function get_feedbacks() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/feedbacks.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  
    xhr.onload = function () {
      document.getElementById("feedbacks-data").innerHTML = this.responseText;
    };
    xhr.send("get_feedbacks");
  }
  window.onload = function(){
    get_feedbacks();
  }