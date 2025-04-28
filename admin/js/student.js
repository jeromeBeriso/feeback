function get_student() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/student.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onload = function () {
    document.getElementById("student-data").innerHTML = this.responseText;
  };
  xhr.send("get_student");
}
let student_form = document.getElementById("student_form");

student_form.addEventListener("submit", function (e) {
  e.preventDefault();
  add_student();
});
function add_student() {
  let data = new FormData();
  data.append("add_student", "");
  data.append("name", student_form.elements["name"].value);
  data.append("student_id", student_form.elements["student_id"].value);
  data.append("faculty", student_form.elements["faculty"].value);

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/student.php", true);
  xhr.onload = function () {
    if (this.responseText == 1) {
      alert("success", "added a student successfully");
      student_form.reset();
      get_student();
    } else if (this.responseText === "duplicate") {
      alert("error", "Already exist");
    } else {
      alert("error", "system is down");
    }
  };
  xhr.send(data);
}
function toggle_status(id, val) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/student.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onload = function () {
    if (this.responseText == 1) {
      if (val == 1) {
        Swal.fire({
          title: "Activated successfully !",
          text: "It will be visible on the webpage now !",
          icon: "success",
          customClass: {
            confirmButton: "bg-primary",
          },
        });
        get_student();
      } else {
        Swal.fire({
          title: "Successfully Deactivated !",
          text: "It won't be visible on the webpage now !",
          icon: "success",
          customClass: {
            confirmButton: "bg-primary",
          },
        });
        get_all_rooms();
      }
    } else {
      Swal.fire({
        icon: "error",
        title: "Failed to initiate changes !",
        text: "Oops...Something went wrong !",
        footer: '<a href="#">Why do I have this issue?</a>',
        customClass: {
          confirmButton: "bg-primary",
        },
      });
    }
  };
  xhr.send("toggle_status=" + id + "&value=" + val);
}

window.onload = function () {
  get_student();
};
