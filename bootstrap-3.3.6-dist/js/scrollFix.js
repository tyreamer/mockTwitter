$('.modal')
  .on('shown', function () {
    $('body').on('wheel.modal mousewheel.modal', function () {
      return false;
    });
  })
  .on('hidden', function () {
    $('body').off('wheel.modal mousewheel.modal');
  });
  