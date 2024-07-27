
const linksOfCurrentPage = document.querySelectorAll('a[href="'+window.location.pathname+'"]');
console.log(window.location.pathname)
for (let i = 0; i < linksOfCurrentPage.length; i++) {
    console.log(linksOfCurrentPage[i])
    linksOfCurrentPage[i].classList.add('selected');
}
