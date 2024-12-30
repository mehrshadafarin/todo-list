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
      
      // Change the color and background color when completed
      actionElements[i].style.backgroundColor = "#d3d3d3";  // Change background color
      actionElements[i].style.color = "#808080";            // Change text color
      actionElements[i].style.borderColor = "#808080";      // Change border color (if applicable)
    }
  }
}


flatpickr(".due_date", {
  dateFormat: "Y-m-d",
  altInput: false,
});

statusCheck();

function reinitializeTaskScripts() {
  flatpickr(".due_date", {
    dateFormat: "Y-m-d",
    altInput: false,
  });
  statusCheck();
}

function groupAndSortTasksByDate() {
  const tasksContainer = document.querySelector(".box"); // Container holding all tasks
  const tasks = Array.from(tasksContainer.querySelectorAll("form")); // Select form elements directly

  // Group tasks by their due date
  const groupedTasks = tasks.reduce((groups, taskForm) => {
    const dueDate = taskForm.querySelector(".due_date").value;
    if (!groups[dueDate]) groups[dueDate] = [];
    groups[dueDate].push(taskForm);
    return groups;
  }, {});

  // Clear the container
  tasksContainer.innerHTML = "";

  // Sort dates and create sections for each group
  Object.keys(groupedTasks)
    .sort((a, b) => new Date(a) - new Date(b)) // Sort dates in ascending order
    .forEach((date) => {
      // Create a heading for the date
      const dateHeading = document.createElement("h3");
      dateHeading.textContent = `Tasks for ${date}`;
      tasksContainer.appendChild(dateHeading);

      // Append tasks under the date heading
      groupedTasks[date].forEach((taskForm) => {
        tasksContainer.appendChild(taskForm); // Append the form element directly
      });
    });
}



// Automatically group and sort tasks when the page loads
document.addEventListener("DOMContentLoaded", () => {
  groupAndSortTasksByDate();
  reinitializeTaskScripts();
});
