$.generate_iban = ( options ) ->

  valid_options = (fields) ->
    for field in fields
      return false unless options[field] and options[field].length
    true

  return false unless valid_options(['country', 'bank_code', 'account_nr', 'iban'])

  timeout = null

  obj = {
    init : ->
      options.country.add(options.bank_code).add(options.account_nr).on('change', obj.request)
      obj.request()
    request: ->
      return false if timeout
      timeout = setTimeout(obj.calculate, 300)
    calculate : ->
      $.ajax
        url : options.url or Routing.generate( 'rodgermd.iban.generate' )
        data :
          country : options.country.val()
          bank_code : options.bank_code.val()
          account_nr : options.account_nr.val()
        dataType : 'json'
        success : ( r ) ->
          return false unless r.success
          options.iban.val(r.iban)
          options.bic.val(r.bic) if options.bic
        complete: ->
          clearTimeout timeout
          timeout = null

  }

  obj.init()