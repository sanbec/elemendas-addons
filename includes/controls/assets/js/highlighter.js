
(function ($) {
  jQuery(window).on("elementor:init", function () {
    ControlHighlighterItemView = elementor.modules.controls.Box_shadow.extend({
    });
    elementor.addControlView(
      "highlighter",
      ControlHighlighterItemView
    );
  });
})(jQuery);
