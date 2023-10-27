document.addEventListener("DOMContentLoaded", function () {
  var wfanswers = document.querySelectorAll(".vfanswer");
  var vfquestions = document.querySelectorAll(".vfquestion");
  var filterInput = document.getElementById("vf-filter");

  // Hide all wfanswers initially
  wfanswers.forEach(function (vfanswer) {
    vfanswer.style.display = "none";
  });

  for (var i = 0; i < vfquestions.length; i++) {
    vfquestions[i].addEventListener("click", function () {
      var parentListItem = this.parentElement;
      var vfanswer = parentListItem.querySelector(".vfanswer");

      if (vfanswer.style.display === "none" || vfanswer.style.display === "") {
        // Hide all wfanswers and remove 'active' class from vfquestions
        vfquestions.forEach(function (q) {
          q.classList.remove("active");
        });
        wfanswers.forEach(function (a) {
          a.style.display = "none";
        });

        // Show the clicked vfanswer and add 'active' class to the wfquestion
        vfanswer.style.display = "block";
        this.classList.add("active");
      } else {
        // Hide the clicked vfanswer and remove 'active' class from the wfquestion
        vfanswer.style.display = "none";
        this.classList.remove("active");
      }
    });
  }

  filterInput.addEventListener("keyup", function () {
    var filter = filterInput.value.toLowerCase();

    var wfqandaListItems = document.querySelectorAll(".vfqanda li");
    var count = 0;

    wfqandaListItems.forEach(function (item) {
      var text = item.textContent.toLowerCase();
      if (text.includes(filter)) {
        item.style.display = "block";
        count++;
      } else {
        item.style.display = "none";
      }
    });
  });
});
