#include <iostream>
using namespace std;

class Student {
public:
    string name;
    int roll;
    float marks;

    void input() {
        cout << "Enter name: ";
        getline(cin, name);
        cout << "Enter roll number: ";
        cin >> roll;
        cout << "Enter marks: ";
        cin >> marks;
    }

    void display() {
        cout << "\nStudent Details:\n";
        cout << "Name: " << name << "\nRoll No: " << roll << "\nMarks: " << marks << endl;
    }
};

int main() {
    Student s;
    s.input();
    s.display();
    return 0;
}
