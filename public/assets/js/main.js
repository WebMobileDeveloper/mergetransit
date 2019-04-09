"use strict";
$("body").on("click", ".hamburger", function(e) {
    e.preventDefault(), $(".header").toggleClass("opened"), $(".hamburger").toggleClass("active"), $(".header nav").hasClass("open") ? ($(".header nav").css("animation-name", "hide-menu"), setTimeout(function() {
        $(".header nav").removeClass("open")
    }, 600)) : ($(".header nav").css("animation-name", "show-menu"), $(".header nav").addClass("open"))
}), setTimeout(function() {
    // $(".preloader").hide()
}, 4e3), $(".testimonials .row").masonry({
    itemSelector: ".testimonials__item"
});
var myTimer = setTimeout(function() {
    changeScreen()
}, 5e3);

function changeScreen() {
    6 == $(".nav-button.active").data("number") ? $('.nav-button[data-number="1"]').trigger("click") : $(".nav-button.active").next("button").trigger("click")
}
clearTimeout(myTimer), $("body").on("click", ".features button", function(e) {
    e.preventDefault();
    var o = $(this).data("number");
    $(".icon").removeClass("active"), $('.icon[data-number="' + o + '"]').addClass("active"), $(".nav-button").removeClass("active"), $('.nav-button[data-number="' + o + '"]').addClass("active"), $(".phone__screen").removeClass("screen--1 screen--2 screen--3 screen--4 screen--5 screen--6"), $(".phone__screen").addClass("screen--" + o), clearTimeout(myTimer), myTimer = setTimeout(function() {
        changeScreen()
    }, 5e3)
}), $("body").on("click", ".services button", function(e) {
    e.preventDefault();
    var o = $(".features").position().top;
    $("html, body").animate({
        scrollTop: o
    }, 1e3);
    $(".icon").removeClass("active"), $('.icon[data-number="1"]').addClass("active"), $(".nav-button").removeClass("active"), $('.nav-button[data-number="1"]').addClass("active"), $(".phone__screen").removeClass("screen--1 screen--2 screen--3 screen--4 screen--5 screen--6"), $(".phone__screen").addClass("screen--1"), $(".features").removeClass("show--optymalization show--organization"), $(".features").addClass($(this).data("class")), clearTimeout(myTimer), myTimer = setTimeout(function() {
        changeScreen()
    }, 5e3)
}), "#steps" == window.location.hash && $("html, body").animate({
    scrollTop: $("#steps").position().top
}, 1e3), $('a[href="#steps"]').on("click", function(e) {
    e.preventDefault(), $("html, body").animate({
        scrollTop: $("#steps").position().top
    }, 1e3)
}), $(".scroll").on("click", function(e) {
    e.preventDefault(), $("html, body").animate({
        scrollTop: $(window).height()
    }, 1e3)
});
var controller = new ScrollMagic.Controller;
var heroPhoneScene = new ScrollMagic.Scene({ triggerElement: "#phone", triggerHook: 0, duration: "80%" })
    .setTween(".phone--1", 1, { y: "-20%", ease: Power0.easeNone })
    .addTo(controller);

// var heroContentScene = new ScrollMagic.Scene({
//     triggerElement: "#hero__wrapper",
//     triggerHook: 0,
//     duration: "30%"
// }).setTween(".hero__wrapper", .1, {
//     alpha: 0,
//     ease: Power0.easeNone
// }, .1).addTo(controller);

// var shadow1Tl = new TimelineMax;
// shadow1Tl.to(".shadow--1", .9, {
//     x: "20%",
//     ease: Power0.easeNone
// }, 0).from(".content--1", .1, {
//     alpha: 0,
//     ease: Power0.easeNone
// }, 0);
// var shadow1Scene = new ScrollMagic.Scene({
//         triggerElement: "#about",
//         triggerHook: 1,
//         duration: "100%"
//     }).setTween(shadow1Tl).addTo(controller),
//     hideShadow1Scene = new ScrollMagic.Scene({
//         triggerElement: "#about h2",
//         triggerHook: 0,
//         duration: "20%"
//     }).setTween(".content--1", 4, {
//         alpha: 0,
//         ease: Power0.easeNone
//     }, .4).addTo(controller);
// (shadow2Tl = new TimelineMax).to(".shadow--2", 10, {
//     x: "190px",
//     ease: Power0.easeNone
// }, 4).from(".content--2", 10, {
//     alpha: 0,
//     ease: Power0.easeNone
// }, 1);
// var shadow2Tl, shadow2Scene = new ScrollMagic.Scene({
//     triggerElement: "#content2 p",
//     triggerHook: 1,
//     duration: "50%"
// }).setTween(shadow2Tl).addTo(controller);
// var hideShadow1Scene = new ScrollMagic.Scene({
//     triggerElement: "#content2 h3",
//     triggerHook: 0,
//     duration: "40%"
// }).setTween(".content--2", 10, {
//     alpha: 0,
//     ease: Power0.easeNone
// }, 1).addTo(controller);
// (shadow2Tl = new TimelineMax).to(".shadow--3", 10, {
//     x: "190px",
//     ease: Power0.easeNone
// }, 4).from(".content--3", 10, {
//     alpha: 0,
//     ease: Power0.easeNone
// }, 4);
// shadow2Scene = new ScrollMagic.Scene({
//     triggerElement: "#content3 p",
//     triggerHook: 1,
//     duration: "50%"
// }).setTween(shadow2Tl).addTo(controller), hideShadow1Scene = new ScrollMagic.Scene({
//     triggerElement: "#content3 h3",
//     triggerHook: 0,
//     duration: "40%"
// }).setTween(".content--3", 10, {
//     alpha: 0,
//     ease: Power0.easeNone
// }, 1).addTo(controller);
// $(".step").each(function(e, o) {
//     new ScrollMagic.Scene({
//         triggerElement: this,
//         triggerHook: .7
//     }).setClassToggle("#stickyPhone", "sticky__content--" + e).addTo(controller)
// }), $(".testimonials__item").each(function(e, o) {
//     var n = .7;
//     1 == e && 992 < $(window).width() && (n = .5);
//     new ScrollMagic.Scene({
//         triggerElement: this,
//         triggerHook: n
//     }).setClassToggle(this, "show").addTo(controller)
// }), $(".services__item").each(function(e, o) {
//     var n = .9;
//     1 == e && (n = .8);
//     new ScrollMagic.Scene({
//         triggerElement: this,
//         triggerHook: n
//     }).setClassToggle(this, "show").addTo(controller)
// });