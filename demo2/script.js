let tasks = [];

function addTask() {
  const taskInput = document.getElementById('taskInput');
  const taskText = taskInput.value.trim();
  
  if (taskText !== "") {
    tasks.push(taskText);
    renderTasks();
    taskInput.value = "";
  }
}

function deleteTask(index) {
  tasks.splice(index, 1);
  renderTasks();
}

function editTask(index) {
  const newTask = prompt("Edit your task:", tasks[index]);
  if (newTask && newTask.trim() !== "") {
    tasks[index] = newTask.trim();
    renderTasks();
  }
}

function renderTasks() {
  const taskList = document.getElementById('taskList');
  taskList.innerHTML = "";

  tasks.forEach((task, index) => {
    const li = document.createElement('li');
    
    const taskText = document.createElement('span');
    taskText.className = "text";
    taskText.textContent = task;

    const editBtn = document.createElement('button');
    editBtn.className = "edit-btn";
    editBtn.innerHTML = "✏️";
    editBtn.onclick = () => editTask(index);

    const deleteBtn = document.createElement('button');
    deleteBtn.className = "delete-btn";
    deleteBtn.innerHTML = "🗑️";
    deleteBtn.onclick = () => deleteTask(index);

    li.appendChild(taskText);
    li.appendChild(editBtn);
    li.appendChild(deleteBtn);
    taskList.appendChild(li);
  });
}
