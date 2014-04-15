(function() {

  $.generate_iban = function(options) {
    var obj, timeout, valid_options;
    valid_options = function(fields) {
      var field, _i, _len;
      for (_i = 0, _len = fields.length; _i < _len; _i++) {
        field = fields[_i];
        if (!(options[field] && options[field].length)) {
          return false;
        }
      }
      return true;
    };
    if (!valid_options(['country', 'bank_code', 'account_nr', 'iban'])) {
      return false;
    }
    timeout = null;
    obj = {
      init: function() {
        options.country.add(options.bank_code).add(options.account_nr).on('change', obj.request);
        return obj.request();
      },
      request: function() {
        if (timeout) {
          return false;
        }
        return timeout = setTimeout(obj.calculate, 300);
      },
      calculate: function() {
        return $.ajax({
          url: options.url || Routing.generate('rodgermd.iban.generate'),
          data: {
            country: options.country.val(),
            bank_code: options.bank_code.val(),
            account_nr: options.account_nr.val()
          },
          dataType: 'json',
          success: function(r) {
            if (!r.success) {
              return false;
            }
            options.iban.val(r.iban);
            if (options.bic) {
              return options.bic.val(r.bic);
            }
          },
          complete: function() {
            clearTimeout(timeout);
            return timeout = null;
          }
        });
      }
    };
    return obj.init();
  };

}).call(this);