function get_subject() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/subject.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onload = function () {
    document.getElementById("subject-data").innerHTML = this.responseText;
  };
  xhr.send("get_subject");
}
let subject_form = document.getElementById("subject_form");

subject_form.addEventListener("submit", function (e) {
  e.preventDefault();
  add_subject();
});
function add_subject() {
  let data = new FormData();
  data.append("add_subject", "");
  data.append("subject", subject_form.elements["subject"].value);
  data.append("CODE", subject_form.elements["CODE"].value);

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/subject.php", true);
  xhr.onload = function () {
    if (this.responseText == 1) {
      alert("success", "added a subject successfully");
      subject_form.reset();
      get_subject();
    } else if (this.responseText === "duplicate") {
      alert("error", "Already exist");
    } else {
      alert("error", "system is down");
    }
  };
  xhr.send(data);
}
function remove_subject(subject_id) {
  // Show a Swal confirmation modal before proceeding
  Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, remove this room",
    cancelButtonText: "Cancel",
  }).then((result) => {
    if (result.isConfirmed) {
      let data = new FormData();
      data.append("remove_subject", "1"); // Corrected key
      data.append("id", subject_id); // Corrected key to match PHP

      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/subject.php", true);

      xhr.onload = function () {
        console.log(this.responseText);
        if (this.responseText == "1") {
          Swal.fire({
            title: "subject successfully removed!",
            text: "This room number is no longer available!",
            icon: "success",
          }).then(() => {
            get_subject();
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Failed to remove this subject!",
            text: "Oops...Something went wrong!",
          });
        }
      };
      xhr.send(data);
    }
  });
}

window.onload = function () {
  get_subject();
};
