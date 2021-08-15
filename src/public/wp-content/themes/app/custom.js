(function () {
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.js-filter-form').forEach(filterForm => {
            filterForm.querySelectorAll('select').forEach(input => {
                input.addEventListener('change', function () {
                    filterForm.submit();
                });
            });
        });
    });
})();