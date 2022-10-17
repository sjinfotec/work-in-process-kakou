// offcanvas

$(function () {
  'use strict'

  $('[data-toggle="offcanvas-left"]').on('click', function () {
    $('.offcanvas_left').toggleClass('open')
  })
  $('[data-toggle="offcanvas-right"]').on('click', function () {
    $('.offcanvas_right').toggleClass('open')
  })
})
