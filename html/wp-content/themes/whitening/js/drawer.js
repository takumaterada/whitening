console.log("test");
$(document).ready(function () {
  $(".drawer-nav").on("click", function () {
    $(".drawer").drawer("close");
  });
  $(".drawer").drawer({
    iscroll: {
      // Configuring the iScroll
      // https://github.com/cubiq/iscroll#configuring-the-iscroll
      mouseWheel: false,
      preventDefault: false,
    },
  });
});
