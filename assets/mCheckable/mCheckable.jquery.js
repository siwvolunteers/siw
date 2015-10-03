/*! mCheckable - v1.0.2 - 2015-03-01
* https://github.com/mIRUmd/mCheckable/
* Copyright (c) 2015 Balan Miroslav; */

!function($) {
    function _triggerClick(settings, $this, element) {
        element.on("click", function(e) {
            e.preventDefault(), $this.trigger("click"), "radio" == $this.attr("type") && renderCurrentStatusElements($('input[type="radio"]')), 
            settings.onClick && settings.onClick();
        });
    }
    function renderCurrentStatusElements($elements) {
        $elements.each(function(i, element) {
            $(element).next().toggleClass("checked", $(element).is(":checked"));
        });
    }
    function _triggerChange($this, element) {
        $this.change(function() {
            "radio" == $this.attr("type") && renderCurrentStatusElements($('input[type="radio"]')), 
            element.toggleClass("checked", $this.is(":checked"));
        });
    }
    var methods = {
        init: function(options) {
            return this.each(function() {
                var $this = $(this), settings = $this.data("mCheckable");
                if (!$this.data("checkable")) {
                    if ("undefined" == typeof settings) {
                        var defaults = {
                            className: "mCheckable",
                            classNameRadioButton: "radiobutton",
                            classNameCheckbox: "checkbox",
                            addClassName: !1,
                            baseTags: "<span></span>",
                            innerTags: "<em></em>"
                        };
                        settings = $.extend({}, defaults, options), $this.data("mCheckable", settings);
                    } else settings = $.extend({}, settings, options);
                    var element = $(settings.baseTags).prepend(settings.innerTags).addClass(settings.className).toggleClass("checked", $this.is(":checked"));
                    settings.addClassName && element.addClass(settings.addClassName), element.addClass("checkbox" == $this.attr("type") ? settings.classNameCheckbox : settings.classNameRadioButton), 
                    $this.hide().after(element), _triggerClick(settings, $this, element), _triggerChange($this, element), 
                    $this.data("checkable", "checkable");
                }
            });
        },
        check: function() {
            return this.each(function() {
                var $this = $(this), element = $this.next();
                $this.prop("checked", !0), element.addClass("checked");
            });
        },
        unCheck: function() {
            return this.each(function() {
                var $this = $(this), element = $this.next();
                $this.prop("checked", !1), element.removeClass("checked");
            });
        }
    };
    $.fn.mCheckable = function() {
        var method = arguments[0];
        if (methods[method]) method = methods[method], arguments = Array.prototype.slice.call(arguments, 1); else {
            if ("object" != typeof method && method) return $.error("Method " + method + " does not exist on jQuery.mCheckable"), 
            this;
            method = methods.init;
        }
        return method.apply(this, arguments);
    };
}(jQuery);