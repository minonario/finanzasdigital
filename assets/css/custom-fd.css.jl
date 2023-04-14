/*
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Other/CascadeStyleSheet.css to edit this template
*/
/* 
    Created on : Feb 18, 2023, 11:31:39 AM
    Author     : josemorales
*/


.home-recomendaciones-del-editor {
    max-width: 100%;
    overflow: hidden
}

@media screen and (min-width: 622px) {
    .home-recomendaciones-del-editor {
        grid-area:1/1/1/3
    }
}

#recomendacionesDelEditor {
    /*margin: 0 auto 0.5rem*/
    margin: 0 0 0.5rem
}

@media screen and (min-width: 622px) {
    #recomendacionesDelEditor {
        max-width:622px
    }
}

#recomendacionesDelEditor article {
    position: relative;
    width: 100vw;
    max-width: 100%;
    margin-bottom:0.5rem;
}

#recomendacionesDelEditor article .post-thumbnail {
    display: block;
    width: 100%
}

#recomendacionesDelEditor article .post-thumbnail img {
    width: 100%;
    height: 60vh;
    max-height: 400px;
    object-fit: cover
}

@media screen and (min-width: 600px) {
    #recomendacionesDelEditor article .post-thumbnail img {
        height:400px
    }
}

#recomendacionesDelEditor article header {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    padding: 1rem;
    display: flex;
    align-items: flex-start;
    justify-content: flex-end;
    flex-direction: column;
    background: black;
    background: linear-gradient(0deg,black 0%,black 5%,rgba(255,255,255,0) 50%,rgba(255,255,255,0) 100%);
    pointer-events: none
}

#recomendacionesDelEditor article .post-category {
    font-size: 0.9rem;
    color: rgba(255,255,255,0.7);
    margin-bottom: 0.5rem
}

#recomendacionesDelEditor article .post-title {
    margin-bottom: 0;
    font-size: 1.3rem;
    text-align: left
}

#recomendacionesDelEditor article .post-title a {
    color: #fff;
    white-space: normal
}

@media screen and (min-width: 1024px) {
    #recomendacionesDelEditor article .post-thumbnail:hover+header a {
        color:#fff!important
    }
}



.carousel-x {
    display: flex;
    overflow-y: hidden;
    overflow-x: auto;
    -ms-scroll-snap-type: x mandatory;
    scroll-snap-type: x mandatory;
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none
}

.carousel-x::-webkit-scrollbar {
    width: 10px;
    height: 10px;
    margin: 5px
}

.carousel-x::-webkit-scrollbar-thumb {
    background: #fff;
    margin: 5px
}

.carousel-x::-webkit-scrollbar-track {
    background: transparent
}

.carousel-x>* {
    scroll-snap-align: start;
    flex-shrink: 0;
    background: #fff;
    transition: transform 0.5s;
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%
}

.carousel-x-btns {
    text-align: center;
    -webkit-user-select: none;
    -ms-user-select: none;
    user-select: none
}

.carousel-x-btns a {
    display: inline-block;
    padding: 0.5rem 1rem;
    background: #003366;
    color: #fff;
    margin: 0.5rem;
    border-radius: 2px;
    padding: 0;
    width: 1rem;
    height: 1rem;
    text-indent: -999999px;
    border-radius: 100%
}

.carousel-x-btns a:hover {
    color: #353664!important
}

@media screen and (min-width: 1024px) {
    .carousel-x-btns a:hover {
        background:#353664;
        color: #151515
    }
}

.hide {
    display: none!important
}


.scroll-touch-x {
    white-space: nowrap;
    overflow-x: auto;
    scroll-behavior: smooth;
    -ms-scroll-snap-type: x mandatory;
    scroll-snap-type: x mandatory;
    scrollbar-color: rgba(0,0,0,0);
    scrollbar-color: transparent transparent;
    scrollbar-height: 1px;
    scrollbar-width: 0px;
    text-align: center
}

.scroll-touch-x::-webkit-scrollbar {
    height: 5px
}

.scroll-touch-x::-webkit-scrollbar-track {
    display: none;
    background: #fff
}

.scroll-touch-x::-webkit-scrollbar-thumb {
    display: none;
    background: rgba(0,0,0,0.3);
    border-radius: 20px;
    border: none
}

.scroll-touch-x>* {
    display: inline-block;
    vertical-align: top
}


/****** SINGLE ******/
.single-post.narrow-content .entry-content > :not([class*="align"]):not([class*="gallery"]):not(.wp-block-image):not(.quote-inner):not(.quote-post-bg), .single-post.narrow-content .mce-content-body:not([class*="page-template-full-width"]) > :not([class*="align"]):not([data-wpview-type*="gallery"]):not(blockquote):not(.mceTemp), .single-post.narrow-content .entry-footer, .single-post.narrow-content .entry-content > .alignwide, .single-post.narrow-content p.has-background:not(.alignfull):not(.alignwide), .single-post.narrow-content .post-nav, .single-post.narrow-content #sinatra-comments-toggle, .single-post.narrow-content #comments, .single-post.narrow-content .entry-content .aligncenter, .single-post.narrow-content .si-narrow-element, .single-post.narrow-content.si-single-title-in-content .entry-header, .single-post.narrow-content.si-single-title-in-content .entry-meta, .single-post.narrow-content.si-single-title-in-content .post-category, .single-post.narrow-content.sinatra-no-sidebar .si-page-header-wrapper, .single-post.narrow-content.sinatra-no-sidebar .si-breadcrumbs nav {
  max-width: 100% !important;
  margin-left: 0 !important;
  margin-right: 0 !important;
}
.sinatra-article h2 {
    font-size: 1rem;
    line-height: 1.2;
}


/**** GENERAL ****/
html,body{
    overflow-x: hidden;
}
h1, h2, h3, h4, h5, h6, p {
    margin: 0px 0px 1.5rem;
    padding: 0px;
}
img {
    display: block;
    margin: auto;
    max-width: 100%;
    image-rendering: crisp-edges;
    height: auto;
}
.home .si-container, .alignfull.si-wrap-content > div {
    max-width: 1260px !important;
    padding: 0 0px;
}
.clearfix {
    display: block;
}
.si-blog-layout-1 .sinatra-article .entry-media img {
  max-width: 100% !important;
}

#topVisitas .title-section {
    background: #353664;
    margin-bottom: 0;
    padding: 0.5rem 1rem
}

@media screen and (min-width: 1024px) {
    #topVisitas .title-section {
        border-radius:2px
    }
}

#topVisitas .title-section span {
    font-size: 0.9rem;
    font-weight: bold;
    color: #fff
}

#topVisitas .list-items>:first-child {
    border-top: none
}

#topVisitas .list-items>:last-child {
    border-bottom: 1px solid rgba(21,21,21,0.3)
}

@media screen and (min-width: 768px){
  .list-items article .post-title {
      font-size: 0.9rem;
  }
}

.list-items article {
    position: relative;
    width: 100%;
    border-bottom: 1px solid rgba(21,21,21,0.3);
}

.list-items article .post-title {
    margin-bottom: 0;
    font-size: 1rem;
    text-align: left;
}

.list-items article .post-title a {
    color: #151515;
    white-space: normal;
    padding: 1rem;
    display: block;
}

@media screen and (max-width: 1023px) {
    #topVisitas .list-items article .post-title a {
        padding:0 0 0 0.5rem;
        margin: 1rem;
        position: relative
    }

    #topVisitas .list-items article .post-title a:before {
        content: " ";
        position: absolute;
        width: 0.5rem;
        height: 0.5rem;
        background: #353664;
        top: 4px;
        left: -0.5rem;
        border-radius: 100%
    }
}

.grid {
    display: grid;
    grid-gap: 1rem;
}

@media screen and (min-width: 640px) {
  .grid {
      grid-template-columns: repeat(2,1fr);
      grid-gap: 1rem;
      max-width: 640px;
      margin: 1rem auto auto;
  }
}

@media screen and (min-width: 1024px) {
  .grid.nl {
      max-width: 100%;
      grid-template-columns: repeat(4,1fr);
  }
  .grid {
      max-width: 100%;
      grid-template-columns: repeat(3,1fr);
  }
}


.home .sinatra-article h2 {
  margin: 0;
}

.single .post-nav .nav-previous .nav-content img {
  margin: 0 1.25rem 0 0
}
.single .post-nav .nav-next .nav-content img {
  margin: 0 0 0 1.25rem
}
.single .post-nav .nav-previous .nav-content span {
  max-width: 250px
}
.single .post-nav .nav-next .nav-content span:not(.ss-on-media-image-wrap) {
  max-width: 250px
}


/**************  grid columns  ****************/
.grid:not(.nl) article, .grid .clasificado {
    position: relative;
    overflow: hidden;
    /*height: 250px;*/
    width: 100%;
    max-width: 320px;
    margin: 0 auto 1.5rem auto
}

.si-blog-layout-1 .sinatra-article .entry-media {
    margin-bottom: 0;
    margin-top: 0;
}

#main .entry-header .entry-title a {
    color: inherit;
    font-size: 1.17rem;
}
.si-blog-horizontal .sinatra-article:not(.format-quote) .entry-summary{
    line-height: 1.3em;
    margin-top: 1em;
}
.titulo, .content-area a:not(.si-btn):not(.wp-block-button__link) {
  color: #353664;
  text-decoration: none;
  overflow-wrap: break-word;
}

.si-blog-horizontal .sinatra-article .entry-media:hover img, .si-blog-layout-1 .sinatra-article .entry-media:hover img {
  transform: none;
}

.otros_titulares {
  list-style: none;
}
.otros_titulares li{
  border-bottom: 1px solid #ccc;
  padding: 6px 0;
}
.title-otros {
  background-color: #353664;
  color: #fff;
  text-transform: uppercase;
  padding: 6px 5px;
  font-size: 1rem;
}
.posted-on {
  margin: 4px 0 4px 0px;
  font-size: 0.8rem
}
.sinatra-article .titulo {
  font-size: 1rem
}
.grid:not(.nl) article {
  line-height: 1.2
}


#sinatra-footer .heateor_sss_bottom_follow {
  margin-top: 1.5em
}
#sinatra-footer ul {
  margin-top: 0.7em;
  padding-left: 2.5rem
}
.widget_heateor_sss_follow span.heateor_sss_svg, a.heateor_sss_instagram span.heateor_sss_svg {
  border-radius: 0 !important;
  background-color: #fff !important;
  background: none;
}
.widget_heateor_sss_follow span.heateor_sss_svg svg path {
  fill: #000
}
#sinatra-footer .sinatra-footer-column {
  padding-top: 20px;
  padding-bottom: 20px
}

.clasificado .whatsapp {
  display: inherit !important;
  margin-left: 10px
}
.grid.nl #tab_container_ds8 {
  margin: 0;
}
#primary img.attachment-full.size-full.wp-post-image {
  border: none;
}
.home.si-blog-layout-1 .sinatra-article .entry-media img, .home.si-blog-layout-1 .sinatra-article .entry-media>a, .archive .entry-media>a,
.archive.si-blog-horizontal .sinatra-article .entry-media img, .archive.si-blog-layout-1 .sinatra-article .entry-media img {
  border-radius: 0;
}
.home.sinatra-sidebar-position__right-sidebar #primary {
  padding-right: 0;
}
#main>.si-container #secondary {
  padding-left: 1rem;
}
.sinatra-sidebar-position__right-sidebar #primary {
  padding-right: 0;
}

#tab_container_ds8 .columnist.nav-tabs > li > a {
  font-size: 0.9rem
}
.grid.nl #tab_container_ds8 .columnist.nav-tabs > li > a {
  padding: 9px 18px 9px 18px;
}
.posts-opinion-card-redes-sociales img {
  display: inherit;
}
.archive.author .navigation .nav-links .page-numbers.next, .archive.author .navigation .nav-links .page-numbers.prev,
.page .navigation .nav-links .page-numbers.next, .page .navigation .nav-links .page-numbers.prev {
  width: auto
}
/***** HACK OPINION *****/


/**** Margenes ****/
@media screen and (max-width: 728px) {
    .home .site-main .si-container {
        padding: 0 0px
    }
    .main.si-container {
        padding: 0 10px
    }
    .main.si-container, #secondary.si-sidebar-container, #sinatra-header-inner>.si-container {
        padding: 0 10px
    }
    #main>.si-container #secondary {
        padding: 0
    }
    .home #main>.si-container #secondary {
        padding: 0 30px
    }
    .home #colophon {
        padding: 0 30px
    }
    #sinatra-footer ul {
      padding-left: 0
    }
    .si-logo-container .si-container {
      padding: 0 30px
    }
    .grid.nl #tab_container_ds8, .home-top-visitas {
      padding: 0 30px
    }
    #primary .main.si-container {
      margin-bottom: 30px
    }
}

#sinatra-footer .sinatra-footer-column .si-widget {
    margin-bottom: 0
}
#sinatra-footer .sinatra-footer-column .si-widget h4.widget-title {
    margin-bottom: 0;
    margin-top: 0.5em
}
colophon .widget-title {
    margin-bottom: 0 !important;
    margin-top: 0.5em !important;
}
#sinatra-footer .heateor_sss_bottom_follow {
    margin-top: 0.5em !important;
}
#colophon #custom_html-2 .widget-title {
    font-size: .9375rem !important;
}
.boletin {
  display: inline-flex;
}
.boletin img {
  width: 30px;
  padding-right: 5px;
}
div#custom_html-3 {
  margin-top: 20px;
}

.carousel-x article, :target {
  scroll-margin-top: 2em;
}
.carousel-x-btns a:target, .carousel-x-btns a:focus, .carousel-x-btns a.activex {
  background: #f47321;
}

/*COOKIES*/

#CookieBanner.is-visible-cookie-banner {
    display: block;
}

#CookieBanner {
    position: fixed;
    z-index: 2147483645;
    min-height: 100vh;
    min-width: 100vw;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    margin: 0 auto;
    pointer-events: none;
    display: none;
}

.is-visible-cookie-banner #CookieBannerOverlay {
    animation: cookieBannerFadeIn .25s ease-in-out;
    animation-fill-mode: forwards;
}

#CookieBannerOverlay {
    background: rgba(0,0,0,.25);
    width: 100%;
    height: 100%;
    position: fixed;
    left: 0;
    right: 0;
    top: 0;
    bottom: 0;
    z-index: 70;
    pointer-events: none;
}

#CookieBanner, #CookieBanner * {
    box-sizing: border-box;
    text-underline-offset: 0.125em;
    outline-offset: 3px;
}

.is-visible-cookie-banner #CookieBannerNotice {
    animation: cookieBannerSlideIn .25s ease-in-out;
    animation-fill-mode: forwards;
}

#CookieBannerNotice {
    color: #000;
    padding: 20px;
    overflow: auto;
    position: fixed;
    left: 0;
    right: 0;
    bottom: 0;
    top: auto;
    max-height: 100vh;
    width: 100%;
    max-width: 100vw;
    background: #202020;
    margin: 0 auto;
    z-index: 500;
    pointer-events: auto;
    -webkit-box-shadow: 0 -10px 20px 0 rgba(34,60,80,.2);
    -moz-box-shadow: 0 -10px 20px 0 rgba(34,60,80,.2);
    box-shadow: 0 -10px 20px 0 rgba(34,60,80,.2);
}

#CookieBanner .cookiebanner__main__description {
    color: #fff;
    font-size: 15px;
    line-height: 1.5;
    margin-top: 0;
    margin-bottom: 0;
}

#CookieBanner .cookiebanner__main__inner {
    max-width: 1440px;
    margin: 0 auto;
}
#CookieBanner .cookiebanner__buttons {
    flex-shrink: 0;
    margin-top: 24px;
}
#CookieBanner ul, #CookieBanner ul li {
    list-style: none;
    margin: 0;
    padding: 0;
    text-indent: 0;
}
#CookieBanner .cookiebanner__buttons ul {
    display: flex;
    align-items: center;
}

#CookieBanner .cookiebanner__buttons li+li {
    margin-top: 0;
}

.cookiebanner__buttons button {
  font-size: 14px;
  line-height: 20px;
  color: #fff;
  font-weight: 700;
  text-decoration: none;
  border-radius: 150px;
  padding: 8px 30px;
  transition: all .3s ease;
  border: none;
  display: inline-block;
  margin: 3px 4px;
  white-space: nowrap;
  text-transform: none;
  letter-spacing: 0;
  cursor: pointer;
  background-color: #0C4DA2;
}
.cookiebanner__buttons button:hover {
  background-color: #fff;
  color: #0C4DA2
}
.cookiebanner__main__description a{
  text-decoration: underline
}
.cookiebanner__main__description a:hover{
  color: #f47321;
}

@media (min-width: 800px) {
  #CookieBannerNotice {
      padding: 20px;
  }
}
@media (min-width: 800px){
  #CookieBanner .cookiebanner__buttons {
      margin-top: 0;
  }
}
@media (min-width: 800px) {
  #CookieBanner .cookiebanner__main__inner {
      align-items: center;
      display: flex;
      justify-content: center;
  }
}

@media (min-width: 800px) {
  #CookieBanner .cookiebanner__main__content {
      margin-right: 25px;
  }
}

@media (min-width: 800px){
  #CookieBanner .cookiebanner__main__description {
      font-size: 15px;
  }
}

.category.si-blog-horizontal .sinatra-article:not(.format-quote) .entry-meta{
  margin-top:0;
}
.category.si-blog-horizontal .sinatra-article:not(.format-quote) .si-blog-entry-wrapper .post-thumb {
    align-self: flex-start;
}

ul {
  list-style: disc;
}
ol, ul {
  margin: 0 0 1em 2em;
}
b, strong {
  font-weight: bold !important;
}
.content-area a:not(.si-btn):not(.wp-block-button__link) {
  font-weight: bold
}
ul.otros_titulares {
  margin: 0
}
.single .content-area a.boton-suscribir {
  font-weight: inherit
}
.single .content-area a.boton-suscribir:hover {
  background-color: #cdcdcd;
  color: #000 !important;
}
.single .content-area .site-content a:not(.si-btn):not(.wp-block-button__link) {
  font-weight: inherit
}

@media screen and (max-width: 768px){
  .sinatra-logo img {
      max-height: 50px;
  }
}
#recomendacionesDelEditor {
  position: relative;
}
#recomendacionesDelEditor .btnx{
  position: absolute;

  top: 50%;
  transform: translateY(-50%);

  height: 40px;
  width: 40px;

  border-radius: 2px;
  background-color: rgba(244,115,33,.8);
  background-position: 50% 50%;
  background-repeat: no-repeat;

  z-index: 1;
}

#recomendacionesDelEditor .next-btn {
  background-image: url('data:image/svg+xml;charset=utf-8,<svg fill="%23FFF" fill-rule="evenodd" width="28" height="28" viewBox="-128 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"/></svg>');
  right: 10px;
}

#recomendacionesDelEditor .prev-btn {
  background-image: url('data:image/svg+xml;charset=utf-8,<svg fill="%23FFF" fill-rule="evenodd" width="28" height="28" viewBox="-128 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M31.7 239l136-136c9.4-9.4 24.6-9.4 33.9 0l22.6 22.6c9.4 9.4 9.4 24.6 0 33.9L127.9 256l96.4 96.4c9.4 9.4 9.4 24.6 0 33.9L201.7 409c-9.4 9.4-24.6 9.4-33.9 0l-136-136c-9.5-9.4-9.5-24.6-.1-34z"/></svg>');
  left: 10px;
}

@media screen and (max-width: 1350px) {
  #main .si-container {
    padding: 0 50px;
    margin: 0 auto;
  }
  #sinatra-header .si-container {
    padding: 0 50px;
    margin: 0 auto;
  }
}
.si-header-widgets .si-search-simple .si-search-form input {
    padding: 14px 0px 14px 2px !important;
}