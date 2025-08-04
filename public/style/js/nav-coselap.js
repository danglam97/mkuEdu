document.addEventListener("DOMContentLoaded", function () {
  var collapseElements = document.querySelectorAll(
    ".nav-link[data-bs-toggle='collapse']"
  );

  collapseElements.forEach(function (element) {
    element.addEventListener("click", function (event) {
      var targetId = this.getAttribute("href");
      var targetCollapse = document.querySelector(targetId);

      collapseElements.forEach(function (otherElement) {
        var otherTargetId = otherElement.getAttribute("href");
        var otherCollapse = document.querySelector(otherTargetId);
        if (otherCollapse) {
          var bsCollapse = bootstrap.Collapse.getInstance(otherCollapse);
          if (bsCollapse) {
            bsCollapse.hide();
          } else {
            otherCollapse.classList.remove("show");
          }
        }
      });

      var bsCollapse = bootstrap.Collapse.getInstance(targetCollapse);
      if (bsCollapse) {
        if (!targetCollapse.classList.contains("show")) {
          bsCollapse.show();
        }
      } else {
        new bootstrap.Collapse(targetCollapse, { toggle: true });
      }
    });
  });
});
