 
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
          ui.controls = ".elemendas-quotation-marks > input:hidden, .elemendas-quotation-marks > select";
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
          return ["quotesType","openquote", "closequote"];
        },

        onReady: function () {
          var self = this,
            currentValue = self.getControlValue();

          self.fillEmptyInputs();
        },

        // update open and close quotes on select change
        setOpenCloseQuotes: function (event) {
          var text = $(event.target).val();
          quotes = text.split(',');

          $(
            "input[type=hidden][data-setting=openquote]"
          ) .val(quotes[0]);
          $(
            "input[type=hidden][data-setting=closequote]"
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
