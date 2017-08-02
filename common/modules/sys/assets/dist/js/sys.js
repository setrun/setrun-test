;(function(win, $, doc){
    "use strict";

    var Sys = {};

    Sys.$doc       = $(doc); // Document
    Sys.fn         = {};     // List of functions
    Sys.components = {};     // List of actions
    Sys.setup      = {};     // List of configuration
    Sys.domready   = false;  // Dom not ready

    /**
     * Data request (ajax)
     * @type {object}
     */
    Sys.setup.request = {
        async    : true,
        type     : 'POST',
        dataType : 'json',
        url      : '',
        formData : false
    };

    /**
     * Helper to init extensions
     * @type {object}
     */
    Sys.setup.component = {
        defaults         : {},
        handlers         : {},
        init             : function() {},
        boot             : function() {},
        autoload         : false,
        registerHandlers : function() {
            var that = this;
            $.map(that.handlers, function(value, key) {
                if (typeof that[key] === 'function') {
                    that.registerHandler(key, value);
                }
            });
        },
        registerHandler : function(name, obj) {
            var that = this,
                arr = [];
            arr = obj.length === undefined ? [obj] : obj;
            $.each(arr, function(key, value) {
                if (typeof value.el === 'undefined') {
                    $(document).on(value.ev, $.proxy(that[name], that));
                } else {
                    $(document).on(value.ev, value.el, $.proxy(that[name], that));
                }
            })
        },
        option : function() {
            if (arguments.length == 1) {
                return this.options[arguments[0]] || undefined;
            } else if (arguments.length == 2) {
                this.options[arguments[0]] = arguments[1];
            }
        }
    };

    /**
     * Register a new component in the Sys
     * @param {object} body  Body of action
     * @param {string} name  Name of action
     * @return {function}
     */
    Sys.component = function(name, body) {
        var that = this,
            fn   = function(element, options) {
                var $this    = this;
                this.element = element ? $(element) : null;
                this.options = $.extend(true, {}, this.defaults, options);

                if (this.element) {
                    this.element.data(name, this);
                }

                this.init();
                this.registerHandlers();

                that.$doc.trigger($.Event('init.Sys.component'), [name, this]);

                return this;
            };

        $.extend(true, fn.prototype, Sys.setup.component, body);

        that.components[name] = fn;

        this[name] = function() {

            var element, options;

            if (arguments.length) {

                switch (arguments.length) {
                    case 1:

                        if (typeof arguments[0] === 'string' || arguments[0].nodeType || arguments[0] instanceof jQuery) {
                            element = $(arguments[0]);
                        } else {
                            options = arguments[0];
                        }

                        break;
                    case 2:

                        element = $(arguments[0]);
                        options = arguments[1];
                        break;
                }
            }

            if (element && element.data(name)) {
                return element.data(name);
            }

            return (new Sys.components[name](element, options));
        };

        if (Sys.domready) {
            Sys.component.boot(name);
        }
        return fn;
    };

    Sys.component.boot = function(name) {

        if (Sys.components[name].prototype && Sys.components[name].prototype.boot && !Sys.components[name].booted) {
            Sys.components[name].prototype.boot.apply(Sys, []);
            if (Sys.components[name].prototype.autoload) {
                new Sys[name]();
            }
            Sys.components[name].booted = true;
        }
    };

    Sys.component.bootComponents = function() {
        for (var component in Sys.components) {
            Sys.component.boot(component);
        }
    };

    /**
     * Check event
     * @return {void}
     */
    Sys.$doc.on('DOMContentLoaded.Sys', function(e) {
        Sys.component.bootComponents();
        Sys.domready = true;
        Sys.$doc.trigger($.Event('ready.Sys'));
    });

    /**
     * Check the exists of an element in an array
     *
     * @param {string} value  Value to check
     * @param {array}  array  Array to check
     *
     * @return {boolean}
     */
    Sys.fn.inArray = function(value, array) {
        for (var i = 0; i < array.length; i++) {
            if (array[i] == value)
                return true;
        }
        return false;
    };

    /**
     * Check the data is in JSON format
     * @param  {string} str String to check
     * @return {boolean}
     */
    Sys.fn.isJSON = function(str) {
        try {
            JSON.parse(str);
        } catch (e) {
            return false;
        }
        return true;
    };

    /**
     * Add value to the local storage
     * @param  {string} key   Key of local storage
     * @param  {string} value String to check
     * @return {void}
     */
    Sys.fn.set = function(key, value) {
        if (typeof value === 'object') {
            value = JSON.stringify(value);
        }
        localStorage.setItem(key, value);
    };

    /**
     * Get value from the local storage
     * @param  {string} key Key of local storage
     * @return {mixed}
     */
    Sys.fn.get = function(key) {
        if (typeof localStorage[key] !== 'undefined') {
            var value = localStorage.getItem(key);
            if (this.isJSON(value)) {
                value = JSON.parse(value);
            }
            return value;
        }
        return false;
    };

    /**
     * Update value from the local storage
     * @param  {string} key Key of local storage
     * @param  {object} obj Object to update
     * @return {void}
     */
    Sys.fn.update = function(key, obj) {
        if (typeof obj === 'object' && typeof this.get(key) === 'object') {
            this.set(key, $.extend(this.get(key), obj));
        }
    };

    /**
     * Get the url not query string
     * @return {string}
     */
    Sys.fn.url = function() {
        return location.href.split('?')[0];
    };


    /**
     * Get the parameter from query sring from url
     * @param  {string} param Parameter of url
     * @return {mixed}
     */
    Sys.fn.paramQString = function(key) {
        var results = new RegExp('[\\?&]' + key + '=([^&#]*)').exec(location.search);
        if (!results) {
            return false;
        }
        return results[1] || false;
    };

    /**
     * Update query string from url
     * @param  {string} url   Url string
     * @param  {string} key   Key to replace
     * @param  {string} value Value to replace
     * @return {string}
     */
    Sys.fn.updateQString = function(url, key, value) {
        var reg = new RegExp("([?|&])" + key + "=.*?(&|#|$)", "i"),
            up, separator;
        if (!url || url === '') {
            url = location.search;
        }
        if (url.match(reg)) {
            up = url.replace(reg, '$1' + key + "=" + value + '$2');
        } else {
            separator = url.indexOf('?') !== -1 ? "&" : "?";
            up = url + separator + key + "=" + value;
        }
        history.pushState(null, null, up);
        return up;
    };

    /**
     * Get the path url
     * @return {string}
     */
    Sys.fn.pathUrl = function() {
        var href = location.pathname;
        return href.substr(1, href.length - 2);
    };

    /**
     * Redirect to url
     * @return {void}
     */
    Sys.fn.redirect = function(url) {
        document.location.href = url;
    };

    /**
     * Generate a random string
     * @param {number} len Length characters
     * @return {string}
     */
    Sys.fn.random = function(len) {
        var rand = '',
            len = typeof len === 'undefined' ? 12 : len,
            possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        for (var i = 0; i < len; i++) {
            rand += possible.charAt(Math.floor(Math.random() * possible.length));
        }
        return rand;
    };

    /**
     * Clear the hash of url
     * @return {void}
     */
    Sys.fn.clearHash = function() {
        var doc = document,
            st = doc.body.scrollTop,
            sl = doc.body.scrollTop;
        doc.location.hash  = '';
        doc.body.scrollTop = st;
        doc.body.scrollTop = sl;
    };

    /**
     * Get meta data
     * @param {string} name Name of meta
     * @param {mixed}  def Default value
     * @return {mixed}
     */
    Sys.fn.meta = function(name, def) {
        var value = $('meta[name="' + name + '"]').attr('content');
        if (typeof value !== 'undefined' && value.length > 0) {
            return value;
        } else if (typeof def !== 'undefined') {
            return def;
        }
        return false;
    };

    /**
     * Get language key
     * @param {string} key Key of Language
     * @return {mixed}
     */
    Sys.fn.lang = function (key) {
        if (typeof Lang !== 'undefined' && Lang[key] !== 'undefined') {
            return Lang[key];
        }
        return false;
    };

    /**
     * Send ajax
     * @param {object} data     Data params to send
     * @param {object} options  Options of send
     * @param {string} url      Url of send
     * @return {void}
     */
    Sys.fn.request = function(data, options, url) {
        if (typeof url === 'undefined') {
            url = location.href;
        }
        var that = this, setup = $.extend({}, Sys.setup.request), request, ajax;
        if (typeof options === 'object') {
            $.extend(true, setup, options);
        } else if(typeof options === 'function') {
            setup.onSuccess = options;
        }

        setup.url = url;

        ajax = {
            type:      setup.type,
            url:       setup.url,
            dataType:  setup.dataType,
            async:     setup.async,
            data:      data,
            beforeSend: function(xhr) {
                if (typeof setup.onBefore === 'function') {
                    setup.onBefore(xhr)
                }
            },
            complete: function() {
                if (typeof setup.onComplete === 'function') {
                    setup.onComplete();
                }
            }
        };
        if (setup.formData) {
            $.extend(ajax, {
                contentType: false,
                processData: false
            });
        }
        request = $.ajax(ajax);
        request.done(function(res) {
            if (typeof res !== 'undefined' && typeof res.status !== 'undefined') {
                if (+res.status === 0) {
                    console.error("request: error");
                    typeof setup.onError === 'function' && setup.onError(res);
                } else if (+res.status === 1) {
                    console.info("request: success");
                    typeof setup.onSuccess === 'function' && setup.onSuccess(res);
                } else {
                    console.warn("request: undefined");
                    typeof setup.onUndefined === 'function' && setup.onUndefined(res);
                }
            } else if (res === null) {
                console.info("request: null");
            }
        });
        request.fail(function(res) {
            console.error("request: fail");
            typeof setup.onError === 'function' && setup.onError(res);
        });
        return request;
    };

    Sys.fn.trim = function (text) {
        return text.replace(/^\s+/, '').replace(/\s+$/, '');
    };

    win.Sys = Sys;
    return Sys;
})(window, jQuery, document);