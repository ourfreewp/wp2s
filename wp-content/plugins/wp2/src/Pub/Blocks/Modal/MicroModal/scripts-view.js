MicroModal.init({
  onShow: modal => console.info(`${modal.id} is shown`),
  onClose: modal => console.info(`${modal.id} is hidden`),
  openClass: 'is-open',
});