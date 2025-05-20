#go to do list
package main

import (
    "fmt"
    "log"
    "net/http"
    "sync"
)

var (
    tasks []string
    mutex sync.Mutex
)

func main() {
    http.HandleFunc("/", showTasks)
    http.HandleFunc("/add", addTask)

    fmt.Println("🚀 Running at: http://localhost:8080")
    log.Fatal(http.ListenAndServe(":8080", nil))
}

func showTasks(w http.ResponseWriter, r *http.Request) {
    mutex.Lock()
    defer mutex.Unlock()

    fmt.Fprintln(w, "<h2>📝 My To-Do List</h2>")
    fmt.Fprintln(w, "<ul>")
    for _, task := range tasks {
        fmt.Fprintf(w, "<li>%s</li>", task)
    }
    fmt.Fprintln(w, "</ul>")
    fmt.Fprintln(w, `
        <form action="/add" method="POST">
            <input name="task" placeholder="Enter task" required>
            <button type="submit">Add</button>
        </form>
    `)
}

func addTask(w http.ResponseWriter, r *http.Request) {
    if r.Method != http.MethodPost {
        http.Redirect(w, r, "/", http.StatusSeeOther)
        return
    }

    task := r.FormValue("task")
    if task != "" {
        mutex.Lock()
        tasks = append(tasks, task)
        mutex.Unlock()
    }

    http.Redirect(w, r, "/", http.StatusSeeOther)
}
