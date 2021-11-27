$(document).ready(function () {
    if (window.innerWidth <= 600) {
        $(".comparativoFastpack tr:nth-child(1) td:first-child").css('display', 'none');
        $(".comparativoFastpack tr:nth-child(2) td:first-child").css('display', 'none');
    } else if (window.innerWidth <= 768) {
        $(".comparativoFastpack tr:nth-child(1) td:first-child").attr('colspan', 1);
        $(".comparativoFastpack tr:nth-child(2) td:first-child").attr('colspan', 1);
        $(".comparativoFastpack tr:nth-child(3) th:first-child").attr('colspan', 1);
    }
});