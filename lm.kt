import java.util.Scanner

data class Book(
    val id: Int,
    val title: String,
    val author: String,
    var isBorrowed: Boolean = false
)

class Library {
    private val books = mutableListOf<Book>()
    private var nextId = 1

    fun addBook(title: String, author: String) {
        books.add(Book(nextId++, title, author))
        println("Book added successfully!")
    }

    fun listBooks() {
        if (books.isEmpty()) {
            println("No books in the library.")
            return
        }
        println("Books in the Library:")
        println("ID | Title | Author | Status")
        books.forEach {
            println("${it.id} | ${it.title} | ${it.author} | ${if (it.isBorrowed) "Borrowed" else "Available"}")
        }
    }

    fun searchBooks(query: String) {
        val results = books.filter { 
            it.title.contains(query, ignoreCase = true) || it.author.contains(query, ignoreCase = true) 
        }
        if (results.isEmpty()) {
            println("No books found matching \"$query\"")
        } else {
            println("Search Results:")
            results.forEach {
                println("${it.id} | ${it.title} | ${it.author} | ${if (it.isBorrowed) "Borrowed" else "Available"}")
            }
        }
    }

    fun borrowBook(id: Int) {
        val book = books.find { it.id == id }
        if (book == null) {
            println("Book with ID $id not found.")
            return
        }
        if (book.isBorrowed) {
            println("Sorry, '${book.title}' is already borrowed.")
        } else {
            book.isBorrowed = true
            println("You borrowed '${book.title}'. Enjoy reading!")
        }
    }

    fun returnBook(id: Int) {
        val book = books.find { it.id == id }
        if (book == null) {
            println("Book with ID $id not found.")
            return
        }
        if (!book.isBorrowed) {
            println("This book was not borrowed.")
        } else {
            book.isBorrowed = false
            println("Thank you for returning '${book.title}'.")
        }
    }
}

fun main() {
    val library = Library()
    val scanner = Scanner(System.`in`)

    println("Welcome to Kotlin Library Management System")

    loop@ while (true) {
        println(
            """
            |Choose an option:
            |1. Add Book
            |2. List Books
            |3. Search Books
            |4. Borrow Book
            |5. Return Book
            |6. Exit
        """.trimMargin()
        )
        print("Enter choice: ")
        when (scanner.nextLine().trim()) {
            "1" -> {
                print("Enter book title: ")
                val title = scanner.nextLine().trim()
                print("Enter book author: ")
                val author = scanner.nextLine().trim()
                if (title.isNotEmpty() && author.isNotEmpty()) {
                    library.addBook(title, author)
                } else {
                    println("Title and author cannot be empty.")
                }
            }
            "2" -> library.listBooks()
            "3" -> {
                print("Enter search query (title or author): ")
                val query = scanner.nextLine().trim()
                if (query.isNotEmpty()) {
                    library.searchBooks(query)
                } else {
                    println("Search query cannot be empty.")
                }
            }
            "4" -> {
                print("Enter book ID to borrow: ")
                val id = scanner.nextLine().toIntOrNull()
                if (id != null) {
                    library.borrowBook(id)
                } else {
                    println("Invalid book ID.")
                }
            }
            "5" -> {
                print("Enter book ID to return: ")
                val id = scanner.nextLine().toIntOrNull()
                if (id != null) {
                    library.returnBook(id)
                } else {
                    println("Invalid book ID.")
                }
            }
            "6" -> {
                println("Exiting... Goodbye!")
                break@loop
            }
            else -> println("Invalid choice. Try again.")
        }
        println()
    }
}
