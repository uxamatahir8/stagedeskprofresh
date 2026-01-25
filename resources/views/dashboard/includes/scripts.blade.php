<!-- Vendor js -->
<script src="{{ asset('js/vendors.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('js/app.js') }}"></script>

{{-- Validation JS --}}
<script src="{{ asset('js/validation.js') }}"></script>

{{-- Notifications JS --}}
<script src="{{ asset('js/notifications.js') }}"></script>

<!-- Jquery for Datatables-->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

<!-- Datatables js -->
<script src="{{ asset('plugins/datatables/dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/responsive.bootstrap5.min.js') }}"></script>

<script src="{{ asset('plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/buttons.bootstrap5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/vfs_fonts.js') }}"></script>
<script src="{{ asset('plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/buttons.print.min.js') }}"></script>

<!-- Page js -->
<script src="{{ asset('js/pages/datatables-export-data.js') }}"></script>

<!-- Fix dropdown/sidebar z-index conflict -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Close topbar dropdowns when clicking on sidebar (not sidebar menus)
        const sidebar = document.querySelector('.sidenav-menu');
        const sidebarToggle = document.querySelector('.sidenav-toggle-button');

        // Function to close only topbar dropdowns (notifications, user menu)
        function closeTopbarDropdowns() {
            // Only target dropdowns in the topbar, not in the sidebar
            const topbarDropdowns = document.querySelectorAll('.app-topbar .dropdown-menu');

            topbarDropdowns.forEach(dropdown => {
                const dropdownButton = dropdown.previousElementSibling;
                if (dropdownButton) {
                    const bsDropdown = bootstrap.Dropdown.getInstance(dropdownButton);
                    if (bsDropdown) {
                        bsDropdown.hide();
                    }
                }
            });
        }

        // Close topbar dropdowns when clicking sidebar navigation
        if (sidebar) {
            sidebar.addEventListener('click', function(event) {
                // Only close topbar dropdowns if clicking on an actual menu link, not the menu toggle
                const isMenuLink = event.target.closest('.side-nav-link');
                if (isMenuLink && !event.target.closest('[data-bs-toggle="collapse"]')) {
                    closeTopbarDropdowns();
                }
            });
        }

        // Close topbar dropdowns when toggling sidebar
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', closeTopbarDropdowns);
        }
    });
</script>
