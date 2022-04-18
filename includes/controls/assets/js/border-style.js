
(function ($) {
  jQuery(window).on("elementor:init", function () {
    ControlBorderStyleItemView = elementor.modules.controls.Choose.extend({
    });
    elementor.addControlView(
      "border-style",
      ControlBorderStyleItemView
    );
  });
})(jQuery);
