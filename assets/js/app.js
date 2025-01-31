/*================
 Template Name: 
 Description: 
 Version: 1.0
 Author: https://themeforest.net/user/themetags
=======================*/
"use strict";

document.addEventListener("DOMContentLoaded", function () {
  const body = document.querySelector("body");

  // Slide Up
  const slideUp = (target, duration = 500) => {
    target.style.transitionProperty = "height, margin, padding";
    target.style.transitionDuration = duration + "ms";
    target.style.boxSizing = "border-box";
    target.style.height = target.offsetHeight + "px";
    target.offsetHeight;
    target.style.overflow = "hidden";
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    window.setTimeout(() => {
      target.style.display = "none";
      target.style.removeProperty("height");
      target.style.removeProperty("padding-top");
      target.style.removeProperty("padding-bottom");
      target.style.removeProperty("margin-top");
      target.style.removeProperty("margin-bottom");
      target.style.removeProperty("overflow");
      target.style.removeProperty("transition-duration");
      target.style.removeProperty("transition-property");
    }, duration);
  };
  // Slide Down
  const slideDown = (target, duration = 500) => {
    target.style.removeProperty("display");
    let display = window.getComputedStyle(target).display;
    if (display === "none") display = "block";
    target.style.display = display;
    let height = target.offsetHeight;
    target.style.overflow = "hidden";
    target.style.height = 0;
    target.style.paddingTop = 0;
    target.style.paddingBottom = 0;
    target.style.marginTop = 0;
    target.style.marginBottom = 0;
    target.offsetHeight;
    target.style.boxSizing = "border-box";
    target.style.transitionProperty = "height, margin, padding";
    target.style.transitionDuration = duration + "ms";
    target.style.height = height + "px";
    target.style.removeProperty("padding-top");
    target.style.removeProperty("padding-bottom");
    target.style.removeProperty("margin-top");
    target.style.removeProperty("margin-bottom");
    window.setTimeout(() => {
      target.style.removeProperty("height");
      target.style.removeProperty("overflow");
      target.style.removeProperty("transition-duration");
      target.style.removeProperty("transition-property");
    }, duration);
  };

  // Slide Toggle
  const slideToggle = (target, duration = 500) => {
    if (target.attributes.style === undefined || target.style.display === "none") {
      return slideDown(target, duration);
    } else {
      return slideUp(target, duration);
    }
  };

  // Primary Menu
  const mdScreen = "(max-width: 991px)";
  const mdScreenSize = window.matchMedia(mdScreen);
  const containSub1 = document.querySelectorAll(".contain-sub-1 > a");
  const containMegaMenu = document.querySelectorAll(".contain-mega-menu > a");
  const innerSubMenu = document.querySelectorAll(".inner-sub-menu > .contain-sub-1__link");
  const mdScreenSizeActive = screen => {
    if (screen.matches) {
      // if menu has sub
      containSub1.forEach(e => {
        e.addEventListener("click", el => {
          el.preventDefault();
          el.stopPropagation();
          el.target.classList.toggle("active");
          const menuSub = e.nextElementSibling;
          slideToggle(menuSub, 500);
        });
      });

      // If sub menu has inner sub
      innerSubMenu.forEach(e => {
        e.addEventListener("click", el => {
          el.preventDefault();
          el.stopPropagation();
          el.target.classList.toggle("active");
          const menuSub = e.nextElementSibling;
          slideToggle(menuSub, 500);
        });
      });

      // if menu has mega menu
      containMegaMenu.forEach(e => {
        e.addEventListener("click", el => {
          el.preventDefault();
          el.stopPropagation();
          el.target.classList.toggle("active");
          const menuSub = e.nextElementSibling;
          slideToggle(menuSub, 500);
        });
      });
    } else {
      containSub1.forEach(e => {
        e.addEventListener("click", el => {
          el.preventDefault();
        });
      });
      innerSubMenu.forEach(e => {
        e.addEventListener("click", el => {
          el.preventDefault();
        });
      });
      containMegaMenu.forEach(e => {
        e.addEventListener("click", el => {
          el.preventDefault();
        });
      });
    }
  };
  mdScreenSize.addEventListener("change", e => {
    if (e.matches) {
      window.location.reload();
      mdScreenSizeActive(e);
    } else {
      mdScreenSize.removeEventListener("change", e => {
        mdScreenSizeActive(e);
      });
      window.location.reload();
    }
  });
  mdScreenSizeActive(mdScreenSize);

  // Sticky Header
  window.addEventListener("scroll", () => {
    const fixedHeader = document.querySelector(".navbar-overlay");
    const navbarTop = document.querySelector(".navbar-top");
    if (navbarTop) {
      const navTop = navbarTop.offsetHeight;
      const scrolled = window.scrollY;
      const navbarTopRemove = () => {
        if (scrolled > navTop) {
          body.classList.add("navbar-top-toggle");
        } else if (scrolled < navTop) {
          body.classList.remove("navbar-top-toggle");
        } else {
          body.classList.remove("navbar-top-toggle");
        }
      };
      navbarTopRemove();
    }
    if (fixedHeader) {
      const headerTop = fixedHeader.offsetHeight;
      const scrolled = window.scrollY;
      const headerFixed = () => {
        if (scrolled > headerTop) {
          body.classList.add("navbar-sticky-init");
        } else if (scrolled < headerTop) {
          body.classList.remove("navbar-sticky-init");
        } else {
          body.classList.remove("navbar-sticky-init");
        }
      };
      setTimeout(headerFixed, 100);
    }
  });

  // ScrollTop
  window.addEventListener('scroll', function () {
    var scrollToTarget = document.querySelector('.scroll-to-target');
    if (window.scrollY > 50) {
      scrollToTarget.classList.add('open');
    } else {
      scrollToTarget.classList.remove('open');
    }
  });
  var scrollToTarget = document.querySelectorAll('.scroll-to-target');
  scrollToTarget.forEach(function (targetElement) {
    targetElement.addEventListener('click', function () {
      var targetId = targetElement.getAttribute('data-target');
      var target;

      // Scroll to top if the target is "html"
      if (targetId === "html") {
        target = document.documentElement;
      } else {
        target = document.querySelector(targetId);
      }
      if (target) {
        window.scrollTo({
          top: 0,
          // Set top to 0 when scrolling to top
          behavior: 'smooth'
        });
      }
    });
  });

  // ScrollTop

  // Feedback Slider
  var feedbackSlider = new Swiper(".feedback-slider", {
    autoplay: true,
    loop: true,
    speed: 700,
    spaceBetween: 24,
    centeredSlides: false,
    pagination: {
      el: ".itc-testimonial-slide-dots",
      clickable: true
    },
    navigation: {
      nextEl: ".feedback-button-next",
      prevEl: ".feedback-button-prev"
    },
    breakpoints: {
      0: {
        slidesPerView: 1
      },
      768: {
        slidesPerView: 2
      },
      1200: {
        slidesPerView: 3
      }
    }
  });

  // Feedback Slider 1
  new Swiper(".feedback-slider-1", {
    autoplay: true,
    loop: true,
    speed: 700,
    spaceBetween: 24,
    centeredSlides: false,
    pagination: {
      el: ".feedback-slider-1__dot",
      clickable: true
    },
    breakpoints: {
      0: {
        slidesPerView: 1
      },
      768: {
        slidesPerView: 2
      },
      1200: {
        slidesPerView: 3
      }
    }
  });

  // Feedback Slider
  var feedbackSlider2 = new Swiper(".feedback-slider-2", {
    autoplay: true,
    loop: true,
    speed: 700,
    spaceBetween: 24,
    centeredSlides: false,
    pagination: {
      el: ".itc-testimonial-slide-dots",
      clickable: true
    },
    navigation: {
      nextEl: ".feedback-button-next",
      prevEl: ".feedback-button-prev"
    },
    breakpoints: {
      0: {
        slidesPerView: 1
      }
    }
  });
  new Swiper(".feedback-2", {
    // autoplay: true,
    loop: true,
    speed: 700,
    spaceBetween: 16,
    slidesPerView: 1,
    pagination: {
      el: ".feedback-2__pagination",
      clickable: true
    }
  });

  // Feedback 3 Slider
  const feedbackSlider3 = document.querySelector(".feedback-3-slider__init");
  if (feedbackSlider3) {
    new Swiper(feedbackSlider3, {
      autoplay: true,
      loop: true,
      slidesPerView: 1,
      centeredSlides: true,
      spaceBetween: 16,
      pagination: {
        el: ".feedback-3-slider__pagination",
        clickable: true
      },
      breakpoints: {
        992: {
          slidesPerView: 3
        }
      }
    });
  }

  // Feedback Slider 4
  var feedbackSliderFour = new Swiper(".feedback-slider-4", {
    autoplay: true,
    loop: true,
    speed: 700,
    spaceBetween: 24,
    centeredSlides: false,
    pagination: {
      el: ".itc-testimonial-slide-dots",
      clickable: true
    },
    navigation: {
      nextEl: ".feedback-button-next",
      prevEl: ".feedback-button-prev"
    },
    breakpoints: {
      0: {
        slidesPerView: 1
      },
      768: {
        slidesPerView: 2
      }
    }
  });

  // Feedback Slider 5
  const feedbackSliderFive = document.querySelector(".feedback-5-slider__init");
  if (feedbackSliderFive) {
    new Swiper(feedbackSliderFive, {
      autoplay: true,
      loop: true,
      speed: 700,
      slidesPerView: 1,
      effect: "fade"
    });
  }

  // Feedback Slider 10
  const feedbackSliderTen = document.querySelector(".feedback_10_wrapper");
  if (feedbackSliderTen) {
    new Swiper(feedbackSliderTen, {
      autoplay: true,
      loop: true,
      speed: 700,
      spaceBetween: 0,
      centeredSlides: true,
      breakpoints: {
        0: {
          slidesPerView: 1
        },
        576: {
          slidesPerView: 2
        },
        768: {
          slidesPerView: 3
        },
        1200: {
          slidesPerView: 4
        }
      }
    });
  }

  // Price show hide
  const priceControlBtn = document.querySelectorAll(".price-control-btn");
  const priceServiceWrapper = document.querySelectorAll(".price-service-wrapper");
  for (let i = 0; i < priceControlBtn.length; i++) {
    priceControlBtn[i].addEventListener("click", function (index) {
      return function () {
        priceServiceWrapper[index].classList.toggle("show");
      };
    }(i));
  }

  // Pricing Switch
  const monthlyPricing = document.querySelectorAll(".monthly-price");
  const yearlyPricing = document.querySelectorAll(".yearly-price");
  const monthlyPricingX = document.querySelectorAll(".monthly-price-x");
  const yearlyPricingX = document.querySelectorAll(".yearly-price-x");

  //Pricing Toggle with Checkbox
  const priceSwitch = document.querySelectorAll(".pricing-toggle");
  if (priceSwitch) {
    priceSwitch.forEach(priceCheck => {
      priceCheck.addEventListener("change", () => {
        if (priceCheck.checked === true) {
          monthlyPricing.forEach(e => {
            e.style.cssText = `display: none;`;
          });
          yearlyPricing.forEach(e => {
            e.style.cssText = `display: block;`;
          });
        } else {
          monthlyPricing.forEach(e => {
            e.style.cssText = `display: block;`;
          });
          yearlyPricing.forEach(e => {
            e.style.cssText = `display: none;`;
          });
        }
      });
    });
  }
  const priceSwitchX = document.querySelectorAll(".pricing-toggle-x");
  if (priceSwitchX) {
    priceSwitchX.forEach(priceCheck => {
      priceCheck.addEventListener("change", () => {
        if (priceCheck.checked === true) {
          monthlyPricingX.forEach(e => {
            e.style.cssText = `display: none;`;
          });
          yearlyPricingX.forEach(e => {
            e.style.cssText = `display: block;`;
          });
        } else {
          monthlyPricingX.forEach(e => {
            e.style.cssText = `display: block;`;
          });
          yearlyPricingX.forEach(e => {
            e.style.cssText = `display: none;`;
          });
        }
      });
    });
  }

  // Timeline Slider Nav
  const timelineSliderNav = document.querySelector(".timeline-slider-nav");
  const timelineSliderContent = document.querySelector(".timeline-slider-content");
  if (timelineSliderNav && timelineSliderContent) {
    const swiperNav = new Swiper(timelineSliderNav, {
      loop: true,
      slidesPerView: 1,
      freeMode: true,
      watchSlidesProgress: true,
      navigation: {
        nextEl: ".timeline-slider-nav__next",
        prevEl: ".timeline-slider-nav__prev"
      },
      breakpoints: {
        576: {
          slidesPerView: 2
        },
        768: {
          slidesPerView: 3
        },
        1200: {
          slidesPerView: 4
        },
        1400: {
          slidesPerView: 5
        }
      }
    });
    new Swiper(timelineSliderContent, {
      loop: true,
      thumbs: {
        swiper: swiperNav
      }
    });
  }
  // Hero 7 Slider
  const heroSlider7 = document.querySelector(".hero-7-slider");
  if (heroSlider7) {
    new Swiper(heroSlider7, {
      loop: true,
      slidesPerView: 1,
      autoplay: true,
      pagination: {
        el: ".hero-7-slider__pagination",
        clickable: true
      }
    });
  }
  // Map Location Active
  const listItems = document.querySelectorAll(".data-center__location");
  listItems.forEach(function (item) {
    item.addEventListener("mouseover", function () {
      item.classList.add("active");
      listItems.forEach(function (sibling) {
        if (sibling !== item) {
          sibling.classList.remove("active");
        }
      });
    });
  });
  const heroLocation = document.querySelectorAll(".hero-1__map-location");
  heroLocation.forEach(function (item) {
    item.addEventListener("mouseover", function () {
      item.classList.add("active");
      heroLocation.forEach(function (sibling) {
        if (sibling !== item) {
          sibling.classList.remove("active");
        }
      });
    });
  });

  // Horizontal Scrolling
  const scrollers = document.querySelectorAll(".scroller-x");
  scrollers.forEach(scroller => {
    scroller.setAttribute("data-animated", true);
    const scrollerInner = scroller.querySelector(".scroller-x__list");
    const scrollerContent = Array.from(scrollerInner.children);
    scrollerContent.forEach(item => {
      const duplicatedItem = item.cloneNode(true);
      duplicatedItem.setAttribute("aria-hidden", true);
      scrollerInner.appendChild(duplicatedItem);
    });
  });
  // Scrollbase Animation Init
  sal({
    threshold: 0.25
  });
});

/**
 * Preloader
 */
// const preloader = document.querySelector(".preloader");
// window.addEventListener("load", () => {
// 	if (preloader) {
// 		preloader.style.display = "none";
// 	}
// });

document.addEventListener("DOMContentLoaded", function () {
  var preloader = document.querySelector(".preloader");
  setTimeout(function () {
    preloader.style.display = "none";
  }, 100);
});
$(function () {
  "use strict";

  $(".popup-init").magnificPopup({
    type: "iframe"
  });
  var handle = $("#custom-handle");
  $(".vps-slider--core").slider({
    value: 1,
    min: 1,
    max: 6,
    create: function () {
      handle.text($(this).slider("value") + " Core");
      $(".vps-slider-details__config--1").text($(this).slider("value"));
    },
    slide: function (event, ui) {
      handle.text(ui.value + " Core");
      $(".vps-slider-details__config--1").text(ui.value);
      var vpsRange = $(".vps-slider-amount").val(ui.value);
      var vpsRangeVal = vpsRange.val();
      if (vpsRangeVal == 1) {
        $(".vps-slider__progress-1").css({
          width: "0"
        });
      } else if (vpsRangeVal == 2) {
        $(".vps-slider__progress-1").css({
          width: "20%"
        });
      } else if (vpsRangeVal == 3) {
        $(".vps-slider__progress-1").css({
          width: "40%"
        });
      } else if (vpsRangeVal == 4) {
        $(".vps-slider__progress-1").css({
          width: "60%"
        });
      } else if (vpsRangeVal == 5) {
        $(".vps-slider__progress-1").css({
          width: "80%"
        });
      } else if (vpsRangeVal == 6) {
        $(".vps-slider__progress-1").css({
          width: "100%"
        });
      }
    }
  });
  var handleTwo = $("#custom-handle-2");
  $(".vps-slider--ram").slider({
    value: 1,
    min: 1,
    max: 6,
    create: function () {
      handleTwo.text($(this).slider("value") + " RAM");
      $(".vps-slider-details__config--2").text($(this).slider("value"));
    },
    slide: function (event, ui) {
      handleTwo.text(ui.value + " RAM");
      $(".vps-slider-details__config--2").text(ui.value);
      var vpsRange = $(".vps-slider-amount-2").val(ui.value);
      var vpsRangeVal = vpsRange.val();
      if (vpsRangeVal == 1) {
        $(".vps-slider__progress-2").css({
          width: "0"
        });
      } else if (vpsRangeVal == 2) {
        $(".vps-slider__progress-2").css({
          width: "20%"
        });
      } else if (vpsRangeVal == 3) {
        $(".vps-slider__progress-2").css({
          width: "40%"
        });
      } else if (vpsRangeVal == 4) {
        $(".vps-slider__progress-2").css({
          width: "60%"
        });
      } else if (vpsRangeVal == 5) {
        $(".vps-slider__progress-2").css({
          width: "80%"
        });
      } else if (vpsRangeVal == 6) {
        $(".vps-slider__progress-2").css({
          width: "100%"
        });
      }
    }
  });
  var handleThree = $("#custom-handle-3");
  $(".vps-slider--ssd").slider({
    value: 1,
    min: 1,
    max: 6,
    create: function () {
      handleThree.text($(this).slider("value") + " SSD");
      $(".vps-slider-details__config--3").text($(this).slider("value"));
    },
    slide: function (event, ui) {
      handleThree.text(ui.value + " SSD");
      $(".vps-slider-details__config--3").text(ui.value);
      var vpsRange = $(".vps-slider-amount-3").val(ui.value);
      var vpsRangeVal = vpsRange.val();
      if (vpsRangeVal == 1) {
        $(".vps-slider__progress-3").css({
          width: "0"
        });
      } else if (vpsRangeVal == 2) {
        $(".vps-slider__progress-3").css({
          width: "20%"
        });
      } else if (vpsRangeVal == 3) {
        $(".vps-slider__progress-3").css({
          width: "40%"
        });
      } else if (vpsRangeVal == 4) {
        $(".vps-slider__progress-3").css({
          width: "60%"
        });
      } else if (vpsRangeVal == 5) {
        $(".vps-slider__progress-3").css({
          width: "80%"
        });
      } else if (vpsRangeVal == 6) {
        $(".vps-slider__progress-3").css({
          width: "100%"
        });
      }
    }
  });
  $(".vps-slider--processor").slider({
    value: 1,
    min: 1,
    max: 6,
    create: function () {
      handle.text($(this).slider("value") + " Core");
      $(".config-info-1").text($(this).slider("value") + 10);
      $(".config-info-2").text($(this).slider("value") + 5);
      $(".config-info-3").text($(this).slider("value") + 25);
    },
    slide: function (event, ui) {
      handle.text(ui.value + " Core");
      $(".config-info-1").text(ui.value + 10);
      $(".config-info-2").text(ui.value + 5);
      $(".config-info-3").text(ui.value + 25);
      var vpsRange = $(".vps-slider-amount").val(ui.value);
      var vpsRangeVal = vpsRange.val();
      if (vpsRangeVal == 1) {
        $(".vps-slider__progress-1").css({
          width: "0"
        });
      } else if (vpsRangeVal == 2) {
        $(".vps-slider__progress-1").css({
          width: "20%"
        });
      } else if (vpsRangeVal == 3) {
        $(".vps-slider__progress-1").css({
          width: "40%"
        });
      } else if (vpsRangeVal == 4) {
        $(".vps-slider__progress-1").css({
          width: "60%"
        });
      } else if (vpsRangeVal == 5) {
        $(".vps-slider__progress-1").css({
          width: "80%"
        });
      } else if (vpsRangeVal == 6) {
        $(".vps-slider__progress-1").css({
          width: "100%"
        });
      }
    }
  });
});