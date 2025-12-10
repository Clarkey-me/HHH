<!-- admin/includes/scripts.php -->
<script>
// Shared across all admin pages
const sidebar = document.getElementById("sidebar");
const collapseBtn = document.getElementById("collapseSidebar");
const toggleIcon = collapseBtn.querySelector("i");
const hamburger = document.getElementById("hamburger");

hamburger.onclick = () => sidebar.classList.add("open");

collapseBtn.onclick = () => {
    sidebar.classList.toggle("collapsed");
    toggleIcon.classList.toggle("fa-angle-left");
    toggleIcon.classList.toggle("fa-angle-right");
};

// Theme Toggle
const themeToggle = document.getElementById("themeToggle");
const body = document.body;

if (localStorage.getItem("theme") === "light") {
    body.classList.add("light-mode");
}

themeToggle.addEventListener("click", () => {
    body.classList.toggle("light-mode");
    localStorage.setItem("theme", body.classList.contains("light-mode") ? "light" : "dark");
});
</script>