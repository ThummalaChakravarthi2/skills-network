#rusty todo
use std::collections::HashMap;
use std::io::{self, Write};

#[derive(Debug)]
struct Task {
    id: usize,
    description: String,
    completed: bool,
}

impl Task {
    fn new(id: usize, description: String) -> Task {
        Task {
            id,
            description,
            completed: false,
        }
    }
}

struct TodoApp {
    tasks: HashMap<usize, Task>,
    next_id: usize,
}

impl TodoApp {
    fn new() -> Self {
        TodoApp {
            tasks: HashMap::new(),
            next_id: 1,
        }
    }

    fn add_task(&mut self, desc: String) {
        let task = Task::new(self.next_id, desc);
        self.tasks.insert(self.next_id, task);
        println!("✅ Task {} added.", self.next_id);
        self.next_id += 1;
    }

    fn list_tasks(&self) {
        if self.tasks.is_empty() {
            println!("📭 No tasks yet!");
        } else {
            for task in self.tasks.values() {
                println!(
                    "[{}] {}: {}",
                    if task.completed { "x" } else { " " },
                    task.id,
                    task.description
                );
            }
        }
    }

    fn complete_task(&mut self, id: usize) {
        match self.tasks.get_mut(&id) {
            Some(task) => {
                task.completed = true;
                println!("🎉 Task {} marked as completed.", id);
            }
            None => println!("❌ Task not found."),
        }
    }

    fn delete_task(&mut self, id: usize) {
        if self.tasks.remove(&id).is_some() {
            println!("🗑️ Task {} deleted.", id);
        } else {
            println!("❌ Task not found.");
        }
    }
}

fn main() {
    let mut app = TodoApp::new();
    loop {
        println!("\n==== TO-DO APP ====");
        println!("1. Add Task");
        println!("2. List Tasks");
        println!("3. Complete Task");
        println!("4. Delete Task");
        println!("5. Exit");
        print!("Choose an option: ");
        io::stdout().flush().unwrap();

        let mut option = String::new();
        io::stdin().read_line(&mut option).unwrap();
        let option = option.trim();

        match option {
            "1" => {
                print!("Enter task description: ");
                io::stdout().flush().unwrap();
                let mut desc = String::new();
                io::stdin().read_line(&mut desc).unwrap();
                app.add_task(desc.trim().to_string());
            }
            "2" => app.list_tasks(),
            "3" => {
                print!("Enter task ID to complete: ");
                io::stdout().flush().unwrap();
                let mut id = String::new();
                io::stdin().read_line(&mut id).unwrap();
                if let Ok(id) = id.trim().parse() {
                    app.complete_task(id);
                } else {
                    println!("Invalid ID.");
                }
            }
            "4" => {
                print!("Enter task ID to delete: ");
                io::stdout().flush().unwrap();
                let mut id = String::new();
                io::stdin().read_line(&mut id).unwrap();
                if let Ok(id) = id.trim().parse() {
                    app.delete_task(id);
                } else {
                    println!("Invalid ID.");
                }
            }
            "5" => {
                println!("👋 Goodbye!");
                break;
            }
            _ => println!("❓ Unknown option."),
        }
    }
}
