import ClipboardJS from "clipboard";

document.addEventListener("DOMContentLoaded", () => {
  new ClipboardJS("[data-clipboard]");

  document.querySelectorAll(".delete-project").forEach((a) => {
    a.addEventListener("click", (e) => {
      if (!window.confirm("Are you sure you want to delete this project?")) {
        e.preventDefault();
      }
    });
  });
});
