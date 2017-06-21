$(function(){
  var smtSwitch = {

    key: 'disable_smt',

    // default 30 day.
    datePeriod: 30,

    initialize: function() {
      // update expires.
      smtSwitch.updateExpires(false);

      $('#smt-switch').show();

      $('#smt-switch').on('click', function() {
        smtSwitch.switchPc();
      });

      $('a.close').on('click', function() {
        $(this).parent().hide();
      });
    },

    isSwitchPc: function() {
      return 1 === opCookie.get(this.key);
    },

    switchPc: function() {
      this.updateExpires(true);
      location.href = $(this).attr('href');
    },

    getExpiresDate: function() {
      var expiresDate = new Date();
      expiresDate.setTime(expiresDate.getTime() + this.datePeriod * 24 * 60 * 60 * 1000);

      return expiresDate;
    },

    updateExpires: function(force) {
      if (force || this.isSwitchPc()) {
        opCookie.set(this.key, '1', this.getExpiresDate(), openpne.baseUrl);
      }
    }
  };

  // initialize.
  smtSwitch.initialize();
});
