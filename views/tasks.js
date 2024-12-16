function setAction(actionValue) {
  const actionElements = document.getElementsByName("action");

  for (let i = 0; i < actionElements.length; i++) {
    actionElements[i].value = actionValue;
  }
}

function statusCheck() {
  const actionElements = document.getElementsByName("status");

  for (let i = 0; i < actionElements.length; i++) {
    if (actionElements[i].value == "completed") {
      actionElements[i].disabled = true;
    }
  }
}

flatpickr(".due_date", {
  dateFormat: "Y-m-d",
  altInput: false,
});

statusCheck();
