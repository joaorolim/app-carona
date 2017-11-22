
var containerCaroneiro = '<div class="row row-caroneiro"></div>';


$('.navegacao a.naveg-link').on('click', function (event) {

    // prevenir comportamento normal do link
    event.preventDefault();

    var hrefValue = $(this).attr("href");
    // alert(hrefValue);

    window.location.href = hrefValue;
});


if (typeof(Storage) !== "undefined") {
    // Code for localStorage/sessionStorage.
    // https://stackoverflow.com/questions/17642872/refresh-page-and-keep-scroll-position
    // on certain links save the scroll postion.
    $('.naveg-link').on("click", function (e) {
        e.preventDefault();

        var currentYOffset = window.pageYOffset;  // save current page postion.
        sessionStorage.jumpToScrollPostion = currentYOffset;

        var url = this.href;
        window.location = url;
    });

    // para o botão Filtrar do form
    $('.btn-filter').on("click", function (e) {
        var currentYOffset = window.pageYOffset;  // save current page postion.
        sessionStorage.jumpToScrollPostion = currentYOffset;
    });

    // check if we should jump to postion.
    if(sessionStorage.jumpToScrollPostion !== "undefined") {
        var jumpTo = Number(sessionStorage.jumpToScrollPostion)+200;
        window.scrollTo(0, jumpTo);
        sessionStorage.jumpToScrollPostion = "undefined";  // and delete storage so we don't jump again.
    }
} else {
    alert('Sorry! Your browser does not support web storage...');
}
