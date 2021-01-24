$(document).ready(function () {
  $(document).on('DOMNodeInserted', '.modal#form-menu-modal', function () {
    const $iconInput = $('.form-group .form-control[name=icon]')
    const $parentIdInput = $('.form-group .form-control[name=parent_id]')

    function disableIcon() {
      $iconInput.attr('disabled', 'disabled')
      $iconInput.attr('required', null)
    }

    function enableIcon() {
      $iconInput.attr('disabled', null)
      $iconInput.attr('required', 'required')
    }

    $parentIdInput.val().length ? disableIcon() : enableIcon()

    $parentIdInput.keyup(function (e) {
      $parentIdInput.val().length ? disableIcon() : enableIcon()
    })
  })
})