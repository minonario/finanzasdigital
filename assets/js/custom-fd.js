jQuery( function ( $ ) {
  
  $(document).ready(function(){

  myElement = document.getElementById('scroller');
  
  if (myElement){
    const gallery_item_size = myElement.querySelector('article').clientWidth;
    let numb = myElement.childElementCount;
    if (myElement.scrollLeft == 0) document.getElementById("prev-btn").style.opacity = "0";
    
    function scrollHandler(e) {
        var atSnappingPoint = e.target.scrollLeft % e.target.offsetWidth === 0;
        var timeOut         = atSnappingPoint ? 0 : 150;
        clearTimeout(e.target.scrollTimeout);
        e.target.scrollTimeout = setTimeout(function() {
            if (!timeOut) {
                [].slice.call(myElement.children).forEach(function (ele, index) {
                    if (Math.abs(ele.getBoundingClientRect().left - myElement.getBoundingClientRect().left) < 10) {
                        $('.activex').removeClass('activex');
                        $( "a[href='#"+ele.id+"']" ).addClass('activex');
                        if (0 == myElement.scrollLeft){
                          document.getElementById("prev-btn").style.opacity = "0";
                          document.getElementById("next-btn").style.opacity = "1";
                        }else{
                          document.getElementById("prev-btn").style.opacity = "1";
                          if ( ( (numb - 1) * myElement.clientWidth ) == myElement.scrollLeft ){
                            document.getElementById("next-btn").style.opacity = "0";
                          }else{
                            document.getElementById("next-btn").style.opacity = "1";
                          }
                        }
                    } else {
                    }
                });
            } else {
            }
        }, timeOut);
    }

    myElement.addEventListener('scroll', scrollHandler);
    
    const nextBtn = document.querySelector(".next-btn");
    const prevBtn = document.querySelector(".prev-btn");
    
    nextBtn.addEventListener("click", () => {
      myElement.scrollBy(gallery_item_size, 0);
      document.getElementById("prev-btn").style.opacity = "1";
    });
    prevBtn.addEventListener("click", () => {
      myElement.scrollBy(-gallery_item_size, 0);
      document.getElementById("next-btn").style.opacity = "1";
      if (gallery_item_size == myElement.scrollLeft){
        document.getElementById("prev-btn").style.opacity = "0";
      }
      
    });
    
  }

  });
 
});

function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function deleteCookie(cname) {
    const d = new Date();
    d.setTime(d.getTime() + (24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=;" + expires + ";path=/";
}

function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

let cookie_consent = getCookie("user_cookie_consent");
if(cookie_consent != ""){
    document.getElementById("CookieBanner").style.display = "none";
}else{
    document.getElementById("CookieBanner").style.display = "block";
}

function acceptCookieConsent(){
    deleteCookie('user_cookie_consent');
    setCookie('user_cookie_consent', 1, 30);
    document.getElementById("CookieBanner").style.display = "none";
}