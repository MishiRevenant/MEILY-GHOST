const sidebar = document.getElementById('sidebar');
if (sidebar) {
  sidebar.addEventListener('mouseenter', () => {
    sidebar.classList.add('expanded');
    document.body.classList.add('sidebar-expanded');
  });
  sidebar.addEventListener('mouseleave', () => {
    sidebar.classList.remove('expanded');
    document.body.classList.remove('sidebar-expanded');
  });
}
