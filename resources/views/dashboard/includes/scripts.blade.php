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
        // Close all dropdowns when clicking on sidebar or sidebar toggle
        const sidebar = document.querySelector('.sidenav-menu');
        const sidebarToggle = document.querySelector('.sidenav-toggle-button');
        const dropdowns = document.querySelectorAll('.dropdown-menu');

        // Function to close all dropdowns
        function closeAllDropdowns() {
            dropdowns.forEach(dropdown => {
                const bsDropdown = bootstrap.Dropdown.getInstance(dropdown.previousElementSibling);
                if (bsDropdown) {
                    bsDropdown.hide();
                }
            });
        }

        // Close dropdowns when clicking sidebar
        if (sidebar) {
            sidebar.addEventListener('click', closeAllDropdowns);
        }

        // Close dropdowns when toggling sidebar
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', closeAllDropdowns);
        }

        // Ensure dropdowns close when clicking outside
        document.addEventListener('click', function(event) {
            const isDropdownClick = event.target.closest('.dropdown');
            if (!isDropdownClick) {
                closeAllDropdowns();
            }
        });
    });
</script>
