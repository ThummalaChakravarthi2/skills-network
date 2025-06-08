const todoList = [];

function addTask(task) {
    todoList.push(task);
    console.log(`Task "${task}" added.`);
}

function displayTasks() {
    console.log("To-Do List:");
    todoList.forEach((task, index) => {
        console.log(`${index + 1}. ${task}`);
    });
}

// Sample usage:
addTask("Complete homework");
addTask("Read a book");
displayTasks();
