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

function groupAndSortTasksByDate() {
  const tasksContainer = document.querySelector('.box'); // Container holding all tasks
  const tasks = Array.from(tasksContainer.querySelectorAll('.item')); // All task items

  // Group tasks by their due date
  const groupedTasks = tasks.reduce((groups, task) => {
      const dueDate = task.querySelector('.due_date').value;
      if (!groups[dueDate]) groups[dueDate] = [];
      groups[dueDate].push(task);
      return groups;
  }, {});

  // Clear the container
  tasksContainer.innerHTML = '';

  // Sort dates and create sections for each group
  Object.keys(groupedTasks)
      .sort((a, b) => new Date(a) - new Date(b)) // Sort dates in ascending order
      .forEach(date => {
          // Create a heading for the date
          const dateHeading = document.createElement('h3');
          dateHeading.textContent = `Tasks for ${date}`;
          tasksContainer.appendChild(dateHeading);

          // Append tasks under the date heading
          groupedTasks[date].forEach(task => tasksContainer.appendChild(task));
      });
}

// Automatically group and sort tasks when the page loads
document.addEventListener('DOMContentLoaded', () => {
  groupAndSortTasksByDate();
});
