function get_faculty() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/faculty.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  xhr.onload = function () {
    document.getElementById("faculty-data").innerHTML = this.responseText;
  };
  xhr.send("get_faculty");
}
let faculty_form = document.getElementById("faculty_form");

faculty_form.addEventListener("submit", function (e) {
  e.preventDefault();
  add_faculty();
});
function add_faculty() {
  let data = new FormData();
  data.append("add_faculty", "");
  data.append("faculty", faculty_form.elements["faculty"].value);
  data.append("ABV", faculty_form.elements["ABV"].value);

  let xhr = new XMLHttpRequest();
  xhr.open("POST", "ajax/faculty.php", true);
  xhr.onload = function () {
    if (this.responseText == 1) {
      alert("success", "added a faculty successfully");
      faculty_form.reset();
      get_faculty();
    } else if (this.responseText === "duplicate") {
      alert("error", "Already exist");
    } else {
      alert("error", "system is down");
    }
  };
  xhr.send(data);
}
function remove_faculty(faculty_id) {
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
      data.append("remove_faculty", "1"); // Corrected key
      data.append("id", faculty_id); // Corrected key to match PHP

      let xhr = new XMLHttpRequest();
      xhr.open("POST", "ajax/faculty.php", true);

      xhr.onload = function () {
        console.log(this.responseText);
        if (this.responseText == "1") {
          Swal.fire({
            title: "Faculty successfully removed!",
            text: "This room number is no longer available!",
            icon: "success",
          }).then(() => {
            get_faculty();
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Failed to remove this room!",
            text: "Oops...Something went wrong!",
          });
        }
      };
      xhr.send(data);
    }
  });
}
window.onload = function () {
  get_faculty();
};
