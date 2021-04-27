window.onload = () => {
    _mediaQuery();
}
function _mediaQuery(){
    let x = window.matchMedia("(max-width: 991.98px)");
    let search_form = document.querySelector('.visitor-navbar form.d-flex');
    let navbar_wrapper = document.getElementById('navbarSupportedContent');
    if(x.matches){
        navbar_wrapper.prepend(search_form);
    }else{
        navbar_wrapper.append(search_form);
    }
}
