BX.ready(function () {
    function getContainer() {
        return document.getElementById('skin-history-container');
    }

    function getLoader() {
        return document.getElementById('skin-history-loader');
    }

    function showLoader() {
        var loader = getLoader();

        if (loader) {
            loader.style.display = 'block';
        }
    }

    function hideLoader() {
        var loader = getLoader();

        if (loader) {
            loader.style.display = 'none';
        }
    }

    document.addEventListener('click', function (event) {
        var target = event.target.closest('a, button');

        if (!target) {
            return;
        }

        if (
                target.closest('.skin-history__pagination') ||
                target.closest('.skin-history__controls')
        ) {
            showLoader();
        }
    });

    document.addEventListener('change', function (event) {
        var target = event.target;

        if (!target) {
            return;
        }

        if (target.closest('.skin-history__controls')) {
            showLoader();
        }
    });

    BX.addCustomEvent('onAjaxSuccess', function () {
        hideLoader();
    });

    BX.addCustomEvent('onAjaxFailure', function () {
        hideLoader();
    });
});