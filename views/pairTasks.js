function setAction(actionValue) {
  const actionElements = document.getElementsByName("action");

  for (let i = 0; i < actionElements.length; i++) {
    actionElements[i].value = actionValue;
  }
}
flatpickr(".due_date", {
  dateFormat: "Y-m-d",
  altInput: false,
});
