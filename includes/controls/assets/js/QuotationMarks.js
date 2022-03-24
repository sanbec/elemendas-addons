 
(function ($) {
  jQuery(window).on("elementor:init", function () {
    var ControlBaseMultipleItemView = elementor.modules.controls.BaseMultiple;

    var ControlMultiUnitItemView = elementor.modules.controls.BaseMultiple.extend(
      {
        ui: function () {
          var ui = ControlBaseMultipleItemView.prototype.ui.apply(
            this,
            arguments
          );
          ui.controls = "input:enabled,select";
          ui.select = "select[data-setting=quotesType]";
          return ui;
        },

        events: function () {
          return _.extend(
            ControlBaseMultipleItemView.prototype.events.apply(this, arguments),
            {
              "change @ui.select": "setOpenCloseQuotes",
            }
          );
        },
        // required variable
        defaultInputValue: "",
        name_control: "",

        initialize: function () {
          ControlBaseMultipleItemView.prototype.initialize.apply(
            this,
            arguments
          );
          this.name_control = this.model.get(["name"]);
        },
        getPossibleInputs: function () {
          return ["quotesType","openQuote", "closeQuote"];
        },
        onReady: function () {
          var self = this,
            currentValue = self.getControlValue();

          self.fillEmptyInputs();
        },
        // update open and close quotes
        setOpenCloseQuotes: function (event) {
          var text = $(event.target).val();
          quotes = text.split(',');
          $(
            "input[type=text][data-setting=openQuote]"
          ) .val(quotes[0]);
          $(
            "input[type=text][data-setting=closeQuote]"
          ) .val(quotes[1]);
          this.updateInputs();
        },

        // update values
        updateInputsValue: function () {
          var currentValue = {},
            inputs = this.getPossibleInputs(),
            $controls = this.ui.controls,
            defaultInputValue = this.defaultInputValue;

          inputs.forEach(function (input) {
            var $element = $controls.filter(
              '[data-setting="' + input + '"]'
            );

            currentValue[input] = $element.length
              ? $element.val()
              : defaultInputValue;
          });

          this.setValue(currentValue);
        },
        // fill empty input
        fillEmptyInputs: function () {
          var inputs = this.getPossibleInputs(),
            allowedInputs = this.model.get("allowed_inputs"),
            $controls = this.ui.controls,
            defaultInputValue = this.defaultInputValue;

          inputs.forEach(function (input) {
            var $element = $controls.filter(
                '[data-setting="' + input + '"]'
              ),
              isAllowedInput =
                -1 !== _.indexOf(allowedInputs, input);

            if (
              isAllowedInput &&
              $element.length &&
              _.isEmpty($element.val())
            ) {
              $element.val(defaultInputValue);
            }
          });
        },
        // update Inputs
        updateInputs: function () {
          this.fillEmptyInputs();
          this.updateInputsValue();
        },
        // clear input
        resetInputs: function () {
          this.ui.controls.val("");

          this.updateInputsValue();
        },
        // run while inputs change
        onInputChange: function (event) {
          var inputSetting = event.target.dataset.setting;

          if (!_.contains(this.getPossibleInputs(), inputSetting)) {
            return;
          }
          this.updateInputs();
        },
      }
    );

    elementor.addControlView(
      "quotation-marks",
      ControlMultiUnitItemView
    );
  });
})(jQuery);
