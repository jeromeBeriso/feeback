function get_subject() {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "ajax/subject.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  
    xhr.onload = function () {
      document.getElementById("subject-data").innerHTML = this.responseText;
    };
    xhr.send("get_subject");
  }
  window.onload = function(){
    get_subject();
  }