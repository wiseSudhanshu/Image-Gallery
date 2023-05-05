$(document).ready(function() {
    // Load the topmost images with the page
    $('.sr-image').each(function() {
        if ($(this).offset().top < $(window).scrollTop() + $(window).height() * 1.5) {
        $(this).attr('src', $(this).attr('data-src'));
        $(this).removeAttr('data-src');
        }
    });
});

$(window).on('scroll', function() {
    var offset = 20;  // Change this value to adjust the offset
    $('.sr-image').each(function() {
        if ($(this).attr('data-src') && $(this).offset().top < $(window).scrollTop() + $(window).height() + offset) {
        $(this).attr('src', $(this).attr('data-src'));
        $(this).removeAttr('data-src');
        }
    });
});
  
  
  
  
  
  
  