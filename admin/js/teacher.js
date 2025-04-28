function get_teacher() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/teacher.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onload = function () {
    document.getElementById("teacher-data").innerHTML = this.responseText;
  };
  xhr.send("get_teacher");
}
let teacher_form = document.getElementById("teacher_form");

teacher_form.addEventListener("submit", function (e) {
  e.preventDefault();
  add_teacher();
});
function add_teacher() {
  let data = new FormData();
  data.append("add_teacher", "");
  data.append("name", teacher_form.elements["name"].value);
  data.append("ID", teacher_form.elements["ID"].value);
  data.append("faculty", teacher_form.elements["faculty"].value);

  let subject = [];

  document.querySelectorAll("input[name='subj']:checked").forEach((el) => {
    subject.push(el.value);
    console.log("subject selected:", el.value);
  });

  data.append("subject", JSON.stringify(subject));

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/teacher.php", true);
  xhr.onload = function () {
    if (this.responseText == 1) {
      alert("success", "added a faculty successfully");
      teacher_form.reset();
      get_teacher();
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
  xhr.open("POST", "ajax/teacher.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onload = function () {
    if (this.responseText == 1) {
      if (val == 1) {
        Swal.fire({
          title: "Account Activated successfully !",
          text: "Account is now usable !",
          icon: "success",
          customClass: {
            confirmButton: "bg-primary",
          },
        });
        get_teacher();
      } else {
        Swal.fire({
          title: "Accoun Successfully Deactivated !",
          text: "It won't be usable now !",
          icon: "success",
          customClass: {
            confirmButton: "bg-primary",
          },
        });
        get_teacher();
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
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll('[data-bs-target="#edit"]').forEach((btn) => {
    btn.addEventListener("click", function () {
      const id = this.getAttribute("data-id");
      fetch_teacher_details(id);
    });
  });
});

function fetch_teacher_details(id) {
  const xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/teacher.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onload = function () {
    let res = JSON.parse(this.responseText);
    if (res.status === "success") {
      document.getElementById("teacher_id").value = res.data.id;
      document.getElementById("name").value = res.data.name;
      document.querySelector('select[name="faculty"]').value =
        res.data.faculty_id;

      // Uncheck all first
      document
        .querySelectorAll("input[name='subj']")
        .forEach((checkbox) => (checkbox.checked = false));

      // Recheck assigned subjects
      res.data.subjects.forEach((sub_id) => {
        let box = document.querySelector(
          `input[name='subj'][value='${sub_id}']`
        );
        if (box) box.checked = true;
      });
    }
  };

  xhr.send("get_teacher=" + id);
}

window.onload = function () {
  get_teacher();
};
